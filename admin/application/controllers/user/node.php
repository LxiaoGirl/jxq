<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 节点管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-15
 * @updated     2014-11-15
 * @version     1.0.0
 */

class Node extends Login_Controller
{
    const node = 'admin_node'; // 节点管理

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/node_model', 'node');
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
        $data = array('node_list' => $this->node->get_node_list());
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/node', $data);
    }

    /**
     * 新建记录
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
          echo  $query = $this->node->create();

            if( ! empty($query))
            {
                redirect('user/node', 'refresh');
            }
        }

        $data['node_list'] = $this->node->get_node_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/node_form', $data);
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
            $query = $this->node->update();

            if( ! empty($query))
            {
                redirect('user/node', 'refresh');
            }
        }

        $data = $this->node->get_node_info();
        $data['node_list'] = $this->node->get_node_list(TRUE);
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('user/node_form', $data);
    }

    /**
     * 删除记录
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->node->delete();
        redirect('user/node', 'refresh');
    }

    /**
     * 验证显示排序
     *
     * @access public
     * @param  integer $sort_order 显示排序
     * @return boolean
     */

    public function is_valid_sort_order($sort_order = 1000)
    {
        return ($sort_order <= 65535) ? TRUE : FALSE;
    }
}