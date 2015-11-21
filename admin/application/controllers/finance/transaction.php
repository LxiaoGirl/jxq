<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 提现记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Transaction extends Login_Controller
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
        $this->load->model('finance/transaction_model', 'transaction');
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
        $data = $this->transaction->show();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/transaction', $data);
    }

    /**
     * 充值审核
     *
     * @access public
     * @return void
     */

    public function verify()
    {
        $this->transaction->verify();
        //redirect('finance/transaction', 'refresh');
    }
}