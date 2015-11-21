<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/5
 * Time: 21:54
 */
class message extends Login_Controller{
	const message = 'message';

	/**
	 * 头部 获取 未读信息的ajax处理方法
	 */
	public function ajax_get_not_read_message_count(){
		$data = array('status'=>'10000','msg'=>'','data'=>array('counts'=>0));
		$data['data']['counts'] = (int)$this->c->count(self::message,array('where'=>array('uid'=>$this->session->userdata('uid'),'read_time'=>0)));
		exit(json_encode($data));
	}
}