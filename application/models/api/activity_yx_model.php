<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 2016年元宵节活动model类
 * Class Activity_yx_model
 */
class Activity_yx_model extends CI_Model{
    const wish  	 = 'activity_wish';      //愿望表
    const wish_log  = 'activity_wish_log';  //愿望 帮助记录表
    const user	     = 'user'; 			 	 //用户表
	//愿望
	const wish_type = 3;
	const wish_name = '元宵请客';

	const wish_start_time = '2016-02-17 10:00:00';//许愿开始时间
	const wish_end_time	   = '2016-02-26 23:59:59';//许愿结束时间
	const help_start_time = '2016-02-17 10:00:00';//其他用户参与开始时间
	const help_end_time   = '2016-02-26 23:59:59';//其他用户参与结束时间

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
	public function set_wish($openid=''){
		$data = array('name'=>'许愿','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$openid){
			$data['msg'] = '数据异常,微信授权openid获取失败!';
			return $data;
		}
		//验证起始时间
		if(time() < strtotime(self::wish_start_time)){$data['msg'] = '许愿活动尚未开始,请稍后!';return $data;}
		if(time() > strtotime(self::wish_end_time)){$data['msg'] = '许愿活动已结束,谢谢参与!';return $data;}

		//验证已许愿个数
		$temp['wish_id'] = $this->c->get_one(self::wish,array('where'=>array('openid'=>$openid),'select'=>'wish_id'));
		if($temp['wish_id'] > 0){
			$data['msg'] 	= 'ok!';
			$data['status'] = '10000';
			$temp['data']['wish_id'] = $temp['wish_id'];
			return $data;
		}

		//添加愿望
		$temp['data'] = array(
			'uid' 		=> 0,
			'wish_type' => self::wish_type,
			'wish_name' => self::wish_name,
			'openid' 	=> $openid,
			'add_time' 	=> time()
		);
		$temp['query'] = $this->c->insert(self::wish,$temp['data']);
		if($temp['query']){
			$data['msg'] 	= '请客成功!';
			$data['status'] = '10000';
			$temp['data']['wish_id'] = $temp['query'];
			$data['data']   = $temp['data'];
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取愿望信息
	 * @param string $wish_id
	 * @return array
	 */
	public function get_wish($wish_id=''){
		$data = array('name'=>'愿望信息','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if($wish_id){
			$temp['where'] = array(
				'where'  => array('wish_id'=>$wish_id)
			);
			$temp['data'] = $this->c->get_row(self::wish,$temp['where']);
			if($temp['data']){
				$data['data'] = $temp['data'];
				$data['msg']  = 'ok!';
				$data['status']  = '10000';
			}else{
				$data['msg'] = '暂无相关信息!';
			}
		}


		unset($temp);
		return $data;
	}

	/**
	 * 添加愿望帮助记录
	 * @param int $wish_id
	 * @param string $wx_name
	 * @param string $wx_avatar
	 * @param string $openid
	 * @return array
	 */
	public function set_wish_log($wish_id=0,$wx_name='',$wx_avatar='',$openid=''){
		$data = array('name'=>'添加愿望帮助记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$wish_id){
			$data['msg'] = '愿望id为空!';
			return $data;
		}
		if( !$wx_name){
			$data['msg'] = '数据异常,微信授权昵称获取失败!';
			return $data;
		}
		if( !$wx_avatar){
			$data['msg'] = '数据异常,微信授权头像获取失败!';
			return $data;
		}
		if( !$openid){
			$data['msg'] = '数据异常,微信授权openid获取失败!';
			return $data;
		}

		//验证愿望id是否存在
		$temp['wish'] = $this->c->get_row(self::wish,array('where'=>array('wish_id'=>$wish_id)));
		if( !$temp['wish']){
			$data['msg'] = '数据异常,当前愿望信息不存在!';
			return $data;
		}
		if($temp['wish']['openid'] == $openid){
			$data['msg'] = '分享给好友吧,自己不能为自己助力!';
			$data['status'] = '10004';
			return $data;
		}

		//验证起始时间
		if(time() < strtotime(self::help_start_time)){$data['msg'] = '助力尚未开始,请稍后!';return $data;}
		if(time() > strtotime(self::help_end_time)){$data['msg'] = '助力已结束,谢谢参与!';return $data;}

		//验证已帮助次数数
		$temp['help_count'] = $this->c->count(self::wish_log,
			array(
				'where'=>array(
					'wish_id'	  => $wish_id,
					'openid'	  => $openid
				)
			)
		);
		if($temp['help_count'] >= 1){
			$data['msg']    = '你已入座!';
			$data['status'] = '10002';
			return $data;
		}

		$this->db->trans_start();

		$this->c->set(
			self::wish,
			array('where'=>array('wish_id'=>$wish_id,'wish_type'=>self::wish_type)),
			array('field'=>'ranking_value','value'=>'`ranking_value`+1')
		);

		$temp['remarks'] = $this->c->get_one(self::wish,array('where'=>array('wish_id'=>$wish_id,'wish_type'=>self::wish_type),'select'=>'ranking_value'));
		//添加愿望帮助记录
		$temp['data'] = array(
			'wish_id' 		=> $wish_id,
			'weixin_name' 	=> $wx_name,
			'weixin_avatar' => $wx_avatar,
			'openid' 		=> $openid,
			'description' 	=> '',
			'remarks' 		=> $temp['remarks']-1,
			'status' 		=> 1,
			'add_time' 		=> time()
		);

		$this->c->insert(self::wish_log,$temp['data']);

		$this->db->trans_complete();
		$temp['query'] = $this->db->trans_status();

		if($temp['query']){
			$data['msg'] 	= 'ok';
			$data['status'] = '10000';
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
				'wish_id' => $wish_id
				//'status'  => $status
			),
			'order_by'=>'add_time DESC'
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

		$temp['ranking_value'] = $this->c->get_one(self::wish,array('select'=>'ranking_value','where'=>array('wish_id'=>$wish_id)));
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
}