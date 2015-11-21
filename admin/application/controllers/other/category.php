<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文章分类
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Category extends Login_Controller
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
        $this->load->model('other/category_model', 'category');
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
        $data = $this->category->show();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/category', $data);
    }

    /**
     * 发布文章
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
            $query = $this->category->create();

            if( ! empty($query))
            {
                redirect('other/category', 'refresh');
            }
        }

        $data = array('cat_list' => $this->category->get_category_list());
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/category_form', $data);
    }

    /**
     * 删除分类
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->category->delete();
        redirect('other/category', 'refresh');
    }

    /**
     * 更新文章
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
            $query = $this->category->update();

            if( ! empty($query))
            {
                redirect('other/category', 'refresh');
            }
        }

        $data = $this->category->get_category_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/category_form', $data);
    }
}