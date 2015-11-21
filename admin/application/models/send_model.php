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
	private $AccountSid;
	private $AccountToken;
	private $AppId;
	private $SubAccountSid;
	private $SubAccountToken;
	private $VoIPAccount;
	private $VoIPPassword;  
	private $ServerIP;
	private $ServerPort;
	private $SoftVersion;
	private $Batch;  //时间戳
	private $BodyType = "xml";//包体格式，可填值：json 、xml
	private $enabeLog = true; //日志开关。可填值：true、
	private $Filename="../log.txt"; //日志文件
	private $Handle; 
	function __construct($ServerIP,$ServerPort,$SoftVersion)	
	{
		$this->Batch = date("YmdHis");
		$this->ServerIP = $ServerIP;
		$this->ServerPort = $ServerPort;
		$this->SoftVersion = $SoftVersion;
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
class Send_model extends CI_Model
{
    const authcode = 'authcode'; // 验证授权

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->config->load('sms');//加载亿美短信通道配置
    }

    /**
     * 获取短信内容
     *
     * @access public
     * @param  integer  $type 记录类型
     * @return boolean
     */
	public function voice($mobile = '', $content = '', $type = 1, $uid = 0)
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '发送内容不能为空！');

        if( ! empty($mobile) && ! empty($content))
        {
            $temp['total'] = $this->_get_send_num($mobile);

            if($temp['total'] < 3)
            {
                $data['msg']     = '服务器繁忙,请联系客服处理！';

                $temp['code']    = $this->_get_random($mobile, 6);
                $temp['content'] = sprintf($content, $temp['code']);

                $query = $this->voiceVerify($temp['code'],"3",$mobile,"4008228090","");

                if( ! empty($query))
                {
                    $data = array('code' => 0, 'msg' => '短信已经发送成功！');
                    $this->_add_send_log($mobile, $temp['code'], $temp['content'], $type, $uid);
                }
            }
            else
            {
                $data = array('code' => 2, 'msg' => '发送频率过于频繁，请稍侯再试！');
            }
        }

        unset($temp);
        return $data;
    }
	public function voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl)
	{
		$accountSid= 'aaf98f894c2578f8014c26a5f0550164';
		//主帐号Token
		$accountToken= 'dfd480dc65e143a7b084d6d31f197f6c';
		//应用Id
		$appId='8a48b5514c9d9c05014cb593381a0ff8';
		//请求地址，格式如下，不需要写https://
		$serverIP='app.cloopen.com';
		//请求端口 
		$serverPort='8883';
		//REST版本号
		$softVersion='2013-12-26';
		// 初始化REST SDK
        $rest = new REST($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);

        //调用语音验证码接口
        echo "Try to make a voiceverify,called is $to <br/>";
        $result = $rest->voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl);

         if($result == NULL ) {
            echo "result error!";
            break;
        }
        
        if($result->statusCode!=0) {
            echo "error code :" . $result->statusCode . "<br>";
            echo "error msg :" . $result->statusMsg . "<br>";
            //TODO 添加错误处理逻辑
        } else{
        return true;
		}
	}
 
    public function get_sms_text($type = 1)
    {
        $str = '';

        switch ($type)
        {
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
     * 发送节日短信
     *
     * @access public
     * @return float
     */

    public function send_sms_jieri($mobile,$content)
    {
	
		$data = $this->file_get_contents_post($this->config->item('wsdl_url'), array('username'=>$this->config->item('username'), 'password'=>$this->config->item('password'), 'action'=>'send', 'receive_number'=>$mobile, 'message_content'=>$content, 'split_type'=>'1'));
		

		return $data;  

    }
	 /**
     * 获取短信余额
     *
     * @access public
     * @return float
     */

    public function get_sms_balance()
    {
	
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

	public function send_email($email = '')
    {
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

        if( ! empty($query))
        {
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

    public function send_sms($mobile = '', $content = '', $type = 1, $uid = 0)
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '发送内容不能为空！');

        if( ! empty($mobile) && ! empty($content))
        {
            $temp['total'] = $this->_get_send_num($mobile);

            if($temp['total'] < 3)
            {
                $data['msg']     = '服务器繁忙,请联系客服处理！';

                $temp['code']    = $this->_get_random($mobile, 6);
                $temp['content'] = sprintf($content, $temp['code']);

                $query = $this->_send_sms($mobile, $temp['code'], $temp['content']);

                if( ! empty($query))
                {
                    $data = array('code' => 0, 'msg' => '短信已经发送成功！');
                    $this->_add_send_log($mobile, $temp['code'], $temp['content'], $type, $uid);
                }
            }
            else
            {
                $data = array('code' => 2, 'msg' => '发送频率过于频繁，请稍侯再试！');
            }
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

    public function validation_email($target = '', $code = '', $minute = 0)
    {
        $uid  = 0;
        $temp = array();

        if( ! empty($target) && ! empty($code))
        {
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
     *
     * @access public
     * @param  string  $target  目标地址
     * @param  string  $code    验证码
     * @param  integer $type    记录类型
     * @param  integer $minute  分钟
     * @return integer
     */

    public function validation($target = '', $code = '', $type = 1, $minute = 0)
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($target) && ! empty($code))
        {
            $temp['where'] = array(
                                'select' => 'uid',
                                'where'  => array(
                                                'code'         => $code,
                                                'send_time >=' => $this->_timestamp($minute),
                                                'target'       => $target,
                                                'type'         => $type
                                            )
                            );

            if(in_array($type, array(1 ,2)))
            {
                $temp['count'] = $this->c->count(self::authcode, $temp['where']);
            }
            else
            {
                $temp['count'] = $this->c->get_one(self::authcode, $temp['where']);
            }

            if( ! empty($temp['count']))
            {
                $query = TRUE;
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 添加手机日志
     *
     * @access private
     * @param  string  $target  目标地址
     * @param  string  $code    授权码
     * @param  string  $content 发送内容
     * @param  integer $type    记录类型
     * @param  integer $uid     会员ID
     * @return boolean
     */

    private function _add_send_log($target = '', $code = '', $content = '', $type = 1, $uid = 0)
    {
        $query = FALSE;
        $logs  = array();

        if( ! empty($target) && ! empty($code) && ! empty($content))
        {
            $logs = array(
                        'code'       => $code,
                        'ip_address' => $this->input->ip_address(),
                        'send_time'  => time(),
                        'uid'        => ( ! empty($uid)) ? (int)$uid : (int)$this->session->userdata('uid'),
                        'type'       => $type,
                        'target'     => $target,
                        'content'    => $content
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

    private function _get_random($target = '', $length = 6, $flag = TRUE)
    {
        $code = '';
        $temp = array();

        if( ! empty($length))
        {
            $code = random($length, $flag);

            $temp['where'] = array(
                                'where' => array(
                                                'code'         => $code,
                                                'send_time >=' => $this->_timestamp(),
                                                'target'       => $target
                                            )
                            );

            $temp['count'] = $this->c->count(self::authcode, $temp['where']);

            if($temp['count'] > 0)
            {
                $this->_get_random($target, $length, $flag);
            }
        }

        unset($temp);
        return $code;
    }

    /**
     * 获取发送数量
     *
     * @access private
     * @param  string  $target 目标地址
     * @return integer
     */

    private function _get_send_num($target = '')
    {
        $total = 0;
        $temp  = array();

        if( ! empty($target))
        {
            $temp['where'] = array(
                                'where' => array(
                                                'ip_address'   => $this->input->ip_address(),
                                                'send_time >=' => $this->_timestamp(3),
                                                'target'       => $target
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

    public function _send_sms($mobile = '', $code = '', $content = '')
    {

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

//            $temp['args'] = array(
//                                'userCode' => 'yitou',
//                                'userPass' => 'yitou123',
//                                'DesNo'    => $mobile,
//                                'Msg'      => $content,
//                                'Channel'  => ''
//                            );
            // $temp['args'] = array(
                // 'arg0'=>$this->config->item('serial_number'),
                // 'arg1'=>$this->config->item('session_key'),
                // 'arg2'=>'',
                // 'arg3'=>$mobile,
                // 'arg4'=>$content,
                // 'arg5'=>'',
                // 'arg6'=>'UTF8',
                // 'arg7'=>5,
                // 'arg8'=>8888
            // );

           // $temp['soap'] = new SoapClient($this->config->item('wsdl_url'));//原：'http://121.199.48.186:1210/Services/MsgSend.asmx?WSDL'
           // $temp['data'] = $temp['soap']->__soapCall('sendSMS',array('parameters' => $temp['args']));//原：SendMsg


            // if( ! empty($temp['data']))
            // {
                // $query = ($temp['data']->return == 0 && $temp['data']->return != NULL) ? TRUE : FALSE;// 原：  $query = ($temp['data']->SendMsgResult > 0) ? TRUE : FALSE;
            // }

            /***2015.5.5 修改****/
        // }

        unset($temp);
        return TRUE;
    }

    /**
     * 获取时间戳
     *
     * @access private
     * @param  integer  $minute 分钟
     * @return integer
     */

    private function _timestamp($minute = 0)
    {
        $second = ( ! empty($minute)) ? $minute * 60 : 3600;
        return time() - $second;
    }
}