<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 银行卡
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Card extends Login_Controller
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
        $this->load->model('member/card_model', 'card');
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
        $data = $this->card->get_card_list();
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/card', $data);
    }
    /**
     * 银行卡操作
     *
     * @access public
     * @return void
     */

    public function finish()
    {
        $this->card->finish();
        //redirect('member/card', 'refresh');
    }
	
	 /**
     * 银行卡修改
     *
     * @access public
     * @return void
     */

    public function modify()
    {
        $this->card->modify();
        //redirect('member/card', 'refresh');
    }

    /**
     * 银行卡详情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->card->get_card_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/card_detail', $data);
    }
}