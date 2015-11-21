<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 个人资料
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-26
 * @updated     2014-10-26
 * @version     1.0.0
 */

class Profile_model extends CI_Model
{
    const admin   = 'admin'; // 后台用户

    /**
     * 更新登录密码
     *
     * @access public
     * @return boolean
     */

    public function password()
    {
        $query = FALSE;
        $temp  = array();

        $temp['password'] = $this->input->post('password', TRUE);

        $temp['admin_id'] = $this->session->userdata('admin_id');
        $temp['hash']     = $this->session->userdata('hash');

        $temp['data'] = array(
                            'password' => $this->c->password($temp['password'], $temp['hash'])
                        );

        $temp['where'] = array('where' => array('admin_id' => $temp['admin_id']));

        $query = $this->c->update(self::admin, $temp['where'], $temp['data']);

        if( ! empty($query))
        {
            $this->session->set_userdata($temp['data']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 更新用户信息
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['data']  = array(
                            'admin_name' => $this->input->post('admin_name', TRUE),
                            'gender'     => (int)$this->input->post('gender'),
                            'mobile'     => $this->input->post('mobile', TRUE),
                            'email'      => $this->input->post('email', TRUE),
                        );

        $temp['admin_id'] = $this->session->userdata('admin_id');
        $temp['where']    = array('where' => array('admin_id' => $temp['admin_id']));

        $query = $this->c->update(self::admin, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 获取用户信息
     *
     * @access public
     * @return array
     */

    public function get_profile_detail()
    {
        $data = $temp= array();

        $temp['admin_id'] = $this->session->userdata('admin_id');

        $temp['where'] = array(
                            'select' => 'admin_name,gender,email,mobile,reg_date,reg_ip,last_date,last_ip',
                            'where'  => array('admin_id' => $temp['admin_id'])
                        );

        $data = $this->c->get_row(self::admin, $temp['where']);

        unset($temp);
        return $data;
    }
}