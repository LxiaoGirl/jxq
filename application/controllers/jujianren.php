<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 扫描居间人二维码/公司二位码后的处理类
 * Class Jujianren
 */

class Jujianren extends My_Controller{
    const user    = 'user';     // 会员表
    const company = 'company'; // 公司表

    /**
     * 构造函数  初始化 加载必要model
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('api/user_model', 'user');
    }

    /**
     * 扫描居间人二维码后的注册页面
     */
    public function index(){
        //接收get的inviter_no信息
         $data['inviter_no'] = '';
         $inviter_no = $this->input->get('inviter_no', TRUE);

        //根据inviter_no是否存在而进行相关验证和提示语
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
     * 居间人二维码扫描后的介绍页面
     */
    public function jieshao(){
        //必要参数信息
        $data = array(
            'inviter_no' => $this->input->get('inviter_no',true),//邀请码
            'nickname'   => '',                                  //名称
            'headimgurl' => ''                                   //头像
        );

        // 如果邀请码存在
        if($data['inviter_no']){
            //查询居间人信息
            $user_info = $this->c->get_row('user',array('select'=>'real_name,user_name,avatar','where'=>array('inviter_no'=>$data['inviter_no'])));

            //如果有信息 获取头像和姓名
            if($user_info){
                $data['nickname']   = $user_info['user_name']?$user_info['user_name']:$user_info['real_name'];
                $data['headimgurl'] = $user_info['avatar']?$this->c->get_oss_image($user_info['avatar']):'';
            }else{
                $data['inviter_no'] = '';
            }
        }

        //调用的mobiles版的介绍页
        $this->load->view('mobiles/intermediary/share_page',$data);
    }

    /**
     * 注册的ajax处理
     */
    public function sign_up(){
        $data = $this->user->register($this->input->post('mobile',true),$this->input->post('password',true),$this->input->post('authcode',true),'',$this->input->post('inviter_no',true));
        if($data['status'] == '10000'){
            //注册成功后保存下注册人session信息
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
            'inviter_no' => $this->input->get('inviter_no',true),
            'nickname'   => ''
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
     * 根据【居间人/公司】邀请码获取【邀请人/公司】信息
     * @param string $code 居间人邀请码/公司邀请码
     * @return array
     */
    protected function _get_inviter_info($code=''){
        $data = array();

        if($code != ''){
            $data = $this->c->get_row(self::user,array('where'=>array('inviter_no'=>$code)));
            //不是居间人邀请码 查看是不是公司码
            if( !$data){
                $data = $this->c->get_row(self::company,array('where'=>array('company_inviter_no'=>$code)));
            }
        }

        return $data;
    }
}