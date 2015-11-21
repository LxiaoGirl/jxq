<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-14
 * @updated     2014-11-14
 * @version     1.0.0
 */

class Home extends Login_Controller
{
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
		$data=array();
		$data = $this->user->show();
		
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        
        $this->load->view('user/home', $data);
    }

    /**
     * 创建用户
     *
     * @access public
     * @return void
     */

    public function create()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run('user/create') == TRUE)
        {
            $query = $this->user->create();

            if( ! empty($query))
            {
                redirect('user', 'refresh');
            }
        }

        $data['admin_list'] = $this->user->get_admin_list();
        $data['role_list']  = $this->user->get_role_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/user_form', $data);
    }

    /**
     * 修改记录
     *
     * @access public
     * @return void
     */

    public function update()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run('user/update') == TRUE)
        {
            $query = $this->user->update();

            if( ! empty($query))
            {
                redirect('user', 'refresh');
            }
        }

        $data = $this->user->get_admin_info();
        $data['admin_list'] = $this->user->get_admin_list(TRUE);
        $data['role_list']  = $this->user->get_role_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/user_form', $data);
    }

    /**
     * 修改状态
     *
     * @access public
     * @return void
     */

    public function status()
    {
        $this->user->set_user_status();
        redirect('user', 'refresh');
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

        if($this->is_mobile($mobile))
        {
            $temp['admin_id'] = $this->input->post('admin_id', TRUE);

            $query = $this->c->unique(self::admin, 'mobile', $mobile);

            if( ! empty($temp['admin_id']))
            {
                $query = ( ! empty($query)) ? FALSE : TRUE;
            }
        }

        unset($temp);
        return $query;
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
        return (preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
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
}