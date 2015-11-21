<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 会员管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Invite_model extends CI_Model
{
    const admin       = 'admin'; //用户表
    const address     = 'user_address'; // 地址信息
    const borrow      = 'borrow'; // 借款记录
    const flow        = 'cash_flow'; // 现金流
    const group       = 'user_group'; // 会员分组
    const info        = 'user_info'; // 扩展信息
    const payment     = 'borrow_payment'; // 投资还款记录
    const recharge    = 'user_recharge'; // 充值记录
    const region      = 'region'; // 地区
    const transaction = 'user_transaction'; // 提现记录
    const user        = 'user'; // 会员表
    const jujian_jiesuan        = 'jujian_jiesuan'; // 结算表
	
	public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
        $this->config->load('lv');//加载居间人等级配置

    }
    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

   // public function show_page()
    public function processing()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => 'uid,user_name,real_name,email,mobile,nric,reg_date,last_date,status,clientkind,nric_image',
                            'order_by' => 'uid desc',
							'where'  => array('inviter_no <>' =>'0'),
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (preg_match('/^1[345789](\d){9}$/', $temp['keyword'])) ? 'mobile' : 'user_name';
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::user, $temp['where']);

		foreach($data['data'] as $k => $v)
		{
            $temp['where']['where'] = array('inviter' => $v['uid']);
	        $count = $this->c->count(self::user, $temp['where']);
			$data['data'][$k]['count']= $count;
		}
		

        unset($temp);
        return $data;
    }

	
	 /**
     * 结算用户
     *
     * @access public
     * @return array
     */

    //public function processing()
	public function show_page()
	{
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'uid,user_name,real_name,email,mobile,nric,reg_date,last_date,status,clientkind,nric_image,lv',
                            'order_by' => 'uid desc',
							'where'  => array('inviter_no <>' =>'0'),
                        );
        $data = $this->c->show_page(self::user, $temp['where']);
		
		//查询当月日期时间段落		
		$start = date('Y-m-01', strtotime(date("Y-m-d")));
		$start = date('Y-m-01', strtotime("$start -1 day"));
		$end =  date('Y-m-d', strtotime("$start +1 month -1 day"));
		$data['start'] = $start;
		$data['end'] = $end;
		$start = strtotime($start);
		$end = strtotime($end);
		$end = $end + 24*60*60 - 1;		
		//日期处理		

		//获取到还款中和已还款的借款信息
		$temp['where'] ="";
		$temp['where'] = array(
							'select' => 'borrow_no,subject,deadline,months',
							'where_in' => array('field' => 'status', 'value' => "4")
    	);				
		$borrow_no = $this->c->get_all(self::borrow, $temp['where']);

		//将获取的单号进行合并

		foreach($borrow_no as $k => $v)
		{
			$v['deadline'] = $v['deadline'] - 24*60*60;
			//有效时常计算
			if($v['deadline'] >= $start && $v['deadline']<= $end){
				if($v['deadline'] >= $start){
					$date = date('Ymd', $end)-date('Ymd', $v['deadline']);
					if($date>$v['months']*30){
						$date = $v['months']*30;
					}
				}elseif($v['deadline'] <= $end){
					$date = date('Ymd', $v['deadline']) - date('Ymd', $start);
				}
				$day[$v['borrow_no']]= $date;
				$r[]=$v['borrow_no'];
			}
		}

		foreach($r as $k => $v)
		{

			if($k==0){
				$borrow = "'".$v."'";
			}else{
				$borrow = $borrow.",'".$v."'";
			}		
		}

		
		//以原生数据库方法进行联查用户投资信息
		foreach($data['data'] as $k => $v)
		{
			$temp['where'] ="";
            $temp['where']['where'] = array('inviter' => $v['uid']);
	        $inviter = $this->c->get_all(self::user, $temp['where']);	
	        $count = $this->c->count(self::user, $temp['where']);
			$data['data'][$k]['count']= $count;			
			foreach($inviter as $m => $n){
				if(!empty($borrow)){
					$query = $this->db->query("SELECT * FROM `p2p`.`cdb_borrow_payment` where status = 1  and  type = 1 and  uid = ".$n['uid']." and borrow_no in (".$borrow.") ORDER BY `id` DESC  LIMIT 0,50;");
					$youxiao =  $query->result();			
					$object =  json_decode(json_encode($youxiao),true);
					foreach($object as $o => $p){
						//读取用户LV等级配置
						$rate = $this->config->item('lv'.$v['lv']);
						//居间人计算公式
						$jujianren = $p['amount']*$rate/360*$day[$p['borrow_no']];
						$data['data'][$k]['jujianren'] += $jujianren;
						//预留入库操作						
					}
				}
			}
		}
		//获取的到的用户订单数据进行封帐处理
		//金额处理
		
		

        unset($temp);
        return $data;
    }
	
    /*******************************************************************************/
    public function get_one(){
		
        $v['uid'] = $this->input->get('uid', TRUE);
        $start = $this->input->get('start_time', TRUE);
        $end = $this->input->get('end_time', TRUE);
		
		if(!empty($start)){
			$start_time = date('Y-m-01', strtotime(date($start)));
			//$start = strtotime($start);
		}else{
			$start = date('Y-m-01', strtotime(date("Y-m-d")));
			$start_time = date('Y-m-01', strtotime("$start -1 day"));
			//$start = strtotime($start);
		}
		if(!empty($end)){
			 $end = date('Y-m-01', strtotime(date($end)));
			 $end_time =  date('Y-m-d', strtotime("$end +1 month -1 day"));

		}else{
			$start;
			$end_time =  date('Y-m-d', strtotime("$start_time +1 month -1 day"));
		}
		


		$data = $temp = array();
		
		$temp['where']['where'] = array('uid' => $v['uid']);
	    $user = $this->c->get_row(self::user, $temp['where']);
		//var_dump( $user);
	
		//查询当月日期时间段落		
		// $start = date('Y-m-01', strtotime(date("Y-m-d")));
		// $start = date('Y-m-01', strtotime("$start -1 day"));
		// $end =  date('Y-m-d', strtotime("$start +1 month -1 day"));
		
		
		// $data['start'] = $start;
		// $data['end'] = $end;
		$start = strtotime($start_time);
		$end = strtotime($end_time);
		$end = $end + 24*60*60 - 1;		
		//日期处理		

		//获取到还款中和已还款的借款信息
		$temp['where'] ="";
		$temp['where'] = array(
							'select' => 'borrow_no,subject,deadline,months',
							'where_in' => array('field' => 'status', 'value' => "4")
    	);				
		$borrow_no = $this->c->get_all(self::borrow, $temp['where']);

		//将获取的单号进行合并

		foreach($borrow_no as $k => $v)
		{
			$v['deadline'] = $v['deadline'] - 24*60*60;
			//有效时常计算
			if($v['deadline'] >= $start && $v['deadline']<= $end){
				if($v['deadline'] >= $start){
					$date = date('Ymd', $end)-date('Ymd', $v['deadline']);
					if($date>$v['months']*30){
						 $date = $v['months']*30;
					}
				}elseif($v['deadline'] <= $end){
					$date = date('Ymd', $v['deadline']) - date('Ymd', $start);
				}
				$day[$v['borrow_no']]= $date;
				$r[]=$v['borrow_no'];
			}
		}

		foreach($r as $k => $v)
		{

			if($k==0){
				$borrow = "'".$v."'";
			}else{
				$borrow = $borrow.",'".$v."'";
			}		
		}


			$temp['where'] ="";
            $temp['where']['where'] = array('inviter' => $user['uid'],'clientkind' => 1);
	        $all = $this->c->get_all(self::user, $temp['where']);
	        $data = $this->c->show_page(self::user, $temp['where']);
			
			foreach($data['data'] as $k => $v)
			{
				$temp['where'] ="";
				$temp['where']['where'] = array('inviter' => $user['uid']);
				$inviter = $this->c->get_all(self::user, $temp['where']);	
				$count = $this->c->count(self::user, $temp['where']);
				$data['data'][$k]['count']= $count;			
					if(!empty($borrow)){
						$query = $this->db->query("SELECT * FROM `p2p`.`cdb_borrow_payment` where status = 1  and  type = 1 and  uid = ".$v['uid']." and borrow_no in (".$borrow.") ORDER BY `id` DESC  LIMIT 0,50;");
						$youxiao =  $query->result();			
						$object =  json_decode(json_encode($youxiao),true);

						$jujianren = 0;
						$data['data'][$k]['jujianren'] += $jujianren;

						foreach($object as $o => $p){
								$jujianren = 0;
							//读取用户LV等级配置
							$rate = $this->config->item('lv'.$user['lv']);
							//居间人计算公式
							$jujianren = $p['amount']*$rate/360*$day[$p['borrow_no']];
							$data['data'][$k]['jujianren'] += $jujianren;

							$temp['where'] = array(
                                'select' => 'status',
                                'where'  => array('borrow_no' => $p['borrow_no'],'start_time' => $start,'end_time' => $end)
                            );

							$temp['data'] = $this->c->get_one(self::jujian_jiesuan, $temp['where']);
							
							$data['data'][$k]['status'] = $temp['data'];

							//预留入库操作						
						}
						//结算状态查询

						
						
					}
			}
			
			
			$sumjiesuan = 0;
			$sum = 0;
			
			foreach($all as $k => $v){
				if(!empty($borrow)){
					$query = $this->db->query("SELECT * FROM `p2p`.`cdb_borrow_payment` where status = 1  and  type = 1 and  uid = ".$v['uid']." and borrow_no in (".$borrow.") ORDER BY `id` DESC  LIMIT 0,50;");
					$youxiao =  $query->result();			
					$object =  json_decode(json_encode($youxiao),true);		
					foreach($object as $o => $p){
								//读取用户LV等级配置
								$rate = $this->config->item('lv'.$user['lv']);								
								$temp['where'] = array(
									'select' => 'status,amount',
									'where'  => array('payment_no' => $p['payment_no'],'borrow_no' => $p['borrow_no'],'start_time >=' => $start,'end_time <=' => $end)
								);
								$temp['data'] = $this->c->get_row(self::jujian_jiesuan, $temp['where']);
								$jujianren = 0;
								if($temp['data']['status']==1){
									$sumjiesuan += $temp['data']['amount'];
								}else{
									$jujianren = $p['amount']*$rate/360*$day[$p['borrow_no']]+0;		
									$sum += $jujianren;				
								}
								
					}
				}
			}
			
			//var_dump( $data);
			//var_dump($data);
			//var_dump($user['uid']);
			//var_dump($inviter);
			
			
			
			
			//var_dump($data);
		$data['user'] = $user;
		$data['start'] = $start_time;
		$data['end'] = $end_time;
		$data['sum'] = $sum;
		$data['sumjiesuan'] = $sumjiesuan;
		unset($temp);
		//var_dump($data);
        return $data;
		 
		
	}
    /**
     * 入库操作
     * @return array
     */
    public function ruku_one(){

	
		$v['uid'] = $this->input->get('uid', TRUE);
        $start = $this->input->get('start_time', TRUE);
        $end = $this->input->get('end_time', TRUE);
		
		if(!empty($start)){
			$start_time = date('Y-m-01', strtotime(date($start)));
			//$start = strtotime($start);
		}else{
			$start = date('Y-m-01', strtotime(date("Y-m-d")));
			$start_time = date('Y-m-01', strtotime("$start -1 day"));
			//$start = strtotime($start);
		}
		if(!empty($end)){
			 $end = date('Y-m-01', strtotime(date($end)));
			 $end_time =  date('Y-m-d', strtotime("$end +1 month -1 day"));

		}else{
			echo $end_time =  date('Y-m-d', strtotime("$start +1 month -1 day"));
		}
		$data = $temp = array();
		
		$temp['where']['where'] = array('uid' => $v['uid']);
	    $user = $this->c->get_row(self::user, $temp['where']);		
		var_dump($user);
		
		$start = strtotime($start_time);
		$end = strtotime($end_time);
		$end = $end + 24*60*60 - 1;		
		//日期处理		

		//获取到还款中和已还款的借款信息
		$temp['where'] ="";
		$temp['where'] = array(
							'select' => 'borrow_no,subject,deadline,months',
							'where_in' => array('field' => 'status', 'value' => "4")
    	);				
		$borrow_no = $this->c->get_all(self::borrow, $temp['where']);
		
		
		foreach($borrow_no as $k => $v)
		{
			$v['deadline'] = $v['deadline'] - 24*60*60;
			//有效时常计算
			if($v['deadline'] >= $start && $v['deadline']<= $end){
				if($v['deadline'] >= $start){
					$date = date('Ymd', $end)-date('Ymd', $v['deadline']);
					if($date>$v['months']*30){
						 $date = $v['months']*30;
					}
				}elseif($v['deadline'] <= $end){
					$date = date('Ymd', $v['deadline']) - date('Ymd', $start);
				}
				$day[$v['borrow_no']]= $date;
				$r[]=$v['borrow_no'];
			}
		}
		foreach($r as $k => $v)
		{
			if($k==0){
				$borrow = "'".$v."'";
			}else{
				$borrow = $borrow.",'".$v."'";
			}		
		}
		$temp['where'] ="";
        $temp['where']['where'] = array('inviter' => $user['uid'],'clientkind' => 1);
	    $all = $this->c->get_all(self::user, $temp['where']);
	    $data = $this->c->show_page(self::user, $temp['where']);	
		
		foreach($all as $k => $v){
			if(!empty($borrow)){
				$query = $this->db->query("SELECT * FROM `p2p`.`cdb_borrow_payment` where status = 1  and  type = 1 and  uid = ".$v['uid']." and borrow_no in (".$borrow.") ORDER BY `id` DESC  LIMIT 0,50;");
				$youxiao =  $query->result();			
				$object =  json_decode(json_encode($youxiao),true);		
				foreach($object as $o => $p){
							//读取用户LV等级配置
							$rate = $this->config->item('lv'.$user['lv']);
							
							$jujianren = 0;
							//居间人计算公式
							$jujianren = $p['amount']*$rate/360*$day[$p['borrow_no']]+0;							

							/* $temp['save_data']=array();
							$temp['where']=array('uid'=>$temp['uid']);
							$temp['save_status']=$this->c->update(self::user,$temp['where'],$temp['save_data']); */
							
							$sum += $jujianren;
							
							$temp['where']['where'] = array('payment_no' =>$p['payment_no']);
							$count = $this->c->count(self::user, $temp['where']);
							if($count==0){										
								
								//预留入库操作
								//要入库什么，如果结算时间段，入库结算金额，入库结算时的LV等级，入库结算时的LV 利息，如果结算时的下属人员
								 $temp['data'] = array(
											'payment_no'  =>   $p['payment_no'],
											'uid' => $v['uid'],
											'inviter' => $user['uid'],
											'borrow_no' => $p['borrow_no'],
											'amount' => $jujianren,
											'lv' => $user['lv'],
											'rate' =>  $this->config->item('lv'.$user['lv']),
											'start_time' => $start,
											'end_time' => $end,
											'pay_time' => time(),
											'status' => "1"
								);
								$query = $this->c->insert(self::jujian_jiesuan, $temp['data']);
							
								
							}
							
				}
			}
		}
        redirect('member/invite/get_one?uid='.$user['uid'].'&start_time='.$start_time.'&end_time='.$end_time, 'refresh');
	}
	 
	 
	 
	 

    /**
     * 须认证的 记录数
     * @return array
     */
    public function show_authentication_page(){
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
            'select'   => join_field('uid,user_name,real_name,email,mobile,nric,reg_date,last_date,status,clientkind,nric_image',self::user).','.join_field('status',self::recharge).' as recharge_status,'.join_field('amount',self::recharge),
            'order_by' => join_field('uid',self::user).' desc',
            'where'=>array(join_field('clientkind',self::user)=>-1,join_field('nric',self::user).' !='=>'',join_field('type',self::recharge)=>3),
            'join'=>array('table'=>self::recharge,'where'=>join_field('uid',self::user).' = '.join_field('uid',self::recharge))
        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (preg_match('/^1[345789](\d){9}$/', $temp['keyword'])) ? 'mobile' : 'user_name';
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::user, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 个人 认证 开户 操作
     */
    public function authentication(){
        $query=$temp=array();
        $query['status']=1;
        $query['info']='ok';

        $temp['uid']=(int)$this->input->get('uid');

        if( ! empty($temp['uid'])){
            $temp['where']=array('uid'=>$temp['uid']);

            $temp['data']=$this->c->get_row(self::user,$temp['where']);

            //开户
            $temp['pay_data']=array();//三方需要的数据

            $this->load->library('pay');
            $temp['pay_return']=$this->pay->form_post($temp['pay_data'],41001);

            if($temp['pay_return']['ReturnInfo']['RtnCode'] === '000000'){
                $temp['save_data']=array();
                $temp['where']=array('uid'=>$temp['uid']);
                $temp['save_status']=$this->c->update(self::user,$temp['where'],$temp['save_data']);
                if( ! empty($temp['save_status'])){
                    //发送 信息
                }
            }else{
                $query['status']=0;
                $query['info']=$temp['pay_return']['ReturnInfo']['RtnInfo'];
            }
        }

        return $query;
    }
    /*******************************************************************************/

    /**
     * 更新会员资料
     *
     * @access public
     * @return boolean
     */
    public function update()
    {
        $query = FALSE;
        $temp  = array();
        $temp['uid'] = (int)$this->input->post('uid');
        if( ! empty($temp['uid']))
        {
            $temp['rate']  = (float)$this->input->post('rate');
        
			$temp['range'] = $this->_get_rate_range($temp['uid']);

            $temp['data'] = array(			
                                'user_name' => $this->input->post('user_name', TRUE),
                                'gender'    => (int)$this->input->post('gender'),
                                'type'      => (int)$this->input->post('type'),
                                'group_id'  => (int)$this->input->post('group_id'),
                                'mobile'    => $this->input->post('mobile', TRUE),
                                'real_name' => $this->input->post('real_name', TRUE),
                                'nric'      => $this->input->post('nric', TRUE),
                                'phone'     => $this->input->post('phone', TRUE),
                                'email'     => $this->input->post('email', TRUE),
                                'rate'      => $temp['rate']
			);
			// 判断邀请人的生成
			$where['where'] = array('where' => array('uid' => $temp['uid']));
			$user = $this->c->get_row(self::user, $temp['where']);
				
			$temp['group_id'] = (int)$this->input->post('group_id');
			if($temp['group_id']==4){
				if(empty($user['inviter_no'])){
					$temp['data']['inviter_no'] = $this->c->transaction_no(self::user, 'inviter');
				}
			}
			$temp['type'] = (int)$this->input->post('type');
			if($temp['type']==1){				
				if(empty($user['bfirmid'])){
					//$configData = $this->pay->create_borrower($user['real_name'],$user['nric']);
					if($configData['ReturnInfo']['RtnCode']="000000"){
						$temp['data']['bfirmid'] = $configData['FundAcc']['FirmId'];
						$temp['data']['bvaccid'] = $configData['FundAcc']['VaccId'];
					}
				}
			}
            if($temp['rate'] < $temp['range']['min'] || $temp['rate'] > $temp['range']['max'])
            {
                unset($temp['data']['rate']);
            }
            $temp['where'] = array('where' => array('uid' => $temp['uid']));
            $query = $this->c->update(self::user, $temp['where'], $temp['data']);
        }
		unset($temp);
        return $query;
    }
    /**
     * 获取会员详情
     *
     * @access public
     * @return array
     */

    public function get_group_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'group_id,group_name,parent_id',
                            'where'    => array('status' => 1),
                            'order_by' => 'sort_order desc'
                        );

        $temp['data'] = $this->c->get_all(self::group, $temp['where']);

        if( ! empty($temp['data']))
        {
            $data = $this->_get_group_level($temp['data']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取会员详情
     *
     * @access public
     * @param  boolean $flag 完整信息
     * @return array
     */

    public function get_member_info($flag = TRUE)
    {
    	$data = $temp = array();

		$temp['uid']   = (int)$this->input->get('uid');

		if( ! empty($temp['uid']))
		{
			$temp['where'] = array('where'  => array('uid' => $temp['uid']));

	    	$data = $this->c->get_row(self::user, $temp['where']);

	    	if( ! empty($data) && ! empty($flag))
	    	{
                $data['balance']     = $this->_get_user_balance($temp['uid']);
                $data['address']     = $this->_get_address_list($temp['uid']);
                $data['borrow']      = $this->_get_borrow_list($temp['uid']);
                $data['rechage']     = $this->_get_recharge_list($temp['uid']);
                $data['invest']      = $this->_get_payment_list($temp['uid']);
                $data['refund']      = $this->_get_payment_list($temp['uid'], 10, 2);
                $data['transaction'] = $this->_get_transaction_list($temp['uid']);
	    	}
		}
		unset($temp);
		return $data;
    }

    /**
     * 获取提成范围
     *
     * @access private
     * @param  integer $uid 会员ID
     * @return array
     */

    private function _get_rate_range($uid = 0)
    {
        $data = $temp = array();
        $data = array('max' => 2.5, 'min' => 0.5);

        if( ! empty($uid))
        {
            $temp['sql']  = 'SELECT a.`rate` FROM '.$this->db->dbprefix('user').' AS a
                                       INNER JOIN '.$this->db->dbprefix('user').' AS b ON a.`uid` = b.`inviter`
                                       WHERE b.`uid` = ?';

            $temp['data'] = $this->db->query($temp['sql'], array($uid))->row_array();

            if(isset($temp['data']['rate']) && $temp['data']['rate'] > 0)
            {
                $data['max'] = ($temp['data']['rate'] > $data['max']) ? $data['max'] : $temp['data']['rate'];
            }

            $temp['where'] = array(
                                'select' => 'MAX(`rate`)',
                                'where'  => array('inviter' => $uid)
                            );

            $temp['data'] = $this->c->get_one(self::user, $temp['where']);

            if( ! empty($temp['data']))
            {
                $data['min'] = ($temp['data'] < $data['min']) ? $data['min'] : $temp['data'];
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地址信息
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  integer $number 记录数量
     * @return array
     */

    private function _get_address_list($uid = 0, $number = 10)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['number'] = ($number > 0) ? (int)$number : 10;

            $temp['where'] = array(
                                'select'   => 'type,province,city,district,address',
                                'where'    => array('uid' => (int)$uid),
                                'order_by' => 'id desc',
                                'limit'    => $temp['number']
                            );

            $data = $this->c->get_all(self::address, $temp['where']);

            if( ! empty($data))
            {
                $temp['region_id'] = array();

                foreach($data as $k => $v)
                {
                    $temp['region_id'][] = $v['province'];
                    $temp['region_id'][] = $v['city'];
                    $temp['region_id'][] = $v['district'];
                }

                $temp['region'] = $this->_get_region_list($temp['region_id']);

                if( ! empty($temp['region']))
                {
                    foreach($data as $k => $v)
                    {
                        $data[$k]['province'] = (isset($temp['region'][$v['province']])) ? $temp['region'][$v['province']] : '';
                        $data[$k]['city'] = (isset($temp['region'][$v['city']])) ? $temp['region'][$v['city']] : '';
                        $data[$k]['district'] = (isset($temp['region'][$v['district']])) ? $temp['region'][$v['district']] : '';
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取借款记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  integer $number 记录数量
     * @return array
     */

    private function _get_borrow_list($uid = 0, $number = 10)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['number'] = ($number > 0) ? (int)$number : 10;

            $temp['where'] = array(
                                'select'   => 'borrow_no,subject,type,months,amount,rate,receive,add_time,status',
                                'where'    => array('uid' => (int)$uid),
                                'order_by' => 'id desc',
                                'limit'    => $temp['number']
                            );

            $data = $this->c->get_all(self::borrow, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取分组层级
     *
     * @access public
     * @param  array  $group      分组数据
     * @param  intege $parent_id 父级节点
     * @param  intege $deep      缩进层级
     * @return array
     */

    private function _get_group_level($group = array(), $parent_id = 0, $deep = 0)
    {
        static $data = array();

        $data = ( ! empty($parent_id)) ? $data : array();

        if( ! empty($group))
        {
            foreach($group as $k => $v)
            {
                if($v['parent_id'] == $parent_id)
                {
                    $data[] = array(
                                    'group_id'   => $v['group_id'],
                                    'group_name' => $v['group_name'],
                                    'parent_id'  => $v['parent_id'],
                                    'deep'       => $deep
                                );

                    $this->_get_group_level($group, $v['group_id'], $deep + 1);
                }
            }
        }

        return $data;
    }

    /**
     * 获取会员信息
     *
     * @access private
     * @param  integer  $uid  用户ID
     * @param  boolean  $flag 后台用户
     * @return array
     */

    private function _get_user_id($uid = 0, $flag = FALSE)
    {
    	$data = $temp = array();

    	if( ! empty($uid))
    	{
            if( ! empty($flag))
            {
                $temp['where'] = array(
                                    'select' => 'mobile,admin_name',
                                    'where'  => array('admin_id' => $uid)
                                );

                $data = $this->c->get_row(self::admin, $temp['where']);
            }
            else
            {
                $temp['where'] = array(
                                    'select' => 'mobile,user_name,real_name',
                                    'where'  => array('uid' => $uid)
                                );

                $data = $this->c->get_row(self::user, $temp['where']);
            }
    	}

        unset($temp);
    	return $data;
    }

    /**
     * 获取扩展信息
     *
     * @access private
     * @param  integer $uid    会员ID
     * @return array
     */

    private function _get_member_extend($uid = 0)
    {
    	$data = $temp = array();

    	if( ! empty($uid))
    	{
    		$temp['where'] = array(
								'select'   => 'type,key,value',
								'where'    => array('uid' => (int)$uid)
    						);

    		$temp['data'] = $this->c->get_all(self::info, $temp['where']);

    		if( ! empty($temp['data']))
    		{
    			foreach($temp['data'] as $v)
    			{
    				$data[$v['type']][$v['key']] = ($v['key'] == 'nric') ? explode('|', $v['value']) : $v['value'];
    			}
    		}
    	}

    	unset($temp);
    	return $data;
    }

    /**
     * 获取用户ID
     *
     * @access private
     * @param  string  $mobile  会员ID
     * @param  boolean $flag    用户ID
     * @return integer
     */

    private function _get_mobile_info($mobile = '', $flag = FALSE)
    {
        $id   = 0;
        $temp = array();

        if( ! empty($mobile))
        {
            $temp['field'] = ( ! empty($flag)) ? 'admin_id' : 'uid';
            $temp['table'] = ( ! empty($flag)) ? self::admin : self::user;

            $temp['where'] = array('select' => $temp['field'], 'where' => array('mobile' => $mobile));

            $id = $this->c->get_one($temp['table'], $temp['where']);
        }

        unset($temp);
        return $id;
    }

    /**
     * 获取地址信息
     *
     * @access private
     * @param  array    $region_id  地区ID
     * @return array
     */

    private function _get_region_list($region_id = array())
    {
    	$data = $temp = array();

    	if( ! empty($region_id))
    	{
    		$region_id = array_unique($region_id);

    		$temp['where'] = array(
								'select'   => 'region_id,region_name',
								'where_in' => array('field' => 'region_id', 'value' => $region_id)
    						);

    		$temp['data'] = $this->c->get_all(self::region, $temp['where']);

    		if( ! empty($temp['data']))
    		{
    			foreach($temp['data'] as $v)
    			{
    				$data[$v['region_id']] = $v['region_name'];
    			}
    		}
    	}

    	unset($temp);
    	return $data;
    }

    /**
     * 获取上级ID
     *
     * @access private
     * @param  integer  $uid  用户ID
     * @return array
     */

    private function _get_parent_id($uid = 0)
    {
        $parent_id = 0;
        $temp      = array();

        if( ! empty($uid))
        {
            $temp['where'] = array('select' => 'parent_id','where'  => array('uid' => $uid));
            $parent_id = $this->c->get_one(self::user, $temp['where']);
        }

        unset($temp);
        return $parent_id;
    }

    /**
     * 获取充值记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  integer $number 记录数量
     * @return array
     */

    private function _get_recharge_list($uid = 0, $number = 10)
    {
    	$data = $temp = array();

    	if( ! empty($uid))
    	{
    		$temp['number'] = ($number > 0) ? (int)$number : 10;

    		$temp['where'] = array(
								'select'   => 'recharge_no,type,amount,source,add_time,status',
								'where'    => array('uid' => (int)$uid),
								'order_by' => 'id desc',
								'limit'    => $temp['number']
    						);

    		$data = $this->c->get_all(self::recharge, $temp['where']);
    	}

    	unset($temp);
    	return $data;
    }

    /**
     * 获取投资还款记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  integer $number 记录数量
     * @param  boolean $type   记录类型
     * @return array
     */

    private function _get_payment_list($uid = 0, $number = 10, $type = 1)
    {
    	$data = $temp = array();

    	if( ! empty($uid))
    	{
    		$temp['number'] = ($number > 0) ? (int)$number : 10;

    		$temp['where'] = array(
                                'select'   => join_field('payment_no,type,borrow_no,rate,amount,balance,charge,dateline', self::payment).','.join_field('subject,status', self::borrow),
                                'where'    => array(join_field('uid', self::payment) => (int)$uid, join_field('type', self::payment) => (int)$type),
                                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                                'order_by' => join_field('id', self::payment).' desc',
                                'limit'    => $temp['number']
    						);

    		$temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $data[] = array(
                                'payment_no' => $v['payment_no'],
                                'type'       => $v['type'],
                                'subject'    => $v['subject'],
                                'borrow_no'  => $v['borrow_no'],
                                'rate'       => $v['rate'],
                                'amount'     => $v['amount'],
                                'balance'    => $v['balance'],
                                'charge'     => $v['charge'],
                                'interest'   => round($v['amount'] * $v['rate'] /36500 , 2),
                                'dateline'   => $v['dateline'],
                                'status'     => $v['status']
                            );
                }
            }
    	}

    	unset($temp);
    	return $data;
    }

    /**
     * 获取提现记录
     *
     * @access private
     * @param  integer $uid    会员ID
     * @param  integer $number 记录数量
     * @return array
     */

    private function _get_transaction_list($uid = 0, $number = 10)
    {
    	$data = $temp = array();

    	if( ! empty($uid))
    	{
    		$temp['number'] = ($number > 0) ? (int)$number : 10;

    		$temp['where'] = array(
								'select'   => 'transaction_no,amount,charge,real_name,bank_name,account,add_time,status',
								'where'    => array('uid' => (int)$uid),
								'order_by' => 'id desc',
								'limit'    => $temp['number']
    						);

    		$data = $this->c->get_all(self::transaction, $temp['where']);
    	}

    	unset($temp);
    	return $data;
    }

    /**
     * 获取会员余额
     *
     * @access private
     * @param  intege $uid 会员ID
     * @return float
     */

    private function _get_user_balance($uid = 0)
    {
        $balance = 0;
        $temp    = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => (int)$uid),
                                'order_by' => 'id desc'
                            );

            $balance = (float)$this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }
}