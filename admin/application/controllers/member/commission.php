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

class Commission extends Login_Controller
{
    const user  = 'user'; // 会员表
    const commission = 'user_commission'; // 佣金提成

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member/commission_model', 'commission');
		$this->load->model('user/user_model', 'user');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->commission->show_page();
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/commission', $data);
    }

    /**
     * 佣金结算
     *
     * @access public
     * @return void
     */

    public function checkout()
    {
        $this->commission->checkout();
        redirect('member/commission', 'refresh');
    }

    /**
     * 会员详情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->commission->get_commission_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/commission_detail', $data);
    }
}