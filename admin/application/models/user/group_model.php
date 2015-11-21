<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 部门管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Group_model extends CI_Model
{
    const group = 'admin_group'; // 部门管理

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
                    'group_name'  => $this->input->post('group_name', TRUE),
                    'parent_id'   => (int)$this->input->post('parent_id'),
                    'sort_order'  => (int)$this->input->post('sort_order'),
                    'status'      => (int)$this->input->post('status'),
                    'remarks'     => $this->input->post('remarks', TRUE),
                    'operator'    => $this->session->userdata('admin_name'),
                    'add_time'    => time(),
                    'update_time' => time()
                );

        $data['sort_order'] = ($data['sort_order'] > 65535) ? 65535 : $data['sort_order'];

        $query = $this->c->insert(self::group, $data);

        unset($temp);
        return $query;
    }

    /**
     * 更新记录
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['group_id'] = (int)$this->input->post('group_id');

        $temp['data'] = array(
                            'group_name'  => $this->input->post('group_name', TRUE),
                            'parent_id'   => (int)$this->input->post('parent_id'),
                            'sort_order'  => (int)$this->input->post('sort_order'),
                            'status'      => (int)$this->input->post('status'),
                            'remarks'     => $this->input->post('remarks', TRUE),
                            'operator'    => $this->session->userdata('admin_name'),
                            'update_time' => time()
                        );

        $temp['data']['sort_order'] = ($temp['data']['sort_order'] > 65535) ? 65535 : $temp['data']['sort_order'];

        $temp['where'] = array('where' => array('group_id' => $temp['group_id']));

        $query = $this->c->update(self::group, $temp['where'], $temp['data']);

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

        $temp['group_id'] = (int)$this->input->get('group_id');

        if( ! empty($temp['group_id']))
        {
            $temp['parent_id'] = 0;

            $temp['where'] = array(
                                'select' => 'parent_id',
                                'where'  => array('group_id' => $temp['group_id'])
                            );

            $temp['parent_id'] = (int)$this->c->get_one(self::group, $temp['where']);

            $temp['where'] = array('where' => array('group_id' => $temp['group_id']));
            $query = $this->c->delete(self::group, $temp['where']);

            if( ! empty($query))
            {
                $temp['where'] = array('where' => array('parent_id' => $temp['group_id']));
                $temp['data']  = array('parent_id' => $temp['parent_id']);

                $this->c->update(self::group, $temp['where'], $temp['data']);
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取部门信息
     *
     * @access public
     * @return array
     */

    public function get_group_info()
    {
        $data = $temp = array();

        $temp['group_id'] = (int)$this->input->get('group_id');

        if( ! empty($temp['group_id']))
        {
            $temp['where'] = array('where' => array('group_id' => $temp['group_id']));
            $data = $this->c->get_row(self::group, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取部门列表
     *
     * @access public
     * @param  boolean $flag 数据更新
     * @return array
     */

    public function get_group_list($flag = FALSE)
    {
        $data = $temp = array();

        $temp['group_id'] = (int)$this->input->get('group_id');
        $temp['keyword']  = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => 'group_id,group_name,parent_id',
                            'order_by' => 'sort_order desc'
                        );

        if( ! empty($temp['group_id']) && ! empty($flag))
        {
            $temp['where']['where'] = array('group_id <>' => $temp['group_id']);
        }

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'group_name', 'match' => $temp['keyword']);
        }

        $temp['data'] = $this->c->get_all(self::group, $temp['where']);

        if( ! empty($temp['data']))
        {
            $data = $this->_get_group_level($temp['data']);
        }

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
}