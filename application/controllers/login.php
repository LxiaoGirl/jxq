<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 2015.11.20
 * 会员登录
 * Class Login
 */
class Login extends MY_Controller{
	//用到的数据库表
	const user     = 'user'; 		// 会员表
	const authcode = 'authcode'; 	// 授权验证

	const remember_hour = 24;//登录信息的记住我的保存cookie时间 小时

	/**
	 * 初始化
	 * Login constructor.
	 */
	public function __construct(){
		parent::__construct();
		//加载用户处理类model和公告发送model
		$this->load->model('api/user_model', 'user');
		$this->load->model('api/commons_model', 'commons');
		//过滤部分方法已登录的访问
		$this->_is_login();
	}

	/**
	 * 会员登录
	 *
	 * @access public
	 * @return void
	 */
	public function index(){
		//ajax部分的处理
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->user->login($this->input->post('mobile',true),$this->input->post('password',true));
			//登录成功的处理
			if($data['status'] == '10000'){
				//保存登录信息
				$this->session->set_userdata($data['data']);

				//记住我的处理 =1 则保存 否则 清除
				if($this->input->post('remember',true) == '1'){
					$cookie = array(
						'name'   => 'mobile',
						'value'  => base64_encode($this->input->post('mobile',true)),
						'expire' => self::remember_hour * 3600,
						'domain' => '',
						'path'   => '/',
						'prefix' => '',
					);
					$this->input->set_cookie($cookie);
					$cookie = array(
						'name'   => 'password',
						'value'  => base64_encode($this->input->post('password',true)),
						'expire' => self::remember_hour * 3600,
						'domain' => '',
						'path'   => '/',
						'prefix' => '',
					);
					$this->input->set_cookie($cookie);
				}else{
					$cookie = array(
						'name'   => 'mobile',
						'value'  => '',
						'expire' => '',
						'domain' => '',
						'path'   => '/',
						'prefix' => '',
					);
					$this->input->set_cookie($cookie);
					$cookie = array(
						'name'   => 'password',
						'value'  => '',
						'expire' => '',
						'domain' => '',
						'path'   => '/',
						'prefix' => '',
					);
					$this->input->set_cookie($cookie);
				}

				//处理登录成功后的跳转
				if($this->session->userdata('login_redirect_url')){
					$data['url'] = $this->session->userdata('login_redirect_url');
					$this->session->set_userdata(array('login_redirect_url'=>false));
				}else{
					$data['url'] = site_url();
				}
			}

			exit(json_encode($data));
		}

		$data = array();
		$data['mobile']  	= $this->input->cookie('mobile', TRUE)?base64_decode($this->input->cookie('mobile', TRUE)):'';
		$data['password']  	= $this->input->cookie('password', TRUE)?base64_decode($this->input->cookie('password', TRUE)):'';

		//js跳转携带返回链接 则保存
		if(isset($_GET['redirect_url'])){
			$this->session->set_userdata(array('login_redirect_url'=>$this->input->get('redirect_url',true)));
		}

		$this->load->view('passport/login', $data);
	}

	/**
	 * 会员注册（第一步）
	 *
	 * @access public
	 * @return void
	 */
	public function register(){
		//ajax部分的处理
		if($this->input->is_ajax_request() == TRUE){
			//保存第一步的注册号码
			$this->session->set_userdata(array('register_mobile'=>$this->input->post('mobile',true)));
			if($this->session->userdata('register_mobile')){
				$data = array('status'=>'10000','msg'=>'请继续下一步!');
			}else{
				$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!');
			}
			exit(json_encode($data));
		}

		//保存 邀请号码信息
		$data['invite_code'] = $this->input->get('invite_code', TRUE);
		if(empty($data['invite_code'])){
			$data['invite_code'] = $this->input->cookie('invite_code');
			$data['invite_code'] = base64_decode($data['invite_code']);
		}
		$this->session->set_userdata(array('invite_coed'=>$data['invite_code']));

		$this->load->view('passport/register');
	}

	/**
	 * 会员注册（第二步）
	 */
	public function register_s1(){
		//ajax处理部分
		if($this->input->is_ajax_request() == TRUE){
			//执行注册操作
			$data = $this->user->register(
					$this->session->userdata('register_mobile'),
					$this->input->post('password',true),
					$this->input->post('authcode',true),
					$this->session->userdata('invite_code'),
					$this->input->post('company_code',true)
			);
			if($data['status'] == '10000'){
				//保存注册第二步session信息
				$this->session->set_userdata(array('register_s2'=>1));
			}
			exit(json_encode($data));
		}

		//验证是否进行了注册第一步 没有则跳转到第一步
		if( ! $this->session->userdata('register_mobile')){
			redirect('login/register','location');
		}
		//验证是否已经注册 再返回或者直接访问
		$data = $this->user->Registered_mobile($this->session->userdata('register_mobile'));
		if($data['status'] != '10000'){
			redirect('','location');
		}

		$this->load->view('passport/register_1');
	}

	/**
	 * 会员注册（第三步）
	 */
	public function register_s2(){
		//验证是否进行了注册第一步 和第二步
		if( ! $this->session->userdata('register_mobile') || ! $this->session->userdata('register_s2')){
			redirect('login/register','location');
		}

		//注销注册session信息
		$this->session->set_userdata(array('register_mobile'=>false));
		$this->session->set_userdata(array('register_s2'=>false));

		$this->load->view('passport/register_cg');
	}

	/**
	 * 注册用 是否注册ajax验证
	 */
	public function ajax_is_register(){
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->user->Registered_mobile($this->input->post('mobile',true));
			exit(json_encode($data));
		}
	}

	/**
	 * 注册用  ajax验证 公司邀请码是否正确
	 */
	public function ajax_check_company_invitation_code(){
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->user->check_company_invitation_code($this->input->post('code',true));
			exit(json_encode($data));
		}
	}

	/**
	 * 忘记密码（第一步）
	 *
	 * @access public
	 * @return void
	 */
	public function forget(){
		//ajax部分
		if($this->input->is_ajax_request() == TRUE){
			//保存第一步 忘记密码的手机号
			$this->session->set_userdata(array('forget_mobile'=>$this->input->post('mobile',true)));
			if($this->session->userdata('forget_mobile')){
				$data = array('status'=>'10000','msg'=>'请继续下一步!');
			}else{
				$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!');
			}

			exit(json_encode($data));
		}

		$this->load->view('passport/find_pw');
	}

	/**
	 * 忘记密码（第二步）
	 */
	public function forget_s1(){
		//ajax部分
		if($this->input->is_ajax_request() == TRUE){
			//保存第二部 手机验证码
			$this->session->set_userdata(array('forget_authcode'=>$this->input->post('authcode',true)));
			if($this->session->userdata('forget_authcode')){
				$data = array('status'=>'10000','msg'=>'请继续下一步!');
			}else{
				$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!');
			}

			exit(json_encode($data));
		}

		//验证是否进行了第一步 没有则跳回第一步操作
		if( ! $this->session->userdata('forget_mobile')){
			redirect('login/forget','location');
		}

		$this->load->view('passport/find_pw_1');
	}

	/**
	 * 忘记密码（第三步）
	 */
	public function forget_s2(){
		//ajax部分
		if($this->input->is_ajax_request() == TRUE){
			//执行密码重置
			$data = $this->user->Forget_login_password($this->session->userdata('forget_mobile'),$this->session->userdata('forget_authcode'),$this->input->post('password',true),$this->input->post('password',true));
			if($data['status'] == '10000'){
				$this->session->set_userdata(array('forget_s2'=>1));
			}

			exit(json_encode($data));
		}

		//验证是否进行了找回密码第一、二步
		if( ! $this->session->userdata('forget_mobile') ||  ! $this->session->userdata('forget_authcode')){
			redirect('login/forget','location');
		}
		$this->load->view('passport/find_pw_2');
	}

	/**
	 * 忘记密码（第四步）
	 */
	public function forget_s3(){
		//验证是否进行了找回密码第一步 和第二 、三步
		if( ! $this->session->userdata('forget_mobile') || ! $this->session->userdata('forget_authcode') || ! $this->session->userdata('forget_s2')){
			redirect('login/forget','location');
		}
		//注销找回密码session信息
		$this->session->set_userdata(array('forget_mobile'=>false));
		$this->session->set_userdata(array('forget_authcode'=>false));
		$this->session->set_userdata(array('forget_s2'=>false));

		$this->load->view('passport/find_pw_cg');
	}

	/**
	 * 退出登录 处理方法
	 */
	public function logout(){
		$this->user->_add_user_log('logout', '注销登录');
		$this->session->sess_destroy();

		redirect('', 'refresh');
	}

/******************************************************以下 私有方法************************************************************************/
	/**
	 * 判断用户是否已经登录
	 *
	 * @access public
	 * @return void
	 */
	private function _is_login(){
		$method = $this->router->fetch_method();
		//已登录状态下 访问登录 注册 忘记密码 跳转到个人中心
		if($this->session->userdata('uid') > 0 && in_array($method, array( 'index','register','register_s1','register_s2', 'forget','forget_s1','forget_s2','forget_s3'))){
			redirect('user/user/account_home', 'refresh');
		}
	}
}