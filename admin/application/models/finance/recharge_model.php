<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 充值记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Recharge_model extends CI_Model
{
    const flow     = 'cash_flow'; // 资金记录
    const recharge = 'user_recharge'; // 充值记录
    const user     = 'user'; // 会员

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
        $temp['status']  = (int)$this->input->get('status');
        $temp['type']    = (int)$this->input->get('type');

        $temp['where'] = array(
                            'select'   => join_field('recharge_no,uid,type,amount,source,remarks,add_time,status', self::recharge).','.join_field('user_name,real_name',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::recharge).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::recharge).' desc'
                        );

        if( ! empty($temp['status']))
        {
            $temp['where']['where'][join_field('status', self::recharge)] = $temp['status'];
        }

        if( ! empty($temp['type']))
        {
            $temp['where']['where'][join_field('type', self::recharge)] = $temp['type'];
        }

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'R') === 0) ? join_field('recharge_no', self::recharge) : join_field('user_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::recharge, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 手动充值
     *
     * @access public
     * @return boolean
     */

    public function refill()
    {
        $query = FALSE;

        $temp['mobile'] = $this->input->post('mobile', TRUE);
		$temp['remarks'] = $this->input->post('remarks', TRUE);
		$temp['amount'] = $this->input->post('amount');

        $temp['where']  = array(
                            'select' => 'uid',
                            'where' => array('mobile' => $temp['mobile'])
                        );

        $temp['uid']   = $this->c->get_one(self::user, $temp['where']);
		$remarks="线下充值";
		$remarks=$temp['remarks'];
		$recharge_no = $this->c->transaction_no(self::recharge, 'recharge_no');
        if( ! empty($temp['uid']))
        {
            $temp['data'] = array(
                                'recharge_no' => $recharge_no,
                                'uid'         => $temp['uid'],
                                'type'        => 1,
                                'amount'      => (float)$temp['amount'],
                                'remarks'     => $this->input->post('remarks', TRUE),
                                'operator'    => $this->session->userdata('admin_name'),
                                'add_time'    => time(),
                                'status'      => 0
                            );

            $query = $this->c->insert(self::recharge, $temp['data']);
			$temp['balance'] = $this->_get_balance_amount($temp['uid']);
            $temp['balance'] = round($temp['balance'] + $temp['amount'], 2);
			  $temp['data1'] = array(
                                    'uid'      => $temp['uid'],
                                    'type'     => 1,
                                    'amount'   => (float)$temp['amount'],
                                    'balance'  => $temp['balance'],
                                    'source'   => $recharge_no,
                                    'remarks'  => $remarks,
                                    'dateline' => time(),
                                );

           //     $this->c->insert(self::flow, $temp['data1']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 充值记录审核
     *
     * @access public
     * @return boolean
     */

    public function verify()
    {
        $query = FALSE;
        $temp  = array();

        $temp['recharge_no'] = $this->input->get('recharge_no', TRUE);

        if( ! empty($temp['recharge_no']))
        {
            $temp['data'] = array(
                                'confirm_time' => time(),
                                'operator'     => $this->session->userdata('admin_name'),
                                'status'       => 1
                            );

            $temp['where'] = array(
                                'where' => array(
                                                'recharge_no' => $temp['recharge_no'],
                                                'status'      => 0
                                            )
                            );

            $temp['recharge'] = $this->_get_recharge_info();

            if( ! empty($temp['recharge']))
            {
                $temp['balance'] = $this->_get_balance_amount($temp['recharge']['uid']);
                $temp['balance'] = round($temp['balance'] + $temp['recharge']['amount'], 2);

                $this->db->trans_start();
                $this->c->update(self::recharge, $temp['where'], $temp['data']);

                $temp['data'] = array(
                                    'uid'      => $temp['recharge']['uid'],
                                    'type'     => 1,
                                    'amount'   => $temp['recharge']['amount'],
                                    'balance'  => $temp['balance'],
                                    'source'   => $temp['recharge']['recharge_no'],
                                    'remarks'  => '充值审核',
                                    'dateline' => time(),
                                );

                $this->c->insert(self::flow, $temp['data']);
                $this->db->trans_complete();

                $query = $this->db->trans_status();

                if( ! empty($query))
                {
                    $temp['subject'] = sprintf('你的充值申请已经通过审核(%s)!', $temp['recharge_no']);
                    $temp['content'] = sprintf(
                                            '你的充值申请已经通过审核(%s)，充值金额：%s，充值后账户余额：%s！',
                                            $temp['recharge_no'],
                                            price_format($temp['recharge']['amount']),
                                            price_format($temp['balance'])
                                        );

                    $this->passport->send_message($temp['recharge']['uid'], $temp['subject'], $temp['content']);
                }
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取充值记录
     *
     * @access private
     * @return array
     */

    private function _get_recharge_info()
    {
        $data = $temp = array();

        $temp['recharge_no'] = $this->input->get('recharge_no', TRUE);

        if( ! empty($temp['recharge_no']))
        {
            $temp['where'] = array(
                                'select' => 'uid,amount,recharge_no',
                                'where'  => array(
                                                'recharge_no' => $temp['recharge_no'],
                                                'status'      => 0
                                            )
                            );

            $data = $this->c->get_row(self::recharge, $temp['where']);
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