<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 资金明细
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Home extends Login_Controller
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
        $this->load->model('finance/finance_model', 'finance');
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
        $data = $this->finance->show();
							//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/home', $data);
    }
}