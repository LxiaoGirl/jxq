<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 借款申请
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Apply_model extends CI_Model
{
    const apply = 'borrow_apply'; // 借款申请

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
        $temp['start_date'] = $this->input->get('start_date', TRUE);
        $temp['end_date'] = $this->input->get('end_date', TRUE);

        $temp['where'] = array(
                            'order_by' => 'status asc,p_type asc,type asc,add_time desc,id desc'//wsb-2015.5.13  status asc,p_type asc,type asc,add_time desc,
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (preg_match('/^1[345789](\d){9}$/', $temp['keyword'])) ? 'mobile' : 'user_name';
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        //wsb-2015.5.13
        if( ! empty($temp['start_date']) || ! empty($temp['end_date'])){
            $temp['start_date']=strtotime($temp['start_date']);
            $temp['end_date']=strtotime($temp['end_date']);
            if($temp['start_date'] !== FALSE || $temp['end_date'] !== FALSE){
                $temp['end_date']  =($temp['end_date'] <= $temp['start_date']) ? $temp['start_date'] + 86400 : $temp['end_date'];
                $temp['condition'] = array('between' => 'add_time BETWEEN '.$temp['start_date'].' AND '.$temp['end_date']);
                $temp['where']     = array_merge($temp['where'], $temp['condition']);
            }
        }

        if(isset($_GET['status'])){
            $temp['status'] = (int)$this->input->get('status');
            $temp['where']['where']     = array('status'=>$temp['status']);
        }

        $data = $this->c->show_page(self::apply, $temp['where']);

        $data['status']=isset($temp['status'])?$temp['status']:'';

        unset($temp);
        return $data;
    }

    /**
     * 获取借款申请详情
     *
     * @access public
     * @return array
     */

    public function get_apply_detail()
    {
        $data = $temp = array();

        $temp['apply_no'] = $this->input->get('apply_no');

        if( ! empty($temp['apply_no']))
        {
            $temp['where'] = array('where' => array('apply_no' => $temp['apply_no']));
            $data = $this->c->get_row(self::apply, $temp['where']);
        }

        unset($temp);
        return $data;
    }
}