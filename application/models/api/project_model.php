<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 项目相关model
 * Class Project_model
 */
class Project_model extends CI_Model{
    //用到的数据库表
	const borrow        = 'borrow';				//借款项目表
	const user          = 'user';				//用户表
	const category      = 'product_category';	//项目分类表
	const guarantee     = 'guarantee';			//项目担保人
	const payment       = 'borrow_payment';		//项目投资还款表
	const repay         = 'borrow_repay_plan'; //项目还款计划表
	const attachment    = 'borrow_attachment'; //项目附件表
	const apply         = 'borrow_apply';		//项目借款申请
	const flow          = 'cash_flow';			//资金流动记录表
	const message       = 'message';			//信息表
	const log           = 'user_log';			//用户日志表
	const transfer      = 'user_transaction';	//提现表
	const jbb		    = 'borrow_jbb';			//聚保宝产品表
	const jbb_dtl       = 'borrow_jbb_dtl';		//聚保宝发标表
	const payment_jbb   = 'borrow_payment_jbb';	//聚保宝购买表
	const payment_jbb_dtl  = 'borrow_payment_jbb_dtl';	//聚保宝购买表明细
	
	const RUN_DATE      = '2015-06-12'; 		//网站运行时间
	private $_page_size = '10';				//分页每页记录数

    public function __construct(){
        parent::__construct();
    }

	/**
	 * 查询项目列表
	 * @param int    $page_id 分页id
	 * @param int    $page_size 分页数量
	 * @param string $category 分类
	 * @param string $status_str 状态字符串 1,2,3
	 * @param string $month_str 月份字符串 x-x 0-0.9
	 * @param string $rate_str 字符串 x-x 0-0.9
	 * @param string $mode_str 字符串 1  1,2 1,2,3
	 * @param string $type_str 字符串 1 1,2 1,2,3
	 * @param int    $active 活动标识
	 *
	 * @return array 二维数组
	 * borrow_no-借款单号
	 * subject-标题
	 * mode-还款方式
	 * rate-收益率
	 * months-借款期限
	 * type-借款方式
	 * amount-借款金额
	 * receive-融资金额
	 * receive_tate-融资率
	 * add_time-发布时间
	 * buy_time-开始购买时间
	 * due_date-结束购买时间
	 * status-项目状态
	 * summary-资金用途
	 * repayment-分享保障
	 * content-内容介绍 借款人介绍
	 * auto_invest-自动投资
	 * can_invest-是否允许投资
	 * lowest-最低起投金额
	 */
    public function get_project_list($category='',$status_str='',$month_str='',$rate_str='',$mode_str='',$type_str='',$page_id=0,$page_size=0,$active=null){
        $temp = array();
        $data = array('name'=>'查询项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        //处理show_page的分页数据
        $this->_set_cutpage_params($page_id,$page_size);

        //组合查询条件
        $temp['where'] = array(
            'select'=>join_field('borrow_no,subject,mode,rate,months,type,amount,receive,add_time,buy_time,due_date,status,summary,repayment,content,lowest,active',self::borrow)
					.','.join_field('category,cat_id',self::category)
					.','.join_field('company_name',self::guarantee),
//                .',user_a.user_name,user_a.mobile,user_a.nric,user_a.real_name,user_b.user_name as last_invester_name,',
            'join'=>array(
//                array('table'=>self::user.' as user_a','where'=>join_field('uid',self::borrow).'= user_a.uid'),
//                array('table'=>self::user.' as user_b','where'=>join_field('last_investor',self::borrow).'= user_b.uid'),
                array('table'=>self::category,'where'=>join_field('productcategory',self::borrow).'= '.join_field('cat_id',self::category)),
                array('table'=>self::guarantee,'where'=>join_field('guarantee_id',self::borrow).'= '.join_field('id',self::guarantee))
            ),
            'order_by' => join_field('active',self::borrow).' DESC,'
						.join_field('sort_order',self::borrow).' DESC,'
						.join_field('productcategory',self::borrow).' ASC,'
						.join_field('status',self::borrow).' ASC,'
						.join_field('id',self::borrow).' DESC',
            'where'=>array(
                join_field('status',self::borrow).' >'=>1,//项目状态审核通过 未取消
                join_field('show_time',self::borrow).' <'=>time()//已经在显示的
            )
        );

		if( ! is_null($active)){
			$temp['where']['where'][join_field('active',self::borrow)] = $active;
		}

        //如果有限定类别
        if($category){
            $temp['where']['where'][join_field('productcategory',self::borrow)] = $category;
        }
        //如果有限定状态
        if($status_str){
            if(is_string($status_str) && strpos($status_str,',')){
                $status_array = explode(',',$status_str);
                //过滤 特俗状态 和 非法状态数字
                foreach($status_array as $k=>$v){
                    if(!is_numeric($v) || $v == 0 || $v == 1 || $v < 0){
                        unset($status_array[$k]);
                    }
                }
                $temp['where']['where_in'] = array(
                    'field'=>join_field('status',self::borrow),
                    'value'=>$status_array
                );
            }else{
                if(is_numeric($status_str) && $status_str > 1){
                    $temp['where']['where'][join_field('status',self::borrow)] = $status_str;
                }
            }
        }

        //是否限定月份
        if($month_str){
            if(is_string($month_str) && strpos($month_str,'-')){
                $month_array = explode('-',$month_str);
                $temp['where']['where'][join_field('months',self::borrow).' >='] = (float)$month_array[0];
                $temp['where']['where'][join_field('months',self::borrow).' <='] = (float)$month_array[1];
            }else{
                if(is_numeric($month_str) && $month_str > 0){
                    $temp['where']['where'][join_field('months',self::borrow)] = $month_str;
                }
            }
        }

        //是否限定利率
        if($rate_str){
            if(is_string($rate_str) && strpos($rate_str,'-')){
                $rate_array = explode('-',$rate_str);
                $temp['where']['where'][join_field('rate',self::borrow).' >='] = (int)$rate_array[0];
                $temp['where']['where'][join_field('rate',self::borrow).' <='] = (int)$rate_array[1];
            }else{
                if(is_numeric($rate_str) && $rate_str > 0){
                    $temp['where']['where'][join_field('rate',self::borrow)] = $rate_str;
                }
            }
        }

	    //如果有限定mode
	    if($mode_str){
		    if(is_string($mode_str) && strpos($mode_str,',')){
			    $mode_array = explode(',',$mode_str);
			    $temp['where']['where_in'] = array(
				    'field'=>join_field('mode',self::borrow),
				    'value'=>$mode_array
			    );
		    }else{
			    if(is_numeric($mode_str)){ // && $mode_str >= 1 && $mode_str <= 4
				    $temp['where']['where'][join_field('mode',self::borrow)] = $mode_str;
			    }
		    }
	    }

	    //如果有限定mode
	    if($type_str){
		    if(is_string($type_str) && strpos($type_str,',')){
			    $type_array = explode(',',$type_str);
			    $temp['where']['where_in'] = array(
				    'field'=>join_field('type',self::borrow),
				    'value'=>$type_array
			    );
		    }else{
			    if(is_numeric($type_str)){ // && $type_str >= 1 && $type_str <= 3
				    $temp['where']['where'][join_field('type',self::borrow)] = $type_str;
			    }
		    }
	    }

        $data['data'] = $this->c->show_page(self::borrow,$temp['where']);
        unset($data['data']['links']);
        if($data['data']){
            $data['status'] = '10000';
            if(empty($data['data']['data'])){
                $data['msg'] = '暂无相关数据';
            }else{
                $data['msg'] = 'ok!';
                //循环处理 项目必要信息 模式 类型 融资率 等
                foreach($data['data']['data'] as $key=>$val){
	                //融资率
	                $data['data']['data'][$key]['receive_rate'] = $this->_get_project_receive_rate($val['amount'],$val['receive'],$val['buy_time']);

					$temp['status_array'] 						= $this->get_project_status($val['buy_time'],$val['due_date'],$data['data']['data'][$key]['receive_rate'],$val['status']);
                    $data['data']['data'][$key]['status'] 		= $temp['status_array']['name'];//项目状态
                    $data['data']['data'][$key]['can_invest'] 	= $temp['status_array']['can_invest'];//项目状态
                    $data['data']['data'][$key]['new_status'] 	= $temp['status_array']['new_status'];//项目新状态
                    $data['data']['data'][$key]['mode'] 		= $this->_get_project_mode($val['mode']);//项目mode
//                    $data['data']['data'][$key]['repay_name'] = $this->_get_project_repay($val['repay']);//项目计息还息方式
                    $data['data']['data'][$key]['type_name'] 	= $this->_get_project_type($val['type']);//项目借款类型
                    $data['data']['data'][$key]['auto_invest'] 	= FALSE;//是否支持自动投资
                    $data['data']['data'][$key]['rate'] 		= $this->_project_rate_format($data['data']['data'][$key]['rate']);//格式化利率

	                //加密必要信息
//	                $data['data']['data'][$key]['real_name'] 	= $this->secret($data['data']['data'][$key]['real_name'],mb_strlen($data['data']['data'][$key]['real_name'])-1);
//	                $data['data']['data'][$key]['mobile'] 		= $this->secret($data['data']['data'][$key]['mobile'],5);
//	                $data['data']['data'][$key]['nric'] 		= $this->secret($data['data']['data'][$key]['nric'],10);
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 查询 项目分类
     * @param int $category_id 分类id 默认为空 查询全部 为数字查询该id内容
     *
     * @return array
	 * 0 =>
	 * array
	 * 'cat_id' => string '1' (length=1) 类别id
	 * 'category' => string '车贷宝' (length=9) 类别名称
	 * 'parent_id' => string '0' (length=1) 父级id
	 * 'sort_order' => string '0' (length=1) 排序字段
	 * 'description' => string '车贷宝' (length=9) 描述
	 * 1 =>
	 * array
	 * 'cat_id' => string '2' (length=1)
	 * 'category' => string '聚农贷' (length=9)
	 * 'parent_id' => string '0' (length=1)
	 * 'sort_order' => string '0' (length=1)
	 * 'description' => string '聚农贷' (length=9)
	 * 2 =>
	 * array
	 * 'cat_id' => string '3' (length=1)
	 * 'category' => string '聚惠理财' (length=12)
	 * 'parent_id' => string '0' (length=1)
	 * 'sort_order' => string '0' (length=1)
	 * 'description' => string '聚惠理财' (length=12)
     */
    public function get_project_category($category_id=0){
        $temp = array();
        $data = array('name'=>'项目分类','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        if($category_id > 0 && is_numeric($category_id)){
            $temp['where'] = array(
                'where'=>array(
                    'cat_id'=>$category_id,
                    'status'=>1
                ),
                'select'=>'cat_id,category,parent_id,sort_order,description','order_bu'=>'sort_order'
            );
            $data['data'] = $this->c->get_row(self::category,$temp['where']);
        }else{
            $temp['where'] = array(
                'where'	=>array('status'=>1),
                'select'=>'cat_id,category,parent_id,sort_order,description','order_bu'=>'sort_order'
            );
            $data['data'] = $this->c->get_all(self::category,$temp['where']);
        }

        $data['status'] = '10000';
        if(empty($data['data'])){
            $data['msg'] = '暂无相关数据';
        }else{
            $data['msg'] = 'ok!';
        }

        unset($temp);
        return $data;
    }

	/**
	 * 获取项目详情
	 * @param string $borrow_no
	 *
	 * @return array
	 *  'borrow_no' => string 'B15101085180543' (length=15)
	 *	'subject' => string '车贷宝1号-42' (length=16)
	 *	'mode' => string '一次性本息' (length=15)
	 *	'rate' => string '12.00' (length=5)
	 *	'months' => string '0.9' (length=3)
	 *	'type' => string '3' (length=1)
	 *	'amount' => string '500000.00' (length=9)
	 *	'receive' => string '500000.00' (length=9)
	 *	'add_time' => string '1444464819' (length=10)
	 *	'buy_time' => string '1444615200' (length=10)
	 *	'due_date' => string '1445184000' (length=10)
	 *	'status' => string '融资完成' (length=12)
	 *	'summary' => string ' (length=738)
	 *	'repayment' => string '
	 *	'content' => string '(length=144)
	 *	'lowest' => string '100.00' (length=6)
	 *	'category' => string '车贷宝' (length=9)
	 *	'receive_rate' => int 100
	 *	'can_invest' => boolean false
	 *	'type_name' => string '担保借款' (length=12)
	 *	'auto_invest' => boolean false
	 */
    public function get_project_info($borrow_no=''){
        $temp = array();
        $data = array('name'=>'项目详情','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        if( !$borrow_no){
            $data['msg'] = '项目id为空';
        }else{
            $temp['where'] = array(
                'select'=>join_field('borrow_no,subject,mode,rate,months,type,amount,receive,add_time,buy_time,due_date,status,summary,repayment,content,lowest,max',self::borrow)
						.','.join_field('category',self::category)
						.','.join_field('company_name',self::guarantee),
//                    .',user_a.user_name,user_a.mobile,user_a.nric,user_a.real_name,user_b.user_name as last_invester_name,',
                'join'=>array(
//                    array('table'=>self::user.' as user_a','where'=>join_field('uid',self::borrow).'= user_a.uid'),
//                    array('table'=>self::user.' as user_b','where'=>join_field('last_investor',self::borrow).'= user_b.uid'),
                    array('table'=>self::category,'where'=>join_field('productcategory',self::borrow).'= '.join_field('cat_id',self::category)),
                    array('table'=>self::guarantee,'where'=>join_field('guarantee_id',self::borrow).'= '.join_field('id',self::guarantee))
                ),
                'where'=>array(join_field('borrow_no',self::borrow)=>$borrow_no,join_field('status',self::borrow).' >'=>1)
            );
            $data['data'] = $this->c->get_row(self::borrow,$temp['where']);

            $data['status'] = '10000';
            if(empty($data['data'])){
                $data['msg'] = '暂无相关数据';
            }else{
                $data['msg'] = 'ok!';
                //补充数据
	            //融资率
				$data['data']['receive_rate'] = $this->_get_project_receive_rate($data['data']['amount'],$data['data']['receive'],$data['data']['buy_time']);

				$temp['status_array'] 		= $this->get_project_status($data['data']['buy_time'],$data['data']['due_date'],$data['data']['receive_rate'],$data['data']['status']);
                $data['data']['status'] 	= $temp['status_array']['name'];//状态
                $data['data']['can_invest'] = $temp['status_array']['can_invest'];//是否克投资
                $data['data']['new_status'] = $temp['status_array']['new_status'];//新状态

                $data['data']['mode_name'] 	= $this->_get_project_mode($data['data']['mode']);//mode
//                $data['data']['repay_name'] = $this->_get_project_repay($data['data']['repay']);//计息还息方式
                $data['data']['type_name'] 	= $this->_get_project_type($data['data']['type']);//借款类型
                $data['data']['auto_invest'] = FALSE;//是否支持自动投标
                $data['data']['rate'] 		= $this->_project_rate_format($data['data']['rate']);//格式化利率

//	            $data['data']['agreement'] 	= json_decode($data['data']['agreement'], TRUE);
//	            $data['data']['claims']    	= json_decode($data['data']['claims'], TRUE);

	            //加密必要信息
//	            $data['data']['mobile'] 	= $this->secret($data['data']['mobile'],5);
//	            $data['data']['real_name'] 	= $this->secret($data['data']['real_name'],mb_strlen($data['data']['real_name'])-1);
//	            $data['data']['nric'] 		= $this->secret($data['data']['nric'],10);
            }
        }

        unset($temp);
        return $data;
    }

	/**
	 * 获取项目还款记录
	 * @param string $borrow_no
	 *
	 * @return array
	 * 'repay_index' => string  期数
	 * 'repay_date' => string  时间
	 * 'repay_type' => string '利息'  类型 1本金 2 利息 3 本息
	 * 'repay_amount' => string  还款金额
	 * 'repay_principal' => string  还款本金
	 * 'repay_interest' => string  还款利息
	 * 'repay_surplus_principal' => string  剩余本金
	 * 'rapay_time' => string  还款时间
	 * 'dateline' => string 记录添加时间
	 * 'status' => string  记录状态 0代表未还  1代表正常  2代表提前  3代表逾期 4代表预付（目前未使用）
	 */
	public function get_project_repayment_list($borrow_no=''){
		$temp = array();
		$data = array('name'=>'项目还款记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		if( ! empty($borrow_no)){
			//根据borrow_no查询项目信息是否存在
			$temp['where']=array(
				'select'=>'mode,due_date,months,repay,amount,rate,deduct,status,confirm_time,due_date',
				'where'=>array('borrow_no'=>$borrow_no)
			);
			$temp['borrow_info']=$this->c->get_row(self::borrow,$temp['where']);

			if( ! empty($temp['borrow_info'])){
				//还款中和还款完成的项目查询还款记录
				if($temp['borrow_info']['status'] == 4 || $temp['borrow_info']['status'] == 7){
					$data['data'] = $this->get_exists_repayment_plan($borrow_no);
				}else{ //其他情况 计算生成还款计划
					//先息后本 处理due_data 日期
					if($temp['borrow_info']['mode'] == 1 ){
						$time = date('Ym',time())."05";
						$time = strtotime($time);
						$temp['borrow_info']['due_date'] = $time;
					}

					//获取还款计划数据
					$temp['plan_data']=$this->get_repayment_plan($temp['borrow_info']['mode'], $temp['borrow_info']['amount'], $temp['borrow_info']['rate'], $temp['borrow_info']['months'], $temp['borrow_info']['repay']);

					if( ! empty($temp['plan_data'])){
						// 获取还款计的还款时间
						$temp['plan_date']=$this->get_repayment_date($temp['borrow_info']['due_date'],$temp['borrow_info']['months'],$temp['borrow_info']['mode'],$temp['borrow_info']['repay']);
					}

					//数据和时间都有
					if( ! empty($temp['plan_date'])){
						$temp['plan_data_count']=count($temp['plan_data']);//还款记录总记录数

						//data和date的k都是从1开始的
						foreach($temp['plan_data'] as $k=>$v){
							if($temp['borrow_info']['mode'] == 3 && $k == $temp['plan_data_count']){ //一次性还款付息 最后一期还本金的时间
								$temp['data']['repay_date']	 = $temp['plan_date'][1];
								$temp['data']['repay_index'] = $k-1;
								$temp['data']['repay_type']	 = 2;
							}elseif($temp['borrow_info']['mode'] == 1 && $k == $temp['plan_data_count']){ //先息后本 最后一起还本金的时间
								$temp['data']['repay_date']	 = $temp['plan_date'][$k-1];
								$temp['data']['repay_index'] = $k-1;
								$temp['data']['repay_type']	 = 2;
							}else{
								$temp['data']['repay_date']	 = $temp['plan_date'][$k];
								$temp['data']['repay_index'] = $k;
								$temp['data']['repay_type']	 = ((($temp['borrow_info']['mode'] == 1) || ($temp['borrow_info']['mode'] == 3))?1:3);
							}

							$temp['data']['repay_amount']			 = $v['amount'];
							$temp['data']['repay_principal']		 = $v['principal'];
							$temp['data']['repay_interest']			 = $v['interest'];
							$temp['data']['repay_surplus_principal'] = $v['surplus_principal'];
							$temp['data']['repay_date']				 = ($temp['borrow_info']['deduct'] >= $k?($temp['borrow_info']['confirm_time']?$temp['borrow_info']['confirm_time']:$temp['borrow_info']['due_date']):strtotime($temp['data']['repay_date']));
							if($temp['borrow_info']['deduct'] > $k)
								$temp['data']['status']				 = 4;

							//获取该记录类型
							$temp['data']['repay_type']  = $this->_get_repayment_type($temp['data']['repay_type']);

							$temp['insert_data'][]		 = $temp['data'];
						}

						$data['data'] = $temp['insert_data'];
					}
				}

				$data['status'] = '10000';
				if($data['data']){
					$data['msg'] = 'ok!';
				}else{
					$data['msg'] = '暂无相关数据';
				}
			}else{
				$data['msg'] = '项目不存在!';
			}
		}else{
			$data['msg'] = '项目id为空!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取交易附件
	 * @param string $borrow_no
	 *
	 * @return array
	 */
	public function get_project_attachment($borrow_no=''){
		$data = array('name'=>'项目交易附件','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());
		$temp = array();

		if( ! empty($borrow_no)){
			$temp['where'] = array(
				'select' => 'type,link_url,description',
				'where'  => array('borrow_no' => $borrow_no)
			);
			$temp['rs'] = $this->c->get_all(self::attachment, $temp['where']);

			$data['status'] = '10000';
			if( ! empty($temp['rs'])){
				foreach($temp['rs'] as $val){
					$data['data'][$val['type']][] = $val;
				}
				$data['msg'] = 'ok!';
			}else{
				$data['msg'] = '暂无相关数据!';
			}
		}else{
			$data['msg'] = '项目id为空!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取项目投资记录
	 * @param string $borrow_no
	 * @param int $page_id
	 * @param int $page_size
	 *
	 * @return array
	 * 'mobile' => string '135*****887' (length=11)
	 *'amount' => string '400000.00' (length=9)
	 *'pay_time' => string '1444616171' (length=10)
	 */
	public function get_project_invest_list($borrow_no='', $page_id=0, $page_size=0){
		$temp = array();
		$data = array('name'=>'项目投资记录','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		$this->_set_cutpage_params($page_id,$page_size);

		if( ! empty($borrow_no)){
			$temp['where'] = array(
				'select'   => join_field('mobile,user_name,avatar', self::user).','.join_field('payment_no,amount,pay_time,automatic_type', self::payment),//uid,rate,amount,charge,dateline,pay_time real_name user_name dateline
				'where'    => array(
					join_field('borrow_no', self::payment) => $borrow_no,
					join_field('type', self::payment) => 1
				),
				'join'     => array(
					'table' => self::user,
					'where' => join_field('uid', self::user).' = '.join_field('uid', self::payment)
				),
				'order_by' 	=> join_field('dateline', self::payment).' desc'
			);

			$temp['data'] = $this->c->show_page(self::payment, $temp['where']);

			$data['status'] = '10000';
			if( ! empty($temp['data']['data'])){
				$data['total'] = $temp['data']['total'];
				$temp['data'] = $temp['data']['data'];
				//加密必要信息
				foreach($temp['data'] as $k=>$v){
					if($temp['data'][$k]['user_name'] != '聚保宝')
					$temp['data'][$k]['user_name'] 	= $this->_secret($temp['data'][$k]['user_name'],2,mb_strlen($temp['data'][$k]['user_name'])>2?mb_strlen($temp['data'][$k]['user_name'])-2:mb_strlen($temp['data'][$k]['user_name'])-1,3);
					$temp['data'][$k]['avatar'] 	= $v['avatar']?$this->c->get_oss_image($v['avatar']):'';
					$temp['data'][$k]['mobile'] 	= $this->_secret($temp['data'][$k]['mobile'],4,4);
				}
				$data['data'] 	= $temp['data'];
				$data['msg'] 	= 'ok!';
			}else{
				$data['msg'] 	= '暂无相关数据!';
			}
		}else{
			$data['msg'] = '项目id为空!';
		}

		unset($temp);
		return $data;
	}

    /**
     * 项目投资
     * @param string $mobile
     * @param int $amount
     * @param string $security
     * @param string $borrow_no
     * @return array
     * balance=>0 剩余金额
     * status 10001 提示性错误 10002 未登陆  10003 未设置资金密码  10004 余额不足 10005 未实名
     */
    public function project_invest($mobile='', $amount=0, $security='',$borrow_no=''){
        $temp = array();
        $data = array('name'=>'项目投资','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		if( !$this->is_mobile($mobile)){
			$data['msg'] 	= '对不起，您还没有登录呢!';
			$data['status'] = '10002';
			return $data;
		}

		$temp['userinfo'] = $this->_get_userinfo($mobile);
		if( ! $temp['userinfo']){
			$data['msg'] = '对不起，该手机用户不存在!';
			return $data;
		}

		//验证实名
		if($temp['userinfo']['clientkind'] != 1){
			$data['msg'] 	= '对不起，请先进行实名认证!';
			$data['status'] = '10005';
			return $data;
		}

		if( ! $amount || !is_numeric($amount)){
			$data['msg'] = '对不起，投资金额不能为空!';
			return $data;
		}

		if( ! $borrow_no){
			$data['msg'] = '对不起，项目单号不能为空!';
			return $data;
		}

		if( ! $security){
			$data['msg'] = '对不起，资金密码不能为空!';
			return $data;
		}

		//生成资金密码
		$temp['security'] = $this->c->password($security, $temp['userinfo']['hash']);

		if( ! $temp['userinfo']['security']){
			$data['msg'] 	= '对不起，您还没有设定资金密码';
			$data['status'] = '10003';
			return $data;
		}

		//验证资金密码
		if($temp['security'] != $temp['userinfo']['security']){
			$data['msg'] = '对不起，您输入的资金密码不正确';
			return $data;
		}

		//验证项目信息是否存在
		$temp['balance'] = $this->get_user_balance($temp['userinfo']['uid']); // 当前账户可用余额
		$temp['detail']  = $this->get_borrow_detail($borrow_no);
		if( ! $temp['detail']){
			$data['msg'] = '对不起，该项目不存在!';
			return $data;
		}

		//获取项目可投资状态信息
		$temp['receive_rate'] = $this->_get_project_receive_rate($temp['detail']['amount'],$temp['detail']['receive']);
		$temp['status_array'] = $this->get_project_status($temp['detail']['buy_time'],$temp['detail']['due_date'],$temp['receive_rate'],$temp['detail']['status']);

		if($temp['status_array']['can_invest'] == FALSE){
			$data['msg'] = '对不起，该项目'.$temp['status_array']['name'];
			return $data;
		}

		//验证用户余额
		if($temp['balance'] < $amount){
			$data['msg'] 	= '对不起，您的账户余额不足！';
			$data['status'] = '10004';
			return $data;
		}

		//验证是否自己的标的
		if($temp['detail']['uid'] == $temp['userinfo']['uid']){
			$data['msg'] = '对不起，您不能投自己的标！';
			return $data;
		}

		//剩余融资金额 大于最低投资金额 验证投资金额是否大于最低投资金额
		if($temp['detail']['surplus'] > $temp['detail']['lowest'] &&  $amount < $temp['detail']['lowest']){
			$data['msg'] = '对不起，投标金额不能小于'.$temp['detail']['lowest'];
			return $data;
		}

		//验证是否大于最大投资金额
		if($amount > $temp['detail']['max']){
			$data['msg'] 	= '对不起，您的投资金额大于最大投资金额！';
			$data['status'] = '10001';
			return $data;
		}

        //验证是否大于剩余融资金额
        if($amount > $temp['detail']['surplus']){
            $data['msg'] 	= '对不起，您的投资金额大于剩余融资金额！';
            $data['status'] = '10001';
            return $data;
        }

		$temp['query']  = $this->_invest($borrow_no, $amount, $temp['userinfo']['uid'],$temp['balance']);

		if( ! empty($temp['query'])){
            $data['status'] 		 = '10000';
            $data['msg'] 			 = sprintf('您(尾号为%s)已成功投资【%s】项目，投资金额为:%s元。公司会定期汇报您的收益情况，祝您生活愉快！', substr($mobile, -4), $temp['detail']['subject'], $amount);
            $data['data']['balance'] = round($temp['balance'] - $amount, 2);

//			$this->load->model('send_model', 'send');
//			$this->send->send_sms($mobiles, $data['msg'], 0, $temp['userinfo']['uid']);
		}

        unset($temp);
        return $data;
    }

	/**
	 * 项目投资私有方法 数据库处理
	 * @param int $amount 投资金额
	 * @param string $borrow_no 借款编号
	 * @param int $balance 帐户可用余额
	 * @param int $uid
	 * @return bool
	 */
	protected function _invest($borrow_no = '',$amount = 0, $uid=0,  $balance = 0){
		$query = FALSE;
		$temp  = array();

		if( ! empty($amount) && !empty($borrow_no) && !empty($balance)  && !empty($uid)){
			$temp['where'] = array(
				'select' => 'rate',
				'where'  => array('borrow_no' => $borrow_no)
			);

			$temp['rate'] = $this->c->get_one(self::borrow, $temp['where']);

			$temp['where'] = array(
				'select' => 'firmid,vaccid,real_name,user_name',
				'where'  => array(
					'uid' => $uid,
				)
			);
			$temp['usr'] = $this->c->get_row(self::user, $temp['where']);
			if( ! empty($temp['rate'])){
				$MarketSerial= $this->c->transaction_no(self::payment, 'payment_no');
				//$FirmId = $temp['usr']['firmid'];//$temp['usr']['firmid'];//$FirmId;// 对公账户
				//$CustName = $temp['usr']['real_name']; //$temp['usr']['real_name'];//对公账户姓名
				//$VaccId = $temp['usr']['vaccid'];//$temp['usr']['vaccid'];//对公账户姓名
				//$configData = $this->pay->touzidongjie($FirmId, $CustName,$VaccId,$MarketSerial,$borrow_no,$amount);
				//现在未做返回状态判断
				// if( ($configData['ReturnInfo']['RtnInfo']=="成功!")&&($amount == $configData['Transfer']['FreezeMoney']))
				// {
				$this->db->trans_start();

				// 添加投资记录
				$temp['payment'] = array(
					'payment_no' => $MarketSerial,
					'uid'        => $uid,
					'type'       => 1,
					'borrow_no'  => $borrow_no,
					'rate'       => $temp['rate'],
					'amount'     => $amount,
					'balance'    => round($balance - $amount, 2),
					'charge'     => 0,
					'dateline'   => time(),
					'pay_time'   => time(),
					'status'     => 1
				);

				$this->c->insert(self::payment, $temp['payment']);

				// 添加资金记录
				$temp['flow'] = array(
					'uid'      => $uid,
					'type'     => 4,
					'amount'   => $amount,
					'balance'  => round($balance - $amount,2),
					'source'   => $temp['payment']['payment_no'],
					'remarks'  => '投资冻结',
					'dateline' => time()
				);

				$this->c->insert(self::flow, $temp['flow']);

				// 更新收款金额
				$temp['where'] = array('where' => array('borrow_no' => $borrow_no));
				$temp['data']  = array('field' => 'receive', 'value' => '`receive` + '.$amount);

				$this->c->set(self::borrow, $temp['where'], $temp['data']);

				// 更新投资人信息
				$temp['data']  = array(
					'last_investor' => $uid,
					'last_amount'   => $amount,
					'last_time'     => time()
				);

				$this->c->update(self::borrow, $temp['where'], $temp['data']);

				$this->db->trans_complete();
				$query = $this->db->trans_status();

				if( ! empty($query)){
					// 可用余额
					//$temp['data'] = array('balance' => round($balance - $amount, 2));
					//$this->session->set_userdata($temp['data']);
					$this->_set_borrow_status(); // 更新记录状态
					$temp['content'] = sprintf('您好，您投资的%s元资金已经冻结。请等待标的结束。', $amount);
					$this->send_message($uid, '您好，您投资的金额已经冻结！', $temp['content'],3);//发送信息
					$this->add_user_log('invest', '投资'.sprintf('¥ %s', round($amount,2)).'(项目编号：'.$borrow_no.')',$uid,$temp['usr']['user_name']);//添加用户日志
				}
			}
		}

		unset($temp);
		return $query;
	}

    /**
     * @param string $mobile
     * @param int $type
     * @param int $amount
     * @param $dateline
     * @param string $province
     * @param string $city
     * @param string $district
     * @param string $from
     * @param string $p_type
     * @return array
     */
    public function project_apply($mobile='', $type=0, $amount=0,$dateline, $province='', $city='', $district='',$from='', $p_type=''){
        $temp = array();
        $data = array('name'=>'项目借款申请','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        //验证必要参数
        if( ! $this->is_mobile($mobile)){
            $data['msg'] = '电话格式不正确!';
            return $data;
        }
		$temp['userinfo'] = $this->_get_userinfo($mobile);
		if( ! $temp['userinfo']){
			$data['msg'] = '借款用户不存在!';
			return $data;
		}
		if( ! $type){
			$data['msg'] = '借款主体为空!';
			return $data;
		}
		if( ! is_numeric($amount) || $amount <= 0){
			$data['msg'] = '借款金额错误!';
			return $data;
		}
		if( ! $dateline){
			$data['msg'] = '洽谈时间为空!';
			return $data;
		}
		if( ! $province){
			$data['msg'] = '省份为空!';
			return $data;
		}
		if( ! $city){
			$data['msg'] = '城市为空!';
			return $data;
		}
		if( ! $district){
			$data['msg'] = '地区为空!';
			return $data;
		}
		if( ! $p_type){
			$data['msg'] = '借款类型为空!';
			return $data;
		}
		//公司借款的时候验证是否又公司认证
		if($type == 2  && $temp['userinfo']['clientkind'] != 0){
			$data['msg'] = '尚未进行公司认证不能公司借款!';
			return $data;
		}
		//验证是否已有未处理的借款申请
		$temp['is_apply'] = $this->c->count(self::apply,array('where'=>array('mobile'=>$mobile,'status'=>0)));
		if($temp['is_apply']){
			$data['msg'] = '您有未审批的借款，请等待风控人员与您取得联系!';
			return $data;
		}else{
			$temp['data'] = array(
				'apply_no'    => $this->c->transaction_no(self::apply, 'apply_no'),
				'user_name'   => $temp['userinfo']['user_name'],
				'mobile'      => $mobile,
				'type'        => (int)$type,
				'amount'      => $amount,
				'dateline'    => $dateline,
				'province'    => (int)$province,
				'city'        => (int)$city,
				'district'    => (int)$district,
				'from'        => $from,
				'add_time'    => time(),
				'update_time' => time(),
				'p_type'      => (int)$p_type,
			);
			$temp['data']['dateline'] = ( ! empty($temp['data']['dateline'])) ? strtotime($temp['data']['dateline']) : time();
			//验证短信验证码
//			$temp['is_check'] = $this->send->validation($temp['data']['mobile'], $temp['authcode'], 10, 5);
//			if(empty($temp['is_check'])){
//				$data['msg'] = '你输入的手机验证码不正确或者已过期！';
//				return $data;
//			}
			$query = $this->c->insert(self::apply, $temp['data']);
			if( ! empty($query)){
				$data = array(
					'status' => '10000',
					'msg'  	 => '你的借款申请已经提交成功,请等待审核!'
				);
			}
		}

        unset($temp);
        return $data;
    }

    /**
     * 获取项目借款类型
     * @return array
     */
    public function get_project_apply_category(){
        $temp = array();
        $data = array('name'=>'项目借款类型','status'=>'10000','msg'=>'ok!','sign'=>'','data'=>array());

        $data['data'] = array(
            array('id'=>1,'name'=>'信用贷'),
            array('id'=>2,'name'=>'实物抵押'),
            array('id'=>3,'name'=>'担保借款')
        );

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
			'name' 	 => '总额统计',
			'status' => '10001',
			'msg' 	 => '服务器繁忙请稍后重试!',
			'sign' 	 => '',
			'data' 	 => array('borrow_total'=>0,'invest_total'=>0,'interest_total'=>0,'user_total'=>0,'days_total'=>0,'risk_total'=>0)
		);

        $temp['where'] = array(
            'select' => 'SUM(amount) as borrow_total,SUM(receive) as invest_total',
            'where'  => array('status >'=>1)
        );
        //如果有分类 则查询分类下的借款总额
        if($category > 0){
            $temp['where']['where']['productcategory']=$category;
        }
        $data['data'] = $this->c->get_row(self::borrow,$temp['where']);

		$temp['interest'] = $this->get_project_interest_total($category);
		if($temp['interest']['status'] == '10000'){
			$data['data']['interest_total']=$temp['interest']['data']['interest'];
		}

		$data['data']['user_total'] = $this->c->count(self::user);
		$data['data']['days_total'] = ceil((time()-strtotime(self::RUN_DATE))/3600/24);
		$data['data']['risk_total'] = 1000000;

        $data['status'] = '10000';
        $data['msg'] = 'ok!';

        unset($temp);
        return $data;
    }

    /**
     * 获取全网利息总额
     * @param int $category
     * @return float|int
     */
    public function get_project_interest_total($category=0){
        $temp = array();
        $data = array('name'=>'项目利息总额','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

        $temp['amount'] = 0;
        $temp['where']  = array('select' => 'amount,months,rate,mode,repay','where'=>array('status >'=>1));

        if( ! empty($category))$temp['where']['where']['productcategory']=$category;

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data'])){
            foreach($temp['data'] as $k => $v){
                $temp['amount'] += $this->get_project_interest($v['amount'],$v['rate'],$v['months'],$v['mode']);//计算公式
            }
            $data['status'] 		  = '10000';
            $data['msg'] 			  = 'ok';
            $data['data']['interest'] =  $temp['amount'];
        }else{
            $data['status'] 		  = '10000';
            $data['msg'] 			  = 'ok';
            $data['data']['interest'] =  0;
        }

        unset($temp);
        return $data;
    }

	/**
	 * 用户已投项目列表
	 * @param int $uid 用户uid
	 * @param int $page_id 分页页码 默认为1
	 * @param int $page_size 分页 每页记录数 默认10
	 * @param string $status_str 状态字符串 1或1，2，3.。
	 * @param int $start_time
	 * @param int $end_time
	 * @return array 二维数组
	 * 'borrow_no' => string 'B15100934941036' (length=15)
	 * 'subject' => string '车贷宝1号-41' (length=16)
	 * 'rate' => string '12.00' (length=5)
	 * 'amount' => string '101.00' (length=6)
	 * 'invest_time' => string '1444618628' (length=10)
	 * 'interest' => float 0.91
	 * 'interest_start_time' => int 1444722479
	 * 'interest_lately_time' => string '20151109' (length=8)
	 * 'status' => string '还款中' (length=9)
	 */
	public function get_user_project_list($uid=0,$status_str='',$start_time=0,$end_time=0, $page_id=1,$page_size=10){
		$temp = array();
		$data = array('name'=>'用户已投项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		//验证uid参数
		if($uid == 0){
			$data['msg'] = '用户uid为空!';
			return $data;
		}

		//根据、参数查询改uid是否存在
		$temp['user_info'] = $this->c->get_row(self::user,array('where'=>array('uid'=>$uid)));
		if( ! $temp['user_info']){
			$data['msg'] = '用户信息不存在!';
			return $data;
		}

		//处理show_page的分页数据
		$this->_set_cutpage_params($page_id,$page_size);

		//组合查询条件
		$temp['where'] = array(
			'select'   => join_field('payment_no,borrow_no,rate,dateline', self::payment).','
						.join_field('subject,status,months,mode,receive,due_date,confirm_time,buy_time', self::borrow).','
						.join_field('amount', self::borrow).' as amounts,'
						.join_field('category',self::category)
						.',SUM('.join_field('amount',self::payment).') as amount',
			'where'    => array(
				join_field('uid', self::payment) => $uid,
				join_field('type', self::payment) => 1,
				join_field('status', self::payment) => 1
			),

			'join'     => array(
				array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
				array('table' => self::category, 'where' => join_field('cat_id', self::category).' = '.join_field('productcategory', self::borrow))
			),
			'order_by' => array(
				array('field'=>join_field('status', self::borrow),'value'=>'asc'),
				array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
				array('field'=>join_field('dateline', self::payment),'value'=>'desc')
			),
			'group_by'=>join_field('borrow_no',self::payment)
		);

		//如果有限定状态
		if($status_str){
			if(is_string($status_str) && strpos($status_str,',')){
				$status_array = explode(',',$status_str);
				//过滤 特俗状态 和 非法状态数字
				foreach($status_array as $k=>$v){
					if(!is_numeric($v) || $v == 0 || $v == 1 || $v < 0){
						unset($status_array[$k]);
					}
				}
				$temp['where']['where_in'] = array(
					'field'=>join_field('status',self::borrow),
					'value'=>$status_array
				);
			}else{
				if(is_numeric($status_str) && $status_str > 1){
					$temp['where']['where'][join_field('status',self::borrow)] = $status_str;
				}
			}
		}

		//处理过滤条件 开始时间和结束时间
		if(is_numeric($start_time) && $start_time > 0){
			$temp['where']['where'][join_field('dateline',self::payment).' >='] = $start_time;
		}
		if(is_numeric($end_time) && $end_time > 0){
			$temp['where']['where'][join_field('dateline',self::payment).' <='] = $end_time;
		}

		//查询数据
		$temp['data'] = $this->c->show_page(self::payment,$temp['where']);

		$data['status'] = '10000';
		if($temp['data']['data']){
			unset($temp['data']['links']);
			//重组数据
			foreach($temp['data']['data'] as $key=>$val){
				$data['data']['data'][$key]['borrow_no'] 			= $val['borrow_no'];//项目单号
				$data['data']['data'][$key]['subject'] 				= $val['subject'];//项目名称
				$data['data']['data'][$key]['rate'] 				= $val['rate'];//项目利率
				$data['data']['data'][$key]['amount'] 				= $val['amount'];//借款金额
				$data['data']['data'][$key]['invest_time'] 			= $val['dateline'];//借款时间
				$data['data']['data'][$key]['interest'] 			= $this->get_project_user_interest($val['borrow_no'],$uid,$val['amount'],$val['rate'],$val['months'],$val['mode']);//预计（实收）收益
				$data['data']['data'][$key]['interest_start_time'] 	= $this->get_project_interest_start_time($val['status'],$val['due_date'],$val['confirm_time']);//收益计息日
				$data['data']['data'][$key]['interest_lately_time'] = $this->get_project_lately_repayment_time($val['borrow_no'],$uid);//还款日
				//项目状态
				$temp['receive_rate'] 			= $this->_get_project_receive_rate($val['amounts'],$val['receive']);
				$temp['status_array'] 			= $this->get_project_status($val['buy_time'],$val['due_date'],$temp['receive_rate'],$val['status']);
				$data['data']['data'][$key]['status'] 	= $temp['status_array']['name'];//项目状态
//				$data['data']['data'][$key]['new_status'] 	= $temp['status_array']['new_status'];//项目新状态


//				$temp['data']['data'][$key]['can_invest'] = $temp['status_array']['can_invest'];//项目是否可以投资
//				$temp['data']['data'][$key]['mode'] = $this->_get_project_mode($val['mode']);//项目mode
			}
			$data['data']['total'] = $temp['data']['total'];
			$data['msg'] = 'ok!';
		}else{
			$data['msg'] = '暂无相关信息!';
		}

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
            'name'=>'获取用户资金统计',
            'status'=>'10001',
            'msg'=>'用户uid为空!',
            'sign'=>'',
            'data'=>array(
                'property_total' 			=> 0,//总资产
                'invest_total' 				=> 0,//累计投资
                'receive_principal_total' 	=> 0,//已收本金
                'wait_principal_total' 		=> 0,//待收本金
                'receive_interest_total' 	=> 0,//已收利息
                'wait_interest_total' 		=> 0,//待收利息
                'invest_freeze_total' 		=> 0,//投资冻结
                'transfer_freeze_total' 	=> 0//提现冻结
            )
        );
        $temp    = array();

        if( ! empty($uid)){
            $data['data']['invest_total'] 			= $this->get_user_invest_total($uid);
            $temp['principal_interest'] 			= $this->get_user_receive_principal_interest($uid);
            $data['data']['receive_principal_total']= $temp['principal_interest']['receive_principal'];
            $data['data']['receive_interest_total'] = $temp['principal_interest']['receive_interest'];
            $data['data']['wait_principal_total'] 	= $data['data']['invest_total']?round($data['data']['invest_total']-$data['data']['receive_principal_total'],2):0;
            $temp['interest_all'] 					= $this->get_user_interest_all($uid);
            $data['data']['wait_interest_total'] 	= $temp['interest_all']?round($temp['interest_all']-$data['data']['receive_interest_total'],2):0;
            $data['data']['invest_freeze_total'] 	= $this->get_user_invest_freeze($uid);
            $data['data']['transfer_freeze_total'] 	= $this->get_user_transfer_freeze($uid);
            $temp['balance'] 						= $this->get_user_balance($uid);
            $data['data']['property_total'] 		= round($temp['balance'] + $data['data']['wait_principal_total']+$data['data']['invest_freeze_total']+$data['data']['transfer_freeze_total'],2);

            $data['status'] = '10000';
            $data['msg'] 	= 'ok!';
        }

        unset($temp);
        return $data;
    }
/********************************************************聚保宝***************************************************************************************/


	/**
	 * 聚保宝项目列表
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	public function jbb_dtl_list($type_code = '',$periods_number = 0){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝项目列表',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		if( ! empty($type_code)&&$periods_number!=0){
			$temp['where'] = array(
				'select'   => '*',
				'where'    => array('type_code' => $type_code,'periods_number' => $periods_number)
			);
			$temp['data'] = $this->c->get_row(self::jbb_dtl, $temp['where']);
		}else{
			$sql = 'select temp.* from (select * from `cdb_borrow_jbb_dtl` order by `start_day` desc ,`start_time` desc ) `temp`  group by type_code order by `type_code`';
			$query =$this->db->query($sql);
			$temp['data'] = json_decode(json_encode($query->result()),true);
		}
		if(!empty($temp['data'])){
			$data = array(
				'name'=>'聚保宝项目列表',
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp['data']
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
	public function jbb($type_code = ''){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝项目',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		if( ! empty($type_code)){
			$temp['where'] = array(
				'select'   => '*',
				'where'    => array('type_code' => $type_code)
			);
			$temp['data'] = $this->c->get_row(self::jbb, $temp['where']);
		}
		if(!empty($temp['data'])){
			$data = array(
				'name'=>'聚保宝项目列表',
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp['data']
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝项目标的
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	public function jbb_list($type_code = ''){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝项目',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		if( ! empty($type_code)){
			$temp['where'] = array(
				'select'   => '*',
				'where' => array('type_code' => $type_code),
				'order_by' => '`start_day` desc,`start_time` desc'
			);
			$temp['data'] = $this->c->get_row(self::jbb_dtl, $temp['where']);
		}
		if(!empty($temp['data'])){
			$data = array(
				'name'=>'聚保宝项目列表',
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp['data']
			);
		}
		unset($temp);
		return $data;
	}


	/**
	 * 聚保宝详情列表
	 * @param string $type_code
	 * @param int $page
	 * @return array
	 */
	public function detail_jbb_list($type_code = '',$per_page = 0){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝项目',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		if( ! empty($type_code)){

			$temp['where'] = array(
				'select'   => join_field('*', self::payment_jbb).','.join_field('user_name', self::user),
                'where'    => array(
					join_field('product_type', self::payment_jbb) =>$type_code
                ),
				'order_by'    =>join_field('id', self::payment_jbb).' desc',
                'join'     => array(
                'table' => self::user,
                'where' => join_field('uid', self::payment_jbb).' = '.join_field('uid', self::user)
                )
			);
			$temp['data'] = $this->c->show_page(self::payment_jbb, $temp['where'],"",0,5,$per_page);
		}
		if(!empty($temp['data'])){
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp['data']
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝累计入团人数
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	public function jbb_nums($type_code = ''){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝累计入团人数',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		$temp['where'] = array(
			'select'   => 'count(*)',
			'where'    => array('product_type' => $type_code)
		);
		$temp['data'] = $this->c->get_one(self::payment_jbb, $temp['where']);	
		if(!empty($temp['data'])){
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>array(
					'jbb_nums' => $temp['data']
				)
			);
		}else{
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>array(
					'jbb_nums' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}




	/**
	 * 聚保宝分散投资数
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	public function jbb_invest_nums($type_code = ''){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝累计入团人数',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		$temp['where'] = array(
			'select'   => 'count(*)',
			'like'	   =>array('field'=>'type_code','match'=>$type_code,'flag'=>'both'),
			'group_by' => 'borrow_no'
		);
		$temp['data'] = $this->c->get_one(self::payment, $temp['where']);	
		if(!empty($temp['data'])){
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>array(
					'jbb_invest_nums' => $temp['data']
				)
			);
		}else{
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>array(
					'jbb_invest_nums' => 0
				)
			);
		}
		unset($temp);
		return $data;
	}



	/**
	 * 聚保宝个人中心投资列表(历史)
	 * @param string $type_code
	 * @param string $periods_number
	 * @return array
	 */
	public function jbb_per_list($uid = '',$status = 1){
		$data = $temp = array();
		$data = array(
				'name'=>'聚保宝累计入团人数',
				'status'=>'10001',
				'msg'=>'暂无相关信息!'
			);
		$temp['where'] = array(
                'select'   => join_field('*', self::payment_jbb).','.join_field('ave_rate', self::jbb).','.join_field('time_limit', self::jbb).','.join_field('type_name', self::jbb).','.join_field('allawexit', self::jbb).','.join_field('rate', self::jbb_dtl).','.join_field('closeday', self::jbb).','.join_field('isrepeat', self::jbb).','.join_field('intervaldays', self::jbb),
                'where'    => array(
                    join_field('uid', self::payment_jbb) => $uid,
                    join_field('status', self::payment_jbb) => $status
                ),
                'join'     => array(
					array(
                       'table' => self::jbb,
					   'where' => join_field('type_code', self::jbb).' = '.join_field('product_type', self::payment_jbb)
                    ),
                    array(
                       'table' => self::jbb_dtl,
                       'where' => join_field('product_type', self::payment_jbb).' = '.join_field('type_code', self::jbb_dtl).' and '.join_field('number_periods', self::payment_jbb).' = '.join_field('periods_number', self::jbb_dtl)
                    )
                
                )
            );
		if($status==2){
			$temp['where']['order_by'] = join_field('exit_time', self::payment_jbb).' desc';
		}elseif($status==3){
			$temp['where']['order_by'] = join_field('exit_audit_time', self::payment_jbb).' desc';
		}else{
			$temp['where']['order_by'] = join_field('purchase_time', self::payment_jbb).' desc';
		}
		$temp['data'] = $this->c->show_page(self::payment_jbb, $temp['where'],'',0,4);	
		if(!empty($temp['data']['data'])){
			if($status==2){
				foreach($temp['data']['data'] as $k => $v){
					$temp['data']['data'][$k]['num'] = $this->jbb_present_ranking($v['product_type'],$v['id']);
				}
			}
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp['data']
			);
		}
		unset($temp);
		return $data;
	}







	/**
	 * 聚保宝投资
	 * @param string $type_code 产品编号
	 * @param string $id 购买id
	 * @return array
	 */
	public function jbb_invest($type_code = '', $mobile = '',$security = '',$amount = 0){
		$temp = array();
        $data = array('name'=>'聚保宝投资','status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());
		$this->_set_jbb_status();
		if( !$this->is_mobile($mobile)){
			$data['msg'] 	= '对不起，您还没有登录呢!';
			$data['status'] = '10002';
			return $data;
		}

		$temp['userinfo'] = $this->_get_userinfo($mobile);
		if( ! $temp['userinfo']){
			$data['msg'] = '对不起，该手机用户不存在!';
			return $data;
		}

		//验证实名
		if($temp['userinfo']['clientkind'] != 1){
			$data['msg'] 	= '对不起，请先进行实名认证!';
			$data['status'] = '10005';
			$data['url'] = site_url('user/user/account_security'); 
			return $data;
		}

		if( ! $amount || !is_numeric($amount)){
			$data['msg'] = '对不起，投资金额不能为空!';
			return $data;
		}

		if( ! $type_code){
			$data['msg'] = '对不起，项目单号不能为空!';
			return $data;
		}

		if( ! $security){
			$data['msg'] = '对不起，资金密码不能为空!';
			return $data;
		}

		//生成资金密码
		$temp['security'] = $this->c->password($security, $temp['userinfo']['hash']);

		if( ! $temp['userinfo']['security']){
			$data['msg'] 	= '对不起，您还没有设定资金密码';
			$data['status'] = '10003';
			$data['url'] = site_url('user/user/account_security'); 
			return $data;
		}

		//验证资金密码
		if($temp['security'] != $temp['userinfo']['security']){
			$data['msg'] = '对不起，您输入的资金密码不正确';
			return $data;
		}

		//验证项目信息是否存在
		$temp['balance'] = $this->get_user_balance($temp['userinfo']['uid']); // 当前账户可用余额				
		$temp['jbb']  = $this->jbb_project($type_code);
		if( ! $temp['jbb']){
			$data['msg'] = '对不起，该项目不存在或已售罄!';
			return $data;
		}
		

		//验证用户余额
		if($temp['balance'] < $amount){
			$data['msg'] 	= '对不起，您的账户余额不足！';
			$data['status'] = '10004';
			return $data;
		}

		

		//剩余融资金额 大于最低投资金额 验证投资金额是否大于最低投资金额
		if( $amount < $temp['jbb']['start_amount']){
			$data['msg'] = '对不起，投标金额不能小于'.$temp['detail']['lowest'];
			return $data;
		}
		
		
		//单人单期投资金额
		$temp['max_user'] = $this->_get_jbb_amount($temp['jbb']['type_code'],$temp['jbb']['periods_number'],$temp['userinfo']['uid']);
		//验证是否大于最大投资金额
		if($temp['max_user']+$amount > $temp['jbb']['all_amount']){
			$data['msg'] 	= '对不起，您的投资金额大于最大投资金额！';
			$data['status'] = '10001';
			return $data;
		}


		
        //验证是否大于剩余融资金额
        if($amount > $temp['jbb']['development_amount']-$temp['jbb']['balance']){
            $data['msg'] 	= '对不起，您的投资金额大于剩余融资金额！';
            $data['status'] = '10001';
            return $data;
        }


		$temp['where'] = array(
				'select' => 'type_name',
				'where'  => array('type_code' => $type_code,'type' => 1)
			);

		$temp['jbb_name'] = $this->c->get_one(self::jbb_dtl, $temp['where']);//产品名称

		//新手标一次投标
		if($type_code=='JBB02'){
			$temp['user_one'] = $this->jbb_user_one($temp['userinfo']['uid']);
			if($temp['user_one'] > 0){
				$data['msg'] 	= '对不起，新手团只能投资一次！';
				$data['status'] = '10001';
				return $data;
			}
		}
		$temp['query']  = $this->_jbb_invest($temp['jbb']['type_code'],$temp['jbb']['periods_number'], $amount, $temp['userinfo']['uid'],$temp['balance']);
		if( ! empty($temp['query'])){
            $data['status'] 		 = '10000';
            $data['msg'] 			 = sprintf('您(尾号为%s)已成功投资【%s】项目，投资金额为:%s元。公司会定期汇报您的收益情况，祝您生活愉快！', substr($mobile, -4), $temp['jbb_name'], $amount);
            $data['data']['balance'] = round($temp['balance'] - $amount, 2);

//			$this->load->model('send_model', 'send');
//			$this->send->send_sms($mobile, $data['msg'], 0, $temp['userinfo']['uid']);


			$data = array(
						'name'=>'聚保宝投资',
						'status'=>'10000',		
						'msg'=>'投资成功,在个人中心查看投资记录！',
						'url'=>site_url('user/user/jbb')
					);
		}else{
			$data = array(
						'name'=>'聚保宝投资',
						'status'=>'10001',
						'msg'=>'投资失败，请重新尝试！'
					);
		}
		

		
        unset($temp);
        return $data;
	}



/********************************************************聚保宝*******************************************************************************************/
	
	/**
	 * 聚保宝提现排名
	 * @param string $type_code 产品编号
	 * @param string $id 购买id
	 * @return array
	 */
	private function jbb_present_ranking($type_code = '', $id = 0){
		$num = 0;
		$sql = 'select count(*) as num from `cdb_borrow_payment_jbb` where exit_time < (select exit_time from `cdb_borrow_payment_jbb`  where id = '.$id.') and status = 2 and product_type ="'.$type_code.'"';
		$query =$this->db->query($sql);
		$num  = $query->row()->num;
		return $num;
	}



	/**
	 * 聚保宝项目
	 * @param string $type_code 产品编号
	 * @param string $id 购买id
	 * @return array
	 */
	private function jbb_project($type_code = ''){
		$temp = $data =array();
		$temp['where'] = array(
			'select'   => '*',
			'where'    => array('type_code' => $type_code,'type' => 1),
			'order_by'	=> 'id desc'
		);
		$data = $this->c->get_row(self::jbb_dtl, $temp['where']);
		return $data;
	}



	/**
	 * 聚保宝新手标是否投资一次
	 * @param string $type_code 产品编号
	 * @param string $id 购买id
	 * @return array
	 */
	private function jbb_user_one($uid = 0){
		$data = $temp = array();
		$temp['where'] = array(
			'select'   => 'count(*)',
			'where'    => array('product_type' => 'JBB02','uid' => $uid)
		);
		$data = $this->c->get_one(self::payment_jbb, $temp['where']);
		return $data;
	}



	/**
	 * 聚保宝项目
	 * @param string $type_code 产品编号
	 * @param string $id 购买id
	 * @return array
	 */
	private function _get_jbb_amount($type_code = '',$periods_number = '' ,$uid = 0){
		$temp = $data =array();
		$temp['where'] = array(
			'select'   => 'sum(amount)',
			'where'    => array('product_type' => $type_code,'status' => 1,'number_periods'=>$periods_number,'uid' =>$uid),
		);
		$all_amount = $this->c->get_one(self::payment_jbb, $temp['where']);
		return $all_amount;
	}



	/**
	 * 聚保宝投资私有方法 数据库处理
	 * @param int $amount 投资金额
	 * @param string $borrow_no 借款编号
	 * @param int $balance 帐户可用余额
	 * @param int $uid
	 * @return bool
	 */
	private function _jbb_invest($type_code = '',$periods_number = 0,$amount = 0, $uid=0,  $balance = 0){
		$query = FALSE;
		$temp  = array();
		if( ! empty($amount) && !empty($type_code) && !empty($periods_number) && !empty($balance)  && !empty($uid)){
			$temp['where'] = array(
				'select' => 'rate',
				'where'  => array('type_code' => $type_code,'periods_number' => $periods_number ,'type' => 1)
			);

			$temp['rate'] = $this->c->get_one(self::jbb_dtl, $temp['where']);//利息
			
			$temp['where'] = array(
				'select' => 'type_name',
				'where'  => array('type_code' => $type_code,'periods_number' => $periods_number ,'type' => 1)
			);

			$temp['jbb_name'] = $this->c->get_one(self::jbb_dtl, $temp['where']);//产品名称
				
			$temp['where'] = array(
				'select' => 'time_limit',
				'where'  => array('type_code' => $type_code)
			);

			$temp['time_limit'] = $this->c->get_one(self::jbb, $temp['where']);//免费天数


			$temp['where'] = array(
				'select' => 'firmid,vaccid,real_name,user_name',
				'where'  => array(
					'uid' => $uid,
				)
			);
			$temp['usr'] = $this->c->get_row(self::user, $temp['where']);//用户信息
			if( ! empty($temp['rate'])){
				$MarketSerial= $this->c->transaction_no(self::payment_jbb, 'order_code');
				//$FirmId = $temp['usr']['firmid'];//$temp['usr']['firmid'];//$FirmId;// 对公账户
				//$CustName = $temp['usr']['real_name']; //$temp['usr']['real_name'];//对公账户姓名
				//$VaccId = $temp['usr']['vaccid'];//$temp['usr']['vaccid'];//对公账户姓名
				//$configData = $this->pay->touzidongjie($FirmId, $CustName,$VaccId,$MarketSerial,$borrow_no,$amount);
				//现在未做返回状态判断
				// if( ($configData['ReturnInfo']['RtnInfo']=="成功!")&&($amount == $configData['Transfer']['FreezeMoney']))
				// {
				$this->db->trans_start();

				// 添加投资记录
				$temp['payment_jbb'] = array(
					'order_code'	=> $MarketSerial,
					'product_type'	=> $type_code,
					'number_periods'=> $periods_number,
					'product_code'	=> $type_code.$periods_number,
					'uid'			=> $uid,
					'amount'		=> $amount,
					'allocated_amount' => 0,
					'matching'		=> 0,
					'purchase_time'	=> time(),
					'closing_time'	=> time(),
					'interest_day'	=> strtotime(date('Y-m-d')),
					'expected_rate'	=> round(jbb_product_amount(360,$temp['rate'],$amount)/$amount*100,2),
					'free_days'		=> $temp['time_limit'],
					'status'		=> 1
				);

				$this->c->insert(self::payment_jbb, $temp['payment_jbb']);

				// 添加资金记录
				$temp['flow'] = array(
					'uid'      => $uid,
					'type'     => 20,
					'amount'   => $amount,
					'balance'  => round($balance - $amount,2),
					'source'   => $MarketSerial,
					'remarks'  => '',
					'dateline' => time()
				);

				$this->c->insert(self::flow, $temp['flow']);

				// 更新收款金额
				$temp['where'] = array('where' => array('type_code' => $type_code,'periods_number' => $periods_number ,'type' => 1));
				$temp['data']  = array('field' => 'balance', 'value' => '`balance` + '.$amount);

				$this->c->set(self::jbb_dtl, $temp['where'], $temp['data']);

				// 更新投资人信息
				//$temp['data']  = array(
				//	'last_investor' => $uid,
				//	'last_amount'   => $amount,
				//	'last_time'     => time()
				//);

				//$this->c->update(self::borrow, $temp['where'], $temp['data']);

				$this->db->trans_complete();
				$query = $this->db->trans_status();

				if( ! empty($query)){
					// 可用余额
					//$temp['data'] = array('balance' => round($balance - $amount, 2));
					//$this->session->set_userdata($temp['data']);
					$this->_set_jbb_status(); // 更新记录状态
					$temp['content'] = sprintf('您好，您投资%s元的%s产品已经生效。每天会产生复利利息。', $amount,$temp['jbb_name']);
					$this->send_message($uid, '您好，您投资的金额已经成功！', $temp['content'],4);//发送信息
					$this->add_user_log('invest', '投资'.sprintf('¥ %s', round($amount,2)).'(聚保宝产品：'.$temp['jbb_name'].'第'.$periods_number.'期)',$uid,$temp['usr']['user_name']);//添加用户日志
				}
			}
		}

		unset($temp);
		return $query;
	}



    /**
     * 更新记录状态
     *
     * @access private
     * @return boolean
     */
    private function _set_jbb_status(){
        $query = FALSE;
        $temp = array();

        $temp['data']  = array('type' => 2);
        $temp['where'] = array(
            'where' => array('type' => 1),
            'query' =>'`development_amount` = `balance`'
        );

        $query = $this->c->update(self::jbb_dtl, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }
/********************************************************项目属性相关*******************************************************************************************/

	/**
	 * 获取项目状态
	 * @param int $buy_time 购买时间
	 * @param int $due_date 截止时间
	 * @param int $receive_rate 融资率
	 * @param int $status 状态标识
	 * @return array
	 */
    public function get_project_status($buy_time=0,$due_date=0,$receive_rate=0,$status=0){
        $status_arr = array('name'=>'','can_invest'=>FALSE,'new_status'=>0);

        if($buy_time>time()){
			$status_arr['name'] = '未开始';
			$status_arr['new_status'] = 1;
        }elseif($status == 2 && $receive_rate == 100){
			$status_arr['name'] = '融资完成';
			$status_arr['new_status'] = 3;
        }elseif($receive_rate < 100 && $due_date < time()){
			$status_arr['name'] = '投标结束';
			$status_arr['new_status'] = 6;
        }else{
			switch($status){
				case '1':
					$status_arr['name'] = '已取消';
					break;
				case '2':
					$status_arr['name'] = '募集中';
					$status_arr['can_invest'] = TRUE;
					$status_arr['new_status'] = 2;
					break;
				case '3':
					$status_arr['name'] = '复审中';
					$status_arr['new_status'] = 3;
					break;
				case '4':
					$status_arr['name'] = '还款中';
					$status_arr['new_status'] = 4;
					break;
				case '5':
					$status_arr['name'] = '流标';
					$status_arr['new_status'] = 6;
					break;
				case '6':
					$status_arr['name'] = '逾期';
					$status_arr['new_status'] = 7;
					break;
				case '7':
					$status_arr['name'] = '还款完成';
					$status_arr['new_status'] = 5;
					break;
				default:
					$status_arr['name'] = '待审核';
					break;
			}
        }

        return $status_arr;
    }

    /**
     * 获取项目mode
     * @param int $mode
     *
     * @return int|string
     */
    protected function _get_project_mode($mode=0){
        $mode_name = '';
        switch($mode){
            case '1':
                $mode_name = ' 先息后本';
                break;
            case '2':
                $mode_name = '等额本息';
                break;
            case '3':
                $mode_name = '一次性本息';
                break;
            case '4':
                $mode_name = '等额本金';
                break;
            default:
        }

        return $mode_name;
    }

    /**
     * 获取项目借款类型
     * @param $type
     *
     * @return string
     */
    protected function _get_project_type($type=0){
        $type_name = '';
        switch($type){
            case '1':
	            $type_name = ' 信用贷';
                break;
            case '2':
	            $type_name = '实物抵押';
                break;
            case '3':
	            $type_name = '担保借款';
                break;
            default:
        }

        return $type_name;
    }

	/**
	 * 扣息方式  按月扣 按日扣
	 */
	protected function _get_project_repay($repay=0){
		$repay_name = '';
		switch($repay){
			case '1':
				$repay_name = ' 按月扣除';
				break;
			case '2':
				$repay_name = '一次性扣除';
				break;
			case '3':
				$repay_name = '按日扣除';
				break;
			default:
		}

		return $repay_name;
	}

	/**
	 * 计算融资率
	 * @param int $amount 总金额
	 * @param int $receive 已收金额
	 * @param int $buy_time 开投时间
	 * @return float|int
	 */
	protected function _get_project_receive_rate($amount=0,$receive=0,$buy_time=null){
		$receive_rate = 0;

		if($receive){
			if($receive / $amount * 100 >0 && $receive / $amount * 100<1){
				$receive_rate = 1;
			}else if(($receive / $amount * 100)>99 && ($receive / $amount * 100)<100){
				$receive_rate = 99;
			}else{
				$receive_rate=round($receive / $amount * 100);
			}
		}else{
			$receive_rate = 0;
		}

		//未开始投标时过滤自动投的部分投资金额
		if(!is_null($buy_time) && $buy_time > time())$receive_rate = 0;

		return $receive_rate;

	}

/***************************计息相关**********************************************************/

	/**
	 * 获取用户特定项目利息
	 * @param string $borrow_no
	 * @param int $uid
	 * @param int $amount
	 * @param int $rate
	 * @param int $months
	 * @param int $mode
	 * @return float|int
	 */
	public function get_project_user_interest($borrow_no='',$uid=0,$amount=0,$rate=0,$months=0,$mode=0){
		$interest = 0;

		if($borrow_no && $uid){
			//查询已还款的利息  如果已收利息大于
			$repay_interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('borrow_no'=>$borrow_no,'type'=>3,'status'=>1,'uid'=>$uid)));
			if($repay_interest && $repay_interest>$amount){
				$interest = round($repay_interest-$amount,2);
			}else{
				$interest = $this->get_project_interest($amount,$rate,$months,$mode);
			}
		}

		return $interest;
	}

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
	 * 项目计息日
	 * @param int $status
	 * @param int $due_date
	 * @param int $confirm_time
	 * @return int
	 */
	public function get_project_interest_start_time($status=0,$due_date=0,$confirm_time=0){
		$start_time = 0;
//		if($status == '2' || $status == '3'){
//			$start_time = $due_date;
//		}
		if($status == '4' || $status == '7'){
			$start_time = $confirm_time-86400;
		}else{
			$start_time = $due_date;
		}
		return $start_time;
	}

	/**
	 * 用户的特定项目最近还款日
	 * @param string $borrow_no
	 * @param int $uid
	 * @return int
	 */
	public function get_project_lately_repayment_time($borrow_no='',$uid=0){
		$lately_time=0;
		$temp = array();
		if($borrow_no && $uid){
			$temp['where'] = array(
				'select'   => 'pay_date',
				'where'    => array('uid' => $uid, 'type' => '3' , 'borrow_no' => $borrow_no )
			);
			$lately_time = $this->c->get_one(self::payment, $temp['where']);
			if( ! $lately_time){
				$temp['repay_plan']	=$this->get_project_repayment_list($borrow_no);
				$lately_time 		= date('Ymd',$temp['repay_plan']['data'][0]['repay_date']);
			}
		}

		return $lately_time;
	}

/*****************************还款计划相关wsb****************************************/

	/**
	 * 获得已生成的还款计划
	 * @param $borrow_no
	 * @return array
	 */
	public function get_exists_repayment_plan($borrow_no=''){
		$query=$temp=array();

		if( ! empty($borrow_no)){
			$temp['where']=array(
				'where' 	=> array('borrow_no'=>$borrow_no),
				'select' 	=> 'repay_index,repay_date,repay_type,repay_amount,repay_principal,repay_interest,repay_surplus_principal,rapay_time,dateline,status',
				'order_by'	=> 'repay_index ASC'
			);
			$temp['data']=$this->c->get_all(self::repay,$temp['where']);
			if(! empty($temp['data'])){
				foreach($temp['data'] as $k=>$v){
					$temp['data'][$k]['repay_type'] = $this->_get_repayment_type($v['repay_type']);
					$temp['data'][$k]['repay_date'] = strtotime($v['repay_date']);
				}
				$query=$temp['data'];
			}
		}

		unset($temp);
		return $query;
	}

	/**
	 * 获得还款计划数据
	 * @param int $mode 计息方式
	 * @param int $amount 金额
	 * @param int $rate 利率
	 * @param int $months 月数
	 * @param int $repay
	 * @return array
	 */
	public function get_repayment_plan($mode=1,$amount=0,$rate=0,$months=0,$repay=1){
		$query = $temp = array();

		switch($mode){
			case 1:
				$query=$this->get_xxhb_repayment_list($amount,$rate,$months,$repay);
				//先息后本  最后的还本
				$query[]=array(
					'principal' 		=> $amount,
					'interest' 			=> 0,
					'amount' 			=> $amount,
					'surplus_principal' => 0
				);
				break;
			case 2:
				$query=$this->get_debx_repayment_list($amount,$rate,$months);
				break;
			case 3:
				$temp['interest']=$this->get_ycxbx_interest($amount,$rate,$months);

				$temp['data'][1]['principal'] 			= 0; //月付本金
				$temp['data'][1]['interest']		 	= $temp['interest'];//月付利息
				$temp['data'][1]['amount'] 				= $temp['interest'];//月付本金和利息总额
				$temp['data'][1]['surplus_principal'] 	= $amount;//剩余本金

				$temp['data'][2]['principal'] 			= $amount; //月付本金
				$temp['data'][2]['interest'] 			= 0;//月付利息
				$temp['data'][2]['amount'] 				= $amount;//月付本金和利息总额
				$temp['data'][2]['surplus_principal'] 	= 0;//剩余本金

				$query=$temp['data'];
				break;
			case 4:
				$query=$this->get_debj_repayment_list($amount,$rate,$months);
				break;
		}

		unset($temp);
		return $query;
	}

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
	public function get_borrow_interest($mode=1,$amount=0,$rate=0,$months=0,$repay=1,$index=1){
		$query=0;
		$temp=array();

		switch($mode){
			case 1:
				$temp['data']=$this->get_xxhb_repayment_list($amount,$rate,$months,$repay);
				$query=$temp['data'][$index]['amount'];
				break;
			case 2:
				$temp['data']=$this->get_debx_repayment_list($amount,$rate,$months);
				$query=$temp['data'][$index]['amount'];
				break;
			case 3:
				$temp['data']=$this->get_ycxbx_interest($amount,$rate,$months);
				$query=$temp['data'];
				break;
			case 4:
				$temp['data']=$this->get_debj_repayment_list($amount,$rate,$months);
				$query=$temp['data'][$index]['amount'];
				break;
			default;

		}

		unset($temp);
		return $query;
	}

	/**
	 * 等额本息还款 计划记录
	 * @param $amount  float 贷款总额
	 * @param $rate    float 年利率
	 * @param $months  int 总期数
	 * @return array   二维数组
	 */
	public function get_debx_repayment_list($amount,$rate,$months){
		$query=$temp=array();

		if( ! empty($amount) && ! empty($rate) && ! empty($months)){
			$temp['m_rate']=($rate/100)/12;//月利率
			$temp['m_amount']=$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额 (a*i*(1+i)^n)/((1+i)^n-1)
			$temp['pay_principal']=0;//已付本金

			for($i=1;$i<=$months;$i++){
				if($i != $months){
					$temp['data']['amount'] 		   = round($temp['m_amount'],2);//月付本金和利息总额
					$temp['data']['interest'] 		   = ($amount*$temp['m_rate']-$temp['m_amount'])*pow((1+$temp['m_rate']),$i-1)+$temp['m_amount'];//月付利息 a*i-b *(1+i)^(n-1)+b
					$temp['data']['interest']	 	   = substr($temp['data']['interest'],0,strpos($temp['data']['interest'],'.')+3);//保留两位 不四舍五入
					$temp['data']['principal'] 		   = round($temp['m_amount']-$temp['data']['interest'],2); //月付本金
					$temp['pay_principal'] 			  += $temp['data']['principal']; //累加 已付本金
					$temp['data']['surplus_principal'] = round($amount-$temp['pay_principal'],2);//剩余本金
				}else{
					$temp['data']['principal'] 			= $query[$i-1]['surplus_principal'];
					$temp['data']['interest'] 			= $temp['data']['principal']*$temp['m_rate'];
					$temp['data']['interest'] 			= substr($temp['data']['interest'],0,strpos($temp['data']['interest'],'.')+3);//保留两位 不四舍五入
					$temp['data']['amount'] 			= $temp['data']['principal']+$temp['data']['interest'];
					$temp['data']['surplus_principal'] 	= 0;
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
	public function get_debx_all_interest($amount,$rate,$months){
		$temp=array();

		$temp['m_rate']   = ($rate/100)/12;//月利率
		$temp['m_amount'] = $amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额

		return round(($months*$temp['m_amount']-$amount),2);
	}

	/**
	 * 等额本金 还款计划列表
	 * @param $amount  float 贷款总额
	 * @param $rate    float 年利率
	 * @param $months  int 总期数
	 * @return array
	 */
	public function get_debj_repayment_list($amount,$rate,$months){
		$query=$temp=array();

		if( ! empty($amount) && ! empty($rate) && ! empty($months)){
			$temp['m_rate']=($rate/100)/12;//月利率

			for($i=1;$i<=$months;$i++){
				$temp['data']['principal'] 			= round($amount/$months,2); //月付本金
				$temp['data']['interest'] 			= round(($amount-($i-1)*$temp['data']['principal'])*$temp['m_rate'],2);//月付利息
				$temp['data']['amount'] 			= round($temp['data']['principal']+$temp['data']['interest'],2);//月付本金和利息总额
				$temp['data']['surplus_principal'] 	= round($amount-$i*$temp['data']['principal'],2);//剩余本金
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
	public function get_debj_all_interest($amount,$rate,$months){
		return round(($months+1)*$amount*(($rate/100)/12)/2,2);
	}

	/**
	 * 一次性本息 利息
	 */
	public function get_ycxbx_interest($amount,$rate,$months){
		return round($amount*(($rate/100)/360)*($months*30),2);
	}

	/**
	 *先息后本 还款计划列表
	 * @param $amount int 数量
	 * @param $rate  int 利率
	 * @param $months int 期数
	 * @param $repay_type int 付息方式  按日3 按月1
	 *
	 * @return array
	 */
	public function get_xxhb_repayment_list($amount=0,$rate=0,$months=0,$repay_type=1){
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
						$query[$i]['principal'] 		= 0;
						$query[$i]['interest'] 			= round($amount*($rate/100/360)*($months*30-($i-1)*30),2);
						$query[$i]['amount']   			= $query[$i]['interest'];
						$query[$i]['surplus_principal'] = $amount;
					}else{
						$query[$i]['principal'] 		= 0;
						$query[$i]['interest'] 			= round($amount*($rate/100/12),2);
						$query[$i]['amount'] 			= $query[$i]['interest'];
						$query[$i]['surplus_principal'] = $amount;
					}
				}
			}

		}

		unset($temp);
		return $query;
	}

	/**
	 * 获取计划还款日
	 *
	 * @access private
	 * @param  integer $confirm_time 确认时间
	 * @param  integer $months 还款期数
	 * @param  integer $mode 1先息后本  2等额本息   3 一次性还本付息 4等额本金
	 * @param  integer $repay 1按月扣除 2一次性扣除 3按日扣除
	 * @return array
	 */
	public function get_repayment_date($confirm_time = 0, $months = 0, $mode=1, $repay=1){
		$aDay = array();

		$day   = "17"; //发布日天数
		$month = date('n', $confirm_time); //发布日月数
		$year  = date('Y', $confirm_time); //发布日年数

		if($mode == 3){ //一次性还本付息 只有一期  最后的时间
			//			$aDay[0]="1";
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

	/**
	 * 获取 还款时记录的类型（利息 本金 本息）
	 * @param int $type 类型id
	 *
	 * @return string
	 */
	protected function _get_repayment_type($type=1){
		$type_name = '';

		switch($type){
			case '1':
				$type_name = '利息';
				break;
			case '2':
				$type_name = '本金';
				break;
			case '3':
				$type_name = '本息';
				break;
			default:
				$type_name = '利息';
		}

		return $type_name;
	}

/********************************************************************************************/

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
	 * @param int $replace_show_max
	 *
	 * @return string
	 */
	protected function _secret($string = '', $start=0, $length = 0, $replace_show_max=0, $replace = '*'){
		if(empty($string)) return '';

		$str  = '';
		$temp = array();

		$temp['arr']   = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
		$temp['start'] = $start?$start-1:round((count($temp['arr']) - $length) / 2);
		$temp['end']   = $temp['start'] + $length;

		$temp['replace_count'] = 0;
		if($replace_show_max > 0 && $replace_show_max > $temp['end']-$temp['start'])$replace_show_max = $temp['end']-$temp['start'];
		for($i = $temp['start']; $i < $temp['end']; $i++){
			if($replace_show_max > 0){
				if($temp['replace_count'] <= $replace_show_max){
					$temp['arr'][$i] = $replace;
					$temp['replace_count']++;
				}else{
					unset($temp['arr'][$i]);
				}
			}else{
				$temp['arr'][$i] = $replace;
			}
		}
		$str = implode('', $temp['arr']);

		unset($temp);
		return $str;
	}

	/**
	 * 查询用户信息
	 * @param string $mobile
	 * @return array
	 */
	protected function _get_userinfo($mobile=''){
		$userinfo = array();

		if($mobile){
			$userinfo = $this->c->get_row(self::user,array('select'=>'user_name,uid,clientkind,hash,security','where'=>array('mobile'=>$mobile)));
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
	 * 获取用户余额
	 * @param int $uid
	 * @return int
	 */
	public function get_user_balance($uid=0){
		$balance = 0;
		$temp    = array();

		if($uid){
			$temp['where'] = array(
				'select'   => 'balance',
				'where'    => array('uid' => $uid),
				'order_by' => 'id desc'
			);

			$balance = $this->c->get_one(self::flow, $temp['where']);
		}

        if(is_null($balance))$balance=0;
		unset($temp);
		return $balance;
	}

	/**
	 * 获取简单版的项目详情
	 * @param string $borrow_no
	 * @return array
	 */
	public function get_borrow_detail($borrow_no = ''){
		$data = $temp = array();

		if( ! empty($borrow_no)){
			$temp['where'] = array(
				'select'   => '`lowest`,`amount` - `receive` AS `surplus`,`uid`,subject,status,buy_time,due_date,amount,receive,max',
				'where'    => array('borrow_no' => $borrow_no)
			);

			$data = $this->c->get_row(self::borrow, $temp['where']);
		}

		unset($temp);
		return $data;
	}


/********************投资时用到**************************************************************************************/

    /**
     * 发送信息
     *
     * @access public
     * @param  integer $uid     会员ID
     * @param  string  $subject 主题
     * @param  string  $content 消息内容
     * @param  int  $type 消息类型
     * @return boolean
     */
    public function send_message($uid = 0, $subject = '', $content = '',$type=0){
        $query = FALSE;
        $data  = array();

        if( ! empty($uid) && ! empty($subject) && ! empty($content)){
            $data = array(
                'uid'       => $uid,
                'subject'   => $subject,
                'content'   => $content,
                'type'  	=> $type,
                'send_time' => time()
            );

            $query = $this->c->insert(self::message, $data);
        }

        unset($data);
        return $query;
    }

    /**
     * 更新记录状态
     *
     * @access private
     * @return boolean
     */
    private function _set_borrow_status(){
        $query = FALSE;
        $temp = array();

        $temp['data']  = array('status' => 3, 'sort_order' => 900);
        $temp['where'] = array(
            'where' => array('status' => 2),
            'query' =>'`amount` = `receive`'
        );

        $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 添加会员日志
     *
     * @access public
     * @param  string   $module    模块名称
     * @param  string   $content   日志内容
     * @param  integer  $uid       会员ID
     * @param  string   $user_name 会员姓名
     * @return boolean
     */
    public function add_user_log($module = '', $content = '', $uid = 0, $user_name = ''){
        $query = FALSE;
        $logs  = array();

        if( ! empty($module) && ! empty($content)){
            $logs = array(
                'uid'       => $uid,
                'user_name' => $user_name,
                'module'    => $module,
                'content'   => $content,
                'dateline'  => time()
            );

            if( ! empty($logs['uid']) && ! empty($logs['user_name'])){
                $query = $this->c->insert(self::log, $logs);
            }
        }

        unset($logs);
        return $query;
    }

/***************************************用户资金统计相关方法 涉及的数据表 borrow payment transfer*********************************************************/
    /**
     * 用户投资总额
     * @param int $uid
     * @param int $category
     * @return float|int
     */
    public function get_user_invest_total($uid=0,$category=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(
                    join_field('uid', self::payment) => $uid,
                    join_field('type', self::payment) => 1,
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

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 查询 borrow_payment 表  获得 已收本金 和利息
     * 累计收益  已还本金
     * @param int $uid
     * @return array
     */
    public function get_user_receive_principal_interest($uid=0){
        $rs = array('receive_principal'=>0,'receive_interest'=>0);
        if($uid > 0){
            $borrow = $this->c->get_all(self::payment,array(
                'select'   => 'borrow_no,SUM(amount) as amounts',
                'where'    => array('uid'=>$uid,'type'=>1),
                'group_by' => 'borrow_no'
            ));

            if( ! empty($borrow)){
                foreach ($borrow as $key => $value) {
                    $interest = $this->c->get_one(self::payment,array(
                        'select' => 'SUM(amount)',
                        'where'  => array('uid'=>$uid,'type'=>3,'borrow_no'=>$value['borrow_no'])
                    ));
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
                        'select' => 'SUM(amount)',
                        'where'  => array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$uid)
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
        $_GET['limit'] = (int)$page_size;
        $_GET['per_page'] = (((int)$page_id-1)*(int)$page_size);
    }

	protected function _project_rate_format($rate){
		if(strpos($rate,'.') !== FALSE){ //有小数点
			$new_rate=rtrim($rate,0);
			if(strpos($new_rate,'.') == strlen($new_rate)-1){
				$new_rate=rtrim($new_rate,'.');
			}
			return $new_rate;
		}
		return $rate;
	}
}