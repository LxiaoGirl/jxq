<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 逾期处理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-29
 * @updated     2014-09-29
 * @version     1.0.0
 */

class Overdue extends MY_Controller
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
        $this->load->model('cron/overdue_model', 'overdue');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $query = $this->overdue->processing();
        echo ( ! empty($query)) ? 'succeed' : 'failed';
    }
}