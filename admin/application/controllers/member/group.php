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

class Group extends Login_Controller
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
        $data = array('group_list' => $this->group->get_group_list());
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('member/group', $data);
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