<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 逾期处理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Overdue_model extends CI_Model
{
    const payment = 'borrow_payment'; // 支付记录
    const flow    = 'cash_flow'; // 资金记录
    const user ='user';

	/**
	 * 逾期处理
	 *
	 * @access public
	 * @return boolean
	 */

    public function processing()
	{
        $query = TRUE;
        $temp  = array();

        // 获取需要处理的记录
		$temp['payment_list'] = $this->_get_payment_list();

		if( ! empty($temp['payment_list']))
		{
            foreach($temp['payment_list'] as $k => $v)
            {
                // 获取余额
                $temp['balance'] = $this->_get_user_balance($v['uid']);

                // 逾期利息和服务费
                $temp['charge'] = $this->_get_charge_amount($v['amount'], $v['dateline']);
                $temp['amount'] = round($temp['charge'] + $v['amount'], 2);

                // 如果有余额扣款
                if($temp['balance'] >= $temp['amount'])
                {

                    $this->db->trans_start();

                    $temp['balance'] = round($temp['balance'] - $temp['amount'], 2);

                    $temp['data'] = array(
                                        'uid'      => $v['uid'],
                                        'type'     => 10,
                                        'amount'   => $temp['amount'],
                                        'balance'  => $temp['balance'],
                                        'source'   => $v['payment_no'],
                                        'remarks'  => '逾期还款,手续费'.price_format($temp['charge']),
                                        'dateline' => time(),
                                    );

                    $this->c->insert(self::flow, $temp['data']);

                    $temp['data']  = array(
                                        'balance'  => $temp['balance'],
                                        'charge'   => $temp['charge'],
                                        'pay_time' => time(),
                                        'status'   => 1
                                    );

                    $temp['where'] = array('where' => array('id' => $v['id']));

                    $this->c->update(self::payment, $temp['where'], $temp['data']);

                    $this->db->trans_complete();
                }
            }
		}

		unset($temp);
		return $query;
	}

    /**
     * 获取逾期利息和服务费
     *
     * @access private
     * @param  float   $amount   逾期金额
     * @param  integer $dateline 逾期时间
     * @return float
     */

    private function _get_charge_amount($amount = 0, $dateline = 0)
    {
        $charge = 0;
        $temp   = array();

        if( ! empty($amount) && ! empty($dateline))
        {
             $temp['day']  = floor((time() - $dateline) / 86400);

            if($temp['day'])
            {
                // 逾期利息
                $temp['rate'] = ($temp['day'] > 31) ? 1 : 0.5;

                $temp['amount'] = $amount * ($temp['rate'] / 1000) * $temp['day'];
                $charge += round($temp['amount'], 2);

                // 逾期服务费
                $temp['rate'] = ($temp['day'] > 30) ? 5 : 1;
                $temp['amount'] = $amount * ($temp['rate'] / 1000) * $temp['day'];
                $charge += round($temp['amount'], 2);
            }
        }

        unset($temp);
        return $charge;
    }

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
        $temp    = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => (int)$uid),
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

    private function _get_payment_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select' => 'id,payment_no,uid,amount,dateline',
                            'where'  => array('status' => 0)
                        );

        $data = $this->c->get_all(self::payment, $temp['where']);

        unset($temp);
        return $data;
    }
    /**
     * 2015.5.20
     * 预期记录列表
     */
    public function get_overdu_list(){
        $data = $temp = array();

        $temp['where'] = array(
            'select' => join_field('id,payment_no,amount,dateline,pay_date,status',self::payment).','.join_field('uid,user_name,mobile',self::user),
            'where'  => array(join_field('status',self::payment) => 0),
            'join'=>array(
                'table'=>self::user,
                'where'=>join_field('uid',self::user).' = '.join_field('uid',self::payment)
            )
        );

        $data = $this->c->get_all(self::payment, $temp['where']);

        unset($temp);
        return $data;
    }
}