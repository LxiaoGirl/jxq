<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 语音 短信验证码 的发送、验证 相关model
 * Class Sms_model
 */
class Send_model extends CI_Model{
    //数据库表名常量
    const authcode             = 'authcode'; // 验证授权表
    const user                  = 'user'; // 用户表 查询uid时用

    //部分配置常量
    const IP_LIMIT_TOTAL       = 3;           //ip地址 一定时间内限制短信语音次数
    const TARGET_LIMIT_TOTAL   = 3;           //ip地址下目标（手机等） 一定时间内限制短信语音次数
    const SPACE_TIME            = 5;           //多少分钟内的短信数量
    const VOICE_TEL             = '4008382182';//语音 显示的电话号码
    const VOICE_PLAY_TIMES     = 3;            //语音 重播次数

    /**
     * 初始化
     */
    public function __construct(){
        parent::__construct();
        $this->load->library('api/Sms');
        $this->load->library('api/Voice');
    }

	/**
	 * 发送手机短信
	 * @param string $mobile 手机号码
	 * @param string $action 类型
	 * @param int    $uid 用户id
	 *
	 * @return array
	 */
    public function send_sms($mobile = '', $action = '', $uid = 0){
	    $data = $this->_send('sms',$mobile,$action,$uid);
	    $data['name'] = '发送短信';
	    return $data;
    }

	/**
	 * 发送语音
	 * @param string $mobile 手机号码
	 * @param string $action 类型
	 * @param int    $uid 用户id
	 *
	 * @return array
	 */
	public function send_voice($mobile = '', $action = '', $uid = 0){
        $data = $this->_send('voice',$mobile,$action,$uid);
		$data['name'] = '发送语音';
        return $data;
    }

	/**
	 * 检查验证码是否有效
	 * @access public
	 * @param  string  $target  目标地址
	 * @param  string  $code    验证码
	 * @param  string  $action  记录类型
	 * @param  integer $uid     用户uid
	 * @param bool|TRUE
	 * @return bool
	 */
	public function validation_authcode($target = '', $code = '', $action = 'register',$uid=0){
		$data = array(
			'name'   =>'验证短信',
			'status' =>'10001',
			'msg'    =>'短信验证码错误或已过期!',
			'sign'   =>'',
			'data'   =>array()
		);
		$temp  = array();

		if($target == ''){
			$data['msg'] = '手机号码不能为空!';
			return $data;
		}

		if($code == ''){
			$data['msg'] = '验证码不能为空!';
			return $data;
		}

		$temp['type'] = $this->_get_type($action);

		if( ! empty($target) && ! empty($code)){
			$temp['where'] = array(
				'select' => 'uid',
				'where'  => array(
					'code'         => $code,
					'send_time >=' => $this->_timestamp(self::SPACE_TIME),
					'target'       => $target,
					'type'         => $temp['type']
				)
			);

			if($uid){
				$temp['count'] = $this->c->get_one(self::authcode, $temp['where']);
			}else{
				$temp['count'] = $this->c->count(self::authcode, $temp['where']);
			}

			if( ! empty($temp['count'])){
				$data['msg'] = '短信验证通过!';
				$data['status'] = '10000';
			}
		}

		unset($temp);
		return $data;
	}

    /**
     * 获取短信余额
     *
     * @access public
     * @return float
     */
    public function get_sms_balance(){
        $data = $this->sms->get_sms_balance();
        return $data;
    }

	/**************************以下 ↓ 短信相关的私有方法**********************************************/

	/**
	 * 发送的处理
	 * @param string $type 短息(sms)或语音（voice）
	 * @param string $mobile
	 * @param string $action
	 * @param int    $uid
	 *
	 * @return array
	 */
	private function _send($type='sms',$mobile='',$action='register',$uid=0){
		$data = array(
			'name'   =>'发送短信',
			'status' =>'10001',
			'msg'    =>'服务器繁忙请稍后重试!',
			'sign'   =>'',
			'data'   =>array()
		);
		$temp = array();

		//验证必要参数
		if( ! $this->is_mobile($mobile)){
			$data['msg'] = '电话号码格式不正确!';
			return $data;
		}

		//获取类型码
		$temp['type'] = $this->_get_type($action);

		//根据短信类型获取短信内容
		$temp['content'] = $this->get_sms_text($temp['type']);

		//没传uid的时候 根据mobile查询
		if( ! $uid)$uid  = $this->_get_uid($mobile);

		//验证发送内容
		if($temp['content'] == ''){
			$data['msg'] = '发送内容不能为空!';
			return  $data;
		}

		//验证ip target 次数
		$temp['total_check'] = $this->_check_total($mobile);
		if($temp['total_check']['code'] == 2){
			$data['msg'] = $temp['total_check']['msg'];
			return $data;
		}
		//验证 特殊type的次数
		if($temp['type'] == 6){
			$temp['type_check'] =$this->check_type_total(6);
			if($temp['type_check']['code'] > 0){
				$data['msg'] = $temp['type_check']['msg'];
				return $data;
			}
		}

		//生成 短信内容 执行
		$temp['code']    = $this->_get_random($mobile, 6);
		$temp['content'] = sprintf($temp['content'], $temp['code']);

		//执行发送短信或语音程序
		switch($type){
			case 'sms':
				$temp['result'] = $this->sms->send($mobile, $temp['content']);
				break;
			case 'voice':
				$temp['result'] = $this->voice->voiceVerify($temp['code'],self::VOICE_PLAY_TIMES,$mobile,self::VOICE_TEL,"");
				if($temp['result'] == null || $temp['result']->statusCode != 0){
					$temp['result'] = false;
				}else{
					$temp['result'] = true;
				}
				break;
			default:
				$temp['result'] = $this->sms->send($mobile, $temp['content']);
		}

		if($temp['result']){
			$this->_add_send_log($mobile, $temp['code'], $temp['content'], $temp['type'], $uid);
			$data['msg'] = '短信已经发送成功！';
			$data['status'] = '10000';
		}

		unset($temp);
		return $data;
	}

	/**
	 * @param string $act 短信类型-字符
	 * @return int 短信类型-数字
	 */
	private function _get_type($act=''){
		$type = 1;

		switch ($act){
			case 'forget': // 忘记密码
				$type = 2;
				break;
			case 'security': // 交易密码
				$type = 3;
				break;
			case 'transfer': // 用户提现
				$type = 4;
				break;
			case 'password': // 修改密码
				$type = 5;
				break;
			case 'huodong': // 修改密码
				$type = 6;
				break;
			case 'jujianren': // 修改密码
				$type =7;
				break;
			case 'bindphone': // 修改绑定手机
				$type = 8;
				break;
			case 'unbindphone': // 解绑手机
				$type = 9;
				break;
			case 'apply':       // 借款
				$type = 10;
				break;
			case 'bindcard':   // 绑定卡
				$type = 11;
				break;
			case 'unbindcard': // 解绑卡
				$type = 12;
				break;
			default:            // 用户注册
				$type = 1;
		}

		return $type;
	}

	/**
	 * 获取 uid （忘记密码的时候）
	 * @param string $mobile
	 * @return int
	 */
	private function _get_uid($mobile=''){
		$uid = 0;
		if($this->is_mobile($mobile)){
			$uid = $this->c->get_one(self::user, array('select' => 'uid', 'where' => array('mobile' => $mobile)));
			if( ! $uid) $uid = 0;
		}

		return $uid;
	}

	/**
	 * 根据类型 获取发送短信的没人模板
	 * @param int $type
	 * @return string
	 */
	public function get_sms_text($type = 1){
		$str = '';
		switch ($type){
			case '2': // 忘记密码
				$str = '【聚雪球】验证码为:%s,为您本次找回密码验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '3': // 交易密码
				$str = '【聚雪球】验证码:%s,为您设置资金密码验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '4': // 会员提现
				$str = '【聚雪球】验证码:%s,为您申请提现验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '5': // 修改密码
				$str = '【聚雪球】验证码:%s,您的账户正在修改密码。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '6': // 堆雪人球
				$str = '【聚雪球】验证码:%s,您在参与堆雪人球活动。验证码5分钟后失效。';
				break;
			case '7': // 居间人邀请
				$str = '【聚雪球】注册验证码:%s,为您申请居间人使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '8': // 修改手机
				$str = '【聚雪球】验证码:%s,您的账户正在绑定新手机。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '9': // 修改手机
				$str = '【聚雪球】验证码:%s,您的账户正在解绑手机。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '10': // 申请借款
				$str = '【聚雪球】验证码:%s,您的账户正在申请借款。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '11': // 申请借款
				$str = '【聚雪球】验证码:%s,您的账户正在绑定银行卡。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			case '12': // 申请借款
				$str = '【聚雪球】验证码:%s,您的账户正在解绑银行卡。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
			default: // 新用户注册
				$str = '【聚雪球】注册验证码:%s,为您注册用户验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
				break;
		}

		return $str;
	}

	/**
	 * 验证用户手机号码
	 *
	 * @access private
	 * @param  string  $mobile 手机号码
	 * @return boolean
	 */
	public function is_mobile($mobile = ''){
		return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
	}

	/**
	 * 添加手机发送日志
	 *
	 * @access private
	 * @param  string  $target  目标地址
	 * @param  string  $code    授权码
	 * @param  string  $content 发送内容
	 * @param  integer $type    记录类型
	 * @param  integer $uid     会员ID
	 * @return boolean
	 */
	private function _add_send_log($target = '', $code = '', $content = '', $type = 1, $uid = 0){
		$query = FALSE;
		$logs  = array();

		if( ! empty($target) && ! empty($code) && ! empty($content)){
			$logs = array(
				'code'        => $code,
				'ip_address' => $this->input->ip_address(),
				'send_time'  => time(),
				'uid'         => $uid,
				'type'        => $type,
				'target'      => $target,
				'content'     => $content
			);
			$query = $this->c->insert(self::authcode, $logs);
		}

		unset($logs);
		return $query;
	}

	/**
	 * 获取授权码
	 *
	 * @access private
	 * @param  string   $target  目标地址
	 * @param  integer  $length  授权码位数
	 * @param  boolean  $flag    纯数字
	 * @return string
	 */
	private function _get_random($target = '', $length = 6, $flag = TRUE){
		$code = '';
		$temp = array();

		if( ! empty($length)){
			$code = random($length, $flag);

			$temp['where'] = array(
				'where' => array(
					'code'         => $code,
					'send_time >=' => $this->_timestamp(self::SPACE_TIME),
					'target'       => $target
				)
			);

			$temp['count'] = $this->c->count(self::authcode, $temp['where']);

			if($temp['count'] > 0){
				$code = $this->_get_random($target, $length, $flag);
			}
		}

		unset($temp);
		return $code;
	}

	/**
	 * 获取当前ip地址 当前目标一定时间内发送信息的数量
	 * @access private
	 * @param  string  $target 目标地址
	 * @return integer
	 */
	private function _get_send_num($target = ''){
		$total = 0;
		$temp  = array();

		if( ! empty($target)){
			$temp['where'] = array(
				'where' => array(
					'ip_address'   => $this->input->ip_address(),
					'send_time >=' => $this->_timestamp(self::SPACE_TIME),
					'target'       => $target
				)
			);
			$total = $this->c->count(self::authcode, $temp['where']);
		}

		unset($temp);
		return $total;
	}

	/**
	 * 获取IP发送数量
	 *
	 * @access private
	 * @param  string  $target 目标地址
	 * @return integer
	 */
	private function _get_send_ip_num($target = ''){
		$total = 0;
		$temp  = array();

		if( ! empty($target)){
			$temp['where'] = array(
				'where' => array(
					'ip_address'   => $this->input->ip_address(),
					'send_time >=' => $this->_timestamp(self::SPACE_TIME),
				)
			);
			$total = $this->c->count(self::authcode, $temp['where']);
		}

		unset($temp);
		return $total;
	}

	/**
	 * 获取当前时间倒回的分钟数的时间戳 不传值默认60分计算
	 * @access private
	 * @param  integer  $minute 分钟
	 * @return integer
	 */
	private function _timestamp($minute = 60){
		return time() - ($minute * 60);
	}

	/**
	 * 验证ip下 一定时间内 目标和ip发送信息次数
	 * @param string $target 目标
	 * @param int $ip_total ip限制次数 默认取常量 IP_LIMIT_TOTAL
	 * @param int $target_total 目标限制次数 默认取常量 TARGET_LIMIT_TOTAL
	 * @return array
	 */
	private function _check_total($target='',$ip_total=0,$target_total=0){
		$data = array('code'=>0,'msg'=>'');
		$temp = array();

		if($ip_total == 0)$ip_total = self::IP_LIMIT_TOTAL;
		if($target_total == 0)$target_total = self::TARGET_LIMIT_TOTAL;

		//验证 当前目标（3分钟内）已发送数量
		$temp['total'] = $this->_get_send_num($target);
		if($temp['total'] < $target_total){
			//验证（3分钟内）当前ip地址发送数量
			$temp['ip_total'] = $this->_get_send_ip_num($target);
			if($temp['ip_total'] >= $ip_total){
				$data = array('code' => 2, 'msg' => '当前IP发送频率过于频繁，请稍侯再试！');
			}
		}else{
			$data = array('code' => 2, 'msg' => '发送频率过于频繁，请稍侯再试！');
		}

		unset($temp);
		return $data;
	}

	/**
	 * 验证特定type的发送次数限制
	 * @param int $type 类型
	 * @param int $total 限制数量
	 * @param int $time 限制时间
	 * @return array
	 */
	public function check_type_total($type = 0,$total=5,$time=300){
		$data = array('code' => 0, 'msg' => '');
		$temp = array();

		if($type > 0){
			$temp['where'] = array(
				'where'  => array(
					'ip_address' => $this->input->ip_address(),
					'send_time >=' => time()-$time,
					'send_time <=' => time(),
					'type'         => $type
				)
			);
			$temp['count'] = $this->c->count(self::authcode, $temp['where']);
			if($temp['count'] >= $total){
				$data = array('code' => 2, 'msg' => '当前IP发送频率过于频繁，请稍侯再试！');
			}
		}

		unset($temp);
		return $data;
	}
}