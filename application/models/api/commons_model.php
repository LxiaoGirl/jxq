<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * API公用model
 * Class Common_model
 */
class Commons_model extends CI_Model{
    //数据库表 表明常量
    const bank      = 'bank';   //银行表
    const region    = 'region'; //地区表

    /**
     * 构造函数 加载model
     */
    public function __construct(){
        parent::__construct();
        //加载 短信model
        $this->load->model('api/common/send_sms_model', 'sends');
        $this->load->model('api/common/email_model', 'email');
    }

	/**
	 * 发送短信
	 * @param string $mobile
	 * @param string $action
	 * @param int    $uid
	 *
	 * @return array
	 */
    public function send_sms($mobile='',$action='register',$uid=0){
        $data = $this->sends->send_sms($mobile,$action,$uid);
        return $data;
    }

	/**
	 * 发送语音
	 * @param string $mobile
	 * @param string $action
	 * @param int    $uid
	 *
	 * @return array
	 */
    public function send_voice($mobile='',$action='register',$uid=0){
        $data = $this->sends->send_voice($mobile,$action,$uid);
        return $data;
    }

	/**
	 * @param int    $uid
	 * @param string $email 邮件地址
	 * @param string $content 邮件内容  （默认为验证邮箱内容）
	 * @param string $validate_url 验证地址
	 * @param string $subject 标题
	 *
	 * @return mixed
	 */
    public function send_email($uid=0,$email='',$content='',$validate_url='',$subject=''){
        $data = $this->email->send_email($uid,$email,$content,$validate_url,$subject);
        return $data;
    }

	/**
	 * 验证短信码
	 * @param string $target 目标(电话)
	 * @param string $code 短信码
	 * @param int    $action 类型
	 * @param int    $uid  uid（无则传0）
	 *
	 * @return mixed
	 */
    public function validation_authcode($target='',$code='',$action='',$uid=0){
	    $data = $this->sends->validation_authcode($target, $code, $action, $uid);
        return $data;
    }

	/**
	 * 验证邮箱码
	 * @param string $target 目标
	 * @param string $code 短信码
	 * @param int    $minute 时间（默认60分钟）
	 *
	 * @return mixed
	 */
    public function validation_email($target='',$code='',$minute=0){
        $data = $this->email->validation_email($target, $code, $minute);
        return $data;
    }

    /**
     * 获取银行信息 无bank_id 则全部查询  有 则查询一条
     * @param string $bank_id
     * @return array
     */
    public function get_bank($bank_id=''){
        $data = array('status'=>'10001','msg'=>'没有相关数据！','sign'=>'','data'=>array());
        $temp = array();

        //为空 查询全部
        if($bank_id == ''){
            $data['data'] = $this->c->get_all(self::bank,array('where'=>array('status'=>1)));
        }else{
            //为逗号分隔的字符串  切割成数组 查询该数组bank_id 数据
            if(strpos($bank_id,',')){
                $temp['bank_id_array'] = explode(',',$bank_id);
                $data['data'] = $this->c->get_all(self::bank,array('where'=>array('status'=>1),'where_in'=>array('field'=>'bank_id','value'=>$temp['bank_id_array'])));
            }else{
                //查询单条数据
                $data['data'] = $this->c->get_row(self::bank,array('where'=>array('bank_id'=>$bank_id,'status'=>1)));
            }
        }

        if($data['data']){
            $data['status'] = '10000';
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
        $data = array('status'=>'10001','msg'=>'没有相关数据！','sign'=>'','data'=>array());
        $temp = array();

        $temp['where'] = array(
            'select' =>'region_id,region_name',
            'where' => array('parent_id' => $region_pid)
        );

        $data['data'] = $this->c->get_all(self::region, $temp['where']);

        if($data['data']){
	        $data['status'] = '10000';
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
	    $data = array('status'=>'10001','msg'=>'没有相关数据！','sign'=>'','data'=>array());
        $temp = array();

        $temp['where'] = array(
            'where' => array('region_id' => $region_id)
        );

        $data['data'] = $this->c->get_row(self::region, $temp['where']);

        if($data['data']){
	        $data['status'] = '10000';
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
        $data = array('status'=>'10001','msg'=>'银行账号格式不正确!','sign'=>'','data'=>array());

        if($account){
            $this->load->library('llpay',array('notify'=>site_url('mobiles/home/llpay_notify'),'return_url'=>site_url('mobiles/home/recharge_success')));
            $rs = $this->llpay->check_bin($account);
	        $rs= json_decode($rs,true);
            if($rs['ret_code'] == '0000'){
                $data = array('status'=>'10000','msg'=>$rs['ret_msg'],'data'=>$rs);
            }else{
                $data['msg'] = $rs['ret_msg'];
            }
        }

        return $data;
    }
}