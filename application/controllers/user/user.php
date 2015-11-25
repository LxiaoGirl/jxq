<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Login_Controller{

	const cash_log_page_size = 5;
	const user_invest_log_page_size = 5;

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
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
	 * �ʽ�����
	 */
	public function account_home(){
		$data = $temp = array();
		$uid=$this->session->userdata('uid');

		//��ȡ�����ʽ��ܶ�����
		$data = $this->cash->get_user_cash_total($uid);

		//��ȡѩ������
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
	 * �����б�
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
	 * ����
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
	 *���ֲ���
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
	 * ��ֵ
	 */
	public function recharge(){
		$this->load->view('user/myaccount/recharge');
	}



	/**
	 * ��ֵ�б�
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
	 * ������ϸ
	 */
	public function transaction_details(){
		$temp = array();
		$data = array(
			'type'=>isset($_GET['type'])?$this->input->get('type',true):'d',
			'year'=>isset($_GET['year'])?$this->input->get('year',true):'',
			'month'=>isset($_GET['month'])?$this->input->get('month',true):'',
		);
		//��֤ʱ���
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
	 * �ҵ�ѩ��
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
	 * �ҵĺ��(δ��ȡ)
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
	 * �ҵĺ��������ȡ��
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
	 * �ҵĺ�����ѹ��ڣ�
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
	 * ��õ������
	 */
	public function redbag_id(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$id = $this->input->get('id',true);
		$data = $this->activity->My_redbag_list(0,$uid,$id);
		exit(json_encode(array($data)));
	}



	/**
	 * ��ȡ���
	 */
	public function Receive_redbag(){
		$data = array();
		$uid=$this->session->userdata('uid');
		$id = $this->input->get('id',true);
		$data = $this->activity->Receive_redbag($uid,$id);
		exit(json_encode(array($data)));
	}



	/**
	 * ��Ϣ����
	 */
	public function information(){
		$data = $temp =array();
		$uid=$this->session->userdata('uid');
		$data['user_messages'] = $this->user->My_message($uid);
		$temp['page_id'] 	= $this->c->get_page_id(10);
		if($data['user_messages']['status']=='10000'){
			$data['links'] 	= $this->c->get_links($data['user_messages']['data']['total'],$temp['page_id'],10);
		}
		$this->load->view('user/myaccount/information',$data);
	}



	/**
	 * Ͷ�ʼ�¼
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
	 * �Զ�Ͷ��
	 */
	public function auto(){
		$this->load->view('user/myinvestment/auto');
	}



	/**
	 * �������ϣ��˻���Ϣ��
	 */
	public function account_information(){
		$data=array();
		$uid=$this->session->userdata('uid');
		$data['user'] = $this->user->_get_user_uid($uid);
		$this->load->view('user/profile/account_information',$data);
	}


	/**
	 * �޸�����
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
	 * �޸��ֻ��ŵ�һ��
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
	 * �޸��ֻ��ŵڶ���
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
	 * ��������
	 */
	public function send_mail(){
		//$data=array();
		$email = $this->input->get('email',true);
		$uid = $this->session->userdata('uid');
		$data = $this->email->send_email($uid,$email,'',site_url('user/user/email_yes'));//������֤
		exit(json_encode($data));
	}
	/**
	 * ��֤�ɹ�
	 */
	public function email_yes(){
		$data = array();
		$email = $this->input->get('from',true);//�����ַ
		$code = $this->input->get('code',true);//������֤��
		$uid = $this->session->userdata('uid');
		$data = $this->email->validation_email($email,$code,2880);//2880  48Сʱ
		if($data['status']=='10000'){
			$data['email'] = $this->user->mailbox_binding($uid,$email);
		}
		$this->load->view('user/profile/mailbox',$data);
	}
	/**
	 * �������ϣ��˻���ȫ��
	 */
	public function account_security(){
		$data =  array();
		$uid=$this->session->userdata('uid');
		$data = $this->user->_get_user_uid($uid);
		$this->load->view('user/profile/account_security',$data);
	}

	/**
	 * ���õ�¼����
	 */
	public function Reset_login_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$password = $this->input->get('new_password',true);//������
		$code = $this->input->get('code',true);//������
		$data = $this->user->Reset_login_password($uid,$password,$code);
		exit(json_encode($data));
	}
	/**
	 * �޸ĵ�¼����
	 */
	public function Change_login_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$password = $this->input->get('password',true);//ԭ����
		$new_password = $this->input->get('new_password',true);//������
		$data = $this->user->Change_login_password($uid,$password,$new_password);
		exit(json_encode($data));
	}
	/**
	 * �ʽ�����(�����ʽ�����,�����ʽ�����)
	 */
	public function Fund_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$mobile = $this->input->get('mobile',true);//�ֻ���
		$security = $this->input->get('security',true);//�ʽ�����
		$authcode = $this->input->get('code',true);//��֤��
		$password = $this->input->get('password',true);//��֤��
		$data = $this->user->Fund_password($uid,$mobile,$security,$authcode,$password);
		exit(json_encode($data));
	}
	/**
	 * �޸��ʽ�����
	 */
	public function update_fund_password(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$mobile = $this->input->get('mobile',true);//�ֻ���
		$security = $this->input->get('security',true);//�ʽ�����
		$security_new = $this->input->get('security_new',true);//���ʽ�����
		$authcode = $this->input->get('code',true);//��֤��
		$password = $this->input->get('password',true);//��¼����
		$data = $this->user->update_fund_password($uid,$mobile,$password,$security,$security_new,$authcode);
		exit(json_encode($data));
	}



	/**
	 * �������ϣ��ϴ�ͷ��
	 */
	public function head_portrait(){
		$this->load->view('user/profile/head_portrait');
	}
	/**
	 * ���п�����
	 */
	public function card(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$data['bank'] = $this->user->user_bank($uid);
		$data['all_bank'] =$this->user->bank_card_list();
		$this->load->view('user/profile/card',$data);
	}

	/**
	 * ���п��ж�
	 */
	public function ajax_check_card_bin(){
		$account = $this->input->post('account',true);
		$data = $this->commons->get_bankcard_bin($account);
		exit(json_encode($data));
	}
	/**
	 * �������
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
	 * ���㲿�� ������ʱ�� ���û�Ͷ���б�
	 */
	public function get_settle_invest_list(){
		$uid = $this->session->userdata('uid');
		$real_month = $this->input->get('real_month',true);//�����·�
		$data = $this->activity->get_settle_invest_list($real_month,$uid);
		exit(json_encode($data));
	}
	/**
	 * ������ѿͻ��б�
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
	 * ������ѿͻ�Ͷ���б�
	 */
	public function get_commission_list(){
		$uid = $this->input->get('uid',true);//�ͻ�uid
		$data = $this->activity->get_commission_list($uid);
		exit(json_encode($data));
	}
	/******************************************ͨ��************************/
	/**
	 * ���Ͷ���
	 */
	public function send_sms(){
		$data =  array();
		$uid= (isset($_GET['uid']))?$this->input->get('uid',true):$this->session->userdata('uid');
		$action = $this->input->get('action',true);//��������  
		$mobile = $this->input->get('mobile',true);//�ֻ�����
		$data = $this->send->send_sms($mobile,$action,$uid);
		exit(json_encode($data));
	}
	/**
	 * ��������
	 */
	public function send_voice(){
		$data =  array();
		$uid= (isset($_GET['uid']))?$this->input->get('uid',true):$this->session->userdata('uid');
		$action = $this->input->get('action',true);//��������  
		$mobile = $this->input->get('mobile',true);//�ֻ�����
		$data = $this->send->send_voice($mobile,$action,$uid);
		exit(json_encode($data));
	}
	/**
	 * �ж��û��Ƿ��Ѿ���¼
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