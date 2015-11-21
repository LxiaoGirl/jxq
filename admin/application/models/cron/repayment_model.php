<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户还款
 *
 * 如果还款方式为一次性支付，用户余额不会发生变化。
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */
class Repayment_model extends CI_Model
{
    const borrow  = 'borrow'; // 交易记录
    const payment = 'borrow_payment'; // 支付记录
    const repay_plan = 'borrow_repay_plan'; // 支付记录
    const payment_log = 'payment_log'; // 支付记录
    const flow    = 'cash_flow'; // 资金记录
    const user    = 'user';//会员
	
	public function __construct()
    {
        parent::__construct();
		$this->load->library('pay');

    }
    /**
     * 用户还款
     * @param string $borrow_no 为空 全部处理   不为空 处理该单号记录
     * @return bool
     */

    public function processing($borrow_no='')
    {
        $query = TRUE;
        $temp = array();

        // 获取需要处理的记录
        $temp['borrow_list'] = $this->_get_borrow_list('',$borrow_no);
		//var_dump($temp['borrow_list']);

        if (!empty($temp['borrow_list'])) {
            $query = TRUE;
            $temp['data'] = $temp['flow'] = $temp['interest'] = array();

            foreach ($temp['borrow_list'] as $k => $v) {
                //已支付的不處理(显示的时候用到)
                if($v['is_pay'] == 0){
                    //根据借款的moel 利息处理方式repay 以及期数 等 计算支付时间数组，如果是（29，30，31则计算月底
                    $temp['pay_day'] = $this->_get_repayment_date($v['confirm_time'], $v['months'], $v['mode'], $v['repay']);
					//var_dump($v['months']);
					//var_dump($v['confirm_time']);
					//var_dump($temp['pay_day']);
                    $temp['today'] = date('Ymd'); //今天（ymd)
                    $temp['end_ymd'] = end($temp['pay_day']); //还款结束日

                    //如果今天是还款日
                    if (in_array($temp['today'], $temp['pay_day'])) {
						//var_dump(11111111);

                        $this->db->trans_start();

                        $temp['balance'] = $this->_get_user_balance($v['uid']);// 获取用户余额
						//var_dump($temp['balance']);
                        $temp['month'] = array_search($temp['today'], $temp['pay_day']); //今天应该支付的期数
                        $temp['interest'] = $this->_get_borrow_interest($v['mode'],$v['amount'], $v['rate'],$v['months'],$v['repay'],$temp['month']);// 计算利息 等额本息本金会有本金一起的

                        //判断是否产生现金流, 默认不产生，（成功支付才产生）
                        $temp['is_cash_flow'] = 1;
                        $temp['status'] = 0; //支付状态为1
                        $temp['is_refund'] = 0;//是否更新还款总额

                        //处理 预支付
                        if ($v['repay'] == 1 || $v['repay'] == 3) { //按月付 加|| $v['repay'] == 3
                            if ($temp['month'] > $v['deduct']) { //已经大于预扣期了
                                if ($temp['balance'] >= $temp['interest']) { //如果余额大于利息
                                    $temp['balance'] = round($temp['balance'] - $temp['interest'], 2); //更改的余额
                                    $temp['is_refund'] = 1;  //还款总额更新
                                    $temp['status'] = 1; //支付状态为1
                                }else{
                                    $temp['is_cash_flow'] = 0;
                                }
                            }else{
                                $temp['status'] = 1; //支付状态为1 (已预付)
                            }
                        }
//                        else if($v['repay'] == 2){
//                            $temp['status'] = 1; //支付状态为1 (已预付)最后付？原来的一次性还款为借款时就扣除  需同步更改
//                        }
                        else{
                            if ($temp['balance'] >= $temp['interest']) { //如果余额大于利息
                                //$temp['balance'] = round($temp['balance'] - $temp['interest'], 2); //更改的余额 *原来的给的利息是减
                                $temp['balance'] = round($temp['balance'] + $temp['interest'], 2); //更改的余额
                                $temp['is_refund'] = 1;  //还款总额更新
                                $temp['status'] = 1; //支付状态为1
                            }else{
                                $temp['is_cash_flow'] = 0;
                            }
                        }
//
                        //产生支付记录
                        $temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');
                        $temp['data'] = array(
                            'payment_no' => $temp['payment_no'],
                            'uid' => $v['uid'],
                            'type' => 2,
                            'borrow_no' => $v['borrow_no'],
                            'rate' => $v['rate'],
                            'amount' => $temp['interest'],
                            'balance' => $temp['balance'],
                            'charge' => 0,
                            'pay_date' =>date('Ymd'), //记录的期数的时间Ymd
                            'dateline' => time(),
                            'pay_time' => $temp['status'] ? time() : 0, //未支付 则支付时间为0  逾期支付后再更新
                            'status' => $temp['status']
                        );
                        $this->c->insert(self::payment, $temp['data']);

                        //产生现金流
                        if ($temp['is_cash_flow']) {
                            $temp['flow'] = array(
                                'uid' => $v['uid'],
                                'type' => 8,
                                'amount' => $temp['interest'],
                                'balance' => $temp['balance'],
                                'source' => $temp['payment_no'],
                                'remarks' => '支付利息',
                                'dateline' => time()
                            );
							//var_dump($temp['flow']);
                            $this->c->insert(self::flow, $temp['flow']);
                        }

                        //更新还款总额
                        if ($temp['is_refund']) {
                            $temp['where'] = array(
                                'where' => array('borrow_no' => $v['borrow_no'])
                            );
                            $temp['data'] = array(
                                "refund" => "refund + {$temp['interest']}",
                                'exp_date'=>date('Ymd'),//原來在updatetime mode中  修改為最後日期 這裡修改為每一期還款的期數 2015..20
                                'is_interest'=>1 //修改为支付利息中 支付收益时会查询 此字段为1的记录  2015..20
                            );

                            $this->c->set(self::borrow, $temp['where'], $temp['data']);
                        }

                        //如果是最后一期 （新增等额本息 等额本金 所以过滤掉该两种mode不在此还本金了）
                        if ($temp['today'] == $temp['end_ymd'] && $v['mode'] != 2 && $v['mode'] != 4) {
                            $temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');

                            $temp['status'] = ($temp['balance'] >= $v['amount']) ? 1 : 0;
                            $temp['balance'] = round($temp['balance'] - $v['amount'], 2);
                            //$temp['balance'] = round($temp['balance'] - $v['amount'], 2);

                            $temp['data'] = array(
                                'payment_no' => $temp['payment_no'],
                                'uid' => $v['uid'],
                                'type' => 2,
                                'borrow_no' => $v['borrow_no'],
                                'rate' => $v['rate'],
                                'amount' => $v['amount'],
                                'balance' => $temp['balance'],
                                'charge' => 0,
                                'pay_date' =>date('Ymd'),
                                'dateline' => time(),
                                'pay_time' => $temp['status'] ? time() : 0,
                                'status' => $temp['status']
                            );

                            $this->c->insert(self::payment, $temp['data']);

                            if ($temp['status']) { // 逾期记录（未支付成功）不会产生资金明细

                                $temp['flow'] = array(
                                    'uid' => $v['uid'],
                                    'type' => 10,
                                    'amount' => $v['amount'],
                                    'balance' => $temp['balance'],
                                    'source' => $temp['payment_no'],
                                    'remarks' => '会员还款',
                                    'dateline' => time()
                                );

                                $this->c->insert(self::flow, $temp['flow']);

                                //更新借款状态(最后一期，关闭交易)
                                $temp['where'] = array(
                                    'where' => array('borrow_no' => $v['borrow_no']),
                                );
                                $temp['data'] = array(
                                    "refund" => "refund + {$v['amount']}",
                                    "finish_time" => time(),
                                    "status" => 7
                                );

                                $this->c->set(self::borrow, $temp['where'], $temp['data']);
                            }
                        }

                        //第三方平台处理
                        $this->db->trans_complete();//$this->db->trans_status()
                    }
                }
            }
        }
        unset($temp);
        return $query;
    }
	
	
	public function get_borrow_user($borrow_no='')
    {
        $temp = array();
		if( ! empty($borrow_no)){
			
			
		}
	}

	/**
     * 借款用户信息
     * @param string $borrow_no 为空 全部处理   不为空 处理该单号记录
     * @return array
     */
	
	
    /**
     * 单个还款
     * @param string $borrow_no
     * @return bool
//     */
//    public function processing_one($borrow_no=''){
//        $query = TRUE;
//        $temp = array();
//        if( ! empty($borrow_no)){
//            $temp['borrow_info'] = $this->_get_borrow_one($borrow_no);
//
//            if( ! empty($temp['borrow_info'])){
//                $temp['data'] = $temp['flow'] = $temp['interest'] = array();
//
//                //计算支付时间，如果是（29，30，31则计算月底
//                $temp['pay_day'] = $this->_get_repayment_date($temp['borrow_info']['confirm_time'], $temp['borrow_info']['months'], $temp['borrow_info']['mode'], $temp['borrow_info']['repay']);
//
//                $temp['today'] = date('Ymd'); //今天（ymd)
//                $temp['end_ymd'] = end($temp['pay_day']); //还款结束日
//
//                if (in_array($temp['today'], $temp['pay_day'])) {
//                    $this->db->trans_start();
//
//                    $temp['balance'] = $this->_get_user_balance($temp['borrow_info']['uid']);// 获取用户余额
//                    $temp['month'] = array_search($temp['today'], $temp['pay_day']); //今天应该支付的期数
//                    $temp['interest'] = $this->_get_borrow_interest($temp['borrow_info']['mode'],$temp['borrow_info']['amount'], $temp['borrow_info']['rate'],$temp['borrow_info']['months'],$temp['borrow_info']['repay'],$temp['month']);// 计算利息
//
//                    //判断是否产生现金流, 默认不产生，（成功支付才产生）
//                    $temp['is_cash_flow'] = 1;
//                    $temp['status'] = 0; //支付状态为1
//                    $temp['is_refund'] = 0;
//                    if ($temp['borrow_info']['repay'] == 1 || $temp['borrow_info']['repay'] == 3) { //按月付 加|| $v['repay'] == 3
//                        if ($temp['month'] > $temp['borrow_info']['deduct']) { //已经大于预扣期了
//                            if ($temp['balance'] >= $temp['interest']) { //如果余额大于利息
//                                $temp['balance'] = round($temp['balance'] - $temp['interest'], 2); //更改的余额
//                                $temp['is_refund'] = 1;  //还款总额更新
//                                $temp['status'] = 1; //支付状态为1
//                            }else{
//                                $temp['is_cash_flow'] = 0;
//                            }
//                        }else{
//                            $temp['status'] = 1; //支付状态为1 (已预付)
//                        }
//                    }
////                        else if($temp['borrow_info']['repay'] == 2){
////                            $temp['status'] = 1; //支付状态为1 (已预付)最后付？
////                        }
//                    else{
//                        if ($temp['balance'] >= $temp['interest']) { //如果余额大于利息
//                            $temp['balance'] = round($temp['balance'] - $temp['interest'], 2); //更改的余额
//                            $temp['is_refund'] = 1;  //还款总额更新
//                            $temp['status'] = 1; //支付状态为1
//                        }else{
//                            $temp['is_cash_flow'] = 0;
//                        }
//                    }
//
//                    //产生支付记录
//                    $temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');
//                    $temp['data'] = array(
//                        'payment_no' => $temp['payment_no'],
//                        'uid' => $temp['borrow_info']['uid'],
//                        'type' => 2,
//                        'borrow_no' => $temp['borrow_info']['borrow_no'],
//                        'rate' => $temp['borrow_info']['rate'],
//                        'amount' => $temp['interest'],
//                        'balance' => $temp['balance'],
//                        'charge' => 0,
//                        'pay_date' => $temp['pay_day'][$temp['month']],//$temp['status'] ? date('Ymd') : 0,//付款期数日期
//                        'dateline' => time(),
//                        'pay_time' => $temp['status'] ? time() : 0,
//                        'status' => $temp['status']
//                    );
//                    $this->c->insert(self::payment, $temp['data']);
//
//                    //产生现金流
//                    if ($temp['is_cash_flow']) {
//                        $temp['flow'] = array(
//                            'uid' => $temp['borrow_info']['uid'],
//                            'type' => 8,
//                            'amount' => $temp['interest'],
//                            'balance' => $temp['balance'],
//                            'source' => $temp['payment_no'],
//                            'remarks' => '支付利息',
//                            'dateline' => time()
//                        );
//                        $this->c->insert(self::flow, $temp['flow']);
//                    }
//
//                    //更新还款总额
//                    if ($temp['is_refund']) {
//                        $temp['where'] = array(
//                            'where' => array('borrow_no' => $temp['borrow_info']['borrow_no'])
//                        );
//                        $temp['data'] = array(
//                            "refund" => "refund + {$temp['interest']}",
//                            'exp_date'=>date('Ymd'),//原來在updatetime mode中  修改為最後日期 這裡修改為每一期還款的期數 2015..20
//                            'is_interest'=>1 //支付利息中 2015..20
//                        );
//
//                        $this->c->set(self::borrow, $temp['where'], $temp['data']);
//                    }
//
//                    //如果是最后一期
//                    if ($temp['today'] == $temp['end_ymd'] && $temp['borrow_info']['mode'] != 2 && $temp['borrow_info']['mode'] != 4) { //&& $v['mode'] != 2 && $v['mode'] != 4 不是等额本金本息还法 还本金
//                        $temp['payment_no'] = $this->c->transaction_no(self::payment, 'payment_no');
//
//                        $temp['status'] = ($temp['balance'] >= $temp['borrow_info']['amount']) ? 1 : 0;
//                        $temp['balance'] = round($temp['balance'] - $temp['borrow_info']['amount'], 2);
//
//                        $temp['data'] = array(
//                            'payment_no' => $temp['payment_no'],
//                            'uid' => $temp['borrow_info']['uid'],
//                            'type' => 2,
//                            'borrow_no' => $temp['borrow_info']['borrow_no'],
//                            'rate' => $temp['borrow_info']['rate'],
//                            'amount' => $temp['borrow_info']['amount'],
//                            'balance' => $temp['balance'],
//                            'charge' => 0,
//                            'pay_date' => $temp['pay_day'][$temp['month']],//$temp['status'] ? date('Ymd') : 0,//付款期数日期
//                            'dateline' => time(),
//                            'pay_time' => $temp['status'] ? time() : 0,
//                            'status' => $temp['status']
//                        );
//
//                        $this->c->insert(self::payment, $temp['data']);
//
//                        if ($temp['status']) { // 逾期记录不会产生资金明细
//
//                            $temp['flow'] = array(
//                                'uid' => $temp['borrow_info']['uid'],
//                                'type' => 10,
//                                'amount' => $temp['borrow_info']['amount'],
//                                'balance' => $temp['balance'],
//                                'source' => $temp['payment_no'],
//                                'remarks' => '会员还款',
//                                'dateline' => time()
//                            );
//
//                            $this->c->insert(self::flow, $temp['flow']);
//
//                            //更新借款状态(最后一期，关闭交易)
//                            $temp['where'] = array(
//                                'where' => array('borrow_no' => $temp['borrow_info']['borrow_no']),
//                            );
//                            $temp['data'] = array(
//                                "refund" => "refund + {$temp['borrow_info']['amount']}",
//                                "finish_time" => time(),
//                                "status" => 7
//                            );
//
//                            $this->c->set(self::borrow, $temp['where'], $temp['data']);
//                        }
//                    }
//
//                    $this->db->trans_complete();
//                }
//            }
//        }
//
//        unset($temp);
//        return $query;
//    }
  /**
     * 查询该还款的记录
     * @return array
     */
    public function show_one(){

		$data=$temp=array();
		$borrow_no = $this->input->get('borrow_no');
		if(!empty($borrow_no)){		
		 $temp['where'] = array(
            'select' => join_field('borrow_no,subject,uid,amount,real_rate,mode,type,repay,months,rate,confirm_time,deadline,exp_date,deduct',self::borrow).','.join_field('real_name,mobile,firmid',self::user),
            'where' => array(join_field('borrow_no',self::borrow)=>$borrow_no),
            'join'=>array('table'=>self::user,'where'=>join_field('uid',self::user).'='.join_field('uid',self::borrow))
        );
        $temp['data'] = $this->c->get_row(self::borrow, $temp['where']);		
		$temp['where'] = array(
                                'select'   => 'repay_date,dateline,repay_index,confirm_time,rapay_time',
                                'where'    => array('borrow_no' => $borrow_no),
                                'order_by' => 'plan_id desc'
                            );
        $data = $this->c->get_row(self::repay_plan, $temp['where']);
		
		$temp['where'] = array(
                                'select'   => 'charge',
                                'where'    => array('borrow_no' => $borrow_no),
                                'order_by' => 'id desc'
                            );
        $payment_log = $this->c->get_row(self::payment_log, $temp['where']);
		
		
		if(empty($data['rapay_time'])){
			$temp['data']['rapay_time'] = time();
		}else{
			$temp['data']['rapay_time'] = $data['rapay_time'];
		}
		// 三方请求投资人预留
		// 
		$MarketSerial = time('YmdHis');
		$CustName =$temp['data']['real_name'];
		$FirmId =$temp['data']['firmid'];
		$configData = $this->pay->zanghuchaxun($MarketSerial,$FirmId,$CustName);
		if($configData['ReturnInfo']['RtnCode'] == '000000'){
			$temp['data']['CurrentBalance'] =  $configData['Transfer']['CurrentBalance']/100;
			$temp['data']['TransferLimit'] =  $configData['Transfer']['TransferLimit']/100;
		}else{
			$temp['data']['CurrentBalance'] =  "三方通讯异常，请通知管理员";
			$temp['data']['TransferLimit'] = "三方通讯异常，请通知管理员";
		}		
		$temp['data']['charge']=$payment_log['charge'];
		$temp['data']['repay_index']=$data['repay_index'];
		$temp['data']['repay_date']=$data['repay_date'];
		$temp['data']['dateline']=$data['dateline'];		
		}
		return $temp['data'];
	}
    /**
     * 查询该还款的记录
     * @return array
     */
    public function show(){
        $data=$temp=array();
        $temp['days']=isset($_GET['days'])?(int)$this->input->get('days'):0;//?天内该还款的记录
        $temp['productcategory']=isset($_GET['productcategory'])?(int)$this->input->get('productcategory'):'';//分类

        $data=$this->_get_borrow_list($temp['productcategory']);//获取要还款的记录

        // if( ! empty($temp['data'])){
            // foreach($temp['data'] as $k=>$v){
                // $temp['pay_day'] = $this->_get_repayment_date($v['confirm_time'], $v['months'], $v['mode'], $v['repay']);//获取还款日期

                // if($temp['month']=$this->_get_recently_pay_day($temp['days'],$temp['pay_day'])){ //几天之内的还款
                    // $v['balance'] = $this->_get_user_balance($v['uid']);// 获取用户余额
                    // $v['interest'] = $this->_get_borrow_interest($v['mode'],$v['amount'], $v['rate'], $v['months'], $v['repay'],$temp['month']);// 根据模式 付息方式 计算该期利息
                    // $v['pay_day']=$temp['pay_day'][$temp['month']];//还款日期
                    // $v['month']=$temp['month'];//第几期
                    // $v['is_repay']=0;
                    // if($v['pay_day'] == end($temp['pay_day']) && $v['mode'] !=2  && $v['mode'] !=4 ){//不是等额本金和等额本息 最后一个月时显示本金和利息
                        // $v['interest'].='+本金'.$v['amount'];
                    // }

                    // if (($v['repay'] == 1 && $temp['month'] < $v['deduct']) || $v['repay'] == 2){ //按月付 小于预付期 或者一次性支付  标记为已付
                        // $v['is_repay']=1;
                    // }
                    // $data[]=$v;
                // }
            // }
        // }

        unset($temp);
        return $data;
    }

    /**
     * 获得 最近几天 是否有在还款日期中
     * @param $days  int 天数
     * @param $array array 还款日期数组
     * @return bool 返回 false或者 还款日期
     */
    private function _get_recently_pay_day($days=0,$array=array()){
        $query='';
        $temp=array();

        if( ! empty($array)){
            $temp['today'] = date('Ymd'); //今天（ymd)
            if($days == 0 && $query=array_search($temp['today'],$array)){

            }else{
                if( ! $query=array_search($temp['today'],$array)){
                    for($i=1;$i<=$days;$i++){
                        $temp['day']=date('Ymd',strtotime('+'.$i.' day'));
                        if($query=array_search($temp['day'],$array)){
                            break;
                        }
                    }
                }
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获得单条 需还款记录信息
     * @param string $borrow
     * @return array
     */
    private function _get_borrow_one($borrow=''){
        $data = $temp = array();

        $temp['where'] = array(
            'select' => join_field('borrow_no,uid,amount,mode,type,repay,months,rate,confirm_time,deadline,exp_date,deduct',self::borrow).','.join_field('user_name,mobile',self::user),
            'where' => array(join_field('status',self::borrow) => 4,join_field('borrow_no',self::borrow)=>$borrow),
            'join'=>array('table'=>self::user,'where'=>join_field('uid',self::user).'='.join_field('uid',self::borrow))
        );

        $temp['data'] = $this->c->get_row(self::borrow, $temp['where']);

        if (!empty($temp['data'])) {

            $temp['borrow_no'][] = $temp['data']['borrow_no'];

            $temp['exist'] = $this->_get_payment_list($temp['borrow_no']);

            if ( ! in_array($temp['data']['borrow_no'], $temp['exist'])) {
                $data=$temp['data'];
            }
        }

        unset($temp);
        return $data;
    }

    /****************↑↑↑↑↑↑wsb2015.5.16*******************************/

    /**
     * 计算利息
     * @param int $mode   借款方式 1先息后本  2等额本息   3 一次性还本付息 4等额本金
     * @param int $amount 数额
     * @param int $rate 利率
     * @param int $months 月
     * @param int $repay 付息方式
     * @param int $index 第几期
     * @return float
     */

    public function _get_borrow_interest($mode=1,$amount=0,$rate=0,$months=0,$repay=1,$index=1)
{
    $query=0;
    $temp=array();

    switch($mode){
        case 1:
            $temp['data']=$this->_get_xxhb_repayment_list($amount,$rate,$months,$repay);
            $query=$temp['data'][$index]['amount'];
            break;
        case 2:
            $temp['data']=$this->_get_debx_repayment_list($amount,$rate,$months);
            $query=$temp['data'][$index]['amount'];
            break;
        case 3:
            $temp['data']=$this->_get_ycxbx_interest($amount,$rate,$months);
            $query=$temp['data'];
            break;
        case 4:
            $temp['data']=$this->_get_debj_repayment_list($amount,$rate,$months);
            $query=$temp['data'][$index]['amount'];
            break;
        default;

    }

    unset($temp);
    return $query;
}

    /**
     * 获得还款计划数据 2015.06.09
     * @param int $mode
     * @param int $amount
     * @param int $rate
     * @param int $months
     * @param int $repay
     * @return array
     */
    public function get_borrow_plan($mode=1,$amount=0,$rate=0,$months=0,$repay=1){
        $query=array();
        $temp=array();

        switch($mode){
            case 1:
                $query=$this->_get_xxhb_repayment_list($amount,$rate,$months,$repay);
                //先息后本  最后的还本
                $query[]=array(
                    'principal'=>$amount,
                    'interest'=>0,
                    'amount'=>$amount,
                    'surplus_principal'=>0
                );
                break;
            case 2:
                $query=$this->_get_debx_repayment_list($amount,$rate,$months);
                break;
            case 3:
                $temp['interest']=$this->_get_ycxbx_interest($amount,$rate,$months);

                $temp['data'][1]['principal']=0; //月付本金
                $temp['data'][1]['interest']=$temp['interest'];//月付利息
                $temp['data'][1]['amount']=$temp['interest'];//月付本金和利息总额
                $temp['data'][1]['surplus_principal']=$amount;//剩余本金

                 $temp['data'][2]['principal']=$amount; //月付本金
                $temp['data'][2]['interest']=0;//月付利息
                $temp['data'][2]['amount']=$amount;//月付本金和利息总额
                $temp['data'][2]['surplus_principal']=0;//剩余本金

                $query=$temp['data'];
                break;
            case 4:
                $query=$this->_get_debj_repayment_list($amount,$rate,$months);
                break;
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取还款日和最后还款日
     *
     * @access private
     * @param  integer $confirm_time 确认时间
     * @param  integer $months 还款期数
     * @return integer
//     */
//
//    private function _get_repayment_date($confirm_time = 0, $months = 0)
//    {
//        $aDay = array();
//
//        $day   = date('j', $confirm_time); //发布日天数
//        $month = date('n', $confirm_time); //发布日月数
//        $year  = date('Y', $confirm_time); //发布日年数
//
//        //生成每个月还款日期数组
//        for ($i = 1; $i <= $months; $i++) {
//
//            //如果大于28号(29, 30, 31)
//            if ($day > 28) {
//                $lastDay = date('t', mktime(0, 0, 0, $month + $i, 1, $year));
//
//                if ($day < $lastDay) {
//                    $aDay[$i] = date('Ym' . $day, mktime(0, 0, 0, $month + $i, 1, $year));
//                } else {
//                    $aDay[$i] = date('Ymt', mktime(0, 0, 0, $month + $i, 1, $year));
//                }
//
//            } else {
//                $aDay[$i] = date('Ymd', mktime(0, 0, 0, $month + $i, $day, $year));
//            }
//
//        }
//
//        return $aDay;
//    }

    /**
     * 获取用户余额
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return array
     */

    private function _get_user_balance($uid = 0)
    {
        $balance = 0;
        $temp    = array();

        if (!empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => (int)$uid),
                                'order_by' => 'id desc'
                            );

            $temp['balance'] = $this->c->get_one(self::flow, $temp['where']);

            if( ! empty($temp['balance']))
            {
                $balance = $temp['balance'];
            }
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取需要处理的记录(已处理的标记is_pay=1)
     *
     * @access private
     * @param  string $type 类型
     * @param  string $borrow_no 借款号
     * @return array
     */

    private function _get_borrow_list($type='',$borrow_no='')
    {
		//header("Content-type:text/html;charset=utf-8");
		//var_dump(date('Ymd'));
        $data = $temp = array();

        $temp['where'] = array(
            'select' => join_field('borrow_no,subject,uid,amount,mode,type,repay,months,rate,confirm_time,deadline,exp_date,deduct',self::borrow).','.join_field('user_name,mobile',self::user),
            'where' => array(join_field('status',self::borrow) => 4),
            'join'=>array('table'=>self::user,'where'=>join_field('uid',self::user).'='.join_field('uid',self::borrow)),
			//'between' => '`repay_date` BETWEEN \'' . $temp['start'] . '\' AND \'' . $temp['end'] . '\''
        );//wsb-2015.5.16 新增 join 查询 用户姓名电话
        if( ! empty($type)){
            $temp['where']['where']['productcategory']=$type;
        }
        if( ! empty($borrow_no)){
            $temp['where']['where']['borrow_no']=$borrow_no;
        }		
        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);
        if (!empty($temp['data'])) {
            $temp['borrow_no'] = array();

            foreach ($temp['data'] as $v) {
                $temp['borrow_no'][] = $v['borrow_no'];
            }
			$temp['exist'] = $this->_get_payment_list($temp['borrow_no']);

            foreach ($temp['data'] as $v) {
				
				foreach ($temp['exist'] as $k) {
					 if (in_array($v['borrow_no'], $k)) {
					  
						 $temp['exist'][$v['borrow_no']]['balance']=$this->_get_user_balance($v['uid']);
						 $temp['exist'][$v['borrow_no']]['month']=$v['month'];
						 $temp['exist'][$v['borrow_no']]['user_name']=$v['user_name'];
						 $temp['exist'][$v['borrow_no']]['rate']=$v['rate'];
						 $temp['exist'][$v['borrow_no']]['mode']=$v['mode'];
						 $temp['exist'][$v['borrow_no']]['mobile']=$v['mobile'];
						 $temp['exist'][$v['borrow_no']]['confirm_time']=$v['confirm_time'];
						 $temp['exist'][$v['borrow_no']]['repay_date']=$k['repay_date'];
						 if($v['mode'] =="3"){
							$temp['exist'][$v['borrow_no']]['month']="1";
							$temp['exist'][$v['borrow_no']]['months']="1";
						 }
						 
						$Date_1=time();
						$Date_2=(int)$v['confirm_time'];
						$Days=round(($Date_1-$Date_2)/3600/24)+1;
						//$Days = 27;   //一次性本息的27天的利息计算
						//echo "今天与2008年10月11日相差".$Days."天";
						 $temp['exist'][$v['borrow_no']]['dayinterest'] =  round($v['amount']*$Days/30*$v['rate']/100/12,2);
						 $temp['exist'][$v['borrow_no']]['subject'] =  $v['subject'];
						 
											 
					}else{
						
					}
					
				}
				
				
               
            }
						$data =$temp['exist'];


			// foreach ($temp['exist'] as $v) {
				   ////  $where['where']['borrow_no']=$v;	
					// var_dump($v);

					// $temp['where'] = array(
						// 'where' => array('borrow_no' => $v),
					// );
					// $borrow = $this->c->get_all(self::repay_plan, $temp['where']);
					// $data = $borrow;							
			// }
			//$data =$temp['exist'];
           /*  foreach ($temp['data'] as $v) {
                $v['is_pay']=0;
                if (in_array($v['borrow_no'], $temp['exist'])) {
                    $v['is_pay']=1;
                }
                $data[] = $v;
            } */
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取还款记录
     *
     * @access private
     * @param  array $borrow_no 借款编号
     * @return array
     */

    private function _get_payment_list($borrow_no = array())
    {
        $day=isset($_GET['day'])?$this->input->get('day',true):'7';
        $data = $temp = array();

        if (!empty($borrow_no)) {
//            $temp['start'] = date('Y-m-') . '01';
//            $temp['start'] = strtotime($temp['start']);
//            $temp['end'] = strtotime('+1 months', $temp['start']);
            $temp['start']=  date('Ymd');
//var_dump($temp['start']);
if($day<0){
    $temp['end'] = strtotime('-1 day', time());
   $temp['end'] = date('Ymd', $temp['end']);

    $temp['start'] = strtotime($day.' day', time());
   $temp['start'] = date('Ymd', $temp['start']);
}else{
	    $temp['end'] = strtotime('+'.$day.' day', time());
         $temp['end'] = date('Ymd',$temp['end']);
}


            foreach ($borrow_no as $v) {
				 $temp['where'] = array(
                'select' => 'borrow_no,repay_amount,repay_type,repay_date',
                'where_in' => array('field' => 'borrow_no', 'value' => $v),
                'where' => array('status' => 0), //,'repay_date'=>$temp['today']),//加了today 还款日为今天的记录
                'between' => '`repay_date` BETWEEN \'' . $temp['start'] . '\' AND \'' . $temp['end'] . '\''
				);
				$data = $this->c->get_all(self::repay_plan, $temp['where']);
				foreach ($data as $v){
					$r[$v['borrow_no']]['borrow_no'] =$v['borrow_no'];
					$r[$v['borrow_no']]['repay_date'] =$v['repay_date'];
					if($v['repay_type']=="2"){
						$r[$v['borrow_no']]['amount']=$v['repay_amount'];							
					}elseif($v['repay_type']=="1"){
						$r[$v['borrow_no']]['interest']=$v['repay_amount'];	
					}
				}
			}
			foreach($r as $v){
				$r[$v['borrow_no']]['sum']=round(($v['amount']+$v['interest'])*100/100,2);		
				if (preg_match('/^(/.[0-9]{1,2})?$/', $r[$v['borrow_no']]['sum'])) {  

				}else{  
					$r[$v['borrow_no']]['sum'] = number_format($r[$v['borrow_no']]['sum'],2,'.','');

					//$r[$v['borrow_no']]['sum'] = $r[$v['borrow_no']]['sum'];
				}  				
			}
			$data=$r;
	
        }

        unset($temp);
        return $data;
    }

    /************************************wsb-2015.5.17*****************************************************************************************************/

    /**
     * 等额本息还款 计划记录
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return array   二维数组
     */
    private function _get_debx_repayment_list($amount,$rate,$months){
        $query=$temp=array();

        if( ! empty($amount) && ! empty($rate) && ! empty($months)){
            $temp['m_rate']=($rate/100)/12;//月利率
            $temp['m_amount']=$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额
            $temp['pay_principal']=0;//已付本金

            for($i=1;$i<=$months;$i++){
                if($i != $months){
                    $temp['data']['amount']=round($temp['m_amount'],2);//月付本金和利息总额
                    $temp['data']['interest']=($amount*$temp['m_rate']-$temp['m_amount'])*pow((1+$temp['m_rate']),$i-1)+$temp['m_amount'];//月付利息
                    $temp['data']['interest']=substr($temp['data']['interest'],0,strpos($temp['data']['interest'],'.')+3);//保留两位 不四舍五入
                    $temp['data']['principal']=round($temp['m_amount']-$temp['data']['interest'],2); //月付本金
                    $temp['pay_principal']+=$temp['data']['principal']; //累加 已付本金
                    $temp['data']['surplus_principal']=round($amount-$temp['pay_principal'],2);//剩余本金
                }else{
                    $temp['data']['principal']=$query[$i-1]['surplus_principal'];
                    $temp['data']['interest']=$temp['data']['principal']*$temp['m_rate'];
                    $temp['data']['interest']=substr($temp['data']['interest'],0,strpos($temp['data']['interest'],'.')+3);//保留两位 不四舍五入
                    $temp['data']['amount']=$temp['data']['principal']+$temp['data']['interest'];
                    $temp['data']['surplus_principal']=0;
                }

                $query[$i]=$temp['data'];
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 等额本息 所有利息
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return float
     */
    private function _get_debx_all_interest($amount,$rate,$months){
        $temp=array();

        $temp['m_rate']=($rate/100)/12;//月利率
        $temp['m_amount']=$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额

        return round(($months*$temp['m_amount']-$amount),2);
    }

    /**
     * 等额本金 还款计划列表
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return array
     */
    private function _get_debj_repayment_list($amount,$rate,$months){
        $query=$temp=array();

        if( ! empty($amount) && ! empty($rate) && ! empty($months)){
            $temp['m_rate']=($rate/100)/12;//月利率

            for($i=1;$i<=$months;$i++){
                $temp['data']['principal']=round($amount/$months,2); //月付本金
                $temp['data']['interest']=round(($amount-($i-1)*$temp['data']['principal'])*$temp['m_rate'],2);//月付利息
                $temp['data']['amount']=round($temp['data']['principal']+$temp['data']['interest'],2);//月付本金和利息总额
                $temp['data']['surplus_principal']=round($amount-$i*$temp['data']['principal'],2);//剩余本金
                $query[$i]=$temp['data'];
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 等额本金 还款 所有利息
     * @param $amount
     * @param $rate
     * @param $months
     * @return float
     */
    private function _get_debj_all_interest($amount,$rate,$months){
        return round(($months+1)*$amount*(($rate/100)/12)/2,2);
    }

    /**
     * 一次性本息 利息
     */
    private function _get_ycxbx_interest($amount,$rate,$months){
        return round($amount*(($rate/100)/360)*($months*30),2);
    }

    /**
     * 先息后本 还款计划列表
     * @param $amount float 数量
     * @param $rate  float 利率
     * @param $months float 期数
     * @param $repay_type int 付息方式  按日3 按月1
     */
    private function _get_xxhb_repayment_list($amount=0,$rate=0,$months=0,$repay_type=1){
        $query=$temp=array();

        if( ! empty($amount) && ! empty($rate) && ! empty($months) && ! empty($repay_type)){

            if($repay_type == 3){ //日付
                $temp['days']=$months*30;//天数
                for($i=1;$i<=$temp['days'];$i++){
                    $query[$i]['principal']=0;
                    $query[$i]['interest']=round($amount*($rate/100/360),2);
                    $query[$i]['amount']=$query[$i]['interest'];
                    $query[$i]['surplus_principal']=$amount;
                }
            }else{ //按月付
                $temp['mon']=ceil($months);//进月取整 得最大期数
                for($i=1;$i<=$temp['mon'];$i++){
                    if($i == $temp['mon']){//最后一期
                        $query[$i]['principal']=0;
                        $query[$i]['interest']=round($amount*($rate/100/360)*($months*30-($i-1)*30),2);
                        $query[$i]['amount']=$query[$i]['interest'];
                        $query[$i]['surplus_principal']=$amount;
                    }else{
                        $query[$i]['principal']=0;
                        $query[$i]['interest']=round($amount*($rate/100/12),2);
                        $query[$i]['amount']=$query[$i]['interest'];
                        $query[$i]['surplus_principal']=$amount;
                    }
                }
            }

        }

        unset($temp);
        return $query;
    }

    /**
     * 获取还款日
     *
     * @access private
     * @param  integer $confirm_time 确认时间
     * @param  integer $months 还款期数
     * @param  integer $mode 1先息后本  2等额本息   3 一次性还本付息 4等额本金
     * @param  integer $repay 1按月扣除 2一次性扣除 3按日扣除
     * @return array
     */

    public function _get_repayment_date($confirm_time = 0, $months = 0, $mode=1, $repay=1)
    {
        $aDay = array();

        $day   = date('j', $confirm_time); //发布日天数
        $month = date('n', $confirm_time); //发布日月数
        $year  = date('Y', $confirm_time); //发布日年数

        if($mode == 3){ //一次性还本付息 只有一期  最后的时间
			$aDay[0]="1";
            $aDay[1]=date('Ymd',$confirm_time+3600*24*$months*30);
        }elseif($mode == 1){ //先息后本 分日付和月付
            if($repay == 3){ //日付 以每天做一期
                for($i=1,$days=$months*30;$i <= $days;$i++){
                    $aDay[]=date('Ymd',$confirm_time+3600*24*$i);
                }
            }else{ //月付 进一取月 最后月最时间调整
                $mon=ceil($months);//进一取整月

                for ($i = 1; $i <= $mon; $i++) {
                    if($i == $mon){ //最后一个月
                        $aDay[]=date('Ymd',$confirm_time+3600*24*$months*30);//还款日最后日期
                    }else{
                        //如果大于28号(29, 30, 31)
                        if ($day > 28) {
                            $lastDay = date('t', mktime(0, 0, 0, $month + $i, 1, $year));

                            if ($day < $lastDay) {
                                $aDay[$i] = date('Ym' . $day, mktime(0, 0, 0, $month + $i, 1, $year));
                            } else {
                                $aDay[$i] = date('Ymt', mktime(0, 0, 0, $month + $i, 1, $year));
                            }

                        } else {
                            $aDay[$i] = date('Ymd', mktime(0, 0, 0, $month + $i, $day, $year));
                        }
                    }
                }

            }
        }else{
            //生成每个月还款日期数组
            for ($i = 1; $i <= $months; $i++) {

                //如果大于28号(29, 30, 31)
                if ($day > 28) {
                    $lastDay = date('t', mktime(0, 0, 0, $month + $i, 1, $year));

                    if ($day < $lastDay) {
                        $aDay[$i] = date('Ym' . $day, mktime(0, 0, 0, $month + $i, 1, $year));
                    } else {
                        $aDay[$i] = date('Ymt', mktime(0, 0, 0, $month + $i, 1, $year));
                    }

                } else {
                    $aDay[$i] = date('Ymd', mktime(0, 0, 0, $month + $i, $day, $year));
                }

            }
        }

        return $aDay;
    }


}