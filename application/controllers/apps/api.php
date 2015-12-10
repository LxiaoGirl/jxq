<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/7/7
 * Time: 17:44
 */

/**
 * api 接口 控制器
 * Class Api
 */

class Api extends Api_Controller{
    const user = 'user';

    /**
     *构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->load->model('apps/api_model');
        $this->load->model('web_1/send_model', 'send');
    }

    /**
     *登陆 接口
     */
    public function login(){
        $data = $this->api_model->sign_in();
        $this->api_return($data);
    }

    public function logout(){
        $this->api_model->logout();
        $this->api_return(array('data'=>'','msg'=>'ok','status'=>0));
    }

    /**
     *忘记密码 接口
     */
    public function forget_password(){
        $data = $this->api_model->forget_password();
        $this->api_return($data);
    }

    /**
     *查询可用余额 接口
     */
    public function get_balance(){
        $data = array('data'=>0,'msg'=>'服务器繁忙请稍后再试！','status'=>1);
        $temp = array();

        $temp['uid'] = $this->input->post('uid',TRUE);

        if( ! empty($temp['uid'])){
            $temp['uid'] = (int)authcode($temp['uid'], '', TRUE);
            if($temp['uid'] > 0){
                $data['data'] = (float)$this->api_model->get_balance_amount($temp['uid']);
                $data['msg'] = 'ok';
                $data ['status'] = 0;
            }else{
                $data['msg'] = '用户id错误';
            }
        }else{
            $data['msg'] = '用户id不能为空';
        }

        $this->api_return($data);
    }

    /**
     *查询收入 接口
     */
    public function get_income(){
        $data = array('data'=>0,'msg'=>'服务器繁忙请稍后再试！','status'=>1);
        $temp = array();

        $temp['uid'] = $this->input->post('uid',TRUE);

        if( ! empty($temp['uid'])){
            $temp['uid'] = (int)authcode($temp['uid'], '', TRUE);
            if($temp['uid'] > 0){
                $data['data'] = (float)$this->api_model->get_income_amount($temp['uid']);
                $data['msg'] = 'ok';
                $data ['status'] = 0;
            }else{
                $data['msg'] = '用户id错误';
            }
        }else{
            $data['msg'] = '用户id不能为空';
        }

        $this->api_return($data);
    }

    /**
     * 手机短信
     *
     * @access public
     * @return object
     */
    public function sms(){
        $data = $temp = array();
        $data = array('status' => 1, 'msg' => '服务器繁忙请稍后再试！','data'=>array());
        $temp['mobile'] = $this->input->post('mobile', TRUE);

        if( $this->_is_mobile($temp['mobile'])){
            $temp['type'] = 2;
            $temp['uid'] = 0;

            $temp['where'] = array('select' => 'uid', 'where' => array('mobile' => $temp['mobile']));
            $temp['uid'] = $this->c->get_one(self::user, $temp['where']);

            $temp['content'] = $this->send->get_sms_text($temp['type']);

            $temp['data'] = $this->send->send_sms($temp['mobile'], $temp['content'], $temp['type'], $temp['uid']);
            $data['status'] = $temp['data']['code'];
            $data['msg'] = $temp['data']['msg'];
        }

        unset($temp);
        $this->api_return($data);
    }

    /**
     * 手机短信
     *
     * @access public
     * @return object
     */
    public function voice(){
        $data = $temp = array();
        $data = array('status' => 1, 'msg' => '服务器繁忙请稍后再试！','data'=>array());

        $temp['mobile'] = $this->input->post('mobile', TRUE) ;

        if( $this->_is_mobile($temp['mobile'])){
            $temp['type'] = 2;
            $temp['uid'] = 0;

            $temp['where'] = array('select' => 'uid', 'where' => array('mobile' => $temp['mobile']));
            $temp['uid'] = $this->c->get_one(self::user, $temp['where']);

            $temp['content'] = $this->send->get_sms_text($temp['type']);

            $temp['data'] = $this->send->voice($temp['mobile'], $temp['content'], $temp['type'], $temp['uid']);
            $data['status'] = $temp['data']['code'];
            $data['msg'] = $temp['data']['msg'];
        }

        unset($temp);
        $this->api_return($data);
    }

    public function set_iphone_token(){
        $data = array('status' => 1, 'msg' => '操作失败请重试！','data'=>array());
        /*
        $new_token = $this->input->post('new_token',true);
        $old_token = $this->input->post('old_token',true); //需去左右<> 和中间的空格
        if( ! empty($old_token)){
            $this->c->delete('iphone_token',array('where'=>array('token'=>$old_token)));
        }
        if( ! empty($new_token)){
            $eists = $this->c->count('iphone_token',array('where'=>array('token'=>$new_token)));
            if(empty($eists)){
                $query = $this->c->insert('iphone_token',array('token'=>$new_token));
                if( ! empty($query)){
                    $data['status'] = 0;
                    $data['msg'] = 'ok';
                }
            }
        }
        */
        $this->api_return($data);
    }

    /**
     * 获取 居间人号
     */
    public function get_inviter_no(){
        $data = array('data'=>0,'msg'=>'服务器繁忙请稍后再试！','status'=>1);
        $temp = array();

        $temp['uid'] = $this->input->post('uid',TRUE);

        if( ! empty($temp['uid'])){
            $temp['uid'] = (int)authcode($temp['uid'], '', TRUE);
            if($temp['uid'] > 0){
                $data['data']['inviter_no'] = $this->c->get_one('user',array('select'=>'inviter_no','where'=>array('uid'=>$temp['uid'])));
                if(!$data['data']['inviter_no'])$data['data']['inviter_no']='';
                $data['msg'] = 'ok';
                $data ['status'] = 0;
            }else{
                $data['msg'] = '用户id错误';
            }
        }else{
            $data['msg'] = '用户id不能为空';
        }

        $this->api_return($data);
    }

    /**
     * 验证用户手机号码
     *
     * @access private
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    private function _is_mobile($mobile = ''){
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }
}