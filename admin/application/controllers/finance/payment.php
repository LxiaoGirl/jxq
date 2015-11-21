<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Payment extends Login_Controller
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
        $this->load->model('finance/payment_model', 'payment');
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
        $data = $this->payment->show();
        $data['productcategory']=$this->c->get_all('product_category');
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/payment', $data);
    }
	 /**
     * 明细
     *
     * @access public
     * @return void
     */

	
	 public function detail()
    {
        $data = $this->payment->detail();
        $data['productcategory']=$this->c->get_all('product_category');
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/payment', $data);
    }

    /**
     * 支付借款
     *
     * @access public
     * @return void
     */

    public function pay_now()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->payment->pay_now();

/*             if( ! empty($query))
            {
                redirect('finance/payment', 'refresh');
            } */
        }

        $data = $this->payment->get_payment_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/payment_pay', $data);
    }
}