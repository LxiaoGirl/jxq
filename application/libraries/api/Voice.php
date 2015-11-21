<?php
/**
 * ���� ������
 * Class CI_Voice
 */
class CI_Voice {
	private $AccountSid   = 'aaf98f894ee35d30014ef2d4438f1085'; //���ʺ�id
	private $AccountToken = 'aeb654fe8acb41028addca0edfd5ef02'; //���ʺ�Token
	private $AppId        ='aaf98f894ee35d30014ef2d78650108f';  //Ӧ��Id
	private $SubAccountSid;
	private $SubAccountToken;
	private $VoIPAccount;
	private $VoIPPassword;
	private $ServerIP     ='app.cloopen.com';                   //�����ַ����ʽ���£�����Ҫдhttps://
	private $ServerPort   ='8883';                              //����˿�
	private $SoftVersion  ='2013-12-26';                        //REST�汾��
	private $Batch;                                             //ʱ���
	private $BodyType     = "xml";                              //�����ʽ������ֵ��json ��xml
	private $enabeLog     = true;                               //��־���ء�����ֵ��true��
	private $Filename     ="../log.txt";                        //��־�ļ�
	private $Handle;

	function __construct($param =array()){
		$this->Batch = date("YmdHis");
		if(isset($param['ServerIP']))$this->ServerIP = $param['ServerIP'];
		if(isset($param['ServerPort']))$this->ServerIP = $param['ServerPort'];
		if(isset($param['SoftVersion']))$this->ServerIP = $param['SoftVersion'];
		@$this->Handle = fopen($this->Filename, 'a');
	}

	/**
	 * �������ʺ�
	 *
	 * @param AccountSid ���ʺ�
	 * @param AccountToken ���ʺ�Token
	 */
	function setAccount($AccountSid,$AccountToken){
		$this->AccountSid = $AccountSid;
		$this->AccountToken = $AccountToken;
	}

	/**
	 * �������ʺ�
	 *
	 * @param SubAccountSid ���ʺ�
	 * @param SubAccountToken ���ʺ�Token
	 * @param VoIPAccount VoIP�ʺ�
	 * @param VoIPPassword VoIP����
	 */
	function setSubAccount($SubAccountSid,$SubAccountToken,$VoIPAccount,$VoIPPassword){
		$this->SubAccountSid = $SubAccountSid;
		$this->SubAccountToken = $SubAccountToken;
		$this->VoIPAccount = $VoIPAccount;
		$this->VoIPPassword = $VoIPPassword;
	}

	/**
	 * ����Ӧ��ID
	 *
	 * @param AppId Ӧ��ID
	 */
	function setAppId($AppId){
		$this->AppId = $AppId;
	}

	/**
	 * ��ӡ��־
	 *
	 * @param log ��־����
	 */
	function showlog($log){
		if($this->enabeLog){
			fwrite($this->Handle,$log."\n");
		}
	}

	/**
	 * ����HTTPS����
	 */
	function curl_post($url,$data,$header,$post=1)
	{
		//��ʼ��curl
		$ch = curl_init();
		//��������
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
		//����ʧ��
		if($result == FALSE){
			if($this->BodyType=='json'){
				$result = "{\"statusCode\":\"172001\",\"statusMsg\":\"�������\"}";
			} else {
				$result = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Response><statusCode>172001</statusCode><statusMsg>�������</statusMsg></Response>";
			}
		}

		curl_close($ch);
		return $result;
	}

	/**
	 * �������ʺ�
	 * @param friendlyName ���ʺ�����
	 */
	function createSubAccount($friendlyName)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
		if($this->BodyType=="json"){
			$body= "{'appId':'$this->AppId','friendlyName':'$friendlyName'}";
		}else{
			$body="<SubAccount>
                    <appId>$this->AppId</appId>
                    <friendlyName>$friendlyName</friendlyName>
                  </SubAccount>";
		}
		$this->showlog("request body = ".$body);
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SubAccounts?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʺ�Id + Ӣ��ð�� + ʱ���
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ��ȡ���ʺ�
	 * @param startNo ��ʼ����ţ�Ĭ�ϴ�0��ʼ
	 * @param offset һ�β�ѯ�������������С��1���������100��
	 */
	function getSubAccounts($startNo,$offset)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/GetSubAccounts?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ���ʺ���Ϣ��ѯ
	 * @param friendlyName ���ʺ�����
	 */
	function querySubAccount($friendlyName)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������

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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/QuerySubAccountByName?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ���Ͷ���
	 * @param to ���Ž��Տ��ֻ����뼯��,��Ӣ�Ķ��ŷֿ�
	 * @param body ��������
	 */
	function sendSMS($to,$smsBody)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/Messages?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʺ�Id + Ӣ��ð�� + ʱ���
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ����ģ�����
	 * @param to ���Ž��Տ��ֻ����뼯��,��Ӣ�Ķ��ŷֿ�
	 * @param datas ��������
	 * @param $tempId ģ��Id
	 */
	function sendTemplateSMS($to,$datas,$tempId)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		//����װ������
		if($datas->statusCode==0){
			if($this->BodyType=="json"){
				$datas->TemplateSMS =$datas->templateSMS;
				unset($datas->templateSMS);
			}
		}

		return $datas;
	}

	/**
	 * ˫��غ�
	 * @param from ���е绰����
	 * @param to ���е绰����
	 * @param customerSerNum ���в���ʾ�Ŀͷ�����
	 * @param fromSerNum ���в���ʾ�ĺ���
	 * @param promptTone �Զ���ز���ʾ��
	 * @param userData ������˽������
	 * @param maxCallTime ���ͨ��ʱ��
	 * @param hangupCdrUrl ʵʱ����֪ͨ��ַ
	 */
	function callBack($from,$to,$customerSerNum,$fromSerNum,$promptTone,$userData,$maxCallTime,$hangupCdrUrl)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->subAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->SubAccountSid . $this->SubAccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/SubAccounts/$this->SubAccountSid/Calls/Callback?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʺ�Id + Ӣ��ð�� + ʱ���
		$authen=base64_encode($this->SubAccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ���֪ͨ
	 * @param to ���к���
	 * @param mediaName �����ļ����ƣ���ʽ wav����mediaTxt����ͬʱΪ�ա�����Ϊ��ʱmediaTxt����ʧЧ��
	 * @param mediaTxt �ı�����
	 * @param displayNum ��ʾ�����к���
	 * @param playTimes ѭ�����Ŵ�����1��3�Σ�Ĭ�ϲ���1�Ρ�
	 * @param respUrl ���֪ͨ״̬֪ͨ�ص���ַ����ͨѶƽ̨�����Url��ַ���ͺ��н��֪ͨ��
	 */
	function landingCall($to,$mediaName,$mediaTxt,$displayNum,$playTimes,$respUrl)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/LandingCalls?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ������֤��
	 * @param verifyCode ��֤�����ݣ�Ϊ���ֺ�Ӣ����ĸ�������ִ�Сд������4-8λ
	 * @param playTimes ���Ŵ�����1��3��
	 * @param to ���պ���
	 * @param displayNum ��ʾ�����к���
	 * @param respUrl ������֤��״̬֪ͨ�ص���ַ����ͨѶƽ̨�����Url��ַ���ͺ��н��֪ͨ
	 */
	function voiceVerify($verifyCode,$playTimes,$to,$displayNum,$respUrl)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/Calls/VoiceVerify?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * IVR���
	 * @param number   �����к��룬ΪDial�ڵ������
	 * @param userdata �û����ݣ���<startservice>֪ͨ�з��أ�ֻ������д�����ַ���ΪDial�ڵ������
	 * @param record   �Ƿ�¼����������Ϊtrue��false��Ĭ��ֵΪfalse��¼����ΪDial�ڵ������
	 */
	function ivrDial($number,$userdata,$record)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
		$body=" <Request>
                  <Appid>$this->AppId</Appid>
                  <Dial number='$number'  userdata='$userdata' record='$record'></Dial>
                </Request>";
		$this->showlog("request body = ".$body);
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/ivr/dial?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/xml","Content-Type:application/xml;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		$datas = simplexml_load_string(trim($result," \t\n\r"));
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ��������
	 * @param date     day ����ǰһ������ݣ���00:00 �C 23:59��;week����ǰһ�ܵ�����(��һ ������)��month��ʾ��һ���µ����ݣ��ϸ��±�ʾ��ǰ�¼�1�����������4��10�ţ����ѯ�����3�·ݵ����ݣ�
	 * @param keywords   �ͻ��Ĳ�ѯ�������ɿͻ����ж��岢�ṩ����ͨѶƽ̨��Ĭ�ϲ�����Դ˲���
	 */
	function billRecords($date,$keywords)
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ƴ���������
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
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/BillRecords?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,$body,$header);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ���ʺ���Ϣ��ѯ
	 */
	function queryAccountInfo()
	{
		//���ʺż�Ȩ��Ϣ��֤���Ա�ѡ���������пա�
		$auth=$this->accAuth();
		if($auth!=""){
			return $auth;
		}
		// ��д��sig����
		$sig =  strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
		// ��������URL
		$url="https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/AccountInfo?sig=$sig";
		$this->showlog("request url = ".$url);
		// ������Ȩ�����ʻ�Id + Ӣ��ð�� + ʱ�����
		$authen = base64_encode($this->AccountSid . ":" . $this->Batch);
		// ���ɰ�ͷ
		$header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
		// ��������
		$result = $this->curl_post($url,"",$header,0);
		$this->showlog("response body = ".$result);
		if($this->BodyType=="json"){//JSON��ʽ
			$datas=json_decode($result);
		}else{ //xml��ʽ
			$datas = simplexml_load_string(trim($result," \t\n\r"));
		}
		//  if($datas == FALSE){
		//            $datas = new stdClass();
		//            $datas->statusCode = '172003';
		//            $datas->statusMsg = '���ذ������';
		//        }
		return $datas;
	}

	/**
	 * ���ʺż�Ȩ
	 */
	function subAuth()
	{
		if($this->ServerIP==""){
			$data = new stdClass();
			$data->statusCode = '172004';
			$data->statusMsg = 'IPΪ��';
			return $data;
		}
		if($this->ServerPort<=0){
			$data = new stdClass();
			$data->statusCode = '172005';
			$data->statusMsg = '�˿ڴ���С�ڵ���0��';
			return $data;
		}
		if($this->SoftVersion==""){
			$data = new stdClass();
			$data->statusCode = '172013';
			$data->statusMsg = '�汾��Ϊ��';
			return $data;
		}
		if($this->SubAccountSid==""){
			$data = new stdClass();
			$data->statusCode = '172008';
			$data->statusMsg = '���ʺ�Ϊ��';
			return $data;
		}
		if($this->SubAccountToken==""){
			$data = new stdClass();
			$data->statusCode = '172009';
			$data->statusMsg = '���ʺ�����Ϊ��';
			return $data;
		}
		if($this->AppId==""){
			$data = new stdClass();
			$data->statusCode = '172012';
			$data->statusMsg = 'Ӧ��IDΪ��';
			return $data;
		}
	}

	/**
	 * ���ʺż�Ȩ
	 */
	function accAuth()
	{
		if($this->ServerIP==""){
			$data = new stdClass();
			$data->statusCode = '172004';
			$data->statusMsg = 'IPΪ��';
			return $data;
		}
		if($this->ServerPort<=0){
			$data = new stdClass();
			$data->statusCode = '172005';
			$data->statusMsg = '�˿ڴ���С�ڵ���0��';
			return $data;
		}
		if($this->SoftVersion==""){
			$data = new stdClass();
			$data->statusCode = '172013';
			$data->statusMsg = '�汾��Ϊ��';
			return $data;
		}
		if($this->AccountSid==""){
			$data = new stdClass();
			$data->statusCode = '172006';
			$data->statusMsg = '���ʺ�Ϊ��';
			return $data;
		}
		if($this->AccountToken==""){
			$data = new stdClass();
			$data->statusCode = '172007';
			$data->statusMsg = '���ʺ�����Ϊ��';
			return $data;
		}
		if($this->AppId==""){
			$data = new stdClass();
			$data->statusCode = '172012';
			$data->statusMsg = 'Ӧ��IDΪ��';
			return $data;
		}
	}
}