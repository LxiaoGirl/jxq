<?php

/**
 * API 访问类
 * Class CI_Api
 */
class CI_Api {
    private $_appid='wjjf559f2a75b4edd';
    private $_appsecret='d4590b29b63e7a50cc571706e28e7c97';
    private $_now=0;
    private $_connector='@';//生成签名的连接符

    /**
     * 构造函数  初始化参数
     * @param array $para
     */
    public function __construct($para=array()){
        if(isset($para['appid']))$this->_appid=$para['appid'];
        if(isset($para['appsecret']))$this->_appsecret=$para['appsecret'];
    }

    /**
     * 访问主方法
     * @param $url
     * @param array $data
     * @return mixed|string
     */
    public function curl_call($url, $data=array()){
        if( empty($this->_appid) || empty($this->_appsecret))
            return json_encode(array('name'=>'','status'=>'10001','msg'=>'appid appsecret 错误','data'=>array()));
        if( empty($url) )
            return json_encode(array('name'=>'','status'=>'10001','msg'=>'接口地址为空!','data'=>array()));

        //获得结果集
        $result = $this->curl_post($url,$data);
        //验证返回的sign
        if($this->_check_sign($result)){
            return $result;
        }else{
            return json_encode(array('name'=>'','status'=>'10001','msg'=>'返回签名验证错误!','data'=>array()));
        }
    }

    /**
     * 执行post
     * @param $url
     * @param array $data
     * @return mixed|string
     */
    public function curl_post($url, $data=array()){
        $temp=array();
        date_default_timezone_set('PRC');
        $this->_now=date('YmdHis',time());

        $header=array(
            'Accept:application/json',
            'Content-Type:application/x-www-form-urlencoded;charset=utf-8'
        );

        $temp['sign'] = $this->sign();
        $url.=('?sign='.$temp['sign']);

        //初始化curl
        $ch = curl_init();
        //参数设置
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, !empty($data));
        if(!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        $result = curl_exec ($ch);

        //连接失败
        if($result === FALSE){
            $result = json_encode(array('name'=>'','status'=>'10001','msg'=>'网络错误,连接失败!','sign'=>$temp['sign'],'data'=>array()));
        }
        curl_close($ch);

        return $result;

    }

    /**
     * 生成签名
     * @return string
     */
    public function sign(){
        date_default_timezone_set('PRC');
        $this->_now=date('YmdHis',time());
        return base64_encode(md5($this->_appid.$this->_appsecret.$this->_now).$this->_connector.base64_encode( $this->_appid.':'.$this->_now ));
    }

    /**
     * 验证返回的签名信息
     * @param string $data
     *
     * @return bool
     */
    protected function _check_sign($data=''){
        $result = FALSE;
        $temp = array();

        if($data){
            $data = json_decode($data,TRUE);
            //返回的是成功状态才验证返回的签名
            if(isset($data['status']) && $data['status'] === '10000'){
                if(isset($data['sign']) && $data['sign'] != ''){
                    //base64 解密签名sign串
                    $temp['sign_str']=base64_decode($data['sign']);
                    //检查链接  得到具体MD5加密签名sign和appid timestamp
                    if(strpos( $temp['sign_str'],$this->_connector) !== FALSE){
                        $temp['sign_array']=explode($this->_connector, $temp['sign_str']);
                        $temp['return_sign']=$temp['sign_array'][0];

                        //解析获得appid和timestamp
                        if( ! empty($temp['sign_array'][1])){
                            $temp['app_str']=base64_decode($temp['sign_array'][1]);

                            if( ! empty($temp['app_str']) && strpos($temp['app_str'],':') !== FALSE){
                                $temp['app_array']=explode(':',$temp['app_str']);
                                $temp['return_appid']=$temp['app_array'][0];
                                $temp['return_timestamp']=$temp['app_array'][1];
                                //验证返回的appid和sign
                                $temp['this_sign'] = md5($this->_appid.$this->_appsecret.$temp['return_timestamp']);
                                if($temp['return_appid'] == $this->_appid && $temp['return_sign'] === $temp['this_sign']){
                                    $result = TRUE;
                                }
                            }
                        }
                    }
                }
            }else{
                $result = TRUE;
            }
        }else{
            $result = TRUE;
        }

        return $result;
    }
}