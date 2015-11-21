<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 资金明细
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Finance_model extends CI_Model
{
	const flow = 'cash_flow'; // 资金明细
    const user = 'user'; // 会员

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
        $temp['type']    = (int)$this->input->get('type');

        $temp['where'] = array(
                            'select'   => join_field('id,uid,type,amount,balance,source,remarks,dateline', self::flow).','.join_field('user_name', self::user),
                            'join'     => array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::flow).' = '.join_field('uid', self::user)
                                            ),
                            'order_by' => join_field('id', self::flow).' desc'
                        );

        if( ! empty($temp['type']))
        {
            $temp['where']['where'] = array(join_field('type', self::flow) => $temp['type']);
        }

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (preg_match('/^1[345789](\d){9}$/', $temp['keyword'])) ? join_field('mobile', self::user) : join_field('user_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::flow, $temp['where']);

        unset($temp);
        return $data;
    }
}