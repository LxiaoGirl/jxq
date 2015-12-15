<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 居间人
 * Class Jujianren
 */

class Jujianren extends My_Controller{
    const user     = 'user'; // 会员
    const admin    = 'admin'; // 管理员
    const message  = 'message'; // 系统消息
    const log      = 'user_log'; // 会员日志
    const authcode = 'authcode'; // 验证授权
    const flow     = 'cash_flow'; // 资金记录

    /**
     * 初始化
     * Jujianren constructor.
     */

    public function __construct(){
        parent::__construct();
        $this->load->model('api/user_model', 'user');
    }


    /**
     * 主页
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
     * 注册处理
     */
    public function sign_up(){
        $data = $this->user->register($this->input->post('mobile',true),$this->input->post('password',true),$this->input->post('authcode',true),$this->input->post('inviter_no',true));
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
     * 根据邀请码获取邀请人信息
     * @param string $code
     * @return array
     */
    protected function _get_inviter_info($code=''){
        $data = array();
        if($code != ''){
            $data = $this->c->get_row(self::user,array('where'=>array('inviter_no'=>$code)));
        }

        return $data;
    }
}