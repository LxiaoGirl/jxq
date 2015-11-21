<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 邀请人佣金处理
 *
 * @author      Longjianghu Email:779898335@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-15
 * @updated     2014-11-15
 * @version     1.0.0
 */

class Commission extends MY_Controller
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
        $this->load->model('cron/commission_model', 'commission');
    }

    /**
     * 会员邀请人的佣金计算处理
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $query = $this->commission->processing();
        echo ( ! empty($query)) ? 'succeed' : 'failed';
    }
}