<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class User_model
 * user相关处理方法
 */

class User_model extends CI_Model{
	const user    		 = 'user'; 			// 会员表
	const log     		 = 'user_log'; 		// 会员日志表
	const flow     		 = 'cash_flow'; 	// 资金流动记录表
	const borrow   		 = 'borrow'; 		// 借款【项目】表
	const payment       = 'borrow_payment';// 投资、还款收益记录表
	const authcode 		 = 'authcode'; 		// 验证授权【验证码】表
	const message 		 = 'message'; 		// 信息表
	const card 			 = 'user_card'; 	// 用户银行卡表
	const automatic 	 = 'user_automatic';// 自动投配置表
	const company 		 = 'company'; 		// 子公司表
	const recharge 		 = 'user_recharge'; // 充值记录表
	const user_renzheng = 'user_renzheng'; // 用户实名认证之认证表
	const bank 			 = 'bank';			//银行表
	const user_info 	 = 'user_info';		//用户扩展信息表

	/**
	 * User_model constructor.
	 * 构造函数 加载必要的其他model
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('api/common/send_model','send');
		$this->load->model('api/commons_model','common');
	}

	/*******************************登录-注册-忘记密码-公司账号申请-实名认证*******************************************/
	/**
	 * 登录处理
	 * @param string $mobile 手机号/邮箱/用户名
	 * @param string $password 密码
	 * @param string $source 登录端【pc|wap】
	 * @return array
	 */
	public function login($mobile='',$password='',$source='pc'){
		$temp = array();
		$data = array('name'=>'登陆','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());

		if($mobile == ''){
			$data['msg'] = '请输入登录用户名!';
			return $data;
		}
		if($password == ''){
			$data['msg'] = '请输入登录密码!';
			return $data;
		}

		//查询用户信息
		$temp['user'] = $this->_get_user_info($mobile);
		if( ! empty($temp['user'])){
			//验证锁定时间
			$temp['minute'] = ($temp['user']['lock_time'] > time()) ? round(($temp['user']['lock_time'] - time()) / 60) : 0;
			if($temp['minute'] == 0){
				//匹配密码
				$temp['password'] = $this->c->password($password, $temp['user']['hash']);
				if($temp['user']['password'] == $temp['password']){
					$this->_set_login_info($temp['user']['uid']);
					$this->_add_user_log('login', $source.'-会员登录', $temp['user']['uid'], $temp['user']['user_name']);
					$data['status'] = '10000';
					$data['msg'] = '欢迎您的光临!';
					$data['data'] = $temp['user'];
				}else{
					//密码错误 处理错误次数和锁定时间更新
					$temp['where'] = array('where' => array('uid' => $temp['user']['uid']));

					if($temp['user']['error_num'] == 2){
						$temp['data'] = array('lock_time' => time() + 600);
						$this->c->update(self::user, $temp['where'], $temp['data']);
					}else{
						$temp['data'] = array('field' => 'error_num', 'value' => '`error_num` + 1');
						$this->c->set(self::user, $temp['where'], $temp['data']);
					}

					$data['status'] = '10002';
					$data['msg']  = '你输入的用户名和密码不匹配!';
				}
			}else{
				$data['msg'] = '当前登录账号已经锁定，请在'.$temp['minute'].'分钟后再登录!';
			}
		}else{
			$data['msg']  = '你输入的用户名还未注册!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 注册[个人|公司]
	 * @param string $mobile 手机号
	 * @param string $password 密码
	 * @param string $authcode 短信验证
	 * @param string $invite_code 居间人邀请码【只验证和添加居间人邀请码 不提示 错误或没有则存0】
	 * @param string $company_code 公司邀请码/居间人邀请码【居间人邀请码时会同步更新如果有的公司邀请码】
	 * @param bool $type true=公司注册 false=个人注册
	 * @return array
	 */
	public function register($mobile='',$password='',$authcode='',$invite_code='',$company_code='',$type=false){
		$temp = array();
		$data = array('name'=>'注册','status'=>'10001','msg'=>'服务器繁忙请稍后重试!!','data'=>array());

		if( ! $this->_is_mobile($mobile)){
			$data['msg'] = '手机格式不正确!';
			return $data;
		}

		if( ! preg_match("/^[a-zA-Z_0-9]{6,20}$/",$password)){
			$data['msg'] = '请输入6-20位由数字字母下划线组成的密码!';
			return $data;
		}

		if( ! $authcode){
			$data['msg'] = '请输入手机验证码!';
			return $data;
		}

		$temp['is_register'] = $this->is_registered($mobile);
		if($temp['is_register']['status'] != '10000'){
			$data['msg'] = $temp['is_register']['msg'];
			return $data;
		}

		$temp['is_check'] = $this->common->validation_authcode($mobile, $authcode, 'register');//验证手机验证码
		if($temp['is_check']['status'] == '10000'){
			if($company_code){
				$temp['company'] = $this->check_invitation_code($company_code);
				if($temp['company']['status'] != '10000'){
					$data['msg'] = '邀请码错误!';
					return $data;
				}else{
					//有data说明company_code  传的值是居间人code 交换一下两个值
					if($temp['company']['data']){
						$invite_code = $company_code;
						$company_code = $temp['company']['data']['company']?$temp['company']['data']['company']:'';
					}
				}
			}

			$temp['hash']     = random(6, FALSE);
			$temp['password'] = $this->c->password($password, $temp['hash']);

			$temp['data'] = array(
				'user_name'   => $mobile,
				'mobile'      => $mobile,
				'password'    => $temp['password'],
				'security'    => '',
				'hash'        => $temp['hash'],
				'rate'        => $this->config->item('min_rate'), // 最小提成比例
				'inviter'     => $this->_get_inviter_uid($invite_code), // 会员邀请人
				'reg_date'    => time(),
				'reg_ip'      => $_SERVER["REMOTE_ADDR"],
				'last_date'   => 0,
				'last_ip'     => '',
				'clientkind'  =>'-1'
			);
			//如果是企业注册 clientkind值初始为-2
			if($type)$temp['data']['clientkind'] = '-2';
			//如果有company_code 则添加
			if($company_code){
				$temp['data']['company'] = $company_code;
			}

			$temp['where'] = array('where' => array('mobile' => $mobile));
			$temp['info'] = $this->c->get_row(self::user, $temp['where']);

			//如果有记录 但没密码 执行修改操作
			if($temp['info'] && $temp['info']['password'] == ''){
				$query = $this->c->update(self::user, array('where'=>array('uid'=>$temp['info']['uid'])), $temp['data']);
			}
			//没有纪律 执行添加
			if( ! $temp['info']){
				$query = $this->c->insert(self::user, $temp['data']);
			}
			if(!empty($query)){
				$temp['is_bind_inviter'] = $temp['data']['inviter']?true:false;
				$temp['is_bind_company'] = $temp['data']['company']?true:false;
				$temp['where'] = array('where' => array('mobile' => $mobile));
				$temp['data']  = $this->c->get_row(self::user, $temp['where']);
				if( ! empty($temp['data'])){
					$data['status']= '10000';
					$data['msg']= '恭喜你,你的账号已经注册成功！';
					$data['data']= $temp['data'];
					$this->_add_user_log('register','注册',$temp['data']['uid'],$temp['data']['user_name']);
					if($temp['is_bind_inviter'])$this->_add_user_log('invitation-intermediary','理财师邀请码绑定',$temp['data']['uid'],$temp['data']['user_name']);
					if($temp['is_bind_company'])$this->_add_user_log('invitation-company','公司邀请码绑定',$temp['data']['uid'],$temp['data']['user_name']);
				}
			}
		}else{
			$data['msg'] = '你输入的手机验证码不正确或者已过期！';
		}


		unset($temp);
		return $data;
	}

	/**
	 * 手机是否已注册
	 * @param string $mobile
	 * @return array
	 */
	public function is_registered($mobile=''){
		$temp = array();
		$data = array('name'=>'手机是否注册验证','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());

		if($mobile == ''){
			$data['msg'] = '手机号码不能为空!';
			return $data;
		}
		if( !$this->_is_mobile($mobile)){
			$data['msg'] = '手机号码格式不正确!';
			return $data;
		}

		//根据电话查询用户信息是否存在
		$temp['where'] = array('where' => array('mobile' => $mobile),'select'=>'password,uid,mobile,clientkind');
		$temp['info'] = $this->c->get_row(self::user, $temp['where']);
		//有手机记录 而且有密码 视为已注册
		if($temp['info'] && $temp['info']['password']){
			$data['msg'] = '手机号码已经注册！';
			if(in_array($temp['info']['clientkind'],array('-2','-3','-4','-5'))){
				$data['msg'] = '该手机已注册,请先登录提交申请信息!';
				$data['status'] = '10002';
			}
		}else{
			$data['status'] = '10000';
			$data['msg'] = '手机号码可以使用！';
		}

		return $data;
	}

	/**
	 * 邀请码验证【优先查询公司邀请码，如果不是则查询居间人邀请码】
	 * @param string $code 邀请码【公司邀请码|居间人邀请码|居间人电话】
	 * @param boolean $flag =true时查询验证居间人邀请码|=false时只查询验证公司邀请码
	 * @return array
	 */
	public function check_invitation_code($code='',$flag=TRUE){
		$temp = array();
		$data = array('name'=>'邀请码验证','status'=>'10001','msg'=>'邀请码不能为空!','data'=>array());

		if($code != ''){
			$temp['where'] = array(
				'select' => 'id,company_name',
				'where'  => array(
					'company_inviter_no'=>$code,
					'status'			=>1
				),
			);
			$temp['data'] = $this->c->get_row(self::company,$temp['where']);

			if($temp['data']){
				$data['msg'] = $temp['data']['company_name'];
				$data['status'] = '10000';
			}else{
				if($flag){
					if($this->_is_mobile($code)){
						$temp['s_filed'] = 'mobile';
					}else{
						$temp['s_filed'] = 'inviter_no';
					}
					$temp['data'] = $this->c->get_row(self::user,array('select'=>'uid,real_name,company','where'=>array($temp['s_filed']=>$code)));
					if($temp['data']){
						$data['msg'] = '邀请人:'.$temp['data']['real_name'];
						$data['status'] = '10000';
						$data['data'] = $temp['data'];
					}else{
						$data['msg'] = '查无此验证码!';
					}
				}else{
					$data['msg'] = '查无此验证码!';
				}
			}
		}

		unset($temp);
		return $data;
	}

	/**
	 * 忘记密码
	 * @param string $mobile 手机
	 * @param string $authcode 手机验证码
	 * @param string $password 新密码
	 * @return array
	 */
	public function forget($mobile='',$authcode='',$password=''){
		$data = array('name'=>'忘记密码','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		$temp = array();

		if($mobile == ''){
			$data['msg'] = '电话不能为空!';
			return $data;
		}
		if( !$this->_is_mobile($mobile)){
			$data['msg'] = '电话格式不正确!';
			return $data;
		}
		if($authcode == ''){
			$data['msg'] = '电话验证码不能为空!';
			return $data;
		}
		if($password == ''){
			$data['msg'] = '新密码不能为空!';
			return $data;
		}
		//查询用户信息
		$temp['where'] = array(
			'select' => 'hash,uid,user_name,password',
			'where'  => array('mobile' => $mobile)
		);
		$temp['user']  = $this->c->get_row(self::user, $temp['where']);

		if(empty($temp['user'])){
			$data['msg'] = '用户信息不存在!';
			return $data;
		}
		//验证手机验证码
		$temp['authcode_check'] = $this->common->validation_authcode($mobile,$authcode,'forget');
		if($temp['authcode_check']['status'] != '10000'){
			$data['msg'] = $temp['authcode_check']['msg'];
			return $data;
		}
		//验证原密码
		$temp['new_password'] = $this->c->password($password, $temp['user']['hash']);
		if($temp['new_password'] == $temp['user']['password']){
			$data['msg'] = '你可以直接使用当前输入的密码登录，勿需更新!';
			return $data;
		}

		//更新密码
		$temp['where'] = array('where' => array('mobile' => $mobile));
		$temp['data']  = array('password' => $temp['new_password']);
		$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

		if($temp['query']){
			$data['msg'] = '密码修改成功!';
			$data['status'] = '10000';
			$this->_add_user_log('forget','忘记登录密码',$temp['user']['uid'],$temp['user']['user_name']);
		}

		unset($temp);
		return $data;
	}

	/**
	 * 企业账户申请
	 * @param int $uid 用户id
	 * @param array $attachment 相关资料附件
	 * @return array
	 */
	public function company_apply($uid=0){
		$temp = array();
		$data = array('name'=>'企业账户申请','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());

		if( !$uid){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}
		$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
		if( !$temp['user_info']){
			$data['msg'] = '用户不存在!';
			return $data;
		}
		if($temp['user_info']['clientkind'] != '-3' && $temp['user_info']['clientkind'] != '-5'){
			$data['msg'] = '该用户当前状态不能进行企业认证资料提交!';
			return $data;
		}
		$temp['balance'] = $this->_get_user_balance($uid);
		if($temp['balance'] < 300){
//			$data['msg'] = '你的余额不够认证费用请充值后重试!';
//			return $data;
		}
		//处理银行账号信息
		/*$temp['card'] = array();
		if(isset($attachment['company_bank_name'])){
			$temp['card']['company_bank_name'] = $attachment['company_bank_name'];
			unset($attachment['company_bank_name']);
		}
		if(isset($attachment['company_bank_account'])){
			$temp['card']['company_bank_account'] = $attachment['company_bank_account'];
			unset($attachment['company_bank_account']);
		}
		if($temp['card']){
			$this->card_bind($uid,$temp['card']['company_bank_account']);
		}*/
		//冻结申请金额
		$this->db->trans_start();
		$temp['query'] = $this->c->update(self::user,array('where'=>array('uid'=>$uid)),array('clientkind'=>'-4'));
		if($temp['query']){
			$temp['data'] = array(
				'uid'      => $uid,
				'type'     => 12,
				'amount'   => 300,
				'balance'  => round($temp['balance']-300, 2),
				'source'   => '-',
				'remarks'  => '企业账号申请费用',
				'dateline' => time(),
			);
			//$temp['query'] = $this->c->insert(self::flow, $temp['data']);
		}

		$this->db->trans_complete();
		$query = $this->db->trans_status();

		if($query){
			$data['status'] = '10000';
			$data['msg'] = 'ok';
			$data['data'] = $temp['data']['balance'];

			$this->_add_user_log('company_apply', '企业账户申请', $temp['user_info']['uid'], $temp['user_info']['user_name']);
		}

		unset($temp);
		return $data;
	}

	/**
	 * 设置用户扩展信息
	 * @param int $uid 用户id
	 * @param string $type 类型
	 * @param array $attachment 信息数组 一维
	 * @return array
	 */
	public function set_user_extend_info($uid=0,$type='',$attachment=array()){
		$temp = array();
		$data = array('name'=>'设置用户扩展信息','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		if( !$uid){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}
		$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
		if( !$temp['user_info']){
			$data['msg'] = '用户不存在!';
			return $data;
		}
		if( !$attachment){
			$data['msg'] = '提交的资料信息为空!';
			return $data;
		}
		if( !$type){
			$data['msg'] = '请输入存储类型!';
			return $data;
		}
		$temp['insert_data'] = $temp['update_data'] = array();
		foreach($attachment as $k=>$v){
			$temp['info_id'] = $this->c->get_one(self::user_info,array('select'=>'id','where'=>array('uid'=>$uid,'type'=>$type,'key'=>$k)));
			if($temp['info_id'] > 0){
				$temp['update_data'][] = array(
					'id'=>$temp['info_id'],
					'uid'=>$uid,
					'type'=>$type,
					'key'=>$k,
					'value'=>$v,
					'required'=>1
				);
			}else{
				$temp['insert_data'][] = array(
					'uid'=>$uid,
					'type'=>$type,
					'key'=>$k,
					'value'=>$v,
					'required'=>1
				);
			}
		}
		$this->db->trans_start();
		if($temp['insert_data']){
			$this->c->insert(self::user_info,$temp['insert_data']);
		}
		if($temp['update_data']){
			$this->c->update(self::user_info,array('where'=>array('uid'=>$uid,'type'=>$type)),$temp['update_data'],'id');
		}
		$this->db->trans_complete();
		$query = $this->db->trans_status();
		if($query){
			$data['status'] = '10000';
			$data['msg'] = 'ok';
		}else{
			$data['msg'] = '附件信息操作失败!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取用户扩展信息
	 * @param int $uid 用户id
	 * @param string $type 类型
	 * @return array 一维数组
	 */
	public function get_user_extend_info($uid=0,$type=''){
		$temp = array();
		$data = array('name'=>'获取用户扩展信息','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','data'=>array());
		if( !$uid){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}
		$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
		if( !$temp['user_info']){
			$data['msg'] = '用户不存在!';
			return $data;
		}
		if( !$type){
			$data['msg'] = '请输入存储类型!';
			return $data;
		}
		$temp['data'] = $this->c->get_all(self::user_info,array('where'=>array('uid'=>$uid,'type'=>$type)));
		if($temp['data']){
			foreach($temp['data'] as $k=>$v){
				$data['data'][$v['key']] = $v['value'];
			}
			$data['status'] = '10000';
			$data['msg'] = 'ok!';
		}else{
			$data['status'] = '10000';
			$data['msg'] = '没有想过信息!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 实名认证
	 * @param string $real_name
	 * @param string $nric
	 * @param int $uid
	 * @param boolean $type false 个人实名 clientkind 1  true 公司个人实名开户 clientkind
	 * @return array
	 */
	public function real_name($real_name='',$nric='',$uid=0,$type=false){
		$temp = array();
		$data = array('name'=>'实名认证','status' => '10001', 'msg' => '你提交的数据有误,请重试！', 'data' => array());

		//验证必要参数
		if($uid <= 0 || !is_numeric($uid)){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}

		$user_info = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid),'select'=>'clientkind'));
		if( ! $user_info){
			$data['msg'] = '用户uid无效!';
			return $data;
		}
		if($type && $user_info['clientkind'] != '-2'){
			$data['msg'] = '该用户已企业开户认证!';
			$data['status'] = '10000';
			return $data;
		}
		if( !$type && in_array($user_info['clientkind'],array('1','2','-2','-3','-4','-5'))){
			$data['msg'] = '该用户已实名认证或其他类型的认证了!';
			return $data;
		}

		if($real_name == ''){
			$data['msg'] = '真实姓名不能为空!';
			return $data;
		}

		if( ! preg_match("/^[\x7f-\xff]+$/", $real_name) || mb_strlen($real_name) < 2){
			$data['msg'] = '姓名为两个及以上中文汉字!';
			return $data;
		}

		if($nric == ''){
			$data['msg'] = '身份证不能为空!';
			return $data;
		}

		if( ! $this->_is_nric($nric)){
			$data['msg'] = '身份证格式不正确!';
			return $data;
		}

		$nric_exists = $this->c->count(self::user,array('where'=>array('nric'=>$nric)));
		if($nric_exists){
//			$data['msg'] = '该身份证已有用户认证使用过!';
//			return $data;
		}

		//查询已经提交实名认证情况
		$temp['where'] = array(
				'select' => 'uid,isok,code,sex',
				'where'  => array(
						'isok' 		 => "1",
						'code' 		 => "1",
						'nric'  	 => $nric,
						'user_name'  => $real_name,
				)
		);
		$temp['is_check'] = $this->c->get_one(self::user_renzheng, $temp['where']);

		$this->load->library('pay');
		if(empty($temp['is_check'])){
			$shenfen = $this->pay->shenfenyanzheng($real_name,$nric);

			$temp['data'] = array(
					'uid' => $uid,
					'user_name'=> $real_name,
					'nric'     => $nric,
					'isok' 	   => $shenfen['isok'],
					'nric_err' => $shenfen['data']['err'],
					'nric_add' => $shenfen['data']['address'],
					'sex'      => $shenfen['data']['sex'],
					'reg_date' => time(),
					'cert_lock'=> "2",
					'cert_err' => "2",
					'birthday' =>$shenfen['data']['birthday'],
					'code'     => $shenfen['code'],
			);
			$this->c->insert(self::user_renzheng, $temp['data']);
		}else{
			$shenfen['isok']="1";
			$shenfen['code']="1";
		}

		//查询 提交失败次数和锁定时间
		$temp['where'] = array(
				'select' => 'cert_error,cert_lock',
				'where'  => array(
						'uid'  => $uid,
				)
		);
		$temp['cert_error_info'] = $this->c->get_row(self::user, $temp['where']);

		//检测锁定时间
		if($temp['cert_error_info']['cert_lock'] >= time()){
			$limit_time = $temp['cert_error_info']['cert_lock']-time();
			if($limit_time > 3600){
				$limit_time_str = ($limit_time/(3600*24)).'小时'.(($limit_time%(3600*24))/60).'分钟';
			}else{
				$limit_time_str = ($limit_time/60).'分钟';
			}
			$data['msg'] = '你的提交错误次数过多,请在'.$limit_time_str.'后可以再次提交';
		}

		if($shenfen['isok'] == 1){
			switch($shenfen['code']){
				case 1:
					$ceshi = $this->pay->create_user($real_name,$nric);
					if(isset($shenfen['data']['sex']) && $shenfen['data']['sex']=="M"){
						$gender ="1";
					}else{
						$gender ="2";
					}

					$str = $ceshi['FundAcc']['VaccId'];
					if(strlen($str)>13)$str=substr($str,0,8);
					if($str=="30200394"){
						if( ! empty($ceshi['FundAcc']['VaccId'])){//if( ! empty($ceshi['data']['sex']))// if( ! empty($ceshi['FundAcc']['VaccId']))
							$temp['data'] = array(
									'gender' => $gender,
//									'user_name' => $real_name,
									'real_name' => $real_name,
									'nric'      => $nric,
									'firmid' 	=> $ceshi['FundAcc']['FirmId'],
									'vaccid'    => $ceshi['FundAcc']['VaccId'],
									'certtype'  => $ceshi['Client']['CertType'],
									'certdate'  => $ceshi['Client']['CertDate'],
									'bankacc'   => $ceshi['BankAcc']['BankAcc'],
//								'platserial' => $ceshi['BankAcc']['PlatSerial'],
									'clientkind'=> ($type?'-3':'1'),
							);
							$temp['where'] = array('where' => array('uid' => $uid));
							$query = $this->c->update(self::user, $temp['where'], $temp['data']);

							if( ! empty($query)){
								$this->_add_user_log('profile-real_name', '更新个人资料-实名认证！',$uid,$real_name);
								if($temp['data']['clientkind'] == "-3" || $temp['data']['clientkind']=="1"){
									$data['msg']   = '你的认证资料已经提交!';
									$data['status']   = '10000';
									$data['data'] = $temp['data'];
								}
							}else{
								$data['msg']   = '服务器繁忙请稍后重试!';
							}
						}else{
							$data['msg']   = '银行线路繁忙，请稍后提交信息!';
						}
					}else{
						$this->_add_user_log('profile-real_name-fail', '更新个人资料-实名认证-开户失败！',$uid,$real_name);
						$data['msg']   = '银行线路繁忙，请稍后提交信息!';
					}
					break;
				case 2:
					$temp['cert_error_info']['cert_error'] +=1;
					switch ($temp['cert_error_info']['cert_error']){
						case '1':
							$time = "30秒";
							$temp['cert_lock']=time()+30;
							break;
						case '2':
							$time = "30分钟";
							$temp['cert_lock']=time()+30*60;
							break;
						case '3':
							$time = "24小时";
							$temp['cert_lock']=time()+60*60*24;
							break;
						default:
							$time = (($temp['cert_error_info']['cert_error']-2) *24)."小时";
							$temp['cert_lock']=time()+60*60*24*($temp['cert_error_info']['cert_error']-2);
					}
					$temp['data'] = array('cert_error'  => $temp['cert_error_info']['cert_error'],'cert_lock'  => $temp['cert_lock']);
					$temp['where'] = array('where' => array('uid' => $uid));
					$query = $this->c->update(self::user, $temp['where'], $temp['data']);
					$data['msg'] = '身份证姓名号码不一致，请仔细填写，锁定'.$time.'后可以再次提交';
					break;
				case 3:
					$temp['cert_error_info']['cert_error'] +=1;
					switch ($temp['cert_error_info']['cert_error']){
						case '1':
							$time = "30秒";
							$temp['cert_lock']=time()+30;
							break;
						case '2':
							$time = "30分钟";
							$temp['cert_lock']=time()+30*60;
							break;
						case '3':
							$time = "24小时";
							$temp['cert_lock']=time()+60*60*24;
							break;
						default:
							$time = (($temp['cert_error_info']['cert_error']-2) *24)."小时";
							$temp['cert_lock']=time()+60*60*24*($temp['cert_error_info']['cert_error']-2);
					}
					$temp['data'] = array('cert_error'  => $temp['cert_error_info']['cert_error'],'cert_lock'  => $temp['cert_lock']);
					$temp['where'] = array('where' => array('uid' => $uid));
					$query = $this->c->update(self::user, $temp['where'], $temp['data']);
					$data['msg'] = '无此身份证号码，请仔细填写，锁定'.$time.'后可以再次提交';
					break;
				case 50:
					$data['msg']   = '身份证号码无效!';
					break;
				default:
			}
		}

		unset($temp);
		return $data;
	}


	/*******************************【居间人|公司】邀请码绑定**********************************************************/
	/**
	 * 公司邀请码绑定
	 * @param int $uid
	 * @param string $code
	 * @return array
	 */
	public function company_invitation_code_bind($uid=0,$code=''){
		$temp = array();
		$data = array('name'=>'绑定公司邀请码','status' => '10001', 'msg' => '你提交的公司邀请码不存在,请重试！', 'data' => array());

		if($code){
			//验证用户信息
			if($uid == 0){
				$data['msg'] = '登陆超时,请重新登陆!';
				return $data;
			}
			$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
			if(!$temp['user_info']){
				$data['msg'] = '用户uid不存在!';
				return $data;
			}
			if($temp['user_info']['company']){
				$data['msg'] = '你已经绑定过公司邀请码了无需重复提交!';
				return $data;
			}

			//验证公司邀请码
			$temp['data'] = $this->check_invitation_code($code,false);
			if($temp['data']['status'] == '10000'){
				$temp['inviter_uid'] = '';//邀请人uid
				//如果存在data 说明code是居间人邀请码
				/*if($temp['data']['data']){
					$temp['inviter_uid'] = $temp['data']['data']['uid'];
					//如果穿着company 说明居间人有公司邀请码 无则没有
					if($temp['data']['data']['company']){
						$code = $temp['data']['data']['company'];
					}else{
						$data['msg'] = '当前理财师也无公司邀请码!';
						$code = '';
					}
				}*/
				//根据上面的过滤再验证是否还有公司邀请码
				if($code){
					//有居间人uid inviter_uid 验证邀请人uid 是否和当前用户的邀请人是否相同
					if($temp['inviter_uid'] && $temp['user_info']['inviter'] && $temp['inviter_uid']!=$temp['user_info']['inviter']){
						$data['msg'] = '当前邀请码与本身理财师邀请码不同!';
						return $data;
					}
					$temp['update_data'] = array(
							'company'=>$code
					);
					//如果有居间人uid 而本身无inviter 则保存
					if($temp['inviter_uid'] && !$temp['user_info']['inviter']){
						$temp['update_data']['inviter'] = $temp['inviter_uid'];
					}
					$query = $this->c->update(self::user,array('where'=>array('uid'=>$uid)),$temp['update_data']);
					if($query){
						$data['msg'] = '操作成功!';
						$data['status'] = '10000';
						$data['data']['company_code'] = $code;
						$this->_add_user_log('invitation-company','公司邀请码绑定',$temp['user_info']['uid'],$temp['user_info']['user_name']);
					}else{
						$data['msg'] = '服务器繁忙请稍后重试!';
					}
				}
			}
		}

		unset($temp);
		return $data;
	}

	/**
	 * 理财师[居间人]邀请码绑定
	 * @param int $uid
	 * @param string $code
	 * @return array
	 */
	public function intermediary_invitation_code_bind($uid=0,$code=''){
		$temp = array();
		$data = array('name'=>'绑定理财师邀请码','status' => '10001', 'msg' => '你提交的理财师邀请码不存在,请重试！', 'data' => array());

		if($code){
			//验证用户信息
			if($uid == 0){
				$data['msg'] = '登陆超时,请重新登陆!';
				return $data;
			}
			$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
			if(!$temp['user_info']){
				$data['msg'] = '用户uid不存在!';
				return $data;
			}
			if($temp['user_info']['inviter']){
				$data['msg'] = '你已经绑定过理财师邀请码了无需重复提交!';
				return $data;
			}
			//验证理财师邀请码
			if($this->_is_mobile($code)){
				$temp['inviter_select_field'] = 'mobile';
			}else{
				$temp['inviter_select_field'] = 'inviter_no';
			}
			$temp['inviter_info'] = $this->c->get_row(self::user,array('select'=>'uid,inviter_no','where'=>array($temp['inviter_select_field']=>$code)));
			if($temp['inviter_info']){
				//如果是电话查询 而inviter_no 不存在 这返回
				if($temp['inviter_select_field'] == 'mobile' && !$temp['inviter_info']['inviter_no']){
					$data['msg'] = '该用户不是理财师!';
					return $data;
				}
				if($temp['inviter_info']['uid'] == $uid){
					$data['msg'] = '不能添加自己为自己的理财师!';
					return $data;
				}
				//如果自己本身理财师 验证邀请码是否为自己的推荐关系的无限极下线人员
				if($temp['user_info']['inviter_no']){
					$temp['is_my_child'] = $this->_in_my_invite_relation($temp['inviter_info']['inviter_no'],$uid,$temp['user_info']['inviter_no']);
					if($temp['is_my_child']){
						$data['msg'] = '不能添加自己下线理财师为自己的理财师!';
						return $data;
					}
				}

				$temp['inviter_uid'] = $temp['inviter_info']['uid'];
				$code = $temp['inviter_info']['inviter_no'];
			}else{
				$temp['inviter_uid'] = 0;
			}

			if($temp['inviter_uid']){
					$temp['update_data'] = array(
							'inviter'=>$temp['inviter_uid']
					);
					$query = $this->c->update(self::user,array('where'=>array('uid'=>$uid)),$temp['update_data']);
					if($query){
						$data['msg'] = '操作成功!';
						$data['status'] = '10000';
						$data['data']['lcs_code'] = $code;
						$this->_add_user_log('invitation-intermediary','理财师邀请码绑定',$temp['user_info']['uid'],$temp['user_info']['user_name']);
					}else{
						$data['msg'] = '服务器繁忙请稍后重试!';
					}
			}
		}

		unset($temp);
		return $data;
	}


	/*******************************PC版充值订单刷新*******************************************************************/
	/**
	 * 刷新订单[查询凯塔充值结果并更新余额信息]
	 * @param string $recharge_no
	 * @param int $uid
	 * @return array
	 * 10000 订单已成功 10001 未成功 10002 uid为空 10003 订单号为空
	 */
	public function recharge_refresh($recharge_no='',$uid=0){
		$data = array('name'=>'订单号刷新','status'=>'10001','msg'=>'订单未成功!','data'=>'');
		$temp =array();
		$recharge_no = authcode($recharge_no,'',TRUE);

		if($uid == 0){
			$data['msg'] = '登陆超时,请重新登陆!';
			$data['status'] = '10002';
			return $data;
		}
		if($recharge_no){
			session_write_close();//關閉session 防止session鎖頁面
			$temp['where'] = array(
				'select'   => 'recharge_no,uid,type,amount,source,remarks,add_time,status',
				'where'    => array('uid' => $uid,'recharge_no' => $recharge_no,'status' => '0')
			);
			$temp['data'] = $this->c->get_row(self::recharge, $temp['where']);
			if($temp['data']){
				if($temp['data']['type'] != '2'){
					$data['status'] = '10000';
					$data['msg'] = '该类型订单不能在此刷新!';
				}else{
					$this->load->model('pay_model','pay');
					$res = $this->pay->dingdanchaxun($recharge_no);
					if($res['FlagInfo']['Flag3']==1 || $res['FlagInfo']['Flag3']==9){
						$temp['update_data'] = array('status' => '1');
						$this->db->trans_start();
						$temp['where'] = array('where' => array('recharge_no' => $temp['data']['recharge_no']));
						$query = $this->c->update(self::recharge, $temp['where'], $temp['update_data']);
						if($query){
							$query=$this->_add_cash_flow($temp['data']['uid'],$temp['data']['amount'],$temp['data']['recharge_no']);
							if($query){
								$this->db->trans_complete();
								$query = $this->db->trans_status();
								if($query){
									$temp['balance'] = $this->_get_user_balance($uid);
									$this->session->set_userdata('balance',$temp['balance']);
									$data['status'] = '10000';
									$data['data'] = $temp['balance'];
									$data['msg'] = 'ok';
								}
							}
						}
					}else{
						$data['msg'] = '改订单尚未充值成功,请稍后重试或联系客服人员!';
						$temp['balance'] = $this->_get_user_balance($uid);
						$data['data'] = $temp['balance'];
					}
				}
			}else{
				$temp['balance'] = $this->_get_user_balance($uid);
				$data['data'] = $temp['balance'];
				$data['status'] = '10000';
				$data['msg'] = '无订单信息!';
			}
		}else{
			$data['status'] = '10003';
			$data['msg'] = '订单号为空!';
		}
		session_start();
		unset($temp);
		return $data;
	}


	/*******************************银行卡绑定、解绑、查看账号、查询绑定的银行卡等相关操作*****************************/
	/**
	 * 绑定银行卡
	 * @param int 		$uid 	 用户uid
	 * @param string 	$account 银行卡号
	 * @param int 		$bank_id 银行卡id
	 * @param string 	$bank_address 银行地址
	 * @return array
	 */
	public function card_bind($uid=0,$account='',$bank_id=0,$bank_address=''){
		$temp = array();
		$data = array('name'=>'绑定银行卡','status' => '10001', 'msg' => '你提交的数据有误,请重试！', 'data' => array());

		if( !$uid){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}

		$temp['real_name']  = $this->c->get_one(self::user, array('select' => 'real_name', 'where'  => array('uid' => $uid)));
		if(empty($temp['real_name'])){
			$data['msg'] = '用户不存在!';
			return $data;
		}
		if( !$bank_id){
			$data['msg'] = '银行卡id为空!';
			return $data;
		}
		$temp['bank_info'] = $this->common->get_bank($bank_id);
		if($temp['bank_info']['status'] == '10000' && $temp['bank_info']['data']){
			$temp['bank_info'] = $temp['bank_info']['data'];
		}else{
			$data['msg'] = '银行卡信息为空!';
			return $data;
		}

		$account = str_replace(' ', '', $account);
		if( !$account || !is_numeric($account)){
			$data['msg'] = '请输入正确格式银行卡账号!';
			return $data;
		}else{
			$temp['card_bin'] = $this->common->get_bankcard_bin($account);
			if($temp['card_bin']['status'] == '10000'){
				if($temp['card_bin']['data']['bank_name'] != $temp['bank_info']['bank_name']){
					$data['msg'] = '当前帐号开户银行名称与选择不对应,请选择正确的银行名称!';
					return $data;
				}
			}else{
				$data['msg'] = $temp['card_bin']['msg'];
				return $data;
			}
		}
		$temp['card_exists'] = $this->get_user_card($uid,$account)['data'];
		if($temp['card_exists']){
			$data['msg'] = '你已绑定了该卡请勿重复绑定!';
			return $data;
		}

		$temp['data'] = array(
			'card_no'   => $this->c->transaction_no(self::card, 'card_no'),
			'uid'       => $uid,
			'real_name' => $temp['real_name'],
			'account'   => $account,
			'bank_id'   => $bank_id,
			'bank_name' => $temp['bank_info']['bank_name'],
			'bankaddr' => $bank_address,
			//'province' => $this->input->post('province', TRUE),
			//'city' => $this->input->post('bankaddr', TRUE),
			'remarks'   => '',
			'dateline'  => time(),
			'status'	=>0
		);

		$query = $this->c->insert(self::card, $temp['data']);

		if( ! empty($query)){
			$this->_add_user_log('card_bind', '添加银行卡',$uid,$temp['real_name']);
			$data['status'] = '10000';
			$data['msg'] = '恭喜，你的银行卡绑定成功!';
			$data['data'] = array(
				'card_no'=>$temp['data']['card_no'],
				'bank_name'=>$temp['data']['bank_name'],
				'real_name'=>$this->_secret($temp['real_name'],2,mb_strlen($temp['real_name'])>2?mb_strlen($temp['real_name'])-2:mb_strlen($temp['real_name'])-1),
				'account'=>$this->_secret($temp['data']['account'],5,strlen($temp['data']['account'])-8)
			);
		}

		unset($temp);
		return $data;
	}

	/**
	 * 验证银行卡是否可自行解绑
	 * @param int $uid
	 * @param string $card_no
	 * @return array
	 */
	public function check_card_unbind_enable($uid=0,$card_no=''){
		$temp = array();
		$data = array('name'=>'验证银行卡是否可自行解绑','status' => '10001', 'msg' => '你提交的数据有误,请重试！', 'data' => array());

		if( !$uid){
			$data['msg'] = '用户uid错误!';
			return $data;
		}
		if( !$card_no){
			$data['msg'] = '银行卡card_no不能为空!';
			return $data;
		}
		$temp['card_info'] = $this->get_user_card($uid,$card_no)['data'];
		if( !$temp['card_info']){
			$data['msg'] = '银行卡信息不存在!';
			return $data;
		}
		$temp['have_recharge_ok'] = $this->c->get_row(self::recharge,array('where'=>array('uid'=>$uid,'bank'=>$temp['card_info']['id'],'status'=>1)));
		if($temp['have_recharge_ok']){
			$data['msg'] = '请致电客服中心申请银行卡解绑!';
			$data['status'] = '10002';
		}else{
			$data['msg'] = '可以自行解绑!';
			$data['status'] = '10000';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 银行卡自行解绑
	 * @param int $uid
	 * @param string $card_no
	 * @param string $security
	 * @return array
	 */
	public function card_unbind($uid=0,$card_no='',$security=''){
		$temp = array();
		$data = array('name'=>'银行卡自行解绑','status' => '10001', 'msg' => '你提交的数据有误,请重试！', 'data' => array());

		if( !$uid){
			$data['msg'] = '用户uid错误!';
			return $data;
		}
		if( !$card_no){
			$data['msg'] = '银行卡card_no不能为空!';
			return $data;
		}
		if( !$security){
			$data['msg'] = '资金密码不能为空!';
			return $data;
		}

		$temp['user_info'] = $this->c->get_row(self::user,array('select'=>'security,hash,uid,user_name','where'=>array('uid'=>$uid)));
		if( !$temp['user_info']){
			$data['msg'] = '用户信息不存在是否没登录哦!!';
			return $data;
		}

		$temp['check_enable'] = $this->check_card_unbind_enable($uid,$card_no);
		if($temp['check_enable']['status'] != '10000'){
			$data['msg'] = $temp['check_enable']['msg'];
			return $data;
		}
		$temp['security'] = $this->c->password($security,$temp['user_info']['hash']);
		if($temp['security'] != $temp['user_info']['security']){
			$data['msg'] = '资金密码错误!';
			return $data;
		}
		$temp['where'] = array('where'=>array('card_no'=>$card_no,'uid'=>$uid));
		$temp['query'] = $this->c->update(self::card,$temp['where'],array('status'=>'-1'));
		if($temp['query']){
			$data['msg'] = '解绑银行卡成功!';
			$data['status'] = '10000';
			$this->_add_user_log('card-unbind','解绑银行卡',$temp['user_info']['uid'],$temp['user_info']['user_name']);
		}else{
			$data['msg'] = '服务器繁忙请稍后重试!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 显示银行卡号[无*]
	 * @param int $uid
	 * @param string $card_no
	 * @param string $authcode
	 * @return array
	 */
	public function card_show_account($uid=0,$card_no='',$authcode=''){
		$temp = array();
		$data = array('name'=>'显示银行卡号','status' => '10001', 'msg' => '你提交的数据有误,请重试！', 'data' => array());

		if( !$uid){
			$data['msg'] = '用户uid错误!';
			return $data;
		}
		$temp['user_info'] = $this->c->get_row(self::user,array('select'=>'mobile','where'=>array('uid'=>$uid)));
		if( !$temp['user_info']){
			$data['msg'] = '用户信息不存在是否没登录哦!!';
			return $data;
		}
		if( !$card_no){
			$data['msg'] = '银行卡card_no不能为空!';
			return $data;
		}
		if( !$authcode){
			$data['msg'] = '短信验证码不能为空!';
			return $data;
		}


		$temp['authcode_check'] = $this->common->validation_authcode($temp['user_info']['mobile'],$authcode,'showcard',$uid);
		if($temp['authcode_check']['status'] != '10000'){
			$data['msg'] = $temp['authcode_check']['msg'];
			return $data;
		}
		$temp['card_info'] = $this->get_user_card($uid,$card_no)['data'];
		if( !$temp['card_info']){
			$data['msg'] = '卡号信息不存在!';
			return $data;
		}
		$data['data'] = $temp['card_info']['account'];
		$data['status'] = '10000';
		$data['msg'] = 'ok!';


		unset($temp);
		return $data;
	}

	/**
	 * 获取用户银行卡信息
	 * @param int $uid 用户uid
	 * @param string $card_no 卡no.或 account
	 * @param bool $all false【默认】=1条|true=多条
	 * @return array
	 */
	public function get_user_card($uid=0,$card_no='',$all=false){
		$data = array('name'=>'获取用户银行卡信息','status'=>'10001','msg'=>'没有相关信息!','data'=>array());
		$temp = array();

		if( !$uid)return $data;
		$temp['where'] = array(
			'select' => join_field('id,card_no,real_name,account,remarks,dateline',self::card).','.join_field('bank_name,code,content,bank_id',self::bank),
			'join'=> array('table' => self::bank,'where'=> join_field('bank_id',self::bank).'='.join_field('bank_id',self::card)),
			'where'  => array(join_field('uid',self::card) => $uid,join_field('status',self::card).' !=' => '-1')
		);
		if($card_no){
			if(substr($card_no,0,1) == 'C'){
				$temp['where']['where'][join_field('card_no',self::card)] = $card_no;
			}else{
				$temp['where']['where'][join_field('account',self::card)] = $card_no;
			}
		}
		$temp['data']  = $all?$this->c->get_all(self::card, $temp['where']):$this->c->get_row(self::card, $temp['where']);

		if( !empty($temp['data'])){
			$data['status'] = '10000';
			$data['msg']    = 'ok!';
			$data['data']   = $temp['data'];
		}

		unset($temp);
		return $data;
	}



	/****************** 密码修改【登录密码、资金密码】、用户名修改、手机解绑 ******************************************/
	/**
	 * 修改登陆密码
	 *$uid 用户uid
	 *$password 原密码
	 *$new_password 新密码
	 *
	 */
	public function Change_login_password($uid=0,$password='',$new_password=''){
		$data = $temp = array();

		$data = array('status' =>'10001', 'msg' => '你提交的数据有误,请重试！', 'url' => '');
		$temp['uid']   =  $uid;

		if( ! empty($temp['uid'])){
			$temp['where'] = array(
					'select' => 'user_name,password,hash,mobile,uid',
					'where'  => array('uid' => $temp['uid'])
			);
			$temp['user']  = $this->c->get_row(self::user, $temp['where']);
			if( ! empty($temp['user'])){
				//验证 今天是否已修改过密码
				$temp['password_today'] = $this->c->count('user_log',array('where'=>array('uid'=>$temp['uid'],'module'=>'password','dateline >='=>strtotime(date('Y-m-d',time()).' 00:00:00'),'dateline <='=>time())));
				if($temp['password_today'] > 0){
					$data['msg'] = '你今天已修改过一次密码了暂不能再修改！';
					return $data;
				}
				$temp['password'] = $password;
				$temp['new_password'] = $new_password;
				$temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);

				if($temp['password'] == $temp['user']['password']){ //比对原始密码
					if(strlen($temp['new_password']) < 6){
						$data['msg'] = '请输入6位及以上新密码';
					}else{
						$temp['new_password'] = $this->c->password($temp['new_password'], $temp['user']['hash']);
						if($temp['new_password'] == $temp['user']['password']){
							$data['msg'] = '你可以直接使用当前输入的密码登录，勿需更新!';
						}else{
							$temp['where'] = array('where' => array('uid' => $temp['uid']));
							$temp['data']  = array('password' => $temp['new_password']);
							$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

							if( ! empty($temp['query'])){
								$data = array(
										'status' => '10000',
										'msg'  => '你的密码修改成功,记得使用新密码登录!',
										'url'  => ''
								);
								$this->_add_user_log('password', '修改登陆密码',$temp['user']['uid'],$temp['user']['user_name']);
							}
						}
					}

				}else{
					$data['msg'] = '原密码错误';
				}
			}
		}else{
			$data['msg'] = '请先登陆！';
			$data['url'] = site_url(self::dir.'home/index');
		}

		unset($temp);
		return $data;
	}

	/**
	 * 重置登陆密码
	 *$uid 用户uid
	 *$password 新密码第一次
	 *$new_password 新密码第二次
	 *$authcode 短信验证码
	 */
	public function Reset_login_password($uid=0,$password='',$authcode=''){
		$data = array('url'=>'','status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();
		$temp['where'] = array(
				'select' => 'mobile,password,hash',
				'where'  => array('uid' => $uid)
		);
		$temp['user']  = $this->c->get_row(self::user, $temp['where']);
		$temp['password'] = $password;
		$temp['authcode'] = $authcode;
		$temp['is_check'] = $this->send->validation_authcode($temp['user']['mobile'], $temp['authcode'], 'password');
		$temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);
		if(strlen($password) < 6){
			$data['msg'] = '请输入6位及以上密码';
		}else{
			if( $temp['is_check']['status']=='10000'){
				$temp['where'] = array('where' => array('uid' => $uid));
				$temp['data']  = array('password' => $temp['password']);
				$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

				if( ! empty($temp['query'])){
					$data = array(
							'status'=>'10000',
							'msg'=>'重置登陆密码成功!'
					);
					$this->_add_user_log('password','重置登录密码',$temp['user']['uid'],$temp['user']['user_name']);
				}else{
					$data['msg']='系统繁忙,请一会再试！';
				}
			}else{
				$data['msg']=$temp['is_check']['msg'];
			}
		}
		unset($temp);
		return $data;
	}

	/**
	 * 修改资金密码
	 *$uid 用户uid
	 *$mobile 手机号
	 *$password 密码
	 *$security_two 第二次输入的资金密码
	 *$authcode 手机验证码
	 *$security_one 第一次输入的资金密码
	 */
	public function update_fund_password($uid=0,$mobile='',$password='',$security='',$security_new='',$authcode=''){
		$data = array('url'=>'','status'=>'10001','msg'=>'输入的数据有误!');
		$temp = array();
		$temp['uid']   =  $uid;
		$temp['password'] = $password;//前台设置就该资金密码是不能为空
		$temp['security'] = $security;//前台设置就该资金密码是不能为空
		$temp['security_new'] = $security_new;//前台设置就该资金密码是不能为空
		$temp['authcode'] = $authcode;
		$temp['is_check'] = $this->send->validation_authcode($mobile, $temp['authcode'],'security');
		$temp['where'] = array(
				'select' => 'user_name,password,hash,mobile,security',
				'where'  => array('uid' => $temp['uid'])
		);
		$temp['user']  = $this->c->get_row(self::user, $temp['where']);
		if(! empty($temp['user'])){
			if(strlen($security_new) < 6){
				$data['msg'] = '请输入6位及以上的资金密码！';
			}else{
				$temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);
				if($temp['password']==$temp['user']['password']){
					if($temp['is_check']['status']=='10000'){
						$temp['security'] = $this->c->password($security, $temp['user']['hash']);
						if($temp['security']==$temp['user']['security']){
							$temp['security_new'] = $this->c->password($temp['security_new'], $temp['user']['hash']);
							if($temp['security_new']!=$temp['security']){
								$temp['data'] = array('security' => $temp['security_new']);
								$temp['where'] = array('where' => array('uid' => $temp['uid']));
								$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);
								if( ! empty($temp['query'])){
									$this->_add_user_log('security', '修改交易密码',$temp['uid'],$temp['user']['user_name']);
									$data = array(
											'status' => '10000',
											'msg'  => '恭喜你交易密码修改成功！',
											'url'  => '',//是否需要跳转
											'data' => array(
													'security' => $temp['security']//存入session security
											)
									);
								}else{
									$data['msg']='系统繁忙！';
								}
							}else{
								$data['status']='10004';
								$data['msg']='资金密码相同步需要修改！';
							}
						}else{
							$data['status']='10004';
							$data['msg']='原资金密码不正确！';
						}
					}else{
						$data['status']='10002';
						$data['msg']=$temp['is_check']['msg'];
					}
				}else{
					$data['status']='10003';
					$data['msg']='登录密码输入错误！';
				}
			}
		}else{
			$data['msg']='非法操作！';
		}
		unset($temp);
		return $data;
	}

	/**
	 * 资金密码(设置资金密码,重置资金密码)
	 *$uid 用户uid
	 *$mobile 手机号
	 *$password 密码
	 *$security_new 第二次输入的资金密码
	 *$authcode 手机验证码
	 *$security 第一次输入的资金密码
	 */
	public function Fund_password($uid=0,$mobile='',$security='',$authcode='',$password = ''){
		$data = array('url'=>'','status'=>'10001','msg'=>'输入的数据有误!');
		$temp = array();
		$temp['uid']   =  $uid;
		$temp['security'] = $security;//前台设置就该资金密码是不能为空
		$temp['authcode'] = $authcode;
		$temp['is_check'] = $this->send->validation_authcode($mobile, $temp['authcode'],'security');
		$temp['where'] = array(
				'select' => 'user_name,password,hash,mobile',
				'where'  => array('uid' => $temp['uid'])
		);
		$temp['user']  = $this->c->get_row(self::user, $temp['where']);
		if(strlen($security) < 6){
			$data['msg'] = '请输入6位及以上的资金密码！';
		}else{
			if(! empty($temp['user'])){
				$temp['password'] = $this->c->password($password, $temp['user']['hash']);
				if($temp['password']==$temp['user']['password']){
					if($temp['is_check']['status']=='10000'){
						$temp['security'] = $this->c->password($temp['security'], $temp['user']['hash']);
						$temp['data'] = array('security' => $temp['security']);
						$temp['where'] = array('where' => array('uid' => $temp['uid']));
						$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);
						if( ! empty($temp['query'])){
							$this->_add_user_log('security', '修改交易密码',$temp['uid'],$temp['user']['user_name']);
							$data = array(
									'status' => '10000',
									'msg'  => '恭喜你交易密码修改成功！',
									'url'  => '',
									'data' => array(
											'security' => $temp['security']//存入session security
									)
							);
						}else{
							$data['msg']='系统繁忙！';
						}
					}else{
						$data['status']='10002';
						$data['msg']=$temp['is_check']['msg'];
					}
				}else{
					$data['status']='10003';
					$data['msg']='登陆密码不正确！';
				}
			}else{
				$data['msg']='非法操作！';
			}

		}
		unset($temp);
		return $data;
	}

	/**
	 * 修改姓名
	 *$name  姓名
	 *$uid 用户uid
	 */
	public function Change_name($name='',$uid=0){
		$data = array('url'=>'','status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();
		$temp['name'] = $name;
		$temp['uid']   =  $uid;

		if(empty($temp['name'])){ $data['msg'] = '昵称不能为空哦~'; return $data;}
		if( ! preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z_0-9\x80-\xff]{4,21}$/',$name)){ $data['msg'] = '用户名建议为中文、字母或下划线开头以中文字母数字下划线组成的5到22位(一个中文为3位)!'; return $data;}
		if($uid == 0){
			$data['msg'] = '登陆超时,请重新登陆!';
			return $data;
		}
		$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
		if( ! $temp['user_info']){
			$data['msg'] = '用户uid不存在!';
			return $data;
		}
		if($name == $temp['user_info']['real_name'] || $name == $temp['user_info']['mobile']){
			$data['msg'] = '用户名只能修改一次，请修改为实名和手机以外的用户名!';
			return $data;
		}
		if($temp['user_info']['user_name'] != $temp['user_info']['real_name'] && $temp['user_info']['user_name'] != $temp['user_info']['mobile']){
			$data['msg'] = '你的用户名已修改过了 暂不能修改了!!';
			return $data;
		}
		$temp['where'] = array('where' => array('user_name' => $temp['name']));
		$temp['count'] = $this->c->get_row(self::user, $temp['where']);

		if( !empty($temp['count'])){
			$data['msg']='用户名已存在！';
			return $data;
		}

		$temp['data'] = array(
				'user_name' => $temp['name']
		);
		$temp['where'] = array('where' => array('uid' => $temp['uid']));
		$query = $this->c->update(self::user, $temp['where'], $temp['data']);
		if( ! empty($query)){
			$data['msg'] = '修改成功';
			$data['status'] = '10000';
			$this->_add_user_log('name','修改用户名',$temp['user_info']['uid'],$temp['user_info']['user_name']);
		}else{
			$data['msg'] = '服务器繁忙,请稍后再试！';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 修改手机第一步
	 *$mobile 手机号
	 *$authcode 手机验证码
	 */
	public function Change_mobile_one($mobile='',$authcode=''){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'数据错误!');
		$temp = array();
		$temp['authcode'] = $authcode;//短信验证
		$temp['is_check'] = $this->send->validation_authcode($mobile, $temp['authcode'], 'unbindphone');//验证是否过期
		if( $temp['is_check']['status']=='10000'){
			$data=array(
					'status'=>'10000',
					'msg'=>'验证码正确！',
					'url'=>'',//跳到第二步
					'data' => array(
							'authcode_new' => $temp['authcode'],//存入session authcode_new
							'mobile' => $mobile
					)
			);
		}else{
			$data['msg']=$temp['is_check']['msg'];
		}
		unset($temp);
		return $data;
	}

	/**
	 * 修改手机第二步
	 *$mobile 手机号
	 *$authcode 手机验证码
	 *$authcode_new session验证码
	 */
	public function Change_mobile_two($mobile='',$authcode='',$authcode_new='',$old_mobile = ''){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'数据错误!');
		$temp = array();
		$temp['mobile'] = $mobile;//手机号
		if(empty($temp['mobile'])) return array('status' =>'10001', 'msg' => '手机号码不能为空！');
		if(! $this->_is_mobile($temp['mobile'])) return array('status' =>'10001', 'msg' => '手机号码格式不正确！');
		$temp['authcode'] = $authcode;//短信验证
		$temp['is_check'] = $this->send->validation_authcode($temp['mobile'], $temp['authcode'], 'bindphone');//验证是否过期
		if(empty($authcode_new)) return array('status' =>'10001','msg' => '请按步骤填写！');
		if( $temp['is_check']['status']=='10000'){
			$temp['user'] = $this->_get_user_info($old_mobile);
			if(! empty($temp['user'])){
				$temp['data'] = array(
						'mobile' => $temp['mobile']
				);
				$temp['where'] = array('where' => array('mobile' => $old_mobile));
				$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);
				if(! empty($temp['query'])){
					$this->_add_user_log('Change_mobile', '修改手机',$temp['user']['uid'],$temp['user']['user_name']);
					$data=array(
							'status'=>'10000',
							'msg'=>'修改手机号码成功!',
							'url'=>''
					);
				}else{
					$data['msg']='系统繁忙，请稍后再试！';
				}
			}else{
				$data['msg']='操作错误！';
			}
		}else{
			$data['msg']=$temp['is_check']['msg'];
		}
		unset($temp);
		return $data;
	}


	/****************** 我的等级 **************************************************************************************/
	/**
	 * 我的等级(未定)
	 */
	public function My_grades($avatar_url=''){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();

		unset($temp);
		return $data;
	}

	/**
	 * 等级明细记录（未定）
	 */
	public function Grade_list($avatar_url=''){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();

		unset($temp);
		return $data;
	}

	/**
	 * 等级领取（未定）
	 */
	public function Level_collection($avatar_url=''){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();

		unset($temp);
		return $data;
	}


	/****************** 消息相关 **************************************************************************************/
	/**
	 * 我的消息
	 *$uid 用户uid
	 */
	public function My_message($uid=0){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'暂无消息!');
		$temp = array();
		$temp['uid'] = $uid;
		$temp['where'] = array(
				'select'   => 'id,subject,content,send_time,status,type',
				'where'    => array('uid' => $temp['uid']),
				'order_by' => 'id desc'
		);
		$data['news'] = $this->c->show_page(self::message, $temp['where'],"",0,10);
		if(!empty($data['news']['data'])){
			$data= array(
					'status' => '10000',
					'msg'=>'ok',
					'data' => $data['news']
			);
		}
		unset($temp);
		return $data;
	}

	/**
	 * 阅读消息
	 *$id  新闻id
	 *$uid  用户uid
	 */
	public function Read_message($id=0,$uid=0){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();
		$temp['uid'] = $uid;
		$temp['id'] = $id;
		$temp['data'] = array(
				'status' => 1
		);
		$temp['where'] = array('where' => array('uid' => $temp['uid'],'id' => $temp['id']));
		$temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);
		if(! empty($temp['query'])){
			$data = array(
					'data'=>array(),
					'status'=>'10000',
					'msg'=>'已阅读!'
			);
		}else{
			$data['msg']='已阅读!';
		}
		unset($temp);
		return $data;
	}

	/****************** 邮箱绑定 **************************************************************************************/
	/**
	 * 绑定邮箱
	 *
	 * @access public
	 * @param  int $uid 用户uid
	 * @param  string $email 用户邮箱
	 * @return integer
	 */
	public function mailbox_binding($uid = 0 , $email = ''){
		$data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		if(empty($email)) return array('status'=>'10001','msg'=>'邮箱不能为空!');
		if($uid!=0){
			$temp['data'] = array(
				'email' => $email
			);
			$temp['where'] = array('where' => array('uid' => $uid));
			$query = $this->c->update(self::user, $temp['where'], $temp['data']);
			if($query){
				$data= array(
					'status' => '10000',
					'msg' => '邮箱绑定成功！',
					'data' => $email
				);
				$user_name = $this->c->get_one(self::user,array('select'=>'user_name','where'=>array('uid'=>$uid)));
				$this->_add_user_log('email','邮箱绑定',$uid,$user_name);
			}else{
				$data['msg'] = '服务器繁忙请稍后重试！';
			}
		}else{
			$data['msg'] = '用户未登录！';
		}
		unset($temp);
		return $data;
	}

	/**
	 * 获取用户信息(个人信息)
	 *
	 * @access public
	 * @param  int $uid 用户uid
	 * @return integer
	 */
	public function _get_user_uid($uid = 0){
		$data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		if($uid!=0){
			$temp['where'] = array('where' => array('uid' => $uid));
			$data['user'] = $this->c->get_row(self::user, $temp['where']);
			if(!empty( $data['user'])){
				if($data['user']['inviter']){
					$data['user']['lcs_no'] = $this->c->get_one(self::user,array('where'=>array('uid'=>$data['user']['inviter']),'select'=>'inviter_no'));
				}
				$data= array(
						'status' => '10000',
						'msg' => 'ok',
						'data' => $data['user']
				);
			}else{
				$data['msg'] = '非法操作！';
			}
		}else{
			$data['msg'] = '用户未登录！';
		}
		unset($temp);
		return $data;
	}



	/****************** 自动投修改 ************************************************************************************/
	/**
	 * 更新自动投资料
	 *$uid 用户uid
	 *$statue 投标状态  0 未开启 1 开启
	 *$mode 投标类型  1 复投  2 定投
	 *$gdpzje 定投配置额度
	 *$group_id  投标配置标的类型 0全部配置
	 *$sy_min  最小收益
	 *$jk_max  最大期限
	 *$pzsj_start 起始日期
	 *$pzsj_end 结束日期
	 */
	public function automatic_update($statue = 0,$uid = 0,$mode = 0,$group_id = 0,$sy_min = 0,$jk_max = 0,$pzsj_start = '',$pzsj_end = '',$gdpzje =0)
	{
		$query = FALSE;
		$temp  = array();
		$temp['uid'] = $uid;
		$temp['statue']=$statue;
		$temp['mode']=$mode;
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		if($temp['statue']==0){
			if( ! empty($temp['uid']))
			{
				$balance=$this->_get_user_balance($temp['uid']);
				if($temp['mode']==2) $temp['pzed']=$gdpzje;else $temp['pzed']=$balance;
				$temp['data'] = array(
						'allamount' => $this->_get_allinvest($temp['uid']),//投资总额
						'uid'  => $temp['uid'],
						'balance'  => $balance,//可用余额
						'dateline' => time(),//操作时间
						'statue'  => '1',//按钮状态（1启动，0关闭）
						'type'    => $group_id,//项目类型（0是全部类型）
						'sy_min' => $sy_min,//收益最小
						'jk_max'      => $jk_max,//期限最大
						'pzsj_start'     => strtotime($pzsj_start),//配置期限开始
						'pzsj_end'     => strtotime($pzsj_end),//配置期限结束
						'mode'     => $temp['mode'],//投资模式
						'balance_ye'     => $temp['pzed'],//可用配置余额
						'balance_ze'      =>$temp['pzed']//配置总额
				);

				$temp['where'] = array('where' => array('uid' => $temp['uid']));
				$data['data'] = $this->c->get_row(self::automatic, $temp['where']);
				if($data['data']['uid']!=''){
					$query = $this->c->update(self::automatic, $temp['where'], $temp['data']);
				}else
				{
					$query = $this->c->insert(self::automatic, $temp['data']);
				}

			}
		}
		else{
			$temp['data'] = array(
					'statue'    => '0'//投标状态
			);
			$temp['where'] = array('where' => array('uid' => $temp['uid']));
			$query = $this->c->update(self::automatic, $temp['where'], $temp['data']);
			$temp['data'] = array(
					'automatic_type' => '2'//自动投设置
			);
			$temp['where'] = array('where' => array('uid' => $temp['uid'],'automatic_type'=>1));
			$query = $this->c->update(self::payment, $temp['where'], $temp['data']);
		}
		if($query){
			$data = array(
				'status' => '10000',
				'msg'	 => '操作成功！'
			);
		}
		unset($temp);
		return $data;
	}

	/**
	 * 获取自动投详情
	 *$uid 用户uid
	 */
	public function automatic_info($uid = 0){
		$data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp['uid']   = $uid;
		if( ! empty($temp['uid']))
		{
			$temp['where'] = array('where'  => array('uid' => $temp['uid']));
			$temp['user'] = $this->c->get_row(self::automatic, $temp['where']);
			if(!empty($temp['user'])){
				$data = array(
						'status'=>'10000',
						'msg'=>'ok',
						'data'=> $temp['user']
				);
			}
		}else{
			$data['msg'] = '非法操作！';
		}
		unset($temp);
		return $data;
	}



	/**
	 * 投资收益
	 *$uid 用户uid
	 *
	 */
	public function investment($uid=0){
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp = array();
		$query = $this->db->query("select  months ,  sum(sy) as sy ,SUM(tz) as tz from
		(select uid, payment_no,max(months) as months,sum(tz) as tz,sum(hk)-sum(tz) as sy from
		(select  a.uid, a.payment_no,a.borrow_no,
		(case when a.type=1 THEN
		a.amount
		ELSE
		0
		end) as tz,
		(case when a.type=3 THEN
		a.amount
		ELSE
		0
		end) as hk,
		 date_format(from_unixtime(a.pay_time),'%Y%m') as months from cdb_borrow_payment as a left join cdb_borrow as b
		on a.borrow_no=b.borrow_no where a.uid=".$uid." and b.status=7 and (a.type=1 or a.type=3)
		) c
		group by uid, payment_no) d where months>=".date("Ym", strtotime("-5 months"))." and months<=".date("Ym")." group by uid,months");
		$temp['investment_month'][5]=date("Ym");
		$temp['investment_month'][4]=date("Ym", strtotime("-1 months"));
		$temp['investment_month'][3]=date("Ym", strtotime("-2 months"));
		$temp['investment_month'][2]=date("Ym", strtotime("-3 months"));
		$temp['investment_month'][1]=date("Ym", strtotime("-4 months"));
		$temp['investment_month'][0]=date("Ym", strtotime("-5 months"));
		$temp['investment_month_1'][5] = '"'.substr($temp['investment_month'][5],0,4).'年'.substr($temp['investment_month'][5],5).'月'.'"';
		$temp['investment_month_1'][4] = '"'.substr($temp['investment_month'][4],0,4).'年'.substr($temp['investment_month'][4],5).'月'.'"';
		$temp['investment_month_1'][3] = '"'.substr($temp['investment_month'][3],0,4).'年'.substr($temp['investment_month'][3],5).'月'.'"';
		$temp['investment_month_1'][2] = '"'.substr($temp['investment_month'][2],0,4).'年'.substr($temp['investment_month'][2],5).'月'.'"';
		$temp['investment_month_1'][1] = '"'.substr($temp['investment_month'][1],0,4).'年'.substr($temp['investment_month'][1],5).'月'.'"';
		$temp['investment_month_1'][0] = '"'.substr($temp['investment_month'][0],0,4).'年'.substr($temp['investment_month'][0],5).'月'.'"';
		$temp['investment']['months']='';
		$temp['investment']['sy']='';
		$temp['investment']['tz']='';
		for($i=0;$i<6;$i++){
			$f=true;
			foreach ($query->result() as $row => $v)
			{
				if($v->months==$temp['investment_month'][$i]){
					if($temp['investment']['months']==''&&$temp['investment']['sy']==''&&$temp['investment']['tz']==''){
						$temp['investment']['months'] = $temp['investment_month_1'][$i];
						$temp['investment']['sy'] = $v->sy;
						$temp['investment']['tz'] = $v->tz;
					}else{
						$temp['investment']['months'] = $temp['investment']['months'].','.$temp['investment_month_1'][$i];
						$temp['investment']['sy'] = $temp['investment']['sy'].','.$v->sy;
						$temp['investment']['tz'] = $temp['investment']['tz'].','.$v->tz;
					}
					$f=false;
				}
			}
			if($f){
				if($temp['investment']['months']==''&&$temp['investment']['sy']==''&&$temp['investment']['tz']==''){
					$temp['investment']['months'] = $temp['investment_month_1'][$i];
					$temp['investment']['sy'] = '0.00';
					$temp['investment']['tz'] = '0.00';
				}else{
					$temp['investment']['months'] = $temp['investment']['months'].','.$temp['investment_month_1'][$i];
					$temp['investment']['sy'] = $temp['investment']['sy'].','.'0.00';
					$temp['investment']['tz'] = $temp['investment']['tz'].','.'0.00';
				}
			}
		}
		if(!empty($temp['investment'])){
			$data=array(
				'status'=>'10000',
				'msg'=>'ok',
				'data' => $temp['investment']
			);
		}
		unset($temp);
		return $data;
	}



	/****************** 部分私有方法 **********************************************************************************/
	/**
	 * 获取用户信息(个人信息)
	 *
	 * @access public
	 * @param  string $mobile 手机号码
	 * @return integer
	 */
	private function _get_user_info($mobile = ''){
		$data = $temp = array();

		if( ! empty($mobile)){
			if($this->_is_mobile($mobile)){
				$temp['field'] = 'mobile';
			}elseif(preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $mobile)){
				$temp['field'] = 'email';
			}else{
				$temp['field'] = 'user_name';
			}
			$temp['where'] = array('where'=>array($temp['field'] => $mobile));
			$data = $this->c->get_row(self::user, $temp['where']);
		}
		unset($temp);
		return $data;
	}

	/**
	 * 获取总投资金额
	 *
	 */
	private function _get_allinvest($uid='')
	{
		$allinvest=0;
		if( ! empty($uid))
		{

			$temp['where'] = array('select' => 'sum(amount)', 'where' => array('type' => '1','status' => '1','uid'=>$uid));

			$allinvest = $this->c->get_one(self::payment, $temp['where']);
		}
		if($allinvest==null){
			$allinvest=0;
		}
		unset($temp);
		return $allinvest;
	}

	/**
	 * 获取会员余额
	 *
	 * @access private
	 * @param  int $uid 会员ID
	 * @return float
	 */
	private function _get_user_balance($uid = 0){
		$balance = 0;
		$temp    = array();

		if( ! empty($uid)){
			$temp['where'] = array(
				'select'   => 'balance',
				'where'    => array('uid' => (int)$uid),
				'order_by' => 'id desc'
			);
			$balance = (float)$this->c->get_one(self::flow, $temp['where']);
		}

		unset($temp);
		return $balance;
	}

	/**
	 * 更新登录信息
	 * @param $uid
	 * @return bool
	 */
	private function _set_login_info($uid){
		$query = FALSE;
		$temp  = array();

		$temp['data'] = array(
				'error_num' => 0,
				'lock_time' => 0,
				'last_date' => time(),
				'last_ip'   => $this->input->ip_address()
		);
		$temp['where'] = array('where' => array('uid' => $uid));
		$query = $this->c->update(self::user, $temp['where'], $temp['data']);

		unset($temp);
		return $query;
	}

	/**
	 * 添加会员日志
	 *
	 * @access private
	 * @param  string   $module    模块名称
	 * @param  string   $content   日志内容
	 * @param  integer  $uid       会员ID
	 * @param  string   $user_name 会员姓名
	 * @return boolean
	 */
	public function _add_user_log($module = '', $content = '', $uid = 0, $user_name = ''){
		$query = FALSE;
		$logs  = array();

		$uid= ($uid!=0)? $uid : $this->session->userdata('uid');
		$user_name=(!empty($user_name))?$user_name: $this->session->userdata('user_name');

		if( ! empty($module) && ! empty($content)){
			$logs = array(
					'uid'       => $uid,
					'user_name' => $user_name,
					'module'    => $module,
					'content'   => $content,
					'dateline'  => time()
			);

			if( ! empty($logs['uid']) && ! empty($logs['user_name'])){
				$query = $this->c->insert(self::log, $logs);
			}
		}

		unset($logs);
		return $query;
	}

	/**
	 * 验证用户手机号码
	 *
	 * @access private
	 * @param  string  $mobile 手机号码
	 * @return boolean
	 */
	private function _is_mobile($mobile = ''){
		return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
	}

	/**
	 * 获取投资人被邀请的信息
	 *
	 * @access private
	 * @param  integer $uid         投资人uid
	 * @param  integer $sub_rate    下级员工佣金，回调使用
	 * @param  integer $progression 员工向上层次级数，回调使用
	 * @return array
	 */
	private function _get_inviter($uid, $sub_rate=0, $progression=0)
	{
		$data = $temp = array();

		if( ! empty($uid))
		{
			$temp['where'] = array(
					'select'	=> 'uid,rate,inviter',
					'where'		=> array('uid' => $uid),
			);

			$temp['data'] = $this->c->get_row(self::user, $temp['where']);

			// 0层级是投资人信息。
			if( ! empty($temp['data']) && ! empty($progression))
			{
				$temp['data']['bonus_rate'] = $temp['data']['rate'] - $sub_rate;

				// 佣金提成小于0的不计算
				if($temp['data']['bonus_rate'] > 0)
				{
					$data[] = $temp['data'];
				}
			}

			if( ! empty($temp['data']['inviter']))
			{
				// 第一个邀请人应该拿他自己的佣金比例
				if($progression != 0)
				{
					$sub_rate = $temp['data']['rate'];
				}

				$temp['up_inviter'] = $this->_get_inviter($temp['data']['inviter'], $sub_rate, ++$progression);

				$data = array_merge($data, $temp['up_inviter']);
			}
		}

		unset($temp);
		return $data;
	}

	/**
	 * 检查inviter_no是否存在 返回uid
	 * @param string $target[邀请码|电话]
	 * @return int
	 */
	protected function _get_inviter_uid($target=''){
		$inviter_uid = 0;

		if($target != ''){
			$field = $this->_is_mobile($target)?'mobile':'inviter_no';
			$inviter_uid = (int)$this->c->get_one(self::user,array('select'=>'uid','where'=>array($field=>$target)));
		}

		return $inviter_uid;
	}

	/**
	 * 验证是否正确格式身份证号码
	 * @param string $nric
	 * @return bool
	 */
	protected function _is_nric($nric=''){
		$city_array = array(
			11=>"北京",
			12=>"天津",
			13=>"河北",
			14=>"山西",
			15=>"内蒙古",
			21=>"辽宁",
			22=>"吉林",
			23=>"黑龙江",
			31=>"上海",
			32=>"江苏",
			33=>"浙江",
			34=>"安徽",
			35=>"福建",
			36=>"江西",
			37=>"山东",
			41=>"河南",
			42=>"湖北",
			43=>"湖南",
			44=>"广东",
			45=>"广西",
			46=>"海南",
			50=>"重庆",
			51=>"四川",
			52=>"贵州",
			53=>"云南",
			54=>"西藏",
			61=>"陕西",
			62=>"甘肃",
			63=>"青海",
			64=>"宁夏",
			65=>"新疆",
			71=>"台湾",
			81=>"香港",
			82=>"澳门",
			91=>"国外"
		);
		//长度验证
		if( !preg_match('/^\d{17}(\d|x)$/i',$nric) && !preg_match('/^\d{15}$/i',$nric)){
			return false;
		}
		//地区验证
		if(!array_key_exists(intval(substr($nric,0,2)),$city_array)){
			return false;
		}

		// 15位身份证验证生日，转换为18位
		if (strlen($nric) == 15){
			$birthday = '19'.substr($nric,6,2).'-'.substr($nric,8,2).'-'.substr($nric,10,2);
			$d = new DateTime($birthday);
			$dd = $d->format('Y-m-d');
			if($birthday != $dd){
				return false;
			}
			$nric = substr($nric,0,6)."19".substr($nric,6,9);//15to18
			$bit18 = $this->_get_verify_bit($nric);//算出第18位校验码
			$nric = $nric.$bit18;
		}
		// 判断是否大于2078年，小于1900年
		$year = substr($nric,6,4);
		if ($year<1900 || $year>2078 ){
			return false;
		}

		//18位身份证处理
		$birthday = substr($nric,6,4).'-'.substr($nric,10,2).'-'.substr($nric,12,2);
		$d = new DateTime($birthday);
		$dd = $d->format('Y-m-d');
		if($birthday != $dd){
			return false;
		}
		//身份证编码规范验证
		$nric_base = substr($nric,0,17);
		if(strtoupper(substr($nric,17,1)) != $this->_get_verify_bit($nric_base)){
			return false;
		}
		return true;
	}

	/**
	 * 计算身份证校验码，根据国家标准GB 11643-1999
	 * @param $nric_base
	 * @return bool
	 */
	protected function _get_verify_bit($nric_base){
		if(strlen($nric_base) != 17){
			return false;
		}
		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($nric_base); $i++){
			$checksum += substr($nric_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}

	/**
	 * 检测 某一个邀请码 是否在自己的下级中
	 * @param string $inviter_no 邀请码
	 * @param int $uid 自己的uid
	 * @param string $my_inviter_no 自己的邀请码
	 * @return bool
	 */
	protected function _in_my_invite_relation($inviter_no='',$uid=0,$my_inviter_no=''){
		$result = false;
		$temp = array();

		if($uid > 0 && $inviter_no && $my_inviter_no){
			$temp['child_uid_str'] = (string)$uid;
			$temp['child_inviter_no_str'] = '';
			while($temp['child_uid_str']){
				$temp['where'] = array(
					'select'=>'GROUP_CONCAT(uid) as uid_str,GROUP_CONCAT(inviter_no) as inviter_no_str',
					'where'=>array('inviter_no !='=>$my_inviter_no),//过滤已经绑定了自己的 防止无限循环
					'where_in'=>array('field'=>'inviter','value'=>$temp['child_uid_str']),
					'like'=>array('field'=>'inviter_no','match'=>'I','flag'=>'after')
				);
				$temp['data'] = $this->c->get_row('user',$temp['where']);
				if($temp['data']){
					$temp['child_uid_str'] = $temp['data']['uid_str'];
					$temp['child_inviter_no_str'] .= ($temp['child_inviter_no_str']?',':'').$temp['data']['inviter_no_str'];
				}else{
					$temp['child_uid_str'] = '';
				}
			}
			if(strpos($temp['child_inviter_no_str'],$inviter_no) !== false){
				$result = true;
			}
		}
		return $result;
	}

	/**
	 * 添加充值记录
	 *
	 * @access private
	 * @param  integer $uid     会员ID
	 * @param  int     $amount  充值金额[ +- 记录中保存过滤后的数字]
	 * @param  string  $source  记录来源
	 * @param  string  $remarks 描述
	 * @param  int     $type    类型
	 * @return boolean
	 */
	private function _add_cash_flow($uid = 0, $amount = 0, $source = '' , $remarks = '会员充值', $type=1){
		$query = FALSE;
		$temp  = array();

		if( ! empty($uid) && ! empty($amount) && ! empty($source)){
			$temp['where'] = array('where' => array('source' => $source));
			$temp['count'] = $this->c->count(self::flow, $temp['where']);

			if($temp['count'] == 0){
				$temp['balance'] = $this->_get_user_balance($uid);

				$temp['data'] = array(
						'uid'      => $uid,
						'type'     => $type,
						'amount'   => ltrim($amount,'-'),
						'balance'  => round($amount + $temp['balance'], 2),
						'source'   => $source,
						'remarks'  => $remarks,
						'dateline' => time(),
				);

				$query = $this->c->insert(self::flow, $temp['data']);
			}
		}
		unset($temp);
		return $query;
	}

	/**
	 * 可固定开始位的加密字符串
	 * @param string $string
	 * @param int    $start
	 * @param int    $length
	 * @param string $replace
	 * @param int $replace_show_max
	 *
	 * @return string
	 */
	protected function _secret($string = '', $start=0, $length = 0, $replace_show_max=0, $replace = '*'){
		if(empty($string)) return '';

		$str  = '';
		$temp = array();

		$temp['arr']   = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
		$temp['start'] = $start?$start-1:round((count($temp['arr']) - $length) / 2);
		$temp['end']   = $temp['start'] + $length;

		$temp['replace_count'] = 0;
		if($replace_show_max > 0 && $replace_show_max > $temp['end']-$temp['start'])$replace_show_max = $temp['end']-$temp['start'];
		for($i = $temp['start']; $i < $temp['end']; $i++){
			if($replace_show_max > 0){
				if($temp['replace_count'] <= $replace_show_max){
					$temp['arr'][$i] = $replace;
					$temp['replace_count']++;
				}else{
					unset($temp['arr'][$i]);
				}
			}else{
				$temp['arr'][$i] = $replace;
			}
		}
		$str = implode('', $temp['arr']);

		unset($temp);
		return $str;
	}
}