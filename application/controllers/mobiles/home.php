<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 14:45
 */

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


    /************************************---登录-注册-忘记密码--*******************************************************/
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
     *注册成功提示页
     */
    public function register_success(){
        $this->load->view(self::dir.'register_success');
    }

    /**
     * 忘记密码的显示和ajax处理
     */
    public function forget(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->user_api->forget(
                $this->input->post('mobile',true),
                $this->input->post('authcode',true),
                $this->input->post('new_password',true)
            );
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'forget');
    }


    /**
     * 自动投开启界面
     */
    public function auto(){
		$data = $this->app->automatic_judge();
		if($data){
			$this->auto_info();
		}else{
			$this->auto_start();
		}
    }


    /**
     * 自动投开启界面
     */
    public function auto_start(){
        $this->load->view(self::dir.'auto');
    }

    /**
     * 自动投设置界面/修改
     */
    public function auto_form(){
		$data['automatic_info'] = $this->app->automatic_info();
		$data['product'] = $this->app->product_info();
        $this->load->view(self::dir.'auto_form',$data);
    }

    /**
     * 自动投关闭
     */
    public function auto_info(){
		$data['automatic_info'] = $this->app->automatic_info();
        $this->load->view(self::dir.'auto_info',$data);
    }

    /**
     * 自动投提交
     */
    public function auto_sub(){	
		$data = $this->app->auto_form(0);
        exit($data);
    }

    /**
     * 自动投提交
     */
    public function auto_close(){
		$data = $this->app->auto_form(1);
        exit($data);
	}


/************************************--聚保宝投资页面相关--***********************************************/
	/**
     *聚保宝主页
     */
    public function jbb(){
		$data['project'] = $this->project_api->jbb_dtl_list();
		if(!empty($data['project']['data'])){
				foreach($data['project']['data'] as $k => $v){
					$jbb_all_invest = $this->cash_api->jbb_all_invest($v['type_code']);//累计投资
					$jbb_nums = $this->project_api->jbb_nums($v['type_code']);//累计入团
					$data['project']['data'][$k]['jbb_all_invest'] = $jbb_all_invest['data']['jbb_all_invest'];
					$data['project']['data'][$k]['jbb_nums'] = $jbb_nums['data']['jbb_nums'];
				}
			}
        $this->load->view(self::dir.'jbb',$data);
    }
	/**
     *聚保宝投资页面
     */
    public function jbb_add(){
        $this->load->view(self::dir.'jbb_add');
    }
	/**
     *聚保宝投资列表
     */
    public function jbb_invest_list(){	
		$type_code =  $this->input->get('type_code',TRUE);
		$data['detail_jbb'] =$this->project_api->detail_jbb_list($type_code,0,15);
        $this->load->view(self::dir.'jbb_invest_list',$data);
    }

	
	/**
     *聚保宝投资列表到底部刷新
     */
    public function jbb_invest_list_add(){
		$type_code =  $this->input->get('type_code',TRUE);
		$per_page =  $this->input->get('page_id',TRUE);
		if($this->input->is_ajax_request() == TRUE){
			$data =$this->project_api->detail_jbb_list($type_code,$per_page,15);
			exit(json_encode($data));
		}
    }
	/**
     *聚保宝投资页面
     */
    public function jbb_invest(){
		$data = $temp =array();

		$temp['type_code'] = $this->input->get('type_code',TRUE)?$this->input->get('type_code',TRUE):'';
		if($temp['type_code'] == '')redirect('','location');
		$data['jbb_all_invest'] = $this->cash_api->jbb_all_invest($temp['type_code']);//累计投资
		$data['jbb_all_Earn'] = $this->cash_api->jbb_all_Earn($temp['type_code']);//累计赚取
		$data['jbb_nums'] = $this->project_api->jbb_nums($temp['type_code']);//累计入团
		$data['jbb_invest_nums'] = $this->project_api->jbb_invest_nums($temp['type_code']);//分散投资
		$data['jbb'] = $this->project_api->jbb($temp['type_code']);//聚保宝产品
		$data['jbb_list'] = $this->project_api->jbb_list($temp['type_code']);//聚保宝产品标的
		$data['total'] = $this->project_api->detail_jbb_list($temp['type_code']);
		if($data['total']['status']==10000){
			$data['total'] = $data['total']['data']['total'];
		}
		//获取余额
		if($this->session->userdata('uid')){
			$temp['balance'] = $this->cash_api->get_user_balance($this->session->userdata('uid'));
			if($temp['balance']['status'] == '10000'){
				$data['balance'] = $temp['balance']['data']['balance'];
			}
		}else{
			$data['balance'] = 0;
		}

		unset($temp);

        $this->load->view(self::dir.'jbb_invest',$data);
    }
	/**
     *聚保宝标的信息
     */
    public function jbb_subject(){
		$temp['type_code'] = $this->input->get('type_code',TRUE)?$this->input->get('type_code',TRUE):'';
		$data['details'] = $this->other_api->jbb_details($temp['type_code']);
        $this->load->view(self::dir.'jbb_subject',$data);
    }

	/**
     *聚保宝标的信息
     */
    public function ajax_jbb_sub(){
		if($this->input->is_ajax_request() == TRUE){
			$amount =  $this->input->post('amount',TRUE);
			$security =  $this->input->post('security',TRUE);
			$type_code =  $this->input->post('type_code',TRUE);
			$data =  $this->project_api->jbb_invest($type_code,$this->session->userdata('mobile'),$security,$amount);
			exit(json_encode($data));
		}
    }




	/**
     *聚保宝购买页表
     */
    public function jbb_list(){
		$page_id =  $this->input->get('page_id',TRUE);
		$uid = $this->session->userdata('uid');
		$data['jbb_list'] = $this->project_api->jbb_per_list($uid,1,6,$page_id);//列表
		$this->load->view(self::dir.'jbb_list',$data);
    }

	/**
     *聚保宝购买页表加载
     */
    public function jbb_list_info(){
		$page_id =  $this->input->get('page_id',TRUE);
		$uid = $this->session->userdata('uid');
		$data = $this->project_api->jbb_per_list($uid,1,6,$page_id);//列表
		exit(json_encode($data));
    }



	/**
     *聚保宝排队页表加载
     */
    public function jbb_list_line(){
		$page_id =  $this->input->get('page_id',TRUE);
		$uid = $this->session->userdata('uid');
		$data = $this->project_api->jbb_per_list($uid,2,6,$page_id);//列表
		exit(json_encode($data));
    }


	/**
     *聚保宝信息页面
     */
    public function jbb_one(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$data['jbb_receive'] = $this->cash_api->jbb_receive($uid);//可领取收益
		$data['cumulative_yield'] = $this->cash_api->jbb_cumulative_yield($uid);//累计提取收益
		$data['add_amount'] = $this->cash_api->jbb_add_amount($uid);//累计加入
		$data['buy_nums'] = $this->cash_api->jbb_buy_nums($uid);//购买笔数
		$data['mate_nums'] = $this->cash_api->jbb_mate_nums($uid);//配标数目
		$this->load->view(self::dir.'jbb_one',$data);
    }




	/**
     *聚保宝提取总收益
     */
    public function jbb_interest(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$id = $this->input->get('id',true);//投标id
		$data = $this->cash_api->jbb_receive($uid,$id);
		exit(json_encode($data));
	}


	/**
	 * 聚保宝申请退出手续费
	 */
	public function jbb_poundage(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$id = $this->input->get('id',true);//投标id
		$data = $this->cash_api->jbb_poundage($uid,$id);
		exit(json_encode($data));
	}




	/**
	 * 聚保宝申请退出
	 */
	public function jbb_out(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$id = $this->input->get('id',true);//投标id
		$data = $this->cash_api->jbb_out($uid,$id);
		exit(json_encode($data));
	}



	/**
	 * 聚保宝提取利息
	 */
	public function jbb_sub_receive(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$id = $this->input->get('id',true);//投标id
		$data = $this->cash_api->jbb_sub_receive($uid,$id);
		exit(json_encode($data));
	}



	/**
     *聚保宝退出列表
     */
    public function jbb_out_list(){
		$uid = $this->session->userdata('uid');
		$data['jbb_list'] = $this->project_api->jbb_per_list($uid,2,6);//列表
		$this->load->view(self::dir.'jbb_out_list',$data);
    }

	/**
	 * 聚保宝取消退出
	 */
	public function jbb_off(){
		$data = array();
		$uid = $this->session->userdata('uid');
		$id = $this->input->get('id',true);//投标id
		$data = $this->cash_api->jbb_off($uid,$id);
		exit(json_encode($data));
	}


	/**
     *聚保宝详细页
     */
    public function jbb_user(){
		$id =  $this->input->get('id',TRUE);
		$uid = $this->session->userdata('uid');
		$data = $this->project_api->jbb_per_info($id,$uid);
		$this->load->view(self::dir.'jbb_user',$data);
    }



	/**
     *聚保宝详细页
     */
    public function jbb_userinfo(){
		$type_code =  $this->input->get('type_code',TRUE);
		$tab_amount =  $this->input->get('tab_amount',TRUE);
		$data = $this->cash_api->jbb_jbb_details($type_code,$tab_amount);//列表
		exit(json_encode($data));
    }



		/**
     *聚保宝历史退出
     */
    public function jbb_user_history(){
		$uid = $this->session->userdata('uid');
		$data['jbb_list'] = $this->project_api->jbb_per_list($uid,3,6);//列表
		$this->load->view(self::dir.'jbb_user_history',$data);
    }
	/**
     *聚保宝历史页表加载
     */
    public function jbb_history_line(){
		$page_id =  $this->input->get('page_id',TRUE);
		$uid = $this->session->userdata('uid');
		$data = $this->project_api->jbb_per_list($uid,3,6,$page_id);//列表
		exit(json_encode($data));
    }
/***********************--主页和项目相关--*************************************************************************/
    /**
     *主页
     */
    public function index(){
        //资金统计
        $data['total'] = $this->cash_api->get_cash_total()['data'];
        $this->load->view(self::dir.'home',$data);
    }

    /**
     * 主页 轮播图 图片获取ajax
     */
    public function ajax_get_slider_list(){
        $data = $this->c->get_all('article',array('where'=>array('cat_id'=>32,'status'=>1),'select'=>'source,link_url','order_by'=>'id DESC'));
        if($data){
            foreach($data as $k=>$v){
                if($v['link_url'] && $v['link_url'] != '#' && $v['link_url'] != 'javascript:void(0);'){
                    $data[$k]['link_url'] = str_replace('__APP__',rtrim(self::dir,'/'),$v['link_url']);
                    $data[$k]['link_url'].= strpos($data[$k]['link_url'],'?')?'&ENV=WAP':'?ENV=WAP';
                }
            }
        }
        exit(json_encode(array('data'=>$data)));
    }

    /**
     * ajax  获得项目列表
     */
    public function get_project_list(){
        if($this->input->is_ajax_request() == TRUE){
            $category = $this->input->get('category',true);
            $m = $this->input->get('m',true);
            $data = $this->project_api->get_project_list($category,'',$m,'','','')['data'];
            //过滤了其中的 link total 等数据
            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>array_merge($data['data']),'code'=>0))); //array_merge 处理了一下排序排序 下标由0开始
            }else{
                exit(json_encode(array('data'=>'','code'=>1)));
            }
        }
    }

    /**
     *我要投资（项目类型）
     */
    public function project_category(){
        $this->load->view(self::dir.'project_category');
    }

    /**
     *项目列表
     */
    public function project_list(){
        //接收项目类型
        $data['category'] = (int)$this->input->get('category');
        if(empty($data['category'])){
            redirect(self::dir.'home/project_category','refresh');
        }
        //处理title
        $data['title'] = $this->c->get_one(self::cate,array('select'=>'category','where'=>array('cat_id'=>$this->input->get('category'))));
        if(empty($data['title']))$data['title'] = '项目列表';

        $this->load->view(self::dir.'project_list',$data);
    }

    /**
     *项目详情
     */
    public function project_detail(){
        //查询项目详情
        $borrow_no = $this->input->get('borrow_no',true);

        $data = $this->project_api->get_project_info($borrow_no)['data'];
        if(empty($data))redirect(self::dir.'home/index', 'refresh');

        //计划表  根据项目状态 确定调用数据库查询还是动态生成的还款计划
        $data['repay_plan'] = $this->project_api->get_project_repayment_list($borrow_no)['data'];
        $data['log'] = $this->project_api->get_project_invest_list($borrow_no,1,100)['data'];

        $this->load->view(self::dir.'project_detail',$data);
    }

    /**
     *投资
     */
    public function project_invest(){
        $this->_check_to_login();
        //获取借款项目单号borrow_no
        $borrow_no = $this->input->get('borrow_no',true);
        if(empty($borrow_no)){
            redirect(self::dir.'home/index','refresh');
        }
        //查询该项目信息
        $data = $this->project_api->get_project_info($borrow_no)['data'];
        if(empty($data)){
            redirect(self::dir.'home/index','refresh');
        }
        $this->load->view(self::dir.'project_invest',$data);
    }

    /**
     *投资确认
     */
    public function project_invest_confirm(){
        $this->_check_to_login();
        //获取投资的借款单号
        $borrow_no = $this->input->get('borrow_no',true);
        if(empty($borrow_no)){
            redirect(self::dir.'home/index','refresh');
        }

        $data = $this->project_api->get_project_info($borrow_no)['data'];
        if(empty($data)){
            redirect(self::dir.'home/index','refresh');
        }

        //获取将要投资的数目
        $data['to_invest'] = (float)$this->input->get('amount',true);
        $data['balance'] = $this->cash_api->get_user_balance($this->session->userdata('uid'))['data']['balance']; // 当前账户可用余额
        if( ! $data['balance'])$data['balance']=0;

        $data['master'] = $this->session->userdata('uid');

        $this->load->view(self::dir.'project_invest_confirm',$data);
    }

    /**
     * 投资的ajax操作处理
     */
    public function ajax_invest(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->project_api->project_invest(
                $this->session->userdata('mobile'),
                (float)$this->input->post('amount',TRUE),
                $this->input->post('password',TRUE),
                $this->input->post('borrow_no',TRUE),4
            );
            exit(json_encode($data));
        }
    }

    /**
     *投资成功提示页面
     */
    public function project_invest_success(){
        $this->load->view(self::dir.'project_invest_success');
    }


    /******************--我要借款--************************************************************************************/
    /**
     *我要借款(借款类型)
     */
    public function borrow_type(){
        $this->load->view(self::dir.'borrow_type');
    }

    /**
     *借款的表单和处理
     */
    public function borrow(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->apply();
            exit(json_encode($data));
        }

        $this->_check_to_login();
        $data['p_type'] = (int)$this->input->get('type');   //借款类型
        if(empty($data['p_type']))redirect(self::dir.'home/borrow_type','refresh');

        //地址信息 省份 城市 地区  用于地址插件初始化 该get_region_list 用GET获取的参数region_id 所以修改$_get 的参数 获取各级地址信息
        $data['province'] = $this->commons_api->get_region(1)['data'];
        $this->load->view(self::dir.'borrow',$data);
    }

    /**
     * 地区 的ajax处理
     */
    public function ajax_get_region_list(){
        $temp = $data = array();

        $region_id = (int)$this->input->get('region_id')?(int)$this->input->get('region_id'):2;
        $data['city'] = $this->commons_api->get_region($region_id)['data'];
        $temp['type'] = $this->input->get('type');
        if( !empty($temp['type']) && $temp['type'] == 'city'){ //获取城市和地区
            $data['district']  = $this->commons_api->get_region($data['city'][0]['region_id'])['data'];
        }else{ //值获取地区
            $data = $data['city'];
        }

        exit(json_encode($data));
    }

    /**
     * 借款提交成功提示页
     */
    public function borrow_success(){
        $this->load->view(self::dir.'borrow_success');
    }


    /************************************--个人详情、实名认证--********************************************************/
    /**
     *个人详情
     */
    public function profile(){
        $this->_check_to_login();
        $this->load->view(self::dir.'profile');
    }

	 /**
     *2016-1-18  Colin新增，用户查询三方账户信息
     */
    public function third_party(){

		$uid = $this->session->userdata('uid');

        $data = $this->c->get_row(self::user,array('select'=>'real_name,firmid,vaccid','where'=>array('uid'=>$uid)));

        $this->load->view(self::dir.'third_party',$data);
    }
	
    /**
     *实名认证的显示和ajax处理
     */
    public function real_name(){
        if($this->input->is_ajax_request() == TRUE){
            $data        = $this->user_api->real_name($this->input->post('real_name',true),$this->input->post('nric',true),$this->session->userdata('uid'));
            if($data['status'] == '10000'){
                $this->session->set_userdata($data['data']);
            }
            exit(json_encode($data));
        }

        $this->_check_to_login();
        $this->load->view(self::dir.'real_name');
    }

    /**
     *实名认证成功提示页
     */
    public function real_name_success(){
        $this->load->view(self::dir.'real_name_success');
    }



    /*****************************************--充值提现--*************************************************************/
    /**
     *充值主页面 选择银行卡 或其他银行卡
     */
    public function recharge(){

        $this->_check_realname();
        //查询用户 已有的银行卡
        $data['card'][] = $this->user_api->get_user_card($this->session->userdata('uid'))['data'];
        if( !$data['card'][0])$data['card'] = array();
        $this->load->view(self::dir.'recharge',$data);
    }

    /**
     * 验证银行卡bin信息
     */
    public function ajax_check_card_bin(){
        if($this->input->is_ajax_request() == TRUE){
            $account = $this->input->post('account');

            if( empty($account)){
                exit(json_encode(array('ret_code'=>1,'ret_msg'=>'银行账号不能为空！')));
            }else{
                $this->load->library('llpay',$this->_llpay_config);
                $rs = $this->llpay->check_bin($account,'D',1);
                exit($rs);
            }
        }
    }

    /**
     *充值的表单填写
     */
    public function recharge_form(){
        //获取card_no  有card_no就是绑定银行卡的充值  没有则是其他银行卡的充值
        $card_no = $this->input->get('card_no',true);
        if($card_no){
            $data['card']  = $this->user_api->get_user_card($this->session->userdata('uid'))['data'];
        }else{
            $data['bank'] = $this->commons_api->get_bank('100,102,103,104,105,308,302,303,305,310,307,309')['data']; //银行列表
        }
        $this->load->view(self::dir.'recharge_form',$data);
    }

    /**
     * 充值确认
     */
    public function recharge_confirm(){
        $temp = array();

        //如果有recharge_no 则是 重新提交过来的
        if(isset($_GET['recharge_no']) && $this->input->get('recharge_no',true) != ''){
            $temp['recharge'] = $this->c->get_row(self::recharge,array('where'=>array('recharge_no'=>$this->input->get('recharge_no',true))));
            if( ! empty($temp['recharge']) && $temp['recharge']['stauts'] == 0 && $temp['recharge']['source'] == ''){ //账单信息存在 并且是没成功的
                //查询 连连支付 结果
                $this->load->library('llpay',$this->_llpay_config);
                $temp['llpay_result'] = $this->llpay->get_result($temp['recharge']['recharge_no'],date('YmdHis',$temp['recharge']['add_time']));
                $temp['llpay_result'] = json_decode($temp['llpay_result'],true);
                if($temp['llpay_result']['ret_code'] == '0000'){
                    switch($temp['llpay_result']['result_pay']){
                        case 'SUCCESS': //更新数据信息
                            $update_data = array(
                                'status'=>2,
                                'source'=>$temp['llpay_result']['oid_paybill']
                            );
                            if($temp['recharge']['amount'] != $temp['llpay_result']['money_order']){ //处理金额
                                $update_data['amount'] = $temp['llpay_result']['money_order'];
                            }
                            $this->db->trans_start();//开启事务
                            $query = $this->c->update(self::recharge,array('where'=>array('recharge_no'=>$temp['llpay_result']['no_order'])),$update_data);
                            if( ! empty($query)){
                                //添加 资金记录
                                $query = $this->_add_cash_flow($temp['recharge']['uid'],$temp['llpay_result']['amount'],$temp['llpay_result']['no_order'],'连连支付充值');
                            }
                            $this->db->trans_complete();
                            $query = $this->db->trans_status();

                            if( ! empty($query)){ //
                                $this->load->view(self::dir.'recharge_success',$temp['llpay_result']);
                            }
                            break;
                        case 'PROCESSING':
                            header("Content-type:text/html;charset=utf-8");
                            echo '<h3 style="text-align: center;">银行处理中..</h3>';
                            exit();
                            break;
                        case 'REFUND':
                            header("Content-type:text/html;charset=utf-8");
                            echo '<h3 style="text-align: center;">已退款无法提交</h3>';
                            exit();
                            break;
                        default://WAITING   FAILURE  重新提交
                            $card_info = $this->c->get_row(self::card,array('where'=>array('id'=>$temp['recharge']['bank'])));
                            //验证是不是自己的卡
                            if($card_info['uid'] != $this->session->userdata('uid')){
                                $card_info = array(
                                    'account'=>'',
                                    'remarks'=>''
                                );
                            }
                            if(empty($card_info['account'])){
                                header("Content-type:text/html;charset=utf-8");
                                echo '<h3 style="text-align: center;">订单的银行卡信息查询失败,可能该订单的银行卡您已移除无法提交！</h3>';
                                exit();
                            }

                            $sbmit = array(
                                'no_order'    =>$temp['recharge']['recharge_no'],//商户系统唯一订单号
                                'dt_order'    =>date('YmdHis',$temp['recharge']['add_time']),//订单时间 YYYYMMDDH24MISS 14位
                                'name_goods'  =>'聚雪球充值',//商品名称
                                'card_no'     =>$card_info['account'],//卡号
                                'no_agree'    =>$card_info['remarks'],//签约号
                                'money_order' =>$temp['recharge']['amount']//交易金额 元 大于0 小数点两位
                            );
                            $form_html = $this->llpay->submit($sbmit);
//                            echo $form_html;
                            $this->session->set_userdata(array('llpay_racharge_form'=>$form_html));
                            $this->load->view(self::dir.'recharge_confirm');
                    }
                }else{
//                    header("Content-type:text/html;charset=utf-8");
//                    echo '<h3 style="text-align: center;">提示：'.$temp['llpay_result']['ret_msg'].'</h3>';
//                    exit();
                    $card_info = $this->c->get_row(self::card,array('where'=>array('id'=>$temp['recharge']['bank'])));
                    //验证是不是自己的卡
                    if($card_info['uid'] != $this->session->userdata('uid')){
                        $card_info = array(
                            'account'=>'',
                            'remarks'=>''
                        );
                    }

                    if(empty($card_info['account'])){
                        header("Content-type:text/html;charset=utf-8");
                        echo '<h3 style="text-align: center;">订单的银行卡信息查询失败,可能该订单的银行卡您已移除无法提交！</h3>';
                        exit();
                    }
                    $sbmit = array(
                        'no_order'    =>$temp['recharge']['recharge_no'],//商户系统唯一订单号
                        'dt_order'    =>date('YmdHis',$temp['recharge']['add_time']),//订单时间 YYYYMMDDH24MISS 14位
                        'name_goods'  =>'聚雪球充值',//商品名称
                        'card_no'     =>$card_info['account'],//卡号
                        'no_agree'    =>$card_info['remarks'],//签约号
                        'money_order' =>$temp['recharge']['amount']//交易金额 元 大于0 小数点两位
                    );
                    $form_html = $this->llpay->submit($sbmit);
//                    echo $form_html;
                    $this->session->set_userdata(array('llpay_racharge_form'=>$form_html));
                    $this->load->view(self::dir.'recharge_confirm');
                }
            }else{
                header("Content-type:text/html;charset=utf-8");
                echo '<h3 style="text-align: center;">无效账单</h3>';
                exit();
            }
            unset($temp);
        }else{
            $temp['recharge_no'] = $this->c->transaction_no(self::recharge, 'recharge_no');
            $temp['account'] = $this->input->post('account',true);
            $temp['amount'] = (float)$this->input->post('amount');
            if( ! empty($temp['amount']) &&  ! empty($temp['account'])){
                $temp['bank_id'] =  (int)$this->input->post('bank_id');
                $temp['bank_name'] = $this->input->post('bank_name');
                $temp['card_id'] =  $this->input->post('card_id',true);
                $temp['no_agree'] =  ''; //签约号
                if(empty($temp['card_id'])){ //账户no。为空 则新曾银行账户
//                    $temp['is_bind'] = $this->c->count(self::card,array('where'=>array('account'=>$temp['account'],'uid'=>$this->session->userdata('uid'),'status'=>1)));
                    $temp['is_bind'] = $this->user_api->get_user_card($this->session->userdata('uid'),$temp['account'])['data'];
                    if(empty($temp['is_bind'])){ //不存在 则新增
                        $temp['new_card_data'] = array(
                            'card_no'   => $this->c->transaction_no(self::card, 'card_no'),
                            'uid'       => $this->session->userdata('uid'),
                            'real_name' => $this->session->userdata('real_name'),
                            'account'   => $temp['account'],
                            'bank_id'   => $temp['bank_id'],
                            'bank_name' => $temp['bank_name'],
                            'bankaddr'  => '默认地址',
                            'province'  => '默认地址',
                            'city'      => '默认地址',
                            'remarks'   => '',
                            'dateline'  => time(),
                            'status'  => 0
                        );
                        $query = $this->c->insert(self::card, $temp['new_card_data']);
                        $temp['card_id'] = $query;
                    }
                }else{
                    $temp['no_agree'] = $this->c->get_one(self::card,array('select'=>'remarks','where'=>array('id'=>$temp['card_id'])));
                }

                $temp['add_time'] = time();
                $temp['data'] = array(
                    'recharge_no' => $temp['recharge_no'],
                    'uid'         => $this->session->userdata('uid'),
                    'type'        => 3,
                    'bank'        => $temp['card_id'],
                    'amount'      => $this->input->post('amount'),
                    'remarks'     => '连连支付会员充值',
                    'add_time'    => $temp['add_time']
                );

                $query = $this->c->insert(self::recharge, $temp['data']);
                if( ! empty($query)){
                    $sbmit = array(
                        'no_order'    =>$temp['recharge_no'],//商户系统唯一订单号
                        'dt_order'    =>date('YmdHis',$temp['add_time']),//订单时间 YYYYMMDDH24MISS 14位
                        'name_goods'  =>'聚雪球充值',//商品名称
                        'card_no'     =>$temp['account'],//卡号
                        'no_agree'    =>$temp['no_agree'],//签约号
                        'money_order' =>$temp['amount']//交易金额 元 大于0 小数点两位
                    );
                    $this->load->library('llpay',$this->_llpay_config);
                    $form_html = $this->llpay->submit($sbmit);

                    unset($temp);
//                    echo $form_html;
                    $this->session->set_userdata(array('llpay_racharge_form'=>$form_html));
                    $this->load->view(self::dir.'recharge_confirm');
                }else{
                    redirect(self::dir.'home/recharge','refresh');
                }
            }else{
                redirect('mobiles/home/recharge');
            }
        }
    }

    public function recharge_iframe(){
        $data['form'] = $this->session->userdata('llpay_racharge_form');
        $this->load->view(self::dir.'recharge_iframe',$data);
    }

    /**
     * 充值成功
     * 接受的post信息
     * oid_partner":"201103171000000000",
     *   "dt_order":"20130515094013",
     *   "no_order":"2013051500001",
     *   "oid_paybill":"2013051613121201",
     *   "money_order":"210.97",
     *   "result_pay":"SUCCESS",
     *   "settle_date":"20130516",
     *   "info_order":"用户13958069593购买了3桶羽毛球",
     *   "pay_type":"2", "bank_code":"01020000",
     */
    public function recharge_success(){
        $data = $this->input->post('res_data',true);
        if( ! empty($data)){
            $this->load->library("llpay");
            $data = $this->llpay->sign_verify($data);
            $data = json_decode($data,true);
        }
        $this->load->view(self::dir.'recharge_success',$data);
    }

    /**
     *   连连支付 异步通知处理
     * 接受的必要信息
     *"oid_partner":"201103171000000000",
     *   "dt_order":"20130515094013",
     *   "no_order":"2013051500001",
     *   "oid_paybill":"2013051613121201",
     *   "money_order":"210.97",
     *   "result_pay":"SUCCESS",
     *   "settle_date":"20130516",
     *   "info_order":"用户13958069593购买了3桶羽毛球",
     *   "pay_type":"2", "bank_code":"01020000",
     */
    public function llpay_notify(){
        $data = file_get_contents("php://input");
        $this->load->library('llpay',$this->_llpay_config);
        $data = $this->llpay->sign_verify($data);
        $data = json_decode($data,true);

        if(isset($data['result_pay']) && $data['result_pay'] == 'SUCCESS'){  //充值成功
            //修改充值记录
            if( ! empty($data['no_order'])){
                $recharge = $this->c->get_row(self::recharge,array('where'=>array('recharge_no'=>$data['no_order'])));
                if( ! empty($recharge) && $recharge['status'] == 0){
                    $update_data = array(
                        'status'=>2,
                        'source'=>$data['oid_paybill']
                    );
                    if($recharge['amount'] != $data['money_order']){ //处理金额
                        $update_data['amount'] = $data['money_order'];
                    }
                    $this->db->trans_start();//开启事务
                    $query = $this->c->update(self::recharge,array('where'=>array('recharge_no'=>$data['no_order'])),$update_data);
                    if( ! empty($query)){
                        //添加 资金记录
                        $query = $this->_add_cash_flow($recharge['uid'],$data['money_order'],$data['no_order'],'连连支付充值');
                    }
                    if( ! empty($query)){
                        $balance = $this->_get_user_balance();
                        $this->session->set_userdata(array('balance'=>$balance));

                        //添加no_agree
                        if(isset($data['no_agree']) && !empty($data['no_agree'])){
                            $card_info = $this->c->get_row(self::card,array('where'=>array('id'=>$recharge['bank'])));
                            if( $card_info['remarks'] == ''){
                                $this->c->update(self::card,array('where'=>array('id'=>$recharge['bank'])),array('remarks'=>$data['no_agree']));
                            }
                        }
                    }
                    $this->db->trans_complete();
                    $query = $this->db->trans_status();

                    if( ! empty($query)){ //回应连连支付
                        echo json_encode(array('ret_code'=>'0000','ret_msg'=>'交易成功'));
                    }
                }
            }

            //变更 余额信息
        }
    }

    /**
     *提现
     */
    public function transfer(){
        if($this->input->is_ajax_request() == TRUE){
            //验证短信
            $sms_check = $this->commons_api->validation_authcode(
                $this->session->userdata('mobile'),
                $this->input->post('authcode',true),
                'transfer',
                $this->session->userdata('uid')
            );
            if($sms_check['status'] != '10000'){
                exit(json_encode($sms_check));
            }

            //执行体现处理
            $data = $this->cash_api->user_transaction(
                $this->session->userdata('uid'),
                $this->input->post('amount',true),
                $this->input->post('card_no',true),
                $this->input->post('security',true)
            );
            //操作成功 处理余额session
            if($data['status'] == '10000'){
                $this->session->set_userdata(array('balance'=>$data['data']['balance']));
            }
            exit(json_encode($data));
        }

        $this->_check_realname(true);
        //查询 余额 和 绑定的银行卡信息
        $data['balance'] = (float)$this->cash_api->get_user_balance($this->session->userdata('uid'))['data']['balance'];
        $data['card'][]  = $this->user_api->get_user_card($this->session->userdata('uid'))['data'];
        //没有绑定银行卡 则跳转到我的银行卡进行绑定
        if(empty($data['card'][0])){
            redirect(self::dir.'home/my_card','location');
        }
        //查询今日是否有提现
        $data['today_transfer'] = $this->c->count('user_transaction',
            array(
                'where'=>array(
                    'uid'=>$this->session->userdata('uid'),
                    'add_time >='=>strtotime(date('Y-m-d').' 00:00:00'),
                    'add_time <='=>time(),
                )
            )
        );
        $this->load->view(self::dir.'transfer',$data);
    }

    /**
     * 提现申请成功 的提示页
     */
    public function transfer_success(){
        $data['amount'] = (int)$this->input->get('amount');
        $this->load->view(self::dir.'transfer_success',$data);
    }



    /************************************--个人中心相关--***************************************************************/
    /**
     * 个人中心主页
     */
    public function my_center(){
        if($this->input->is_ajax_request() == TRUE){
            $uid = $this->session->userdata('uid');
            session_write_close();
            //可用余额和累计收益的统计
            $total = $this->cash_api->get_user_cash_total($uid)['data'];
            $data['data']['my_balance'] = (float)$total['balance'];        //可用余额
            $data['data']['all_income'] = (float)$total['receive_interest_total'];
            exit(json_encode($data));
        }

        $this->_check_to_login();
        $this->load->view(self::dir.'my_center');
    }

    /**
     *我的余额信息【可用余额】的显示和ajax处理
     */
    public function my_balance(){
        if($this->input->is_ajax_request() == TRUE){
            $uid = $this->session->userdata('uid');
            session_write_close();
            $temp['total'] = $this->cash_api->get_user_cash_total($uid)['data'];
            $data['data'] = array(
                'my_balance'         => (float)$temp['total']['balance'],               //可用余额
                'my_wait_principal'  => (float)$temp['total']['wait_principal_total'],  //待收本金
                'my_invest_freeze'   => (float)$temp['total']['invest_freeze_total'],   //投资冻结金额
                'my_transfer_freeze' => (float)$temp['total']['transfer_freeze_total'], //提现冻结金额
                'my_amount'          => (float)$temp['total']['property_total']         //总资产
            );
            unset($temp);
            exit(json_encode($data));
        }

        $this->_check_to_login();
        $this->load->view(self::dir.'my_balance');
    }

    /**
     * 充值记录的ajax处理方法
     */
    public function ajax_get_recharge_list(){
        $data = $this->cash_api->get_user_recharge_list($this->session->userdata('uid'));
        if( ! empty($data['data'])){
            exit(json_encode(array('code'=>0,'msg'=>'ok','data'=>$data['data']['data'])));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'there have no data now','data'=>'')));
        }
    }

    /**
     *我的收入信息 （累计收益）
     */
    public function my_income(){
        if($this->input->is_ajax_request() == TRUE){
            $uid = $this->session->userdata('uid');
            session_write_close();
            $temp['total'] = $this->cash_api->get_user_cash_total($uid)['data'];
            $data['data'] = array(
                'my_invest'         => (float)$temp['total']['invest_total'],           //我的总投资
                'my_interest'       => (float)$temp['total']['receive_interest_total'], //已收利息
                'my_wait_principal' => (float)$temp['total']['wait_principal_total'],   //待收本金
                'my_wait_interest'  => (float)$temp['total']['wait_interest_total'],    //待收利息
                'my_today_invest'   => (float)$this->project_api->get_user_today_interest($this->session->userdata('uid'))['data']['interest']//今日收益
            );
            unset($temp);
            exit(json_encode($data));
        }

        $this->_check_to_login();
        $this->load->view(self::dir.'my_income');
    }

    /**
     * 收益记录的ajax处理方法
     */
    public function ajax_get_interest_list(){
        $data = $this->project_api->get_user_project_list($this->session->userdata('uid'))['data'];
        if( ! empty($data['data'])){
            exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
        }else{
            exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
        }
    }

    /**
     *我的银行卡
     */
    public function my_card(){
        $this->_check_to_login();
        $this->_check_realname(true);

        $data['account'][] = $this->user_api->get_user_card($this->session->userdata('uid'))['data'];
        if(empty($data['account'][0]))$data['account'] = array();

        $this->load->view(self::dir.'my_card',$data);
    }

    /**
     *绑定银行卡
     */
    public function my_card_bind(){
        if($this->input->is_ajax_request() == TRUE){
            //验证短信
            $sms_check = $this->commons_api->validation_authcode(
                $this->session->userdata('mobile'),
                $this->input->post('authcode',true),
                'card_bind',
                $this->session->userdata('uid')
            );
            if($sms_check['status'] != '10000'){
                exit(json_encode($sms_check));
            }

            $data = $this->user_api->card_bind(
                $this->session->userdata('uid'),
                $this->input->post('amount',true),
                $this->input->post('bank_id',true),
                $this->input->post('bankaddr',true)
            );
            exit(json_encode($data));
        }

        //银行列表
        $data['bank'] = $this->commons_api->get_bank('100,102,103,104,105,308,302,303,305,310,307,309')['data'];

        //地址处理
        $data['province']  = $this->commons_api->get_region(1)['data'];
        $data['city']      = $this->commons_api->get_region(2)['data'];
        $data['distic']    = $this->commons_api->get_region(52)['data'];

        $this->load->view(self::dir.'my_card_bind',$data);
    }

    /**
     *解绑银行卡
     */
    public function my_card_unbind(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->user_api->card_unbind(
                $this->session->userdata('uid'),
                $this->input->post('card_no',true),
                $this->input->post('security',true)
            );
            exit(json_encode($data));
        }

        $card_no = $this->input->get('card_no',true);

        //查询解绑的银行卡信息
        if( ! empty($card_no)){
            $data = $this->user_api->get_user_card($this->session->userdata('uid'),$card_no)['data'];
        }else{
            redirect(self::dir.'home/my_card','refresh');
        }

        $this->load->view(self::dir.'my_card_unbind',$data);
    }

    /**
     * 解绑成功 提示页
     */
    public function my_card_success(){
        $this->load->view(self::dir.'my_card_success');
    }

    /**
     * 已投项目
     */
    public function my_project(){
        //ajax处理
        if($this->input->is_ajax_request() == TRUE){
            $status = $this->input->get('status');
            $status = str_replace('-',',',$status);
            $data = $this->project_api->get_user_project_list($this->session->userdata('uid'),$status)['data'];

            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $this->load->view(self::dir.'my_project');
    }

    /*
     public function terms(){
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no',TRUE);

        //查询 用户（甲方）地址信息
        $this->load->model('web_1/user/authentication_model','authentication');
        $temp['a_addr'] = $this->user->get_user_address(3);
        $temp['a_addr'] = $this->authentication->get_region_str($temp['a_addr']);

        //用户（甲方）信息整合
        $data['data'] = array(
            'a_real_name' => profile('real_name'),
            'a_nric'      => profile('nric'),
            'a_mobile'    => profile('mobile'),
            'a_addr'      => $temp['a_addr']
        );

        $temp['borrow'] = $this->c->get_row(self::borrow,
            array(
                'select' =>'SUM('.join_field('amount',self::payment).') as invest_amount,'.join_field('amount,rate,confirm_time',self::borrow).','
                .join_field('uid',self::user).' as b_uid,'.join_field('real_name',self::user).' as b_real_name,'.join_field('mobile',self::user).' as b_mobile,'
                .join_field('nric',self::user).' as b_nric',
                'where'  =>array(join_field('borrow_no',self::borrow)=>$temp['borrow_no'],join_field('uid',self::payment)=>profile('uid'),join_field('type',self::payment)=>1,join_field('status',self::payment)=>1),
                'join'   =>array(
                    array(
                        'table' =>self::payment,
                        'where' =>join_field('borrow_no',self::borrow).'='.join_field('borrow_no',self::payment)
                    ),
                    array(
                        'table' =>self::user,
                        'where' =>join_field('uid',self::user).'='.join_field('uid',self::borrow)
                    )
                )
            )
        );

        if( ! empty($temp['borrow']))
        {
            $temp['borrow']['amount_upper']        = num2cny($temp['borrow']['amount']);
            $temp['borrow']['b_mobile']            = secret($temp['borrow']['b_mobile'], 5);
            $temp['borrow']['invest_amount_upper'] = num2cny($temp['borrow']['invest_amount']);
            $temp['borrow']['b_addr']              = $this->user->get_user_address(3, $temp['borrow']['b_uid']);
            $temp['borrow']['b_addr']              = $this->authentication->get_region_str($temp['borrow']['b_addr']);
            $temp['borrow']['b_real_name_nric']    = $temp['borrow']['b_real_name'].'('.$temp['borrow']['b_nric'].")";
        }
        $data['data'] = array_merge($data['data'],$temp['borrow']);

        $this->load->view(self::dir.'terms', $data);
    }
    */

    /**
     *  回款计划
     */
    public function my_interest(){
        //更多 的ajax处理
        if($this->input->is_ajax_request() == TRUE){
            $status = $this->input->post('status',true);
            $data = $this->project_api->get_user_project_list($this->session->userdata('uid'),$status)['data'];
            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $this->load->view(self::dir.'my_interest');
    }

    /**
     *  交易明细
     */
    public function my_cash_log(){
        if($this->input->is_ajax_request() == TRUE){
            $temp = $data = array();
            //status   支出 收入 全部的  标识参数
            $temp['status'] = (int)$this->input->get('status');
			switch($temp['status']){
                case 1://收入 待确定
                    $temp['in_str'] = array(1,7,11,21,22);
                    break;
                case 2://支出
                    $temp['in_str'] = array(2,5,10,20);
                    break;
                case 3://冻结
                    $temp['in_str'] = array(3,4);
                    break;
                default:
                    $temp['in_str'] = array(1,2,3,4,5,7,10,11,20,21,22);
            }

            if($temp['status'] == 3){ //投资冻结
                $temp['where'] = array(
                    'where'=>array(join_field('uid',self::payment)=>$this->session->userdata('uid'),join_field('type',self::payment)=>1),
                    'select'=>join_field('subject',self::borrow).','.join_field('amount,dateline',self::payment),
                    'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(2,3)),
                    'join'=>array('table'=>self::borrow,'where'=>join_field('borrow_no',self::borrow).'='.join_field('borrow_no',self::payment))
                ); 
                $temp['data'] = $this->c->show_page(self::payment,$temp['where']);
                if( ! empty($temp['data'])){
                    foreach ($temp['data']['data'] as $key => $value) {
                        $temp['data']['data'][$key]['remarks'] = $value['subject'];
                        unset($temp['data']['data'][$key]['subject']);
                        $temp['data']['data'][$key]['status'] = 3;
                    }
                }
                $data = $temp['data'];
            }elseif($temp['status'] == 4){ //提现冻结
                $temp['where'] = array(
                    'where'=>array('uid'=>$this->session->userdata('uid'),'status'=>0),
                    'select'=>'amount,remarks,add_time'
                );
                $temp['data'] = $this->c->show_page(self::transfer,$temp['where']);
                if( ! empty($temp['data'])){
                    foreach ($temp['data']['data'] as $key => $value) {
                        $temp['data']['data'][$key]['dateline'] = $value['add_time'];
                        unset($temp['data']['data'][$key]['add_time']);
                        $temp['data']['data'][$key]['status'] = 4;
                    }
                }
                $data = $temp['data'];
            }else{
                $temp['where'] = array(
                    'where'=>array(
                        'uid'=>$this->session->userdata('uid')
                    ),
                    'where_in'=>array(
                        'field'=>'type',
                        'value'=>$temp['in_str']
                    ),
                    'order_by'=>'id desc'
                );
                $data = $this->c->show_page(self::cash,$temp['where']);
            }
            unset($temp);

            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
            
        }

        $this->load->view(self::dir.'my_cash_log');
    }

    /**
     * cash_log 统计的ajax方法
     */
    public function ajax_get_cash_log_total(){
        if($this->input->is_ajax_request() == TRUE){
            $uid = $this->session->userdata('uid');
            session_write_close();

            $data = array('income_total'=>0,'pay_total'=>0,'frozen_total'=>0);

            $temp['my_invest_freeze']   = $this->cash_api->get_user_invest_freeze($uid);          //投资冻结金额
            $temp['my_transfer_freeze'] = $this->cash_api->get_user_transfer_freeze($uid);         //提现冻结金额

            $data['frozen_total']       = round($temp['my_invest_freeze']+$temp['my_transfer_freeze'],2);//冻结合计
            $data['income_total']       = $this->get_income_total($uid);
            $data ['pay_total']         = $this->get_pay_total($uid);

            unset($temp);
            exit(json_encode(array('data'=>$data)));
        }
    }
    public function get_income_total($uid=0){
        $query = 0;
        $temp  = array();


        if($uid > 0){
            $temp['my_principal_interest'] = $this->cash_api->get_user_receive_principal_interest($uid); //我的已收本金和利息
            $query =  $temp['my_principal_interest']['receive_interest'];  //已收利息

            $temp['recharge_total'] = $this->c->get_one(self::recharge,array('select'=>'SUM(amount)','where'=>array('uid'=>$uid,'status >='=>1)));
            $query = round($query+$temp['recharge_total'],2);
        }

        unset($temp);
        return $query;
    }
    public function get_pay_total($uid=0){
        $query = 0;
        $temp  = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)+ sum(charge)',
                'where'    => array('uid' => $uid, 'status' => 1)
            );

            $query = (float)$this->c->get_one('user_transaction', $temp['where']);
        }

        unset($temp);
        return $query;
    }


   /**
     *我的雪球
     */
    public function my_integral(){
		$data_balance=array();
        $temp['where'] = array(
            'select'   => 'balance',
            'where'    => array('uid' => $this->session->userdata('uid')),
            'order_by' => 'id desc'
        );
        $data_balance['balance'] = $this->c->get_one(self::snowballdtl, $temp['where']);
		if($this->input->is_ajax_request() == TRUE){
            //status   支出 收入 全部的  标识参数

             $temp['status'] = (int)$this->input->get('status');
			switch($temp['status']){
                case 1://收入 待确定
                    $temp['in_str'] = '1';
                    break;
                case 2://支出
                    $temp['in_str'] = '0';
                    break;
                default:
                    $temp['in_str'] = '1';

            }

            $temp['where'] = array(
                'where'=>array(
                    'uid'=>$this->session->userdata('uid'),
					'flag'=>$temp['in_str']
                ),
            );
            $data = $this->c->show_page(self::snowballdtl,$temp['where'],"");

            unset($temp);

            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $this->load->view(self::dir.'my_integral',$data_balance);
    }

    /**
     *我的金币
     */
    public function my_gold(){
        $this->load->view(self::dir.'my_gold');
    }


    /************************************--设置--**********************************************************************/
    /**
     *  解绑手机
     */
    public function phone(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->phone_unbind();
            exit(json_encode($data));
        }

        $this->load->view(self::dir.'phone');
    }

    /**
     *绑定手机
     */
    public function phone_bind(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->phone_bind();
            exit(json_encode($data));
        }

        $token = $this->input->get('token',true);
        if(empty($token)){
            redirect(self::dir.'home/phone','refresh');
        }
        $this->load->view(self::dir.'phone_bind',array('token'=>$token));
    }

    /**
     * 绑定手机成功提示页
     */
    public function phone_success(){
        $this->load->view(self::dir.'phone_success');
    }

    /**
     *设置资金密码
     */
    public function security(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->security();
            exit(json_encode($data));
        }

        //如果有资金密码  跳转到修改资金密码
        if(profile('security')){
            redirect(self::dir.'home/security_change','location');
        }
        $this->load->view(self::dir.'security');
    }

    /**
     *修改资金密码
     */
    public function security_change(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->security();
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'security_change');
    }

    /**
     *修改资金密码成功提示页
     */
    public function security_success(){
        $this->load->view(self::dir.'security_success');
    }

    /**
     *修改登陆密码
     */
    public function password(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->password();
            exit(json_encode($data));
        }

        $this->load->view(self::dir.'password');
    }

    /**
     * 登陆密码修改成功提示页
     */
    public function password_success(){
        $this->load->view(self::dir.'password_success');
    }


    /************************************--关于我们--******************************************************************/
   /**
     * 关于我们
     */
    public function about_us(){
        $this->load->view(self::dir.'about_us');
    }

	 /**
     * 平台介绍
     */
    public function ptjs(){
        $this->load->view(self::dir.'ptjs');
    }

	 /**
     * 安全保障
     */
    public function aqbz(){
        $this->load->view(self::dir.'aqbz');
    }

	 /**
     * 充值说明
     */
    public function recharge_notice(){
		
		$temp['where'] = array(
                'where'  => array('status' => 1)
		);

        $data['bank'] = $this->c->get_all(self::bank, $temp['where']); //银行列表
			
        $this->load->view(self::dir.'recharge_notice',$data);
    }

	



    /**
     * 实名验证
     * @return bool
     */
    protected function _check_realname($type=false){
        if( !$this->session->userdata('uid'))redirect(self::dir.'home/real_name','location');

        //加入企业认证后的实名验证
        if($type){ //type=true 标识 严格检查 必须所有认证通过 =1|2
            if($this->session->userdata('clientkind') !='1' && $this->session->userdata('clientkind') != '2'){
                if(!$this->session->userdata('clientkind') || $this->session->userdata('clientkind') == '-1'){
                    redirect(self::dir.'home/real_name','location');
                }else{
                    exit('<h2>请进入PC版进行企业认证资料提交!</h2>');
                }
            }
        }else{ //非严格认证 =1|2|-3|-4|-5 都进行了实名认证 但企业认证是部分资料不完整
            if( !in_array($this->session->userdata('clientkind'),array('1','2','-3','-4','-5'))){
                if($this->session->userdata('clientkind') == '-2'){
                    exit('<h2>请进入PC版进行企业认证资料提交!</h2>');
                }else{
                    redirect(self::dir.'home/real_name','location');
                }
            }
        }
    }

    /**
     * 添加充值记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  float   $amount 充值金额
     * @param  string  $source 记录来源
     * @return boolean
     */

    private function _add_cash_flow($uid = 0, $amount = 0, $source = '' , $remarks = '会员充值')
    {
        $query = FALSE;
        $temp  = array();
        if( ! empty($uid) && ! empty($amount) && ! empty($source))
        {
            $temp['where'] = array('where' => array('source' => $source));
            $temp['count'] = $this->c->count(self::flow, $temp['where']);

            if($temp['count'] == 0)
            {
                $temp['balance'] = $this->_get_user_balance($uid);

                $temp['data'] = array(
                    'uid'      => $uid,
                    'type'     => 1,
                    'amount'   => $amount,
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
     * 获取会员余额
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return float
     */

    private function _get_user_balance($uid = 0)
    {
        $balance = 0;
        $temp    = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                'select'   => 'balance',
                'where'    => array('uid' => $uid),
                'order_by' => 'id desc'
            );

            $balance = $this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }
	

	 /**
	 *yx 9-1
     * 红包活动
     */
    public function redbag(){
		$data=$temp=array();
		$uid=$this->session->userdata('uid');
		$temp['where_receive'] = array(
				'select'   => 'id,amount,active,source,deadline',
                'where'    => array('uid' => $uid , 'status' => '0' ),
                'order_by' => 'id desc'
            );
		$temp['where_receive_log'] = array(
                'where'    => array('uid' => $uid , 'status' => '1' ),
                'order_by' => 'receive_time desc'
            );
		$data['receive'] = $this->c->get_all(self::redbag , $temp['where_receive']);
		$data['receive_log'] = $this->c->get_all(self::redbag , $temp['where_receive_log']);
        $this->load->view(self::dir.'redbag',$data);
    }


    /**
   	 *yx 9-1
     * 注销
     */
    public function ajax_get_redbagdata(){
		$data=array();
	    $data = $this->app->ajax_get_redbagdata();
        if($this->input->is_ajax_request() == TRUE){
            exit(json_encode($data));
        }
    }


	 /**
	 *yx 9-1
     * 红包活动ajax
     */
     public function redbag_ajax(){
		$data=$temp=array();
		  $uid=$this->session->userdata('uid');
		 $temp['id'] = $this->input->get('id');
		$temp['where'] = array(
				'select'   => 'amount',
                'where'    => array('id' => $temp['id'],'uid' => $uid,'status'=>'0')
            );
		  $temp['amount'] = $this->c->get_one(self::redbag , $temp['where']);
		$temp['where'] = array(
				'select'   => 'count(*)',
                'where'    => array('id' => $temp['id'],'uid' => $uid,'status'=>'0')
            );
		  $temp['num'] = $this->c->get_one(self::redbag , $temp['where']);
		
		if($temp['num']==1&&$uid!=''&&$temp['amount']!=''){
		$_SESSION['balance']=($_SESSION['balance']+$temp['amount']);
		$temp['balance'] = $this->_get_user_balance($uid);
		$temp['recharge']=$this->c->transaction_no(self::redbag, 'id');
		$temp['insert'] = array(
                                            'uid'      => $uid,
                                            'type'     => 11,
                                            'amount'   => $temp['amount'],
                                            'balance'  => round($temp['balance'] + $temp['amount'], 2),
                                            'source'   => $temp['recharge'],
                                            'remarks'  => '红包',
                                            'dateline' => time()
                                        );
		$this->c->insert(self::flow, $temp['insert']);
		 }

		$temp['where_update'] = array(
                'where'    => array('id' => $temp['id'] )
            );
		$this->c->update(self::redbag , $temp['where_update'] , array('status' => "1","receive_time" => time()));

		$temp['where'] = array(
				'select'   => 'id,amount,active,source,deadline,status',
                'where'    => array('uid' => $uid , 'status' => '1'),
                'order_by' => 'receive_time desc'
            );
		$data = $this->c->get_all(self::redbag , $temp['where']);
        if($this->input->is_ajax_request() == TRUE){
            exit(json_encode($data));
        }
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

    /*******************************/
    public function wx(){
        $this->load->library('wx');
        $data = $this->wx->authorization('userinfo');
        echo '<meta name="viewport"content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>';
        echo '<div style="width: 50%;margin: 20% auto;">头像:<img src="'.$data['headimgurl'].'" style="width:100px;height:100px;" /><br/>'.'用户名:'.$data['nickname'].'<br/></div>';
    }
}