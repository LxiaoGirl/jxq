<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Payment_model extends CI_Model
{
    const payment_log = 'payment_log'; // 借款支付记录
    const payment     = 'borrow_payment'; // 投资还款记录
    const user        = 'user'; // 会员表
    const card        = 'user_card'; // 银行卡
    const flow        = 'cash_flow'; // 资金记录
    const borrow      = 'borrow'; // 借款记录
    const transfer_accounts      = 'transfer_accounts'; // 借款记录
    const repay      = 'borrow_repay_plan'; // 还款计划

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
    }

    /**
     * 支付记录
     *
     * @access public
     * @return boolean
     */

    public function pay_now()
    {
        $query = FALSE;
        $temp  = array();

        $temp['payment_no'] = $this->input->get('payment_no', TRUE);
        //$temp['card_no'] = $this->input->post('card_no', TRUE);
        $temp['status']  = (int)$this->input->post('status', TRUE);

        if( ! empty($temp['payment_no']))
        {
            $temp['where']   = array('where'  => array('payment_no' => $temp['payment_no']));
            $temp['payment'] = $this->c->get_row(self::payment_log, $temp['where']);
        }
			$temp['where'] = array('where' => array('uid' => $temp['payment']['uid']));
            $temp['usr'] = $this->c->get_row(self::user, $temp['where']);

		if( ! empty( $temp['payment']))
        {

            $temp['charge'] = (float)$this->input->post('charge');
			//三方支付(返回成功的人，成功的总金额)不返回完全成功，不走下面方法
            $sanfang = $this->_sanfang($temp['payment_no'],$temp['payment']['borrow_no'],$temp['payment']['status']);
            $this->db->trans_start();
			$temp['where'] = array(
                                    'select'   => 'uid,',
                                    'where'    => array('borrow_no' => $temp['payment']['borrow_no'], 'type' => "1"),
                                );
			$temp['data'] = $this->c->get_all(self::transfer_accounts, $temp['where']);

			if(!empty($temp['data'])){
				$temp['where'] = array(
										'select'   => 'uid,',
										'where'    => array('borrow_no' => $temp['payment']['borrow_no'], 'type' => "2"),
									);
				$temp['data'] = $this->c->get_all(self::transfer_accounts, $temp['where']);
				if(empty($temp['data'])){
					$temp['data'] = array(
										'card_no'     => $temp['usr']['vaccid'],//$temp['card_no'],
										'charge'      => $temp['charge'],//手续费暂时不处理
										'real_name'   => $temp['usr']['real_name'],//$temp['card']['real_name'],
										'bank_name'   => "",//$temp['card']['bank_name'],
										'account'     => "",//$temp['card']['account'],
										'operator'    => $this->session->userdata('admin_name'),
										'update_time' => time(),
										'status'      => $temp['status'],
									);
					$temp['where'] = array('where' => array('payment_no' => $temp['payment_no']));
					$this->c->update(self::payment_log, $temp['where'], $temp['data']);
					if( ! empty($temp['status']))
					{

							// 添加支付记录
							$query = $temp['balance'] = $this->_get_user_blance($temp['payment']['uid']);
							if( ! empty($query))
							{
								/* $temp['flow'] = array(
													'uid'      => $temp['payment']['uid'],
													'type'     => 6,
													'amount'   => round($temp['payment']['amount'], 2),
													'balance'  => $temp['balance']+round($temp['payment']['amount'], 2),
													'source'   => $temp['payment_no'],
													'remarks'  => '用户借款(手续费'.price_format($temp['charge']).')',
													'dateline' => time()
												); */
								//$query = $this->c->insert(self::flow, $temp['flow']);
								// if( ! empty($query))
								// {
									// 更新冻结金额改为投资金额
									$query =  $this->_set_flow_type($temp['payment']['borrow_no']);
									if( ! empty($query))
									{
										// 更新借款记录并发送通知
										$query =  $this->_set_borrow_info($temp['payment']['borrow_no']);

												$query =$this->_set_repay_plan($temp['payment']['borrow_no']);
												// 生成还款计划
									}
								//}
							}

							
					}
				}else{
					$temp['data'] = array(
										'card_no'     => $temp['usr']['vaccid'],//$temp['card_no'],
										'charge'      => $temp['charge'],//手续费暂时不处理
										'real_name'   => $temp['usr']['real_name'],//$temp['card']['real_name'],
										'bank_name'   => "",//$temp['card']['bank_name'],
										'account'     => "",//$temp['card']['account'],
										'operator'    => $this->session->userdata('admin_name'),
										'update_time' => time(),
										'status'      => "2",
									);
					$temp['where'] = array('where' => array('payment_no' => $temp['payment_no']));
					$this->c->update(self::payment_log, $temp['where'], $temp['data']);
					
				}

				$this->db->trans_complete();

				$query = $this->db->trans_status();
			}
        }

        if( ! empty($query) && ! empty($temp['status']))
        {
			
            $temp['subject'] = sprintf('您好，你申请编号：%s的标地已经“满标”。', $temp['payment']['borrow_no']);
            $temp['content'] = sprintf('您好，你申请编号：%s 的标地已经“满标”。我们会在48小时内支付到您的账户。请注意查收！如需帮助请拨打专属客户经理电话。', $temp['payment']['borrow_no']);

            $this->passport->send_message($temp['payment']['uid'], $temp['subject'], $temp['content']);
        }
    	//redirect('finance/payment', 'refresh');

      //  unset($temp);
       // return $query;
    }
	
    /**
     * 三方平台 操作
     *
     * @access public
     * @return array
     */

    private function _sanfang($payment_no = '',$borrow_no = '',$status = '')
    {

        if( ! empty($payment_no) && ! empty($borrow_no))
		{
			//获取到借款单号和本次付款单号，根据借款单号获取到所有的用户
			$temp['where'] = array(
									'select' => 'uid,months',
									'where'  => array('borrow_no' => $borrow_no)
									);
			$temp['borrow'] = $this->c->get_row(self::borrow, $temp['where']);
			$temp['where'] = array('where' => array('uid' =>$temp['borrow']['uid']));
			$temp['user_B'] = $this->c->get_row(self::user, $temp['where']);
			if($status=="0")
			{
				//获取借款人全部信息user_B
				if( ! empty($temp['borrow'])&&! empty($temp['user_B']))
				{
					$temp['where'] = array(
										'select'   => 'payment_no,borrow_no,uid,SUM(`amount`) AS `amount`',
										'where'    => array('borrow_no' => $borrow_no, 'type' => 1),
										'group_by' => 'uid'
									);
					$temp['data'] = $this->c->get_all(self::payment, $temp['where']);

					//获取所有投资人的信息
					 if( ! empty($temp['data']))
					{
						foreach ($temp['data'] as $k => $v)
						{
						   //接入三方支付，多对一进行转账
							$temp['where'] = array('where' => array('uid' => $v['uid']));
							$temp['user_r'] = $this->c->get_row(self::user, $temp['where']);						
							$MarketSerial = "R".$v['payment_no'];
							$PVaccId = $temp['user_r']['vaccid'];
							$PCustName = $temp['user_r']['real_name'];
							$RVaccId = $temp['user_B']['vaccid'];
							$RCustName = $temp['user_B']['real_name'];
							
							// $RVaccId = $temp['user_r']['vaccid'];
							// $RCustName = $temp['user_r']['real_name'];
							// $PVaccId = $temp['user_B']['vaccid'];
							// $PCustName = $temp['user_B']['real_name'];
							
							$TransferAmount = $v['amount']*100;
							$TransferCharge ="0";
							$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));
							$temp['transfer_accounts'] = $this->c->get_row(self::transfer_accounts, $temp['where']);
							if(empty($temp['transfer_accounts'])){
								$temp['transfer'] = array(
										'uid'      => $v['uid'],
										'type'     => "2",
										'amount'   => $v['amount'],
										'remarks'   => "未提交到三方",
										'payment_no'  => $v['payment_no'],
										'borrow_no'  => $v['borrow_no'],
										'dateline' => time()
									);
								$query = $this->c->insert(self::transfer_accounts, $temp['transfer']);
							}else{
								$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmount,$TransferCharge);
								//header("Content-type:text/html;charset=utf-8");
					            $zanghuchaxun = $this->pay->zanghuchaxun($MarketSerial, $temp['user_B']['firmid'],$temp['user_B']['real_name']);

								if($configData['ReturnInfo']['RtnCode']=="000000")
								{									
									$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));
						
									$temp['data'] = array(
										'type'     => "1",
										'remarks'   => "三方已处理"
									);
									$this->c->update(self::transfer_accounts, $temp['where'], $temp['data']);								
								}else{
									$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));

									$temp['data'] = array(
											'type'     => "2",
											'remarks'   => $configData['ReturnInfo']['RtnInfo'],
									);
									$this->c->update(self::transfer_accounts,$temp['where'],$temp['data']);
								}
							}
						}
					}               
				}
			}else{
				$temp['where'] = array(
										'select'   => 'uid,amount',
										'where'    => array('borrow_no' => $borrow_no, 'type' => 2),
									);
				$temp['data'] = $this->c->get_all(self::transfer_accounts, $temp['where']);
				foreach ($temp['data'] as $k => $v){
							$temp['where'] = array('where' => array('uid' => $v['uid']));
							$temp['user_r'] = $this->c->get_row(self::user, $temp['where']);						
							$MarketSerial = $v['payment_no'];
							$PVaccId = $temp['user_r']['vaccid'];
							$PCustName = $temp['user_r']['real_name'];
							$RVaccId = $temp['user_B']['vaccid'];
							$RCustName = $temp['user_B']['real_name'];
							$TransferAmount = $v['amount']*100;
							$TransferCharge ="0";
							$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));
							$temp['transfer_accounts'] = $this->c->get_row(self::transfer_accounts, $temp['where']);
							
							
							$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmount,$TransferCharge);
								if(! empty($configData['Transfer']['CurrentBalance']))
								{									
									$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));
						
									$temp['data'] = array(
										'type'     => "1",
										'remarks'   => "三方已处理"
									);
									$this->c->update(self::transfer_accounts, $temp['where'], $temp['data']);								
								}else{
									$temp['where'] = array('where' => array('payment_no' => $v['payment_no']));

									$temp['data'] = array(
											'type'     => "3",
											'remarks'   => $configData['ReturnInfo']['RtnInfo'],
									);
									$this->c->update(self::transfer_accounts,$temp['where'],$temp['data']);
								}
					
				}
				
			}
		}
		unset($temp);
    }
    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);
        $temp['productcategory']   = $this->input->get('productcategory', TRUE);

        $temp['where'] = array(
                            'select'   => join_field('id,payment_no,uid,borrow_no,amount,charge,bank_name,account,auditor,add_time,status', self::payment_log).','.join_field('user_name,real_name', self::user).','.join_field('productcategory',self::borrow),
                            'join'     => array(
                                            array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::payment_log).' = '.join_field('uid', self::user)
                                            ),
                                            array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment_log).' = '.join_field('borrow_no', self::borrow)
                                            )
                                        ),
                            'order_by' => join_field('id', self::payment_log).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'P') === 0) ? join_field('payment_no', self::payment_log) : join_field('real_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $temp['where']['where']=array();
        $temp['status']=isset($_GET['status'])?(int)$this->input->get('status'):0;

        $temp['where']['where']=array(join_field('status',self::payment_log)=>$temp['status']);

        if( ! empty($temp['productcategory'])){
            $temp['where']['where']=array_merge($temp['where']['where'],array(join_field('productcategory',self::borrow)=>$temp['productcategory']));
        }

        $data = $this->c->show_page(self::payment_log, $temp['where']);

        $data['status']=isset($_GET['status'])?(int)$this->input->get('status'):'';
        $data['productcategory_select']=isset($temp['productcategory'])?$temp['productcategory']:'';

        unset($temp);
        return $data;
    }

	
	/**
     * 获取借款明细
     *
     * @access public
     * @return array
     */

   /*  public function get_payment_info()
    {
        $data = $temp = array();

        $temp['payment_no'] = $this->input->get('payment_no', TRUE);

        if( ! empty($temp['payment_no']))
        {
            $temp['where'] = array(
                                'select' => join_field('*', self::payment_log).','.join_field('mobile,user_name,real_name,card_no', self::user),
                                'join'   => array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::payment_log).' = '.join_field('uid', self::user)
                                            ),
                                'where'  => array(
                                                join_field('payment_no', self::payment_log) => $temp['payment_no'],
                                                join_field('status', self::payment_log)     => 0
                                            )
                            );

            $data = $this->c->get_row(self::payment_log, $temp['where']);

            if( ! empty($data))
            {
                $data['card_list'] = $this->_get_user_card($data['uid']);
            }
        }

        unset($temp);
        return $data;
    }
	 */
	

	/**
     * 获取支付明细
     *
     * @access public
     * @return array
     */

    public function get_payment_info()
    {
        $data = $temp = array();

        $temp['payment_no'] = $this->input->get('payment_no', TRUE);

        if( ! empty($temp['payment_no']))
        {
            $temp['where'] = array(
                                'select' => join_field('*', self::payment_log).','.join_field('mobile,user_name,real_name,card_no', self::user),
                                'join'   => array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::payment_log).' = '.join_field('uid', self::user)
                                            ),
                                'where'  => array(
                                                join_field('payment_no', self::payment_log) => $temp['payment_no'],
                                                join_field('status', self::payment_log)     => 0
                                            )
                            );

            $data = $this->c->get_row(self::payment_log, $temp['where']);

            if( ! empty($data))
            {
                $data['card_list'] = $this->_get_user_card($data['uid']);
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取银行卡列表
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return array
     */

    private function _get_user_card($uid = 0)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select' => 'card_no,bank_name,account',
                                'where'  => array('uid' => (int)$uid)
                            );

            $data = $this->c->get_all(self::card, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取会员余额
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return float
     */

    private function _get_user_blance($uid = 0)
    {
        $balance = 0;
        $temp    = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => $uid),
                                'order_by' => 'id desc'
                            );

            $temp['balance'] = $this->c->get_one(self::flow, $temp['where']);

            if( ! empty($temp['balance']))
            {
                $balance = $temp['balance'];
            }
        }

        unset($temp);
        return $balance;
    }

    /**
     * 更新借款记录
     *
     * @access private
     * @param  string   $borrow_no 借款编号
     * @return boolean
     */

    public function _set_borrow_info($borrow_no = '')
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select' => 'uid,months',
                                'where'  => array('borrow_no' => $borrow_no)
                            );

            $temp['borrow'] = $this->c->get_row(self::borrow, $temp['where']);
			$temp['where'] = array('where' => array('uid' =>$temp['borrow']['uid']));
			$temp['user_B'] = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($temp['borrow']))
            {
                $temp['time'] = strtotime('+1 days');
                $temp['date'] = repayment_date($temp['time'], $temp['borrow']['months']);

                $temp['data'] = array(
                                    'is_interest'  => 1,
                                    'confirm_time' => $temp['time'],
                                    'deadline'     => $temp['date']['deadline'],
                                    'exp_date'     => $temp['date']['exp_date'],
                                    'status'       => 4
                                );

                $temp['where'] = array('where' => array('borrow_no' => $borrow_no));
                $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);
            }

            if( ! empty($query))
            {
                $temp['where'] = array(
                                    'select'   => 'uid,SUM(`amount`) AS `amount`',
                                    'where'    => array('borrow_no' => $borrow_no, 'type' => 1),
                                    'group_by' => 'uid'
                                );

                $temp['data'] = $this->c->get_all(self::payment, $temp['where']);
                if( ! empty($temp['data']))
                {
                    foreach ($temp['data'] as $k => $v)
                    {
						$temp['where'] = array('where' => array('uid' => $v['uid']));
						$temp['user_r'] = $this->c->get_row(self::user, $temp['where']);

                        $temp['subject'] = sprintf('您好，您的投资已经生效(项目编号：%s)！', $borrow_no);
						$temp['content'] = sprintf("尊敬的（%s），您标的编号%s的投资已于%s通过终审，开始还款，期限%s个月，每月%s日为还息日。感谢您的支付。您的投资协议已经生效，查看【%s】和【%s】。",
								$temp['user_r']['user_name'],
								'<a href="'.$this->config->item('application_domain').'/index.php/borrow/detail?borrow_no='.$borrow_no.'" target="_blank">'.$borrow_no.'</a>',
								date('Y-m-d H:i:s', time()),
								$temp['borrow']['months'],
								date('d', time()),
								'<a href="'.$this->config->item('application_domain').'/index.php/terms?borrow_no=' . $borrow_no . '" target="_blank">委托借款协议</a>',
								'<a href="'.$this->config->item('application_domain').'/index.php/terms/claims?borrow_no=' . $borrow_no . '" target="_blank">债权转让协议</a>'
							);

                        $this->passport->send_message($v['uid'], $temp['subject'], $temp['content']);
						/* $content = sprintf("尊敬的（%s），您标的编号%s的投资已于%s通过终审，开始还款，期限%s个月。感谢您的支付。您的投资协议已经生效",
								$temp['user_r']['user_name'],
								$borrow_no,
								date('Y-m-d H:i:s', time()),
								$temp['borrow']['months'],
							); */
								//发送短信
						                        $temp['subject'] = sprintf('您标的%s的投资已于%s通过终审，开始开始计息，期限%s个月', $temp['borrow']['subject'],date('Y-m-d H:i:s', time()),$temp['borrow']['months']);
			    $data = $this->file_get_contents_post("http://61.156.157.209:8889/api.php", array('username'=>'cqdx008', 'password'=>'cq008', 'action'=>'send', 'receive_number'=>$temp['user_r']['mobile'], 'message_content'=> "【聚雪球】".$temp['subject'], 'split_type'=>'1'));
						
						if(! empty($temp['user_r']['email']))
						{
                            $this->config->load('email');//wsb-2015.5.12 修改
							$temp['send']  = array(
												'from'    => $this->config->item('smtp_user'),//wsb-2015.5.12 修改
												'name'    => '网加金服客服',
												'to'      => $temp['user_r']['email'],
												'subject' => $temp['subject'],
												'message' => $temp['content']
											);

							$this->c->send_mail($temp['send']);
						}
                    }
                }
            }
        }

        unset($temp);
        return $query;
    }
	  /**
     * 链接方式
     * 5.15
     * @access public
     * @return float
     */
	public function file_get_contents_post($url, $post) {  
		$options = array(  
			'http' => array(  
				'method' => 'POST',  
				// 'content' => 'name=caiknife&email=caiknife@gmail.com',  
				'content' => http_build_query($post),  
			),  
		);  
		$result = file_get_contents($url, false, stream_context_create($options));  
		return $result;  
	}

    /**
     * 更新冻结金额改为投资金额
     *
     * @access private
     * @param  string   $borrow_no 借款编号
     * @return boolean
     */

    public function _set_flow_type($borrow_no = '')
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select' => 'payment_no',
                                'where'  => array('borrow_no' => $borrow_no, 'type' => 1)
                            );

            $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                $temp['payment_no'] = array();

                foreach($temp['data'] as $v)
                {
                    $temp['payment_no'][] = $v['payment_no'];
                }

                $temp['data']  = array('type' => 5);

                $temp['where'] = array(
                                    'where'    => array('type'  => 3),
                                    'where_in' => array('field' => 'source', 'value' => $temp['payment_no'])
                                );
                $query = $this->c->update(self::flow, $temp['where'], $temp['data']);
            }
        }

        unset($temp);
        return $query;
    }
	public function _set_repay_plan($borrow_no){
        $query=FALSE;
        $temp=array();

        if( ! empty($borrow_no)){
            //查询该单号数据是否存在
            $temp['where']=array(
                'select'=>'mode,due_date,confirm_time,months,repay,amount,rate,deduct',
                'where'=>array('borrow_no'=>$borrow_no,'status'=>4)
            );
            $temp['borrow_info']=$this->c->get_row(self::borrow,$temp['where']);
			$temp['borrow_info']['confirm_time'] = $temp['borrow_info']['confirm_time']-24*60*60;
			
            if( ! empty($temp['borrow_info'])){
                //查询是否已存在计划列表
                $temp['where']=array(
                    'where'=>array('borrow_no'=>$borrow_no)
                );
                $temp['plan_exist']=$this->c->count(self::repay,$temp['where']);

               // $temp['borrow_info']['mode']=1;

                if(empty($temp['plan_exist'])){
                    $this->load->model('cron/repayment_model','repay');//加载cron下的repayment model 调用各种借款方式的还款计划
                    $temp['plan_data']=$this->repay->get_borrow_plan($temp['borrow_info']['mode'], $temp['borrow_info']['amount'], $temp['borrow_info']['rate'], $temp['borrow_info']['months']);

                    if( ! empty($temp['plan_data']))$temp['plan_date']=$this->repay->_get_repayment_date($temp['borrow_info']['confirm_time'],$temp['borrow_info']['months'],$temp['borrow_info']['mode'],$temp['borrow_info']['repay']);// 计划时间

                    //数据和时间都有
                    if( ! empty($temp['plan_date'])){
                        $temp['plan_data_count']=count($temp['plan_data']);

                        $temp['data']['borrow_no']=$borrow_no;
                        $temp['data']['amount']=$temp['borrow_info']['amount'];
                        $temp['data']['months']=$temp['borrow_info']['months'];
                        $temp['data']['rate']=$temp['borrow_info']['rate'];
                        $temp['data']['mode']=$temp['borrow_info']['mode'];
                        $temp['data']['repay']=$temp['borrow_info']['repay'];
                        $temp['data']['confirm_time']=$temp['borrow_info']['confirm_time'];
                        $temp['data']['dateline']=time();

                        foreach($temp['plan_data'] as $k=>$v){ //data和date的k都是从1开始的
                            if($temp['borrow_info']['mode'] == 3 && $k == $temp['plan_data_count']){ //一次性还款付息 最后一起还本金的时间
                               $temp['data']['repay_date']=$temp['plan_date'][1];
                                $temp['data']['repay_index']=$k-1;
                                $temp['data']['repay_type']=2;
                            }elseif($temp['borrow_info']['mode'] == 1 && $k == $temp['plan_data_count']){ //先息后本 最后一起还本金的时间
                                $temp['data']['repay_date']=$temp['plan_date'][$k-1];
                                $temp['data']['repay_index']=$k-1;
                                $temp['data']['repay_type']=2;
                            }else{
                                $temp['data']['repay_date']=$temp['plan_date'][$k];
                                $temp['data']['repay_index']=$k;
                                $temp['data']['repay_type']=((($temp['borrow_info']['mode'] == 1) || ($temp['borrow_info']['mode'] == 3))?1:3);
                            }

                            $temp['data']['repay_amount']=$v['amount'];
                            $temp['data']['repay_principal']=$v['principal'];
                            $temp['data']['repay_interest']=$v['interest'];
                            $temp['data']['repay_surplus_principal']=$v['surplus_principal'];
                            $temp['data']['rapay_time']=($temp['borrow_info']['deduct'] > $k?time():0);
                            $temp['data']['status']=($temp['borrow_info']['deduct'] > $k?3:0);

                            $temp['insert_data'][]=$temp['data'];
                        }
													var_dump($temp['insert_data']);

                        $query=$this->c->insert(self::repay,$temp['insert_data']);
                    }
                }
            }
        }
        unset($temp);
        return $query;
    }
}