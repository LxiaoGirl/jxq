<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model{
    const borrow  = 'borrow';           //借款
    const payment = 'borrow_payment';   //投资
    const cate    = 'product_category'; //分类
    const settle  = 'jujian_jiesuan';   //居间人 结算表
    const user    = 'user';             //用户表
    const redbag  = 'redbag';           //红包
	const snowballdtl  = 'snowballdtl'; //雪球
	const flow    = 'cash_flow'; //资金表

    public function __construct(){
        parent::__construct();
		$this->load->model('common_model','c');
		$this->load->model('api/cash_model','cash');
    }



	/**
     * 我的红包列表
	 *$f 红包状态 （1已领取  0 是未领取 100过期）
	 *$uid 用户uid
	 *$id 红包id
    */
    public function My_redbag_list($f=0,$uid=0,$id = 0){
        $data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
        $temp = array();
		$temp['uid'] = $uid;
			if($f==1){
				 $temp['where'] = array(
					'select' => '*',
					'order_by' => 'receive_time desc',
					'where'  => array('uid' => $temp['uid'], 'status' => $f )
				);
				 $temp['data'] = $this->c->show_page(self::redbag, $temp['where'],'',0,5);
			}
			if($f==0){
				 $temp['where'] = array(
					'select' => '*',
					'order_by' => 'id desc',
					'where'  => array('uid' => $temp['uid'], 'status' => $f, 'deadline < ' => time())
				);
				$temp['data'] = $this->c->show_page(self::redbag, $temp['where'],'',0,5);			
			}
			if($f==100){
				 $temp['where'] = array(
					'select' => '*',
					'order_by' => 'id desc',
					'where'  => array('uid' => $temp['uid'], 'deadline > ' => time(),'status' => 0)
				);
				$temp['data'] = $this->c->show_page(self::redbag, $temp['where'],'',0,5);			
			}
		if($id != 0){
			$temp['where'] = array(
					'where'  => array('id' => $id, 'deadline < ' => time(),'uid' => $temp['uid'])
				);
			$temp['data'] = $this->c->get_row(self::redbag, $temp['where']);
			if(!empty($temp['data'])){
				$data= array(
					'status' => '10000',	
					'msg' => 'ok',
					'data' => $temp['data']
					); 
			}else{
				$data['msg']= '系统繁忙,请稍后再试！'; 
			}
		}else{	
		if($temp['uid']!=0&&!empty($temp['uid'])){
			if(!empty($temp['data']['data'])){
				$data=array(
					'status' => '10000',	
					'msg' => 'ok',
					'data' => $temp['data']
				);
			}else{
				$data['msg']= '暂无相关信息！';
			}
		}else{
			$data['msg']='非法操作!';
		}
		}
        unset($temp);
        return $data;
    }



	/**
     * 我的红包个数
     *$uid 用户uid
    */
   public function receive_red_num($uid=0,$status=200){
        $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
        $temp['uid'] = $uid;
        $temp['where'] = array(
                            'select' => 'count(*) AS `num`' 
                        );
		if($status!=200){
			$temp['where']['where'] = array('uid' => $temp['uid'],'status' => $status);
		}else{
			$temp['where']['where'] =array('uid' => $temp['uid']);
		}
        $rate = $this->c->get_row(self::redbag, $temp['where']);
		if(!empty($rate)){
			$data=array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => array(
					'num' => $rate['num']
				)
			);
		}
        unset($temp);
        return $data;
    }



	/**
     * 领取红包
     *$uid  用户uid
	 *$id 红包id
    */
	public function Receive_redbag($uid=0,$id=0){
		$data=$temp=array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'操作错误!');
		$temp['id'] = $id;//获取红包id
		$temp['where'] = array(
				'select'   => 'amount',
                'where'    => array('id' => $temp['id'],'uid' => $uid,'status'=>'0')
            );
		$temp['amount'] = $this->c->get_one(self::redbag , $temp['where']);
		$temp['where'] = array(
				'select'   => 'count(*)',
                'where'    => array('id' => $temp['id'],'uid' => $uid,'status'=>'0')
            );
		$temp['num'] = $this->c->get_one(self::redbag , $temp['where']);
		if(empty($uid)||$uid=='0') return array('url'=>site_url('user'),'status'=>'10001','msg'=>'您还没有登录!');
		if(empty($temp['amount'])) return array('status'=>'10001','msg'=>'数据不全!');
		if($temp['num']!=1) return array('status'=>'10001','msg'=>'红包不存在或已领取!');
		//$_SESSION['balance']=($_SESSION['balance']+$temp['amount']);//修改session 中的  余额值
		$temp['balance'] = $this->cash->get_user_balance($uid);//获得余额(******)
		$temp['balance']=$temp['balance']['data']['balance'];
		$temp['recharge']=$this->c->transaction_no(self::redbag, 'id');
		$temp['where'] = array(
								'uid'      => $uid,
								'type'     => 11,
								'amount'   => $temp['amount'],
								'balance'  => round($temp['balance'] + $temp['amount'], 2),
								'source'   => $temp['recharge'],
								'remarks'  => '红包',
								'dateline' => time()
							);
		 $temp['data']=$this->c->insert(self::flow, $temp['where']);
		if(empty($temp['data'])){
			return  $data=array(
				'status' => '10001',
				'msg' => '系统错误!'
			);
		}
		$temp['where_update'] = array(
                'where'    => array('id' => $temp['id'] )
            );
		$temp['red']=$this->c->update(self::redbag , $temp['where_update'] , array('status' => "1","receive_time" => time()));
		if(!empty($temp['red'])){
			$data=array(
				'status' => '10000',
				'msg' => '红包领取成功!'
			);
		}else{
			$data=array(
				'status' => '10001',
				'msg' => '系统错误!'
			);
		}
        return $data;
	}


	/**
     * 我的雪球总数
     *$uid 用户uid
    */
	public function My_snowball_total($uid=0){
		$data=$temp=array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
		$temp['where'] = array(
                            'select'   => 'balance',
                            'where'    => array('uid' => $uid),
                            'order_by' => 'id desc'
                        ); 
		$temp['balance'] = $this->c->get_one(self::snowballdtl, $temp['where']);
		if(!empty($temp['balance'])){
			$data=array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => array(
					'snowball_total' => $temp['balance']
					)
				);
		}else{
			$data['msg'] = "服务器繁忙,请稍后再试！";
		}
		return $data;
	}



	/**
     * 我的雪球明细
     *$uid 用户id
	 *$status 雪球状态 1 获得 2 使用
    */
	public function My_snowball($uid=0,$status=0){
		$data=$temp=array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'暂无相关信息!');
		$temp['status'] = $status;
			switch($temp['status']){
                case 1://收入 待确定
                    $temp['in_str'] = '1';
                    break;
                case 2://支出
                    $temp['in_str'] = '0';
                    break;
                default:
                    $temp['in_str'] = '';

            }

            $temp['where'] = array(
                'where'=>array(
                    'uid'=>$uid,
                )
            );
		if(!empty($temp['in_str'])){
			$temp['where']['where']['flag']=$temp['in_str'];
		}
        $data['snowballdtl_list'] = $this->c->show_page(self::snowballdtl,$temp['where'],'',0,5);
		if(! empty($data['snowballdtl_list']['data'])){
			$data=array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => $data['snowballdtl_list']
				);
		}
		unset($temp);
		return $data;
	}



	/**
     * 居间人收益结算
     *
    */
	public function get_settle_amount($uid=0){
		$data =$temp = array();
		$data = array('data'=>array('jujian_amount' => 0),'status'=>'10001','msg'=>'暂无相关信息!');
        $temp['uid'] = $uid;

        if($temp['uid'] > 0){
            $temp['jujian_amount'] = $this->c->get_one(self::settle,array('select'=>'SUM(jujian_amount)','where'=>array('inviter'=>$temp['uid'],'status'=>1)));
        }else{
			$data['msg'] = '非法操作！';
			return $data;
		}

        if(!empty($temp['jujian_amount'])){
			$data=array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => array(
					'jujian_amount' => $temp['jujian_amount']
					)
				);
		}

        unset($temp);
        return $data;
	}



	/**
     * 居间人用户列表
     *
    */
	public function get_intermediary_user($show_page=TRUE,$uid=0){
		$data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
        if($uid){
			$temp['where'] = array(
					'select'   => 'SUM('.join_field('amount', self::payment).') as amount,'.join_field("user_name,uid,last_date,real_name",self::user),
					'where'    => array(join_field('inviter', self::user) => $uid),
					'join'     => array(
							array('table' => self::payment, 'where' => join_field('uid', self::payment).' = '.join_field('uid', self::user).' AND '.join_field('type', self::payment).'=1 '),
							array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow).' AND '.join_field('status',self::borrow).' in(4,7)')
					),
					'order_by'=>'SUM('.join_field('amount', self::payment).') DESC',
					'group_by'=>join_field("uid",self::user)
			);
            if(!$show_page){
                $temp['data'] = $this->c->get_all(self::user,$temp['where']);
            }else{
                $temp['all_data'] = $this->c->show_page(self::user,$temp['where'],"",0,3);
                $temp['data'] = $temp['all_data']['data'];
            }

            if($temp['data']){
                foreach($temp['data'] as $k=>$v){
                    $v['active_level'] = date('Y-m-d',$v['last_date']).'<br/>'.date('H:i:s',$v['last_date']);
					$v['amount'] = (float)$v['amount'];
                    $v['ralation']     = $this->_get_intermediary_ralation($v['amount']);
                    $temp['data'][$k]          = $v;
                }

                if($show_page){
                    $temp['all_data']['data'] = $temp['data'];
                    $data['page'] = $temp['all_data'];
                }else{
                    $data['page'] = $temp['data'];
                }
				$data =array(
						'status' => '10000',
						'msg' => 'ok',
						'data' =>  $data['page']
						);
            }
        }

        return $data;
	}



	/**
     * 居间人用户之投资列表
     *
    */
	public function get_commission_list($uid=0){
		$data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');

        if($uid > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,amount as invest_amount,dateline', self::payment).','
                                .join_field('subject as subject_1,status,months,mode,receive', self::borrow).','
                                .join_field('amount', self::borrow).' as all_amounts,'.join_field('category',self::cate),
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
				'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'order_by' => array(
                    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
                )
            );

            $temp['page'] = $this->c->get_all(self::payment, $temp['where']);
			if(!empty($data)){
			$data=array(
				'status' => '10000',
				'msg' => 'ok',
				'data' =>  $temp['page']
				);
			}
        }
		
        unset($temp);
        return $data;
	}




    /**
     * 居间人 结算记录
     * @param int $uid
     * @return array
     */
    public function get_settle_list($uid=0){
        $data = $temp = array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
        $temp['uid'] = $uid;
        $temp['this_month'] = date('Ym',time());
        if($temp['uid'] > 0){
            $temp = $this->c->show_page(self::settle,array(
                'select'  => 'real_month,SUM(jujian_amount) as amount,pay_time,status',
                'where'   =>array(
                    'inviter' =>$temp['uid'],
                    'real_month <='=>$temp['this_month']
                    ),
                'group_by'=>'real_month',
                'order_by'=>'real_month DESC'
                )
            ,"",0,3);
			if(!empty($temp)){
				$data = array(
					'status'=>'10000',
					'msg'=>'ok!',
					'data'=>$temp
				);
			}
        }

        unset($temp);
        return $data;
    }




    /**
     * 结算部分 按结算时间 的用户投资列表
	 *$uid  居间人uid
     * @param  integer $start_time [description]
     * @return [type]              [description]
     */
    public function get_settle_invest_list($real_month=0,$uid=0){
        $data = $temp =array();
		$data = array('data'=>array(),'status'=>'10001','msg'=>'没有相关信息!');
        if(empty($real_month)){ 
			$data['msg'] = '月份不能为空！';
			return $data;
		}
        $temp['where'] = array(
            'where'=>array(
                join_field('real_month',self::settle)=>$real_month,
                join_field('inviter',self::settle)=>$uid,
				join_field('type',self::payment)=>1
            ),
            'select'=>join_field('subject',self::borrow).','
                .join_field('amount',self::settle).' as invest_amount,'
                .join_field('user_name',self::user).','
				.join_field('id',self::settle).','
                .join_field('jujian_amount',self::settle).' as settle_amount,'
                .join_field('real_day',self::settle).','
				.join_field('pay_time',self::payment),
            'join'=>array(
                array('table' => self::borrow, 'where' => join_field('borrow_no', self::settle).' = '.join_field('borrow_no', self::borrow)),
                array('table' => self::user, 'where' => join_field('uid', self::settle).' = '.join_field('uid', self::user)),
				array('table' => self::payment, 'where' => join_field('payment_no', self::settle).' = '.join_field('payment_no', self::payment))
            ),
            'order_by'=>join_field('user_name',self::user)   
        );

        $temp = $this->c->get_all(self::settle,$temp['where']);
		if(!empty($temp)){
			$data = array(
				'status'=>'10000',
				'msg'=>'ok!',
				'data'=>$temp
			);
		}
        unset($temp);
        return $data;
    }




    /**
     * 用户投资总额
     * @param int $uid
     * @return float|int
     */
    public function get_user_invest_all($uid=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'SUM('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow))
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }



    /**
     * 居间人 客户 关系
     * @param  integer $amounts [总金额]
     * @return [string]         [关系]
     */
    protected function _get_intermediary_ralation($amount=0){
        $ralation = '--';
        if($amount>0&&$amount<=10000){
            $ralation = '过客';
        }else if($amount>10000&&$amount<=100000){
            $ralation = '朋友';
        }else if($amount>100000&&$amount<=500000){
            $ralation = '闺蜜';
        }else if ($amount>500000&&$amount<=5000000){
            $ralation = '亲人';
        }else if($amount>5000000&&$amount<=10000000){
            $ralation = '挚友';
        }else if($amount>10000000&&$amount<=50000000){
            $ralation = '福将';
        }else if ($amount>50000000){
            $ralation = '财神';
        }
        return $ralation;
    }
}