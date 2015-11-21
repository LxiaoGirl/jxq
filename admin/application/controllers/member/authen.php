<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 佣金提成
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Authen extends Login_Controller
{
    const user  = 'user'; // 会员表

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member/authen_model', 'authen');
		$this->load->model('user/user_model', 'user');
    }


    /**
     * 首页，默认为个人认证审核
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->authen->authen();
			//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/authen', $data);
    }
    /**
     * 企业认证审核
     *
     * @access public
     * @return void
     */

	public function enterprise()
    {
        $data = $this->authen->enterprise();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/enterprise', $data);
    }

}