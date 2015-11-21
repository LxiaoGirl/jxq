<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员登录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Home extends Login_Controller
{
    const user    = 'user'; // 会员表
    const borrow  = 'borrow'; // 借款记录
    const payment = 'borrow_payment'; // 支付记录
    const renzheng = 'user_renzheng'; // 支付记录
    const recharge = 'user_recharge'; // 支付记录

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('finance/payment_model', 'payment');
        $this->load->model('borrow/borrow_model', 'borrow');
        $this->load->model('send_model', 'send');
        $this->load->model('pay_model', 'payend');
		$this->load->model('user/user_model', 'user');
    }
	
	public function test()
    {
				$temp['data'] = array(
					'status' => "1",
					'confirm_time' => time(),
				);
				$temp['where'] = array(
					'where' => array('status' => "2",'type' => "3")
                );				
				$this->c->update(self::recharge, $temp['where'], $temp['data']);
				
		
		
			/* $MarketSerial = time('YmdHis');
			$CustName ="沈阳网加金服互联网金融服务有限公司";
			$FirmId ="f05sy";
			$configData = $this->payend->zanghuchaxun($MarketSerial,$FirmId,$CustName);
			var_dump($configData);
			
			
			//批量发送短信			
			$temp['where'] = array(
                                'select' => 'uid,amount',
                                'where'  => array('borrow_no' => 'B15081844924787','type' => '1')
                            );
            $payment = $this->c->get_all(self::payment,$temp['where']);
			foreach ($payment as $k => $v)	
			{
				//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
				$r[$v['uid']]['uid']=$v['uid'];
				$r[$v['uid']]['amount']+=$v['amount'];
			}
			//$r['89']=  89;
			//$content="【聚雪球】尊敬的聚雪球客户，因平安银行电子支付系统升级，您投资的车贷宝1号-06标的，到帐时间为T+2，若有不便敬请谅解，感谢支持，祝您生活愉快！";
			foreach ($r as $v)			
			{
				$temp['where'] = array(
                                'select' => 'mobile,real_name,firmid',
                                'where'  => array('uid' => $v['uid'])
                            );
				$user = $this->c->get_row(self::user,$temp['where']);
				echo "</br>";
				echo $v['uid'];
				echo "</br>";
				echo $v['amount'];
				echo "</br>";
				//var_dump($user);
				$MarketSerial = time('YmdHis');
				$configData = $this->payend->zanghuchaxun($MarketSerial,$user['firmid'],$user['real_name']);
				echo $configData["Transfer"]['CurrentBalance']/100;
				if($configData["Transfer"]['CurrentBalance']/100){
					
				}
				
				
				echo "</br>";
				echo "</br>";
				
			}  */
			
			
			// $v['mobile']="15840554054";
			// $content="【聚雪球】尊敬的会员，您好！为更好地保证资金安全，应监管银行要求，聚雪球会员只可绑定一张银行卡，如有疑问请拨打客服热线4007-918-333，谢谢！";
			// $res1 = $this->send->send_sms_jieri($v['mobile'],$content);

			//$res1 = $this->payment->_set_repay_plan("B15080388650703");



			//$configData = $this->payend->zanghuchaxun($MarketSerial,$FirmId,$CustName);
			// var_dump($configData);
			// $MarketSerial = time('YmdHis');
			// $PVaccId ="30200394000067";
			// $PCustName ="韩秋红";
			// $RVaccId ="30200394000014";
			// $RCustName ="沈阳网加金服互联网金融服务有限公司";
			// $amount ="24826";
			// $TransferCharge = 0;
			// $configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$amount*100,$TransferCharge);
			// var_dump($configData);
			
			
			/* 
			//批量发送短信			
			$temp['where'] = array(
                                'select' => 'uid',
                                'where'  => array('borrow_no' => 'B15072064651828','type' => '1')
                            );
            $payment = $this->c->get_all(self::payment,$temp['where']);
			foreach ($payment as $k => $v)	
			{
				//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
				$r[$v['uid']]=$v['uid'];
			}
			$r['89']=  89;
			$content="【聚雪球】尊敬的聚雪球客户，因平安银行电子支付系统升级，您投资的车贷宝1号-06标的，到帐时间为T+2，若有不便敬请谅解，感谢支持，祝您生活愉快！";
			foreach ($r as $v)			
			{
				$temp['where'] = array(
                                'select' => 'mobile,real_name,gender',
                                'where'  => array('uid' => $v)
                            );
				$user = $this->c->get_all(self::user,$temp['where']);
				echo  $user[0]['real_name'];
				echo "	";
				// if($user[0]['gender']=="0"){
					// echo "女士";
				// }else{
					// echo "先生";
				// }
				echo $mobile = $user[0]['mobile'];
				//$res1 = $this->send->send_sms_jieri($mobile,$content);
				//echo $res1;
				echo "</br>";
			} */
		
		
		
		
			// $MarketSerial = time('YmdHis');
			// $CustName ="夏伟";
			// $FirmId ="a050613092743";

			// $configData = $this->payend->zanghuchaxun($MarketSerial,$FirmId,$CustName);
			// var_dump($configData);
			/* $temp['where'] = array(
                                'select' => 'uid',
                                'where'  => array('status' => '1')
            );			
            $payment = $this->c->get_all(self::payment, $temp['where']);
			
			foreach ($payment as $k => $v)	
			{
				//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
				$r[$v['uid']]=$v['uid'];
			}
			var_dump($r);
			
			
			foreach ($r as $v)			
			{
				$temp['where'] = array(
                                'select' => 'mobile,real_name,gender',
                                'where'  => array('uid' => $v)
                            );
				$user = $this->c->get_all(self::user,$temp['where']);
				echo  $user[0]['real_name'];
				if($user[0]['gender']=="0"){
					echo "女士";
				}else{
					echo "先生";
				}
				echo $mobile = $user[0]['mobile'];
				
				$temp['where'] = array(
                                'select' => 'sex',
                                'where'  => array('uid' => $v,'isok' => '1','nric_err' => '0')
                            );
				$sex = $this->c->get_one(self::renzheng,$temp['where']);
				var_dump($sex);
				///$res1 = $this->send->send_sms_jieri($mobile,$content);
				//echo $res1;
				echo "</br>";
			} */
			
			//$this->payend->testallcustom();
			//$res = $this->payend->chaxunyonghuyue();
			//$res = $this->payend->shenfenyanzheng("寇琳","211021199004050053");
			//var_dump($res);
			/* $borrow_no ="B15061824865048";
			$RVaccId = "30200394000067";
			$RCustName = "韩秋红";
			$temp['where'] = array(
                                'select' => 'uid,amount',
                                'where'  => array('borrow_no' => 'B15061824865048','type' => '3')
            );
            $payment = $this->c->get_all(self::payment,$temp['where']);

			foreach ($payment as $k => $v)
			{
				$temp['where'] = array(
                                'select' => 'vaccid,real_name',
                                'where'  => array('uid' => $v['uid'])
				);
	            $user = $this->c->get_row(self::user,$temp['where']);
				$MarketSerial = time('YmdHis');
				//echo $user['real_name'];
				//echo $user['vaccid'];
				$TransferCharge = 0;
				// $configData = $this->pay->zhifu($MarketSerial,$user['vaccid'],$user['real_name'],$RVaccId,$RCustName,$v['amount']*100,$TransferCharge);
				 var_dump($configData);
			} */			
			// var_dump(111);
			// $CustName ="韩秋红";
			// $FirmId ="a050612155126";

			// $configData = $this->payend->zanghuchaxun($MarketSerial,$FirmId,$CustName);
			 //$borrow_no2 ="R15071764871208";
			// $res = $this->payend->dingdanchaxun($borrow_no2);
			// $RVaccId ="30200394000067";
			// $RCustName ="韩秋红";
			// $PVaccId ="30200394000024";
			// $PCustName ="杨红";
/* 175 15120.96
136 15120.96
132 75604.83
155 151209.66
169 151209.66 */
			//$MarketSerial = time('YmdHis');
			// $PVaccId ="30200394000139";
			// $PCustName ="张伟";		
			// $PVaccId ="30200394000138";
			// $PCustName ="郑博";		
			// $PVaccId ="30200394000101";
			// $PCustName ="王兰";		
			// $PVaccId ="30200394000123";
			// $PCustName ="冉茂建";
			// $PVaccId ="30200394000133";
			// $PCustName ="张岩";
            // $RVaccId ="30200394000067";
			// $RCustName ="韩秋红";
			// $TransferAmounta ="15120966";
			// $TransferCharge ="0";
			// $configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);
			// var_dump($configData);
			//			$TransferAmounta ="1374170";
			//$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);
			//			$TransferAmounta ="1963100";
			//$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);
			//		$TransferAmounta ="3271833";
			//$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);
			//			$TransferAmounta ="3271833";
			//$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$TransferAmounta,$TransferCharge);


			 		//	$borrow_no ="B15062481346459";
			//$this->payend->testtouzi($borrow_no); 
			// $borrow_no ="R15070130298326";
			// $borrow_no2 ="R15070104825848";
			// $res = $this->payend->dingdanchaxun($borrow_no);
			// var_dump($res);
			// $res =$this->payend->dingdanchaxun($borrow_no2);
			// var_dump($res);
			/* $temp['payment']['borrow_no']="B15061764335644";
			
			//批量发送短信			
			$temp['where'] = array(
                                'select' => 'uid',
                                'where'  => array('borrow_no' => 'B15061764335644','type' => '3')
                            );
            $payment = $this->c->get_all(self::payment,$temp['where']);
			foreach ($payment as $k => $v)	
			{
				//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
				$r[$v['uid']]=$v['uid'];
			}
			$r['89']=  89;
			var_dump($r);
			$content="尊敬的客户,您好!您在聚雪球平台投资的车贷宝二号已还款,本金和利息已转到您的平台账户里,请及时登录查询,如需帮助请拨打客服热线4007-918-333【聚雪球】";
			foreach ($r as $v)			
			{
				$temp['where'] = array(
                                'select' => 'mobile,real_name,gender',
                                'where'  => array('uid' => $v)
                            );
				$user = $this->c->get_all(self::user,$temp['where']);
				echo  $user[0]['real_name'];
				if($user[0]['gender']=="0"){
					echo "女士";
				}else{
					echo "先生";
				}
				echo $mobile = $user[0]['mobile'];
				///$res1 = $this->send->send_sms_jieri($mobile,$content);
				//echo $res1;
				echo "</br>";
			} */
			
			//var_dump($r);
			
			
			
	/* 		
			$content="棕子好甜怡人醉，共诉团圆酒一杯，龙舟雄黄祝福语，端午生色星光辉，理财产品车贷宝，保本保息高收益，道路平稳无妨碍，心静安宁乐相随，聚雪球平台预祝所有用户端午节快乐，心想事成、收益多多！【聚雪球】";
			foreach ($user as $k => $v)	
			{
				//$res1 = $this->send->send_sms_jieri($v['mobile'],$content);
				echo $v['mobile'];
				echo $res1;
				echo "</br>";
			}  */
			//var_dump($res1);
	
			//$this->borrow->finish($temp['payment']['borrow_no']);
			//$res1= $this->payend->shenfenyanzheng("寇琳","21102119900405005x");
		//$temp['payment']['borrow_no']="B15061201249323";
			//$this->payment->_set_flow_type($temp['payment']['borrow_no']);
			//$this->payment->_set_borrow_info($temp['payment']['borrow_no']);
			//$this->payment->_set_repay_plan($temp['payment']['borrow_no']);
 		// $res1 = $this->payend->create_user("王飞","220211197803032093");
		//var_dump($res1);
		
    }
    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
		$data=$temp=array();
        $data = array(
                    'member' => $this->_get_member_total(),
                    'borrow' => $this->_get_borrow_amount(),
                    'invest' => $this->_get_invest_amount(),
                    'rate'   => $this->_get_borrow_rate()
                );
		//获得一级列表
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('home', $data);
    }

    /**
     * 获取会员数量
     *
     * @access private
     * @return integer
     */

    private function _get_member_total()
    {
        return $this->c->count(self::user);
    }

    /**
     * 获取融资金额
     *
     * @access private
     * @return integer
     */

    private function _get_borrow_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['where'] = array('select' => 'SUM(`amount`)');
        $amount = $this->c->get_one(self::borrow, $temp['where']);

        unset($temp);
        return $amount;
    }

    /**
     * 获取投资金额
     *
     * @access private
     * @return integer
     */

    private function _get_invest_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['where'] = array(
                            'select' => 'SUM(`amount`)',
                            'where'  => array('type' => 1)
                        );

        $amount = $this->c->get_one(self::payment, $temp['where']);

        unset($temp);
        return $amount;
    }

    /**
     * 获取借款利率
     *
     * @access private
     * @return integer
     */

    private function _get_borrow_rate()
    {
        $rate = 0;
        $temp = array();

        $temp['where'] = array('select' => 'SUM(`real_rate`)/COUNT(*)');
        $temp['rate']  = $this->c->get_one(self::borrow, $temp['where']);

        if( ! empty($temp['rate']))
        {
            $rate = round($temp['rate'], 2);
        }

        unset($temp);
        return $rate;
    }

    /**
     * oss 图片处理
     */
    public function image(){
        $filename=urldecode($this->input->get('f',true));
        $size = getimagesize($filename); //获取mime信息
        $fp=fopen($filename, "rb"); //二进制方式打开文件
        header("Content-type: {$size['mime']}");
        if ($size && $fp) {
            fpassthru($fp); // 输出至浏览器
        }
    }
}