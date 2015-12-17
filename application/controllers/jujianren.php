<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 居间人
 * Class Jujianren
 */

class Jujianren extends My_Controller{
    const user     = 'user'; // 会员
    const company     = 'company'; // 公司

    /**
     * 初始化
     * Jujianren constructor.
     */

    public function __construct(){
        parent::__construct();
        $this->load->model('api/user_model', 'user');
    }

    /**
     * 注册 主页
     */
    public function index(){
         $data['inviter_no'] = '';
         $inviter_no = $this->input->get('inviter_no', TRUE);

         if( !$inviter_no){
             $data['inviter_no_msg'] = '没有邀请人哦!确认注册吗?';
         }else{
             $info = $this->_get_inviter_info($inviter_no);
             if($info){
                 $data['inviter_no'] = $inviter_no;
             }else{
                 $data['inviter_no_msg'] = '当前邀请码不正确!请重新扫描邀请人二维码或者联系客服人员!';
             }
         }

         $this->load->view('jujianren/home', $data);
    }

    /**
     * 介绍页面
     */
    public function jieshao(){
        $data = array(
            'inviter_no'=>$this->input->get('inviter_no',true),
            'nickname' => '',
            'headimgurl' => '',
            'is_myself'=>($this->session->userdata('is_myself')?1:0)
        );

        $openid = $this->input->get('openid');


        if($data['inviter_no']){
            $userinfo = $this->c->get_row('user',array('select'=>'real_name,user_name,avatar','where'=>array('inviter_no'=>$data['inviter_no'])));

            if($userinfo){
                if(!$openid){
                    $data['nickname']   = $userinfo['real_name']?$userinfo['real_name']:$userinfo['user_name'];
                    $data['headimgurl'] = $userinfo['avatar']?$this->c->get_oss_image($userinfo['avatar']):'';
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

        $this->load->view('mobiles/intermediary/share_page',$data);
    }

    /**
     * 注册处理
     */
    public function sign_up(){
        $data = $this->user->register($this->input->post('mobile',true),$this->input->post('password',true),$this->input->post('authcode',true),'',$this->input->post('inviter_no',true));
        if($data['status'] == '10000'){
            $this->session->set_userdata($data['data']);
        }
        exit(json_encode($data));
    }

    /**
     * 成功页面
     */
    public function success(){
        $this->load->view('jujianren/success');
    }

    /**
     * 公司二维码扫描后显示页面
     */
    public function company(){
        $data = array(
            'inviter_no'=>$this->input->get('inviter_no',true),
            'nickname' => ''
        );

        if($data['inviter_no']){
            $company_info = $this->c->get_row(self::company,array('where'=>array('company_inviter_no'=>$data['inviter_no'])));

            if($company_info){
                $data['nickname']   = $company_info['company_name'];
            }else{
                $data['inviter_no'] = '';
            }
        }

        $this->load->view('jujianren/company', $data);
    }

    /**
     * 根据邀请码获取邀请人信息
     * @param string $code
     * @return array
     */
    protected function _get_inviter_info($code=''){
        $data = array();
        if($code != ''){
            $data = $this->c->get_row(self::user,array('where'=>array('inviter_no'=>$code)));
            if( !$data){
                //查看是不是公司码
                $data = $this->c->get_row(self::company,array('where'=>array('company_inviter_no'=>$code)));
            }
        }

        return $data;
    }
}