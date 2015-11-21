<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 银行卡管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Authen_model extends CI_Model
{
	const card = 'user_card'; // 银行卡
	const user = 'user'; // 用户表
	
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
    }
    /**
     * 获取列表
     *
     * @access public
     * @return array
     */

    public function authen()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array('where' => array('clientkind' => "-1"));


        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'real_name', 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::user, $temp['where']);

        unset($temp);
        return $data;
    }
	 /**
     * 个人审核
     *
     * @access public
     * @return array
     */

    public function finish()
    {
        $data = $temp = array();

        $temp['uid'] = $this->input->get('uid');

        if( ! empty($temp['uid']))
        {

			$temp['where'] = array('where' => array('uid' => $temp['uid']));
            $user = $this->c->get_row(self::user, $temp['where']);

			$res = $this->pay->sfrz($user['firmid'],$user['real_name'],$user['nric']);
			if($res['ReturnInfo']['RtnCode']=="000000"){
				$temp['data']  = array('clientkind' => "1");
				$temp['where'] = array('where' => array('uid' =>  $temp['uid']));
				$query = $this->c->update(self::user, $temp['where'], $temp['data']);
			}else{
				$temp['data']  = array('clientkind' => "-3");
				$temp['where'] = array('where' => array('uid' =>  $temp['uid']));
				$query = $this->c->update(self::user, $temp['uid'], $temp['uid']);
				
			}

        
		}

        // unset($temp);
        // return $data;
    }

}