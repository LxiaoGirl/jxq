<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 活动类 之 新年愿望
 * Class Activity_wish_model
 */
class Activity_wish_model extends CI_Model{
    const wish  	 = 'activity_wish';      //愿望表
    const wish_log   = 'activity_wish_log';  //愿望 帮助记录表
    const user	     = 'user'; 			 	 //用户表
	const wish_limit = 1; 					 //每个人的愿望限制个数
	const help_limit = 2; 					 //每个人的帮助限制次数
	const help_success_odds = 80; 			 //每个人的帮助成功几率
	//愿望
	protected $_wish = array(
		1=>'投资返现红包-50元',
		2=>'注册祝福红包-20元',
	);
	//助力成功的描述语
	protected $_description_success = array(
		'这个才是正宗的摸金范儿，帮你的新年愿望助了力哦！【Ta的排名咻一下上升了%d位】',
		'那就让他们见识见识摸金校尉的手段，帮你的新年愿望助了力哦~~【Ta的排名蹭蹭蹭上升了%d位】',
		'么么哒，简直是中国好朋友，我可是帮你助力成功了哦！',
		'跟着我左手右手一个慢动作，手快给朋友，帮你的新年愿望助了力哦~~',
		'如果新年没有收到我的礼物，请不要怀疑我们的感情，我有帮你的新年愿望助力了哦！',
		'只能爱你you are my superstar，给朋友新年愿望助了力哦！'
	);
	//助力失败的描述语句
	protected $_description_fail = array(
		'你是猴子请来的逗逼，来一圈啥也没帮上',
		'狗带，真心不是故意的，这次没成功，我会再接再厉哒',
		'损的就是你，不要再怀疑，就是没有帮你助力哦',
		'静悄悄的来，不带走一片云彩，丝毫没有给朋友助力',
		'世界这么大，认识你真不幸，竟然没有给我助力'
	);

	const wish_start_time = '2016-01-05 10:00:00';//许愿开始时间
	const wish_end_time	  = '2016-02-04 23:59:59';//许愿结束时间
	const help_start_time = '2016-01-05 10:00:00';//助力开始时间
	const help_end_time   = '2016-02-04 23:59:59';//助力结束时间

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
			$data['msg'] = '登录超时,请重新登录!';
			return $data;
		}
		if( !$wish_name){
			$data['msg'] = '愿望名称为空!';
			return $data;
		}
		if( !$openid){
			$data['msg'] = '数据异常,微信授权openid获取失败!';
			return $data;
		}
		//验证uid是否存在
		$temp['uid_exists'] = $this->c->count(self::user,array('where'=>array('uid'=>$uid)));
		if( !$temp['uid_exists']){
			$data['msg'] = '数据异常,未匹配到当前用户信息!';
			return $data;
		}
		//验证起始时间
		if(time() < strtotime(self::wish_start_time)){$data['msg'] = '许愿活动尚未开始,请稍后!';return $data;}
		if(time() > strtotime(self::wish_end_time)){$data['msg'] = '许愿活动已结束,谢谢参与!';return $data;}

		//验证愿望id
		if( !isset($this->_wish[$wish_type]) || $this->_wish[$wish_type] != $wish_name){
			$data['msg'] = '数据异常,未匹配到该类型愿望!';
			return $data;
		}
		//验证已许愿个数
		$temp['wish_all'] = $this->c->get_all(self::wish,array('where'=>array('uid'=>$uid)));
		if(count($temp['wish_all']) >= self::wish_limit){
			$data['msg'] 	= '每人可许愿'.self::wish_limit.'次，你已经许过愿望了!';
			$data['status'] = '10002';
			$data['data'] 	= $temp['wish_all'];
			return $data;
		}

		if($wish_type == 1){
			$temp['is_invested'] = $this->c->count('borrow_payment',array('where'=>array('uid'=>$uid,'type'=>1)));
			if( !$temp['is_invested']){
				$data['msg'] = '你尚未投资不能选择改类型的愿望哦!';
				return $data;
			}
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
			$temp['data']['wish_id'] = $temp['query'];
			$data['data']   = $temp['data'];
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

		$temp['where'] = array(
			'where'  => array(join_field($temp['filed'],self::wish)=>$temp['value']),
			'select' => join_field('*',self::wish).','.join_field('real_name,inviter_no',self::user),
			'join'	 => array(
				'table' => self::user,
				'where' => join_field('uid',self::wish).'='.join_field('uid',self::user)
			)
		);
		$temp['data'] = $this->c->get_row(self::wish,$temp['where']);
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
	 * @return array
	 */
	public function set_wish_log($wish_id=0,$weixin_name='',$weixin_avatar='',$openid=''){
		$data = array('name'=>'添加愿望帮助记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if( !$wish_id){
			$data['msg'] = '愿望id为空!';
			return $data;
		}
		if( !$weixin_name){
			$data['msg'] = '数据异常,微信授权昵称获取失败!';
			return $data;
		}
		if( !$weixin_avatar){
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
			$data['msg'] = '分享给好友吧,自己不能帮自己!';
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
					'openid'	  => $openid,
					'add_time >=' => strtotime(date('Y-m-d').' 00:00:00'),
					'add_time <=' => time()
				)
			)
		);
		if($temp['help_count'] >= self::help_limit){
			$data['msg']    = '每人每天可帮助'.self::help_limit.'次，你已经帮助过了!';
			$data['status'] = '10002';
			$data['data']['have_count'] = 0;
			return $data;
		}
		$temp['have_count'] = self::help_limit-$temp['help_count'];

		//获取帮助成功失败信息
		$temp['help_result'] = $this->_get_help_result();
		$temp['now_ranking'] = (int)$this->get_wish_ranking($wish_id)['data'];

		$this->db->trans_start();

		//帮助成功 获取新排名和构建随机话术
		if($temp['help_result']['result'] == 1){
			$this->c->set(
				self::wish,
				array('where'=>array('wish_id'=>$wish_id)),
				array('field'=>'ranking_value','value'=>'`ranking_value`+1')
			);
			$temp['new_ranking'] = (int)$this->get_wish_ranking($wish_id)['data'];
			$temp['description'] = $this->_get_help_description($temp['now_ranking'],$temp['new_ranking']);
		}else{
			$temp['description'] = $this->_get_help_description();;
		}

		//添加愿望帮助记录
		$temp['data'] = array(
			'wish_id' 		=> $wish_id,
			'weixin_name' 	=> $weixin_name,
			'weixin_avatar' => $weixin_avatar,
			'openid' 		=> $openid,
			'description' 	=> $temp['description'],
			'remarks' 		=> $temp['help_result']['num'],
			'status' 		=> $temp['help_result']['result'],
			'add_time' 		=> time()
		);

		$this->c->insert(self::wish_log,$temp['data']);

		$this->db->trans_complete();
		$temp['query'] = $this->db->trans_status();

		if($temp['query']){
			$temp['have_count'] -= 1;
			$data['data']['have_count'] = $temp['have_count'];
			$data['msg'] 	= $temp['description'];

			if($temp['help_result']['result'] == 1){
				$data['status'] = '10000';
			}else{
				$data['status'] = '10002';
			}

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

	/**
	 * 获取帮助结果  随机一个1-100的数字 验证80%几率
	 * @return array
	 */
	protected function _get_help_result(){
		$num = rand(0,100);
		if($num < self::help_success_odds){
			$query = 1;
		}else{
			$query = 0;
		}

		return array('num'=>$num,'result'=>$query);
	}

	/**
	 * 帮助成功获取提示性话语
	 * @param int $now_ranking
	 * @param int $new_ranking
	 * @return string
	 */
	protected function _get_help_description($now_ranking=0,$new_ranking=0){
		if($now_ranking && $new_ranking){
			if($new_ranking > $now_ranking){
				$str = sprintf($this->_description_success[array_rand($this->_description_success)],$now_ranking-$now_ranking);
			}else{
				$str = $this->_description_success[rand(2,count($this->_description_success))];
			}
		}else{
			$str = $this->_description_fail[array_rand($this->_description_fail)];
		}

		return $str;
	}

}