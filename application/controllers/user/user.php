<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Login_Controller{

	const cash_log_page_size = 5;
	const user_invest_log_page_size = 5;
	const recharge = 'user_recharge';

	public function __construct(){
		parent::__construct();
		$this->load->model('api/user_model','user');
		$this->load->model('api/commons_model','commons');
		$this->load->model('api/activity_model','activity');
		$this->load->model('api/other_model','other');
		$this->load->model('api/common/email_model','email');
		$this->load->model('api/common/send_model','send');
		$this->load->model('api/cash_model','cash');
		$this->load->model('api/project_model','project');
		$this->_is_login();
	}


	/**
	 * 资金总览
	 */
	public function account_home(){
		$data = $temp = array();
		$uid=$this->session->userdata('uid');

		//获取个人资金总额数据
		$data = $this->cash->get_user_cash_total($uid);

		//获取雪球数据
		$data['snowball_num'] = $this->activity->My_snowball_total($uid);
		$data['snowball_num'] =($data['snowball_num']['status']=='10000')? $data['snowball_num']['data']['snowball_total']:0;

		$temp['investment'] = $this->user->investment($uid);
		if($temp['investment']['status']==10000){
			$data['months'] = $temp['investment']['data']['months'];
			$data['sy'] = $temp['investment']['data']['sy'];
			$data['tz'] = $temp['investment']['data']['tz'];
		}
		$data['red_bag']=$this->activity->receive_red_num($uid,0);

		$this->load->view('user/myaccount/account_home',$data);
	}


	/**
	 * 提现列表
	 */
	public function withdrawals_jl(){
		$uid=$this->session->userdata('uid');
		$time_limit=(!empty($_GET['limit_time']))?$this->input->get('limit_time',true):0;
		$start=(!empty($_GET['start']))?strtotime($this->input->get('start',true)):0;
		$end=(!empty($_GET['end']))?strtotime($this->input->get('end',true)):0;
		$data = $this->cash->get_user_transfer_list($uid,'',$start,$end,$time_limit);
		$temp['page_id'] 	= $this->c->get_page_id(7);
		if($data['status']=='10000' && $data['data']){
			$data['links'] 	= $this->c->get_links($data['data']['total'],$temp['page_id'],7);
		}else{
			$data['links'] = '';
		}
		$data['balance'] = $this->cash->get_user_balance($uid);
		$this->load->view('user/myaccount/withdrawals_jl',$data);
	}



	/**
	 * 提现
	 */
	public function withdrawals(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$data['user_bank'] = $this->user->user_bank($uid);
		if($data['user_bank']['status']==10000){
			$data['bank'] = $this->user->bank_card_list($data['user_bank']['data']['bank_id']);
		}else{
			redirect('user/user/card', 'refresh');
		}
		$data['balance'] = $this->cash->get_user_balance($uid);
		$this->load->view('user/myaccount/withdrawals',$data);
	}

	/**
	 *提现操作
	 */
	public function user_transfer(){
		$uid=$this->session->userdata('uid');
		$card_no=$this->user->user_bank($uid);
		$card_no = $card_no['data']['account'];
		$amount=$this->input->get('amount',true);
		$security=$this->input->get('security',true);
		$authcode=$this->input->get('authcode',true);
		$data = $this->cash->user_transfer($uid,$amount,$card_no,$security,$authcode);
		exit(json_encode($data));
	}


	/**
	 * 充值
	 */
	public function recharge(){

		//验证实名
		if($this->session->userdata('clientkind') != "1"){
			redirect('user/user/account_security', 'refresh');
		}
		$data['balance'] = $this->cash->get_user_balance($this->session->userdata('uid'));
		if($data['balance']['status'] == '10000')$data['balance'] = $data['balance']['data']['balance'];
		$data['recharge_min'] = item('recharge_min')?item('recharge_min'):50;
		$data['recharge_no'] = urlencode(authcode($this->c->transaction_no(self::recharge, 'recharge_no')));

		$this->load->view('user/myaccount/recharge',$data);
	}

	public function ajax_recharge_auto_refresh(){
		$data = $this->user->recharge_refresh($this->input->post('recharge_no',true),$this->session->userdata('uid'));
		exit(json_encode($data));
	}



	/**
	 * 充值列表
	 */
	public function recharge_jl(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$page_id = $this->input->get('page_id',true);
		$time_limit=(!empty($_GET['time_limit']))?$this->input->get('time_limit',true):0;
		$start=(!empty($_GET['start']))?strtotime($this->input->get('start',true)):0;
		$end=(!empty($_GET['end']))?strtotime($this->input->get('end',true)):0;
		$data = $this->cash->get_user_recharge_list($uid,'',$start,$end,$time_limit);
		$temp['page_id'] 	= $this->c->get_page_id(7);
		if($data['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['data']['total'],$temp['page_id'],7);
		}
		$data['balance'] = $this->cash->get_user_balance($uid);
		$this->load->view('user/myaccount/recharge_jl',$data);
	}



	/**
	 * 交易明细
	 */
	public function transaction_details(){
		$temp = array();
		$data = array(
				'type'=>isset($_GET['type'])?$this->input->get('type',true):'d',
				'year'=>isset($_GET['year'])?$this->input->get('year',true):'',
				'month'=>isset($_GET['month'])?$this->input->get('month',true):'',
		);
		//验证时间段
		switch($data['type']){
			case 'd';
				$temp['start_time'] = strtotime(date('Y-m-d').' 00:00:00');
				$temp['end_time'] = time();
				break;
			case 'w';
				$temp['start_time'] = strtotime((date('Y-m').'-'.(date('d')-(date('w')>0?date('w'):7))).' 00:00:00');
				$temp['end_time'] = time();
				break;
			case 'm';
				$temp['start_time'] = strtotime(date('Y-m-01').' 00:00:00');
				$temp['end_time'] = time();
				break;
			case '3m';
				$temp['start_time'] = strtotime(date('Y-m-t',strtotime('-3 month')).' 00:00:00');
				$temp['end_time'] = time();
				break;
			case '6m';
				$temp['start_time'] = strtotime(date('Y-m-t',strtotime('-6 month')).' 00:00:00');
				$temp['end_time'] = time();
				break;
			case 'auto';
				if( !$data['year'])$data['year'] = date('Y');
				if( !$data['month'])$data['month'] = date('m');
				$temp['start_time'] = strtotime($data['year'].'-'.$data['month'].'-01'.' 00:00:00');
				$temp['end_time'] = strtotime(date('Y-m-d H:i:s',$temp['start_time']).' +1 month -1 day');
				break;
			default:
				$temp['start_time'] = strtotime(date('Y-m-d').' 00:00:00');
				$temp['end_time'] = time();
		}
		$temp['page_id'] = $this->c->get_page_id(self::cash_log_page_size);
		$temp['data'] = $this->cash->get_user_cash_list($this->session->userdata('uid'),'','',$temp['start_time'],$temp['end_time'],$temp['page_id'],self::cash_log_page_size);
		if($temp['data']['status'] == '10000' && $temp['data']['data']){
			$data['log'] = $temp['data']['data']['data'];
			if($temp['data']['data']['total']){
				$data['links'] = $this->c->get_links($temp['data']['data']['total'],$temp['page_id'],self::cash_log_page_size);
			}else{
				$data['links'] = '';
			}
		}else{
			$data['log'] = array();
			$data['links'] = '';
		}

		$data['cash_total'] = $this->cash->get_user_limit_time_cash_total($this->session->userdata('uid'),$temp['start_time'],$temp['end_time']);
		if($data['cash_total']['status'] = '10000'){
			$data['cash_total'] = $data['cash_total']['data'];
		}else{
			$data['cash_total'] = array('income_total'=>0,'pay_total'=>0);
		}

		unset($temp);
		$this->load->view('user/myinvestment/transaction_details',$data);
	}







	/**
	 * 我的雪球
	 */
	public function my_xq(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['snowball_num'] = $this->activity->My_snowball_total($uid);
		$data['snowball_num'] =($data['snowball_num']['status']=='10000')? $data['snowball_num']['data']['snowball_total']:0;
		$data['snowball_list'] = $this->activity->My_snowball($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['snowball_list']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['snowball_list']['data']['total'],$temp['page_id'],5);
		}
		$this->load->view('user/myaccount/my_xq',$data);
	}



	/**
	 * 我的红包(未领取)
	 */
	public function my_redbag(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['redbag_noreceive'] = $this->activity->My_redbag_list(0,$uid);
		$data['redbag_num'] = $this->activity->receive_red_num($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['redbag_noreceive']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['redbag_noreceive']['data']['total'],$temp['page_id'],5);
		}
		$this->load->view('user/myaccount/my_redbag',$data);
	}
	/**
	 * 我的红包（已领取）
	 */
	public function my_redbag_lq(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['redbag_receive'] = $this->activity->My_redbag_list(1,$uid);
		$data['redbag_num'] = $this->activity->receive_red_num($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['redbag_receive']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['redbag_receive']['data']['total'],$temp['page_id'],5);
		}
		$this->load->view('user/myaccount/my_redbag_lq',$data);
	}
	/**
	 * 我的红包（已过期）
	 */
	public function my_redbag_gq(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['redbag_timeout'] = $this->activity->My_redbag_list(100,$uid);
		$data['redbag_num'] = $this->activity->receive_red_num($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['redbag_timeout']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['redbag_timeout']['data']['total'],$temp['page_id'],5);
		}
		$this->load->view('user/myaccount/my_redbag_gq',$data);
	}


	/**
	 * 获得单个红包
	 */
	public function redbag_id(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$id = $this->input->get('id',true);
		$data = $this->activity->My_redbag_list(0,$uid,$id);
		exit(json_encode(array($data)));
	}



	/**
	 * 领取红包
	 */
	public function Receive_redbag(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$id = $this->input->get('id',true);
		$data = $this->activity->Receive_redbag($uid,$id);
		exit(json_encode(array($data)));
	}



	/**
	 * 消息中心
	 */
	public function information(){
		$data = $temp =array();
		$uid=$this->session->userdata('uid');
		$data['user_messages'] = $this->user->My_message($uid);
		$temp['page_id'] 	= $this->c->get_page_id(10);
		if($data['user_messages']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['user_messages']['data']['total'],$temp['page_id'],10);
		}else{
			$data['links'] = '';
		}

		$this->load->view('user/myaccount/information',$data);
	}



	/**
	 * 投资记录
	 */
	public function transaction_note(){
		$temp = array();
		$data = array(
				'type'=>isset($_GET['type'])?$this->input->get('type',true):'',
				'start_time'=>isset($_GET['start_time'])&&$this->input->get('start_time',true)?(strpos($this->input->get('start_time',true),'-')?strtotime($this->input->get('start_time',true)):$this->input->get('start_time',true)):0,
				'end_time'=>isset($_GET['end_time'])&&$this->input->get('end_time',true)?(strpos($this->input->get('end_time',true),'-')?strtotime($this->input->get('end_time',true)):$this->input->get('end_time',true)):time(),
				'project'=>array(),
				'links'=>''
		);
		$temp['page_id'] = $this->c->get_page_id(self::user_invest_log_page_size);
		$temp['project'] = $this->project->get_user_project_list($this->session->userdata('uid'),$data['type'],$data['start_time'],$data['end_time'],$temp['page_id'],self::user_invest_log_page_size);
		if($temp['project']['status'] == '10000' && isset($temp['project']['data']['data'])){
			$data['project'] = $temp['project']['data']['data'];
			if($temp['project']['data']['total'])$data['links'] = $this->c->get_links($temp['project']['data']['total'],$temp['page_id'],self::user_invest_log_page_size);
		}

		unset($temp);
		$this->load->view('user/myinvestment/transaction_note',$data);
	}




	/**
	 * 自动投资
	 */
	public function auto(){
		$this->load->view('user/myinvestment/auto');
	}



	/**
	 * 个人资料（账户信息）
	 */
	public function account_information(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['user'] = $this->user->_get_user_uid($uid);

		//新手指引get来的触发类型 name-改名称 phone-改手机
		$data['type'] = isset($_GET['type'])&&$this->input->get('type',true)?$this->input->get('type',true):'';

		$this->load->view('user/profile/account_information',$data);
	}


	/**
	 * 修改姓名
	 */
	public function Change_name(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$name = $this->input->get('name',true);
		$f = $this->input->get('f',true);
		if($f!=''){
			$data = $this->user->Change_name($name,$uid);
		}else{
			$data = $this->user->Change_name($name,0);
		}
		$data['name'] = $name;
		if($this->input->is_ajax_request() == TRUE){
			exit(json_encode(array($data)));
		}
	}



	/**
	 * 修改手机号第一步
	 */
	public function Change_mobile_one(){
		$data=array();
		$mobile = $this->input->get('mobile',true);
		$authcode = $this->input->get('authcode',true);
		$data = $this->user->Change_mobile_one($mobile,$authcode);
		if($data['data']!=''){
			$this->session->set_userdata($data['data']);
		}
		exit(json_encode($data));
	}
	/**
	 * 修改手机号第二步
	 */
	public function Change_mobile_two(){
		$data=array();
		$mobile = $this->input->get('mobile',true);
		$authcode = $this->input->get('authcode',true);
		$new_authcode = $this->session->userdata('authcode_new');
		$old_mobile = $this->session->userdata('mobile');
		$data = $this->user->Change_mobile_two($mobile,$authcode,$new_authcode,$old_mobile);
		exit(json_encode($data));
	}
	/**
	 * 发送邮箱
	 */
	public function send_mail(){
		//$data=array();
		$email = $this->input->get('email',true);
		$uid = $this->session->userdata('uid');
		$data = $this->email->send_email($uid,$email,'',site_url('user/user/email_yes'));//邮箱验证
		exit(json_encode($data));
	}
	/**
	 * 验证成功
	 */
	public function email_yes(){
		$data = array();
		$email = $this->input->get('from',true);//邮箱地址
		$code = $this->input->get('code',true);//邮箱验证码
		$uid = $this->session->userdata('uid');
		$data = $this->email->validation_email($email,$code,2880);//2880  48小时
		if($data['status']=='10000'){
			$data['email'] = $this->user->mailbox_binding($uid,$email);
		}
		$this->load->view('user/profile/mailbox',$data);
	}
	/**
	 * 个人资料（账户安全）
	 */
	public function account_security(){
		$data =  array();
		$uid=$this->session->userdata('uid');
		$data = $this->user->_get_user_uid($uid);

		//新手指引get来的触发类型 find_security-找回交易密码  change_security change_password-密码
		$data['type'] = isset($_GET['type'])&&$this->input->get('type',true)?$this->input->get('type',true):'';

		$this->load->view('user/profile/account_security',$data);
	}

	/**
	 * 重置登录密码
	 */
	public function Reset_login_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$password = $this->input->get('new_password',true);//新密码
		$code = $this->input->get('code',true);//新密码
		$data = $this->user->Reset_login_password($uid,$password,$code);
		exit(json_encode($data));
	}
	/**
	 * 修改登录密码
	 */
	public function Change_login_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$password = $this->input->get('password',true);//原密码
		$new_password = $this->input->get('new_password',true);//新密码
		$data = $this->user->Change_login_password($uid,$password,$new_password);
		exit(json_encode($data));
	}
	/**
	 * 资金密码(设置资金密码,重置资金密码)
	 */
	public function Fund_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$mobile = $this->input->get('mobile',true);//手机号
		$security = $this->input->get('security',true);//资金密码
		$authcode = $this->input->get('code',true);//验证码
		$password = $this->input->get('password',true);//验证码
		$data = $this->user->Fund_password($uid,$mobile,$security,$authcode,$password);
		exit(json_encode($data));
	}
	/**
	 * 修改资金密码
	 */
	public function update_fund_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$mobile = $this->input->get('mobile',true);//手机号
		$security = $this->input->get('security',true);//资金密码
		$security_new = $this->input->get('security_new',true);//新资金密码
		$authcode = $this->input->get('code',true);//验证码
		$password = $this->input->get('password',true);//登录密码
		$data = $this->user->update_fund_password($uid,$mobile,$password,$security,$security_new,$authcode);
		exit(json_encode($data));
	}



	/**
	 * 个人资料（上传头像）
	 */
	public function head_portrait(){
		$this->load->view('user/profile/head_portrait');
	}
	/**
	 * 银行卡管理
	 */
	public function card(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$data['bank'] = $this->user->user_bank($uid);
		$data['all_bank'] =$this->user->bank_card_list();
		$this->load->view('user/profile/card',$data);
	}

	/**
	 * 银行卡判断
	 */
	public function ajax_check_card_bin(){
		$account = $this->input->post('account',true);
		$data = $this->commons->get_bankcard_bin($account);
		exit(json_encode($data));
	}
	/**
	 * 邀请好友
	 */
	public function invite(){
		$uid = $this->session->userdata('uid');
		$data['lv'] = $this->session->userdata('lv');
		$data['invite_code'] = $this->session->userdata('inviter_no');
		$data['jujian_amount'] = $this->activity->get_settle_amount($uid);
		$data['jujian_list'] = $this->activity->get_settle_list($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['jujian_list']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['jujian_list']['data']['total'],$temp['page_id'],3);
		}
		$this->load->view('user/profile/invite',$data);
	}
	/**
	 * 结算部分 按结算时间 的用户投资列表
	 */
	public function get_settle_invest_list(){
		$uid = $this->session->userdata('uid');
		$real_month = $this->input->get('real_month',true);//结算月份
		$data = $this->activity->get_settle_invest_list($real_month,$uid);
		exit(json_encode($data));
	}
	/**
	 * 邀请好友客户列表
	 */
	public function invite_customer(){
		$uid = $this->session->userdata('uid');
		$data['lv'] = $this->session->userdata('lv');
		$data['invite_code'] = $this->session->userdata('inviter_no');
		$data['jujian'] = $this->activity->get_intermediary_user(true,$uid);
		$data['jujian_amount'] = $this->activity->get_settle_amount($uid);
		$temp['page_id'] 	= $this->c->get_page_id(5);
		if($data['jujian']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['jujian']['data']['total'],$temp['page_id'],3);
		}
		$this->load->view('user/profile/invite_1',$data);
	}



	/**
	 * 邀请好友客户投资列表
	 */
	public function get_commission_list(){
		$uid = $this->input->get('uid',true);//客户uid
		$data = $this->activity->get_commission_list($uid);
		exit(json_encode($data));
	}
	/******************************************通用************************/
	/**
	 * 发送短信
	 */
	public function send_sms(){
		$data =  array();
		$uid= (isset($_GET['uid']))?$this->input->get('uid',true):$this->session->userdata('uid');
		$action = $this->input->get('action',true);//短信类型
		$mobile = $this->input->get('mobile',true);//手机号码
		$data = $this->send->send_sms($mobile,$action,$uid);
		exit(json_encode($data));
	}
	/**
	 * 发送语音
	 */
	public function send_voice(){
		$data =  array();
		$uid= (isset($_GET['uid']))?$this->input->get('uid',true):$this->session->userdata('uid');
		$action = $this->input->get('action',true);//短信类型
		$mobile = $this->input->get('mobile',true);//手机号码
		$data = $this->send->send_voice($mobile,$action,$uid);
		exit(json_encode($data));
	}
	/**
	 * 判断用户是否已经登录
	 *
	 * @access public
	 * @return void
	 */
	private function _is_login(){
		$method = $this->router->fetch_method();
		if(in_array($method, array('withdrawals_jl','withdrawals','recharge', 'recharge_jl', 'transaction_details', 'account_home','my_xq','my_redbag','my_redbag_lq','redbag_id','Receive_redbag','information','transaction_note','auto','Change_name','Change_mobile_one','Change_mobile_two','send_mail','email_yes','account_security','Reset_login_password','Change_login_password','Fund_password','update_fund_password','card')) && $this->session->userdata('uid') == ''){
			redirect('login', 'refresh');
		}
	}
}