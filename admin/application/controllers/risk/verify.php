<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 标的审核
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Verify extends Login_Controller
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
        $this->load->model('risk/risk_model', 'risk');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->risk->get_borrow_list();
        $data['type'] = (int)$this->input->get('type');

        $this->load->view('risk/verify', $data);
    }
}