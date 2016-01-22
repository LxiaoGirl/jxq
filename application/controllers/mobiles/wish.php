<?php

/**
 * Created by PhpStorm.
 * User: keren
 * Date: 16-1-11
 * Time: 下午9:54
 */
class wish extends MY_Controller{

    protected $_title = array(
      '能不能娶上媳妇，就靠各位兄弟姐妹进来给我猛戳两下了',
      '聚雪球“新年来许愿，全民来帮忙”简直爽呆了，朕要求你一起来体验',
      '谢谢你出现在我的青春里，一定要帮我实现许愿成功哦',
      '是朋友不？是朋友就帮我来实现新年的愿望吧，就差你一个了哦~',
      '赌你能帮我许愿，若许愿成功，我邀你共赏这世上繁华',
      '其实我最在意的，就是你到底有没有帮我助力我的新年许愿',
      '这世上最难偿还的就是情分，所以你帮我助力我的新年愿望，那我就会记得一辈子哒',
      'We are 伐木累，so,你必须进来帮我实现我的新年愿望啊',
      '你是猴子请来的救兵么？帮我实现了我的新年愿望',
      '你愿不愿意把我们纯洁的革命友谊，再升华一下，一定要进来帮我新年许愿啊',
      '让我们红尘作伴，许愿的潇潇洒洒，快来结伴许愿吧',
      '一声朋友，一声情。情谊在，梦想在。戳进来给我新年愿望助力啊',
      '求求各位大侠了，求猛戳，求助力！我的新年愿望靠你们了',
      '快来捧个场，我的小心脏啊，噗噗的为你而跳，因为我知道你在乎我的新年愿望',
      '如果再给我一次机会，我的一定许我的新年愿望里有你参与',
      '童鞋们，哥们尚未成功，大家还需继续努力啊',
      '见者有份，你帮我助力新年愿望，我请你吃新年大餐'
    );

    /**
     * 构造函数 加载必要model
     * wish constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('api/activity_wish_model','wish'); //活动类-新年愿望
        $this->load->library('wx');                           //微信类
    }

    /**
     * 新年愿望活动 主页
     */
    public function index(){
        if( !$this->session->userdata('openid')){
            //微信获取openid
            $code = $this->wx->get_code();
            $data['openid'] = $this->wx->get_openid_token($code)['openid'];
            $this->session->set_userdata('openid',$data['openid']);
        }

        //验证是否登录 如果已登录则查询是否已许愿 如果已许愿则跳转至愿望详情
        $data['uid'] = $this->session->userdata('uid')?$this->session->userdata('uid'):0;
        $data['clientkind'] = '';
        $data['is_invested'] = '';
        $data['inviter_no'] = $this->input->get('inviter_no',true);
        if($data['uid']){
            $data['clientkind'] = $this->session->userdata('clientkind');
            $data['is_invested'] = 1;//$this->c->count('borrow_payment',array('where'=>array('uid'=>$data['uid'],'type'=>1)));
            $wish = $this->wish->get_wish('',$data['uid'])['data'];
            if($wish)redirect('mobiles/wish/detail?wish_id='.$wish['wish_id'].'&uid='.$data['uid'],'location');
        }

        $this->load->view('mobiles/wish/home',$data);
    }

    /**
     * 新年愿望活动 详情
     */
    public function detail(){
        //验证是否为本人从banner点击进入 不是则获取微信信息 [用户已登录 且get参数中携带uid信息 为banner进入]
        if( !$this->session->userdata('uid') || $this->session->userdata('uid') != $this->input->get('uid')){
            if( !$this->session->userdata('openid')|| !$this->session->userdata('nickname')|| !$this->session->userdata('headimgurl')){
                $weixin_data = $this->wx->authorization('userinfo');
                $this->session->set_userdata($weixin_data);
            }
        }

        //验证必要参数 愿望id 和愿望id是否存在
        $wish_id = (int)$this->input->get('wish_id');
        if( !$wish_id)redirect('mobiles/wish/index','location');

        $data['wish'] = $this->wish->get_wish($wish_id)['data'];
        if( !$data['wish'])redirect('mobiles/wish/index','location');

        //查询排名
        $data['ranking'] = $this->wish->get_wish_ranking($wish_id)['data'];

        $data['title'] = $this->_title[array_rand($this->_title)];

        $data['is_self'] = 'no';
        if($data['wish']['openid'] == $this->session->userdata('openid'))$data['is_self'] = 'yes';

        $this->load->view('mobiles/wish/detail',$data);
    }

    /**
     * 登录
     */
    public function login(){
        if($this->input->is_ajax_request() == TRUE){
            $this->load->model('api/user_model','user_api');
            if($this->session->userdata('captcha') == $this->input->post('captcha',true)){
                $data = $this->user_api->login(
                    $this->input->post('mobile',true),
                    $this->input->post('password',true),
                    'wap'
                );
                if($data['status'] == '10000'){
                    $this->session->set_userdata($data['data']);
                    $data['data']['is_invested'] = 1;//$this->c->count('borrow_payment',array('where'=>array('uid'=>$data['data']['uid'],'type'=>1)));
                }
            }else{
                $data = array('status'=>'10001','msg'=>'验证码不正确!');
            }

            exit(json_encode($data));
        }
    }

    /**
     * 许愿的ajax方法
     */
    public function ajax_set_wish(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->set_wish(
                $this->session->userdata('uid'),
                $this->input->post('wish_type',true),
                $this->input->post('wish_name',true),
                $this->session->userdata('openid')
            );
            exit(json_encode($data));
        }
    }

    /**
     * 获取愿望帮助列表
     */
    public function ajax_get_help_log(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->get_wish_log($this->input->post('wish_id',true))['data'];
            exit(json_encode($data));
        }
    }

    /**
     * 帮助实现愿望的ajax方法
     */
    public function ajax_help(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->set_wish_log(
                $this->input->post('wish_id',true),
                $this->session->userdata('nickname'),
                $this->session->userdata('headimgurl'),
                $this->session->userdata('openid')
            );
            exit(json_encode($data));
        }
    }

    public function ajax_get_ticket(){
        exit(json_encode($this->wx->get_jsapi_ticket($this->input->post('url'))));
    }
}