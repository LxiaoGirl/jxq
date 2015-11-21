<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文章管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Home extends Login_Controller
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
        $this->load->model('other/article_model', 'article');
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
        $data = $this->article->show();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/home', $data);
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

        if($this->form_validation->run('article/create') == TRUE)
        {
            $query = $this->article->create();

            if( ! empty($query))
            {
                redirect('other', 'refresh');
            }
        }

        $data = array('cat_list' => $this->article->get_category_list());
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/article_form', $data);
    }

    /**
     * 删除文章
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->article->delete();
        redirect('other', 'refresh');
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

        if($this->form_validation->run('article/update') == TRUE)
        {
            $query = $this->article->update();

            if( ! empty($query))
            {
                redirect('other', 'refresh');
            }
        }

        $data = $this->article->get_article_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('other/article_form', $data);
    }
}