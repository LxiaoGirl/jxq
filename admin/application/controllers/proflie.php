<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 个人资料
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-26
 * @updated     2014-10-26
 * @version     1.0.0
 */

class Proflie extends Login_Controller
{
    const admin = 'admin'; // 后台用户

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile_model', 'profile');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->profile->update();
            redirect('', 'refresh');
        }

        $data = $this->profile->get_profile_detail();
        $this->load->view('passport/profile', $data);
    }

    /**
     * 修改密码
     *
     * @access public
     * @return void
     */

    public function password()
    {
        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->profile->password();
            redirect('', 'refresh');
        }

        $this->load->view('passport/password');
    }

    /**
     * 验证手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_valid_mobile($mobile = '')
    {
        $query = FALSE;
        $temp  = array();

        if(preg_match('/^1[345789](\d){9}$/', $mobile) == TRUE)
        {
            $temp['admin_id'] = $this->session->userdata('admin_id');

            $temp['where']    = array(
                                    'where' => array(
                                                    'mobile'      => $mobile,
                                                    'admin_id <>' => $temp['admin_id']
                                                )
                                );

            $temp['count'] = $this->c->count(self::admin, $temp['where']);

            if(empty($temp['count']))
            {
                $query = TRUE;
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 验证登录密码
     *
     * @access public
     * @param  string  $password 密码
     * @return boolean
     */

    public function is_valid_password($password = '')
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($password))
        {
            $temp['original'] = $this->session->userdata('password');
            $temp['hash']     = $this->session->userdata('hash');

            $temp['password'] = $this->c->password($password, $temp['hash']);

            if($temp['original'] == $temp['password'])
            {
                $query = TRUE;
            }
        }

        unset($temp);
        return $query;
    }
}