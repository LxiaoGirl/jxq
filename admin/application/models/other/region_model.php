<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 地区管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Region_model extends CI_Model
{
	const region = 'region'; // 地区

    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['where']     = array();
        $temp['keyword']   = $this->input->get('keyword', TRUE);
        $temp['parent_id'] = (int)$this->input->get('parent_id');

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'region_name', 'match' => $temp['keyword']);
        }

        if( ! empty($temp['parent_id']))
        {
            $temp['where']['where'] = array('parent_id' => $temp['parent_id']);
        }

        $data = $this->c->show_page(self::region, $temp['where']);

        unset($temp);
        return $data;
    }
}