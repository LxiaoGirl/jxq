<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_wish_model extends CI_Model{
    const wish  	  = 'activity_wish';     //愿望表
    const wish_log   = 'activity_wish_log'; //愿望 帮助记录表
    const user	      = 'user'; 			 //用户表
	const wish_limit = 1; 					 //每个人的愿望限制个数
	const help_limit = 2; 					 //每个人的帮助限制次数
	const help_success_odds = 80; 			 //每个人的帮助成功几率

    public function __construct(){
        parent::__construct();
    }

	/**
	 * 许愿【添加愿望】
	 * @param int $uid
	 * @param int $wish_type
	 * @param string $wish_name
	 * @param string $openid
	 * @return array
	 */
	public function set_wish($uid=0,$wish_type=1,$wish_name='',$openid=''){
		$data = array('name'=>'许愿','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$uid){
			$data['msg'] = '用户uid为空!';
			return $data;
		}
		if( !$wish_name){
			$data['msg'] = '愿望名称为空!';
			return $data;
		}
		if( !$openid){
			$data['msg'] = '微信openid为空!';
			return $data;
		}
		//验证uid是否存在
		$temp['uid_exists'] = $this->c->count(self::user,array('where'=>array('uid'=>$uid)));
		if($temp['uid_exists'] == 0){
			$data['msg'] = '当前用户信息不存在!';
			return $data;
		}
		//验证已许愿个数
		$temp['wish_count'] = $this->c->count(self::wish,array('where'=>array('uid'=>$uid)));
		if($temp['wish_count'] >= self::wish_limit){
			$data['msg'] = '每人可许愿'.self::wish_limit.'次，你已经许过了!';
			$data['status'] = '10002';
			return $data;
		}
		//添加愿望
		$temp['data'] = array(
			'uid' 		=> $uid,
			'wish_type' => $wish_type,
			'wish_name' => $wish_name,
			'openid' 	=> $openid,
			'add_time' 	=> time()
		);
		$temp['query'] = $this->c->insert(self::wish,$temp['data']);
		if($temp['query']){
			$data['msg'] 	= '许愿成功!';
			$data['status'] = '10000';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取愿望信息
	 * @param int $wish_id
	 * @param int $uid
	 * @return array
	 */
	public function get_wish($wish_id=0,$uid=0){
		$data = array('name'=>'愿望信息','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$uid && !$wish_id){
			$data['msg'] = '用户uid或愿望id为空!';
			return $data;
		}
		$temp['filed'] = 'uid';
		$temp['value'] = $uid;
		if($wish_id){
			$temp['filed'] = 'wish_id';
			$temp['value'] = $wish_id;
		}

		$temp['data'] = $this->c->get_row(self::wish,array('where'=>array($temp['filed']=>$temp['value'])));
		if($temp['data']){
			$data['data'] = $temp['data'];
			$data['msg']  = 'ok!';
			$data['status']  = '10000';
		}else{
			$data['msg'] = '暂无相关信息!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 添加愿望帮助记录
	 * @param int $wish_id
	 * @param string $weixin_name
	 * @param string $weixin_avatar
	 * @param string $openid
	 * @param string $description
	 * @return array
	 */
	public function set_wish_log($wish_id=0,$weixin_name='',$weixin_avatar='',$openid='',$description=''){
		$data = array('name'=>'添加愿望帮助记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$wish_id){
			$data['msg'] = '愿望id为空!';
			return $data;
		}
		if( !$weixin_name){
			$data['msg'] = '微信昵称为空!';
			return $data;
		}
		if( !$weixin_avatar){
			$data['msg'] = '微信头像链接为空!';
			return $data;
		}
		if( !$description){
			$data['msg'] = '记录描述为空!';
			return $data;
		}
		if( !$openid){
			$data['msg'] = '微信openid为空!';
			return $data;
		}
		//验证愿望id是否存在
		$temp['wish_id_exists'] = $this->c->count(self::wish,array('where'=>array('wish_id'=>$wish_id)));
		if($temp['wish_id_exists'] == 0){
			$data['msg'] = '当前愿望信息不存在!';
			return $data;
		}
		//验证已帮助次数数
		$temp['help_count'] = $this->c->count(self::wish_log,array('where'=>array('wish_id'=>$wish_id,'openid'=>$openid)));
		if($temp['wish_count'] >= self::help_limit){
			$data['msg']    = '每人可帮助'.self::help_limit.'次，你已经帮助过了!';
			$data['status'] = '10002';
			return $data;
		}
		//获取帮助成功失败信息
		$temp['help_result'] = $this->_get_help_result();

		//添加愿望帮助记录
		$temp['data'] = array(
			'wish_id' 		=> $wish_id,
			'weixin_name' 	=> $weixin_name,
			'weixin_avatar' => $weixin_avatar,
			'openid' 		=> $openid,
			'description' 	=> $description,
			'remarks' 		=> $temp['help_result']['num'],
			'status' 		=> $temp['help_result']['result'],
			'add_time' 		=> time()
		);
		$this->db->trans_start();

		$this->c->insert(self::wish_log,$temp['data']);
		if($temp['help_result']['result'] == 1){
			$this->c->set(self::wish,array('where'=>array('wish_id'=>$wish_id)),array('field'=>'ranking_value','value'=>'`ranking_value`+1'));
		}
		$this->db->trans_complete();
		$temp['query'] = $this->db->trans_status();

		if($temp['query']){
			$data['msg'] 	= '帮助成功!';
			$data['status'] = '10000';
			$data['data']['ranking'] = (int)$this->get_wish_ranking($wish_id)['data'];
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取愿望帮助记录
	 * @param int $wish_id
	 * @param int $status
	 * @param int $page_id
	 * @param int $page_size
	 * @return array
	 */
	public function get_wish_log($wish_id=0,$status=1,$page_id=1,$page_size=0){
		$data = array('name'=>'获取愿望帮助记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$wish_id){
			$data['msg'] = '愿望id为空!';
			return $data;
		}
		$this->_set_cutpage_params($page_id,$page_size);

		$temp['where'] = array(
			'where' => array(
				'wish_id' => $wish_id,
				'status'  => $status
			)
		);
		$temp['data'] = $this->c->show_page(self::wish_log,$temp['where']);

		unset($temp['data']['links']);
		$data['data'] = $temp['data'];
		if($temp['data']['data']){
			$data['status'] = '10000';
			$data['msg'] 	= 'ok!';
		}else{
			$data['msg'] = '暂无相关信息!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取愿望排名
	 * @param int $wish_id
	 * @return array
	 */
	public function get_wish_ranking($wish_id=0){
		$data = array('name'=>'获取愿望排名','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$wish_id){
			$data['msg'] = '愿望id为空!';
			return $data;
		}

		$temp['ranking_value'] = $this->c->get_one(self::wish,array('select'=>'rank_value','where'=>array('wish_id'=>$wish_id)));
		$temp['ranking'] = (int)$this->c->get_one(self::wish,array('select'=>'COUNT(*)+1','where'=>array('ranking_value >'=>$temp['ranking_value'])));

		$data['status'] = '10000';
		$data['data']   = $temp['ranking'];
		$data['msg']    = 'ok!';

		unset($temp);
		return $data;
	}

	/**
	 * 设置修正分页的参数
	 * @param int $page_id
	 * @param int $page_size
	 */
	protected function _set_cutpage_params($page_id=0,$page_size=0){
		if(isset($_GET['limit']) && !$page_size){
			$page_size = (int)$this->input->get('limit');
		}else{
			if( !$page_size || !is_numeric($page_size))$page_size = $this->_page_size;
			$_GET['limit'] = (int)$page_size;
		}

		if( !isset($_GET['per_page'])){
			if(!is_numeric($page_id) || $page_id<=0)$page_id=1;
			$_GET['per_page'] = (((int)$page_id-1)*(int)$page_size);
		}
	}

	protected function _get_help_result(){
		$num = rand(0,100);
		if($num < self::help_success_odds){
			$query = 1;
		}else{
			$query = 0;
		}

		return array('num'=>$num,'result'=>$query);
	}

}