<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 支付利息
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-29
 * @updated     2014-09-29
 * @version     1.0.0
 */
class Interest extends MY_Controller
{
    /**
     * 初始化
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron/interest_model', 'interest');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data['data'] = $this->interest->show();
        $this->load->view('cron/interest',$data);
    }

    public function interest(){
        $query = $this->interest->processing();
        echo (!empty($query)) ? 'succeed' : 'failed';
    }
}