<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 文章管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Article_model extends CI_Model
{
    const article  = 'article'; // 文章管理
    const category = 'article_category'; // 文章分类

    /**
     * 录入文章
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $temp  = array();

        $temp['data'] = array(
                            'title'       => $this->input->post('title', TRUE),
                            'keywords'    => $this->input->post('keywords', TRUE),
                            'description' => $this->input->post('description', TRUE),
                            'source'      => $this->input->post('source', TRUE),
                            'link_url'    => $this->input->post('link_url', TRUE),
                            'cat_id'      => (int)$this->input->post('cat_id'),
                            'status'      => (int)$this->input->post('status'),
                            'content'     => $this->input->post('content'),
                            'operator'    => $this->session->userdata('admin_name'),
                            'add_time'    => time(),
                            'update_time' => time()
                        );

        $query = $this->c->insert(self::article, $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 删除文章
     *
     * @access public
     * @return array
     */

    public function delete()
    {
        $query = FALSE;
        $temp  = array();

        $temp['id'] = (int)$this->input->get('id');

        if( ! empty($temp['id']))
        {
            $temp['where'] = array('where' => array('id' => $temp['id']));
            $query = $this->c->delete(self::article, $temp['where']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 更新文章
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['id'] = (int)$this->input->get('id');

        if( ! empty($temp['id']))
        {
            $temp['data'] = array(
                                'title'       => $this->input->post('title', TRUE),
                                'keywords'    => $this->input->post('keywords', TRUE),
                                'description' => $this->input->post('description', TRUE),
                                'source'      => $this->input->post('source', TRUE),
                                'link_url'    => $this->input->post('link_url', TRUE),
                                'cat_id'      => (int)$this->input->post('cat_id'),
                                'status'      => (int)$this->input->post('status'),
                                'content'     => $this->input->post('content'),
                                'operator'    => $this->session->userdata('admin_name'),
                                'update_time' => time()
                            );

            $temp['where'] = array('where' => array('id' => $temp['id']));

            $query = $this->c->update(self::article, $temp['where'], $temp['data']);
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
                            'select'   => 'id,title,cat_id,source,update_time,status',
                            'order_by' => 'id desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'title', 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::article, $temp['where']);

        if( ! empty($data))
        {
            $temp['cat_id'] = array();

            foreach ($data['data'] as $k => $v)
            {
                $temp['cat_id'][] = $v['cat_id'];
            }

            $temp['category'] = $this->_get_category_name($temp['cat_id']);

            if( ! empty($temp['category']))
            {
                foreach($data['data'] as $k => $v)
                {
                    $data['data'][$k]['category'] = (isset($temp['category'][$v['cat_id']])) ? $temp['category'][$v['cat_id']] : '';
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取详情
     *
     * @access public
     * @return array
     */

    public function get_article_info()
    {
        $data = $temp = array();

        $temp['id']    = (int)$this->input->get('id');

        if( ! empty($temp['id']))
        {
            $temp['where'] = array('where' => array('id' => $temp['id']));
            $data = $this->c->get_row(self::article, $temp['where']);

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
     * @param  array   $category  分类信息
     * @param  integer $parent_id 父级ID
     * @return array
     */

    private function _get_category_level($category = array(), $parent_id = 0, $deep = 0)
    {
        static $data = array();

        $data = ($parent_id != 0) ? $data : array();

        if( ! empty($category))
        {
            foreach($category as $k => $v)
            {
                if($v['parent_id'] == $parent_id)
                {
                    $data[] = array(
                                    'cat_id'    => $v['cat_id'],
                                    'category'  => $v['category'],
                                    'parent_id' => $v['parent_id'],
                                    'deep'      => $deep
                                );

                    $this->_get_category_level($category, $v['cat_id'], $deep + 1);
                }
            }
        }

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
                            'select'   => 'cat_id,parent_id,category',
                            'where'    => array('status' => 1),
                            'order_by' => 'sort_order desc,cat_id desc'
                        );

        $data = $this->c->get_all(self::category, $temp['where']);

        if( ! empty($data))
        {
            $data = $this->_get_category_level($data, 0);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取文章分类名称
     *
     * @access private
     * @param  array   $cat_id 分类ID
     * @return array
     */

    private function _get_category_name($cat_id = array())
    {
        $data = $temp = array();

        if( ! empty($cat_id))
        {
            $temp['where'] = array(
                                'select'   => 'cat_id,category',
                                'where_in' => array(
                                                'field' => 'cat_id',
                                                'value' => $cat_id
                                            )
                            );

            $temp['data'] = $this->c->get_all(self::category, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    $data[$v['cat_id']] = $v['category'];
                }
            }
        }

        unset($temp);
        return $data;
    }
}