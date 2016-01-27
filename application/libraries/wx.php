<?php
/**
* 微信相关处理
*/
class CI_wx{
    //微信公众号相关参数
    protected $_ghid      = 'gh_90ba2364f6aa';                  //公众号ghid
    protected $_appid     = 'wx43bcf839ae10dab4';               //公众号appid
    protected $_appsecret = '34b60a4f8dedd64b363ea4b6ccde7a44'; //公众号appsecret
    protected $_CI        = '';
    protected $_state     = '';

    const code_url  = 'https://open.weixin.qq.com/connect/oauth2/authorize'; //获取code的地址
    const token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token'; //获取全局token openid的地址
    const userinfo_url = 'https://api.weixin.qq.com/sns/userinfo'; //获取userinfo的地址

    const log_dir = './application/logs/wx_ticket.txt';//微信分享js ticket 存储位置

	function __construct(){
		$this->_CI = &get_instance();
        $this->_state = uniqid();
	}

    public function authorization($type='base'){
        $code = $this->get_code($type);
        if( !$code)$this->error_info('获取code失败!');
        $data['code'] = $code;
        $token_openid = $this->get_openid_token($code,false);
        if($token_openid === false){
            unset($_GET['code']);
            unset($_GET['state']);
            $this->get_code($type);
        }
        $data['token'] = $token_openid['token'];
        $data['openid'] = $token_openid['openid'];
        if($type == 'userinfo'){
            $info = $this->get_userinfo($token_openid['token'],$token_openid['openid']);
            $data = array_merge($data,$info);
        }
        return $data;
    }

    /**
     * 获取code
     * @param string $type base|userinfo
     * @return mixed
     */
    public function get_code($type='base'){
        if(isset($_GET['code'])){
            return $this->_CI->input->get('code',true);
        }else{
            //取消授权
            if($type == 'userinfo' && isset($_GET['state']) && $this->_CI->input->get('state') == $this->_state){
                exit();
            }else{
                $url=$this->get_current_url();
                redirect(sprintf(self::code_url.'?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect',$this->_appid,urlencode($url),'snsapi_'.$type,$this->_state));
            }
        }
    }

    /**
     * 获取token 和openid
     * @param string $code
     * @param boolean $showError
     * @return array
     */
    public function get_openid_token($code='',$showError=true){
        $rs=$this->get_content(self::token_url.sprintf('?appid=%s&secret=%s&code=%s&grant_type=authorization_code',$this->_appid,$this->_appsecret,$code));
        if(isset($rs['errcode']) && isset($rs['errmsg'])){
            if($showError){
                $this->error_info('code:'.$rs['errcode'].',msg:'.$rs['errmsg']);
            }else{
                return false;
            }
        }
        return array('token'=>$rs['access_token'],'openid'=>$rs['openid']);
    }

    /**
     * 获取用户信息
     * @param string $token
     * @param string $openid
     * @return mixed
     */
    public function get_userinfo($token='',$openid=''){
        $url = self::userinfo_url."?access_token={$token}&openid={$openid}&lang=zh_CN";
        $rs = $this->get_content($url);
        if(isset($rs['errcode']) && isset($rs['errmsg'])){
            $this->error_info('code:'.$rs['errcode'].',msg:'.$rs['errmsg']);
        }
        return $rs;
    }

    /**
     * 获取当前全路径
     * @return string
     */
    function get_current_url(){
        $url='http://';

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')$url='https://';

//        if($_SERVER['SERVER_PORT'] != '80'){
//            $url.=$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
//        }else{
            $url.=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//        }

        return $url;
    }

    /**
     * 获取 网页内容 json
     */
    public function get_content($url,$data =false){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($data)curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        return json_decode(curl_exec($ch),true);
    }

    public function error_info($info=''){
    	exit('<h3 style="text-align: center;margin-top: 20%;">'.$info.'</h3>');
    }


    /**
     * 获取微信分享js接口ticket
     * @param $url
     * @return array|mixed
     */

    public function get_jsapi_ticket($url){
        $ticket = json_decode($this->get_php_file(self::log_dir),true);
        if ($ticket['timestamp'] < time()){
            $ticket = $this->_get_jsapi_ticket();
            $ticket['timestamp'] += 7000;
            $this->set_php_file(self::log_dir,json_encode($ticket));
        }
        $data = $this->_signature($ticket['ticket'],$url);
        return $data;
    }

    private function _get_jsapi_ticket(){
        $ACCESS_TOKEN = $this->getAccessToken();
        $result = $this->get_content("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$ACCESS_TOKEN}&type=jsapi");
        $data['ticket'] = $result['ticket'];
        $data['token']  = $ACCESS_TOKEN;
        return $data;
    }
    
    private function _signature($ticket,$url){
        $noncestr = uniqid();
        $jsapi_ticket = $ticket;
        $timestamp = time();
        $str = sprintf('jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s',$jsapi_ticket,$noncestr,$timestamp,$url);
        $sha =  sha1($str);
        return array(
            'noncestr'  =>$noncestr,
            'ticket'    => $jsapi_ticket,
            'timestamp' => $timestamp,
            'signature' => $sha,
            'url'       => $url,
            'appid'     =>$this->_appid
        );
    }

    private function get_php_file($filename) {
        return trim(file_get_contents($filename));
    }
    private function set_php_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp,$content);
        fclose($fp);
    }
    private function getAccessToken() {
        // 如果是企业号用以下URL获取access_token
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->_appid&corpsecret=$this->_appsecret";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->_appid&secret=$this->_appsecret";
        $res = $this->get_content($url);
        $access_token = $res['access_token']?$res['access_token']:'';
        return $access_token;
    }
}