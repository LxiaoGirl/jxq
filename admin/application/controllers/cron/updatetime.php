<?php

/**
 * Created by PhpStorm.
 * User: fainle
 * Date: 14-11-20
 * Time: 下午2:50
 */
class Updatetime extends MY_Controller
{
    /**
     * 初始化
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron/updatetime_model', 'updatetime');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $query = $this->updatetime->processing();
        echo (!empty($query)) ? 'succeed' : 'failed';
    }
}