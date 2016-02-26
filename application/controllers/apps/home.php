<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 14:45
 */

/**
 * app 控制器
 * Class Home
 */
class Home extends MY_Controller{
    const dir       = 'apps/';          //当前控制器controller model view目录

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
        //验证app 登陆 是否GET uid参数
        $this->_check_uid();
        if( ! $this->session->userdata('captcha'))$this->session->set_userdata(array('captcha'=>md5('wang')));//发送短信 处理

        //连连支付 配置一部通知地址和返回地址
        $this->_llpay_config['notify'] = site_url(self::dir.'home/llpay_notify');
        $this->_llpay_config['return_url'] = site_url(self::dir.'home/recharge_success');

        //分页参数的修正
        if(isset($_GET['pageId'])){
            $_GET['limit']    = (int)$this->input->get('pageSize');
            $_GET['per_page'] = ((int)$this->input->get('pageId')-1)*(int)$this->input->get('pageSize');
        }

        //加载必要model
        $this->load->model('web_1/user1_model','user');                         //用户
        $this->load->model('web_1/borrow_model', 'borrow');                   //借款
        $this->load->model('web_1/user/transaction_model', 'transaction'); //交易 充值提现
        $this->load->model(self::dir.'app_model', 'app');               //app model
        $this->load->model('web_1/send_model', 'send');                       //发送短信
        $this->load->model('web_1/user/account_model','account');           //会员银行帐户



        $this->load->model('api/user_model','user_api');       //2.0版user相关model
        $this->load->model('api/project_model','project_api'); //2.0版项目相关model
        $this->load->model('api/cash_model','cash_api');       //2.0版资金相关model
        $this->load->model('api/commons_model','commons_api'); //2.0版资金相关model
		$this->load->model('api/other_model','other_api'); //2.0版资金相关model

    }

/************************************---注册---***********************************************/
    /**
     * 注册的显示和ajax处理
     */
    public function register(){
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

        $this->load->view(self::dir.'register');
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
     * 验证手机号码是否注册
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return object
     */
    public function is_registered(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您输入的号码已注册!');

        $temp['mobile'] = $this->input->post('mobile', TRUE);

        if($this->is_mobile( $temp['mobile'] )){
            $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
            $temp['count'] = $this->c->count(self::user, $temp['where']);

            if($temp['count'] == 0){
                $data = array('code' => 0, 'msg' => '您输入的号码可以注册!');
            }
        }

        unset($temp);
        exit(json_encode($data));
    }

    /**
     * 验证用户手机号码格式
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    public function is_mobile($mobile = ''){
        return (preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }
/************************************---注册---***********************************************/


/************************************--主页和项目相关--***********************************************/
    /**
     *主页
     */
    public function index(){
        $temp['total'] = $this->cash_api->get_cash_total();
        if($temp['total']['status'] == '10000'){
            $data['total'] = $temp['total']['data'];
        }
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
                    $data[$k]['link_url'].= strpos($data[$k]['link_url'],'?')?'&ENV=APP':'?ENV=APP';
                }
            }
        }
        exit(json_encode($data));
    }

    /**
     * ajax  获得项目列表
     */
    public function get_project_list(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->get_project_list();
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
        $data = $this->borrow->get_borrow_info();
        if(empty($data)){
            redirect(self::dir.'home/index', 'refresh');
        }
        //计划表  根据项目状态 确定调用数据库查询还是动态生成的还款计划
        $data['repay_plan']=($data['status'] == 4 || $data['status'] == 7)?$this->borrow->get_repay_plan($this->input->get('borrow_no',true)):$this->borrow->set_repay_plan($this->input->get('borrow_no',true));
        $this->load->view(self::dir.'project_detail',$data);
    }

    /**
     *投资
     */
    public function project_invest(){
        //获取借款项目单号borrow_no
        $borrow_no = $this->input->get('borrow_no',true);
        if(empty($borrow_no)){
            redirect(self::dir.'home/index','refresh');
        }
        //查询该项目信息
        $data = $this->c->get_row(self::borrow,array('where'=>array('borrow_no'=>$borrow_no)));
        if(empty($data)){
            redirect(self::dir.'home/index','refresh');
        }
        $this->load->view(self::dir.'project_invest',$data);
    }

    /**
     *投资确认
     */
    public function project_invest_confirm(){
        //获取投资的借款单号
        $borrow_no = $this->input->get('borrow_no',true);
        if(empty($borrow_no)){
            redirect(self::dir.'home/index','refresh');
        }
        $data = $this->c->get_row(self::borrow,array('where'=>array('borrow_no'=>$borrow_no)));
        if(empty($data)){
            redirect(self::dir.'home/index','refresh');
        }
        //获取将要投资的数目
        $data['to_invest'] = (float)$this->input->get('amount',true);
        $data['balance'] = $this->borrow->get_user_balance(); // 当前账户可用余额
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
                $this->input->post('borrow_no',TRUE),3
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
/************************************--主页和项目相关--***********************************************/


/************************************--我要借款--***********************************************/
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
        $data = array();

        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->apply();
            exit(json_encode($data));
        }

        $data['p_type'] = (int)$this->input->get('type');   //借款类型
        if(empty($data['p_type'])){
            redirect(self::dir.'home/borrow_type','refresh');
        }

        //地址信息 省份 城市 地区  用于地址插件初始化 该get_region_list 用GET获取的参数region_id 所以修改$_get 的参数 获取各级地址信息
        $data['province'] = $this->borrow->get_region_list();
        $this->load->view(self::dir.'borrow',$data);
    }

    /**
     * 借款提交成功提示页
     */
    public function borrow_success(){
        $this->load->view(self::dir.'borrow_success');
    }
/************************************--我要借款--***********************************************/

/************************************--个人详情--***********************************************/
    /**
     *个人详情
     */
    public function profile(){
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
     *实名认证
     */
    public function real_name(){
        if($this->input->is_ajax_request() == TRUE){
            $data        = $this->user_api->real_name($this->input->post('real_name',true),$this->input->post('nric',true),$this->session->userdata('uid'));
            if($data['status'] == '10000'){
                $this->session->set_userdata($data['data']);
            }
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'real_name');
    }

    /**
     *实名认证成功提示页
     */
    public function real_name_success(){
        $this->load->view(self::dir.'real_name_success');
    }

/************************************--个人详情--***********************************************/


/************************************--充值提现--***********************************************/
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
                            echo $form_html;
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
                    echo $form_html;
                }
            }else{
                header("Content-type:text/html;charset=utf-8");
                echo '<h3 style="text-align: center;">无效账单</h3>';
                exit();
            }
            unset($temp);
        }else{
            $temp['recharge_no'] = $this->c->transaction_no(self::recharge, 'recharge_no');
            $temp['account']     = $this->input->post('account',true);
            $temp['amount']      = (float)$this->input->post('amount');
            if( ! empty($temp['amount']) &&  ! empty($temp['account'])){
                $temp['bank_id']     =  (int)$this->input->post('bank_id');
                $temp['bank_name']   = $this->input->post('bank_name');
                $temp['card_id']     =  $this->input->post('card_id',true);
                $temp['no_agree']    =  ''; //签约号
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
                    echo $form_html;
                }else{
                    redirect(self::dir.'home/recharge','refresh');
                }
            }else{
                redirect('apps/home/recharge');
            }
        }
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

/************************************--充值提现--***********************************************/


/************************************--个人中心相关--***********************************************/

    /*
    新：2015-7.30 与寇林沟通后确认
    1.可用余额   ： cash_flow 的最新一条记录的balance
    2.总资产       ：
    3.冻结金额   ：type=3
    4.提现中的金额：type=2
    5.累计收益：TYPE=8
    6.代收本金：type=5
    7.已收本金：TYPE=9
    8.待收利息：需计算
    9.已收利息：TYPE=8
     */

    /**
     *我的余额信息（可用余额）
     */
    public function my_balance(){
        if($this->input->is_ajax_request() == TRUE){
            $data['my_balance']            = $this->user->get_user_balance();        //可用余额
            $temp['my_principal_interest'] = $this->app->get_receive_principal_interest(); //我的已收本金和利息
            $temp['my_invest']             = $this->app->get_user_invest_all();//我的总投资
			$temp['jbb_all_amount']        = $this->app->jbb_all_amount(1);
            $data['my_wait_principal']     = $temp['my_invest']>0?$temp['my_invest']-$temp['my_principal_interest']['receive_principal']:0;
			$data['my_wait_principal']	   = $temp['jbb_all_amount']+$data['my_wait_principal'];
            $data['my_invest_freeze']      = $this->app->get_user_invest_freeze();          //投资冻结金额
            $data['my_transfer_freeze']    = $this->app->get_user_transfer_freeze();         //提现冻结金额
            $data['my_amount']             = $data['my_balance'] + $data['my_wait_principal'] + $data['my_invest_freeze']+$data['my_transfer_freeze'];
            unset($temp);
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'my_balance');
    }

    /**
     * 充值记录的ajax处理方法
     */
    public function ajax_get_recharge_list(){
        $data = $this->transaction->get_recharge_list();
        if( ! empty($data['data'])){
            exit(json_encode(array('code'=>0,'msg'=>'ok','data'=>array_merge($data['data']))));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'there have no data now','data'=>'')));
        }
    }

    /**
     *我的收入信息 （累计收益）
     */
    public function my_income(){
        if($this->input->is_ajax_request() == TRUE){
            $data['my_invest']             =  (float)$this->app->get_user_invest_all();//我的总投资
            $temp['my_principal_interest'] = $this->app->get_receive_principal_interest(); //我的已收本金和利息
            $data['my_interest']           =  $temp['my_principal_interest']['receive_interest'];  //已收利息
            $data['my_wait_principal']     =  round($data['my_invest'] - $temp['my_principal_interest']['receive_principal'],2) ;  //待收本金
            
            $temp['interest']              = $this->app->get_user_interest();//计算所有利息
            $data['my_wait_interest']      =  $temp['interest']>0?round($temp['interest']-$temp['my_principal_interest']['receive_interest'],2):0;  //待收利息
            $data['my_today_invest']       =  $this->user->get_today_amount();  //今日收益

            unset($temp);
            exit(json_encode($data));
        }

        $this->load->view(self::dir.'my_income');
    }

    /**
     * 收益记录的ajax处理方法
     */
    public function ajax_get_interest_list(){
        $data = $this->app->get_invest_list(TRUE);
        if( ! empty($data['data'])){
            foreach($data['data'] as $k=>$v){
                //查询收益情况
                $interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$this->session->userdata('uid'))));
                if($interest){
                    //如果有收益  根据收益数量判断
                    if($interest  > $v['amount']){ //比投资额度大 说明已收益完成
                        $data['data'][$k]['interest']       = round($interest-$v['amount'],2); //收益
                        $data['data'][$k]['type']           = 1; //预计收益和已收益的标识
                        $data['data'][$k]['haved_interest'] = 0;
                    }else{
                        //收益了一部分  前断计算
                        $all_interest = $this->app->get_project_interest(array('mode'=>$v['mode'],'amount'=>$v['amount'],'months'=>$v['months'],'rate'=>$v['rate'],));
                        $data['data'][$k]['interest']       = round($all_interest - $interest,2);
                        $data['data'][$k]['haved_interest'] = $interest;
                        $data['data'][$k]['type']           = 2;
                    }
                }else{
                    $data['data'][$k]['interest']       = $this->app->get_project_interest(array('mode'=>$v['mode'],'amount'=>$v['amount'],'months'=>$v['months'],'rate'=>$v['rate'],));
                    $data['data'][$k]['type']           = 0;
                    $data['data'][$k]['haved_interest'] = 0;
                }
            }
            exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
        }else{
            exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
        }
    }

    /**
     *我的银行卡
     */
    public function my_card(){
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
     * 地区 的ajax处理
     */
    public function ajax_get_region_list(){
        $temp = $data = array();

        $data['city'] = $this->commons_api->get_region((int)$this->input->get('region_id'))['data'];
        $temp['type'] = $this->input->get('type');
        if( !empty($temp['city']) && $temp['type'] == 'city'){ //获取城市和地区
            $data['district']  = $this->borrow->get_region_list($data['city'][0]['region_id'])['data'];
        }else{ //值获取地区
            $data = $data['city'];
        }

        exit(json_encode($data));
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
        //更多 的ajax处理
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->get_invest_list();
            if( ! empty($data['data'])){
                $temp = array();
                $temp['uid'] = $this->session->userdata('uid');

                foreach($data['data'] as $k => $v){
                    if($v['status']=='2'||$v['status']=='3'){
                        $start_time=$v['due_date'];
                    }
                    if($v['status']=='4'||$v['status']=='7'){
                         $start_time=$v['confirm_time']-86400;
                    }
                    $data['data'][$k]['start_time']=date('Y-m-d',$start_time);
                    $start_time=strtotime(date("Y-m-d",$start_time));
                    $temp['where_time'] = array(
                                'select'   => 'pay_date',
                                'where'    => array('uid' => $temp['uid'], 'type' => '3' , 'borrow_no' => $v['borrow_no'] )
                            );
                    $last_time_re = $this->c->get_one(self::payment, $temp['where_time']);
                    if($last_time_re!=''){
                        $data['data'][$k]['last_time']=date("Y-m-d",strtotime($last_time_re));
                        //$last_time=strtotime($last_time_re);
                    }else{
                        $data['data'][$k]['repay_plan']=($v['status'] == 4 || $v['status'] == 7)?$this->borrow->get_repay_plan($v['borrow_no']):$this->borrow->set_repay_plan($v['borrow_no']);
                        foreach($data['data'][$k]['repay_plan'] as $k1 => $v1){
                            $data['data'][$k]['last_time']=date("Y-m-d",strtotime($v1['repay_date']));
                            break;
                        }
                        //$last_time=strtotime($data['data'][$k]['last_time']);
                        unset($data['data'][$k]['repay_plan']);
                    }
                    

                    //查询 是否 已还款完成
                    $repay_interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$this->session->userdata("uid"))));
                    if($repay_interest && $repay_interest>$v['amount']){
//                        $data['data'][$k]['project_interest'] = round($repay_interest-$v['amount'],2);
                        $data['data'][$k]['project_interest'] = bcsub($repay_interest,$v['amount'],2);//2015.12.16-wsb-修改为不进位保留两位小数
                    }else{
                        $data['data'][$k]['project_interest'] = $this->app->get_project_interest(array('mode'=>$v['mode'],'amount'=>$v['amount'],'rate'=>$v['rate'],'months'=>$v['months']));
                        // if($repay_interest){
                        //     $data['data'][$k]['project_interest'] = round($data['data'][$k]['project_interest']-$repay_interest,2);
                        // }
                    }

                    $data['data'][$k]['dateline'] = date("Y-m-d",$data['data'][$k]['dateline'] );

                }

                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $this->load->view(self::dir.'my_project');
    }

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

    /**
     *  回款计划
     */
    public function my_interest(){
        //更多 的ajax处理
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->app->get_invest_list(TRUE);
            if( ! empty($data['data'])){
                foreach($data['data'] as $k=>$v){
                    $interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$this->session->userdata('uid'))));
                    if($interest  > $v['amount']){ //收益总额大于本金 说明本金已还
                        $data['data'][$k]['type']     = 1;
                        $data['data'][$k]['interest'] = $interest;
                    }else{
                        $data['data'][$k]['type']     = 0;
                        $data['data'][$k]['interest'] = $this->app->get_project_interest(array('mode'=>$v['mode'],'amount'=>$v['amount'],'months'=>$v['months'],'rate'=>$v['rate'],));
                        $data['data'][$k]['interest'] = round($data['data'][$k]['interest']+$v['amount'],2);
                    }
                }
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

        //合计的初值

        $data = array('income_total'=>0,'pay_total'=>0,'frozen_total'=>0);

        

        $temp['my_invest_freeze']   = $this->app->get_user_invest_freeze();          //投资冻结金额
        $temp['my_transfer_freeze'] = $this->app->get_user_transfer_freeze();         //提现冻结金额
        
        $data['frozen_total']       = round($temp['my_invest_freeze']+$temp['my_transfer_freeze'],2);//冻结合计
        $data['income_total']       = $this->app->get_income_total();
        $data ['pay_total']         = $this->app->get_pay_total(); 

        unset($temp);

        $this->load->view(self::dir.'my_cash_log',$data);
    }

   /**
     *我的雪球
     */
    public function my_integral(){
		$data_balance=array();
		 $data_balance['balance']= $this->user->get_num_amount($this->session->userdata('uid'),self::snowballdtl,'balance');
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

/************************************--个人中心相关--***********************************************/

/************************************--设置--***********************************************/
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

   /**
     * 关于我们
     */
    public function about_us(){
        // $this->load->view(self::dir.'about_us');
        $this->load->view(self::dir.'platform_introduction');
    }
	 /**
     * 平台介绍
     */
    public function platform_introduction(){
        $this->load->view(self::dir.'platform_introduction');
    }
	 /**
     * 安全保障
     */
    public function safety_assurance(){
        $this->load->view(self::dir.'safety_assurance');
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
/************************************--设置--***********************************************/

    /**
     * 验证app访问时 传递的uid
     */
    private function _check_uid(){
        $temp =array();

        //如果有get参数uid 且不为空  则查询 存储uid信息
        if(isset($_GET['uid']) && !empty($_GET['uid'])){
            //解密
            $temp['uid'] = authcode($this->input->get('uid',TRUE),'',TRUE);
            if( ! empty($temp['uid'])){
                $temp['user'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$temp['uid'])));
                if( ! empty($temp['user'])){
                    $this->session->set_userdata($temp['user']);
                }
            }
        }

        unset($temp);
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
        $data                 =$temp=array();
        $uid                  =$this->session->userdata('uid');
        $temp['id']           = $this->input->get('id');
        $temp['amount']       = $this->input->get('amount');
        $temp['where_update'] = array(
                'where'    => array('id' => $temp['id'] )
            );
		$this->c->update(self::redbag , $temp['where_update'] , array('status' => "1","receive_time" => time()));
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

/***************************聚宝宝自动投*****************************************/
	/**
     *聚保宝主页
     */
    public function jbb(){
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

    /**
     * 自动投设置
     */
    public function my_auto_invest(){
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
}