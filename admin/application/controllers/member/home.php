<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员列表
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Home extends Login_Controller
{
    const user  = 'user'; // 会员表
    const admin = 'admin'; // 后台用户

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member/member_model', 'member');
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
        $data = $this->member->show_page();
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/home', $data);
    }

    /**
     * 会员详情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->member->get_member_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/member_detail', $data);
    }

    /**
     * 资料更新
     *
     * @access public
     * @return void
     */

    public function update()
    {
        $data = array();
        $this->load->library('form_validation');

        if($this->form_validation->run('member/update') == TRUE)
        {
            $query = $this->member->update();

            if( ! empty($query))
            {
                redirect('member/home', 'refresh');
            }
        }

        $data = $this->member->get_member_info(FALSE);
        $data['group_list'] = $this->member->get_group_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/member_form', $data);
    }

    /**
     * 获取手机号码
     *
     * @access public
     * @return string
     */

    public function mobile()
    {
        $data = $temp = array();

        $temp['q'] = $this->input->get('q', TRUE);
        $temp['m'] = $this->input->get('m', TRUE);

        if( ! empty($temp['q']))
        {
            $temp['where'] = array(
                                'select' => 'mobile,user_name,real_name',
                                'like'   => array('field' => 'mobile', 'match' => $temp['q']),
                                'limit'  => 10
                            );

            if( ! empty($temp['m']))
            {
                $temp['where'] = array(
                                    'select' => 'mobile,admin_name',
                                    'like'   => array('field' => 'mobile', 'match' => $temp['q']),
                                    'limit'  => 10
                                );
            }

            $data = ( ! empty($temp['m'])) ? $this->c->get_all(self::admin, $temp['where']) : $this->c->get_all(self::user, $temp['where']);
        }

        unset($temp);
        exit(json_encode($data));
    }

    /**
     * 验证手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_mobile($mobile = '')
    {
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * 验证电话号码
     *
     * @access public
     * @param  string  $phone 电话号码
     * @return boolean
     */

    public function is_phone($phone = '')
    {
        $query = TRUE;

        if( ! empty($phone))
        {
            $query = (preg_match('/^((\d){3,5}-)?(\d){7,8}$/', $phone)) ? TRUE : FALSE;
        }

        return $query;
    }



	 /**yx
     * 设置自动投
     *
     * @access public
     * @return void
     */

    public function automatic_set()
    {
		      $data = array();
        $this->load->library('form_validation');

        if($this->form_validation->run('member/automatic_update') == TRUE)
        {
			//die();
            $query = $this->member->automatic_update();
			
            if( ! empty($query))
            {
                redirect('member/home', 'refresh');
            }
        }

        $data = $this->member->get_member_info();
		$data['all']=$this->member->automatic_info();
        $data['product_category_list'] = $this->member->get_product_category_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/automatic_form', $data);
    }
}