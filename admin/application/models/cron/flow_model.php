<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 流标处理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Flow_model extends CI_Model
{
    const borrow  = 'borrow'; // 借款记录
    const payment = 'borrow_payment'; // 支付记录
    const flow    = 'cash_flow'; // 资金记录

	/**
	 * 流标处理
	 *
	 * @access public
	 * @return boolean
	 */

    public function processing()
	{
        $query = TRUE;
        $temp  = array();

        // 获取需要处理的记录
		$temp['borrow_no'] = $this->_get_borrow_list();

		if( ! empty($temp['borrow_no']))
		{
            $this->db->trans_start();

            // 获取冻结资金记录
            $temp['freeze'] = $this->_get_freeze_amount($temp['borrow_no']);

            if( ! empty($temp['freeze']))
            {
                foreach($temp['freeze'] as $k => $v)
                {
                    $temp['balance'] = $this->_get_user_balance($v['uid']);

                    $temp['data'][] = array(
                                            'uid'      => $v['uid'],
                                            'type'     => 4,
                                            'amount'   => $v['amount'],
                                            'balance'  => round($temp['balance'] + $v['amount'], 2),
                                            'source'   => $v['borrow_no'],
                                            'remarks'  => '资金解冻',
                                            'dateline' => time()
                                        );
                }

                $query = $this->c->insert(self::flow, $temp['data']);
            }

            $temp['data']  = array('finish_time' => time(), 'status' => 5);

            $temp['where'] = array(
                                'where_in' => array(
                                                'field' => 'borrow_no',
                                                'value' => $temp['borrow_no']
                                            )
                            );

            $this->c->update(self::borrow, $temp['where'], $temp['data']);

            $this->db->trans_complete();
		}

		unset($temp);
		return $query;
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

    private function _get_borrow_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select' => 'borrow_no',
                            'where'  => array(
                                            'due_date <' => time(),
                                            'amount >'   => 'receive',
                                            'status'     => 2
										)
                        );

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data']))
        {
            foreach($temp['data'] as $v)
            {
                $data[] = $v['borrow_no'];
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取冻结金额
     *
     * @access private
     * @param  array   $borrow_no 借款编号
     * @return array
     */

    private function _get_freeze_amount($borrow_no = array())
    {
        $data = $temp = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select'   => 'uid,SUM(`amount`) AS `amount`,borrow_no',
                                'where_in' => array(
                                                'field' => 'borrow_no',
                                                'value' => $borrow_no
                                            ),
                                'group_by' => 'uid'
                            );

            $data = $this->c->get_all(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }
}