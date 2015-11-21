<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 角色管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-15
 * @updated     2014-11-15
 * @version     1.0.0
 */

class Role extends Login_Controller
{
    const role = 'admin_role'; // 角色管理

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/role_model', 'role');
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
        $data = $this->role->show();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/role', $data);
    }

    /**
     * 创建记录
     *
     * @access public
     * @return void
     */

    public function create()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->role->create();

            if( ! empty($query))
            {
                redirect('user/role', 'refresh');
            }
        }

        $data['group_list'] = $this->role->get_group_list();
        $data['role_list']  = $this->role->get_role_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/role_form', $data);
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

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->role->update();

            if( ! empty($query))
            {
                redirect('user/role', 'refresh');
            }
        }

        $data = $this->role->get_role_info();
        $data['group_list'] = $this->role->get_group_list();
        $data['role_list']  = $this->role->get_role_list(TRUE);
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/role_form', $data);
    }

    /**
     * 删除记录
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->role->delete();
        redirect('user/role', 'refresh');
    }

    /**
     * 角色授权
     *
     * @access public
     * @return void
     */

    public function authorization()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->role->authorization();

            if( ! empty($query))
            {
                redirect('user/role', 'refresh');
            }
        }

        $data = $this->role->get_role_info();
        $data['node_list'] = $this->role->get_node_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/role_authorization', $data);
    }
}