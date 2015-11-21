<?php

/**
 * 发送短信
 * Class CI_Sms
 */
class CI_Sms {
	//短信 通道配置
	protected $_sms_config       = array(
		'username'        =>'cqdx008',                            //用户名
		'password'        =>'cq008',                              //密码
		'receive_number'  =>'',
		'message_content' =>'',                                   //密码
		'wsdl_url'        =>'http://61.156.157.209:8889/api.php', //webserver 短信通道地址
		'signature_pre'   =>'',                                   //签名（前）
		'signature_suff'  =>''                                    //签名（后）
	);

	public function __construct($params=array()){

	}

	/**
	 * 发送手机短信
	 * @param string $mobile
	 * @param string $content
	 *
	 * @return bool|string
	 */
	public function send($mobile = '', $content = ''){
		$result = FALSE;

		if( ! empty($mobile) && ! empty($content)){
			$post_data = array(
				'username'=>$this->_sms_config['username'],
				'password'=>$this->_sms_config['password'],
				'action'=>'send',
				'receive_number'=>$mobile,
				'message_content'=>$content
			);
			if (!isset($content{70})){
				$result = $this->_file_get_contents_post($this->_sms_config['wsdl_url'], $post_data);
			}else{
				$post_data['split_type'] = 1;
				$result = $this->_file_get_contents_post($this->_sms_config['wsdl_url'], $post_data);
			}
		}

		//$query = FALSE;
		//$temp  = array();
		// if( ! empty($mobile) && ! empty($code) && ! empty($content))
		// {
		/***2015.5.5 修改****/
		/*
			$temp['args'] = array(
				'userCode' => 'yitou',
				'userPass' => 'yitou123',
				'DesNo'    => $mobile,
				'Msg'      => $content,
				'Channel'  => ''
			);
		*/
		/*
			 $temp['args'] = array(
				 'arg0'=>$this->config->item('serial_number'),
				 'arg1'=>$this->config->item('session_key'),
				 'arg2'=>'',
				 'arg3'=>$mobile,
				 'arg4'=>$content,
				 'arg5'=>'',
				 'arg6'=>'UTF8',
				 'arg7'=>5,
				 'arg8'=>8888
			 );

			$temp['soap'] = new SoapClient($this->config->item('wsdl_url'));//原：'http://121.199.48.186:1210/Services/MsgSend.asmx?WSDL'
			$temp['data'] = $temp['soap']->__soapCall('sendSMS',array('parameters' => $temp['args']));//原：SendMsg


			 if( ! empty($temp['data']))
			 {
				 $query = ($temp['data']->return == 0 && $temp['data']->return != NULL) ? TRUE : FALSE;// 原：  $query = ($temp['data']->SendMsgResult > 0) ? TRUE : FALSE;
			 }
		*/
		/***2015.5.5 修改****/
		// }

		unset($temp);
		return $result;
	}

	/**
	 * 获取wsdl地址内容
	 * @param string $url  wsdl地址
	 * @param array  $post post数组
	 *
	 * @return string
	 */
	protected function _file_get_contents_post($url='', $post=array()) {
		$result = FALSE;
		if($url && $post){
			$options = array(
				'http' => array(
					'method' => 'POST',
					'header' => "Content-type: application/x-www-form-urlencoded ",
					'content' => http_build_query($post),
				),
			);
			$result = file_get_contents($url, false, stream_context_create($options));
			if($result == '1'){
				$result = TRUE;
			}else{
				$result = FALSE;
			}
		}
		return $result;
	}

	/**
	 * 获取短信余额
	 *
	 * @access public
	 * @return float
	 */
	public function get_sms_balance(){
		$data = $this->_file_get_contents_post($this->_sms_config['wsdl_url'], array('username'=>$this->_sms_config['username'], 'password'=>$this->_sms_config['password'], 'action'=>'surplus'));
		return $data;
	}
}