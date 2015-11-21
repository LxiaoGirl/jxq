<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 投资人
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
        $this->load->model('service/service_model', 'service');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->service->get_member_list();
        $this->load->view('service/home', $data);
    }

    /**
     * 投资人详情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->service->get_member_detail();
        $this->load->view('service/investor_detail', $data);
    }
}