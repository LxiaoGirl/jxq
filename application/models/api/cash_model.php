<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 资金相关model处理
 * Class Cash_model
 */
class Cash_model extends CI_Model{
    //用到的数据库表
    const borrow        = 'borrow';             //借款项目表
    const user          = 'user';               //用户表
    const category      = 'product_category';   //项目分类表
    const payment       = 'borrow_payment';     //项目投资还款表
    const transfer      = 'user_transaction';   //提现表
    const cash          = 'cash_flow';          //资金记录表
    const recharge      = 'user_recharge';      //充值记录表
    const card          = 'user_card';          //用户银行卡记录表
	const payment_jbb = 'borrow_payment_jbb';   //用户银行卡记录表
	const jbb           = 'borrow_jbb';		    //用户银行卡记录表
	const jbb_dtl       = 'borrow_jbb_dtl';     // 聚保宝发标表
	const recharge_jbb  = 'user_recharge_jbb';  //聚保宝提取收益审核表
	const risk          = 'risk_money';          //风险保证金


    const RUN_DATE      = '2015-06-12';         //网站运行时间
    const TRANSFE_MIN   = '50';                 //提现最低金额
    private $_page_size = '10';                 //分页每页记录数

    public function __construct(){
        parent::__construct();
		date_default_timezone_set('PRC');
		$this->load->model('api/common/send_model','send');
    }

    /**
     * 获取用户余额
     * @param int $uid 用户id 默认0
     * @return int
     */
    public function get_user_balance($uid=0){
        $data = array(
            'name'   =>'用户余额',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array('balance'=>0)
        );
        $temp    = array();

        if( ! empty($uid)){
            $temp['where'] = array(
                'select'   => 'balance',
                'where'    => array('uid' => $uid),
                'order_by' => 'id desc'
            );

            $temp['balance'] = $this->c->get_one(self::cash, $temp['where']);
            if(is_null($temp['balance']))$temp['balance']=0;
            $data['data']['balance'] = $temp['balance'];
            $data['status']          = '10000';
            $data['msg']             = 'ok!';
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取全网 借款总额 投资总额
     * @param int $category 类别id
     * @return array
     * 'borrow_total' => string '24159580.00' (length=11)借款总额
     * 'invest_total' => string '24023752.00' (length=11) 投资总额
     * 'interest_total' => float 233576.22 利息总额
     * 'user_total' => string '411' (length=3) 用户总额
     *'days_total' => int 138 运行时间总额
     *'risk_total' => int 1000000 风险保障总额
     */
    public function get_cash_total($category=0){
        $temp = array();
        $data = array(
            'name'   =>'全网总额统计',
            'status' =>'10001',
            'msg'    =>'服务器繁忙请稍后重试!',
            'sign'   =>'',
            'data'   =>array('borrow_total'=>0,'invest_total'=>0,'interest_total'=>0,'user_total'=>0,'days_total'=>0,'risk_total'=>0)
        );

        $temp['where'] = array(
            'select' =>'SUM(amount) as borrow_total,SUM(receive) as invest_total',
            'where'  =>array('status >'=>1)
        );
        //如果有分类 则查询分类下的借款总额
        if($category > 0){
            $temp['where']['where']['productcategory']=$category;
        }
        $data['data']     = $this->c->get_row(self::borrow,$temp['where']);
        
        $temp['interest'] = $this->get_project_interest_total($category);
        if($temp['interest']['status'] == '10000'){
            $data['data']['interest_total']=$temp['interest']['data']['interest'];
        }

        $data['data']['user_total'] = $this->c->count(self::user);
        $data['data']['days_total'] = ceil((time()-strtotime(self::RUN_DATE))/3600/24);
        $data['data']['risk_total'] = $this->c->get_one(self::risk,array('where'=>array('id'=>1),'select'=>'money'));

        $data['status'] = '10000';
        $data['msg']    = 'ok!';

        unset($temp);
        return $data;
    }

    /**
     * 用户资金统计
     * @param int $uid
     * @return array
     * 'property_total' => float 223.86 总资产
     *  'invest_total' => int 783 累计投资
     *  'receive_principal_total' => int 682 已收本金
     *  'wait_principal_total' => int 101 待收本金
     *  'receive_interest_total' => float 5.71 已收利息
     *  'wait_interest_total' => float 0.91 待收利息
     *  'invest_freeze_total' => int 0 投资冻结
     *  'transfer_freeze_total' => int 0 提现冻结
     */
    public function get_user_cash_total($uid=0){
        $data = array(
            'name'=>'用户资金统计',
            'status'=>'10001',
            'msg'=>'用户uid为空!',
            'sign'=>'',
            'data'=>array(
                'property_total'          =>0,//总资产
                'invest_total'            =>0,//累计投资
                'receive_principal_total' =>0,//已收本金
                'wait_principal_total'    =>0,//待收本金
                'receive_interest_total'  =>0,//已收利息
                'wait_interest_total'     =>0,//待收利息
                'invest_freeze_total'     =>0,//投资冻结
                'transfer_freeze_total'   =>0,//提现冻结
				'balance'				  =>0//可用余额
            )
        );
        $temp    = array();

        if( ! empty($uid)){
			$data['data']['jbb_all_amount_1']		 = $this->jbb_all_amount($uid,1);//代收本金 聚保宝
			$data['data']['jbb_all_amount_2']		 = $this->jbb_all_amount($uid,2);//冻结资金 聚保宝
            $data['data']['invest_total']            = $this->get_user_invest_total($uid);
            $temp['principal_interest']              = $this->get_user_receive_principal_interest($uid);
            $data['data']['receive_principal_total'] = $temp['principal_interest']['receive_principal'];
            $data['data']['receive_interest_total']  = $temp['principal_interest']['receive_interest'];
            $data['data']['wait_principal_total']    = $data['data']['invest_total']?round($data['data']['invest_total']-$data['data']['receive_principal_total']+$data['data']['jbb_all_amount_1'],2):0;
            $temp['interest_all']                    = $this->get_user_interest_all($uid);
            $data['data']['wait_interest_total']     = $temp['interest_all']?round($temp['interest_all']-$data['data']['receive_interest_total'],2):0;
            $data['data']['invest_freeze_total']     = $this->get_user_invest_freeze($uid);
            $data['data']['transfer_freeze_total']   = $this->get_user_transfer_freeze($uid);
            $temp['balance']                         = $this->get_user_balance($uid);			
			$data['data']['balance']				 = $temp['balance']['data']['balance'];
            $data['data']['property_total']          = round($temp['balance']['data']['balance'] + $data['data']['wait_principal_total']+$data['data']['invest_freeze_total']+$data['data']['transfer_freeze_total'],2);

            $data['status'] = '10000';
            $data['msg']    = 'ok!';
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户各类别下投资金额统计
     * @param int $uid
     * @return array
     * 0 =>
     *   array
     *  'cat_id' => string '1' (length=1)
     *  'category' => string '车贷宝' (length=9)
     *  'invest_total' => int 783
     *  1 =>
     *  array
     *  'cat_id' => string '2' (length=1)
     *  'category' => string '聚农贷' (length=9)
     *  'invest_total' => int 0
     *  2 =>
     *  array
     *  'cat_id' => string '3' (length=1)
     *  'category' => string '聚惠理财' (length=12)
     *  'invest_total' => int 0
     */
    public function get_user_category_invest_total($uid=0){
        $data = array(
            'name'   =>'用户各类别下投资金额统计',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        if($uid > 0){
            $temp['category'] = $this->c->get_all(self::category);
            if($temp['category']){
                foreach($temp['category'] as $key=>$val){
                    $data['data'][$key]=array(
                        'cat_id'       =>$val['cat_id'],
                        'category'     =>$val['category'],
                        'invest_total' =>$this->get_user_invest_total($uid,$val['cat_id'])
                    );
                }
                $data['msg']    = 'ok!';
                $data['status'] = '10000';
            }else{
                $data['msg'] = '没有类型!';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户资金记录
     * @param int $uid
     * @param string $type_str
     * @param string $month
     * @param string $start_time
     * @param string $end_time
     * @param int $page_id
     * @param int $page_size
     * @return array
     *
     * 'amount' => string '+9585.50' (length=8)
     *  'remarks' => string '一次性本息收益' (length=21)
     *  'dateline' => string '1445497425' (length=10)
     *  'type' => string '7' (length=1)
     *  'type_name' => string '收入' (length=6)
     */
    public function get_user_cash_list($uid=0,$type_str='',$month='',$start_time='',$end_time='',$page_id=0,$page_size=0){
        $data = array(
            'name'   =>'用户资金记录',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        //处理show_page的分页数据
        $this->_set_cutpage_params($page_id,$page_size);

        if($uid > 0){
            $temp['where'] = array(
                'select' =>'id,amount,remarks,dateline,type,source,balance',
                'where'  =>array(
                    'uid'=>$uid
                ),
                'where_not_in'=>array('field'=>'type','value'=>array(3,4)),
                'order_by'=>'id desc'
            );

            //如果有限定type
            if($type_str){
                if(is_string($type_str) && strpos($type_str,',')){
                    $type_array = explode(',',$type_str);
                    $temp['where']['where_in'] = array(
                        'field'=>'type',
                        'value'=>$type_array
                    );
                }else{
                    if(is_numeric($type_str) && $type_str > 1){
                        $temp['where']['where']['type'] = $type_str;
                    }
                }
            }
            //验证月份
            if($month != ''){
                $temp['month_start'] = strtotime($month.'01 00:00:00');
                $temp['month_end']   = strtotime(date('Y-m-d',$temp['month_start']).' +1 months -1 days');
                $temp['where']['where']['dateline >='] = $temp['month_start'];
                $temp['where']['where']['dateline <='] = $temp['month_end'];
            }

            //验证起始时间
            if($start_time){
                $temp['where']['where']['dateline >=']=$start_time;
                if( ! $end_time) $end_time = time();
            }
            if($end_time){
                $temp['where']['where']['dateline <=']=$end_time;
            }

            $temp['data'] = $this->c->show_page(self::cash,$temp['where']);

            unset($temp['data']['links']);
            if($temp['data']['data']){
                $data['msg']    = 'ok!';
                $data['status'] = '10000';
                //处理type
                foreach($temp['data']['data'] as $key=>$val){
                    //获取投资的项目名称
                    if($val['type'] == 5){
                        $temp['data']['data'][$key]['remarks'] = '投资'.$this->_get_cash_log_borrow_subject($val['source']);
                    }
                    //填充没有remarks的
                    $temp['data']['data'][$key]['remarks'] = $this->_get_cash_log_remarks($temp['data']['data'][$key]['remarks'],$val['type']);
                    //获取支出收入类型中文名称
                    $temp['data']['data'][$key]['type']    = $this->_get_cash_log_type($val['type']);
                    //处理收入支出资金的 + -
                    if( $temp['data']['data'][$key]['type'] == '支出'){
                        $temp['data']['data'][$key]['amount'] = '-'. $temp['data']['data'][$key]['amount'];
                    }else{
                        $temp['data']['data'][$key]['amount'] = '+'. $temp['data']['data'][$key]['amount'];
                    }
                    //过滤source字段的显示
//                    unset($temp['data']['data'][$key]['source']);
                }

                $data['data'] = $temp['data'];
            }else{
	            $data['data'] = $temp['data'];
                $data['msg']    = '暂无相关信息!';
                $data['status'] = '10000';
            }
        }

        unset($temp);
        return $data;
    }

    public function get_user_limit_time_cash_total($uid=0,$start_time='',$end_time=''){
        $data = array(
            'name'   =>'用户特定时间段资金记录收支统计',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array(
                'income_total'=>0,
                'pay_total'=>0
            )
        );
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'where'=>array('uid'=>$uid),
                'select'=>'SUM(amount)',
                'where_in'=>array(
                    'field'=>'type',
                    'value'=>array(1,7)//array(1,4,7)
                )
            );
            //验证起始时间
            if($start_time){
                $temp['where']['where']['dateline >=']=$start_time;
            }
            if($end_time){
                $temp['where']['where']['dateline <=']=$end_time;
            }
            $data['data']['income_total'] = (float)$this->c->get_one(self::cash,$temp['where']);
//            $temp['where']['where_not_in'] = $temp['where']['where_in'];
            $temp['where']['where_not_in'] = array(1,3,4,7);
            unset($temp['where']['where_in']);
            $data['data']['pay_total'] = (float)$this->c->get_one(self::cash,$temp['where']);
            $data['status'] = '10000';
            $data['msg'] = 'ok';
        }


        unset($temp);
        return $data;
    }

    /**
     * 获取用户充值记录
     * @param int $uid
     * @param string $type
     * @param int $start_time
     * @param int $end_time
     * @param int $page_id
     * @param int $page_size
	 * @param int $time_limit  //季度条件  0 全部 1 当月 2 季度 3 半年 4 今年
     * @return array
     * 'recharge_no' => string 'R15070154801269' (length=15)
     *  'amount' => string '5000.00' (length=7)
     *  'remarks' => string '会员充值' (length=12)
     *  'add_time' => string '1435730918' (length=10)
     *  'status' => string '充值失败' (length=12)
     */
    public function get_user_recharge_list($uid=0,$type='',$start_time=0,$end_time=0,$time_limit = 0){
        $data = array(
            'name'   =>'用户充值记录',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        //处理show_page的分页数据
        //$this->_set_cutpage_params($page_id,$page_size);

        if($uid > 0){
            $temp['where'] = array(
                'select' =>'recharge_no,amount,type,remarks,add_time,status',
                'where'  =>array('uid'=>$uid),
                'order_by'=>'add_time DESC'
            );

            //验证type
            if($type){
                $temp['where']['where']['type']=$type;
            }

			switch ($time_limit) {
            case '1':
                $time = strtotime(date('Y-m-01',time()));
                break;
			case '2':
                $time = strtotime(date('Y-(m-2)-01',time()));
                break;
			case '3':
                $time = strtotime(date('Y-(m-5)-01',time()));
                break;
			case '4':
                $time = strtotime(date('Y-01-01',time()));
                break;
            default:
                $time = '';
                break;
			 }
			 if($time!=''){
				$temp['where']['where']['add_time >=']=$time;
				$temp['where']['where']['add_time <=']=time();
			 }
            //验证起始时间
            if($start_time){
                $temp['where']['where']['add_time >=']=$start_time;
                if( ! $end_time) $end_time = time();
            }
            if($end_time){
                $temp['where']['where']['add_time <=']=$end_time;
            }

            $temp['data'] = $this->c->show_page(self::recharge,$temp['where'],"",0,7);
            unset($temp['data']['links']);

            if($temp['data']['data']){
                foreach($temp['data']['data'] as $key=>$val){
                    $temp['data']['data'][$key]['status'] = $this->_get_recharge_status($val['status'],$val['type']);
                   // unset($temp['data']['data'][$key]['type']);
                }
                $data['data']   = $temp['data'];
                $data['status'] = '10000';
                $data['msg']    = 'ok!';
            }else{
	            $data['data'] = '';
                $data['status'] = '10000';
                $data['msg']    = '暂无相关数据!';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取充值记录详情
     * @param string $recharge_no
     * @return array
     * 'id' => string '1878' (length=4)
     *  'recharge_no' => string 'R15070118811888' (length=15)
     *  'uid' => string '155' (length=3)
     *  'type' => string '凯塔充值' (length=12)
     *  'bank' => string '0' (length=1)
     *  'amount' => string '5000.00' (length=7)
     *  'source' => string '' (length=0)
     *  'remarks' => string '会员充值' (length=12)
     *  'operator' => string '' (length=0)
     *  'add_time' => string '1435731073' (length=10)
     *  'confirm_time' => string '0' (length=1)
     *  'status' => string '充值失败' (length=12)
     */
    public function get_user_recharge_info($recharge_no=''){
        $data = array(
            'name'   =>'用户充值记录具体信息',
            'status' =>'10001',
            'msg'    =>'充值单号为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        if($recharge_no){
            $temp['recharge_info'] = $this->c->get_row(self::recharge,array('where'=>array('recharge_no'=>$recharge_no)));
            if($temp['recharge_info']){
                $data['status']                  = '10000';
                $data['msg']                     = 'ok!';
                $temp['recharge_info']['status'] = $this->_get_recharge_status($temp['recharge_info']['status'],$temp['recharge_info']['type']);
                $temp['recharge_info']['type']   = $this->_get_recharge_type($temp['recharge_info']['type']);
                $data['data']                    = $temp['recharge_info'];
            }else{
                $data['msg'] = '没有相关信息!';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 提现  //10002 未登陆  10003 未设置资金密码  10004 余额不足 10005 未实名
     * @param int $uid
     * @param int $amount
     * @param string $card_no
     * @param string $security
     * @param string $authcode
     * @param int $charge
     * @return array
     *  balance=>0
     */
    public function user_transfer($uid=0,$amount=0,$card_no='',$security='',$authcode='',$charge=2){
        $data = array(
            'name'   =>'提现',
            'status' =>'10001',
            'msg'    =>'您提交的数据有误,请重试!',
            'sign'   =>'',
            'data'   =>array('balance'=>0)
        );
        if(!$uid){
            $data['msg']    = '用户未登陆!';
            $data['status'] = '10002';
            return $data;
        }

        if(!is_numeric($amount)){
            $data['msg'] = '请输入数字类型的提现金额!';
            return $data;
        }

        if($amount < self::TRANSFE_MIN){
            $data['msg'] = '最低提现金额'.self::TRANSFE_MIN.'元!';
            return $data;
        }
        if(!$card_no){
            $data['msg'] = '卡号信息为空!';
            return $data;
        }
        if(!$security){
            $data['msg'] = '交易密码为空!';
            return $data;
        }
//        if(!$authcode){
//            $data['msg'] = '短信验证码为空!';
//            return $data;
//        }

        //查询该用户是否存在
        $temp['user_info'] = $this->_get_userinfo($uid);
        if(!$temp['user_info']){
            $data['msg'] = '用户信息不存在!';
            return $data;
        }
        if(!$temp['user_info']['security']){
            $data['msg']    = '交易密码未设置!';
            $data['status'] = '10003';
            return $data;
        }
        //查询提现卡号信息是否存在
        $temp['card_info'] = $this->_get_card_info($uid);
        if(!$temp['card_info']){
            $data['msg'] = '银行账户信息不存在!';
            return $data;
        }
        //获取余额 验证余额
        $temp['balance']  = $this->get_user_balance($uid);
        $temp['balance']  = $temp['balance']['data']['balance'];
        if($amount > $temp['balance']){
            $data['msg']    = '余额不足!';
            $data['status'] = '10004';
            return $data;
        }
        //验证手机验证码
        $temp['is_check'] = $this->send->validation_authcode($temp['user_info']['mobile'], $authcode, 'transfer');
        if($temp['is_check']['status']=='10001'){
			$data['status'] = '10006';
            $data['msg'] = $temp['is_check']['msg'];
           return $data;
        }

        //验证资金密码
        $temp['security'] = $this->c->password($security, $temp['user_info']['hash']);
        if($temp['security'] != $temp['user_info']['security']){
			 $data['status'] = '10005';
            $data['msg'] = '交易密码有误!';
            return $data;
        }
        //开启事务
        $this->db->trans_start();

        $temp['transaction_no'] = $this->c->transaction_no(self::transfer, 'transaction_no');

        $temp['data'] = array(
            'transaction_no' => $temp['transaction_no'],
            'uid'            => $uid,
            'card_no'        => $card_no,
            'amount'         => $charge?round($amount - $charge,2):$amount,//提现手续费处理
            'charge'         => $charge,//提现手续费处理
            'real_name'      => $temp['card_info']['real_name'],
            'bank_name'      => $temp['card_info']['bank_name'],
            'account'        => $temp['card_info']['account'],
            'remarks'        => '会员提现',
            'add_time'       => time(),
        );
        $this->c->insert(self::transfer, $temp['data']);
        //添加资金记录
        $temp['data'] = array(
            'uid'      => $uid,
            'type'     => 3,
            'amount'   => $amount,
            'balance'  => round($temp['balance'] - $amount, 2),
            'source'   => $temp['transaction_no'],
            'remarks'  => '会员提现',
            'dateline' => time()
        );
        $this->c->insert(self::cash, $temp['data']);

        $this->db->trans_complete();
        $query = $this->db->trans_status();

        if( ! empty($query)){
            $data['status']          = '10000';
            $data['msg']             = '您的提现申请已经提交请等待审核!';
            $data['data']['balance'] = $temp['data']['balance'];
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户提现记录
     * @param int $uid
     * @param string $type
     * @param int $start_time
     * @param int $end_time
     * @param int $page_id
     * @param int $page_size
     * @return array
     * 'transaction_no' => string 'T15081390250642' (length=15)
     *  'amount' => string '1.00' (length=4)
     *  'charge' => string '2.00' (length=4)
     *  'account' => string '6214********6219' (length=16)
     *  'add_time' => string '1439459394' (length=10)
     *  'status' => string '提现成功' (length=12)
     */
    public function get_user_transfer_list($uid=0,$type='',$start_time=0,$end_time=0,$time_limit = 0){
        $data = array(
            'name'   =>'用户提现记录',
            'status' =>'10001',
            'msg'    =>'用户uid为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();
		
		switch ($time_limit) {
            case '1':
                $time = strtotime(date('Y-m-01',time()));
                break;
			case '2':
                $time = strtotime(date('Y-(m-2)-01',time()));
                break;
			case '3':
                $time = strtotime(date('Y-(m-5)-01',time()));
                break;
			case '4':
                $time = strtotime(date('Y-01-01',time()));
                break;
            default:
                $time = '';
                break;
			 }
			 

        //处理show_page的分页数据
       // $this->_set_cutpage_params($page_id,$page_size);

        if($uid > 0){
            $temp['where'] = array(
                'select' =>'transaction_no,amount,charge,account,add_time,status,remarks',
                'where'  =>array('uid'=>$uid),
                'order_by'=>'add_time DESC'
            );
			//时间类型
			if($time!=''){
				$temp['where']['where']['add_time >=']=$time;
				$temp['where']['where']['add_time <=']=time();
			 }
            //验证type
            if($type !== ''){
                $temp['where']['where']['status']=$type;
            }
            //验证起始时间
            if($start_time){
                $temp['where']['where']['add_time >=']=$start_time;
                if( ! $end_time) $end_time = time();
            }
            if($end_time){
                $temp['where']['where']['add_time <=']=$end_time;
            }

            $temp['data'] = $this->c->show_page(self::transfer,$temp['where'],"",0,7);
            unset($temp['data']['links']);

            if($temp['data']['data']){
                foreach($temp['data']['data'] as $key=>$val){
                    $temp['data']['data'][$key]['status']  = $this->_get_transfer_status($val['status']);
                    $temp['data']['data'][$key]['account'] = $this->secret($val['account'],strlen($val['account'])-8);
                }
                $data['data']   = $temp['data'];
                $data['status'] = '10000';
                $data['msg']    = 'ok!';
            }else{
	            $data['data'] = $temp['data'];
                $data['status'] = '10000';
                $data['msg']    = '暂无相关数据!';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户提现记录具体信息
     * @param string $transaction_no
     * @return array
     * 'id' => string '78' (length=2)
     *   'transaction_no' => string 'T15081390250642' (length=15)
     *  'uid' => string '58' (length=2)
     *  'amount' => string '1.00' (length=4)
     *  'charge' => string '2.00' (length=4)
     *  'real_name' => string '陈*祚' (length=7)
     *  'bank_name' => string '招商银行' (length=12)
     *  'account' => string '6214********6219' (length=16)
     *  'remarks' => string '会员提现' (length=12)
     *  'operator' => string 'haha' (length=4)
     *  'add_time' => string '1439459394' (length=10)
     *  'confirm_time' => string '1439459626' (length=10)
     *  'status' => string '提现成功' (length=12)
     */
    public function get_user_transfer_info($transaction_no=''){
        $data = array(
            'name'   =>'用户提现记录具体信息',
            'status' =>'10001',
            'msg'    =>'提现单号为空!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        if($transaction_no){
            $temp['transfer_info'] = $this->c->get_row(self::transfer,array(
                'where'  =>array('transaction_no'=>$transaction_no),
                'select' =>'id,transaction_no,uid,amount,charge,real_name,bank_name,account,remarks,operator,add_time,confirm_time,status'
            ));
            if($temp['transfer_info']){
                $data['status'] = '10000';
                $data['msg']    = 'ok!';
                //获取状态
                $temp['transfer_info']['status']    = $this->_get_transfer_status($temp['transfer_info']['status']);
                //加密账号和实名信息
                $temp['transfer_info']['account']   = $this->secret($temp['transfer_info']['account'],strlen($temp['transfer_info']['account'])-8);
                $temp['real_name_len']              = mb_strlen($temp['transfer_info']['real_name']);
                $temp['transfer_info']['real_name'] = $this->_secret($temp['transfer_info']['real_name'],2,1);
                $data['data']                       = $temp['transfer_info'];
            }else{
                $data['msg'] = '没有相关信息!';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取全部用户投资总额列表
     * @param string $month 月份 201509
     * @param int $page_id
     * @param int $page_size
     * @return array
     */
    public function get_user_invest_total_list($month='',$page_id=0,$page_size=0){
        $data = array(
            'name'   =>'用户投资总额列表',
            'status' =>'10001',
            'msg'    =>'您提交的数据有误,请重试!',
            'sign'   =>'',
            'data'   =>array()
        );
        $temp    = array();

        //处理show_page的分页数据
        $this->_set_cutpage_params($page_id,$page_size);

        $temp['where'] = array(
            'select'   => 'SUM('.join_field('amount', self::payment).') as invest_total,'.join_field('mobile', self::user),
            'where'    => array(
                join_field('type', self::payment)   => 1,
                join_field('status', self::payment) => 1
            ),
            'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
            'join'     => array(
                array(
                    'table' => self::borrow,
                    'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                ),
                array(
                    'table' => self::user,
                    'where' => join_field('uid', self::payment).' = '.join_field('uid', self::user)
                )
            ),
            'order_by'=>'SUM('.join_field('amount', self::payment).') DESC',
            'group_by'=>join_field('uid', self::payment)
        );

        if($month){
            $temp['month_start'] = strtotime($month.'01 00:00:00');
            $temp['month_end']   = strtotime(date('Y-m-d',$temp['month_start']).' +1 months -1 days');
            $temp['where']['where'][join_field('dateline >=',self::payment)] = $temp['month_start'];
            $temp['where']['where'][join_field('dateline <=',self::payment)] = $temp['month_end'];
        }

        $temp['data'] = $this->c->show_page(self::payment,$temp['where']);
        unset($temp['data']['links']);
        if($temp['data']['data']){
            foreach($temp['data']['data'] as $key=>$val){
                $temp['data']['data'][$key]['mobile'] = $this->_secret($val['mobile'],4,4);
            }
            $data['data']   = $temp['data'];
            $data['status'] = '10000';
            $data['msg']    = 'ok!';
        }else{
	        $data['data'] = $temp['data'];
            $data['status'] = '10000';
            $data['msg']    = '暂无相关数据!';
        }

        unset($temp);
        return $data;
    }

/********************************************************聚保宝***************************************************************************************/


    /**
     * 聚保宝累计投资
     * @param string $type_code
     * @return array
     */
    public function jbb_all_invest($type_code=''){
        $data = array(
            'name'   =>'聚保宝累计投资总额',
            'status' =>'10001',
            'msg'    =>'暂无相关数据!',
            'sign'   =>'',
            'data'   =>array(
				'jbb_all_invest'=>0
			)
        );
        $temp = array();
		$temp['where'] =  array(
				'select'   =>'SUM(amount) as jbb_all_invest',
                'where'    =>array('product_type'=>$type_code),
                'group_by' =>'product_type'
			);
		$temp['data'] = $this->c->get_one(self::payment_jbb,$temp['where']);
		if(!empty($temp['data'])){
			$data = array(
				'status' =>'10000',
				'msg'    =>'ok!',
				'data'   =>array(
					'jbb_all_invest'=>$temp['data']
				)
			);
		}else{
			$data = array(
				'status' =>'10000',
				'msg'    =>'ok!',
				'data'   =>array(
					'jbb_all_invest'=>0
				)
			);
		}
        unset($temp);
        return $data;
    }


    /**
     * 聚保宝累计赚取
     * @param string $type_code
     * @return array
     */
    public function jbb_all_Earn($type_code=''){
		$temp = array();
        $data = array(
            'name'   =>'聚保宝累计赚取',
            'status' =>'10001',
            'msg'    =>'暂无相关数据!',
            'sign'   =>''
        );
		$temp['all_invest'] = $this->jbb_all_invest($type_code);
		$temp['jbb'] = $this->jbb($type_code);	
		$temp['data'] =$temp['all_invest']['data']['jbb_all_invest']*((1+$temp['jbb']['ave_rate']/100/360)*(pow((1+$temp['jbb']['ave_rate']/100/360),$temp['jbb']['time_limit'])-1));
		if($temp['data']>=0	){
			$data = array(
            'status' =>'10000',
            'msg'    =>'ok!',
			'data'   =>array(
				'jbb_all_Earn' => $temp['data']
			)
        );
		}
        unset($temp);
        return $data;
    }



	/**
	 * 聚保宝项目
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	private function jbb($type_code = ''){
		$data = $temp = array();
		if( ! empty($type_code)){
			$temp['where'] = array(
				'select'   => '*',
				'where'    => array('type_code' => $type_code)
			);
			$data = $this->c->get_row(self::jbb, $temp['where']);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝投资详情
	 *
	 * @access public
	 * @param  string $type_code  投资编号
	 * @return $data  
	 */
	public function jbb_jbb_details($type_code = ''){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
                'select' =>join_field('*',self::borrow),
                'where'  =>array(
					join_field('type_code',self::payment) => $type_code
                ),
				'group_by' =>join_field('borrow_no',self::payment),
                'join'=>array(
                    'table'=>self::payment,
                    'where'=>join_field('borrow_no',self::payment).' = '.join_field('borrow_no',self::borrow)
                )
            );
		$temp['data'] = $this->c->get_all(self::borrow,$temp['where']);
		if(!empty($temp['data'])){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => $temp['data']
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝加入总金额（元）
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @return $data  
	 */
	public function jbb_add_amount($uid=0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
			'select' => 'SUM(`amount`)',
			'where' => array('uid' => $uid,'status'=>1)
			);
		$temp['data'] = $this->c->get_one(self::payment_jbb,$temp['where']);
		if(!empty($temp['data'])){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'add_amount' => $temp['data']
				)
			);
		}else{
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'add_amount' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝购买笔数
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @return $data  
	 */
	public function jbb_buy_nums($uid=0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
			'select' => 'count(*)',
			'where' => array('uid' => $uid,'status'=>1)
			);
		$temp['data'] = $this->c->get_one(self::payment_jbb,$temp['where']);
		if(!empty($temp['data'])){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'buy_nums' => $temp['data']
				)
			);
		}else{
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'buy_nums' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝累计提取收益
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @return $data  
	 */
	public function jbb_cumulative_yield($uid=0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
			'select' => 'sum(gain)',
			'where' => array('uid' => $uid)
			);
		$temp['data'] = $this->c->get_one(self::payment_jbb,$temp['where']);
		if(!empty($temp['data'])){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'cumulative_yield' => $temp['data']
				)
			);
		}else{
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'cumulative_yield' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝可领取收益
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @return $data  
	 */
	public function jbb_receive($uid=0 , $id=0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
                'select' =>join_field('*',self::payment_jbb).','.join_field('rate',self::jbb_dtl).','.join_field('closeday',self::jbb).','.join_field('allawexit',self::jbb).','.join_field('intervaldays',self::jbb).','.join_field('isrepeat',self::jbb).','.join_field('service_charge',self::jbb),
                'where'  =>array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('status',self::payment_jbb)=>1
                ),
                'join'=>array(
                    array(
						'table'=>self::jbb_dtl,
						'where'=>join_field('type_code',self::jbb_dtl).' = '.join_field('product_type',self::payment_jbb).' and '.join_field('periods_number',self::jbb_dtl).' = '.join_field('number_periods',self::payment_jbb)
					),
					array(
						'table'=>self::jbb,
						'where'=>join_field('type_code',self::jbb).' = '.join_field('product_type',self::payment_jbb)
					),
                )
            );
		if($uid != 0 && $id != 0){
			$temp['where']['where']  = 
				array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('id',self::payment_jbb)=>$id,
					join_field('status',self::payment_jbb)=>1
                );
		}
		$temp['data'] = $this->c->get_all(self::payment_jbb,$temp['where']);
		$receive=0;
		$service = 0;
		foreach($temp['data'] as $k => $v){		
			
			$rate=$v['rate'];
			$amount=$v['amount'];			
			if($v['isrepeat']==0){
			if((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])>=$v['intervaldays']&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)<=$v['closeday']){
				$days=floor((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])/$v['intervaldays'])*$v['intervaldays'];
			}elseif((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])>$v['intervaldays']&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday']){
				$days=$v['closeday']-$v['receive_days'];
			}else{
				$days=0;	
			}
			$receive=$receive+round(jbb_no_product_amount($days,$rate,$amount),2);
			}else{
			$days=($v['isrepeat']==1&&$v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'];
			$receive=$receive+round(jbb_product_amount($days,$rate,$amount),2);
			}
			$service_charge = $v['service_charge'];
			$service=$service+round($receive*$service_charge,2);
		}
		
		if($uid!=0){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'receive' => $receive,
					'service' => $service
				)
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝匹配标数
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @return $data  
	 */
	public function jbb_mate_nums($uid=0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
                'select' =>join_field('borrow_no',self::payment),
                'where'  =>array(
					join_field('status',self::payment_jbb)=>1,
					join_field('uid',self::payment_jbb)=>$uid,
					join_field('type',self::payment)=>1
                ),
				'group_by' =>join_field('borrow_no',self::payment),
                'join'=>array(
                    'table'=>self::payment_jbb,
                    'where'=>join_field('type_code',self::payment).' = concat('.join_field('product_type',self::payment_jbb).','.join_field('number_periods',self::payment_jbb).')'
                )
            );
		$temp['data'] = $this->c->get_all(self::payment,$temp['where']);
		$num = count($temp['data']);
		if(!empty($temp['data'])){
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'mate_nums' => $num
				)
			);
		}else{
			$data = array(
				'status' => '10000',
				'msg' => 'ok!',
				'data' => array(
					'mate_nums' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝提取收益
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @param  integer $id    购买id
	 * @return $data  
	 */
	public function jbb_sub_receive($uid = 0 , $id = 0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		$temp['where'] = array(
                'select' =>join_field('*',self::payment_jbb).','.join_field('rate',self::jbb_dtl).','.join_field('closeday',self::jbb).','.join_field('allawexit',self::jbb).','.join_field('intervaldays',self::jbb).','.join_field('isrepeat',self::jbb).','.join_field('service_charge',self::jbb),
                'where'  =>array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('status',self::payment_jbb)=>1
                ),
                'join'=>array(
                    array(
						'table'=>self::jbb_dtl,
						'where'=>join_field('type_code',self::jbb_dtl).' = '.join_field('product_type',self::payment_jbb).' and '.join_field('periods_number',self::jbb_dtl).' = '.join_field('number_periods',self::payment_jbb)
					),
					array(
						'table'=>self::jbb,
						'where'=>join_field('type_code',self::jbb).' = '.join_field('product_type',self::payment_jbb)
					),
                )
            );
		if($uid != 0 && $id != 0){
			$temp['where']['where']  = 
				array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('id',self::payment_jbb)=>$id,
					join_field('status',self::payment_jbb)=>1
                );
		}
		$temp['data'] = $this->c->get_all(self::payment_jbb,$temp['where']);
		$receive=0;
		$receives=0;
		$service=0;
		$services = 0;
		foreach($temp['data'] as $k => $v){		
			
			$rate=$v['rate'];
			$amount=$v['amount'];	
			if($v['isrepeat']==0){
			if((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])>=$v['intervaldays']&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)<=$v['closeday']){
				$days=floor((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])/$v['intervaldays'])*$v['intervaldays'];
			}elseif((ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'])>$v['intervaldays']&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday']){
				$days=$v['closeday']-$v['receive_days'];
			}else{
				$days=0;	
			}
			$receive=round(jbb_no_product_amount($days,$rate,$amount),2);
			$receives=$receives+round(jbb_product_amount($days,$rate,$amount),2);//得到总利息
			}else{
			$days=($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'];
			$receive=round(jbb_product_amount($days,$rate,$amount),2);//得到利息
			$receives=$receives+round(jbb_product_amount($days,$rate,$amount),2);//得到总利息
			}		
			$balance = $this->get_user_balance($uid);
			$balance = $balance['data']['balance'];
			$service = $v['service_charge'];
			$services = $services+round($service*$receive,2);
			
				$this->db->trans_start();	
				//转账过程
				//生成聚保宝记录
				$temp['jbb']  = array(
					'receive_days' => $v['receive_days']+$days,
					'service_amount' => $v['service_amount']+round($service*$receive,2),
					'gain'         => $v['gain']+$receive
					);
				$temp['jbb_where'] = array(
					'where' => array('order_code' => $v['order_code'])
					);
				$this->c->update(self::payment_jbb, $temp['jbb_where'], $temp['jbb']);	
				//生成资金记录
				$temp['cash']  = array(
					'uid'		=> $v['uid'],
					'type'		=> 20,
					'amount'	=>$receive,
					'balance'	=>$balance+$receive-round($service*$receive,2),
					'source'	=>$v['order_code'],
					'remarks'	=>'利息提取',
					'dateline'	=>time()
					);
				$this->c->insert(self::cash, $temp['cash']);
				//生成结算记录
				if($id!=0){
					$temp['query_recharge_jbb']  = array(
						'recharge_no'=> $this->c->transaction_no(self::recharge_jbb, 'recharge_no'),
						'uid'		 => $v['uid'],
						'type'		 => 0,
						'amount'	 => $receive,
						'source'	 => $v['order_code'],
						'remarks'	 => '聚保宝利息提取',
						'add_time'	 => time(),
						'status'     => 0
						);
					$this->c->insert(self::recharge_jbb, $temp['query_recharge_jbb']);
				}
				$this->db->trans_complete();	
		}
		if($id==0){
					$temp['query_recharge_jbb']  = array(
						'recharge_no'=> $this->c->transaction_no(self::recharge_jbb, 'recharge_no'),
						'uid'		 => $uid,
						'type'		 => 0,
						'amount'	 => $receives-$services,
						'source'	 => '',
						'remarks'	 => '聚保宝一次性利息提取',
						'add_time'	 => time(),
						'status'     => 0
						);
					$this->c->insert(self::recharge_jbb, $temp['query_recharge_jbb']);
				}
		$query = $this->db->trans_status();
		if(!empty($query)){
			$data = array(
				'status' => '10000',
				'msg'	 => '提取收益成功!',
				'url'  	 => site_url('user/user/jbb'),
				'data'   => array(
					'$receive'	=>$receive,
					'service'   =>$service
				)
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝提取收益
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @param  integer $id    购买id
	 * @return $data  
	 */
	public function jbb_out($uid = 0 , $id = 0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		if($uid == 0 || $id == 0){
			return $data;
		}
		$temp['where'] = array(
                'select' =>join_field('*',self::payment_jbb).','.join_field('rate',self::jbb_dtl).','.join_field('closeday',self::jbb).','.join_field('allawexit',self::jbb).','.join_field('counter_Fee',self::jbb).','.join_field('service_charge',self::jbb).','.join_field('time_limit',self::jbb),
                'where'  =>array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('id',self::payment_jbb)=>$id,
					join_field('status',self::payment_jbb)=>1
                ),
                'join'=>array(
                    array(
						'table'=>self::jbb_dtl,
						'where'=>join_field('type_code',self::jbb_dtl).' = '.join_field('product_type',self::payment_jbb).' and '.join_field('periods_number',self::jbb_dtl).' = '.join_field('number_periods',self::payment_jbb)
					),
					array(
						'table'=>self::jbb,
						'where'=>join_field('type_code',self::jbb).' = '.join_field('product_type',self::payment_jbb)
					),
                )
            );
		$temp['data'] = $this->c->get_all(self::payment_jbb,$temp['where']);
		$query='';
		$receive=0;//利息
		$counter_Fee = 0;//手续费
		foreach($temp['data'] as $k => $v){		
			$days=($v['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)>$v['closeday'])?($v['closeday']-$v['receive_days']):ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24)-$v['receive_days'];
			$rate=$v['rate'];
			$amount=$v['amount'];			
			$receive=round(jbb_product_amount($days,$rate,$amount),2);//得到利息
			$day = ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24);
			if($day<$v['time_limit']){
				$counter_Fee = $v['counter_Fee']*$v['amount'];
			}
				//生成聚保宝退出记录
				$temp['jbb']  = array(
					'exit_time' => time(),//退出时间
					'exit_days' => ceil((strtotime(date('Y-m-d'))-$v['interest_day'])/3600/24),//退出持有天数
					'transfer_fee' =>  $counter_Fee,//手续费
					'service_amount' => $v['service_amount']+$v['service_charge']*$receive,//服务费
					'expected_amount' => round(jbb_product_amount($day,$rate,$amount),2),//预计收益
					'real_amount' => round(jbb_product_amount($days,$rate,$amount),2)+$v['gain'],//真实收益
					'interest_amount' =>  $receive+$v['amount']+$v['gain']- $counter_Fee-$v['service_charge']*$v['amount'],//本息收益
					'status' => 2
					);
				$temp['jbb_where'] = array(
					'where' => array('order_code' => $v['order_code'])
					);
				$query = $this->c->update(self::payment_jbb, $temp['jbb_where'], $temp['jbb']);			
		}
		if(!empty($query)){
			$data = array(
				'status' => '10000',
				'msg'	 => '申请退出成功!',
				'url'  	 => site_url('user/user/jbb_line')
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝手续费
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @param  integer $id    购买id
	 * @return $data  
	 */
	public function jbb_poundage($uid = 0 , $id = 0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		if($uid == 0 || $id == 0){
			return $data;
		}
		$temp['where'] = array(
                'select' =>join_field('*',self::payment_jbb).','.join_field('rate',self::jbb_dtl).','.join_field('closeday',self::jbb).','.join_field('allawexit',self::jbb).','.join_field('counter_Fee',self::jbb).','.join_field('service_charge',self::jbb).','.join_field('time_limit',self::jbb).','.join_field('service_charge',self::jbb),
                'where'  =>array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('id',self::payment_jbb)=>$id,
					join_field('status',self::payment_jbb)=>1
                ),
                'join'=>array(
                    array(
						'table'=>self::jbb_dtl,
						'where'=>join_field('type_code',self::jbb_dtl).' = '.join_field('product_type',self::payment_jbb).' and '.join_field('periods_number',self::jbb_dtl).' = '.join_field('number_periods',self::payment_jbb)
					),
					array(
						'table'=>self::jbb,
						'where'=>join_field('type_code',self::jbb).' = '.join_field('product_type',self::payment_jbb)
					),
                )
            );
		$temp['data'] = $this->c->get_row(self::payment_jbb,$temp['where']);
		$Fee = 0;
		$day=0;
		$service_charge=0;
		if(!empty($temp['data'])){
			$day = ceil((strtotime(date('Y-m-d'))-$temp['data']['interest_day'])/3600/24);
			if($day<$temp['data']['time_limit']){
				$Fee = round($temp['data']['counter_Fee']*$temp['data']['amount'],2);
			}
			$days=($temp['data']['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$temp['data']['interest_day'])/3600/24)>$temp['data']['closeday'])?($temp['data']['closeday']-$temp['data']['receive_days']):ceil((strtotime(date('Y-m-d'))-$temp['data']['interest_day'])/3600/24)-$temp['data']['receive_days'];
			$rate=$temp['data']['rate'];
			$amount=$temp['data']['amount'];	
			$receive=round(jbb_product_amount($days,$rate,$amount),2);//得到利息
			$service_charge = round($receive*$temp['data']['service_charge'],2);
		}
		$data = array(
			'status'=>'10000',
			'msg'=>'ok!',
			'data' => array(
				'fee' => $Fee,
				'service' => $service_charge,
				'day' => $day
			)
		);
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝取消退出
	 *
	 * @access public
	 * @param  integer $uid    会员ID
	 * @param  integer $id    购买id
	 * @return $data  
	 */
	public function jbb_off($uid = 0 , $id = 0){
		$data = array('status'=>'10001','msg'=>'数据有误，请稍候尝试!');
        $temp = array();
		if($uid == 0 || $id == 0){
			return $data;
		}
		$temp['where'] = array(
                'select' =>join_field('*',self::payment_jbb).','.join_field('rate',self::jbb_dtl).','.join_field('closeday',self::jbb).','.join_field('allawexit',self::jbb).','.join_field('counter_Fee',self::jbb).','.join_field('service_charge',self::jbb).','.join_field('time_limit',self::jbb).','.join_field('service_charge',self::jbb),
                'where'  =>array(
                    join_field('uid',self::payment_jbb)=>$uid,
					join_field('id',self::payment_jbb)=>$id,
					join_field('status',self::payment_jbb)=>2
                ),
                'join'=>array(
                    array(
						'table'=>self::jbb_dtl,
						'where'=>join_field('type_code',self::jbb_dtl).' = '.join_field('product_type',self::payment_jbb).' and '.join_field('periods_number',self::jbb_dtl).' = '.join_field('number_periods',self::payment_jbb)
					),
					array(
						'table'=>self::jbb,
						'where'=>join_field('type_code',self::jbb).' = '.join_field('product_type',self::payment_jbb)
					),
                )
            );
		$temp['data'] = $this->c->get_row(self::payment_jbb,$temp['where']);
		$Fee = 0;
		$day=0;
		$service_charge=0;
		if(!empty($temp['data'])){
			$days=($temp['data']['allawexit']==0&&ceil((strtotime(date('Y-m-d'))-$temp['data']['interest_day'])/3600/24)>$temp['data']['closeday'])?($temp['data']['closeday']-$temp['data']['receive_days']):ceil((strtotime(date('Y-m-d'))-$temp['data']['interest_day'])/3600/24)-$temp['data']['receive_days'];
			$rate=$temp['data']['rate'];
			$amount=$temp['data']['amount'];	
			$receive=round(jbb_product_amount($days,$rate,$amount),2);//得到利息
			$service_charge = round($receive*$temp['data']['service_charge'],2);
			$temp['jbb']  = array(
					'exit_time' => '',//退出时间
					'exit_days' => '',//退出持有天数
					'transfer_fee' =>  '',//手续费
					'service_amount' => $temp['data']['service_amount']-$service_charge,//服务费
					'expected_amount' => '',//预计收益
					'real_amount' => '',//真实收益
					'interest_amount' =>  '',//本息收益
					'status' => 1
					);
			$temp['jbb_where'] = array(
				'where' => array('order_code' => $temp['data']['order_code'])
				);
			$query = $this->c->update(self::payment_jbb, $temp['jbb_where'], $temp['jbb']);
			if(!empty($query)){
				$data = array(
				'status' => '10000',
				'msg'	 => '取消成功!',
				'url'  	 => site_url('user/user/jbb')
				);
			}
		}
		unset($temp);
		return $data;
	}
/********************************************************聚保宝***************************************************************************************/

    /***************************************全网资金统计相关*********************************************************/
    /**
     * 获取全网利息总额
     * @param int $category
     * @return float|int
     */
    public function get_project_interest_total($category=0){
        $temp = array();
        $data = array('name'=>'项目利息总额','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        $temp['amount'] = 0;
        $temp['where'] = array('select' => 'amount,months,rate,mode,repay','where'=>array('status >'=>1));

        if( ! empty($category))$temp['where']['where']['productcategory']=$category;

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data'])){
            foreach($temp['data'] as $k => $v){
                $temp['amount'] += $this->get_project_interest($v['amount'],$v['rate'],$v['months'],$v['mode']);//计算公式
            }
            $data['status']           = '10000';
            $data['msg']              = 'ok';
            $data['data']['interest'] =  $temp['amount'];
        }else{
            $data['status']           = '10000';
            $data['msg']              = 'ok';
            $data['data']['interest'] =  0;
        }

        unset($temp);
        return $data;
    }
    /***************************************全网资金统计相关*********************************************************/


    public function get_user_month_invest_interest($uid=0,$month=6){
        $data = array('name'=>'最近n个月的投资收益记录列表','status'=>'10001','msg'=>'ok','data'=>array('month'=>'', 'invest' => '', 'interest' =>''));
        $temp = array();

        if($uid > 0){
            if($month <= 0){
                $data['msg'] = '月份为空!';
                return $data;
            }
            $temp['6m_data'] = array(
                'month'=>'',
                'invest'=>'',
                'interest'=>''
            );
            for($i=5;$i>=0;$i--){
                $temp['start_time'] = strtotime(date('Y-m-01',strtotime('-'.$i.' month')).' 00:00:00');
                $temp['end_time'] = strtotime(date('Y-m-t',strtotime('-'.$i.' month')).' 23:59:59');
                $temp['6m_data']['month'][] = date('Y年m月',$temp['start_time']);
                $temp['invest'] = $this->get_user_invest_total($uid,0,$temp['start_time'],$temp['end_time']);
                $temp['6m_data']['invest'][] = $temp['invest'];
                $temp['interest'] = $this->get_user_receive_principal_interest($uid,$temp['start_time'],$temp['end_time']);
                $temp['6m_data']['interest'][] = $temp['interest']['receive_interest'];
            }
            $data['data'] = $temp['6m_data'];
            $data['msg'] = 'ok!';
            $data['status'] = '10000!';
        }else{
            $data['msg'] = '用户uid为空!';
        }
        unset($temp);
        return $data;
    }

    /***************************************用户资金统计相关方法 涉及的数据表 borrow payment transfer*********************************************************/
    /**
     * 用户投资总额
     * @param int $uid
     * @param int $category
     * @param int $start_time
     * @param int $end_time
     * @return float|int
     */
    public function get_user_invest_total($uid=0,$category=0,$start_time=0,$end_time=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(
                    join_field('uid', self::payment)    => $uid,
                    join_field('type', self::payment)   => 1,
                    join_field('status', self::payment) => 1
                ),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array(
                    'table' => self::borrow,
                    'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                )
            );

            if($category){
                $temp['where']['where'][join_field('productcategory', self::borrow)]=$category;
            }
            //验证起始时间
            if($start_time){
                $temp['where']['where']['dateline >=']=$start_time;
                if( ! $end_time) $end_time = time();
            }
            if($end_time){
                $temp['where']['where']['dateline <=']=$end_time;
            }

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 查询 borrow_payment 表  获得 已收本金 和利息
     * 累计收益  已还本金
     * @param int $uid
     * @param int $start_time
     * @param int $end_time
     * @return array
     */
    public function get_user_receive_principal_interest($uid=0,$start_time=0,$end_time=0){
        $rs = array('receive_principal'=>0,'receive_interest'=>0);
        if($uid > 0){
            $borrow = $this->c->get_all(self::payment,array(
                'select'   =>'borrow_no,SUM(amount) as amounts',
                'where'    =>array('uid'=>$uid,'type'=>1),
                'group_by' =>'borrow_no'
            ));

            if( ! empty($borrow)){


                foreach ($borrow as $key => $value) {
                    $temp['where'] = array(
                        'select' =>'SUM(amount)',
                        'where'  =>array('uid'=>$uid,'type'=>3,'borrow_no'=>$value['borrow_no'])
                    );
                    //验证起始时间
                    if($start_time){
                        $temp['where']['where']['dateline >=']=$start_time;
                        if( ! $end_time) $end_time = time();
                    }
                    if($end_time){
                        $temp['where']['where']['dateline <=']=$end_time;
                    }

                    $interest = $this->c->get_one(self::payment,$temp['where']);
                    if($interest > $value['amounts']){
                        $rs['receive_principal'] += $value['amounts'];
                        $rs['receive_interest']  += $interest - $value['amounts'];
                    }else{
                        $rs['receive_interest']  += $interest;
                    }
                }
            }
            $rs['receive_principal'] = round($rs['receive_principal'],2);
            $rs['receive_interest']  = round($rs['receive_interest'],2);
        }
        return $rs;
    }

    /**
     * 投资的冻结金额
     * @param int $uid
     * @return float|int
     */
    public function get_user_invest_freeze($uid=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(2,3)),
                'join'     => array(
                    'table' => self::borrow,
                    'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                )
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 提现的冻结金额
     * @param int $uid
     * @return float|int
     */
    public function get_user_transfer_freeze($uid=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)',
                'where'    => array('uid' => $uid, 'status' => 0)
            );

            $data = (float)$this->c->get_one(self::transfer, $temp['where']);
        }

        unset($temp);
        return $data;
    }



    /**
     * 聚保宝投资
     * @param int $uid
     * @return float|int
     */
    protected function jbb_all_amount($uid=0,$status = 0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)',
				'where'    => array(
					'uid' =>$uid,
					'status' => $status
				)
            );

            $data = (float)$this->c->get_one(self::payment_jbb, $temp['where']);
        }

        unset($temp);
        return $data;
    }




    /**
     * 用户全部项目总收益（预计和已收）
     * @param int $uid
     * @return int
     */
    public function get_user_interest_all($uid=0){
        $interest = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,dateline', self::payment).','
                    .join_field('subject,status,months,mode,receive', self::borrow).','
                    .join_field('amount', self::borrow).' as amounts,'.
                    join_field('category',self::category)
                    .',SUM('.join_field('amount', self::payment).') as amount',
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array(
                    array(
                        'table' => self::borrow,
                        'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                    ),
                    array(
                        'table' => self::category,
                        'where' => join_field('cat_id', self::category).' = '.join_field('productcategory', self::borrow)
                    )
                ),
                'group_by'=>join_field('borrow_no',self::payment)
            );

            $temp['invest_list'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['invest_list'])){

                foreach($temp['invest_list'] as $v){

                    $temp['project_interest'] = 0;

                    //查询 是否 已还款完成
                    $repay_interest = $this->c->get_one(self::payment,array(
                        'select' =>'SUM(amount)',
                        'where'  =>array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$uid)
                    ));
                    if($repay_interest && $repay_interest>$v['amount']){
                        $temp['project_interest'] = $repay_interest-$v['amount'];
                    }else{
                        switch($v['mode']){
                            case '1':
                                $temp['project_interest'] = $this->get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '2':
                                $temp['project_interest'] = $this->get_debx_all_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '3':
                                $temp['project_interest'] = $this->get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '4':
                                $temp['project_interest'] = $this->get_debj_all_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                        }
                    }
                    if( ! $temp['project_interest'])$temp['project_interest'] = 0;
                    $interest += $temp['project_interest'];
                }
            }
        }

        unset($temp);
        return round($interest,2);
    }
    /**************************************用户资金统计相关方法**********************************************************/

    /**************************************利息计算**********************************************************/
    /**
     * 计算项目预计收益（利息）
     * @param int $amount
     * @param int $rate
     * @param int $months
     * @param int $mode
     * @return float
     */
    public function get_project_interest($amount=0,$rate=0,$months=0,$mode=0){
        $interest = 0;
        if($amount > 0){
            switch($mode){
                case '1':
                    $interest = $this->get_ycxbx_interest($amount,$rate,$months);
                    break;
                case '2':
                    $interest = $this->get_debx_all_interest($amount,$rate,$months);
                    break;
                case '3':
                    $interest = $this->get_ycxbx_interest($amount,$rate,$months);
                    break;
                case '4':
                    $interest = $this->get_debj_all_interest($amount,$rate,$months);
                    break;
                default :

            }
        }

        return (float)$interest;
    }

    /**
     * 等额本息 所有利息
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return float
     */
    public function get_debx_all_interest($amount,$rate,$months){
        $temp=array();

        $temp['m_rate']   =($rate/100)/12;//月利率
        $temp['m_amount'] =$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额

        return round(($months*$temp['m_amount']-$amount),2);
    }

    /**
     * 等额本金 还款 所有利息
     * @param $amount
     * @param $rate
     * @param $months
     * @return float
     */
    public function get_debj_all_interest($amount,$rate,$months){
        return round(($months+1)*$amount*(($rate/100)/12)/2,2);
    }

    /**
     * 一次性本息 利息
     */
    public function get_ycxbx_interest($amount,$rate,$months){
        return round($amount*(($rate/100)/360)*($months*30),2);
    }

    /**************************************资金日志类型相关处理**********************************************************/

    /**
     * 获取 资金记录的收入支出类型
     * @param int $type
     * @return string
     */
    protected function _get_cash_log_type($type=1){
        $type_name = '';

        if(in_array($type,array(1,7,4,11))){
            $type_name = '收入';
        }else{
            $type_name = '支出';
        }

        return $type_name;
    }

    /**
     * 完善资金记录的中文描述
     * @param string $remarks
     * @param int $type
     * @return string
     */
    protected function _get_cash_log_remarks($remarks='',$type=1){
        $remarks_name = $remarks;

        if($remarks == ''){
            switch($type){
                case '1':
                    $remarks_name = '充值';
                    break;
                case '2':
                    $remarks_name = '提现';
                    break;
                case '3':
                    $remarks_name = '提现冻结';
                    break;
                case '4':
                    $remarks_name = '投资冻结';
                    break;
                case '5':
                    $remarks_name = '投资';
                    break;
                case '7':
                    $remarks_name = '收益';
                    break;
                case '10':
                    $remarks_name = '还款扣款';
                    break;
                case '11':
                    $remarks_name = '红包';
		case '20':
                    $remarks_name = '活期产品';
                    break;
            }
        }
        return $remarks_name;
    }

    /**
     * 根据cash_flow 获取投资项目名称
     * @param string $payment_no
     * @return string
     */
    protected function _get_cash_log_borrow_subject($payment_no=''){
        $subject = '';

        if($payment_no){
            $subject = $this->c->get_one(self::payment,array(
                'select' =>join_field('subject',self::borrow),
                'where'  =>array(
                    join_field('payment_no',self::payment)=>$payment_no
                ),
                'join'=>array(
                    'table'=>self::borrow,
                    'where'=>join_field('borrow_no',self::payment).'='.join_field('borrow_no',self::borrow)
                )
            ));
        }

        return $subject;
    }

    /**
     * 获取充值状态中文说明
     * @param int $status
     * @param int $type
     * @return string
     */
    protected function _get_recharge_status($status=0,$type=1){
        $status_name = '';
        if($status == 0){
            if($type == 1){
                $status_name = '待审核';
            }else{
                $status_name = '充值失败';
            }
        }else{
            $status_name = '充值成功';
        }

        return $status_name;
    }

    /**
     * 获取充值途径
     * @param int $type
     * @return string
     */
    protected function _get_recharge_type($type=1){
        $type_name = '后台充值';

        switch($type){
            case '1':
                $type_name = '后台充值';
                break;
            case '2':
                $type_name = '凯塔充值';
                break;
            case '3':
                $type_name = '连连支付充值';
                break;
        }
        return $type_name;
    }

    /**
     * 提现状态中文说明
     * @param int $status
     * @return string
     */
    protected function _get_transfer_status($status=0){
        $status_name = '后台充值';

        switch($status){
            case '1':
                $status_name = '提现成功';
                break;
            case '2':
                $status_name = '提现取消';
                break;
            default :
                $status_name = '提现中';
        }
        return $status_name;
    }

    /***************************************************************************************************************/

    /**
     * 加密字符串
     * @param int    $string 字符串
     * @param int    $length 加密长度
     * @param string $replace 替换字符 默认是*
     *
     * @return string
     */
    protected function secret($string = 0, $length = 0, $replace = '*'){
        if(empty($string)) return '';

        $str  = '';
        $temp = array();

        $temp['arr']   = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        $temp['start'] = round((count($temp['arr']) - $length) / 2);
        $temp['end']   = $temp['start'] + $length;

        for($i = $temp['start']; $i < $temp['end']; $i++){
            $temp['arr'][$i] = $replace;
        }
        $str = implode('', $temp['arr']);

        unset($temp);
        return $str;
    }

	/**
	 * 可固定开始位的加密字符串
	 * @param string $string
	 * @param int    $start
	 * @param int    $length
	 * @param string $replace
	 *
	 * @return string
	 */
	protected function _secret($string = '', $start=0, $length = 0, $replace = '*'){
        if(empty($string)) return '';

        $str  = '';
        $temp = array();

        $temp['arr']   = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        $temp['start'] = $start?$start-1:round((count($temp['arr']) - $length) / 2);
        $temp['end']   = $temp['start'] + $length;

        for($i = $temp['start']; $i < $temp['end']; $i++){
            $temp['arr'][$i] = $replace;
        }
        $str = implode('', $temp['arr']);

        unset($temp);
        return $str;
    }

    /**
     * 查询用户信息
     * @param string $mobile mobile 或uid
     * @return array
     */
    protected function _get_userinfo($mobile=''){
        $userinfo = array();

        if($this->is_mobile($mobile)){
            $field='mobile';
        }else{
            $field='uid';
        }
        if($mobile){
            $userinfo = $this->c->get_row(self::user,array('select'=>'user_name,uid,mobile,clientkind,hash,security','where'=>array($field=>$mobile)));
        }

        return $userinfo;
    }

    /**
     * 验证用户手机号码
     *
     * @access private
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    public function is_mobile($mobile = ''){
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * 获取银行卡信息
     *
     * @access public
     * @param  string  $card_no 卡号
     * @param int $uid
     * @return array
     */
    private function _get_card_info($uid=0){
        $data = $temp = array();

        if( ! empty($uid)){
            $temp['where'] = array(
                'select' => 'real_name,bank_name,account',
                'where'  => array(
                    'uid'     => $uid
                )
            );

            $data = $this->c->get_row(self::card, $temp['where']);
        }

        unset($temp);
        return $data;
    }


    /**
     * 设置修正分页的参数
     * @param int $page_id
     * @param int $page_size
     */
    protected function _set_cutpage_params($page_id=0,$page_size=0){
        if(!is_numeric($page_id) || $page_id<=0){
            $page_id=1;
        }
        if(!$page_size || !is_numeric($page_size)){
            $page_size = $this->_page_size;
        }
        $_GET['limit']    = (int)$page_size;
        $_GET['per_page'] = (((int)$page_id-1)*(int)$page_size);
    }
}