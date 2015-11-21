<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_models extends CI_Model{
    //数据库表 表明常量
    const BANK      = 'bank';   //银行表
    const REGION    = 'region'; //地区表

    //提示信息
    protected $_msg = array(
        'mobile_is_empty'      =>'请输入电话号码！',
        'mobile_format_error'  =>'请输入正确格式的手机号码!',
        'captcha_is_empty'     =>'请输入文字验证码!',
        'captcha_format_error' =>'文字验证码错误!',
        'email_is_empty'       =>'请输入电子邮件地址!',
        'email_format_error'   =>'请输入正确格式的电子邮件地址!',
        ''=>'',
        ''=>'',
        ''=>''
    );

    /**
     * 构造函数 加载碧桃model
     */
    public function __construct(){
        parent::__construct();
        //加载 短信model
        $this->load->model('api/common/sms_model', 'sms');
        //加载正则验证library
        $this->load->library('api/regex','regex');
    }

    /**
     * 发送短信
     * @param array $params 参数数组：mobile captcha act（默认为注册） uid（可选）
     * @return array
     */
    public function send_sms($params=array()){
        $data = $this->_send('sms',$params);
        return $data;
    }

    /**
     * 发送语音
     * @param array $params 参数：mobile captcha act（默认为注册） uid（可选）
     * @return array
     */
    public function send_voice($params=array()){
        $data = $this->_send('voice',$params);
        return $data;
    }

    /**
     * 发送邮件
     * @param array $params 需要邮件地址email  内容content（默认是验证模板内容） 验证链接（默认为空）url
     * @return array
     */
    public function send_email($params=array()){
        $data = $this->email->send_email($params);
        return $data;
    }

    /**
     * 验证短信码 需要目标(电话) 短信码 类型 时间（默认60分钟） uid（无则传0）
     * @return array
     */
    public function validation_authcode(){
        $data = array('code'=>1,'msg'=>'服务器繁忙，请稍后重试!');
        $temp = array(
            'target'=>$this->input->post('target',true),
            'code'=>$this->input->post('code',true),
            'type'=>$this->input->post('type',true),
            'minite'=>$this->input->post('minite',true),
            'uid'=>$this->input->post('uid',true)
        );

        $temp['result'] = $this->sms->validation($temp['target'], $temp['code'], $temp['type'], $temp['minute'] , $temp['uid']);

        if($temp['result']){
            $data  = array('code'=>0,'msg'=>'验证码通过验证!');
        }

        unset($temp);
        return $data;
    }

    /**
     * 验证邮箱码 需要目标 短信码 时间（默认60分钟）
     * @return array
     */
    public function validation_email(){
        $data = array('code'=>1,'msg'=>'服务器繁忙，请稍后重试!');
        $temp = array(
            'target'=>$this->input->post('target',true),
            'code'=>$this->input->post('code',true),
            'minite'=>$this->input->post('minite',true)
        );

        $temp['result'] = $this->email->validation_email($temp['target'], $temp['code'], $temp['minute']);

        if($temp['result']){
            $data  = array('code'=>0,'msg'=>'邮箱通过验证!');
        }
        unset($temp);
        return $data;
    }

    /**
     * 获取银行信息 无bank_id 则全部查询  有 则查询一条
     * @param string $bank_id
     * @return array
     */
    public function get_bank($bank_id=''){
        $data = array('code'=>1,'msg'=>'?]有相关数据！','data'=>array());
        $temp = array();

        //为空 查询全部
        if($bank_id == ''){
            $data['data'] = $this->c->get_all(self::BANK,array('where'=>array('status'=>1)));
        }else{
            //为逗号分隔的字符串  切割成数组 查询该数组bank_id 数据
            if(strpos($bank_id,',')){
                $temp['bank_id_array'] = explode(',',$bank_id);
                $data['data'] = $this->c->get_row(self::BANK,array('where'=>array('status'=>1),'where_in'=>array('field'=>'bank_id','value'=>$temp['bank_id_array'])));
            }else{
                //查询单条数据
                $data['data'] = $this->c->get_row(self::BANK,array('where'=>array('bank_id'=>$bank_id,'status'=>1)));
            }
        }

        if($data['data']){
            $data['code'] = 0;
            $data['msg'] = 'ok';
        }

        unset($temp);
        return $data;

    }

    /**
     * 根据地区 parent_id 获取该parent_id 下地区列表
     * @param int $region_pid 地区父id
     * @return array
     */
    public function get_region($region_pid=0){
        $data = array('code'=>1,'msg'=>'?]有相关数据！','data'=>array());
        $temp = array();

        $temp['where'] = array(
            'select' =>'region_id,region_name',
            'where' => array('parent_id' => $region_pid)
        );

        $data['data'] = $this->c->get_all(self::REGION, $temp['where']);

        if($data['data']){
            $data['code'] = 0;
            $data['msg'] = 'ok';
        }

        unset($temp);
        return $data;
    }

    /**
     * 根据 地区id 查询地区名称信息
     * @param int $region_id
     * @return array
     */
    public function get_region_info($region_id=1){
        $data = array('code'=>1,'msg'=>'?]有相关数据！','data'=>array());
        $temp = array();

        $temp['where'] = array(
            'where' => array('region_id' => $region_id)
        );

        $data['data'] = $this->c->get_row(self::REGION, $temp['where']);

        if($data['data']){
            $data['code'] = 0;
            $data['msg'] = 'ok';
        }

        unset($temp);
        return $data;
    }

    /**
     * 查询银行卡 bin信息   需要连连支付library
     * @param string $account 银行卡账号
     * @return mixed 数组
     */
    public function get_bankcard_bin($account=''){
        $data = array('code'=>1,'msg'=>'请输入正确格式银行账号!','data'=>array());

        if($account){
            $this->load->library('llpay',array('notify'=>site_url('mobiles/home/llpay_notify'),'return_url'=>site_url('mobiles/home/recharge_success')));
            $rs = $this->llpay->check_bin($account);
            if($rs['ret_code'] == '0000'){
                $data = array('code'=>0,'msg'=>$rs['ret_msg'],'data'=>$rs);
            }else{
                $data['msg'] = $rs['ret_msg'];
            }
        }

        return $data;
    }

    /**************************以下 ↓ 短信相关的私有方法**********************************************/

    /**
     * 发送的处理
     * @param string $type 短息(sms)或语音（voice）
     * @param array $params 参数数组
     * @return array
     */
    private function _send($type='sms',$params=array()){
        $temp = array();
        $data = array('code' => 1, 'msg' => '服务器繁忙请稍后再试！');
        $temp = $params;

        //验证必要参数
        if( ! isset($temp['mobile']))return array('code' => 1, 'msg' => $this->_msg['mobile_is_empty']);
        if( ! isset($temp['code']))return array('code' => 1, 'msg' => $this->_msg['captcha_is_empty']);
        if( ! isset($temp['action']))$temp['action'] = 'register';
        if( ! isset($temp['uid']))$temp['uid'] = 0;

        //验证 文字验证码
        if( ! $this->_check_captcha($temp['code'])) {
            $data['msg'] = $this->_msg['captcha_format_error'];
            unset($temp);
            return $data;
        }
        //验证手机号
        if(  ! $this->regex->is_mobile($temp['mobile'])){
            $data['msg'] = $this->_msg['mobile_format_error'];
            unset($temp);
            return $data;
        }

        $temp['type'] = $this->_get_type($temp['action']);

        //根据短信类型获取短信内容
        $temp['content'] = $this->sms->get_sms_text($temp['type']);
        //没传uid的时候 根据mobile查询
        if( ! $temp['uid'])$temp['uid']  = $this->_get_uid($temp['mobile']);

        //执行发送短信或语音程序
        switch($type){
            case 'sms':
                $data = $this->sms->send_sms($temp['mobile'], $temp['content'], $temp['type'], $temp['uid']);
                break;
            case 'voice':
                $data = $this->sms->voice($temp['mobile'], $temp['content'], $temp['type'], $temp['uid']);
                break;
            default:
                $data = $this->sms->send_sms($temp['mobile'], $temp['content'], $temp['type'], $temp['uid']);
        }

        return $data;
    }

    /**
     * 文字图形验证码验证.正确返回true，错误返回false
     * @param string $captcha 文字图形验证码
     * @return bool
     */
    private  function _check_captcha($captcha=''){
        $result = FALSE;
        $temp = array();

        if($captcha != ''){
            $temp['captcha'] = $this->session->userdata('captcha');
            if($temp['captcha'] && $temp['captcha'] == $captcha){
                $result = TRUE;
            }
        }

        return $result;
    }

    /**
     * @param string $act 短信类型-字符
     * @return int 短信类型-数字
     */
    private function _get_type($act=''){
        $type = 1;

        switch ($act){
            case 'forget': // 忘记密码
                $type = 2;
                break;
            case 'security': // 交易密码
                $type = 3;
                break;
            case 'transfer': // 用户提现
                $type = 4;
                break;
            case 'password': // 修改密码
                $type = 5;
                break;
            case 'huodong': // 修改密码
                $type = 6;
                break;
            case 'jujianren': // 修改密码
                $type =7;
                break;
            case 'bindphone': // 修改绑定手机
                $type = 8;
                break;
            case 'unbindphone': // 解绑手机
                $type = 9;
                break;
            case 'apply':       // 借款
                $type = 10;
                break;
            case 'bindcard':   // 绑定卡
                $type = 11;
                break;
            case 'unbindcard': // 解绑卡
                $type = 12;
                break;
            default:            // 用户注册
                $type = 1;
        }

        return $type;
    }

    /**
     * 获取 uid （忘记密码的时候）
     * @param string $mobile
     * @return int
     */
    private function _get_uid($mobile=''){
        $uid = 0;
        if($this->regex->is_mobile($mobile)){
            $uid = $this->c->get_one(self::user, array('select' => 'uid', 'where' => array('mobile' => $mobile)));
            if( ! $uid) $uid = 0;
        }

        return $uid;
    }
}