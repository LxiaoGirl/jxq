<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 充值提现
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Transaction_model extends CI_Model
{
    const recharge    = 'user_recharge'; // 充值记录
    const transaction = 'user_transaction'; // 提现记录
    const card        = 'user_card'; // 用戶银行卡
    const bank        = 'bank'; // 银行卡
    const flow        = 'cash_flow'; // 资金记录

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pay');
        $this->lang->load('form');
    }

    /**
     * 账户充值
     * 5元认证
     * @access public
     * @return array
     */
	public function recharge5()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $temp['bank']        = $this->input->post('bank');
            $temp['recharge_no'] = $this->c->transaction_no(self::recharge, 'recharge_no');
            $temp['amount']      = (float)$this->input->post('amount');

            if( ! empty($temp['amount']))
            {
                $temp['data'] = array(
                                    'recharge_no' => $temp['recharge_no'],
                                    'uid'         => $this->session->userdata('uid'),
                                    'type'        => 2,
                                    'bank'        => $temp['bank'],
                                    'amount'      => $this->input->post('amount'),
                                    'remarks'     => '认证付款',
                                    'add_time'    => time()
                                );

                $query = $this->c->insert(self::recharge, $temp['data']);

                if( ! empty($query))
                {
                    $data = array(
                                'code' => 0,
                                'msg'  => '正在转向支付通道,请等待！',
                                'url'  => site_url('pay/pay/renzheng?recharge_no='.$temp['recharge_no'])
                            );
                }
            }
            else
            {
                $data['msg'] = '充值金额必须填写!';
            }

        }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }
	/**
     * 账户充值
     *
     * @access public
     * @return array
     */
    public function recharge()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您提交的数据有误,请重试！', 'url' => '');

        /* if($this->form_validation->run() == TRUE)
        { */
            $temp['bank']        = $this->input->post('bank');
        /*************************************2015-10-30 wsb add 用于充值提交到凯塔后 当前页面根据订单号定是查询结果 由于原来是到充值中转方法处理的 当前页面得不到订单号 z这里获取传过来的订单号******************************************************************/
//        $temp['recharge_no'] = $this->c->transaction_no(self::recharge, 'recharge_no');
            $temp['recharge_no'] = authcode($this->input->get('recharge_no',TRUE),'',TRUE);
            $this->session->set_userdata(array('balance_refresh_over'=>'0'));//开启余额刷新
        /*************************************2015-10-30 wsb add******************************************************************/
            $temp['amount']      = (float)$this->input->post('amount');

            if( ! empty($temp['amount']))
            {
                    $form ="";
                    $form .= '<meta charset="utf-8">';
                    $form .= '<form name="pay_form" id="pay_form" action="'.site_url('pay/pay/index').'" method="post">';//
                    $form .= '<input type="hidden" name="recharge_no" value="'.$temp['recharge_no'].'"/>';
                    $form .= '<input type="hidden" name="bank" value="'.$temp['bank'].'"/>';
                    $form .= '<input type="hidden" name="amount" value="'.$temp['amount'].'"/>';
                    $form .= '</form>';
                    $form .= '<script>document.forms[\'pay_form\'].submit();</script>';
                    echo $form;
            }
            else
            {
                $data['msg'] = '充值金额必须填写!';
            }

       /*  }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        } */

        unset($temp);
        return $data;
    }

    /**
     * 会员提现
     *
     * @access public
     * @return boolean
     */

    public function transfer()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('transaction/transfer') == TRUE)
        {
            $temp['balance']  = $this->user->get_user_balance();
            $temp['amount']   = $this->input->post('amount', TRUE);

            try
            {
                if($temp['amount'] > $temp['balance'])
                {
                    throw new Exception('对不起,您的余额不足！');
                }

                $temp['authcode'] = $this->input->post('authcode', TRUE);
                $temp['mobile']   = $this->session->userdata('mobile', TRUE);
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 4, 150);

                if(empty($temp['is_check']))
                {
                    throw new Exception('对不起,您输入的手机验证码有误!');
                }

                $temp['security'] = $this->input->post('security', TRUE);
                $temp['security'] = $this->c->password($temp['security'], $this->session->userdata('hash'));

                if($temp['security'] != $this->session->userdata('security'))
                {
                    throw new Exception('对不起,您输入的交易密码有误!');
                }
            }
            catch(Exception $e)
            {
                $temp['msg'] = $e->getMessage();
            }

            if( ! isset($temp['msg']))
            {
                $this->db->trans_start();

                $temp['amount'] = (int)$this->input->post('amount', TRUE);
				//提现手续费处理
                $temp['charge'] = $this->_check_today_transfer()?2:0;

                $temp['transaction_no'] = $this->c->transaction_no(self::transaction, 'transaction_no');

                $temp['data'] = array(
                                    'transaction_no' => $temp['transaction_no'],
                                    'uid'            => $this->session->userdata('uid'),
                                    'card_no'        => $this->input->post('card_no', TRUE),
                                    'amount'         => round($temp['amount'] - $temp['charge'],2),
                                    'charge'         => $temp['charge'],
                                    'real_name'      => '',
                                    'bank_name'      => '',
                                    'account'        => '',
                                    'remarks'        => '会员提现',
                                    'add_time'       => time(),
                                );

                $temp['card_info'] = $this->_get_card_info($temp['data']['card_no']);

                if( ! empty($temp['card_info']))
                {
                    $temp['data']['real_name'] = $temp['card_info']['real_name'];
                    $temp['data']['bank_name'] = $temp['card_info']['bank_name'];
                    $temp['data']['account']   = $temp['card_info']['account'];
                }

                $this->c->insert(self::transaction, $temp['data']);

                $temp['data'] = array(
                                    'uid'      => $this->session->userdata('uid'),
                                    'type'     => 3,
                                    'amount'   => $temp['amount'],
                                    'balance'  => round($temp['balance'] - $temp['amount'], 2),
                                    'source'   => $temp['transaction_no'],
                                    'remarks'  => '会员提现',
                                    'dateline' => time()
                                );

                $this->c->insert(self::flow, $temp['data']);

                $this->db->trans_complete();

                $query = $this->db->trans_status();

                if( ! empty($query))
                {
					// 更新会员可用余额显示
					$this->session->set_userdata('balance', $temp['data']['balance']);

                    $data = array(
                                'code' => 0,
                                'msg'  => '您的提现申请已经提交请等待审核！',
                                'url'  => site_url('user/transaction/transfer_list')
                            );
                }
            }
            else
            {
                $data['msg'] = $temp['msg'];
            }
        }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    protected function _check_today_transfer(){
        $data = $this->c->get_row(self::transaction,array(
            'where'=>array('uid'=>$this->session->userdata('uid'),'add_time >='=>strtotime(date('Y-m-d').' 00:00:00'),'add_time <='=>time()))
        );
        return $data?true:false;
    }

    /**
     * 会员取消提现
     *
     * @access public
     * @return boolean
     */

    public function transfer_cancel()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您提交的数据有误,请重试！', 'url' => '');

		try
		{
            $temp['uid']            = $this->session->userdata('uid');
            $temp['transaction_no'] = $this->input->get('transaction_no', TRUE);

            $temp['where'] = array(
                                'select' => 'transaction_no,amount,charge',
                                'where'  => array(
                                                'transaction_no' => $temp['transaction_no'],
                                                'uid'            => $temp['uid'],
                                                'status'         => 0
                                            )
                            );

            $temp['transaction'] = $this->c->get_row(self::transaction, $temp['where']);

			if(empty($temp['transaction']))
			{
				throw new Exception('对不起,没有你要取消的提现数据！');
			}
		}
		catch(Exception $e)
		{
			$temp['msg'] = $e->getMessage();
		}

		if( ! isset($temp['msg']))
		{
			$this->db->trans_start();

            $temp['data']  = array('field' => 'status', 'value' => 2);
            $temp['where'] = array('where' => array('transaction_no' => $temp['transaction_no']));

			$this->c->set(self::transaction, $temp['where'], $temp['data']);

            $temp['balance'] = $this->user->get_user_balance();

            $temp['amount']  = round($temp['transaction']['amount'] + $temp['transaction']['charge'], 2);
            $temp['balance'] = round($temp['balance'] + $temp['amount'], 2);

            $temp['where']   = array(
                                    'where' => array(
                                                    'uid'    => $temp['uid'],
                                                    'type'   => 4,
                                                    'source' => $temp['transaction_no']
                                                )
                                );

            $temp['count'] = $this->c->count(self::flow, $temp['where']);

            if($temp['count'] == 0)
            {
                $temp['data'] = array(
                                    'uid'      => $temp['uid'],
                                    'type'     => 4,
                                    'amount'   => $temp['amount'],
                                    'balance'  => $temp['balance'],
                                    'source'   => $temp['transaction_no'],
                                    'remarks'  => '取消提现',
                                    'dateline' => time()
                                );

                $this->c->insert(self::flow, $temp['data']);
            }

			$this->db->trans_complete();

			$query = $this->db->trans_status();

			if( ! empty($query))
			{
				// 更新会员可用余额显示

				$this->session->set_userdata('balance', $temp['balance']);

				$data = array(
							'code' => 0,
							'msg'  => '您的提现取消成功！',
							'url'  => site_url('user/transaction/transfer_list')
						);
			}
		}
		else
		{
			$data['msg'] = $temp['msg'];
		}

        unset($temp);
        return $data;
    }
	/**
     * 单条处理充值记录
     * 猜想，假设用户充值了2笔，最后
     * @access public
     * @return array
     */
	public function recharge_one()
    {

		$data = $temp = array();
		
		
		$temp['recharge_no'] = $this->input->get('recharge_no', TRUE);

        if(empty($temp['recharge_no']))
        {

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid'],'status' => '0'),
                            'order_by' => 'id desc'
                        );
		$data = $this->c->get_all(self::recharge, $temp['where']);
		 $data = $data[0]; 
		}else{
			
		$temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid'],'recharge_no' => $temp['recharge_no'],'status' => '0'),
                            'order_by' => 'id desc'
                        );
		$data = $this->c->get_all(self::recharge, $temp['where']);
					 $data = $data[0]; 

			
		}
		if(!empty($data)){
				$res = $this->pay->dingdanchaxun($data['recharge_no']);		

						if($res['FlagInfo']['Flag3']==1){							
							$temp['data'] = array(
						        'status' => '1'
							);
				            $this->db->trans_start();
							$temp['where'] = array('where' => array('recharge_no' => $data['recharge_no']));
							$query = $this->c->update(self::recharge, $temp['where'], $temp['data']);							
							if($query){
								$query=$this->_add_cash_flow($data['uid'],$data['amount'],$data['recharge_no']);	
								if($query){
								$this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));
								}
							}
							if($query){
								$this->db->trans_complete();	
							}
							
						}elseif($res['FlagInfo']['Flag3']==9){
							$temp['data'] = array(
						        'status' => '1'
							);
				            $this->db->trans_start();
							$temp['where'] = array('where' => array('recharge_no' => $data['recharge_no']));							
							$query = $this->c->update(self::recharge, $temp['where'], $temp['data']);					
							if($query){
								$query=$this->_add_cash_flow($data['uid'],$data['amount'],$data['recharge_no']);								
								if($query){
								$this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));
								}							}
							if($query){
								$this->db->trans_complete();	
							}
						}
			}
						
 $temp['where'] = array(
                            'select'   => 'recharge_no,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );
        $data = $this->c->show_page(self::recharge, $temp['where']);
        unset($temp);
        return $data;
		
		
	}
	 
	 
	/**
     * 批量处理充值记录
     *
     * @access public
     * @return array
     */

    public function rerecharge_list()
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid'],'status' => '0'),
                            'order_by' => 'id desc'
                        );
		$data = $this->c->get_all(self::recharge, $temp['where']);
		 foreach ($data as $k => $v)
                    {
						$res = $this->pay->dingdanchaxun($v['recharge_no']);							
						
						if($res['FlagInfo']['Flag3']==1){							
							$temp['data'] = array(
						        'status' => '1'
							);
				            $this->db->trans_start();
							$temp['where'] = array('where' => array('recharge_no' => $v['recharge_no']));
							$query = $this->c->update(self::recharge, $temp['where'], $temp['data']);							
							if($query){
								$query=$this->_add_cash_flow($v['uid'],$v['amount'],$v['recharge_no']);	
								if($query){
								$this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));
								}
							}
							if($query){
								$this->db->trans_complete();	
							}
							
						}elseif($res['FlagInfo']['Flag3']==9){
							$temp['data'] = array(
						        'status' => '1'
							);
				            $this->db->trans_start();
							$temp['where'] = array('where' => array('recharge_no' => $v['recharge_no']));							
							$query = $this->c->update(self::recharge, $temp['where'], $temp['data']);					
							if($query){
								$query=$this->_add_cash_flow($v['uid'],$v['amount'],$v['recharge_no']);								
								if($query){
								$this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));
								}							}
							if($query){
								$this->db->trans_complete();	
							}
						}
						
					}
		 $temp['where'] = array(
                            'select'   => 'recharge_no,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );
        $data = $this->c->show_page(self::recharge, $temp['where']);
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

    private function _get_user_balance($uid = 0)
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

            $balance = $this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return (float)$balance;
    }
	 /**
     * 添加充值记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  float   $amount 充值金额
     * @param  string  $source 记录来源
     * @return boolean
     */

    private function _add_cash_flow($uid = 0, $amount = 0, $source = '' , $remarks = '会员充值')
    {
        $query = FALSE;
        $temp  = array();

		//var_dump($uid);
        if( ! empty($uid) && ! empty($amount) && ! empty($source))
        {
            $temp['where'] = array('where' => array('source' => $source));
            $temp['count'] = $this->c->count(self::flow, $temp['where']);

            if($temp['count'] == 0)
            {
                $temp['balance'] = $this->_get_user_balance($uid);

                $temp['data'] = array(
                                    'uid'      => $uid,
                                    'type'     => 1,
                                    'amount'   => $amount,
                                    'balance'  => round($amount + $temp['balance'], 2),
                                    'source'   => $source,
                                    'remarks'  => $remarks,
                                    'dateline' => time(),
                                );

                $query = $this->c->insert(self::flow, $temp['data']);
            }
        }
		        /*
				$temp['where'] = array(
                            'select'   => 'recharge_no,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );
				$data = $this->c->show_page(self::recharge, $temp['where']);
				*/

        unset($temp);
        return $query;
    }

    /**
     * 获取充值记录
     *
     * @access public
     * @return array
     */

    public function get_recharge_list()
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'recharge_no,type,amount,source,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );

        $temp['condition'] = $this->user->get_search_condition();

		
		
        if(isset($_GET['status']))
        {
            $temp['where']['where']['status'] = (int)$this->input->get('status');
        }

        if( ! empty($temp['condition']))
        {
            $temp['condition']  = array('between' => 'add_time BETWEEN '.$temp['condition']['start_date'].' AND '.$temp['condition']['end_date']);
            $temp['where'] = array_merge($temp['where'], $temp['condition']);
        }

        $data = $this->c->show_page(self::recharge, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取提现记录
     *
     * @access public
     * @return array
     */

    public function get_transfer_list()
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'id,transaction_no,amount,charge,real_name,bank_name,account,remarks,add_time,status',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );

        $temp['condition'] = $this->user->get_search_condition();

        if(isset($_GET['status']))
        {
            $temp['where']['where']['status'] = (int)$this->input->get('status');
        }

        if( ! empty($temp['condition']))
        {
            $temp['condition']  = array('between' => 'add_time BETWEEN '.$temp['condition']['start_date'].' AND '.$temp['condition']['end_date']);
            $temp['where'] = array_merge($temp['where'], $temp['condition']);
        }

        $data = $this->c->show_page(self::transaction, $temp['where']);
        unset($temp);
        return $data;
    }

    /**
     * 获取银行卡
     *
     * @access public
     * @return array
     */

    public function get_user_card()
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => join_field('card_no,account', self::card).','.join_field('bank_name,code', self::bank),
                            'where'    => array(self::card.'.uid' => $temp['uid'],self::card.'.status' => 1),
                            'join'      => array(
                                            'table' => self::bank,
                                            'where' => join_field('bank_id', self::card).' = '.join_field('bank_id', self::bank),
                                            'flag'=>'inner'
                                            ),
                            'order_by' => self::card.'.id desc'
                        );
        $data = $this->c->get_all(self::card, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取银行卡信息
     *
     * @access public
     * @param  string  $card_no 卡号
     * @return boolean
     */

    private function _get_card_info($card_no = '')
    {
        $data = $temp = array();

        if( ! empty($card_no))
        {
            $temp['uid']   = $this->session->userdata('uid');

            $temp['where'] = array(
                                'select' => 'real_name,bank_name,account',
                                'where' => array(
                                                'card_no' => $card_no,
                                                'uid'     => $temp['uid'],
                                                'status'  => 1
                                            )
                            );

            $data = $this->c->get_row(self::card, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取银行列表
     *
     * @access public
     * @return array
     */

    public function get_bank_list()
    {
        $data = $temp = array();

		$temp['where'] = array('select' => 'bank_id,bank_name,code');
		$data = $this->c->get_all(self::bank, $temp['where']);

        unset($temp);
		return $data;
    }

/*************************************2015-10-30 wsb add******************************************************************/
    /**
     * 处理特定订单号的凯塔订单结果 返回余额信息
     * code=1是处理失败了  =0 是处理成功了
     * @return array
     */
    public function ajax_recharge_auto_refresh(){
        $data = array('code'=>1,'data'=>'');
        $temp =array();
        $recharge_no = authcode($this->input->post('recharge_no',TRUE),'',TRUE);//$this->input->post('recharge_no',true);

        if($recharge_no){
            $temp['uid'] = $this->session->userdata('uid');
            session_write_close();//關閉session 防止session鎖頁面
            $temp['where'] = array(
                'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
                'where'    => array('uid' => $temp['uid'],'recharge_no' => $recharge_no,'status' => '0','type' => 2)
            );
            $temp['data'] = $this->c->get_row(self::recharge, $temp['where']);
            if($temp['data']){
                $res = $this->pay->dingdanchaxun($recharge_no);
                if($res['FlagInfo']['Flag3']==1 || $res['FlagInfo']['Flag3']==9){
                    $temp['update_data'] = array('status' => '1');
                    $this->db->trans_start();
                    $temp['where'] = array('where' => array('recharge_no' => $temp['data']['recharge_no']));
                    $query = $this->c->update(self::recharge, $temp['where'], $temp['update_data']);
                    if($query){
                        $query=$this->_add_cash_flow($temp['data']['uid'],$temp['data']['amount'],$temp['data']['recharge_no']);
                        if($query){
                            $this->db->trans_complete();
                            $query = $this->db->trans_status();
                            if($query){
                                session_start();
                                $temp['balance'] = $this->_get_user_balance($this->session->userdata('uid'));
                                $this->session->set_userdata('balance',$temp['balance']);
                                $data['code'] = 0;
                                $data['data'] = $temp['balance'];
                            }
                        }
                    }
                }
            }else{
                session_start();
                $temp['balance'] = $this->_get_user_balance($this->session->userdata('uid'));
                $data['data'] = $temp['balance'];
                $data['code'] = 0;
            }
        }else{
            session_start();
            $temp['balance'] = $this->_get_user_balance($this->session->userdata('uid'));
            $data['data'] = $temp['balance'];
            $data['code'] = 0;
        }

        unset($temp);
        return $data;
    }

    /**
     * 处理两个小时内凯塔的充值失败订单 然后返回余额信息
     * @return array
     */
    public function ajax_balance_refresh(){
        $data = array('code'=>0,'data'=>'');
        $temp =array();
        $temp['uid'] = $this->session->userdata('uid');
        session_write_close();//關閉session 防止session鎖頁面
            //查询两个小时内的凯塔的充值失败订单

            $temp['where'] = array(
                'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
                'where'    => array('uid' => $temp['uid'],'status' => '0','type' => 2,'add_time >='=>time()-7200),
                'order_by'=>'add_time DESC',
                'limit'=>2
            );
            $temp['data'] = $this->c->get_all(self::recharge, $temp['where']);

            if($temp['data']){
                //如果有订单信息 循环查询订单结果
                foreach($temp['data'] as $key=>$val){
                    $res = $this->pay->dingdanchaxun($val['recharge_no']);
                    if($res['FlagInfo']['Flag3']==1 || $res['FlagInfo']['Flag3']==9){
                        $temp['update_data'] = array('status' => '1');
                        $this->db->trans_start();
                        $temp['where'] = array('where' => array('recharge_no' => $val['recharge_no']));
                        $query = $this->c->update(self::recharge, $temp['where'], $temp['update_data']);
                        if($query){
                            $query=$this->_add_cash_flow($val['uid'],$val['amount'],$val['recharge_no']);
                            if($query){
                                $this->db->trans_complete();
                            }
                        }
                    }
                }
            }
        session_start();
        $this->session->set_userdata(array('balance_refresh_over'=>'1'));//余额刷新 是否完成的标识 view/common/user.php里有判断 进行余额刷新的调用  recharge里也有更改这个标识的
        //循环完成后 查询余额信息进行返回
       $temp['balance'] = (float) $this->_get_user_balance($temp['uid']);
        $this->session->set_userdata('balance',$temp['balance']);
        $data['data'] = $temp['balance'];

        unset($temp);
        return $data;
    }
/*************************************2015-10-30 wsb add******************************************************************/
}