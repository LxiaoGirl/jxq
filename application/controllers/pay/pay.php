<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 智付支付平台异步通知接口
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-29
 * @updated     2014-09-29
 * @version     1.0.0
 */

class Pay extends Login_Controller
{
    const flow     = 'cash_flow'; // 资金记录
    const recharge = 'user_recharge'; // 充值记录
    const user = 'user'; //  用户信息

    /**
     * Pay constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pay_model', 'pay');

    }
    /************************web2.0 wsb 修改**********************************************************/
    /**
     * 首页
     *
     * @access public
     * @return void
     */

     public function index(){
        $data = $temp = array();
        //防止 直接访问
        if(!$this->session->userdata('clientkind') || in_array($this->session->userdata('clientkind'),array('-1','-2'))){
            if($this->session->userdata('clientkind') == '-2'){
                $this->_redirect('login/company_apply',3,'请先进行实名认证!');
            }else{
                $this->_redirect('user/user/account_security',3,'请先进行实名认证!');
            }
        }

         if((int)date('Hi') >= 2330 || (int)date('Hi') <= 30){
             $this->_redirect('user/user/account_security',3,'聚雪球平台每日凌晨23:30-00:30间不可充值，为银行日切时间，请大家避开此时间段充值。带来不便，敬请谅解。望周知！');
         }

         if((int)date('Ymd') >= 20160206 && (int)date('Ymd') <= 20160214){
             $this->_redirect('user/user/account_security',3,'温馨提示：网银支付系统将于2016-02-6  至 2016-02-14 这段时间内停止使用，2016-02-15 恢复使用');
         }

        //接受参数
        $temp['bank']        = $this->input->post('bank');
        $temp['recharge_no'] = authcode($this->input->get('recharge_no',TRUE),'',TRUE);
        $temp['amount']      = (float)$this->input->get('amount');
        $temp['recharge_min'] = item('recharge_min')?item('recharge_min'):50;

        //验证订单号和金额
        if(empty($temp['recharge_no'])){
            $this->_redirect('user/user/recharge',3,'充值单号出错了!!');
        }


        $temp['where'] = array(
            'select' => 'uid,recharge_no,bank,amount,add_time',
            'where'  => array(
                'recharge_no' => $temp['recharge_no']
            )
        );
        $temp['recharge'] = $this->c->get_row(self::recharge, $temp['where']);


        // 判断充值流水是否存在
        if( empty($temp['recharge'])){
            //没有订单号 再验证金额  有订单号的重新提交可以不用传金额了
            if( ! is_numeric($temp['amount']) || $temp['amount'] < $temp['recharge_min']){
                $this->_redirect('user/user/recharge',3,'最低充值金额为'.$temp['recharge_min'].'元!');
            }

            // 充值记录写入数据库
            $temp['data'] = array(
                'recharge_no' => $temp['recharge_no'],
                'uid'         => $this->session->userdata('uid'),
                'type'        => 2,
                'bank'        => $temp['bank'],
                'amount'      => $temp['amount'],
                'remarks'     => '会员充值',
                'add_time'    => time()
            );
            $query = $this->c->insert(self::recharge, $temp['data']);

            if($query) {
                // 查询用户真实姓名和三方账号
                $temp['where'] = array(
                    'select' => 'firmid,real_name',
                    'where' => array(
                        'uid' =>$this->session->userdata('uid'),
                    )
                );
                $temp['usr'] = $this->c->get_row(self::user, $temp['where']);

                // 判断充值记录写入是否成功
                if ( !empty($temp['usr']) && !empty($temp['usr']['firmid']) && !empty($temp['usr']['real_name'])) {
                    $MarketSerial    = $temp['recharge_no'];
                    $FirmId          = $temp['usr']['firmid'];
                    $CustName        = $temp['usr']['real_name'];
                    $TransferAmount  = floatval( $temp['amount'] * 100);// 转化为分单位
                    $Add1            = site_url('pay/pay/recharge_status?recharge_no=') . $MarketSerial;
                    // 与三方通讯
                    $configData = $this->pay->chongzhi($MarketSerial, $FirmId, $CustName, $TransferAmount, $Add1);
                    // post数据给三方支付
                    if (!empty($configData['Address']['Add2'])) {
                        $form = "";
                        $form .= '<meta charset="utf-8">';
                        $form .= '<form name="pay_form" id="pay_form" action="' . $configData['Address']['Add2'] . '" method="post">';//wsb-充值测试  修改-205.5.12 原https://pay.dinpay.com/gateway?input_charset=UTF-8" method="post
                        $form .= '<input type="hidden" name="orig" value="' . $configData['CfcaParamList']['orig'] . '"/>';
                        $form .= '<input type="hidden" name="sign" value="' . $configData['CfcaParamList']['sign'] . '"/>';
                        $form .= '<input type="hidden" name="returnurl" value="' . $configData['CfcaParamList']['returnurl'] . '"/>';
                        $form .= '<input type="hidden" name="NOTIFYURL" value="' . $configData['CfcaParamList']['NOTIFYURL'] . '"/>';
                        $form .= '</form>';
                        $form .= '<script>document.forms[\'pay_form\'].submit();</script>';
                        echo $form;
                        exit();
                    }
                    $this->_redirect('user/user/recharge_jl',3,'通信异常请稍后重试!');
                }else{
                  // 实名信息不完整
                    $this->_redirect('user/user/recharge',3,'用户信息异常请稍后重试!');
                }
            }else{
               // 数据库插入失败
                $this->_redirect('user/user/recharge',3,'服务器繁忙请稍后重试!');
            }
        }else{
            //不开启 重新提交 直接跳转  开启则删除此跳转
            $this->_redirect('user/user/recharge_jl',3,'该订单已存在!');

            // 有充值记录 验证充值
            if($temp['recharge']['status'] == 0){
                //上面充值的处理的复制
            }else{
                //已经是成功的订单号了 返回
                $this->_redirect('user/user/recharge_jl',3,'该订单已成功!');
            }
        }
	}

    /**
     * 带提示信息的跳转
     * @param string $uri
     * @param int $delay
     * @param string $msg
     */
    protected function _redirect($uri='',$delay=0,$msg=''){
        if($uri == ''){
            $uri = site_url('');
        }else{
            $uri = site_url($uri);
        }
        if( !is_numeric($delay) || $delay < 0)$delay=0;
        if($msg != '' && $delay > 0){
            header("Content-type:text/html;charset=utf-8");
            echo '<h4 style="text-align: center;">'.$msg.','.$delay.'秒后跳转 <a href="'.site_url('user/user/recharge').'">直接跳转</a></h4>';
        }
        header("Refresh:".$delay.";url=".$uri);
        exit();
    }

    /************************web2.0 wsb 修改 以下为1.0版本**********************************************************/
	/**
     * 查询用户账户
     *
     * @access public
     * @return void
     */
	
	public function check()
    {
		

		
	}
	 /**
     * 认证扣费
     *
     * @access public
     * @return void
     */
	
	 public function renzheng()
    {
        $data = $temp = array();

        $temp['recharge_no'] = $this->input->get('recharge_no', TRUE);

        if( ! empty($temp['recharge_no']))
        {
            $temp['where'] = array(
                                'select' => 'uid,recharge_no,bank,amount,add_time',
                                'where'  => array(
                                                'recharge_no' => $temp['recharge_no'],
                                                'status'      => 0
                                            )
                            );
            $temp['recharge'] = $this->c->get_row(self::recharge, $temp['where']);
            if( ! empty($temp['recharge']))
            {
				$TransferAmount = floatval($temp['recharge']['amount']*100);
				$MarketSerial=$temp['recharge_no'];
				$configData = $this->pay->renzheng($MarketSerial);
				$form ="";
				$form .= '<meta charset="utf-8">';
				$form .= '<form name="pay_form" id="pay_form" action="'.$configData['Address']['Add2'].'" method="post">';//wsb-充值测试  修改-205.5.12 原https://pay.dinpay.com/gateway?input_charset=UTF-8" method="post
				$form .= '<input type="hidden" name="orig" value="'.$configData['CfcaParamList']['orig'].'"/>';
				$form .= '<input type="hidden" name="sign" value="'.$configData['CfcaParamList']['sign'].'"/>';
				$form .= '<input type="hidden" name="returnurl" value="'.site_url('pay/pay/recharge_status?recharge_no='.$MarketSerial."&type=2").'"/>';
				$form .= '<input type="hidden" name="NOTIFYURL" value="'.$configData['CfcaParamList']['NOTIFYURL'].'"/>';
				$form .= '</form>';
				$form .= '<script>document.forms[\'pay_form\'].submit();</script>';
				echo $form;
				exit();
            }
        }
         redirect('user/transaction/recharge_list', 'refresh');
    
	}
	
	/**
     * 异步验证充值
     *
     * @access public
     * @return void
     */

    public function recharge_status()
    {
		//header("Content-type:text/html;charset=gbk");	
		$data = $_REQUEST;
		
		
		//var_dump($data);
		// 'orig' => string 'PGtDb2xsIGlkPSJvdXRwdXQiIGFwcGVuZD0iZmFsc2UiPjxmaWVsZCBpZD0ic3RhdHVzIiB2YWx1%0AZT0iMDEiLz48ZmllbGQgaWQ9ImRhdGUiIHZhbHVlPSIyMDE1MDUyNzA5MjMxMiIvPjxmaWVsZCBp%0AZD0iY2hhcmdlIiB2YWx1ZT0iMTAiLz48ZmllbGQgaWQ9Im1hc3RlcklkIiB2YWx1ZT0iMjAwMDMx%0AMTE0NiIvPjxmaWVsZCBpZD0ib3JkZXJJZCIgdmFsdWU9IjIwMDAzMTExNDYyMDE1MDUyNzAwMDA1%0ANjc1Ii8%2BPGZpZWxkIGlkPSJjdXJyZW5jeSIgdmFsdWU9IlJNQiIvPjxmaWVsZCBpZD0iYW1vdW50%0AIiB2YWx1ZT0iMTAwMCIvPjxmaWVsZCBpZD0icGF5ZGF0ZSIgdmFsdWU9IjIwMTUwNTI3MDkyNzI0%0AIi8%2BPGZpZWxkIGlkPSJyZW1hcmsiIHZhbH'... (length=703)
		// 'sign' => string 'ODdjNjJhZjgzNzRlNjcxNjg4YTYxMTRiM2ZkNjg5MjU3MzQyM2RmMWM4ODJkMmU1ZGM0NDEwZjZm%0ANGMwYjY2Nzk0MmVhZjJjODIwZTZhZGQ0MjJlNzI5MGUxZjY4ZTMxMTVlMmQ4MTY0YjIyM2E2NGEy%0AMDkzNmUwOTg0MTA4MmM0ZWI2NTM5YWMwZjc1ZjNkODk1MDc3MTE3NGU2NzBiNDE3ZDBlODcyZGUx%0AZjZhNGIxYWM2NWViYzQxOWE5Y2FlYzVmMWEyNTczYzA4NzkyZmEzMGEwYjZkMGI1NTk3MTRkZmRi%0AOTBkNTVhOTBjOTRiNzhiNjdmYjAyYjQ0MzdmOA%3D%3D%0A' (length=363)
		$result = base64_decode($data['orig']);
		//echo $data['orig'];
		$result1 = $data['orig'];
		$result1 = base64_decode(urldecode($result1));
		preg_match('/<field id="status" value="(.*)"\/>/isU',$result1,$status);
		preg_match('/<field id="charge" value="(.*)"\/>/isU',$result1,$charge);// 这个是手续费，默认都是10，有疑问
		preg_match('/<field id="masterId" value="(.*)"\/>/isU',$result1,$masterId);
		preg_match('/<field id="orderId" value="(.*)"\/>/isU',$result1,$orderId);
		preg_match('/<field id="currency" value="(.*)"\/>/isU',$result1,$currency);
		preg_match('/<field id="amount" value="(.*)"\/>/isU',$result1,$amount);
		preg_match('/<field id="paydate" value="(.*)"\/>/isU',$result1,$paydate);

        $temp['recharge'] = $this->_get_recharge_info($data['recharge_no']);
		if($data['type']!=2){
			
			if($status[1]=="01")
			
			{
					$query = $this->_set_recharge_status($data['recharge_no'], "1");	

					if( ! empty($query))
					{
						$this->_add_cash_flow($temp['recharge']['uid'], $amount[1],  $data['recharge_no']);
					}			
			}
			$this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));
			redirect('user/transaction/recharge_list', 'refresh');
		}else{
			
			if($status[1]=="01")
			
			{
					$query = $this->_set_recharge_status($data['recharge_no'], "1");	

					if( ! empty($query))
					{
						$this->_add_cash_flow("32", $amount[1],  $data['recharge_no'],"认证扣费");
					}			
			}		
			redirect('user/transaction/recharge_list', 'refresh');
		}
    }

    /**
     * 异步通知
     *
     * @access private
     * @return void
     */

    public function notify()
    {
        $query = FALSE;
        $temp  = array();

        $temp['recharge_no'] = $this->input->post('order_no', TRUE);

        if( ! empty($temp['recharge_no']))
        {
            $temp['data'] = array(
                                'merchant_code'      => $this->input->post('merchant_code', TRUE),
                                'notify_type'        => $this->input->post('notify_type', TRUE),
                                'notify_id'          => $this->input->post('notify_id', TRUE),
                                'interface_version'  => $this->input->post('interface_version', TRUE),
                                'sign_type'          => $this->input->post('sign_type', TRUE),
                                'sign'               => $this->input->post('sign', TRUE),
                                'order_no'           => $temp['recharge_no'],
                                'order_time'         => $this->input->post('order_time', TRUE),
                                'order_amount'       => $this->input->post('order_amount', TRUE),
                                'extra_return_param' => $this->input->post('extra_return_param', TRUE),
                                'trade_no'           => $this->input->post('trade_no', TRUE),
                                'trade_time'         => $this->input->post('trade_time', TRUE),
                                'trade_status'       => $this->input->post('trade_status', TRUE),
                                'bank_seq_no'        => $this->input->post('bank_seq_no', TRUE),
                            );

            $temp['sign']     = $this->_get_sign_string($temp['data']);
            $temp['recharge'] = $this->_get_recharge_info($temp['recharge_no']);

            if( ! empty($temp['recharge']) && $temp['sign'] == $temp['data']['sign'])
            {
                $query = $this->_set_recharge_status($temp['recharge_no'], $temp['data']['trade_no']);

                if( ! empty($query))
                {
                    $this->_add_cash_flow($temp['recharge']['uid'], $temp['recharge']['amount'], $temp['recharge_no']);
                }
            }
        }

//        echo ( ! empty($query)) ? 'SUCCESS' : 'FAILED';

        //wsb-支付接口 测试 修改
        header("Content-type:text/html;charset=utf-8");
        echo load_file('style.css,style_addin.css');
        echo load_file('seajs/sea.js');
        if(! empty($query)){
            $this->session->set_userdata('balance',$this->_get_user_balance($this->session->userdata('uid')));

            echo '<script>seajs.use([,"jquery","sys"],function(){ $(function(){ sys.alert("充值成功");setTimeout(function(){ window.location.href="'.site_url('user/transaction/recharge_list').'"},1000)});})</script>';
        }else{
            echo '<script>seajs.use([,"jquery","sys"],function(){ $(function(){ sys.alert("充值失败","z-error");setTimeout(function(){ window.location.href="'.site_url('user/transaction/recharge_list').'"},1000)});})</script>';
        }
    }

    /**
     * 添加充值记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  float   $amount 充值金额
     * @param  string  $source 记录来源
     * @return boolean
     */

    private function _add_cash_flow($uid = 0, $amount = 0, $source = '' , $remarks = '会员充值')
    {
        $query = FALSE;
        $temp  = array();

		//var_dump($uid);
        if( ! empty($uid) && ! empty($amount) && ! empty($source))
        {
            $temp['where'] = array('where' => array('source' => $source));
            $temp['count'] = $this->c->count(self::flow, $temp['where']);

            if($temp['count'] == 0)
            {
                $temp['balance'] = $this->_get_user_balance($uid);

                $temp['data'] = array(
                                    'uid'      => $uid,
                                    'type'     => 1,
                                    'amount'   => $amount,
                                    'balance'  => round($amount + $temp['balance'], 2),
                                    'source'   => $source,
                                    'remarks'  => $remarks,
                                    'dateline' => time(),
                                );

                $query = $this->c->insert(self::flow, $temp['data']);
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 生成提交表单
     *
     * @access private
     * @param  array   $args 用户参数
     * @return string
     */

    private function _create_form($configData)
    {
			$form = '';
            $form .= '<meta charset="utf-8">';
            $form .= '<form name="pay_form" id="pay_form" action="'.$configData['Address']['Add2'].'" method="post">';//wsb-充值测试  修改-205.5.12 原https://pay.dinpay.com/gateway?input_charset=UTF-8" method="post
            $form .= '<input type="hidden" name="orig" value="'.$configData['CfcaParamList']['orig'].'"/>';
            $form .= '<input type="hidden" name="sign" value="'.$configData['CfcaParamList']['sign'].'"/>';
            $form .= '<input type="hidden" name="returnurl" value="'.$configData['CfcaParamList']['returnurl'].'"/>';
            $form .= '<input type="hidden" name="NOTIFYURL" value="'.$configData['CfcaParamList']['NOTIFYURL'].'"/>';
            $form .= '</form>';
            $form .= '<script>document.forms[\'pay_form\'].submit();</script>';
			return $form;
    }

    /**
     * 获取会员余额
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return float
     */

    private function _get_user_balance($uid = 0)
    {
        $balance = 0;
        $temp    = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => $uid),
                                'order_by' => 'id desc'
                            );

            $balance = $this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取充值信息
     *
     * @access private
     * @param  string   $recharge_no 充值编号
     * @return float
     */

    private function _get_recharge_info($recharge_no = '')
    {
        $data = $temp = array();

        if( ! empty($recharge_no))
        {
            $temp['where'] = array(
                                'select' => 'uid,amount',
                                'where'  => array('recharge_no' => $recharge_no)
                            );

            $data = $this->c->get_row(self::recharge, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 生成签名
     *
     * @access private
     * @param  array   $args 用户参数
     * @return string
     */

    private function _get_sign_string($args = array())
    {
        $sign = '';

        if( ! empty($args))
        {
            ksort($args);

            foreach($args as $k => $v)
            {
                if( ! in_array($k, array('sign', 'sign_type')) && ! empty($v))
                {
                    $sign .= $k.'='.$v.'&';
                }
            }

            $sign .= 'key=SAE8asio1_4006_COM_ASD_1239iadsCM12sd23rSf';
            $sign = md5($sign);
        }

        return $sign;
    }

    /**
     * 更新充值状态
     *
     * @access private
     * @param  string   $recharge_no 充值编号
     * @param  string   $source      流水编号
     * @return boolean
     */

    private function _set_recharge_status($recharge_no = '', $source = '')
    {
        $query = FALSE;
        $temp  = array();

        $temp['data']  = array(
                            'source'       => $source,
                            'operator'     => 'System',
                            'confirm_time' => time(),
                            'status'       => 1
                        );

        $temp['where'] = array(
                            'where' => array('recharge_no' => $recharge_no, 'status' => 0)
                         );

        $query = $this->c->update(self::recharge, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }



    //wsb-测试。。。 205.5.12↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
    public function test(){
        $temp['data'] = array(
            'merchant_code'      => $this->input->post('merchant_code', TRUE),
            'notify_type'        => $this->input->post('notify_type', TRUE),
            'notify_id'          => $this->input->post('notify_id', TRUE),
            'interface_version'  => $this->input->post('interface_version', TRUE),
            'sign_type'          => $this->input->post('sign_type', TRUE),
            'sign'               => '',
            'order_no'           => $this->input->post('order_no', TRUE),
            'order_time'         => $this->input->post('order_time', TRUE),
            'order_amount'       => $this->input->post('order_amount', TRUE),
            'extra_return_param' => '',
            'trade_no'           => 'n'.date('Ymdhis'),
            'trade_time'         => date("Y-m-d H:i:s"),
            'trade_status'       => 1,
            'bank_seq_no'        => 'bank_seq_no'.date('YmdHis'),
        );
        $temp['data']['sign']=$this->_get_sign_string($temp['data']);
        echo $this->_create_form_test($temp['data']);
        exit();
    }

    /**
     * wsb-模拟 第三方充值回调 表单 205.5.12
     * @param array $args
     * @return string
     *
     */
    private function _create_form_test($args = array())
    {
        $form = '';

        if( ! empty($args))
        {
            $form .= '<meta charset="utf-8">';
            $form .= '<form name="pay_form" id="pay_form" action="'.site_url('pay/dinpay/notify').'" method="post">';

            foreach ($args as $k => $v)
            {
                $form .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
            }

            $form .= '</form>';
            $form .= '<script>document.forms[\'pay_form\'].submit();</script>';
        }

        return $form;
    }
}