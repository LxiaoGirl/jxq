<?php

/**
 * Created by PhpStorm.
 * User: keren
 * Date: 16-1-11
 * Time: 下午9:54
 */
class Yx extends MY_Controller{
    /**
     * 构造函数 加载必要model
     * wish constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('api/activity_yx_model','wish'); //活动类-新年愿望
        $this->load->library('wx');                           //微信类
        $this->session->set_userdata(array(
            'openid'=>'23123aaa',
            'nickname'=>'a',
            'headimgurl'=>'asd'
        ));
    }

    /**
     * 新年愿望活动 主页
     */
    public function index(){
        if( !$this->session->userdata('openid')|| !$this->session->userdata('nickname')|| !$this->session->userdata('headimgurl')){
            $data = $this->wx->authorization('userinfo');
            $this->session->set_userdata($data);
        }

        $wish = $this->wish->get_wish($this->session->userdata('openid'))['data'];
        if($wish)redirect('mobiles/yx/detail?wish_id='.$wish['wish_id']);

        $this->load->view('mobiles/yx/home',$data);
    }

    /**
     * 新年愿望活动 详情
     */
    public function detail(){
        if( !$this->session->userdata('openid') || !$this->session->userdata('nickname') || !$this->session->userdata('headimgurl')){
            $weixin_data = $this->wx->authorization('userinfo');
            $this->session->set_userdata($weixin_data);
        }

        //验证必要参数 愿望id 和愿望id是否存在
        $wish_id = (int)$this->input->get('wish_id');
        if( !$wish_id)redirect('mobiles/yx/index');

        $data['wish'] = $this->wish->get_wish($wish_id)['data'];
        if( !$data['wish'])redirect('mobiles/yx/index');

        //查询排名
        $data['ranking'] = $this->wish->get_wish_ranking($wish_id)['data'];
        //入座
        if($data['wish']['openid'] != $this->session->userdata('openid')){
            $a = $this->wish->set_wish_log($wish_id,$this->session->userdata('nickname'),$this->session->userdata('headimgurl'),$this->session->userdata('openid'));
        }

        $this->load->view('mobiles/yx/detail',$data);
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
            $data = $this->wish->set_wish($this->session->userdata('openid'));
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

    public function ajax_get_ticket(){
        exit(json_encode($this->wx->get_jsapi_ticket($this->input->post('url'))));
    }
}