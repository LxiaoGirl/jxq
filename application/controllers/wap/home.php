<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * wap版控制器
 * Class Home
 */
class Home extends MY_Controller{
    const dir       = 'mobiles/';          //当前控制器controller model view目录
    //数据表 常量
    const user        = 'user';             // 会员
    const bank        = 'bank';             //银行
    const card        = 'user_card';        //会员之银行卡表
    const cash        = 'cash_flow';        //资金流动【收支】记录表
    const cate        = 'product_category'; //项目分类表
    const borrow      = 'borrow';           //借款【项目】表
    const recharge    = 'user_recharge';    // 充值记录
    const flow        = 'cash_flow';        // 现金记录
    const payment     = 'borrow_payment';   // 投资还款记录表
    const snowballdtl = 'cdb_snowballdtl';  //活动表
    const redbag      = 'cdb_redbag';       //红包表
    const transfer    = 'user_transaction'; //提现表
    protected $_llpay_config = array();    //连连支付需要的配置

    /**
     *构造函数
     * 初始化部分内容和model
     */
    public function __construct(){
        parent::__construct();

        //连连支付 配置异步通知地址和返回地址
        $this->_llpay_config['notify']     = site_url(self::dir.'home/llpay_notify');
        $this->_llpay_config['return_url'] = site_url(self::dir.'home/recharge_success');

        //分页参数的修正
        if(isset($_GET['pageId'])){
            $_GET['limit']    = (int)$this->input->get('pageSize');
            $_GET['per_page'] = ((int)$this->input->get('pageId')-1)*(int)$this->input->get('pageSize');
        }

        //加载必要model
        $this->load->model(self::dir.'app_model', 'app');     //app model

        $this->load->model('api/user_model','user_api');       //2.0版user相关model
        $this->load->model('api/project_model','project_api'); //2.0版项目相关model
        $this->load->model('api/cash_model','cash_api');       //2.0版资金相关model
        $this->load->model('api/commons_model','commons_api'); //2.0版公共数据相关model
		$this->load->model('api/other_model','other_api'); //2.0版其他相关model
    }

    //-------------------------------主页-------------------------------------------------------------------------------
    /**
     *主页
     */
    public function index(){

        $this->load->view(WAP_VIEW_DIR.'home');
    }

    //-------------------------------登录-注册-忘记密码-----------------------------------------------------------------
    /**
     * 登录的显示和ajax处理
     */
    public function login(){
        //ajax部分
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->user_api->login($this->input->post('mobile',true),$this->input->post('password',true),'wap');
            if($data['status'] == '10000'){
                $this->session->set_userdata($data['data']);
                //处理登录成功后的跳转
                if($this->session->userdata('login_redirect_url')){
                    $data['url'] = $this->session->userdata('login_redirect_url');
                    $this->session->set_userdata(array('login_redirect_url'=>false));
                }else{
                    $data['url'] = site_url('mobiles');
                }
            }
            exit(json_encode($data));
        }

        //页面显示部分
        $this->_is_login();
        //js跳转携带返回链接 则保存
        if(isset($_GET['redirect_url'])){
            $this->session->set_userdata(array('login_redirect_url'=>$this->input->get('redirect_url',true)));
        }
        $this->load->view(self::dir.'login');
    }

    /**
     * 注册的显示部分和ajax处理
     *
     */
    public function register(){
        //ajax部分
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->user_api->register(
                $this->input->post('mobile',true),
                $this->input->post('password',true),
                $this->input->post('authcode',true),'',
                $this->input->post('invite_code',true)
            );
            if($data['status'] == '10000'){
                $this->session->set_userdata($data['data']);
            }
            exit(json_encode($data));
        }
        $data['inviter_no'] = $this->input->get('inviter_no',true);
        $this->load->view(self::dir.'register',$data);
    }

    /**
     * 注册协议
     */
    public function register_agreement(){
        $this->load->view(self::dir.'register_agreement');
    }












    /**
     * 注销
     */
    public function logout(){
        $this->session->sess_destroy();
        redirect('mobiles/home/index','location');
    }

    /**
     * 已经登录的验证
     */
    protected function _is_login(){
        if($this->session->userdata('uid') > 0){
            redirect(self::dir.'home/index','location');
        }
    }

    /**
     * 验证是否登录 未登录跳转到登录
     */
    protected function _check_to_login(){
        if( !$this->session->userdata('uid')){
            redirect(self::dir.'home/login','location');
        }
    }
}