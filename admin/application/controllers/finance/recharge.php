<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 充值记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Recharge extends Login_Controller
{
    const user = 'user'; // 会员表

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('finance/recharge_model', 'recharge');
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
        $data = $this->recharge->show();
		$data['sidebar']=$this->user->get_node_navigation();

        $this->load->view('finance/recharge', $data);
    }

    /**
     * 手动充值
     *
     * @access public
     * @return void
     */

    public function refill()
    {
        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->recharge->refill();

           if( ! empty($query))
           {
                redirect('finance/recharge', 'refresh');
           }
        }
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('finance/recharge_refill',$data);
    }

    /**
     * 充值审核
     *
     * @access public
     * @return void
     */

    public function verify()
    {
        $this->recharge->verify();
        redirect('finance/recharge', 'refresh');
    }

    /**
     * 验证手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_valid_mobile($mobile = '')
    {
        $query = FALSE;
        $temp  = array();

        if(preg_match('/^1[345789](\d){9}$/', $mobile) == TRUE)
        {
            $temp['where'] = array('where' => array('mobile' => $mobile));
            $temp['count'] = $this->c->count(self::user, $temp['where']);

            $query = ( ! empty($temp['count'])) ? TRUE : FALSE;
        }

        unset($temp);
        return $query;
    }
}