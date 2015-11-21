<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 验证授权
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */


class REST {
	private $AccountSid = 'aaf98f894ee35d30014ef2d4438f1085';//主帐号id
	private $AccountToken = 'aeb654fe8acb41028addca0edfd5ef02';//主帐号Token
	private $AppId ='aaf98f894ee35d30014ef2d78650108f';//应用Id
	private $SubAccountSid;
	private $SubAccountToken;
	private $VoIPAccount;
	private $VoIPPassword;  
	private $ServerIP ='app.cloopen.com';//请求地址，格式如下，不需要写https://
	private $ServerPort ='8883';//请求端口
	private $SoftVersion ='2013-12-26';//REST版本号
	private $Batch;  //时间戳
	private $BodyType = "xml";//包体格式，可填值：json 、xml
	private $enabeLog = true; //日志开关。可填值：true、
	private $Filename="../log.txt"; //日志文件
	private $Handle; 
	function __construct($ServerIP='',$ServerPort='',$SoftVersion=''){
		$this->Batch = date("YmdHis");
		if($ServerIP)$this->ServerIP = $ServerIP;
        if($ServerPort)$this->ServerPort = $ServerPort;
        if($SoftVersion)$this->SoftVersion = $SoftVersion;
        $this->Handle = fopen($this->Filename, 'a');
	}

   /**
    * 设置主帐号
    * 
    * @param AccountSid 主帐号
    * @param AccountToken 主帐号Token
    */    
    function setAccount($AccountSid,$AccountToken){
      $this->AccountSid = $AccountSid;
      $this->AccountToken = $AccountToken;   
    }
    
   /**
    * 设置子帐号
    * 
    * @param SubAccountSid 子帐号
    * @param SubAccountToken 子帐号Token
    * @param VoIPAccount VoIP帐号
    * @param VoIPPassword VoIP密码
    */    
    function setSubAccount($SubAccountSid,$SubAccountToken,$VoIPAccount,$VoIPPassword){
      $this->SubAccountSid = $SubAccountSid;
      $this->SubAccountToken = $SubAccountToken;
      $this->VoIPAccount = $VoIPAccount;
      $this->VoIPPassword = $VoIPPassword;     
    }
    
   /**
    * 设置应用ID
    * 
    * @param AppId 应用ID
    */
    function setAppId($AppId){
       $this->AppId = $AppId; 
    }
    
   /**
    * 打印日志
    * 
    * @param log 日志内容
    */
    function showlog($log){
      if($this->enabeLog){
         fwrite($this->Handle,$log."\n");  
      }
    }
    
    /**
     * 发起HTTPS请求
     */
     function curl_post($url,$data,$header,$post=1)
     {
       //初始化curl
       $ch = curl_init();
       //参数设置  
       $res= curl_setopt ($ch, CURLOPT_URL,$url);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
       curl_setopt ($ch, CURLOPT_HEADER, 0);
       curl_setopt($ch, CURLOPT_POST, $post);
       if($post)
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
       $result = curl_exec ($ch);
       //连接失败
       if($result == FALSE){
          if($this->BodyType=='json'){
             $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
          } else {
             $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>网络错误</statusMsg></Response>"; 
          }    
       }

       curl_close($ch);
       return $result;
     } 

    /**
    * 创建子帐号
    * @param friendlyName 子帐号名称
    */
	  function createSubAccount($friendlyName)
	  {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','friendlyName':'$friendlyName'}";
        }else{
           $body="<SubAccount>
                    <appId>$this->AppId</appId>
                    <friendlyName>$friendlyName</friendlyName>
                  </SubAccount>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数  
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SubAccounts?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐号Id + 英文冒号 + 时间戳
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头 
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
	  }
        
    /**
    * 获取子帐号
    * @param startNo 开始的序号，默认从0开始
    * @param offset 一次查询的最大条数，最小是1条，最大是100条
    */
    function getSubAccounts($startNo,$offset)
    {   
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        $body="
            <SubAccount>
              <appId>$this->AppId</appId>
              <startNo>$startNo</startNo>  
              <offset>$offset</offset>
            </SubAccount>";
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','startNo':'$startNo','offset':'$offset'}";
        }else{
        	 $body="
            <SubAccount>
              <appId>$this->AppId</appId>
              <startNo>$startNo</startNo>  
              <offset>$offset</offset>
            </SubAccount>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数  
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/GetSubAccounts?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头 
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
    }
        
   /**
    * 子帐号信息查询
    * @param friendlyName 子帐号名称
    */
    function querySubAccount($friendlyName)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','friendlyName':'$friendlyName'}";
        }else{
        	 $body="
            <SubAccount>
              <appId>$this->AppId</appId>
              <friendlyName>$friendlyName</friendlyName>
            </SubAccount>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数  
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/QuerySubAccountByName?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头 
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas; 
    }

   /**
    * 发送短信
    * @param to 短信接收彿手机号码集合,用英文逗号分开
    * @param body 短信正文
    */
    function sendSMS($to,$smsBody)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体    
        if($this->BodyType=="json"){
           $body= "{'to':'$to','body':'$smsBody','appId':'$this->AppId'}";
        }else{
           $body="<SMSMessage>
                    <to>$to</to> 
                    <body>$smsBody</body>
                    <appId>$this->AppId</appId>
                  </SMSMessage>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数 
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL        
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/Messages?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐号Id + 英文冒号 + 时间戳
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        } 
        return $datas; 
    }
    
   /**
    * 发送模板短信
    * @param to 短信接收彿手机号码集合,用英文逗号分开
    * @param datas 内容数据
    * @param $tempId 模板Id
    */       
    function sendTemplateSMS($to,$datas,$tempId)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        if($this->BodyType=="json"){
           $data="";
           for($i=0;$i<count($datas);$i++){
              $data = $data. "'".$datas[$i]."',"; 
           }
           $body= "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[".$data."]}";
        }else{
           $data="";
           for($i=0;$i<count($datas);$i++){
              $data = $data. "<data>".$datas[$i]."</data>"; 
           }
           $body="<TemplateSMS>
                    <to>$to</to> 
                    <appId>$this->AppId</appId>
                    <templateId>$tempId</templateId>
                    <datas>".$data."</datas>
                  </TemplateSMS>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数 
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL        
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        //重新装填数据
        if($datas->statusCode==0){
         if($this->BodyType=="json"){
            $datas->TemplateSMS =$datas->templateSMS;
            unset($datas->templateSMS);   
          }
        }
 
        return $datas; 
    } 
  
    /**
    * 双向回呼
    * @param from 主叫电话号码
    * @param to 被叫电话号码
    * @param customerSerNum 被叫侧显示的客服号码  
    * @param fromSerNum 主叫侧显示的号码
	* @param promptTone 自定义回拨提示音 
	* @param userData 第三方私有数据  
	* @param maxCallTime 最大通话时长
	* @param hangupCdrUrl 实时话单通知地址    
    */
    function callBack($from,$to,$customerSerNum,$fromSerNum,$promptTone,$userData,$maxCallTime,$hangupCdrUrl)
	  {   
        //子帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->subAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体 
        if($this->BodyType=="json"){
           $body= "{'from':'$from','to':'$to','customerSerNum':'$customerSerNum','fromSerNum':'$fromSerNum','promptTone':'$promptTone','userData':'$userData','maxCallTime':'$maxCallTime','hangupCdrUrl':'$hangupCdrUrl'}";
        }else{
           $body= "<CallBack>
                     <from>$from</from>
                     <to>$to</to>
                     <customerSerNum>$customerSerNum</customerSerNum>
                     <fromSerNum>$fromSerNum</fromSerNum>
                     <promptTone>$promptTone</promptTone>
					 <userData>$userData</userData>
					 <maxCallTime>$maxCallTime</maxCallTime>
					 <hangupCdrUrl>$hangupCdrUrl</hangupCdrUrl>
                   </CallBack>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数  
        $sig =  strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));
        // 生成请求URL
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Calls/Callback?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：子帐号Id + 英文冒号 + 时间戳 
        $authen=base64_encode($this->SubAccountSid . ":" . $this->Batch);
        // 生成包头 
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
	}

    /**
    * 外呼通知
    * @param to 被叫号码
    * @param mediaName 语音文件名称，格式 wav。与mediaTxt不能同时为空。当不为空时mediaTxt属性失效。
    * @param mediaTxt 文本内容
    * @param displayNum 显示的主叫号码
    * @param playTimes 循环播放次数，1－3次，默认播放1次。
    * @param respUrl 外呼通知状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知。
    */
    function landingCall($to,$mediaName,$mediaTxt,$displayNum,$playTimes,$respUrl)
    {   
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        } 
        // 拼接请求包体
        if($this->BodyType=="json"){
           $body= "{'playTimes':'$playTimes','mediaTxt':'$mediaTxt','mediaName':'$mediaName','to':'$to','appId':'$this->AppId','displayNum':'$displayNum','respUrl':'$respUrl'}";
        }else{
           $body="<LandingCall>
                    <to>$to</to>
                    <mediaName>$mediaName</mediaName>
                    <mediaTxt>$mediaTxt</mediaTxt> 
                    <appId>$this->AppId</appId>
                    <displayNum>$displayNum</displayNum>
                    <playTimes>$playTimes</playTimes>
                    <respUrl>$respUrl</respUrl>
                  </LandingCall>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/LandingCalls?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
    }
    
    /**
    * 语音验证码
    * @param verifyCode 验证码内容，为数字和英文字母，不区分大小写，长度4-8位
    * @param playTimes 播放次数，1－3次
    * @param to 接收号码
    * @param displayNum 显示的主叫号码
    * @param respUrl 语音验证码状态通知回调地址，云通讯平台将向该Url地址发送呼叫结果通知
    */
    function voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','verifyCode':'$verifyCode','playTimes':'$playTimes','to':'$to','respUrl':'$respUrl','displayNum':'$displayNum'}";
        }else{
           $body="<VoiceVerify>
                    <appId>$this->AppId</appId>
                    <verifyCode>$verifyCode</verifyCode>
                    <playTimes>$playTimes</playTimes>
                    <to>$to</to>
                    <respUrl>$respUrl</respUrl>
                    <displayNum>$displayNum</displayNum>
                  </VoiceVerify>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/VoiceVerify?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
    }
      
   /**
    * IVR外呼
    * @param number   待呼叫号码，为Dial节点的属性
    * @param userdata 用户数据，在<startservice>通知中返回，只允许填写数字字符，为Dial节点的属性
    * @param record   是否录音，可填项为true和false，默认值为false不录音，为Dial节点的属性
    */
    function ivrDial($number,$userdata,$record)
    {
       //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        } 
       // 拼接请求包体
        $body=" <Request>
                  <Appid>$this->AppId</Appid>
                  <Dial number='$number'  userdata='$userdata' record='$record'></Dial>
                </Request>";
        $this->showlog("request body = ".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/ivr/dial?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/xml","Content-Type:application/xml;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        $datas = simplexml_load_string(trim($result," \t\n\r"));
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;
    }
    
   /**
    * 话单下载
    * @param date     day 代表前一天的数据（从00:00 – 23:59）;week代表前一周的数据(周一 到周日)；month表示上一个月的数据（上个月表示当前月减1，如果今天是4月10号，则查询结果是3月份的数据）
    * @param keywords   客户的查询条件，由客户自行定义并提供给云通讯平台。默认不填忽略此参数
    */
    function billRecords($date,$keywords)
    {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 拼接请求包体
        if($this->BodyType=="json"){
           $body= "{'appId':'$this->AppId','date':'$date'}";
        }else{
           $body="<BillRecords>
                    <appId>$this->AppId</appId>
                    <date>$date</date>
                    <keywords>$keywords</keywords>
                  </BillRecords>";
        }
        $this->showlog("request body = ".$body);
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/BillRecords?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,$body,$header);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas; 
   } 
   
  /**
    * 主帐号信息查询
    */
   function queryAccountInfo()
   {
        //主帐号鉴权信息验证，对必选参数进行判空。
        $auth=$this->accAuth();
        if($auth!=""){
            return $auth;
        }
        // 大写的sig参数
        $sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL  
        $url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/AccountInfo?sig=$sig";
        $this->showlog("request url = ".$url);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url,"",$header,0);
        $this->showlog("response body = ".$result);
        if($this->BodyType=="json"){//JSON格式
           $datas=json_decode($result); 
        }else{ //xml格式
           $datas = simplexml_load_string(trim($result," \t\n\r"));
        }
      //  if($datas == FALSE){
//            $datas = new stdClass();
//            $datas->statusCode = '172003';
//            $datas->statusMsg = '返回包体错误'; 
//        }
        return $datas;  
   }

  /**
    * 子帐号鉴权
    */   
   function subAuth()
   {
       if($this->ServerIP==""){
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
          return $data;
        }
        if($this->ServerPort<=0){
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
          return $data;
        }
        if($this->SoftVersion==""){
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
          return $data;
        } 
        if($this->SubAccountSid==""){
            $data = new stdClass();
            $data->statusCode = '172008';
            $data->statusMsg = '子帐号为空';
          return $data;
        }
        if($this->SubAccountToken==""){
            $data = new stdClass();
            $data->statusCode = '172009';
            $data->statusMsg = '子帐号令牌为空';
          return $data;
        }
        if($this->AppId==""){
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
          return $data;
        }  
   }
   
  /**
    * 主帐号鉴权
    */   
   function accAuth()
   {
       if($this->ServerIP==""){
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
          return $data;
        }
        if($this->ServerPort<=0){
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
          return $data;
        }
        if($this->SoftVersion==""){
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
          return $data;
        } 
        if($this->AccountSid==""){
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg = '主帐号为空';
          return $data;
        }
        if($this->AccountToken==""){
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg = '主帐号令牌为空';
          return $data;
        }
        if($this->AppId==""){
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
          return $data;
        }   
   }
}
class Send_model extends CI_Model{
    const authcode = 'authcode'; // 验证授权

    const IP_LIMIT_TOTAL      = 3;//ip地址 一定时间内限制短信语音次数
    const TARGET_LIMIT_TOTAL = 3;//ip地址下目标（手机等） 一定时间内限制短信语音次数
    const SPACE_TIME          = 3;//多少分钟内的短信数量
    const VOICE_TEL           = '4008382182';//语音 显示的电话号码

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct(){
        parent::__construct();
        $this->config->load('sms');//加载亿美短信通道配置
    }

    /**
     * 发送语音
     * @param string $mobile 手机号码
     * @param string $content 发送内容
     * @param int $type 发送类型
     * @param int $uid 用户id
     * @return array 返回
     */
	public function voice($mobile = '', $content = '', $type = 1, $uid = 0){
        $data = array('code' => 1, 'msg' => '服务器繁忙,请稍后重试或联系客服处理！');
        $temp = array();
        //验证手机号码
        if( ! $this->_is_mobile($mobile)){
            return  array('code' => 2, 'msg' => '手机号码为空或格式不正确！');
        }
        //验证发送内容
        if($content == ''){
            return  array('code' => 2, 'msg' => '发送内容不能为空！');
        }
        //验证ip target 次数
        $temp['total_check'] = $this->_check_total($mobile);
        if($temp['total_check']['code'] == 2){
            return $temp['total_check'];
        }
        //验证 特殊type的次数
        if($type == 6){
            $temp['type_check'] =$this->check_type_total(6);
            if($temp['type_check']['code'] > 0){
                return $temp['type_chekc'];
            }
        }
        //生成 短信内容 执行
        $temp['code']    = $this->_get_random($mobile, 6);
        $temp['content'] = sprintf($content, $temp['code']);
        $query = $this->voiceVerify($temp['code'],"3",$mobile,self::VOICE_TEL,"");

        if( ! empty($query)){
            $data = array('code' => 0, 'msg' => '短信已经发送成功！');
            $this->_add_send_log($mobile, $temp['code'], $temp['content'], $type, $uid);
        }

        unset($temp);
        return $data;
    }

    /**
     * 发送语音 处理
     * @param $verifyCode string 验证码
     * @param $playTimes int 读语音次数
     * @param $to string 目标
     * @param $displayNum string 显示号码
     * @param $respUrl
     * @return bool
     */
	public function voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl){
		// 初始化REST SDK
        $rest = new REST();
        //调用语音验证码接口
        $result = $rest->voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl);

        if($result == NULL ) return false;

        if($result->statusCode != 0) {
            // echo "error code :" . $result->statusCode . "<br>";
            // echo "error msg :" . $result->statusMsg . "<br>";
            //添加错误处理逻辑
            return false;
        }else{
            return true;
		}
	}

    /**
     * 根据类型 获取发送短信的没人模板
     * @param int $type
     * @return string
     */
    public function get_sms_text($type = 1){
        $str = '';
        switch ($type){
            case '2': // 忘记密码
                $str = '【聚雪球】验证码为:%s,为您本次找回密码验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '3': // 交易密码
                $str = '【聚雪球】验证码:%s,为您设置资金密码验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '4': // 会员提现
                $str = '【聚雪球】验证码:%s,为您申请提现验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;  
			case '5': // 修改密码
                $str = '【聚雪球】验证码:%s,您的账户正在修改密码。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
			case '6': // 堆雪人球
                $str = '【聚雪球】验证码:%s,您在参与堆雪人球活动。验证码5分钟后失效。';
                break;
			case '7': // 居间人邀请
              $str = '【聚雪球】注册验证码:%s,为您申请居间人使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '8': // 修改手机
                $str = '【聚雪球】验证码:%s,您的账户正在绑定新手机。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '9': // 修改手机
                $str = '【聚雪球】验证码:%s,您的账户正在解绑手机。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '10': // 申请借款
                $str = '【聚雪球】验证码:%s,您的账户正在申请借款。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '11': // 申请借款
                $str = '【聚雪球】验证码:%s,您的账户正在绑定银行卡。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            case '12': // 申请借款
                $str = '【聚雪球】验证码:%s,您的账户正在解绑银行卡。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
            default: // 新用户注册
                $str = '【聚雪球】注册验证码:%s,为您注册用户验证使用。验证码5分钟后失效。如需帮助请拨免费客服电话4007-918-333。';
                break;
        }

        return $str;
    }

    /**
     * 链接方式
     * 5.15
     * @access public
     * @return float
     */
	public function file_get_contents_post($url, $post) {  
		$options = array(  
			'http' => array(  
				'method' => 'POST',  
				// 'content' => 'name=caiknife&email=caiknife@gmail.com',  
				'content' => http_build_query($post),  
			),  
		);  
		$result = file_get_contents($url, false, stream_context_create($options));  
		return $result;  
	}

	 /**
     * 获取短信余额
     *
     * @access public
     * @return float
     */
    public function get_sms_balance(){
		$data = $this->file_get_contents_post($this->config->item('wsdl_url'), array('username'=>$this->config->item('username'), 'password'=>$this->config->item('password'), 'action'=>'surplus'));
		return $data;
    }

    /**
     * 发送验证邮件
     *
     * @access public
     * @param  string  $email 邮件地址
     * @return boolean
     */
	public function send_email($email = ''){
        $query = FALSE;
        $temp  = array();

        $temp['code']    = $this->_get_random($email, 6, FALSE);
        $temp['email']   = $email;
        $temp['content'] = $this->load->view('email/validation', $temp, TRUE);

        $this->config->load('email'); //wsb-2015.5.12 eamail配置
        $temp['send']  = array(
                                'from'    => $this->config->item('smtp_user'),//wsb-2015.5.12 修改 eamail配置
                                'name'    => '网加金服客服',
                                'to'      => $temp['email'],
                                'subject' => '欢迎你注册网加金服',
                                'message' => $temp['content']
                            );

        $query = $this->c->send_mail($temp['send']);
        if( ! empty($query)){
            $this->_add_send_log($temp['email'], $temp['code'], $temp['content'], 5);
        }

        unset($temp);
        return $query;
    }

    /**
     * 发送手机短信
     *
     * @access public
     * @param  string  $mobile  手机号码
     * @param  string  $content 短信内容
     * @param  integer $type    记录类型
     * @param  integer $uid     用户ID
     * @return boolean
     */
    public function send_sms($mobile = '', $content = '', $type = 1, $uid = 0){
        $data = array('code' => 1, 'msg' => '服务器繁忙,请稍后重试或联系客服处理！');
        $temp = array();
        //验证手机号码
        if( ! $this->_is_mobile($mobile)){
            return  array('code' => 2, 'msg' => '手机号码为空或格式不正确！');
        }
        //验证发送内容
        if($content == ''){
            return  array('code' => 2, 'msg' => '发送内容不能为空！');
        }
        //验证ip target 次数
        $temp['total_check'] = $this->_check_total($mobile);
        if($temp['total_check']['code'] == 2){
            return $temp['total_check'];
        }
        //验证 特殊type的次数
        if($type == 6){
            $temp['type_check'] =$this->check_type_total(6);
            if($temp['type_check']['code'] > 0){
                return $temp['type_chekc'];
            }
        }
        //生成 短信内容 执行
        $temp['code']    = $this->_get_random($mobile, 6);
        $temp['content'] = sprintf($content, $temp['code']);
        $query = $this->_send_sms($mobile, $temp['code'], $temp['content']);

        if( ! empty($query)){
            $data = array('code' => 0, 'msg' => '短信已经发送成功！');
            $this->_add_send_log($mobile, $temp['code'], $temp['content'], $type, $uid);
        }

        unset($temp);
        return $data;
    }

    /**
     * 检查验证码是否有效
     *
     * @access public
     * @param  string  $target  目标地址
     * @param  string  $code    验证码
     * @param  integer $minute  分钟
     * @return integer
     */
    public function validation_email($target = '', $code = '', $minute = 0){
        $uid  = 0;
        $temp = array();

        if( ! empty($target) && ! empty($code)){
            $temp['where'] = array(
                'select' => 'uid',
                'where'  => array(
                    'code'         => $code,
                    'send_time >=' => $this->_timestamp($minute),
                    'target'       => $target,
                    'type'         => 5
                )
            );
            $uid = $this->c->get_one(self::authcode, $temp['where']);
        }

        unset($temp);
        return $uid;
    }

    /**
     * 检查验证码是否有效
     * @access public
     * @param  string  $target  目标地址
     * @param  string  $code    验证码
     * @param  integer $type    记录类型
     * @param  integer $minute  分钟
     * @param bool|TRUE $uid_exists 是否存在uid（存在就查uid 不存在就差count数量）
     * @return bool
     */
    public function validation($target = '', $code = '', $type = 1, $minute = 0 , $uid_exists=TRUE){
        $query = FALSE;
        $temp  = array();

        if( ! empty($target) && ! empty($code)){
            $temp['where'] = array(
                'select' => 'uid',
                'where'  => array(
                    'code'         => $code,
                    'send_time >=' => $this->_timestamp($minute),
                    'target'       => $target,
                    'type'         => $type
                )
            );
            if(in_array($type, array(1 ,2,6))){
                $temp['count'] = $this->c->count(self::authcode, $temp['where']);
            }else{
                if($uid_exists){
                    $temp['count'] = $this->c->get_one(self::authcode, $temp['where']);
                }else{
                    $temp['count'] = $this->c->count(self::authcode, $temp['where']);
                }
            }
            if( ! empty($temp['count'])){
                $query = TRUE;
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 添加手机发送日志
     *
     * @access private
     * @param  string  $target  目标地址
     * @param  string  $code    授权码
     * @param  string  $content 发送内容
     * @param  integer $type    记录类型
     * @param  integer $uid     会员ID
     * @return boolean
     */
    private function _add_send_log($target = '', $code = '', $content = '', $type = 1, $uid = 0){
        $query = FALSE;
        $logs  = array();

        if( ! empty($target) && ! empty($code) && ! empty($content)){
            $logs = array(
                'code'        => $code,
                'ip_address' => $this->input->ip_address(),
                'send_time'  => time(),
                'uid'         => ( ! empty($uid)) ? (int)$uid : (int)$this->session->userdata('uid'),
                'type'        => $type,
                'target'      => $target,
                'content'     => $content
            );
            $query = $this->c->insert(self::authcode, $logs);
        }

        unset($logs);
        return $query;
    }

    /**
     * 获取授权码
     *
     * @access private
     * @param  string   $target  目标地址
     * @param  integer  $length  授权码位数
     * @param  boolean  $flag    纯数字
     * @return string
     */
    private function _get_random($target = '', $length = 6, $flag = TRUE){
        $code = '';
        $temp = array();

        if( ! empty($length)){
            $code = random($length, $flag);

            $temp['where'] = array(
                'where' => array(
                    'code'         => $code,
                    'send_time >=' => $this->_timestamp(),
                    'target'       => $target
                )
            );

            $temp['count'] = $this->c->count(self::authcode, $temp['where']);

            if($temp['count'] > 0){
                $code = $this->_get_random($target, $length, $flag);
            }
        }

        unset($temp);
        return $code;
    }

    /**
     * 获取当前ip地址 当前目标一定时间内发送信息的数量
     * @access private
     * @param  string  $target 目标地址
     * @return integer
     */

    private function _get_send_num($target = ''){
        $total = 0;
        $temp  = array();

        if( ! empty($target)){
            $temp['where'] = array(
                'where' => array(
                    'ip_address'   => $this->input->ip_address(),
                    'send_time >=' => $this->_timestamp(self::SPACE_TIME),
                    'target'       => $target
                )
            );
            $total = $this->c->count(self::authcode, $temp['where']);
        }

        unset($temp);
        return $total;
    }

	/**
     * 获取IP发送数量
     *
     * @access private
     * @param  string  $target 目标地址
     * @return integer
     */

    private function _get_send_ip_num($target = ''){
        $total = 0;
        $temp  = array();

        if( ! empty($target)){
            $temp['where'] = array(
                'where' => array(
                    'ip_address'   => $this->input->ip_address(),
                    'send_time >=' => $this->_timestamp(self::SPACE_TIME),
                )
            );
            $total = $this->c->count(self::authcode, $temp['where']);
        }

        unset($temp);
        return $total;
    }

    /**
     * 发送手机短信
     *
     * @access private
     * @param  string  $mobile  手机号码
     * @param  string  $code    授权码
     * @param  string  $content 发送内容
     * @return boolean
     */

    public function _send_sms($mobile = '', $code = '', $content = ''){

        if( ! empty($mobile) && ! empty($code) && ! empty($content))
        {
			if (!isset($content{70})) 
			{
				$data = $this->file_get_contents_post($this->config->item('wsdl_url'), array('username'=>$this->config->item('username'), 'password'=>$this->config->item('password'), 'action'=>'send', 'receive_number'=>$mobile, 'message_content'=>$content));  
			}else{
			    $data = $this->file_get_contents_post($this->config->item('wsdl_url'), array('username'=>$this->config->item('username'), 'password'=>$this->config->item('password'), 'action'=>'send', 'receive_number'=>$mobile, 'message_content'=>$content, 'split_type'=>'1'));
			}
			//$data = $this->file_get_contents_post($this->config->item('wsdl_url'), array('username'=>$this->config->item('username'), 'password'=>$this->config->item('password'), 'action'=>'send', 'receive_number'=>$mobile, 'message_content'=>$content, 'split_type'=>'1'));  
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
        return TRUE;
    }

    /**
     * 获取当前时间倒回的分钟数的时间戳 不传值默认60分计算
     * @access private
     * @param  integer  $minute 分钟
     * @return integer
     */
    private function _timestamp($minute = 60){
        return time() - ($minute * 60);
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

    /**
     * 验证ip下 一定时间内 目标和ip发送信息次数
     * @param string $target 目标
     * @param int $ip_total ip限制次数 默认取常量 IP_LIMIT_TOTAL
     * @param int $target_total 目标限制次数 默认取常量 TARGET_LIMIT_TOTAL
     * @return array
     */
    private function _check_total($target='',$ip_total=0,$target_total=0){
        $data = array('code'=>0,'msg'=>'');
        $temp = array();

        if($ip_total == 0)$ip_total = self::IP_LIMIT_TOTAL;
        if($target_total == 0)$target_total = self::TARGET_LIMIT_TOTAL;

        //验证 当前目标（3分钟内）已发送数量
        $temp['total'] = $this->_get_send_num($target);
        if($temp['total'] < $target_total){
            //验证（3分钟内）当前ip地址发送数量
            $temp['ip_total'] = $this->_get_send_ip_num($target);
            if($temp['ip_total'] >= $ip_total){
                $data = array('code' => 2, 'msg' => '当前IP发送频率过于频繁，请稍侯再试！');
            }
        }else{
            $data = array('code' => 2, 'msg' => '发送频率过于频繁，请稍侯再试！');
        }

        unset($temp);
        return $data;
    }

    /**
     * 验证特定type的发送次数限制
     * @param int $type 类型
     * @param int $total 限制数量
     * @param int $time 限制时间
     * @return array
     */
    public function check_type_total($type = 0,$total=5,$time=300){
        $data = array('code' => 0, 'msg' => '');
        $temp = array();

        if($type > 0){
            $temp['where'] = array(
                'where'  => array(
                    'ip_address' => $this->input->ip_address(),
                    'send_time >=' => time()-$time,
                    'send_time <=' => time(),
                    'type'         => $type
                )
            );
            $temp['count'] = $this->c->count(self::authcode, $temp['where']);
            if($temp['count'] >= $total){
                $data = array('code' => 2, 'msg' => '当前IP发送频率过于频繁，请稍侯再试！');
            }
        }

        unset($temp);
        return $data;
    }
}