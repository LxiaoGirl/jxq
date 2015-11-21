<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文章分类管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Pcategory_model extends CI_Model
{
    const pcategory = 'product_category'; // 产品分类

    /**
     * 添加分类信息
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $data  = array();

        $data = array(
                    'category'    => $this->input->post('category', TRUE),
                    'parent_id'   => (int)$this->input->post('parent_id'),
                    'sort_order'  => (int)$this->input->post('sort_order'),
                    'description' => $this->input->post('description', TRUE),
                    'status'      => (int)$this->input->post('status'),
                    'operator'    => $this->session->userdata('admin_name'),
                    'add_time'    => time(),
                    'update_time' => time()
                );

        $data['sort_order'] = ($data['sort_order'] > 65535) ? 65535 : $data['sort_order'];

        $query = $this->c->insert(self::pcategory, $data);

        unset($data);
        return $query;
    }

    /**
     * 删除分类
     *
     * @access public
     * @return boolean
     */

    public function delete()
    {
        $query = FALSE;
        $temp  = array();

        $temp['cat_id'] = (int)$this->input->get('cat_id');
        $temp['where']  = array('where' => array('cat_id' => $temp['cat_id']));

        $query = $this->c->delete(self::pcategory, $temp['where']);

        unset($temp);
        return $query;
    }

    /**
     * 更新分类信息
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['cat_id'] = (int)$this->input->post('cat_id');

        $temp['data'] = array(
                        'category'    => $this->input->post('category', TRUE),
                        'parent_id'   => (int)$this->input->post('parent_id'),
                        'sort_order'  => (int)$this->input->post('sort_order'),
                        'description' => $this->input->post('description', TRUE),
                        'status'      => (int)$this->input->post('status'),
                        'operator'    => $this->session->userdata('admin_name'),
                        'update_time' => time()
                    );

        $temp['data']['sort_order'] = ($temp['data']['sort_order'] > 65535) ? 65535 : $temp['data']['sort_order'];

        $temp['where'] = array('where' => array('cat_id' => $temp['cat_id']));
        $query = $this->c->update(self::pcategory, $temp['where'], $temp['data']);

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
                            'select'   => 'cat_id,category,sort_order,update_time,status',
                            'order_by' => 'cat_id desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'category', 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::pcategory, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取分类信息
     *
     * @access public
     * @return array
     */

    public function get_category_info()
    {
        $data = $temp = array();

        $temp['cat_id'] = (int)$this->input->get('cat_id');

        if( ! empty($temp['cat_id']))
        {
            $temp['where'] = array('where' => array('cat_id' => $temp['cat_id']));
            $data = $this->c->get_row(self::pcategory, $temp['where']);

            if( ! empty($data))
            {
                $data['cat_list'] = $this->get_category_list();
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取分类列表
     *
     * @access public
     * @return array
     */

    public function get_category_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'cat_id,category',
                            'where'    => array('parent_id' => 0),
                            'order_by' => 'sort_order desc,cat_id desc'
                        );

        $data = $this->c->get_all(self::pcategory, $temp['where']);

        unset($temp);
        return $data;
    }
}