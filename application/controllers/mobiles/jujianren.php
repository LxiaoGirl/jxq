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
class Jujianren extends MY_Controller{
    const dir       = 'mobiles/';          //当前控制器controller model view目录
    const user ='user';

    //数据表 常量

    /**
     *构造函数
     */
    public function __construct(){
        parent::__construct();
        if( ! $this->session->userdata('captcha'))$this->session->set_userdata(array('captcha'=>md5('wang')));//发送短信 处理

        //加载必要model
        $this->load->model('web_1/send_model', 'send');                       //发送短信
        $this->load->model(self::dir.'app_model', 'app');                       //发送短信
    }

    /**
     * 居间人 申请 主页
     */
    public function index(){
        $this->load->view(self::dir.'jujianren/home');
    }

    /**
     * 居间人 申请 电话验证
     */
    public function ajax_jujianren_check(){
        if($this->input->is_ajax_request() == TRUE){
            $data = $temp = array();

            $data = array('code' => 1, 'msg' => '您输入的号码已是居间人了无须再申请!','data'=>array());

            $temp['mobile'] = $this->input->post('mobile', TRUE);

            if($this->is_mobile( $temp['mobile'] )){
                $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
                $temp['user'] = $this->c->get_row(self::user, $temp['where']);
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
        $temp['mobile'] = $this->input->post('mobile',true);

        if($this->is_mobile($temp['mobile']) && ! empty($temp['authcode'])){
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 7, 5);
                if( ! empty($temp['is_check'])){
                    $data['msg'] = '验证码正确！';
                    $data['code']=0;
                }else{
                    $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                }
        }

        unset($temp);
        exit(json_encode($data)) ;
    }

    /**
     * 居间人 申请 处理
     */
    public function ajax_jujianren_apply(){
        if($this->input->is_ajax_request() == TRUE){
            $data = array('data'=>'','msg'=>'你提交的数据有误！','code'=>1);
            $temp = array();

            $temp['mobile'] = $this->input->post('mobile',true);
            $temp['password'] =$this->input->post('password',true);
            $temp['authcode'] =$this->input->post('authcode',true);

            if($this->is_mobile($temp['mobile'])){
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 7, 5);
                if(empty($temp['is_check'])){
                    $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                    exit(json_encode($data));
                }

                $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
                $temp['user'] = $this->c->get_row(self::user, $temp['where']);
                if($temp['user'] && $temp['user']['inviter_no']){  //用户信息 存在 并且是居间人
                    $data['msg'] = '您提交的号码已是居间人了无须再申请';
                }else{
                    if($temp['user']){
                        $temp['update_data'] = array(
                            'inviter_no'=>$this->c->transaction_no(self::user,'inviter_no'),
                            'lv'=>1
                        );
                        if($temp['user']['password']){ //有密码 验证密码
                            $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);
                            if($temp['user']['password'] != $temp['password']){
                                $data['msg'] = '你的输入的密码不正确！';
                                exit(json_encode($data));
                            }
                        }else{
                            //没有密码
                            $temp['hash']     = random(6, FALSE);
                            $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                            $temp['update_data']['password'] = $temp['password'];
                            $temp['update_data']['hash'] = $temp['hash'];
                        }
                        $query = $this->c->update(self::user,array('where'=>array('uid'=>$temp['user']['uid'])),$temp['update_data']);
                        if($query){
                            $data['msg'] = '申请成功！';
                            $data['code'] = 0;
                            if($temp['user']['clientkind'] != 1){
                                $data['data'] = site_url('mobiles/jujianren/real_name');
                            }else{
                                $data['data'] = site_url('mobiles/jujianren/apply_success');
                            }
                        }else{
                            $data['msg'] = '服务器繁忙请稍后重试！';
                        }
                    }else{
                        //没有信息 注册一个
                        $temp['hash']     = random(6, FALSE);
                        $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                        $temp['data'] = array(
                            'user_name'   => $temp['mobile'],
                            'mobile'      => $temp['mobile'],
                            'password'    => $temp['password'],
                            'security'    => '',
                            'hash'        => $temp['hash'],
                            'rate'        => $this->config->item('min_rate'), // 最小提成比例
                            'inviter'     => '', // 会员邀请人
                            'inviter_no'     => $this->c->transaction_no(self::user,'inviter_no'),
                            'lv'     => 1, // 会员邀请人
                            'reg_date'    => time(),
                            'reg_ip'      => $this->input->ip_address(),
                            'last_date'   => 0,
                            'last_ip'     => '',
                        );
                        $query = $this->c->update(self::user,array('where'=>array('uid'=>$temp['user']['uid'])),$temp['update_data']);
                        if($query){
                            $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                            $temp['data']  = $this->c->get_row(self::user, $temp['where']);
                            if( ! empty($temp['data'])){
                                $this->session->set_userdata($temp['data']);
                                $data['msg'] = '申请成功！';
                                $data['code'] = 0;
                                $data['data'] = site_url('mobiles/jujianren/real_name');
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
        $this->load->view(self::dir.'jujianren/agree');
    }

    /**
     * 居间人 申请 实名
     */
    public function real_name(){
        if($this->input->is_ajax_request() == TRUE){
            $this->load->model('web_1/user/authentication_model','authentication');
            $data = $this->authentication->real_name();
            $data['url'] ='';
            exit(json_encode($data));
        }
        $this->load->view(self::dir.'jujianren/real_name');
    }

    /**
     * 居间人 申请 成功页
     */
    public function apply_success(){
        $this->load->view(self::dir.'jujianren/step2');
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
    protected function _check_realname(){
        if($this->session->userdata('clientkind') != 1){
            redirect(self::dir.'home/real_name','location');
        }
    }
}