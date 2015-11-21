<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自定义控制器
 *
 * 如需动态更换主题请查看MY_Loader.php
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

class MY_Controller extends CI_Controller
{
    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('common_model', 'c');
        $this->load->model('passport_model', 'passport');
    }
}

class Login_Controller extends MY_Controller
{
    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();

        // 判断用户是否登录
        if($this->session->userdata('admin_id') === FALSE)
        {
            redirect('passport', 'refresh');
        }
    }
}