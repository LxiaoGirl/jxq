<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员日志
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Service_model extends CI_Model
{
    const user    = 'user'; // 会员
    const borrow  = 'borrow'; // 借款记录
    const payment = 'borrow_payment'; // 支付记录

    /**
     * 获取地区列表
     *
     * @access public
     * @return array
     */

    public function get_member_list()
    {
        $data = $temp = array();

        $temp['mobile'] = $this->input->get('mobile', TRUE);
        $temp['nric']   = $this->input->get('nric', TRUE);
        $temp['type']   = $this->router->fetch_class();
        $temp['type']   = ($temp['type'] == 'home') ? 1 : 2;

        if( ! empty($temp['mobile']) && ! empty($temp['nric']))
        {
            $temp['where'] = array(
                                'select'   => join_field('uid,user_name,nric,mobile,reg_date,last_date', self::user),
                                'join'     => array(
                                                    'table' => self::user,
                                                    'where' => join_field('uid', self::payment).' = '.join_field('uid', self::user)
                                                ),
                                'where'    => array(
                                                    join_field('type', self::payment) => $temp['type'],
                                                    join_field('mobile', self::user)  => $temp['mobile'],
                                                    join_field('nric', self::user)    => $temp['nric']
                                                ),
                                'order_by' => join_field('id', self::payment).' desc'
                            );

            $data = $this->c->show_page(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取投资人详情
     *
     * @access public
     * @return array
     */

    public function get_member_detail()
    {
        $data = $temp = array();

        $temp['uid'] = (int)$this->input->get('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'uid,user_name,nric,mobile,reg_date,last_date',
                                'where' => array('uid' => $temp['uid'])
                            );

            $data = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($data))
            {
                $data['list'] = $this->_get_borrow_list($data['uid']);
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取项目列表
     *
     * @access public
     * @param  integer $uid 用户ID
     * @return array
     */

    private function _get_borrow_list($uid = 0)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select' => join_field('borrow_no,rate,amount,balance,dateline', self::payment).','.join_field('subject,confirm_time,months,status', self::borrow),
                                'join'   => array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                                            ),
                                'where'  => array(
                                                join_field('uid', self::payment) => $uid
                                            ),
                                'order_by' => join_field('id', self::payment).' desc'
                            );

            $data = $this->c->get_all(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }
}