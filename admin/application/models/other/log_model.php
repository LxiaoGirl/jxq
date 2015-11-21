<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 管理日志
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class log_model extends CI_Model
{
	const log = 'admin_log'; // 管理日志

    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['where']   = array();
        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => 'id,admin_name,content,dateline',
                            'order_by' => 'id desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'admin_name', 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::log, $temp['where']);

        unset($temp);
        return $data;
    }
}