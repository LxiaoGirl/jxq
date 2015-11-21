<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 登录注册
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Passport extends MY_Controller
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
        $this->_is_login();
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
            $query = $this->passport->sign_in();
            redirect('home', 'refresh');
        }

        $this->load->view('passport/home');
    }

    /**
     * 注销登录
     *
     * @access public
     * @return void
     */

    public function sign_out()
    {
        $this->passport->add_user_log('sign_out', '注销登录');
        $this->session->sess_destroy();

        redirect('passport', 'refresh');
    }

    /**
     * 忘记密码
     *
     * @access public
     * @return void
     */

    public function forgot()
    {
        $this->load->view('passport/forgot');
    }

    /**
     * 会员注册
     *
     * @access public
     * @return void
     */

    public function sign_up()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_message('is_valid_mobile', '');

        if($this->form_validation->run() == TRUE)
        {
            $this->passport->sign_up();
            redirect('passport', 'refresh');
        }

        $this->load->view('passport/sign_up');
    }

    /**
     * 手机号码是否注册
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_valid_mobile($mobile = '')
    {
        $query = FALSE;
        $temp  = array();

        if($this->is_mobile($mobile))
        {
            $temp['where'] = array('where' => array('mobile' => $mobile));
            $temp['count'] = $this->c->count(self::admin, $temp['where']);

            $query = (empty($temp['count'])) ? TRUE : FALSE;
        }

        unset($temp);
        return $query;
    }

    /**
     * 验证用户手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_mobile($mobile = '')
    {
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * 判断用户是否已经登录
     *
     * @access public
     * @return void
     */

    private function _is_login()
    {
        $method = $this->router->fetch_method();

        if(in_array($method, array('sign_up', 'index')) && $this->session->userdata('admin_id') > 0)
        {
            redirect('home', 'refresh');
        }
    }
}