<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户分组
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-15
 * @updated     2014-11-15
 * @version     1.0.0
 */

class Invite extends Login_Controller
{
    const group = 'user_group'; // 用户分组

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member/group_model', 'group');
        $this->load->model('member/invite_model', 'invite');
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
        $data = $this->invite->show_page();
					//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/invite', $data);
    }
	
	/**
     * 居间人批量封账处理
     *
     * @access public
     * @return void
     */

    public function processing()
    {
		
        $data = $this->invite->processing();
       // $this->load->view('member/invite', $data);
       // redirect('member/invite', 'refresh');
    }
	
	
	/**
     * 居间人查询单人数据
     *
     * @access public
     * @return void
     */

    public function get_one()
    {
        $data = $this->invite->get_one();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/get_one', $data);
       // $this->load->view('member/invite', $data);
       // redirect('member/invite', 'refresh');
    }
	
	/**
     * 居间人结算
     *
     * @access public
     * @return void
     */

    public function ruku_one()
    {
        $data = $this->invite->ruku_one();
       // $this->load->view('member/invite', $data);
       // redirect('member/invite', 'refresh');
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

        if($this->form_validation->run('member/group/create') == TRUE)
        {
            $query = $this->group->create();

            if( ! empty($query))
            {
                redirect('member/group', 'refresh');
            }
        }

        $data['group_list'] = $this->group->get_group_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/group_form', $data);
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

        if($this->form_validation->run('member/group/update') == TRUE)
        {
            $query = $this->group->update();

            if( ! empty($query))
            {
                redirect('member/group', 'refresh');
            }
        }

        $data = $this->group->get_group_info();
        $data['group_list'] = $this->group->get_group_list(TRUE);
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/group_form', $data);
    }

    /**
     * 删除记录
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->group->delete();
        redirect('member/group', 'refresh');
    }

    /**
     * 会员分组名称
     *
     * @access public
     * @param  string   $group_name 分组名称
     * @return boolean
     */

    public function is_valid_group_name($group_name = '')
    {
        $query = FALSE;
        $temp  = array();

        $temp['group_id'] = (int)$this->input->post('group_id');

        $temp['where']    = array(
                                'where' => array(
                                                'group_name'  => $group_name,
                                                'group_id <>' => $temp['group_id']
                                            )
                            );

        $temp['count'] = $this->c->count(self::group, $temp['where']);

        $query = ($temp['count'] == 0) ? TRUE : FALSE;

        unset($temp);
        return $query;
    }
}