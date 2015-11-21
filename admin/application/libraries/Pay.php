<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *三方平台
 */

class CI_Pay {

  	
 
    /**
     * 传输方法
     *
     * @access public
     * @return array
     */
	public function vpost($data){
    $url='http://pasy.catainfo.com:9446/paysnet/PayInterfaceI';// 提交网关地址
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    // if (curl_errno($curl)) {
    // echo 'Errno'.curl_error($curl);//捕抓异常
    // }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
	}
	
    /**
     * 格式化传输信息
     *
     * @access public
     * @return array
     */
	public function vpostdata($xmldata,$MktCode,$type){
		$key ="6ea42a67a6703bf1b7d169fe2aea37c4";
		$MktCode="10000006";
		$xmldata = str_replace("\n", '', $xmldata);
		$message = base64_encode($xmldata);
		$signature = md5 ( base64_encode($xmldata).$MktCode.$key );
        $data='mktcode='.urlencode($MktCode).'&tradecode='.urlencode($type).'&message='.urlencode($message).'&signature='.urlencode($signature);
		return $data;
	}
	/**
     * 格式化返回参数
     * @param $result  传输返回的加密数据
     * @return array   二维数组
     */
	public function geshihua($result){
				$result = base64_decode($result);
				$ob= simplexml_load_string($result);
				$json  = json_encode($ob);
				$configData = json_decode($json, true);		
				return $configData;
	}
	/**
     * 账户查询
     * @param $MarketSerial  varchar 三方那边生成的MarketSerial
     * @param $FirmId    varchar 三方那边生成的FirmId
     * @param $CustName  varchar 用户的真实名字
     * @return array   二维数组
     */
			public function zanghuchaxun($MarketSerial,$FirmId,$CustName){
				$MktCode="10000006";
				$type ="41011";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><FundAcc><FirmId>".$FirmId."</FirmId><CustName>".$CustName."</CustName></FundAcc></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
				}
	/**
     * 转账
     * @param $MarketSerial  varchar 付款编号P
     * @param $PVaccId    varchar 付款人VaccId
     * @param $CustName  varchar 付款人真实名字    
	 
     * @param $RVaccId    varchar 收款人VaccId
     * @param $RCustName  varchar 收款人真实名字
	 
     * @param $TransferAmount  varchar 付款金额	 
     * @param $TransferCharge  varchar 手续费	 
     * @return array   二维数组
     */
	public function zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmount,$TransferCharge="0"){		
				$MktCode="10000006";
				$type ="41005";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><PFundAcc><VaccId>".$PVaccId."</VaccId><CustName>".$PCustName."</CustName></PFundAcc><RFundAcc><VaccId>".$RVaccId."</VaccId><CustName>".$RCustName."</CustName><FundPwd>4QrcOUm6Wau+VuBX8g+IPg==</FundPwd></RFundAcc><FlagInfo><Flag1>01</Flag1><Flag2>01</Flag2><Flag3>01</Flag3></FlagInfo><Transfer><TransferAmount>".$TransferAmount."</TransferAmount><TransferCharge>".$TransferCharge."</TransferCharge></Transfer></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return $configData;
				}
	/**
     * 信息变更，修改账户资料用
	 * 理论上，涉及不到修改账户资料，开户后所有资料锁死不可修改，认证未通过，再次提交记为修改
     * @param $MarketSerial  varchar 流水号
     * @param $FirmId    varchar 开户时生成的ID码FirmId
     * @param $VaccId  varchar 开户时生成的VaccId 
     * @param $CustName    varchar 用户真实姓名
	 
     * @param $ClientName  varchar 法人姓名，程序中默认与用户真实姓名为一个
	 
     * @param $ClientKind  varchar 个人还是企业，0企业，1个人	 
     * @param $CertType  varchar 证件类型，A为身份证	 
     * @param $CertID  varchar 证件号码，默认为身份证号码	 
     * @param $CertDate  varchar 证件有效期，默认为身份证有效期，实际无填写 
     * @param $Gender  varchar 性别，默认不填为1，用户有修改可以携带	 
     * @param $CorType  varchar 企业证件种类	 
     * @param $CorID  varchar 企业证件号码	 
     * @param $CorDate  varchar 企业证件有效期	 
     * @param $Email  varchar 手续费	 
     * @return array   二维数组
     */
	public function xxbg($MarketSerial,$FirmId,$VaccId,$CustName,$ClientName,$ClientKind,$CertType,$CertID,$CertDate,$Gender,$CorType,$CorID,$CorDate,$Email){		
				$MktCode="10000006";
				$type ="41002";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><FundAcc><FirmId>".$FirmId."</FirmId><VaccId>".$VaccId."</VaccId><CustName>".$CustName."</CustName></FundAcc><Client><ClientName>".$ClientName."</ClientName><ClientKind>".$ClientKind."</ClientKind><CertType>".$CertType."</CertType><CertID>".$CertID."</CertID><CertDate>".$CertDate."</CertDate><Gender>".$Gender."</Gender><Nationality>CHN</Nationality><TelNo>0371-88888888</TelNo><FaxNo>0371-88888888</FaxNo><MobiNo>13888888888</MobiNo><PostCode>476400</PostCode><Address>河南郑州</Address><CorType>".$CorType."</CorType><CorID>".$CorID."</CorID><CorDate>".$CorDate."</CorDate><Email>".$Email."</Email></Client></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return $configData;
				}
	/**
     * 投资冻结，提交给三方进行投资冻结，但是不能即时冻结，此功能暂时停用
	 * 
	 * @param $MarketSerial  varchar 流水号
     * @param $FirmId    varchar 开户时生成的ID码FirmId
     * @param $VaccId  varchar 开户时生成的VaccId 
     * @param $CustName    varchar 用户真实姓名
	 
     * @param $borrow_no  varchar 投资的项目编号	 
     * @param $borrow_no  varchar 投资金额
	 
     * @return array   二维数组
     */
	public function touzidongjie($FirmId, $CustName,$VaccId,$MarketSerial,$borrow_no,$amount){
				$MktCode="10000006";
				$type ="35022";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay/><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><Serial><MarketSerial>".$MarketSerial."</MarketSerial><BillNo>".$borrow_no."</BillNo></Serial><FundAcc><VaccId>".$VaccId."</VaccId><FirmId>".$FirmId."</FirmId><CustName>".$CustName."</CustName></FundAcc><Transfer><FreezeMoney>".$amount."</FreezeMoney></Transfer><FlagInfo><Flag1>0</Flag1></FlagInfo><SummaryInfo><Summary1/><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
	}
	/**
     * 转账查询
	 * 
	 * @param $MarketSerial  varchar 查询流水号
	 * 此功能暂时停用
	 
     * @return array   二维数组
     */
	public function chaxundingdan($MarketSerial){
				$MktCode="10000006";
				$type ="41006";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>41006</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><SummaryInfo><Summary1>订单查询</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	}	
	/**
     * 充值查询
	 * 
	 * @param $MarketSerial  varchar 查询充值流水号
	 * 
	 
     * @return array   二维数组
     */
				
	public function dingdanchaxun($MarketSerial){
				$MktCode="10000006";
				$type ="41019";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>".$MktCode."</MktCode></Pub><Serial><MktSer>".$MarketSerial."</MktSer><OTSer>".$MarketSerial."</OTSer></Serial><FlagInfo><Flag1>5</Flag1><Flag2>0</Flag2></FlagInfo><SummaryInfo><Summary1>订单查询</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
	}
	/**
     * 合作方发起资金信息查询
	 * 返回
	 * 资金账号 
	 * 客户名称
	 * 资金余额
	 * 冻结金额
	 * 授信文件卷宗号
	 * 授信余额
	 * 银行经办机构代码
	 * 授信期限
	 * 授信期限单位
	 * 授信起始日期
	 * 授信到期日期
	 
	 * 此功能暂时停用
	 
	 * @param $MarketSerial  varchar 查询充值流水号
	 * 
	 
     * @return array   二维数组
     */
	public function chaxun1($MarketSerial,$FirmId,$CustName){
				$MktCode="10000006";
				$type ="41011";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MarketSerial>1432791247798</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><FundAcc><FirmId>".$FirmId."</FirmId><CustName>".$CustName."</CustName></FundAcc></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	}
	/**
     * 开户
	 * 默认为个人开户
	 * @param $real_name  varchar 真实姓名
	 * @param $nric  varchar 真实身份证号
     * @return array   二维数组
     */
	public function create_user($real_name,$nric){
				$MktCode="10000006";
				$type="41001";
				$MarketSerial="KH".date('Ymd').date('His');
				$FirmId="a05".date('md').date('His');
				$VaccId = $FirmId;
				$CustName = $real_name;
				$CertDate = "21120101";
				$CertID = $nric;
				$ClientKind = "1";
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>".$MktCode."</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><FundAcc><FirmId>".$FirmId."</FirmId><VaccId>".$VaccId."</VaccId><CustName>".$CustName."</CustName></FundAcc><Client><ClientName>".$CustName."</ClientName><ClientKind>".$ClientKind."</ClientKind><CertType>A</CertType><CertID>".$CertID."</CertID><CertDate>".$CertDate."</CertDate><Gender>0</Gender><Nationality>CHN</Nationality><TelNo>0371-88888888</TelNo><FaxNo>0371-88888888</FaxNo><MobiNo>13888888888</MobiNo><PostCode>476400</PostCode><Address>河南郑州</Address><CorType>G</CorType><CorID>123</CorID><CorDate>21120101</CorDate><Email>qqqq@163.com</Email></Client></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return $configData;	
				}
	/**
     * 认证充值，本质是充值，固定充值用户
	 * @param $MarketSerial  varchar 充值流水号
	 * @param $TransferAmount  varchar 充值金额
     * @return array   二维数组
     */
	public function renzheng($MarketSerial,$TransferAmount){
				$MktCode="10000006";
				$type ="41017";
				$FirmId = "daozhang11";//$FirmId;// 对公账户
				$CustName = "daozhang11";//对公账户姓名				
				
				//$IP1 = "192.168.1.5";
				//$Add1 = "http://localhost";//返回地址
				
				//认证费用
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MktSer>".$MarketSerial."</MktSer></Serial><FundAcc><FirmId>".$FirmId."</FirmId><CustName>".$CustName."</CustName></FundAcc><MoneyKind><MoneyKind>CNY</MoneyKind><CashExCode>1</CashExCode></MoneyKind><Address><IP1>".$IP1."</IP1><Add1>".$Add1."</Add1></Address><Transfer><TransferAmount>".$TransferAmount."</TransferAmount></Transfer><FlagInfo><Flag1>5</Flag1></FlagInfo><SummaryInfo><Summary1>认证扣费</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
				}
	/**
     * 充值
	 * @param $MarketSerial  varchar 充值流水号
	 * @param $FirmId  varchar 充值账户
	 * @param $CustName  varchar 充值用户名
	 * @param $TransferAmount  varchar 充值金额
	 * @param $Add1  varchar 返回地址
     * @return array   二维数组
     */
	public function chongzhi($MarketSerial,$FirmId,$CustName,$TransferAmount,$Add1){
				$MktCode="10000006";
				$type ="41017";			
				$IP1 = "192.168.1.5";				
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>10000006</MktCode></Pub><Serial><MktSer>".$MarketSerial."</MktSer></Serial><FundAcc><FirmId>".$FirmId."</FirmId><CustName>".$CustName."</CustName></FundAcc><MoneyKind><MoneyKind>CNY</MoneyKind><CashExCode>1</CashExCode></MoneyKind><Address><IP1>".$IP1."</IP1><Add1>".$Add1."</Add1></Address><Transfer><TransferAmount>".$TransferAmount."</TransferAmount></Transfer><FlagInfo><Flag1>5</Flag1></FlagInfo><SummaryInfo><Summary1>充值</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo></root>";
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
				}
	/**
     * 本来是提现，现在三方确认为银行卡维护，没有实际操作
	 * @param $MarketSerial  varchar 充值流水号
	 * @param $BankAcc  varchar 银行卡号
	 * @param $CustName  varchar 提现用户名
	 * @param $BankCode  varchar 银行编码
	 * @param $OpenBankName  varchar 银行名称
	 * @param $BankAddr  varchar 银行地址
	 * @param $FirmId  varchar 提现用户FirmId
	 * @param $ClientKind  varchar 提现用户类型，0企业，1个人
	 * @param $CertType  varchar 证件类型，身份为0
	 * @param $CertID  varchar 证件ID号
	 * @param $Flag1  varchar 操作方式  1、增加；2、修改；3、删除 4、查询
	 * @param $TransferAmount  varchar 提现金额
     * @return array   二维数组
     */
	public function tixian($MarketSerial,$BankAcc,$CustName,$BankCode,$OpenBankName,$BankAddr,$FirmId,$ClientKind,$CertType,$CertID,$TransferAmount,$Flag1="1",$province,$city){
				$MktCode="10000006";
				$type ="31039";			
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>".$MktCode."</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><BankAcc><BankAcc>".$BankAcc."</BankAcc><CustName>".$CustName."</CustName><BankCode>".$BankCode."</BankCode><OpenBankName>".$OpenBankName."</OpenBankName><BankAddr>".$BankAddr."</BankAddr></BankAcc><FundAcc><FirmId>".$FirmId."</FirmId></FundAcc><Client><ClientKind>".$ClientKind."</ClientKind><CertType>".$CertType."</CertType><CertID>".$CertID."</CertID><MobiNo>15840554054</MobiNo><Email>xx@xxxx</Email></Client><Transfer><TransferAmount>".$TransferAmount."</TransferAmount></Transfer><FlagInfo><Flag1>".$Flag1."</Flag1><Flag2>3</Flag2></FlagInfo><SummaryInfo><Summary1>".$province."</Summary1><Summary2>".$city."<Summary2/></SummaryInfo></root>";				
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				if($configData){
					return  $configData;	
				}else{
					$result = base64_decode($result);
					return  $result;	
				}
				}
				/**
     * 身份认证
	 * @param $CustName  varchar 认证用户名
	 * @param $FirmId  varchar 编号
	 * @param $CertID  varchar 证件ID号
	 * @param $TransferAmount  varchar 提现金额
     * @return array   二维数组
     */

	public function sfrz($FirmId,$CustName,$CertID){
				$MktCode="10000006";
				$type ="99001";			
				$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><FirmId>".$FirmId."</FirmId><FullName>".$CustName."</FullName><IDNumber>".$CertID."</IDNumber><AuthcType>1</AuthcType></root>";				
				$data = $this->vpostdata($xmldata,$MktCode,$type);
				$result = $this->vpost($data);
				$configData = $this->geshihua($result);
				return  $configData;	
				}
	/**
     * 借款人身份建立
	 * @param $CustName  varchar 认证用户名
	 * @param $FirmId  varchar 编号
	 * @param $CertID  varchar 证件ID号
	 * @param $TransferAmount  varchar 提现金额
     * @return array   二维数组
     */

	public function create_borrower($real_name,$nric){
		$MktCode="10000006";
		$type="41001";
		$MarketSerial="KH".date('Ymd').date('His');
		$FirmId="b05".date('md').date('His');
		$VaccId = $FirmId;
		$CustName = $real_name;
		$CertDate = "21120101";
		$CertID = $nric;
		$ClientKind = "1";
		$xmldata="<?xml version='1.0'encoding='UTF-8'standalone='no'?><root><Pub><Version>3.0</Version><TradeCode>".$type."</TradeCode><Date>".date('Ymd')."</Date><Time>".date('His')."</Time><EntWay>I</EntWay><BankId>A14</BankId><TradeSrc>I</TradeSrc><MktCode>".$MktCode."</MktCode></Pub><Serial><MarketSerial>".$MarketSerial."</MarketSerial></Serial><MoneyKind><MoneyKind>CNY</MoneyKind></MoneyKind><SummaryInfo><Summary1>平安银行跨行入金摘要</Summary1><Summary2/><Tel>~</Tel><BatchId>~</BatchId><Number/></SummaryInfo><FundAcc><FirmId>".$FirmId."</FirmId><VaccId>".$VaccId."</VaccId><CustName>".$CustName."</CustName></FundAcc><Client><ClientName>".$CustName."</ClientName><ClientKind>".$ClientKind."</ClientKind><CertType>A</CertType><CertID>".$CertID."</CertID><CertDate>".$CertDate."</CertDate><Gender>0</Gender><Nationality>CHN</Nationality><TelNo>0371-88888888</TelNo><FaxNo>0371-88888888</FaxNo><MobiNo>13888888888</MobiNo><PostCode>476400</PostCode><Address>河南郑州</Address><CorType>G</CorType><CorID>123</CorID><CorDate>21120101</CorDate><Email>qqqq@163.com</Email></Client></root>";
		$data = $this->vpostdata($xmldata,$MktCode,$type);
		$result = $this->vpost($data);
		$configData = $this->geshihua($result);
		return $configData;	
	}
	
}

