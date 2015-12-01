<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/5
 * Time: 16:10
 * 发送类的处理和验证
 */
class send extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('api/commons_model','commons');
	}

	/**
	 * 发送调用的主方法
	 */
	public function index(){
		$data = array('status'=>'10001','msg'=>'错误的发送类型');
		$temp = array();

		//接受参数
		$temp['mobile'] = $this->input->post('mobile',true)?$this->input->post('mobile',true):'';
		$temp['type'] 	= $this->input->post('type',true)?$this->input->post('type',true):'sms';
		$temp['action'] = $this->input->post('action',true)?$this->input->post('action',true):'register';
		$temp['uid'] 	= $this->session->userdata('uid')?$this->session->userdata('uid'):0;

		//过滤 间隔时间
		if($temp['type'] == 'sms')$temp['last_time_space'] = $this->session->userdata('sms_last_send_time')?time()-$this->session->userdata('sms_last_send_time'):'';
		if($temp['type'] == 'voice')$temp['last_time_space'] = $this->session->userdata('voice_last_send_time')?time()-$this->session->userdata('voice_last_send_time'):'';
		$temp['item_space'] 	 =item("sms_space_time")?item("sms_space_time"):60;

		if($temp['last_time_space'] !== '' && $temp['last_time_space'] < $temp['item_space']){
			$data['msg'] = '发送过于频繁,请在'.($temp['item_space']-$temp['last_time_space']).'秒后重试!';
		}else{
			switch($temp['type']){
				case 'sms':
					$data = $this->commons->send_sms($temp['mobile'],$temp['action'],$temp['uid']);
					break;
				case 'voice':
					$data = $this->commons->send_voice($temp['mobile'],$temp['action'],$temp['uid']);
					break;
				default:
			}
		}

		//添加最后发送时间
		if($data['status'] == '10000'){
			if($temp['type'] == 'sms'){
				$this->session->set_userdata(array('sms_last_send_time'=>time()));
			}else{
				$this->session->set_userdata(array('voice_last_send_time'=>time()));
			}
		}
		unset($temp);
		exit(json_encode($data));
	}

	/**
	 * 手机验证码的验证方法
	 */
	public function validate_authcode(){
		$data = array('status'=>'10001','msg'=>'错误的发送类型');
		$temp = array();

		//接受参数
		$temp['mobile'] 	= $this->input->post('mobile',true)?$this->input->post('mobile',true):'';
		$temp['authcode'] 	= $this->input->post('authcode',true)?$this->input->post('authcode',true):'';
		$temp['action'] 	= $this->input->post('action',true)?$this->input->post('action',true):'register';
		$temp['uid'] 		= $this->session->userdata('uid')?$this->session->userdata('uid'):0;

		$data = $this->commons->validation_authcode($temp['mobile'],$temp['authcode'],$temp['action'],$temp['uid']);

		unset($temp);
		exit(json_encode($data));
	}

	/**
	 * 生成图片验证码
	 *
	 * @access public
	 * @return void
	 */
	public function captcha(){
		$this->load->library('Captcha/simplecaptcha');
		$this->simplecaptcha->create();
	}

	/**
	 * 验证图片验证码 的 ajax验证
	 */
	public function ajax_check_captcha(){
		if($this->input->is_ajax_request() == TRUE){
			$data = array('status'=>'10001','msg'=>'验证码不正确!');
			if($this->session->userdata('captcha') == $this->input->post('captcha',true)){
				$data = array('status'=>'10000','msg'=>'验证码正确!');
			}
			exit(json_encode($data));
		}
	}

	/**
	 * oss https 图片处理 的调用地址方法
	 */
	public function get_oss_image(){
		$filename=urldecode($this->input->get('f',true));
		$size = getimagesize($filename); //获取mime信息
		$fp=fopen($filename, "rb"); //二进制方式打开文件
		header("Content-type: {$size['mime']}");
		if ($size && $fp) {
			fpassthru($fp); // 输出至浏览器
		}
	}
}