<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 提现记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Transaction_model extends CI_Model
{
    const flow        = 'cash_flow'; // 资金记录
    const transaction = 'user_transaction'; // 提现记录
    const user        = 'user'; // 会员
    const message     = 'message'; // 系统信息
	const card = 'user_card'; // 银行卡

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
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => join_field('transaction_no,uid,amount,charge,real_name,bank_name,account,add_time,status', self::transaction).','.join_field('user_name,real_name',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::transaction).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::transaction).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'T') === 0) ? join_field('transaction_no', self::transaction) : join_field('real_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::transaction, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 记录记录审核
     *
     * @access public
     * @return boolean
     */

    public function verify()
    {
        $query = FALSE;
        $temp  = array();

        $temp['transaction'] = $this->_get_transaction_info();

        if( ! empty($temp['transaction']))
        {
            $temp['transaction_no'] = $this->input->get('transaction_no', TRUE);
            $temp['balance']        = $this->_get_balance_amount($temp['transaction']['uid']);
			var_dump(11111);
            
			$this->db->trans_start();
			
			$temp['where'] = array('where' => array('uid' =>$temp['transaction']['uid']));
            $user = $this->c->get_row(self::user, $temp['where']);

			var_dump( $user);

			$temp['where'] = array('where' => array('transaction_no' =>$temp['transaction_no']));
            $transaction = $this->c->get_row(self::transaction, $temp['where']);
			var_dump( $transaction);
			$temp['where'] = array('where' => array('card_no' => $transaction['card_no']));
            $data = $this->c->get_row(self::card, $temp['where']);
			var_dump( $data);

			$Flag1="2";
			$TransferAmount = $temp['transaction']['amount']*100;
			$res = $this->pay->tixian($temp['transaction_no'] ,$transaction['account'],$user['real_name'],$data['bank_id'],$data['bank_name'],$data['bankaddr'],$user['firmid'],"1","0",$user['nric'],$TransferAmount,$Flag1,$data['province'],$data['city']);
			  header("Content-type:text/html;charset=utf-8");
var_dump($res);
			if($res['ReturnInfo']['RtnCode']=="000000"){
				            $temp['data'] = array(
                                'confirm_time' => time(),
                                'operator'     => $this->session->userdata('admin_name'),
                                'status'       => 1
                            );

            $temp['where'] = array(
                                'where' => array(
                                                'transaction_no' => $temp['transaction_no'],
                                                'status'         => 0
                                            )
                            );

				$this->c->update(self::transaction, $temp['where'], $temp['data']);

				$temp['data'] = array(
									'type'     => 2,
									'remarks'  => '提现审核通过',
								);

				$temp['where'] = array(
									'where' => array(
													'uid'    => $temp['transaction']['uid'],
													'source' => $temp['transaction_no'],
													'type'   => 3
												)
								);

				$this->c->update(self::flow, $temp['where'], $temp['data']);

				$this->db->trans_complete();

				$query = $this->db->trans_status();

				if( ! empty($query))
				{
					$temp['subject'] = sprintf('你的提现申请已经通过审核(%s)！', $temp['transaction_no']);
					$temp['content'] = sprintf(
											'你的提现申请已经通过审核(%s),提现金额：%s,提现后账户余额：%s！',
											$temp['transaction_no'],
											price_format($temp['transaction']['amount']),
											price_format($temp['balance'])
										);

					$this->passport->send_message($temp['transaction']['uid'], $temp['subject'], $temp['content']);
				}
			}
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取提现信息
     *
     * @access private
     * @param  integer  $uid 用户ID
     * @return float
     */

    private function _get_transaction_info()
    {
        $data = $temp = array();

        $temp['transaction_no'] = $this->input->get('transaction_no', TRUE);

        if( ! empty($temp['transaction_no']))
        {
            $temp['where'] = array(
                                'select' => 'transaction_no,uid,amount',
                                'where'  => array(
                                                'transaction_no' => $temp['transaction_no'],
                                                'status' => 0
                                            )
                            );

            $data = $this->c->get_row(self::transaction, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户余额
     *
     * @access private
     * @param  integer  $uid 用户ID
     * @return float
     */

    private function _get_balance_amount($uid = 0)
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
        return $balance;
    }
}