<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 角色管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Role_model extends CI_Model
{
    const role  = 'admin_role'; // 角色管理
    const group = 'admin_group'; // 部门管理
    const node  = 'admin_node'; // 节点管理

    /**
     * 创建记录
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $data  = array();

        $data = array(
                    'role_name'   => $this->input->post('role_name', TRUE),
                    'group_id'    => (int)$this->input->post('group_id'),
                    'status'      => (int)$this->input->post('status'),
                    'remarks'     => $this->input->post('remarks', TRUE),
                    'operator'    => $this->session->userdata('admin_name'),
                    'add_time'    => time(),
                    'update_time' => time()
                );

        $query = $this->c->insert(self::role, $data);

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

        $temp['role_id'] = (int)$this->input->post('role_id');

        $temp['data'] = array(
                            'role_name'   => $this->input->post('role_name', TRUE),
                            'group_id'    => (int)$this->input->post('group_id'),
                            'status'      => (int)$this->input->post('status'),
                            'remarks'     => $this->input->post('remarks', TRUE),
                            'operator'    => $this->session->userdata('admin_name'),
                            'update_time' => time()
                        );

        $temp['where'] = array('where' => array('role_id' => $temp['role_id']));

        $query = $this->c->update(self::role, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 删除记录
     *
     * @access public
     * @return boolean
     */

    public function delete()
    {
        $query = FALSE;
        $temp  = array();

        $temp['role_id'] = (int)$this->input->get('role_id');

        if( ! empty($temp['role_id']))
        {
            
                $temp['where'] = array('where' => array('role_id' => $temp['role_id']));
                $temp['data']  = array('status' => '0');

                $this->c->update(self::role, $temp['where'], $temp['data']);
            
        }

        unset($temp);
        return $query;
    }

    /**
     * 角色授权
     *
     * @access public
     * @return boolean
     */

    public function authorization()
    {
        $query = FALSE;
        $temp  = array();

        $temp['role_id']    = (int)$this->input->post('role_id');
        $temp['authorized'] = $this->input->post('authorized');

        if( ! empty($temp['role_id']) && ! empty($temp['authorized']))
        {
            $temp['authorized'] = json_encode($temp['authorized']);
            $temp['where'] = array('where' => array('role_id' => $temp['role_id']));
            $temp['data']  = array('authorized' => $temp['authorized'], 'update_time'=>time());

            $query = $this->c->update(self::role, $temp['where'], $temp['data']);
        }

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
                            'select'   => join_field('role_id,role_name,operator,update_time,status', self::role).','.join_field('group_name', self::group),
                            'join'     => array(
                                                'table'=> self::group,
                                                'where' => join_field('group_id', self::role).' = '.join_field('group_id', self::group)
                                            ),
							'where'  => array(join_field('status', self::role) => '1'),
                            'order_by' => join_field('role_id', self::role).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => join_field('role_name', self::role), 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::role, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取角色信息
     *
     * @access public
     * @return array
     */

    public function get_role_info()
    {
        $data = $temp = array();

        $temp['role_id'] = (int)$this->input->get('role_id');

        if( ! empty($temp['role_id']))
        {
            $temp['where'] = array('where' => array('role_id' => $temp['role_id']));
            $data = $this->c->get_row(self::role, $temp['where']);

            if( ! empty($data))
            {
                $data['authorized'] = json_decode($data['authorized'], TRUE);
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取部门列表
     *
     * @access public
     * @return array
     */

    public function get_group_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'group_id,group_name,parent_id',
                            'where'    => array('status' => 1),
                            'order_by' => 'sort_order desc'
                        );

        $temp['data'] = $this->c->get_all(self::group, $temp['where']);

        if( ! empty($temp['data']))
        {
            $data = $this->_get_group_level($temp['data']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取节点列表
     *
     * @access public
     * @return array
     */

    public function get_node_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'node_id,node_name,parent_id,link_url,actions',
                            'where'    => array('status' => 1),
                            'order_by' => 'sort_order desc'
                        );

        $temp['data'] = $this->c->get_all(self::node, $temp['where']);

        if( ! empty($temp['data']))
        {
            $temp['data'] = $this->_get_node_level($temp['data']);

            foreach ($temp['data'] as $key => $val)
            {
                $val['actions'] = ( ! empty($val['actions'])) ? explode(PHP_EOL, $val['actions']) : '';

                if( ! empty($val['actions']))
                {
                    $temp['node'] = array();

                    foreach($val['actions'] as $k => $v)
                    {
                        list($temp['name'], $temp['link_url']) = explode('|', trim($v));
                        $temp['node'][$temp['name']] = $temp['link_url'];
                    }

                    $val['actions'] = $temp['node'];
                }

                if( ! empty($val['link_url']) && stripos($val['link_url'], '/') === FALSE)
                {
                    $val['link_url'] .= '/home';
                }

                $data[] = array(
                                'node_id'   => $val['node_id'],
                                'node_name' => $val['node_name'],
                                'parent_id' => $val['parent_id'],
                                'link_url'  => $val['link_url'],
                                'actions'   => $val['actions'],
                                'deep'      => $val['deep'],
                            );
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取角色列表
     *
     * @access public
     * @param  boolean $flag 数据更新
     * @return array
     */

    public function get_role_list($flag = FALSE)
    {
        $data = $temp = array();

        $temp['role_id'] = (int)$this->input->get('role_id');

        $temp['where'] = array(
                            'select'   => join_field('role_id,role_name', self::role).','.join_field('group_name', self::group),
                            'join'     => array(
                                                'table' => self::group,
                                                'where' => join_field('group_id', self::role).' = '.join_field('group_id', self::group)
                                            ),
                            'where'    => array(join_field('status', self::role) => 1),
                            'order_by' => join_field('status', self::role).' desc'
                        );

        if( ! empty($temp['role_id']) && ! empty($flag))
        {
            $temp['where']['where'] = array(join_field('role_id', self::role).' <>' => $temp['role_id']);
        }

        $data = $this->c->get_all(self::role, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取部门层级
     *
     * @access public
     * @param  array  $group     部门列表
     * @param  intege $parent_id 父级节点
     * @param  intege $deep      缩进层级
     * @return array
     */

    private function _get_group_level($group = array(), $parent_id = 0, $deep = 0)
    {
        static $data = array();

        $data = ( ! empty($parent_id)) ? $data : array();

        if( ! empty($group))
        {
            foreach($group as $k => $v)
            {
                if($v['parent_id'] == $parent_id)
                {
                    $data[] = array(
                                    'group_id'   => $v['group_id'],
                                    'group_name' => $v['group_name'],
                                    'parent_id' => $v['parent_id'],
                                    'deep'      => $deep
                                );

                    $this->_get_group_level($group, $v['group_id'], $deep + 1);
                }
            }
        }

        return $data;
    }

    /**
     * 获取节点层级
     *
     * @access public
     * @param  array  $node      节点列表
     * @param  intege $parent_id 父级节点
     * @param  intege $deep      缩进层级
     * @return array
     */

    private function _get_node_level($node = array(), $parent_id = 0, $deep = 0)
    {
        static $data = array();

        $data = ( ! empty($parent_id)) ? $data : array();

        if( ! empty($node))
        {
            foreach($node as $k => $v)
            {
                if($v['parent_id'] == $parent_id)
                {
                    $data[] = array(
                                    'node_id'   => $v['node_id'],
                                    'node_name' => $v['node_name'],
                                    'parent_id' => $v['parent_id'],
                                    'link_url'  => $v['link_url'],
                                    'actions'   => $v['actions'],
                                    'deep'      => $deep
                                );

                    $this->_get_node_level($node, $v['node_id'], $deep + 1);
                }
            }
        }

        return $data;
    }
}