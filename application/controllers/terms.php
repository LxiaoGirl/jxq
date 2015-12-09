<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * 数据库表的控制
 * 
 * @author 艾信
 *        
 */
class Terms extends MY_Controller {
	
	const borrow  = 'borrow'; // 借款
    const payment = 'borrow_payment'; // 投資记录
    const user = 'user'; // 投資记录
    const repay_plan = 'borrow_repay_plan'; // 投資记录
    const guarantee = 'guarantee'; // 担保方
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('tcpdf2');
    }
	/**
	 * 默认
	 */
	function index() {
		//echo	"1111111111";
		$temp['borrow_no'] =  $this->input->get('borrow_no');		
		//echo	$temp['borrow_no'];


		if(!empty($temp['borrow_no'])){			
		
			$temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));
			$temp['data']  = $this->c->get_row(self::borrow, $temp['where']);

			$temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no'],'type' => "1"));
			$temp['payment']  = $this->c->get_all(self::payment, $temp['where']);

			$temp['where'] = array('where' => array('uid' => $temp['data']['uid']));
			$temp['borrow_user']  = $this->c->get_row(self::user, $temp['where']);

			$temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));
			$temp['repay_plan']  = $this->c->get_all(self::repay_plan, $temp['where']);		

			$temp['where'] = array('where' => array('id' => $temp['data']['guarantee_id']));
			$temp['guarantee']  = $this->c->get_row(self::guarantee, $temp['where']);

			$tmp = array(
					'uid'=>$this->session->userdata('uid'),
					'real_name'=>$this->session->userdata('real_name'),
					'nric'=>$this->session->userdata('nric'),
					'mobile'=>$this->session->userdata('mobile'),
			);
			
			

			//var_dump($tmp);
			if($temp['data']['confirm_time']==0){
				$confirm_time = "--";
			}else{
				$confirm_time = date('Ymd',$temp['data']['confirm_time']-86400);
			}
			
			$fontname = 'droidsansfallback';
			$fontsize = 10;
			$this->tcpdf2->setFooterFont(array($fontname, '', 7));
			$this->tcpdf2->setHeaderPageNumAlign(NULL);
			
		if (isset($data['sample']) && $data['sample'] === TRUE) $this->tcpdf2->enableSampleMark();
		$this->tcpdf2->SetMargins(10, 30, 10);
		$this->tcpdf2->AddPage();
		$margins = $this->tcpdf2->getMargins();
		$mp = ($this->tcpdf2->getPageWidth()-$margins['left']-$margins['right'])/2;
		$this->tcpdf2->SetFont($fontname, '', 12);
		
		//$this->tcpdf2->AddPage();

// set text shadow effect

// Set some content to print
$html = <<<EOD
<!-- EXAMPLE OF CSS STYLE -->
<style>
    h1 {
        font-family: droidsansfallback;
    }
    table.first {
        border: 1px solid black;
    }
    td {
        border: 1px solid black;
    }

</style>
<p style="text-align: center;font-size: 28pt;">借款协议</p>
<p style="text-align: right;">合同编号： <i style="text-decoration: underline;">

EOD;
$html .= $temp['data']['borrow_no'];
$html .= <<<EOD

</i></p>
EOD;
		 $html .= '
<p>甲方(投资人) （具体以在线签署时载明为准）：
</p>';


$html .= <<<EOD

<table class="first">
 <tr>  
  <td width="130" align="center"><b>姓名(手机)</b></td>
  <td width="100" align="center"><b>出借金额</b></td>
  <td width="100" align="center"><b>借款期限(月)</b></td>
  <td width="100" align="center"><b>全部应收</b></td>
  <td width="100" align="center"><b>出借日期</b></td>
 </tr>
 
EOD;
	
		
	foreach ($temp['payment'] as $k => $v) {
		$temp['where'] = array('where' => array('uid' => $v['uid']));
		$temp['user']  = $this->c->get_row(self::user, $temp['where']);
		if($v['uid']!=$tmp['uid']){
		 $html .= '
			<tr>
		  <td width="130" align="center">'.secret($temp['user']['mobile'], 5).'</td>
		  <td width="100">'.$v['amount'].'</td>
		  <td width="100">'.$temp['data']['months'].'</td>
		  <td width="100">'.$temp['data']['months']*$temp['data']['rate']/100/12*$v['amount'].'</td>
		  <td width="100">'.$confirm_time.'</td>
		 </tr>';
		}else{
		$html .= '
			<tr>
		  <td width="130" align="center">'.$temp['user']['mobile'].'('.$temp['user']['real_name'].')</td>
		  <td width="100">'.$v['amount'].'</td>
		  <td width="100">'.$temp['data']['months'].'</td>
		  <td width="100">'.$temp['data']['months']*$temp['data']['rate']/100/12*$v['amount'].'</td>
		  <td width="100">'.$confirm_time.'</td>
		 </tr>';
		}
	}

 
 
 
 
 $html .= <<<EOD
 
</table>

<p>
注：如借款人提前还款，以实际出借天数计算收益。
</p>
EOD;
if($temp['data']['add_time']<1444924800){
	 $html .='
	<p>
	乙方（投资接受人）：'.$temp['borrow_user']['real_name'].'
	</p>

	<p>身份证号码：'.secret($temp['borrow_user']['nric'], 13).'
	</p>';
}else{
	 $html .='
	<p>
	乙方（投资接受人）：'.$temp['data']['jkr_name'].'
	</p>

	<p>身份证号码：'.secret($temp['data']['jkr_idcard'], 13).'
	</p>';
		
}



if(!empty($temp['guarantee'])){
$html .='
	 <p>
丙方（担保方）：'.$temp['guarantee']['company_name'].'
</p>
<p>住所地：'.$temp['guarantee']['company_address'].'
</p>
';
}



$html .= '
<p>
丁方（居间方）：沈阳网加互联网金融服务有限公司
</p>
<p>
住所地：沈阳市皇姑区宁山西路21号
</p>

';
 $html .= <<<EOD

<p>
鉴于：
</p>
<p>
1、丁方为“聚雪球”网络平台（网址：www.zgwjjf.com）的运营管理人，提供互联网金融信息咨询及相关服务。
</p>
<p>
2、甲方及乙方同意遵守丁方平台的各项行为准则，在充分阅读理解本文本情形下本着诚信自愿原则签订本《借款协议》。
</p>
<p>
3、丙方为乙方的推荐人，丙方同意为乙方在本协议下债务提供连带责任保证担保。
</p>
<p>
现各方根据平等、自愿的原则，达成本协议如下：
</p>
<p>
</p>
<p>
</p>
<h1>
定义：
</h1>
<p>
1、投资人：指通过“聚雪球”网络平台注册账户，有合法来源的闲余资金，可参考丙方的推荐，自主选择出借一定金额的资金给投资接受人，且具有完全民事权利和行为能力的自然人。
</p>
<p>
2、投资接受人：指有一定的资金需求，经过丙方信用评估后筛选推荐、在“聚雪球”网络平台注册账户，由丁方推荐给出借人并得到投资人资金，且具有完全民事权利及行为能力的自然人或法人。
</p>
<p>
3、借款：指投资人拟向投资接受人提供的资金。
</p>
<p>
4、“聚雪球”网络平台：指丁方运营管理的网站，网址为www.zgwjjf.com，负责为互联网金融交易提供信息服务，并向交易各方提供资金清结算数据的统计服务。
</p>
<p>
5、“聚雪球”网络平台账户：指投资人或投资接受人以自身名义在“聚雪球”网络平台注册后系统自动产生的虚拟账户，通过第三方支付机构或银行及其他通道进行充值或提现。
</p>
<p>
6、监管账户：以丁方及第三方支付机构共同的名义在资金监管银行开立的、账户内资金独立于丁方其他资金的监管账户。
</p>
<p>
7、担保：指丙方为投资人的借款提供的全额本息保障方式，包括但不限于以保证、抵押、质押等方式提供担保，或承诺进行代偿、债权回购或发放后备贷款等方式。
</p>
<h1>
1.借款金额及期限
</h1>
<p>
乙方同意通过互联网平台向甲方借款，甲方同意通过互联网平台向乙方发放该等借款如下：
</p>

EOD;

 $html .='
<p>
项目编号:'.$temp['data']['borrow_no'].'
</p>
<p>
借款详细用途:'.$temp['data']['summary'].'
</p>
<p>
借款本金数额（小写） 	'.$temp['data']['amount'].'
</p>
<p>
借款本金数额（大写） 	'. num2cny($temp['data']['amount']).'
</p>
<p>
借款年化利率 	'.$temp['data']['rate'].'%
</p>
<p>
合同生效日期 	'.$confirm_time.'
</p>
<p>
还款计划：
</p>
<table class="first">
 <tr>  
  <td width="200" align="center"><b>还款日期</b></td>
  <td width="150" align="center"><b>类型</b></td>
  <td width="200" align="center"><b>还款金额(元)</b></td>
 </tr>

';
	
		
	foreach ($temp['repay_plan'] as $k => $v) {
		if($v['repay_type'] == 1):$v['type'] = '利息';else: $v['type'] ='本金';endif;
		$html .= '
		<tr>
		  <td width="200" align="center"><b>'.$v['repay_date'].'</b></td>
		  <td width="150" align="center"><b>'.$v['type'].'</b></td>
		  <td width="200" align="center"><b>'.$v['repay_amount'].'</b></td>
		 </tr>';
	}

 
 
 
 
 $html .= <<<EOD
</table>

<p>
 (非一次性本息的列出所有的期数)		
 </p>
 

<h1>
2.借款流程
</h1>
<p>
2.1 本协议成立：甲方按照“聚雪球”网络平台的规则，通过在互联网平台上对乙方发布的借款需求点击“我要投资”选项确认时，本协议立即成立。 
</p>
<p>
2.2 出借资金冻结：甲方点击“我要投资”即视为其已经向丁方发出不可撤销的授权指令，授权丁方委托其指定的支付机构（包括第三方支付机构或银行，下同），在甲方“聚雪球”网络平台账户中，冻结金额等同于本协议第一条所列的“借款本金数额”的资金。
</p>
<p>
2.3 本协议生效：本协议在乙方发布的借款需求全部得到满足，且乙方借款需求所对应的资金已经全部冻结时立即生效。 
</p>
<p>
2.4 资金划转：本协议生效时，乙方即不可撤销地授权丁方委托其指定的第三方支付机构或银行，根据丁方发出支付指令，将金额等同于本协议第一条所列的“借款本金数额”的资金，转至乙方指定的银行账户，划转完毕日即视为借款发放成功日，借款利息及相关费用开始计算。 
</p>
<p>
2.5 授权：乙方按照“聚雪球”网络平台的规则，通过在互联网平台上对发布借款需求，点击“我要借款”选项确认时，就视为乙方不可撤销的授权丁方查询乙方个人征信等相关信息，并为提供的个人资料的真实性负责，如提供虚假资料，所产生的法律后果由乙方负责。
</p>
<h1>
3. 偿还方式 
</h1>
<p>
3.1 
乙方必须按照本协议的约定按时、足额偿还对甲方的借款本金和利息。甲方、乙方同意并授权丁方按如下方式代为收取上述费用：丁方在每月还款日当日，根据甲方的授权代为向乙方收取乙方当期应支付的本息，金额等同于本协议第一条所列的“月偿还本息数额”的资金。
</p>
<p>
3.2 乙方指定丁方的监管账户为本协议的还款收款账户，各方同意丁方代为收取的本息一经划转至丁方的监管账户后，即视为乙方已经履行本协议项下对甲方的相应还款义务。收到上述本息后，丁方根据与甲方之间的约定通过指定的支付机构向甲方支付该等资金。
</p>
<p>
3.3 如果还款日遇到法定假日或公休日，还款日期提前。
</p>
<p>
3.4乙方每期还款按照如下顺序清偿：（1）违约金（2）平台逾期催收费（3）拖欠的利息（4）拖欠的本金（5）正常的利息（6）正常的本金（7）根据本协议产生的其他全部费用。
</p>
<p>
3.5如乙方还款不足以偿还借款本金、利息和逾期罚息的，甲方同意各自按照其借出款项比例收取还款，不足的部分由丙方进行代偿。
</p>
<p>
3.6借款资金来源保证：甲方保证其所用于出借的资金来源合法，甲方是该资金的合法所有人，如果第三方对资金归属、合法性问题提出异议，由甲方自行解决。
</p>
<h1>
4.收费及税费 
</h1>
<p>
4.1 丙方有权就为本协议借款所提供的服务向乙方收取手续费和服务费、催收费等费用，上述费用的收取方式和计费标准由丙方和乙方共同协商确定。 
</p>
<p>
4.2 丁方有权就为本协议借款所提供的服务向乙方收取服务费，费用计费标准为借款金额的
EOD;
$html .= $temp['data']['real_rate'];
$html .= <<<EOD
%，在放款时一次性扣取。
</p>
<p>
4.3 丁方有权就本协议借款所提供的服务代第三方支付机构向乙方收取第三方支付机构托管费用，具体计费标准为借款金额的0.2%，在放款时一次性扣取。
</p>
<p>
4.4 丁方有权根据甲方与法律服务单位在“聚雪球”网络平台上签订的《互联网金融专项法律服务协议》的约定，向乙方代为收取专项法律服务费。收取方式与4.2相同。
</p>
<p>
4.5 合同各方应按照法律规定自行缴纳各自应承担的税费。
</p>
<h1>
5. 代偿及服务
</h1>
<p>
5.1 
丙方同意为本协议下甲、乙双方的债权债务提供咨询服务和贷后管理等工作，并在乙方未按时偿还债务时立刻承担代偿责任。如在约定还款日乙方未按时还款，丙方应在约定还款日次日立即向丁方的监管账户转入等同于乙方当期应还的借款本息的金额及逾期利息，此时丙方受让甲方对乙方当期的债权。
</p>
<p>
5.2 
丙方根据上述规定承担代偿责任后，甲方、丁方在本协议项下的当期的所有权利视为已经得到满足和实现，甲方、丁方不得再对乙方提出任何请求或主张。甲方、丁方在本协议下所享的关于当期债权的全部权利和主张，包括但不限于对借款本息、补偿金、服务费等所享有权利和主张，均由丙方享有。同时，丙方有权向乙方进行追偿，甲方及丁方应提供合理及必要的协助。丙方有权以诉讼、债权转让等方式处理丙方对乙方的债权。
</p>
<p>
5.3  丙方的承担代偿责任的期限为：自本协议项下借款发放之日起至借款到期后2年止。
</p>
<p>
5.4 丙方的代偿范围包括：乙方的借款本息、逾期罚息、补偿金、服务费等。 
</p>
<p>
5.5 甲方委托丁方作为其合法代理人，在本协议项下协助办理借款代偿等相关事宜，以保证甲方借款资金安全。
</p>
<h1>
6.提前还款 
</h1>
<p>
6.1 乙方提出提前偿还全部剩余本金时，甲方授权丙方决定是否同意乙方提出的提前还款申请。若乙方提前还款申请得到允许的，乙方应向甲方支付剩余全部本金及当期利息及提前结清补偿金。乙方应支付给甲方的提前结清补偿金总额=剩余全部借款本金*
EOD;
$html .= $temp['data']['rate'];
$html .= <<<EOD
 %，同时乙方应当全额向丙方和丁方支付相关服务费。提前还款的款项划转方式与正常还款的方式相同。 
</p>
<p>
6.2 如乙方未能按照本协议约定按时、足额偿还任何一期借款本息超过 1天，或者根据丙方认定发生任何可能影响乙方偿付能力的情形，各方同意丙方有权代甲方提出提前清偿全部剩余本金的要求，甲方、乙方及丁方应同意该等要求，且乙方应当在收到丙方提前还款通知之日起 1个工作日内向甲方支付剩余全部本金、当期利息及提前结清补偿金。如乙方未能支付上述款项则丙方需在收到丁方通知后 1 个工作日内履行代偿责任，自代偿全部完成后，甲方、丁方在本协议下所享的剩余债权的全部权利和主张，包括但不限于对借款剩余本息、逾期罚息、服务费等所享有权利和主张，均全部转让给丙方。 
</p>
<p>
6.3乙方提出提前偿还部分本金的，则乙方提前还款部分由丁方监管账户代为保管，不提前冲减乙方剩余本息及费用，在下一期还款时从中冲减应还款项。
</p>
<h1>
7.逾期还款 
</h1>
<p>
7.1 如约定还款日丁方监管账户中未收到或未足额收到乙方当月应还款的，视为逾期还款。
</p>
<p>
7.2 如乙方逾期还款且丙方未及时履行代偿责任的，适用先息后本的还款方式，乙方应按照初始借款本金，自逾期之日起，逾期30日内按 0.05 %/日的利率按日向丁方支付逾期罚息，逾期超过30日按 0.1%/日的利率按日向丁方支付逾期罚息，直至清偿完毕之日止，逾期罚息不计复利。
</p>
<p>
7.3 如乙方未在每月还款日足额支付当月应还借款本息，但丙方及时履行代偿责任。
</p>
<p>
7.4 借款期间内，逾期罚息的计收标准可根据“聚雪球”网络平台相关规则的变化进行相应调整。如相关规则发生变化，则“聚雪球”网络平台会在网站公示该等规则的变化。
</p>
<p>
7.5 逾期款项中的利息不计利息。
</p>
<h1>
8.违约责任
</h1>
<p>
8.1 如果乙方擅自改变本协议第一条规定的借款用途、严重违反本协议义务、提供虚假资料、故意隐瞒重要事实或未经甲方、丙方同意擅自转让本协议项下借款债务的视为乙方恶意违约，甲方、丙方有权提前终止本协议；乙方须在甲方委托丙方或丁方提出终止本协议之日起的 3 天内，按照本协议规定的款项划转方式一次性支付余下的所有本金、利息，丁方再根据其与甲方之间的约定向甲方支付该等资金。如乙方未按约定时间支付上述款项的，则由丙方履行代偿义务。如乙方违约构成犯罪的，甲方、丙方及丁方有权向相关国家机关报案，追究乙方刑事责任。
</p>
<p>
8.2 发生下列任何一项或几项情形的，视为乙方严重违约：
</p>
<p>
(1) 乙方的任何财产遭受没收、征用、查封、扣押、冻结等可能影响其履约能力的不利事件，且不能及时提供有效补救措施的；
</p>
<p>
(2) 乙方的财务状况出现影响其履约能力的不利变化，且不能及时提供有效补救措施的。
</p>
<p>
8.3 若发生8.2款所述情形，或根据甲方、丙方合理判断乙方可能发生严重违约事件的，甲方、丙方有权自行或由丙方委托丁方采取下列任何一项或几项救济措施：
</p>
<p>
8.3.1 立即暂缓、取消发放全部或部分借款；
</p>

<p>
8.3.2 宣布已发放借款全部提前到期，乙方应立即偿还所有应付款；
</p>
<p>
8.3.3 提前终止本协议；
</p>
<p>
8.3.4 采取法律、法规以及本协议约定的其他救济措施。采取上述措施后，丙方在原代偿范围内继续承担本协议项下的代偿责任。
</p>
<p>
8.4 甲方、丙方根据本协议8.1及8.3的规定自行或由丙方委托丁方终止本协议的，如乙方在其互联网平台账户下有任何余额，则乙方余额按照本协议第3.5款的规定进行清偿。
</p>
<p>
8.5因出现上述违约情况而提前终止本协议的，若乙方未按照约定时间向甲方一次性支付余下的所有本金、利息和相关违约金，则丙方须按本协议第七条的约定履行代偿责任，代替乙方向甲方一次性支付余下的所有本金、利息和相关违约金，丙方在履行代偿责任后，有权向乙方追偿并收取相应的款项。
</p>
<h1>
9、 变更通知 
</h1>
<p>
9.1 本协议签订之日至借款全部清偿之日期间，若乙方的任何信息（包括但不限于乙方姓名、身份证号码、住址、电子邮件等信息的变更）发生变更，则乙方应在发生变更之日起的 3 天内通过“聚雪球”网络平台提供更新后的信息给甲方，并提交相应的证明文件。
</p>
<p>
9.2 若因乙方未及时提供上述变更信息而导致的甲方、丙方及丁方的损失由乙方承担，甲方、丙方及/或丁方为此而发生的调查及诉讼费用应由乙方承担。
</p>
<h1>
10、债权转让
</h1>
<p>
10.1债权存续期间，甲方有权将其所持有的全部或部分债权在“聚雪球”网络平台上进行转让，签署相关《债权转让合同》，该《债权转让合同》视为本协议的补充协议，用以载明债权人变更事项。
</p>
<p>
10.2甲方在此委托丁方，《债权转让合同》一经签订，即由丁方通过其网络管理系统向乙方注册账号发出债权转让通知消息（站内短信），该等消息一经发出即视为债权转让的通知送达乙方，债权转让自通知送达时即对乙方发生债权转让的效力。
</p>
<p>
10.3丙方承诺：丙方担保义务不因债权流转为免责事由，丙方的担保责任无条件及于被转让债权及债权受让人，且该转让行为引起的债权人变更无需通知担保人。
</p>
<h1>
11、债务转让
</h1>
<p>
未经甲方及丙方的事先书面（包括但不限于电子邮件等方式）同意，乙方不得将本协议项下的任何权利义务转让给任何第三方。
</p>
<h1>
12、 其他 
</h1>
<p>
12.1 
本协议以电子文本形式生成，乙方按照“聚雪球”网络平台的规则，通过在“聚雪球”网络平台上勾选相应选项并确认后，即视为乙方与甲方、丙方及丁方已达成协议并同意接受本协议的全部约定以及与“聚雪球”网络平台网站所包含的其他与本协议有关的各项规则的规定。同时，由各方一致同意的被委托的法律服务团队以线下文本固定的方式实时保存交易的所有证据，各方应无条件向法律服务团队提供全部交易证明文件。    
</p>
<p>
12.2 乙方将本协议下全部本金、利息、逾期罚息、补偿金、居间服务费及其他相关费用全部偿还完毕之时，本协议即自动终止。
</p>
<p>
12.3 本协议的任何修改、补充均须在“聚雪球”网络平台以电子文本形式作出。
</p>
<p>
12.4 各方均确认，本协议的签订、生效和履行以不违反法律为前提。如果本协议中的任何一条或多条违反适用的法律，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。
</p>
<p>
12.5 如果各方在本协议履行过程中发生任何争议，应友好协商解决；如协商不成，则须提交丁方所在地人民法院进行诉讼。
</p>
<p>
12.6 各方委托“聚雪球”网络平台保管所有与本协议有关的书面文件或电子信息。
</p>
<p>
EOD;

			// Print text using writeHTMLCell()
			$this->tcpdf2->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);	
			$this->tcpdf2->MultiCell(0, 0, $content3, 0, 'l', false, 1, '', '',  true, 0, true, true, 0, 'T', false) ;		
			$this->tcpdf2->Output($temp['data']['borrow_no'].'.pdf', 'I');	
		}
	}
}

