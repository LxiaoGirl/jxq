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
class Intermediary extends MY_Controller{
    const dir     = 'apps/';          //当前控制器controller model view目录
    const user    ='user';
    //数据表常量
    const borrow  = 'borrow';           //借款
    const payment = 'borrow_payment';   //投资
    const cate    = 'product_category'; //分类
    const settle  = 'jujian_jiesuan';   //居间人 结算表

    //数据表 常量

    /**
     *构造函数
     */
    public function __construct(){
        parent::__construct();

        //验证居间人起始时间
        if($this->uri->uri_string() != 'apps/intermediary/no_start')$this->_check_intermediary_time();

        if( ! $this->session->userdata('captcha'))$this->session->set_userdata(array('captcha'=>md5('wang')));//发送短信 处理

        //分页参数的修正
        if(isset($_GET['pageId'])){
            $_GET['limit']    = (int)$this->input->get('pageSize');
            $_GET['per_page'] = ((int)$this->input->get('pageId')-1)*(int)$this->input->get('pageSize');
        }

        //加载必要model
        $this->load->model('web_1/send_model', 'send');                       //发送短信
        $this->load->model(self::dir.'app_model', 'app');                       //发送短信
    }

    /*
     * 居间人 主页
     */
    public function index(){
        if($this->input->is_ajax_request() == TRUE){
            $type = $this->input->get('type',true);
            if($type && in_array($type,array('user','income'))){
                switch($type){
                    case 'user';
                        $data['data'] = $this->_get_intermediary_user();
                        break;
                    case 'income';
                        //先查询 未结算部分（不好分页）（放在结算后面 如果结算没有1页以上的话 看不到）
                        // if($this->input->get('per_page') == 0){
                        //     $data = $this->_get_not_settle_list();
                        // }else{
                        //     $data = array('data'=>array());
                        // }
                        // if(!$this->session->userdata('per_page_ex'))$this->session->set_userdata(array('per_page'=>$this->input->get('per_page')));
                        // $len = count($data['data']);
                        // //验证未结算部分的 记录数 不足则以已结算记录补齐
                        // if(!$data['data'] || $len < $this->input->get('limit')){
                        //     $per_page_ex = $this->input->get('limit')-$len;
                        //     //处理per_page
                        //     $_GET['per_page'] = $this->input->get('per_page') - $this->session->userdata('per_page');
                        //     //如果已存过session 则不进行limit处理 否则 进行limit处理
                        //     if(!$this->session->userdata('per_page_ex')){
                        //         $this->session->set_userdata(array('per_page_ex'=>$per_page_ex));
                        //         $_GET['limit']   = $per_page_ex;
                        //         $temp['ex_data'] = $this->_get_settle_list();
                        //         $data['data']    = array_merge($data['data'],$temp['ex_data']['data']);
                        //     }else{
                        //         $data = $this->_get_settle_list();
                        //     }
                        // }
                        $this->load->model('mobiles/intermediary_model','intermediary');
                        $data = $this->intermediary->get_settle_list();
                        break;
                    default:

                }
            }

            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $this->_check_login();
        $this->_check_intermediary();
        $this->_check_real_name();

        if($this->session->userdata('per_page_ex'))$this->session->set_userdata(array('per_page_ex'=>FALSE));
        if($this->session->userdata('per_page'))$this->session->set_userdata(array('per_page'=>FALSE));
        $data['my_interest']    =  $this->_get_settle();  //已收利息

        $this->load->view(self::dir.'intermediary/home',$data);
    }

    /**
     * 结算月份的投资列表
     * @return [type] [description]
     */
    public function settle_invest_list(){
        $temp = $data = array();

        //以结算是 时间  未结算传的borrow_no
        if((isset($_GET['real_month']))){

            $data['real_month'] = isset($_GET['real_month'])?$this->input->get('real_month'):0;
            $data['amount']     = $this->input->get('amount')?$this->input->get('amount'):0;
            $this->load->model('mobiles/intermediary_model','intermediary');
            $_GET['limit'] = 100;
            $_GET['per_page'] = 0;
            $data['data'] = $this->intermediary->get_settle_invest_list($data['real_month']); 
            $data['data'] = $data['data']['data'];

        }else{
            redirect('mobiles/intermediary/index','location');
        }
        $this->load->view(self::dir.'intermediary/settle_invest_list',$data);
    }

    /**
     * 引导页面
     */
    public function guide(){
        if($this->session->userdata('inviter_no')){
            redirect(self::dir.'intermediary/index','location');
        }
        $data['inviter_no'] = $this->input->get('inviter_no')?$this->input->get('inviter_no'):'';
        $this->load->view(self::dir.'intermediary/guide',$data);
    }

    /**
     * 居间人 用户 投资列表
     */
    public function user_invest_list(){
        if($this->input->is_ajax_request() == TRUE){
            $data['uid'] = (int)$this->input->post('uid');
            $data = $this->_get_invest_list( $data['uid']);

            if( ! empty($data['data'])){
                exit(json_encode(array('data'=>$data['data'],'msg'=>'ok','code'=>0)));
            }else{
                exit(json_encode(array('data'=>'','msg'=>'no data','code'=>1)));
            }
        }

        $data['uid'] = (int)$this->input->get('uid');
//        $data['uid'] = authcode((int)$this->input->get('uid'),'',TRUE);

        if($data['uid'] > 0){
            $data['user']       = $this->c->get_row('user',array('select'=>'mobile,user_name,avatar','where'=>array('uid'=>$data['uid'])));
            $data['all_invest'] = $this->_get_user_invest_all($data['uid']);
            if($data['user']){
                $this->load->view(self::dir.'intermediary/user_invest_list',$data);
            }else{
                redirect('mobiles/intermediary/index','refresh');
            }
        }else{
            redirect('mobiles/intermediary/index','refresh');
        }
    }

    /**
     * 分享 微信
     * @return [type] [description]
     */
    public function share_weixin(){
        $this->_check_login();
        $this->_check_intermediary();
        $this->_check_real_name();

        $data = array(
            'nickname'   =>$this->session->userdata('real_name')?$this->session->userdata('real_name'):$this->session->userdata('user_name'),
            'headimgurl' =>$this->session->userdata('avatar')
        );


        if($this->_is_weixin()){
            //微信浏览器 网页授权获取openid 跳转到分享页面
            // $this->load->library('wx');
            // $openid=$this->wx->get_openid();
            // redirect('/mobiles/intermediary/share_page?inviter_no='.$this->session->userdata('inviter_no').'&openid='.$openid);

            $this->load->view(self::dir.'/intermediary/QR_code',$data);
            
        }else{
            //不是微信浏览器 跳转到二维码页面
            $this->load->view(self::dir.'/intermediary/QR_code',$data);
        }
    }

    /**
     * 分享页面
     * @return [type] [description]
     */
    public function share_page(){
        $data = array(
            'inviter_no'=>$this->input->get('inviter_no',true),
            'nickname' => '',
            'headimgurl' => ''
        );

        $openid = $this->input->get('openid');
        
        
        if($data['inviter_no']){
            $userinfo = $this->c->get_row('user',array('select'=>'real_name,user_name,avatar','where'=>array('inviter_no'=>$data['inviter_no'])));

            if($userinfo){
                if(!$openid){
                    $data['nickname']   = $userinfo['real_name']?$userinfo['real_name']:$userinfo['user_name'];
                    $data['headimgurl'] = $userinfo['avatar'];
                }else{
                    $this->load->library('wx');
                    $wx_userinfo        = $this->wx->get_wx_userinfo($openid);
                    $data['nickname']   = $wx_userinfo['nickname'];
                    $data['headimgurl'] = $wx_userinfo['headimgurl'];
                }
            }else{
                $data['inviter_no'] = '';
            }
        }

        $this->load->view(self::dir.'/intermediary/share_page',$data);
    }

    public function get_jsapi_ticket(){
        $this->load->library('wx');
        $url    = $this->input->post('url');
        $result = $this->wx->get_jsapi_ticket($url);
        exit(json_encode($result));
    }

    /**
     * 居间人 申请 主页
     */
    public function apply(){
        $data['inviter_no'] = $this->input->get('inviter_no')?$this->input->get('inviter_no'):'';
        $this->load->view(self::dir.'intermediary/apply',$data);
    }

    /**
     * 居间人 申请 电话验证
     */
    public function ajax_intermediary_check(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $temp = array();

            $data = array('code' => 1, 'msg' => '您输入的号码已是居间人了无须再申请!','data'=>array());

            $temp['mobile'] = $this->input->post('mobile', TRUE);

            if($this->is_mobile( $temp['mobile'] )){
                $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
                $temp['user']  = $this->c->get_row(self::user, $temp['where']);
                if(empty($temp['user']) || empty($temp['user']['password']) || empty($temp['user']['inviter_no'])){
                    $data = array('code' => 0, 'msg' => '您输入的号码可以申请!' ,'data'=>$temp['user']);
                }
            }else{
                $data = array('code' => 1, 'msg' => '您输入的号码格式有误!' ,'data'=>array());
            }

            unset($temp);
            exit(json_encode($data));
        }
    }

    /**
     * 居间人 申请 验证码验证
     */
    public function ajax_authcode_check(){
        $data = array('code'=>1,'msg'=>'你提交的数据有误,请重试！','url'=>'');
        $temp = array();

        $temp['authcode'] = $this->input->post('authcode',true);
        $temp['mobile']   = $this->input->post('mobile',true);

        if($this->is_mobile($temp['mobile']) && ! empty($temp['authcode'])){

                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 7, 5);
                if(!$temp['is_check']){
                    $temp['where'] = array(
                        'select' => 'uid',
                        'where'  => array(
                                        'code'         => $temp['authcode'],
                                        'send_time >=' => time()-30000,
                                        'target'       => $temp['mobile'],
                                        'type'         => 7
                                    )
                    );
                    $temp['is_check'] = $this->c->count('authcode', $temp['where']);
                }
                if( ! empty($temp['is_check'])){
                    $data['msg']  = '验证码正确！';
                    $data['code'] = 0;
                }else{
                    $data['msg']  = '你输入的手机验证码不正确或者已过期！';
                }
        }

        unset($temp);
        exit(json_encode($data)) ;
    }

    /**
     * 居间人 申请 处理
     */
    public function ajax_intermediary_apply(){
        if($this->input->is_ajax_request() == TRUE){
            $data = array('data'=>'','msg'=>'你提交的数据有误！','code'=>1);
            $temp = array();
            
            $temp['mobile']   = $this->input->post('mobile',true);
            $temp['password'] = $this->input->post('password',true);
            $temp['authcode'] = $this->input->post('authcode',true);
            $temp['source']   = $this->input->post('source',true);
            
            if($this->is_mobile($temp['mobile'])){
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 7, 5);
                if(!$temp['is_check']){
                    $temp['where'] = array(
                        'select' => 'uid',
                        'where'  => array(
                                        'code'         => $temp['authcode'],
                                        'send_time >=' => time()-30000,
                                        'target'       => $temp['mobile'],
                                        'type'         => 7
                                    )
                    );
                    $temp['is_check'] = $this->c->count('authcode', $temp['where']);
                }
                if(empty($temp['is_check'])){
                    $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                    exit(json_encode($data));
                }

                $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
                $temp['user']  = $this->c->get_row(self::user, $temp['where']);
                if($temp['user'] && $temp['user']['inviter_no']){  //用户信息 存在 并且是居间人
                    $data['msg'] = '您提交的号码已是居间人了无须再申请';
                }else{
                    if($temp['user']){
                        $temp['update_data'] = array(
                            'inviter_no' =>$this->c->transaction_no(self::user,'inviter_no'),
                            'lv'         =>1
                        );

                        if($temp['source'])$temp['update_data']['address'] = $temp['source'];

                        if($temp['user']['password']){ //有密码 验证密码
                            $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);
                            if(!profile('uid') && $temp['user']['password'] != $temp['password']){
                                $data['msg'] = '你的输入的密码不正确！';
                                exit(json_encode($data));
                            }
                        }else{
                            //没有密码
                            $temp['hash']     = random(6, FALSE);
                            $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                            $temp['update_data']['password'] = $temp['password'];
                            $temp['update_data']['hash']     = $temp['hash'];
                        }
                        $query = $this->c->update(self::user,array('where'=>array('uid'=>$temp['user']['uid'])),$temp['update_data']);
                        if($query){
                            $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                            $temp['user']  = $this->c->get_row(self::user, $temp['where']);
                            if( ! empty($temp['user'])){
                                $this->session->set_userdata($temp['user']);
                            }
                            $data['msg'] = '申请成功！';
                            $data['code'] = 0;
                            if($temp['user']['clientkind'] != 1){
                                $data['data'] = site_url('apps/intermediary/real_name');
                            }else{
                                $data['data'] = site_url('apps/intermediary/apply_success');
                            }
                        }else{
                            $data['msg'] = '服务器繁忙请稍后重试！';
                        }
                    }else{
                        //没有信息 注册一个
                        $temp['hash']     = random(6, FALSE);
                        $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                        $temp['data'] = array(
                            'user_name'  => $temp['mobile'],
                            'mobile'     => $temp['mobile'],
                            'password'   => $temp['password'],
                            'security'   => '',
                            'hash'       => $temp['hash'],
                            'rate'       => $this->config->item('min_rate'), // 最小提成比例
                            'inviter'    => '', // 会员邀请人
                            'inviter_no' => $this->c->transaction_no(self::user,'inviter_no'),
                            'lv'         => 1, // 会员邀请人
                            'reg_date'   => time(),
                            'reg_ip'     => $this->input->ip_address(),
                            'last_date'  => 0,
                            'last_ip'    => '',
                        );

                        if($temp['source'])$temp['data']['address'] = $temp['source'];

                        $query = $this->c->insert(self::user,$temp['data']);
                        if($query){
                            $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                            $temp['data']  = $this->c->get_row(self::user, $temp['where']);
                            if( ! empty($temp['data'])){
                                $this->session->set_userdata($temp['data']);
                                $data['msg']  = '申请成功！';
                                $data['code'] = 0;
                                $data['data'] = site_url('apps/intermediary/real_name');
                            }else{
                                $data['msg'] = '服务器繁忙请稍后重试！';
                            }
                        }else{
                            $data['msg'] = '服务器繁忙请稍后重试！';
                        }
                    }
                }
            }

            unset($temp);
            exit(json_encode($data));
        }
    }

    /**
     * 居间人 申请 协议
     */
    public function agree(){
        $this->load->view(self::dir.'intermediary/agree');
    }

    /**
     * 居间人 申请 实名
     */
    public function real_name(){
        if($this->input->is_ajax_request() == TRUE){
            $this->load->model('web_1/user_model','user');                         //用户
            $this->load->model('web_1/user/authentication_model','authentication');
            $data        = $this->authentication->real_name();
            $data['url'] ='';
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'intermediary/real_name');
    }

    /**
     * 居间人 申请 成功页
     */
    public function apply_success(){
        $this->load->view(self::dir.'intermediary/apply_success');
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

    /**
     * 实名验证
     * @return bool
     */
    protected function _check_real_name(){
        if($this->session->userdata('clientkind') != 1){
            redirect(self::dir.'intermediary/real_name','location');
        }
    }

    /**
     * 登录验证
     */
    protected function _check_login(){
        if( ! $this->session->userdata('uid')){
            // redirect(self::dir.'home/login','location');
            redirect(self::dir.'intermediary/guide','location');
        }
    }

    /**
     * 验证是否 居间人
     */
    protected function _check_intermediary(){
        if( ! $this->session->userdata('inviter_no')){
            redirect(self::dir.'intermediary/guide','location');
        }
    }

    /**
     * 居间人 结算总额
     * @param int $uid
     * @return int
     */
    protected  function _get_settle($uid=0){
        $data = 0;
        $temp = array();

        $temp['uid'] = $uid?(int)$uid:$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $data = $this->c->get_one(self::settle,array('select'=>'SUM(jujian_amount)','where'=>array('inviter'=>$temp['uid'],'status'=>1)));
        }

        unset($temp);
        return $data;
    }

    /**
     * 居间人 结算 记录
     * @param int $uid
     * @return array
     */
    protected  function _get_settle_list($uid=0){
        $data = array();
        $temp = array();

        $temp['uid'] = $uid?(int)$uid:$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $data = $this->c->show_page(self::settle,array(
                'select'=>'start_time,SUM(amount) as amount,pay_time,status',
                'where'=>array(
                    'inviter' =>$temp['uid'],
                    'status'  =>1
                    ),
                'group_by'=>'start_time',
                'order_by'=>'start_time DESC'
                )
            );
        }

        unset($temp);
        return $data;
    }

    /**
     * 未结算的列表
     * @return array
     */
    protected  function _get_not_settle_list(){
        $this->config->load('lv');//加载居间人等级配置
        $rate = $this->config->item('lv'.$this->session->userdata('lv'))?$this->config->item('lv'.$this->session->userdata('lv')):0;

        $data = array('data'=>array());
        $temp = array();
        //查询推荐的用户id
        $temp['user'] = $this->c->get_all(self::user,array('where'=>array('inviter'=>$this->session->userdata('uid')),'select'=>'uid'));
        if( ! empty($temp['user'])){
            foreach($temp['user'] as $v){
                $temp['uid_array'][] = $v['uid'];
            }

            if($temp['uid_array']){
                //查询所以推荐用户的投资信息  以项目分组统计总额
                $temp['where'] = array(
                    'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','.join_field('confirm_time,status,months,mode', self::borrow).',SUM('.join_field('amount',self::payment).') as amounts',
                    'where'    => array(join_field('type', self::payment) => 1),
                    'where_in'=>array(
                        array('field'=>join_field('uid',self::payment),'value'=>$temp['uid_array']),
                        array('field'=>join_field('status',self::borrow),'value'=>array(4,7))
                    ),
                    'join'     =>array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    'group_by'=>join_field('borrow_no',self::payment)
                );

                $temp['project'] = $this->c->get_all(self::payment,$temp['where']);

                //计算 已结算部分
                if($temp['project']){
                    foreach($temp['project'] as $k1=>$v1){
                        $temp['settle'] = (float)$this->c->get_one(self::settle,array('where'=>array('borrow_no'=>$v1['borrow_no'],'status'=>1,'inviter'=>$this->session->userdata('uid')),'select'=>'SUM(amount)'));
                        //收益计算2
                        // $temp['interest'] = $this->app->get_project_interest($v1);
                        // $temp['settle_all'] =  round($temp['interest'] * $rate/100,2);
                        //收益计算1
                        $temp['settle_all'] = $v1['amounts']*($rate/3000)*$v1['months']*30;

                        if($temp['settle_all'] > $temp['settle']){ //未结算完  或 未结算
                            $temp['ajax_data']['start_time'] = 0;
                            $temp['ajax_data']['pay_time']   = 0;
                            $temp['ajax_data']['status']     = 0;
                            $temp['ajax_data']['borrow_no']  = $v1['borrow_no'];
                            $temp['ajax_data']['amount']     = round($temp['settle_all'] - $temp['settle'],2);
                            $data['data'][] = $temp['ajax_data'];
                            // $temp['project'][$k1]['amounts'] = $temp['project'][$k1]['amounts'] - $temp['settle'];
                            // $temp['project'][$k1]['settle_time'] = date('Y-m-d',strtotime(date('Y-m-d',$v1['confirm_time']).' +'.($v1['months']*30).'days'));
                        }
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 用户投资总额
     * @param int $uid
     * @return float|int
     */
    protected function _get_user_invest_all($uid=0){
        $data = 0;
        $temp = array();

        $temp['uid'] = (int)$uid;

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow))
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 查询投资列表信息
     * @param int $uid
     * @return array
     */
    protected function _get_invest_list($uid=0){
        $data = $temp = array();

        $temp['uid'] = (int)$uid;

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,amount as invest_amount,dateline', self::payment).','.join_field('subject as subject_1,status,months,mode,receive', self::borrow).','.join_field('amount', self::borrow).' as all_amounts,'.join_field('category',self::cate),
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'order_by' => array(
                    // array('field'=>join_field('status', self::borrow),'value'=>'asc'),
                    // array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
                )
            );

            $temp['status'] = (int)$this->input->get('status');
            if( ! empty($temp['status'])){
                $temp['condition']      = array(join_field('status',self::borrow)=>$temp['status']);
                $temp['where']['where'] = array_merge($temp['where']['where'], $temp['condition']);
            }

            $data = $this->c->show_page(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 居间人 用户
     * @return array
     */
    protected  function _get_intermediary_user(){
        $data = array();
        $uid = $this->session->userdata('uid');
        if($uid){
            $data = $this->c->get_all(self::user,array('select'=>'user_name,uid,last_date','where'=>array('inviter'=>$uid)));
            if($data){
                $sort_arr = array();
                foreach($data as $k=>$v){
                    $v['amount']       = $this->_get_user_invest_all($v['uid']);
                    // $v['active_level'] = $this->_get_active_level($v['last_date']);
                    $v['active_level'] = $v['last_date']?date('Y-m-d',$v['last_date']).'<br/>'.date('H:i:s',$v['last_date']):'--';
                    $data[$k]          = $v;
                    $sort_arr[]        = $v['amount'];
                }
                array_multisort($sort_arr, SORT_DESC, $data);
            }
        }

        return $data;
    }

    /**
     * 居间人 推荐的用户的活跃等级
     * @param int $lastDate
     * @return string
     */
    protected function _get_active_level($lastDate=0){
        $str      = '';
        $dateNow  = date('Ymd',time());
        $lastDate = date('Ymd',$lastDate);

        $exp = $dateNow - $lastDate;

        switch($exp){
            case $exp>=0&&$exp<=1:
                $str = '很活跃';
                break;
            case $exp>1&&$exp<=7:
                $str = '活跃';
                break;
            case $exp>7&&$exp<=15:
                $str = '不活跃';
                break;
            default:
                $str = '已远离';
        }

        return $str;
    }

    /**
     * 是否微信浏览器检查函数
     * @return boolean [description]
     */
    protected function _is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false ) 
        return true;
        return false;
    }


    /**
     * 验证居间人起始时间
     * @return [type] [description]
     */
    protected function _check_intermediary_time(){
        $this->config->load('intermediary');//加载居间人时间配置
        $start_time = $this->config->item('intermediary_start_time');
        $end_time   = $this->config->item('intermediary_end_time');

        if(!$start_time)$start_time = '2015-10-10 09:00:00';
        if(!$end_time)  $end_time   = '2035-10-10 09:00:00';

        $start_time = strtotime($start_time);
        $end_time   = strtotime($end_time);

        if(time() < $start_time  || time() > $end_time){
            redirect(self::dir.'intermediary/no_start','location');
        }
    }

    public function no_start(){
        $this->config->load('intermediary');//加载居间人时间配置

        $data['start_time'] = $this->config->item('intermediary_start_time');
        if(!$data['start_time'])$data['start_time'] = '2015-10-10 09:00:00';

        $this->load->view(self::dir.'/intermediary/no_start',$data);
    }
}