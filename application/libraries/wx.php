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

    const state = '123';
    const code_url  = 'https://open.weixin.qq.com/connect/oauth2/authorize'; //获取code的地址
    const token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token'; //获取全局token openid的地址
    const userinfo_url = 'https://api.weixin.qq.com/sns/userinfo'; //获取userinfo的地址

	function __construct(){
		$this->_CI = &get_instance();
	}

    public function authorization($type='base'){
        $code = $this->get_code($type);
        if( !$code)$this->error_info('获取code失败!');
        $data['code'] = $code;
        $token_openid = $this->get_openid_token($code);
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
            if($type == 'userinfo' && isset($_GET['state']) && $this->_CI->input->get('state') == self::state){
                exit();
            }else{
                $url=$this->get_current_url();
                redirect(sprintf(self::code_url.'?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect',$this->_appid,urlencode($url),'snsapi_'.$type,self::state));
            }
        }
    }

    /**
     * 获取token 和openid
     * @param string $code
     * @return array
     */
    public function get_openid_token($code=''){
        $rs=$this->get_content(self::token_url.sprintf('?appid=%s&secret=%s&code=%s&grant_type=authorization_code',$this->_appid,$this->_appsecret,$code));
        if(isset($rs['errcode']) && isset($rs['errmsg'])){
            $this->error_info('code:'.$rs['errcode'].',msg:'.$rs['errmsg']);
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




    public function get_jsapi_ticket($url){
        $info = $this->_get_jsapi_ticket($this->_appid,$this->_appsecret);
        $result = $this->_signature($info['ticket'],$url);
        return $result;
        /*
        if(IS_AJAX){
            $Token = M('zmf_a_gongzonghao_token')->where(array(
                'ghid' => $ghid,
                'appid' => $appid,
            ))->find();
            //$result =array();
            if($Token){
                if(time() - $Token['time']>=7200){
                    $info = $this->_get_jsapi_ticket($appid,$secret);
                    $result = $this->_signature($info['ticket'],$url);
                    $Token['access_token'] = $info['token'];
                    $Token['jsapi_ticket'] = $info['ticket'];
                    $Token['time'] =time();
                    M('zmf_a_gongzonghao_token')->save($Token);
                }else{
                    $result = $this->_signature($Token['jsapi_ticket'],$url);
                }
            }else{
                $info = $this->_get_jsapi_ticket($appid,$secret);
                $result = $this->_signature($info['ticket'],$url);
                $Token= array(
                    'ghid' => $ghid,
                    'appid' => $appid,
                    'access_token' => $info['token'],
                    'jsapi_ticket' => $info['ticket'],
                    'time' =>time(),
                );
                M('zmf_a_gongzonghao_token')->add($Token);
            }
            $this->ajaxreturn($result);
        }*/
    }

    private function _get_jsapi_ticket($appid,$secret){
        $ACCESS_TOKEN = $this->get_access_token($appid,$secret);
        $result = $this->get_content("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$ACCESS_TOKEN}&type=jsapi");
        if($result['errmsg'] == 'ok')
            return array(
                'ticket' => $result['ticket'],
                'token'  => $ACCESS_TOKEN,
            );
        else
            return false;
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
}