<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class User_model extends CI_Model
{
    const admin = 'admin'; //用户表
    const group = 'admin_group'; // 用户组
    const role  = 'admin_role'; // 角色列表
	const node = 'admin_node'; // 节点
    /**
     * 创建记录
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $temp  = array();

        $temp['hash'] = random(6);

        $temp['mobile']   = $this->input->post('mobile', TRUE);
        $temp['role_id']  = (int)$this->input->post('role_id');
        $temp['group_id'] = $this->_get_group_id($temp['role_id']);

        $temp['password'] = $this->c->password(substr($temp['mobile'], -6), $temp['hash']);

        $temp['data'] = array(
                            'admin_name' => $this->input->post('admin_name', TRUE),
                            'password'   => $temp['password'],
                            'gender'     => $this->input->post('gender'),
                            'group_id'   => $temp['group_id'],
                            'mobile'     => $temp['mobile'],
                            'parent_id'  => (int)$this->input->post('parent_id'),
                            'role_id'    => $temp['role_id'],
                            'hash'       => $temp['hash'],
                            'reg_date'   => time(),
                            'reg_ip'     => $this->input->ip_address(),
                            'status'     => $this->input->post('status')
                        );

        $query = $this->c->insert(self::admin, $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 修改记录
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['mobile']   = $this->input->post('mobile', TRUE);

        $temp['admin_id'] = (int)$this->input->post('admin_id');
        $temp['role_id']  = (int)$this->input->post('role_id');
        $temp['group_id'] = $this->_get_group_id($temp['role_id']);

        $temp['data'] = array(
                            'admin_name' => $this->input->post('admin_name', TRUE),
                            'gender'     => $this->input->post('gender'),
                            'group_id'   => $temp['group_id'],
                            'mobile'     => $temp['mobile'],
                            'parent_id'  => (int)$this->input->post('parent_id'),
                            'role_id'    => $temp['role_id'],
                            'status'     => $this->input->post('status')
                        );

        $temp['where'] = array('where' => array('admin_id' => $temp['admin_id']));

        $query = $this->c->update(self::admin, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => join_field('admin_id,admin_name,gender,mobile,last_date,status', self::admin).','.join_field('role_name', self::role),
                            'join'     => array(
                                            'table' => self::role,
                                            'where' => join_field('role_id', self::admin).' = '.join_field('role_id', self::role)
                                        ),
                            'order_by' => join_field('admin_id', self::admin).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = preg_match('/^(\d+)/', $temp['keyword']) ? join_field('mobile', self::admin) : join_field('admin_name', self::admin);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::admin, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取用户信息
     *
     * @access public
     * @return array
     */

    public function get_admin_info()
    {
        $data = $temp = array();

        $temp['admin_id'] = (int)$this->input->get('admin_id');

        if( ! empty($temp['admin_id']))
        {
            $temp['where'] = array('where' => array('admin_id' => $temp['admin_id']));
            $data = $this->c->get_row(self::admin, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户列表
     *
     * @access public
     * @param  boolean $flag 数据更新
     * @return array
     */

    public function get_admin_list($flag = FALSE)
    {
        $data = $temp = array();

        $temp['admin_id'] = (int)$this->input->get('admin_id');

        $temp['where'] = array(
                            'select' => 'admin_id,admin_name,parent_id',
                            'where'  => array('status' => 1)
                        );

        $temp['data'] = $this->c->get_all(self::admin, $temp['where']);

        if( ! empty($temp['data']))
        {
            $temp['ignore'] = $this->_get_children_id($temp['data'], $temp['admin_id']);

            if( ! empty($temp['admin_id']))
            {
                array_unshift($temp['ignore'], $temp['admin_id']);
            }

            foreach($temp['data'] as $k => $v)
            {
                if( ! in_array($v['admin_id'], $temp['ignore']))
                {
                    $data[] = array(
                                    'admin_id'   => $v['admin_id'],
                                    'admin_name' => $v['admin_name']
                                );
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取角色列表
     *
     * @access public
     * @return array
     */

    public function get_role_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => join_field('role_id,role_name', self::role).','.join_field('group_name', self::group),
                            'join'     => array(
                                                'table' => self::group,
                                                'where' => join_field('group_id', self::role).' = '.join_field('group_id', self::group)
                                            ),
                            'where'    => array(join_field('status', self::role) => 1),
                            'order_by' => join_field('role_id', self::role).' desc'
                        );

        $data = $this->c->get_all(self::role, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 更新会员状态
     *
     * @access public
     * @return boolean
     */

    public function set_user_status()
    {
        $query = FALSE;
        $temp  = array();

        $temp['admin_id'] = (int)$this->input->get('admin_id');
        $temp['status']   = (int)$this->input->get('status');

        if( ! empty($temp['admin_id']))
        {
            $temp['data']  = array('status' => $temp['status']);
            $temp['where'] = array('where' => array('admin_id' => $temp['admin_id']));

            $query = $this->c->update(self::admin, $temp['where'], $temp['data']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取部门ID
     *
     * @access public
     * @param  integer $role_id 角色ID
     * @return integer
     */

    public function _get_group_id($role_id = 0)
    {
        $group_id = 0;
        $temp     = array();

        if( ! empty($role_id))
        {
            $temp['where'] = array(
                                'select' => 'group_id',
                                'where' => array('role_id' => $role_id)
                            );

            $group_id = $this->c->get_one(self::role, $temp['where']);
        }

        unset($temp);
        return $group_id;
    }

    /**
     * 获取用户层级
     *
     * @access public
     * @param  array  $user      用户列表
     * @param  intege $parent_id 父级节点
     * @return array
     */

    private function _get_children_id($user = array(), $parent_id = 0)
    {
        static $data = array();

        $data = ( ! empty($parent_id)) ? $data : array();

        if( ! empty($user))
        {
            foreach($user as $k => $v)
            {
                if($v['parent_id'] == $parent_id)
                {
                    $data[] = $v['admin_id'];

                    $this->_get_children_id($user, $v['admin_id']);
                }
            }
        }

        return $data;
    }




  /**
     * 获得左侧导航  get_node_navigation
     *
     */

    public function get_node_navigation()
    {
		$temp=$data=array();
        $ci    = & get_instance();
		 $authorized= $ci->session->userdata('authorized');
		$data['data']=$authorized;
		$temp['where'] = array(
                            'select' => 'link_url , node_name',
                            'where'  => array('parent_id' => 1)
                        );
		//获得一级列表
		$data['link_url']=$this->c->get_all(self::node, $temp['where']);
		return $data; 
}


}