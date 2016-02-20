<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 2016-新年元宵活动控制器类
 * Class Yx
 */
class Yx extends MY_Controller{
    const desk_seat = 8;
    /**
     * 构造函数 加载必要model
     * wish constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('api/activity_yx_model','wish'); //活动类-新年元宵活动model类
        $this->load->library('wx');                         //微信类
    }

    /**
     * 新年元宵活动 主页
     */
    public function index(){
        //微信授权获取微信用户信息 如果session中已有则不在获取
        if( !$this->session->userdata('openid')|| !$this->session->userdata('nickname')|| !$this->session->userdata('headimgurl')){
            $data = $this->wx->authorization('userinfo');
            $this->session->set_userdata($data);
        }

        //查询是否已经取得活动资格[领桌子] 如果有 跳转到活动详情页面
        $wish = $this->wish->get_wish('',$this->session->userdata('openid'))['data'];
        if($wish)redirect('mobiles/yx/detail?wish_id='.$wish['wish_id']);

        $data['start'] = $this->wish->get_start_time() <= time() ?'Y':'N';
        //显示页面
        $this->load->view('mobiles/yx/home',$data);
    }

    /**
     * 新年元宵活动 详情
     */
    public function detail(){
        //微信授权获取微信用户信息 如果session中已有则不在获取
        if( !$this->session->userdata('openid') || !$this->session->userdata('nickname') || !$this->session->userdata('headimgurl')){
            $weixin_data = $this->wx->authorization('userinfo');
            $this->session->set_userdata($weixin_data);
        }

        //验证必要参数 愿望id 和愿望id是否存在
        $wish_id = (int)$this->input->get('wish_id');
        if( !$wish_id)redirect('mobiles/yx/index');

        $data['wish'] = $this->wish->get_wish($wish_id)['data'];
        if( !$data['wish'])redirect('mobiles/yx/index');

        $data['join'] = 'N';//是否入座的标识
        //入座-如果当前微信不是该id活动者微信 执行参与处理
        if($data['wish']['openid'] != $this->session->userdata('openid')){
            $seat_array = $this->wish->set_wish_log(
                $wish_id,
                $this->session->userdata('nickname'),
                $this->session->userdata('headimgurl'),
                $this->session->userdata('openid')
            );
            if($seat_array['status'] == '10000' || $seat_array['status'] == '10002'){
                //计算当前openid座位情况
                $data['desk_id'] = ceil(($seat_array['data']['remarks']+1)/self::desk_seat);//当前桌
                $data['wish']['ranking_value'] += 1;//入座成功 人数+1
                $data['join'] = 'Y';//刚加入和已加入
            }else{
                $data['desk_id'] = ceil($data['wish']['ranking_value']/self::desk_seat);//最后桌
            }
        }else{
            $data['desk_id'] = ceil($data['wish']['ranking_value']/self::desk_seat);//最后桌
        }

        if($data['desk_id'] == 0)$data['desk_id'] = 1;
        //查询排名
        $data['ranking'] = $this->wish->get_wish_ranking($wish_id)['data'];
        $data['desk_count'] = ceil($data['wish']['ranking_value']/self::desk_seat);
        //显示页面
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
     * 开启活动[领桌子]ajax方法
     */
    public function ajax_set_wish(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->set_wish(
                $this->session->userdata('openid'),
                $this->session->userdata('nickname'),
                $this->session->userdata('headimgurl')
            );
            exit(json_encode($data));
        }
    }

    /**
     * 获取愿望帮助列表
     */
    public function ajax_get_help_log(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->get_wish_log($this->input->post('wish_id',true),1,$this->input->post('page_id',true),self::desk_seat)['data'];
            exit(json_encode($data));
        }
    }

    /**
     * 微信js获取授权到ajax方法
     */
    public function ajax_get_ticket(){
        exit(json_encode($this->wx->get_jsapi_ticket($this->input->post('url'))));
    }

    /**
     * 获取排行榜列表
     */
    public function ajax_get_ranking_list(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $this->wish->get_wish_ranking_list()['data'];
            exit(json_encode($data));
        }
    }
}