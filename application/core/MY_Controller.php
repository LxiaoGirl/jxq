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
        $this->_set_cookie();
    }

    /**
     * 获取推广渠道
     *
     * @access private
     * @return void
     */

    private function _set_cookie()
    {
        $invite_code = $this->input->get('invite_code', TRUE);

        if( ! empty($invite_code))
        {
            $cookie = array(
                       'name'   => 'invite_code',
                       'value'  => base64_encode($invite_code),
                       'expire' => '86400',
                       'domain' => '',
                       'path'   => '/',
                       'prefix' => '',
                   );

            $this->input->set_cookie($cookie);
        }
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
        if($this->session->userdata('uid') === FALSE)
        {
            if($this->uri->uri_string() != 'user/invite/yaoqing'){//2015.10.12 过滤邀请好友躺着赚钱的登录验证
	            redirect('login', 'refresh');
            }

        }

//        $this->load->model('user_model', 'user');
//        $this->load->model('send_model', 'send');
    }
}

class Api_Controller extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('common_model', 'c');
        $this->load->library('api');
        $this->api->index();
    }
    /**
     * api的返回
     * @param array $data
     */
    public function api_return($data=array()){
        $this->api->api_return($data);
    }

    public function curd($c=0,$u=0,$r=0,$d=0){
        $this->api->check_curd($c,$u,$r,$d);
    }
}