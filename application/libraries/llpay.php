<?php

/**
 * Class Llpay
 */
class CI_Llpay{

    protected $_oid_partner = '201507311000437502';//商户号
    protected $_sign_type   = 'MD5'; //签名类型
    protected $_md5_key     = 'd4590b29b63e7a50cc571706e28e7c';
    public     $_notify      = 'http://120.24.208.200/index.php/20150707/home/llpay_notify';//异步通知地址
    public     $_return_url = 'http://120.24.208.200/index.php/20150707/home/recharge_success';//回显地址
    protected $_ci           = '';
    protected $_versiob     = 1.1;

    protected $_llpay_api = array(
        'pay'=>'https://yintong.com.cn/llpayh5/authpay.htm', //支付提交数据
        'bin'=>'https://yintong.com.cn/traderapi/bankcardquery.htm',//银行卡bin查询
        'card_list'=>'https://yintong.com.cn/traderapi/userbankcard.htm',//用户签约信息
        'card_unbind'=>'https://yintong.com.cn/traderapi/bankcardunbind.htm',//银行卡解约
        'result'=>'https://yintong.com.cn/traderapi/orderquery.htm'//支付结果查询
    );

    /**
     *构造函数
     */
    public function __construct($config=array()){
        if(isset($config['notify']) && $config['notify'] != '')         $this->_notify = $config['notify'];
        if(isset($config['return_url']) && $config['return_url'] != '')$this->_return_url = $config['return_url'];
        $this->_ci = &get_instance();
    }

    /**
     * wap 充值
     * @param $arr
     */
    public function submit($arr){
        $uid        = $this->_ci->session->userdata('uid');
        $nric       = $this->_ci->session->userdata('nric');
        $real_name  = $this->_ci->session->userdata('real_name');

        if(empty($uid))                                                      return $this->_my_return('用户uid不能为空！');
        if(empty($nric) || empty($real_name))                               return $this->_my_return('实名信息不能为空！');
        if(!isset($arr['no_order']) || empty($arr['no_order']))          return $this->_my_return('订单编号不能为空！');
        if(!isset($arr['dt_order']) || empty($arr['dt_order']))          return $this->_my_return('订单时间不能为空！');
        if(!isset($arr['name_goods']) || empty($arr['name_goods']))      return $this->_my_return('商品名称不能为空！');
        if(!isset($arr['money_order']) || empty($arr['money_order']))    return $this->_my_return('金额不能为空！');
        $risk = array(
            'frms_ware_category'        => 2009,
            'user_info_mercht_userno'  => $uid,
            'user_info_dt_register'     => date("YmdHis",$this->_ci->session->userdata('reg_date')),
            'user_info_full_name'       => urlencode($real_name),
            'user_info_id_no'            => $nric,
            'user_info_identify_state' => 1,
            'user_info_identify_type'  => 1
        );
        $risk = urldecode(json_encode($risk));
        $data = array(
            'version'        => $this->_versiob,//
            'oid_partner'   => $this->_oid_partner,//
            'user_id'        => $uid,//
            'app_request'   => isset($arr['app_request'])?$arr['app_request']:3,//请求应用标示 1 Android 2ios 3 wap
            'busi_partner'  => isset($arr['busi_partner'])?$arr['busi_partner']:101001,//商户业务类型  101001 虚拟商品销售 109001 实物商品销售
            'no_order'       => $arr['no_order'],//商户系统唯一订单号
            'dt_order'       => $arr['dt_order'],//订单时间 YYYYMMDDH24MISS 14位
            'name_goods'    => $arr['name_goods'],//商品名称
            'money_order'   => $arr['money_order'],//交易金额 元 大于0 小数点两位
            'notify_url'  => $this->_notify,//服务器异步通知地址
            'id_type'        => '0',//证件类型 0 默认  身份证
            'id_no'          => $nric,//证件号
            'card_no'       => $arr['card_no'],//卡号 卡前置时 必须
            'acct_name'     => $real_name,//银行账户姓名
            'risk_item'     => $risk,//风险控制参数 json
            /* 以下选填  */
            'platform'      => isset($arr['platform'])?$arr['platform']:'',//
            'info_order'    => isset($arr['info_order'])?$arr['info_order']:'',//描述
            'url_return'    => $this->_return_url,//支付结束回显url
            'no_agree'      => isset($arr['no_agree'])?$arr['no_agree']:'',//用户签约协议号
            'valid_order'   => isset($arr['valid_order'])?$arr['valid_order']:'', //订单有效时间 分钟
            'shareing_data' => isset($arr['shareing_data'])?$arr['shareing_data']:'' //分账信息数据
        );
        return $this->_form($data);
    }

    /**
     * 验证银行卡bin信息
     * @param $card_no string 卡号
     * @param int $pay_type 支付类型 2 快捷支付  D 认证支付
     * @param int $limit 是否返回限额信息  1 返回 0不返回
     * @return mixed json
     * array(
        'ret_code'=> '',//0000
        'ret_msg'=> '交易成功',
        'sign_type'=>'',  //签名类型 MD5 RSA
        'sign'=>'',        //签名
        'bank_code'=> '',
        'bank_name'=> '',
        'card_type'=> '', // 2   储蓄卡  3 信用卡
        'single_amt'=> '', // 单笔限额     pay_type = d  且 flag_amt_limit =1 时返回 限额信息
        'day_amt'=> '', //单日限额 元
        'month_amt'=> ''//单月限额
        );
     */
    public function check_bin($card_no,$pay_type=2,$limit=0){
        if(empty($card_no) || !is_numeric($card_no)) return $this->_my_return('请传入正确的银行卡号！');

        $data =array(
            'oid_partner'       => $this->_oid_partner,//商户号码
            'card_no'           => $card_no,    //卡号
            'pay_type'          => $pay_type,   // 支付方式  快捷支付 2 （默认）  认证支付 D
            'flag_amt_limit'   => $limit //是否返回限额 0 不返回（默认）  1 返回
        );
        $rs = $this->_curl($this->_llpay_api['bin'],$this->_init_data($data));
        return $this->sign_verify($rs);
    }

    /**
     * 查询用户 签约银行卡信息
     * @param int $uid
     * @return mixed json
     * array(
        'ret_code'=> '',//0000
        'ret_msg'=> '交易成功',
        'user_id'=> '',
        'count'=> '',
        'agreement_list'=> '', //结果集
        'sign_type'=>'',  //签名类型 MD5 RSA
        'sign'=>'',        //签名
        'no_agree'=> '',
        'card_no'=> '', //卡号后四位
        'bank_code'=> '', //
        'bank_name'=> '', //
        'card_type'=> '', // 2   储蓄卡  3 信用卡
        'bind_mobile'=> '' // 绑定手机
        );
     */
    public function get_card_list($uid = 0){
        $uid = $uid?$uid:$this->_ci->session->userdata('uid');
        if(!$uid)return  $this->_my_return('用户uid为空！');

        $data =array(
            'oid_partner' => $this->_oid_partner,//商户号码
            'user_id'     => $uid,  //商户用户唯一编号
            'platform'    => '',    //平台来源
            'pay_type'    => 'D',   // 支付方式  快捷支付 2 （默认）  认证支付 D
            'no_agree'    => '', //连连银通 签约编号
            'offset'      => '0'
        );

        $rs = $this->_curl($this->_llpay_api['card_list'],$this->_init_data($data));
//        return $this->sign_verify($rs);
        return $rs;
    }

    /**
     * @param int $uid
     * @param int $no_agree
     * @return string
     * array(
        'ret_code'=> '',//0000
        'ret_msg'=> '交易成功',
        'sign_type'=>'',  //签名类型 MD5 RSA
        'sign'=>'',        //签名
        );
     */
    public function card_unbind($uid=0 ,$no_agree=0){
        $uid = $uid?$uid:$this->_ci->session->userdata('uid');
        if(!$uid)       return  $this->_my_return('用户uid为空！');
        if(!$no_agree)  return  $this->_my_return('银行卡签约编号为空！');

        $data =array(
            'oid_partner'   => $this->_oid_partner,//商户号码
            'user_id'       => $uid,  //商户用户唯一编号
            'platform'      => '',    //平台来源 可不传
            'pay_type'      => 'D',   // 支付方式  快捷支付 2 （默认）  认证支付 D
            'no_agree'      => $no_agree //连连银通 签约编号
        );

        $rs = $this->_curl($this->_llpay_api['card_unbind'],$this->_init_data($data));
        return $this->sign_verify($rs);
    }

    /**
     * 查询订单结果
     * @param int $no_order 商户订单号
     * @param int $dt_order 订单时间 14位  YYYYMMDDH24MISS
     * @param string $odi_paybill
     * @return mixed
     * array(
        'ret_code'=>'0000',
        'ret_ms'=>'',
        'sign_type'=>'',
        'sign'=>'',
        'result_pay'=>'',//结果：SUCCESS WAITING 等待支付 PROCESSING银行支付处理中 REFUND 退款 FAILURE 失败
        'oid_partner'=>'',
        'dt_order'=>'',
        'no_order'=>'',
        'oid_paybill'=>'',
        'money_order'=>'', //金额 元 精确两位
        'settle_date'=>'',//YYYYMMDD 支付成功后会有 清算日期
        'info_order'=>'',//描述
        'pay_type'=>'',
        'bank_code'=>'',
        'bank_name'=>'', //不参与签名
        'memo'=>'', //支付备注 不参与签名
        'card_no'=>''//不参与签名
        );
     */
    public function get_result($no_order=0,$dt_order=0,$oid_paybill=''){
        if(!$no_order)return  $this->_my_return('订单编号不能为空！');
        if(!$dt_order)return  $this->_my_return('订单时间不能为空！');

        $data =array(
            'oid_partner'   => $this->_oid_partner,//商户号码
            'user_id'       => $this->_ci->session->userdata('uid'),  //商户用户唯一编号
            'no_order'      => $no_order, //商户系统唯一订单号
            'dt_order'      => $dt_order, //商户订单时间 YYYYMMDDH24MISS 14位
            'oid_paybill'   => $oid_paybill, //连连支付订单号  可不传
            'query_version' => '' //默认1.0 可不传
        );

        $rs = $this->_curl($this->_llpay_api['result'],$this->_init_data($data));
        return $this->sign_verify($rs,'result');
    }

    /**
     * 生成签名
     * @param $data  array 报文数组
     * @return string
     */
    protected function _sign($data){
        $sign = '';
        if( ! empty($data)){
            ksort($data); // 排序
            reset($data);
            $sign = http_build_query($data); //生成 键值对
            $sign.= '&key='.$this->_md5_key;
            $sign = urldecode($sign);//反编译url和中文
            $sign_type = (isset($data['sign_type']))?$data['sign_type']:$this->_sign_type;
            switch($sign_type){
                case 'MD5':
                    $sign = md5($sign);
                    break;
                case 'RSA':
                    $sign = '';
                    break;
                default :
                    $sign = md5($sign);
            }
        }
        return $sign;
    }

    /**
     * curl 请求
     * @param $uri string api接口地址
     * @param $data array post的数组
     * @return mixed json
     */
    protected function _curl($uri,$data){
        $is_post = empty($data)?false:true;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $uri );
        curl_setopt ( $ch, CURLOPT_POST, $is_post );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json','Content-Length: ' . strlen($data)));
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        if($is_post)curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $result = curl_exec ( $ch );
        curl_close ( $ch );

        return $result;
    }

    /**
     * 格式化 报文数组
     * @param $data
     * @return string
     */
    protected function _init_data($data){
        $data = $this->_paraFilter($data);//去空 和sign
        $data['sign_type'] = $this->_sign_type;
        $data['sign']       = $this->_sign($data);
        if(isset($data['risk_item']))$data['risk_item'] = str_replace('"','\"',$data['risk_item']);
        foreach ($data as $key => $value) {
            $data[$key] = urlencode($value);
        }
        return urldecode(json_encode($data));
    }

    /**
     * 构建提交的表单
     * @param $data array 数组
     * @return string 表单html
     */
    protected function _form($data) {
        //待请求参数数组
        $para = $this->_init_data($data);
        $sHtml = "<form id='llpaysubmit' name='llpaysubmit' action='" . $this->_llpay_api['pay'] . "' method='post'>";
        $sHtml .= "<input type='hidden' name='req_data' value='" . $para . "'/>";
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "</form>";
        $sHtml = $sHtml."<script>document.forms['llpaysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 过滤 数组中的空 和 sign键值对
     * @param $para
     * @return array
     */
    protected function _paraFilter($para) {
        $para_filter = array();
        foreach($para as $key=>$val){
            if($val === "" || $val == "sign")continue;
            else $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 参数验证的返回
     * @param $msg string 提示信息
     * @return string json
     */
    protected function _my_return($msg){
        return json_encode(array('ret_code'=>1,'ret_msg'=>$msg));
    }

    /**
     * 验证 连连支付 返回结果时的签名  有sign则验证签名 没有签名 而且ret_code = 0000 成功的标识 验证不通过  否则结果集错误信息
     * @param $result  string 结果集
     * @param string $type  string 类型  默认空
     * @return string 正确返回结果集  错误返回提示信息
     */
    public  function sign_verify($result,$type=''){
        if( ! empty($result)){
            $result_array = json_decode($result,true);
            if($type == 'result'){  //过滤 查询结果 不参与签名的字段
                if(isset($result_array['bank_name'])) unset($result_array['bank_name']);
                if(isset($result_array['memo'])) unset($result_array['memo']);
                if(isset($result_array['card_no'])) unset($result_array['card_no']);
            }
            if((isset($result_array['ret_code']) && $result_array['ret_code'] == '0000') || (isset($result_array['result_pay']) && $result_array['result_pay'] == 'SUCCESS')){ //成功的时候验证签名
                $ll_sign = $result_array['sign'];
                unset($result_array['sign']);
                $my_sign = $this->_sign($result_array);
                if($ll_sign === $my_sign){
                    return $result;
                }else{
                    return $this->_my_return('连连支付返回签名验证为通过！');
                }
            }else{
                return $result;
            }
        }
    }
}
