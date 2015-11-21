<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付利息（发放给投资人，日息）
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */
class Interest_model extends CI_Model
{
    const borrow  = 'borrow'; // 交易记录
    const payment = 'borrow_payment'; // 支付记录
	const repay_plan = 'borrow_repay_plan'; // 支付记录
    const flow    = 'cash_flow'; // 资金记录
    const user    = 'user'; // 资金记录
    const payment_accounts    = 'payment_accounts'; // 资金记录

    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron/repayment_model', 'repayment');
        $this->load->model('send_model', 'send');
		$this->load->library('pay');

    }
    /**
     * 发放利息
     *
     * @access public
     * @return boolean
     */

    public function processing($borrow_no='')
    {
        $query = TRUE;
        $temp = array();
		//获取三方用余额，对真实金额与应付金额进行对比
		
		//var_dump(11);
		//获取借款人信息
        $borrow_user_amount = $this->_get_borrow_user($borrow_no);
		$temp['where'] = array('where' => array('uid' => $borrow_user_amount['uid']));
        $borrow_user = $this->c->get_row(self::user, $temp['where']);
		$borrow_user['balance'] = $this->_get_user_balance($borrow_user['uid']);
		$borrow_user = array_merge($borrow_user, $borrow_user_amount);  
		
		
		$MarketSerial="R".date('YmdHis').$i;
		//$configData = $this->pay->zanghuchaxun($MarketSerial,$borrow_user['firmid'],$borrow_user['real_name']);
		$borrow_user['CurrentBalance'] = $configData['Transfer']['CurrentBalance'];
		//$borrow_user['CurrentBalance'] ="50000000";
		$borrow_user['TransferLimit'] = $configData['Transfer']['TransferLimit'];
		//$borrow_user['balance'] ="50000000";
		//计算提交还款时候给的利息
		$Date_1=time();
		$Date_2=(int)$borrow_user['confirm_time'];
		$Days=round(($Date_1-$Date_2)/3600/24)+1;
		
		// $start = date('Ymd',$borrow_user['confirm_time']);
		// $end = date('Ymd');
		// $Days = $end-$start+1;
			// var_dump($end);
				// var_dump($end);
				// var_dump($Days);
				
		
		//$Days = 27;   //一次性本息的27天的利息计算
		//echo "今天与2008年10月11日相差".$Days."天";
		$borrow_user['interest'] =  round($borrow_user['amount']*$Days/30*$borrow_user['rate']/100/12,2);
		// if($borrow_user['CurrentBalance']>($borrow_user['interest']+$borrow_user['amount'])){
		// if($borrow_user['balance']+0>($borrow_user['interest']+$borrow_user['amount'])){
		// 获取需要处理的记录
			//$temp['borrow_list'] = $this->_get_invester_list($borrow_no);
	    // 获取已还款记录
		
			$temp['where'] = array('where' => array('borrow_no' => $borrow_no,'type' => '3'));
			$temp['borrow_end_list'] = $this->c->get_all(self::payment, $temp['where']);	
			//var_dump($temp['borrow_end_list']);			
			            foreach ($temp['borrow_end_list'] as $k => $v){
							$t[$k] = $v['payment_no'];							
						}
						//var_dump(1111111111);
						//var_dump($t);
						//var_dump(1111111111);
				$temp['where'] = array('where' => array('borrow_no' => $borrow_no,'type' => '1'));
				$temp['borrow_start_list'] = $this->c->get_all(self::payment, $temp['where']);
				foreach ($temp['borrow_start_list'] as $k => $v){
					$s[$k] = $v['payment_no'];
				}
				//var_dump($s);
			if (!empty($t)){
				$diff = array_diff($s,$t);
				if (!empty($diff)){
					foreach ($diff as $k => $v){
						$temp['where'] = array('where' => array('payment_no' =>  $v,'type' => '1'));
						$temp['_borrow_list'] = $this->c->get_all(self::payment, $temp['where']);	
						$temp['borrow_list'][$k] = $temp['_borrow_list'];
					}
				}
			}else{
				foreach ($s as $k => $v){
						$temp['where'] = array('where' => array('payment_no' =>  $v,'type' => '1'));
						$temp['_borrow_list'] = $this->c->get_all(self::payment, $temp['where']);	
						$temp['borrow_list'][$k] = $temp['_borrow_list'];
				}				
			}
			//var_dump($temp['borrow_list']);

			//var_dump($s);			
			

			//var_dump($diff );
			//var_dump($temp['borrow_list']);

        // 做比较 用需要还款的，和已还款的差集 (差集需保留能区别的支付号)
        
			
        if (!empty($temp['borrow_list'])){
            $query = TRUE;
            $temp['data'] = $temp['flow'] = $temp['interest'] = array();
			//var_dump($temp['borrow_list']);
            foreach ($temp['borrow_list'] as $k => $v){
				$v = $v[0];
				
				//三方预留转账接口
				
                
				
				$temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');
				$MarketSerial = $temp['payment_no'] ;
				//var_dump($MarketSerial);

				$temp['where'] = array('where' => array('uid' => $v['uid']));
				$temp['user_r'] = $this->c->get_row(self::user, $temp['where']);			
				//var_dump($temp['user_r']['mobile']);			

				//计算投资人应得收益
				// $start = date('Ymd',$borrow_user['confirm_time']);
				// $end = date('Ymd');
				// $Days = $end-$start+1;
				$Date_1=time();
				$Date_2=(int)$borrow_user['confirm_time'];
				$Days=round(($Date_1-$Date_2)/3600/24)+1;
		
				//var_dump($end);
				//var_dump($end);
				//var_dump($Days);
				//$Days = 27;   //一次性本息的27天的利息计算
				//echo "今天与2008年10月11日相差".$Days."天";
				$temp['interest'] =  round($v['amount']*$Days/30*$v['rate']/100/12,3);
				  
				$arr ="";
				$arr = explode(".",$temp['interest']);
                $a = substr($arr[1],0,2);
				$number = empty($a) ? '00' :  $a;
				$temp['interest']  = $arr[0].'.'.$number;
	
				//$temp['sum'] +=$temp['interest'];

				$TransferAmount = round(($v['amount'] + $temp['interest'])/100*100, 3); 
				$temp['sum1'] += $TransferAmount;
				$TransferAmounta = $TransferAmount*100;
								//var_dump($TransferAmounta);

				$PVaccId = $borrow_user['vaccid'];
				$PCustName = $borrow_user['real_name'];
				$RVaccId = $temp['user_r']['vaccid'];
				$RCustName = $temp['user_r']['real_name'];				
				$TransferCharge = "0";
				$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);
				if($configData['ReturnInfo']['RtnCode']=="000000")
				{			
				//$temp['pay_day'] = $this->repayment->_get_repayment_date($v['confirm_time'], $v['months'], $v['mode'], $v['repay']);//新增
                //$temp['month']=array_search($v['exp_date'],$temp['pay_day']);
				//var_dump($temp['month']);

                //$temp['end_day'] = end($temp['pay_pay']); //还款结束时间
                $temp['balance'] = $this->_get_user_balance($v['uid']); // 获取用户余额
                //$temp['interest'] = $this->repayment->_get_borrow_interest($v['mode'],$v['amount'],$v['rate'],$v['months'],$v['repay'],$temp['month']); // 计算利息（日息）
				//var_dump($v['rate']);				
				//计算提交还款时候给的利息
				


                //开始支付日息
                //$temp['balance'] = round($temp['balance'] + $temp['interest']*(1-item('charge_rate')), 2); //余额+利息-手续费
				//var_dump($temp['balance']);
				
				
				
				$this->db->trans_start();
//				$temp['today'] = date('Ymd'); //现在时间		
				
//				添加三方支付信息
			//var_dump($temp['balance']+$TransferAmount);
			//var_dump($temp['balance']);
			//var_dump($TransferAmount);
				
                //支付数据（日息数据)
                $temp['data'] = array(
                    'payment_no' => $v['payment_no'],
                    'uid' => $v['uid'],
                    'type' => 3,
                    'borrow_no' => $v['borrow_no'],
                    'rate' => $v['rate'],
                    'amount' => $TransferAmount,
                    'balance' => $temp['balance']+$TransferAmount,
                    'charge' => 0,
                    'pay_date' => date('Ymd'),
                    'dateline' => time(),
                    'pay_time' => time(),
                    'status' => 1
                );

                $this->c->insert(self::payment, $temp['data']); //插入支付记录

                //现金流（日息数据）
                $temp['flow'] = array(
                    'uid' => $v['uid'],
                    'type' => 7,
                    'amount' => $TransferAmount,
                    'balance' => $temp['balance']+$TransferAmount,
                    'source' => $temp['payment_no'],
                    'remarks' => '一次性本息收益',
                    'dateline' => time()
                );

                $this->c->insert(self::flow, $temp['flow']);

                //最后一次，则还款本金(除了等额本息 等额本金)
                // if ($v['exp_date'] == $temp['end_day'] && $v['mode'] != 2 && $v['mode'] != 4) {
                    // $temp['balance'] = round($temp['balance'] + $v['amount'], 2);
                    // $temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');

                    // $temp['data'] = array(
                        // 'payment_no' => $temp['payment_no'],
                        // 'uid' => $v['uid'],
                        // 'type' => 3,
                        // 'borrow_no' => $v['borrow_no'],
                        // 'rate' => $v['rate'],
                        // 'amount' => $v['amount'],
                        // 'balance' => $temp['balance'],
                        // 'charge' => 0,
                        // 'pay_date' => $v['exp_date'],//date('Ymd'),
                        // 'dateline' => time(),
                        // 'pay_time' => time(),
                        // 'status' => 1
                    // );

                    // $this->c->insert(self::payment, $temp['data']);

                    // $temp['flow'] = array(
                        // 'uid' => $v['uid'],
                        // 'type' => 9,
                        // 'amount' => $v['amount'],
                        // 'balance' => $temp['balance'],
                        // 'source' => $temp['payment_no'],
                        // 'remarks' => '偿还本金',
                        // 'dateline' => time()
                    // );

                    // $this->c->insert(self::flow, $temp['flow']);
                // }

                $this->db->trans_complete();
				//还款给投资人短信接口预留

				//$temp['subject'] = '尊敬的客户,您好!您在聚雪球平台投资的车贷宝一号已还款,本金和利息已转到您的平台账户里,请及时登录查询,如需帮助请拨打客服热线4007-918-333';
			    //$data = $this->file_get_contents_post("http://61.156.157.209:8889/api.php", array('username'=>'cqdx008', 'password'=>'cq008', 'action'=>'send', 'receive_number'=>$temp['user_r']['mobile'], 'message_content'=> "【聚雪球】".$temp['subject']));
				$temp['where'] = array(
					'where' => array('is_interest' => 1, "exp_date" => $v['exp_date']),//date('Ymd')
				);
				$temp['data'] = array(
					"is_interest" => 2
				);
				$this->c->set(self::borrow, $temp['where'], $temp['data']);									
				}
			}
            //更新利息状态
		}
		
		
		
			$temp['where'] = array('where' => array('borrow_no' => $borrow_no,'type' => '3'));
			$temp['borrow_end_list'] = $this->c->get_all(self::payment, $temp['where']);	
			//var_dump($temp['borrow_end_list']);			
			            foreach ($temp['borrow_end_list'] as $k => $v){
							$t[$k] = $v['payment_no'];							
						}
						//var_dump(1111111111);
						//var_dump($t);
						//var_dump(1111111111);
				$temp['where'] = array('where' => array('borrow_no' => $borrow_no,'type' => '1'));
				$temp['borrow_start_list'] = $this->c->get_all(self::payment, $temp['where']);
				foreach ($temp['borrow_start_list'] as $k => $v){
					$s[$k] = $v['payment_no'];
				}
				//var_dump($s);
			if (!empty($t)){
				$diff = array_diff($s,$t);
				if (!empty($diff)){
				}else{
						$temp['where'] = array(
							'where' => array('borrow_no' => $borrow_no),//date('Ymd')
						);
						$temp['data'] = array(
							"status" => 7
						);
						$this->c->set(self::borrow, $temp['where'], $temp['data']);
						
						
						$temp['where'] = array(
							'where' => array('borrow_no' => $borrow_no),//date('Ymd')
						);
						$temp['data'] = array(
							"status" => 7
						);
						$this->c->set(self::borrow, $temp['where'], $temp['data']);
						
						$temp['where'] = array(
                                'where'  => array('borrow_no' => $borrow_no)
                        );
						$borrowsub = $this->c->get_row(self::borrow,$temp['where']);
						//var_dump($borrowsub);						
						$temp['where'] = array(
                                'select' => 'uid',
                                'where'  => array('borrow_no' => $borrow_no,'type' => '3')
                        );
						$payment = $this->c->get_all(self::payment,$temp['where']);
						
						//处理投资人充值记录
						//*************************************************************//
						$temp['data'] = "";
						$temp['data'] = array(
											'uid'      => $borrow_user['uid'],
											'type'     => 10,
											'amount'   => $borrow_user['interest']+$borrow_user['amount'],
											'balance'  => round($borrow_user['balance']-$borrow_user['interest']-$borrow_user['amount'], 2),
											'source'   => $borrow_no,
											'remarks'  => "借款人还款",
											'dateline' => time(),
										);

						$query = $this->c->insert(self::flow, $temp['data']);
						//*************************************************************//
						
						foreach ($payment as $k => $v)	
						{
							//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
							$r[$v['uid']]=$v['uid'];
						}
						
						foreach ($r as $v)			
						{
							$temp['where'] = array(
											'select' => 'mobile,real_name,gender',
											'where'  => array('uid' => $v)
										);
							$user = $this->c->get_all(self::user,$temp['where']);
							
							$content="尊敬的客户，您好！您在聚雪球平台投资的".$borrowsub['subject']."现已还款,本金和利息已转到您的平台账户里，请及时登录查询，感谢您选择聚雪球平台投资，您可以继续投资或申请提现，如需帮助请拨打客服热线4007-918-333";
							$mobile = $user[0]['mobile'];
							$res1 = $this->send->send_sms_jieri($mobile,$content);
						}
				}
			}
		
		
		// }else{
			// echo "用户聚雪球平台余额不足";
		// }
		// }else{
			// echo "三方资金未到账,在途资金为：".$borrow_user['TransferLimit']/100;
		// }
		//redirect('cron/repayment/detail?borrow_no='.$borrow_no, 'refresh');

        unset($temp);
        return $query;
    }
	
	
	 /**
     * 获取借款人信息
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return array
     */

    private function _get_borrow_user($borrow_no = 0)
    {
            $temp['where'] = array(
            'select' => join_field('borrow_no,uid,amount,mode,type,repay,months,rate,confirm_time,deadline,exp_date,deduct',self::borrow).','.join_field('real_name,mobile,firmid,vaccid',self::user),
            'where' => array(join_field('status',self::borrow) => 4),
            'join'=>array('table'=>self::user,'where'=>join_field('uid',self::user).'='.join_field('uid',self::borrow),),
			//'between' => '`repay_date` BETWEEN \'' . $temp['start'] . '\' AND \'' . $temp['end'] . '\''
			);//wsb-2015.5.16 新增 join 查询 用户姓名电话

        $temp['where']['where']['borrow_no']=$borrow_no;
        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);
		$temp['data'] = $temp['data'][0];
		return $temp['data'];
    }
	

    /**
     * 计算利息（日息）
     *
     * @access private
     * @param  float $amount 借款金额
     * @param  float $rate 借款利率
     * @return float
//     */
//
//    private function _get_borrow_interest($amount = 0, $rate = 0)
//    {
//        return round($amount * ($rate / 100) / 365 , 2);
//    }
//
//    /**
//     * 获取还款日
//     *
//     * @access private
//     * @param  integer $confirm_time 确认时间
//     * @param  integer $deadline 还款截止时间
//     * @param  integer $months 还款期数
//     * @return integer
//     */
//
//    private function _get_repayment_date($confirm_time = 0, $months = 0)
//    {
//
//        $day = date('j', $confirm_time); //发布日天数
//        $month = date('n', $confirm_time); //发布日月数
//        $year = date('Y', $confirm_time); //发布日年数
//
//        //如果大于28号(29, 30, 31)
//        if ($day > 28) {
//            $lastDay = date('t', mktime(0, 0, 0, $month + $months, 1, $year));
//
//            if ($day < $lastDay) {
//                $iDay = date('Ym' . $day, mktime(0, 0, 0, $month + $months, 1, $year));
//            } else {
//                $iDay = date('Ymt', mktime(0, 0, 0, $month + $months, 1, $year));
//            }
//
//        } else {
//            $iDay = date('Ymd', mktime(0, 0, 0, $month + $months, $day, $year));
//        }
//
//        return $iDay;
//    }

    /**
     * 获取用户余额
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return array
     */

    private function _get_user_balance($uid = 0)
    {
        $balance = 0;
        $temp = array();

        if (!empty($uid)) {
            $temp['where'] = array(
                'select' => 'balance',
                'where' => array('uid' => (int)$uid),
                'order_by' => 'id desc'
            );

            $balance = $this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取需要处理的记录
     *
     * @access private
     * @return array
     */

    private function _get_invester_list($borrow_no='')
    {
        $data = $temp = array();

        $temp['where'] = array(
            'select' => join_field('uid,borrow_no,rate,amount', self::payment) . ',' .
                join_field('months,confirm_time,deadline,exp_date,mode,repay', self::borrow). ',' .join_field('amount', self::borrow).' as borrow_amount',
            'join' => array(
                'table' => self::borrow,
                'where' => join_field('borrow_no', self::payment) . ' = ' . join_field('borrow_no', self::borrow)
            ),
            'where' => array(
                join_field('type', self::payment) => 1, //投资记录
                join_field('exp_date', self::borrow) . ' <= ' => date('Ymd'), //还款中
                join_field('is_interest	', self::borrow) => 1 //支付利息中
            )
        );
        //2015.5.19 新增
        if( ! empty($borrow_no)){
            $temp['where']['where'][join_field('borrow_no	', self::borrow)]=$borrow_no;
        }

        //查询所有投资记录
        $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

        //去除已经支付过利息的用户
        if (!empty($temp['data'])) {
            foreach ($temp['data'] as $k => $v) {
                //获取支付记录
                $temp['exist'] = $this->_get_payment_list($v['uid'], $v['borrow_no'], $v['exp_date']);//新增$v['exp_date']

                if (!empty($temp['exist'])) {
                    unset($temp['data'][$k]);
                }
            }

            $data = $temp['data'];
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取支付记录（日息）
     *
     * @access private
     * @param  integer 　$uid 　　　会员ID
     * @param  string $borrow_no 借款编号
     * @return boolean
     */

    private function _get_payment_list($uid = 0, $borrow_no = '',$date='')
    {
        $query = FALSE;
        $temp = array();

        if (!empty($borrow_no)) {

//            $today = date('Ymd');

            $temp['where'] = array(
                'select' => 'uid,borrow_no',
                'where' => array('uid' => $uid, 'type' => 3, 'borrow_no' => $borrow_no, 'pay_date' => $date), //3 利息支付记
            );

            $temp['count'] = $this->c->count(self::payment, $temp['where']);

            $query = (!empty($temp['count'])) ? TRUE : FALSE;
        }

        unset($temp);
        return $query;
    }

    /***************************2015.5.18**********************/


    /**
     *  获得特定借款号的投资人投资记录
     * @param array $borrow
     */
    public function get_interest_list($borrow=array()){
        $query=$temp=array();

        if( ! empty($borrow)){

            $temp['where'] = array(
                'select' => join_field('user_name,real_name,mobile,firmid', self::user) . ',' .
                    join_field('uid,payment_no,amount,borrow_no,pay_time,pay_date', self::payment).','.
                    join_field('amount', self::borrow).' as borrow_amount'.','.join_field('months,mode,repay,rate,exp_date', self::borrow),
                'join' => array(
                    array(
                        'table' => self::user,
                        'where' => join_field('uid', self::payment) . ' = ' . join_field('uid', self::user)
                    ),
                    array(
                        'table' => self::borrow,
                        'where' => join_field('borrow_no', self::payment) . ' = ' . join_field('borrow_no', self::borrow)
                    )
                ),
                'where' => array(
                    join_field('type', self::payment) => 1, //投资记录
//                    join_field('exp_date', self::borrow) . ' >= ' => date('Ymd'), //还款中
//                    join_field('is_interest	', self::borrow) => 1 //支付利息中
                ),
                'where_in'=>array(
                    'field'=>join_field('borrow_no', self::payment),
                    'value'=>$borrow
                )
            );
			$temp['data'] = $this->c->get_all(self::payment, $temp['where']);

			
			
			
			$temp['where'] = array(
                                'select'   => 'repay_date,dateline,repay_index,confirm_time,rapay_time',
                                'where'    => array('borrow_no' => $borrow),
                                'order_by' => 'plan_id desc'
                            );
			$data = $this->c->get_row(self::repay_plan, $temp['where']);
			if(empty($data['rapay_time'])||($data['rapay_time'] ==0)){
				$data['rapay_time'] = time();
			}else{
				$data['rapay_time']=$data['rapay_time'];
			}

            //查询所有投资记录
			//var_dump($temp['data']);

            //去除已经支付过利息的用户
            if (!empty($temp['data'])) {
                foreach ($temp['data'] as $k => $v){
                    //获取支付记录
                    
					$temp['where'] = array(
                                'select'   => 'pay_date',
                                'where'    => array('payment_no' => $v['payment_no']),
                                'order_by' => 'id desc'
                            );
					$pay_date = $this->c->get_row(self::payment, $temp['where']);
					//var_dump($pay_date);
                    $v['is_pay']=0;
					//var_dump($pay_date['pay_date']);
                    if ($pay_date['pay_date']=="0") {
                        $v['is_pay']=0;
                    }else{
                        $v['is_pay']=1;
					}
                    $v['interest_bili']=round($v['amount']/$v['borrow_amount'],6)*100;//
					$MarketSerial = time('YmdHis');
					$configData = $this->pay->zanghuchaxun($MarketSerial,$v['firmid'],$v['real_name']);
					if($configData["ReturnInfo"]["RtnCode"] == '000000'&&$configData["ReturnInfo"]["RtnInfo"] == '查询客户信息成功'){
						$v['statusid'] = "1";
						$v['status'] = "正常";
					}else{
						$v['statusid'] = "2";
						$v['status'] = "异常";
					}					
					//$v['interest'] += $v['interest'];
                    //$v['interest']=round(($v['interest_bili']/100)*$this->repayment->_get_borrow_interest($v['mode'],$v['borrow_amount'],$v['rate'],$v['months'],$v['repay'],1),2);
					$start = date('Ymd',$data['confirm_time']);
					$end = date('Ymd',$data['rapay_time']);
					$Days=abs((strtotime($end)-strtotime($start))/86400)+1;
					
					//$Days = floor(($data['rapay_time']-$data['confirm_time']+24*60*60)/3600/24);	
					$v['interest'] = $v['amount']*$v['rate']/100*$Days/360;
					
					$arr ="";
				    $arr = explode(".",$v['interest']);
                    $a = substr($arr[1],0,2);
					$number = empty($a) ? '00' :  $a;
					$v['interest']  = $arr[0].'.'.$number;
					
					
                    $query[$v['borrow_no']][]=$v;
					$r[$k] =$v['amount'];
					$t[$k] =$v['interest'];
                   // $query[$v['borrow_no']][0]=$v['interest'];
		   			//var_dump( array_sum($query['amount']));

                }

            }
			//var_dump(array_sum($r));
			//var_dump(array_sum($t));
			$query['sum']=array_sum($r);
			$query['interest']=array_sum($t);
			//var_dump($query);

        }

        unset($temp);
        return $query;
    }

}