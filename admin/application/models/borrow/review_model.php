<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 银行卡管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Review_model extends CI_Model
{
    const user    = 'user'; // 会员表
    const info    = 'user_info'; //会员信息
    const address = 'user_address'; // 地址信息
    const region  = 'region'; // 地区信息

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
                            'select'   => 'uid,user_name,real_name,nric,status',
                            'order_by' => 'uid desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (preg_match('/^1[345789](\d){9}$/', $temp['keyword'])) ? 'mobile' : 'user_name';
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::user, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取会员详情
     *
     * @access public
     * @return array
     */

    public function get_member_info()
    {
        $data = $temp = array();

        $temp['uid'] = (int)$this->input->get('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'uid,user_name,avatar,gender,email,mobile,phone,real_name,nric,status',
                                'where'  => array('uid' => $temp['uid'])
                            );

            $data = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($data))
            {
                $data['extend']  = $this->_get_user_info($data['uid']);
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户姓名
     *
     * @access public
     * @param  integer $uid  会员ID
     * @return string
     */

    private function _get_user_name($uid = 0)
    {
        $name = '';
        $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select' => 'user_name,real_name',
                                'where'  => array('uid' => (int)$uid)
                            );

            $temp['data'] = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($temp['data']))
            {
                $name .= $temp['data']['user_name'];
                $name .= ( ! empty($temp['data']['real_name'])) ? '['.$temp['data']['real_name'].']' : '';
            }
        }

        unset($temp);
        return $name;
    }

    /**
     * 获取扩展信息
     *
     * @access public
     * @param  integer $uid  会员ID
     * @return array
     */

    private function _get_user_info($uid = 0, $type = array())
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'type,key,value',
                                'where'    => array('uid' => $uid),
                                'where_in' => array('field' => 'type', 'value' => array(1,2,3,4,5))
                            );

            $temp['data'] = $this->c->get_all(self::info, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    if($v['type'] == 1 && $v['key'] == 'address')
                    {
                        $v['value'] = $this->_get_user_address($v['value']);
                    }

                    if($v['type'] == 2 && in_array($v['key'], array('registered', 'place')))
                    {
                        $v['value'] = $this->_get_user_address($v['value']);
                    }

                    if($v['type'] == 3 && in_array($v['key'], array('province','city','district')))
                    {
                        $v['value'] = $this->_get_region_info($v['value']);
                    }

                    $data[$v['type']][$v['key']] = $v['value'];
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地址信息
     *
     * @access public
     * @param  integer $id 地址ID
     * @return array
     */

    private function _get_user_address($id = 0)
    {
        $address = '';
        $temp    = array();

        if( ! empty($id))
        {
            $temp['where'] = array(
                                'select' => 'province,city,district,address',
                                'where'  => array('id' => $id),
                            );

            $temp['data'] = $this->c->get_row(self::address, $temp['where']);

            if( ! empty($temp['data']))
            {
                $temp['region_id'] = array($temp['data']['province'], $temp['data']['city'], $temp['data']['district']);
                $temp['address']   = $this->_get_region_info($temp['region_id']);

                if( ! empty($temp['address']))
                {
                    $address = $temp['address'].$temp['data']['address'];
                }
            }
        }

        unset($temp);
        return $address;
    }

    /**
     * 获取地区信息
     *
     * @access public
     * @param  array  $region_id 地址ID
     * @return array
     */

    private function _get_region_info($region_id = array())
    {
        $data = $temp = array();

        if( ! empty($region_id))
        {
            $temp['where'] = array(
                                'select' => 'region_id, region_name',
                                'where_in' => array('field' => 'region_id', 'value' => $region_id)
                            );

            $temp['data'] = $this->c->get_all(self::region, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach ($temp['data'] as $k => $v)
                {
                    $data[] = $v['region_name'];
                }

                $data = implode('', $data);
            }
        }

        unset($temp);
        return $data;
    }
}