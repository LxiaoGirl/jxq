<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 佣金提成
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Commission_model extends CI_Model
{
    const user  = 'user'; // 会员表
    const commission = 'user_commission'; // 佣金提成

    /**
     * 佣金结算
     *
     * @access public
     * @return boolean
     */

    public function checkout()
    {
        $query = FALSE;
        $temp  = array();

        $temp['commission_no'] = $this->input->get('commission_no', TRUE);

        if( ! empty($temp['commission_no']))
        {
            $temp['data']  = array(
                                'operator' => $this->session->userdata('admin_name'),
                                'pay_time' => time(),
                                'status'   => 1
                            );

            $temp['where'] = array(
                                'where' => array('commission_no' => $temp['commission_no'], 'status' => 0)
                            );

            $query = $this->c->update(self::commission, $temp['where'], $temp['data']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 数据分页
     *
     * @access public
     * @return array
     */

    public function show_page()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => join_field('commission_no,uid,borrow_no,amount,rate,commission,dateline,status', self::commission).','.join_field('user_name,real_name', self::user),
                            'join'     => array(
                                            'table' => self::user,
                                            'where' => join_field('uid', self::commission).' = '.join_field('uid', self::user)
                                        ),
                            'order_by' => join_field('status', self::commission).' asc,'.join_field('id', self::commission).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => join_field('user_name', self::user), 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::commission, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取佣金详情
     *
     * @access public
     * @return array
     */

    public function get_commission_info()
    {
        $data = $temp = array();

        $temp['commission_no'] = $this->input->get('commission_no', TRUE);

        if( ! empty($temp['commission_no']))
        {
            $temp['where'] = array(
                                'where' => array('commission_no' => $temp['commission_no'])
                            );

            $data = $this->c->get_row(self::commission, $temp['where']);

            if( ! empty($data))
            {
                $temp['uid'] = array($data['uid'], $data['investor']);

                $temp['where'] = array(
                                    'select'   => 'uid,user_name,real_name',
                                    'where_in' => array('field' => 'uid', 'value' => $temp['uid'])
                                );

                $temp['data'] = $this->c->get_all(self::user, $temp['where']);

                if( ! empty($temp['data']))
                {
                    foreach($temp['data'] as $k => $v)
                    {
                        $temp['user'][$v['uid']] = array('user_name' => $v['user_name'], 'real_name' => $v['real_name']);
                    }

                    if(isset($temp['user'][$data['uid']]))
                    {
                        $data['uid'] = $temp['user'][$data['uid']];
                    }

                    if(isset($temp['user'][$data['investor']]))
                    {
                        $data['investor'] = $temp['user'][$data['investor']];
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }
}