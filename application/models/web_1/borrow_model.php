<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 借款管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Borrow_model extends CI_Model
{
    const borrow       = 'borrow'; // 借款记录
    const user         = 'user'; // 用户
    const address      = 'user_address'; // 用户地址
    const info         = 'user_info'; // 扩展信息
    const company      = 'company'; // 担保公司
    const flow         = 'cash_flow';  // 现金流
    const announcement = 'announcement'; // 公告
    const collateral   = 'borrow_collateral'; // 抵押物信息
    const payment      = 'borrow_payment'; // 支付记录
    const region       = 'region'; // 地区
    const apply        = 'borrow_apply'; // 借款申请
    const article      = 'article'; // 文章列表
    const attachment   = 'borrow_attachment'; // 借款附件
    const message      = 'message'; // 系统消息
    const recharge     = 'user_recharge'; // 会员充值

    const repay      = 'borrow_repay_plan'; // 还款计划 2015.06.09-wsb

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('web_1/user/authentication_model', 'authentication');
        $this->load->library('form_validation');
        $this->load->library('pay');
        $this->lang->load('form');
    }

    /**
     * 借款申请
     *
     * @access public
     * @return boolean
     */

    public function apply()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if((int)$this->input->post('type') == 2 && ! $this->_check_enterprise()){ //wsb-2015.5.13 添加企业认证检查
//            $data['url']=site_url('user/authentication/enterprise');
            $data['msg']='公司借款请先通过企业认证审核';
            return $data;
        }

        if($this->form_validation->run() == TRUE)
        {
            $temp['data'] = array(
                                'apply_no'    => $this->c->transaction_no(self::apply, 'apply_no'),
                                'user_name'   => $this->input->post('user_name', TRUE),
                                'mobile'      => $this->input->post('mobile', TRUE),
                                'type'        => (int)$this->input->post('type'),
                                'amount'      => $this->input->post('amount', TRUE),
                                'dateline'    => $this->input->post('dateline', TRUE),
                                'province'    => (int)$this->input->post('province'),
                                'city'        => (int)$this->input->post('city'),
                                'district'    => (int)$this->input->post('district'),
                                'from'        => $this->input->post('from', TRUE),
                                'add_time'    => time(),
                                'update_time' => time(),
                                'p_type'        => (int)$this->input->post('p_type'),//wsb-2015.5.13 新增
                            );

            $temp['data']['dateline'] = ( ! empty($temp['data']['dateline'])) ? strtotime($temp['data']['dateline']) : time();
            $query = $this->c->insert(self::apply, $temp['data']);

            if( ! empty($query))
            {
                $data = array(
                            'code' => 0,
                            'msg'  => '你的借款申请已经提交成功,请等待审核!',
                            'url'  => site_url()
                        );
            }
        }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取交易金额
     *
     * @access public
     * @param  boolean $flag 金额类型
     * @return boolean
     */

    public function get_borrow_amount($flag = FALSE,$category=0)
    {
        $amount = 0;
        $temp   = array();

        $temp['field'] = ( ! empty($flag)) ? 'amount' : 'receive';

        $temp['where'] = array(
                            'select' => 'SUM(`'.$temp['field'].'`)',
                            'where'  => array('status >' => 1)
                        );

        if( ! empty($category)){
            $temp['where']['where']['productcategory']=$category; //不同产品类别下的金额统计 201.5.20
        }
        $amount = $this->c->get_one(self::borrow, $temp['where']);

        unset($temp);
        return $amount;
    }

    /**
     * 获取利息总额
     *
     * @access public
     * @return float
     */

    public function get_borrow_interest($category=0)
    {
        $amount = 0;
        $temp   = array();

        $temp['where'] = array('select' => 'amount,months,rate');
        if( ! empty($category))$temp['where']['where']['productcategory']=$category;//2015.5.20新加
        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data']))
        {
            foreach($temp['data'] as $k => $v)
            {
                $amount += round($v['amount'] * $v['rate'] / 1200 * $v['months'], 2);
            }
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取用户排行
     *
     * @access public
     * @param  integer  $number 记录数量
     * @return integer
     */

    public function get_user_top10($number = 10)
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => join_field('user_name', self::user).','.join_field('amount,confirm_time', self::recharge),
                            'join'     => array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::recharge).' = '.join_field('uid', self::user)
                                            ),
                            'where'    => array(join_field('status', self::recharge) => 1),
                            'order_by' => join_field('id', self::recharge),
                            'limit'    => $number
                        );

        $data = $this->c->get_all(self::recharge, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取支付余额
     *
     * @access public
     * @return float
     */

    public function get_borrow_balance()
    {
        $balance = 0;
        $temp    = array();

        $temp['borrow_no'] = $this->input->get('borrow_no');

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'payment - refund',
                                'where'  => array('borrow_no' => $temp['borrow_no'])
                            );

            $balance = (float)$this->c->get_one(self::borrow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取还款记录
     *
     * @access public
     * @return array
     */

    public function get_repayment_list()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'amount,rate,months,confirm_time',
                                'where'  => array('borrow_no' => $temp['borrow_no'])
                            );

            $temp['data'] = $this->c->get_row(self::borrow, $temp['where']);

            if( ! empty($temp['data']))
            {
                $temp['payment'] = $this->_get_payment_list($temp['borrow_no']);

                $temp['interest'] = round($temp['data']['amount'] * $temp['data']['rate'] / 1200, 2);
                $temp['surplus']  = round($temp['data']['amount'] + $temp['interest'] * $temp['data']['months'], 2);
                $temp['days']     = repayment_date($temp['data']['confirm_time'], $temp['data']['months']);

                for($i = 1; $i <= $temp['data']['months']; $i++)
                {
                    $temp['surplus'] -= $temp['interest'];
                    $temp['dateline'] = strtotime('+ '.$i.' months', $temp['data']['confirm_time']);

                    $temp['date']     = date('Y-m-d', $temp['dateline']);
                    $temp['amount']   = (isset($temp['payment'][$temp['date']])) ? $temp['payment'][$temp['date']] : 0;
                    $temp['need_pay'] = ($i == $temp['data']['months']) ? $temp['surplus'] +  $temp['interest']: $temp['interest'];

                    $data[] = array(
                                'number'   => $i,
                                'payment'  => $temp['need_pay'],
                                'surplus'  => ($i == $temp['data']['months']) ? 0 : $temp['surplus'],
                                'dateline' => (isset($temp['days'][$i])) ? strtotime($temp['days'][$i]) : '',
                                'status'   => ($temp['amount'] >= $temp['need_pay']) ? 1 : 0
                            );
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取支付列表
     *
     * @access private
     * @param  string  $borrow_no 借款编号
     * @return array
     */

    private function _get_payment_list($borrow_no = '')
    {
        $data = $temp = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select' => 'amount,dateline,status',
                                'where'  => array('borrow_no' => $borrow_no, 'type' => 2)
                            );

            $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $v['dateline'] = date('Y-m-d', $v['dateline']);

                    if(isset($data[$v['dateline']]))
                    {
                        $data[$v['dateline']] += ( ! empty($v['status'])) ? $v['amount'] : 0;
                    }
                    else
                    {
                        $data[$v['dateline']] = ( ! empty($v['status'])) ? $v['amount'] : 0;
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取逾期金额
     *
     * @access public
     * @return float
     */

    public function get_overdue_amount()
    {
        $balance = 0;
        $temp    = array();

        $temp['borrow_no'] = $this->input->get('borrow_no');

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'SUM(amount)',
                                'where'  => array(
                                                'borrow_no' => $temp['borrow_no'],
                                                'type'      => 2,
                                                'status'    => 0
                                            )
                            );

            $balance = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取交易详情
     *
     * @access public
     * @return array
     */

    public function get_borrow_info()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => join_field('*', self::borrow).','.join_field('user_name,nric,dateline,mobile,real_name', self::user),
                                'join'   => array(
                                                'table' => self::user,
                                                'where' => join_field('uid', self::borrow).' = '.join_field('uid', self::user)
                                            ),
                                'where'  => array(join_field('borrow_no', self::borrow) => $temp['borrow_no'])
                            );

            $data = $this->c->get_row(self::borrow, $temp['where']);

            if( ! empty($data))
            {
                $data['interest']     = round($data['amount'] * $data['rate'] / 1200,2);
				if(! empty($data['receive']))
					{
						if($data['receive'] / $data['amount'] * 100 >0 && $data['receive'] / $data['amount'] * 100<1)
						{
							$data['receive_rate'] =1;
						}else if(($data['receive'] / $data['amount'] * 100)>99 && ($data['receive'] / $data['amount'] * 100)<100)
						{
							$data['receive_rate']=99;
						}
						else
						{
							$data['receive_rate']=round($data['receive'] / $data['amount'] * 100);
						}
					
					}
					else
					{
						$data['receive_rate']=0;						
					}
				

                if($data['type'] == 1)
                {
                    $temp['age'] = ( ! empty($data['nric'])) ? substr($data['nric'], 6, 4) : 0;
                    $data['age'] = ( ! empty($temp['age'])) ? date('Y')- $temp['age'] : 0;

                    $temp['extend'] = $temp['extend'] = array();

                    $temp['extend']  = $this->authentication->get_user_info(2, $data['uid']);
                    $temp['company'] = $this->authentication->get_user_info(3, $data['uid']);

                    if( ! empty($temp['company']))
                    {
                        $temp['company']['province'] = $this->_get_region_name($temp['company']['province']);
                    }

                    $data['extend'] = array_merge($temp['extend'], $temp['company']);
                }

                if($data['type'] == 2)
                {
                    $data['collateral'] = $this->_get_collateral_list();
                }

                $data['attachment'] = $this->_get_borrow_attachment();

                $data['credit'] = $this->_get_credit_info($data['uid']);
                $data['log']    = $this->_get_borrow_log($data['borrow_no']);
				$data['agreement'] = json_decode($data['agreement'], TRUE);
				$data['claims']    = json_decode($data['claims'], TRUE);
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取交易附件
     *
     * @access public
     * @return array
     */

    private function _get_borrow_attachment()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'type,link_url,description',
                                'where'  => array('borrow_no' => $temp['borrow_no'])
                            );

            $temp['rs'] = $this->c->get_all(self::attachment, $temp['where']);

			if( ! empty($temp['rs']))
			{
				foreach($temp['rs'] as $val)
				{
					$data[$val['type']][] = $val;
				}
			}
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取平均利率
     *
     * @access public
     * @return boolean
     */

    public function get_borrow_rate()
    {
        $rate = 0;
        $temp = array();

        $temp['where'] = array(
                            'select' => 'SUM(`rate`)/COUNT(*)',
                            'where'  => array('status >' => 1)
                        );

        $rate = (int)$this->c->get_One(self::borrow, $temp['where']);

        unset($temp);
        return $rate;
    }

    /**
     * 获取借款记录
     *
     * @access public
     * @param  integer $number  记录数量
     * @param  string  $type    借款类型
     * @return array
     */

    public function get_borrow_list($number = 6, $type = 0, $category_id=0, $status=0)
    {
        $data = $temp = array();

        if( ! empty($number))
        {
            $temp['where'] = array(
                                'select'   => 'borrow_no,subject,type,uid,months,amount,mode,rate,receive,lowest,due_date,last_investor,last_amount,last_time,add_time,buy_time,status',
                                'where'    => array('show_time <=' => time(),'status > ' => 1),
                                'order_by' => 'sort_order desc,id desc',
	                            'limit'    => $number
                            );

            if( ! empty($type))
            {
                $temp['where']['where']['type'] = $type;
            }
//2015.5.19
            if( ! empty($category_id))
            {
                $temp['where']['where']['productcategory'] = $category_id;
            }
 

            $data = $this->c->show_page(self::borrow, $temp['where']);

            if( ! empty($data))
            {
                $temp['cid'] = $temp['uid'] = array();

                foreach($data['data'] as $k => $v)
                {
                   // $data['data'][$k]['receive_rate'] = ( ! empty($v['receive'])) ? floor($v['receive'] / $v['amount'] * 100) : 0;
					if(! empty($v['receive']))
					{
						if($v['receive'] / $v['amount'] * 100 >0 && $v['receive'] / $v['amount'] * 100<1)
						{
							$data['data'][$k]['receive_rate'] =1;
						}else if(($v['receive'] / $v['amount'] * 100)>99 && ($v['receive'] / $v['amount'] * 100)<100)
						{
							$data['data'][$k]['receive_rate']=99;
						}
						else
						{
							$data['data'][$k]['receive_rate']=round($v['receive'] / $v['amount'] * 100);
						}
					
					}
					else
					{
						$data['data'][$k]['receive_rate']=0;						
					}
					
					
                    if( ! empty($v['last_investor']))
                    {
                        $temp['uid'][] = $v['last_investor'];
                    }
                }

                $temp['investor'] = $this->_get_investor_info($temp['uid']);

                foreach($data['data'] as $k => $v)
                {
                    $data['data'][$k]['investor'] = (isset($temp['investor'][$v['last_investor']])) ? $temp['investor'][$v['last_investor']] : array();
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取借款记录
     *
     * @access public
     * @param  integer $number  记录数量
     * @param  string  $type    借款类型
     * @return array
     */

    public function get_borrow_list_by_category()
    {
        $data = $temp = array();

        $temp['where'] = array('order_by' => 'cat_id desc');

        $temp['productcategory']=$this->c->get_all('product_category', $temp['where']);
			
        if( ! empty($temp['productcategory'])){
            foreach($temp['productcategory'] as $k=> $v){

                $temp['list']=$this->get_borrow_list(2,0,$v['cat_id'],"2,3,4");
                if( ! empty($temp['list']['data'])){
                    $temp['productcategory'][$k]['data']=array();
                    foreach($temp['list']['data'] as $k1=>$v1){
                        if( ! ($v1['status'] == 5 || ($v1['status'] == 2 && $v1['due_date'] < time() && $v1['amount'] >= $v1['receive']) )){
                            $temp['productcategory'][$k]['data'][]=$v1;
                        }
                    }
                }
            }
            $data['data']=$temp['productcategory'];
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取搜索记录
     *
     * @access public
     * @return array
     */

    public function get_search_list()
    {
        $data = $temp = array();

        $temp['type']  = (int)$this->input->get('t');
        $temp['category']  = isset($_GET['category'])?(int)$this->input->get('category'):1;
        $temp['status']  = isset($_GET['s'])?(int)$this->input->get('s'):0;
        $temp['months']  = isset($_GET['m'])?$this->input->get('m'):0;
        $temp['rate']  = isset($_GET['r'])?$this->input->get('r'):0;


        $temp['sort']  = $this->input->get('sort', TRUE);
        $temp['sort']  = ( ! empty($temp['sort']) && in_array(strtolower($temp['sort']), array('months', 'rate', 'amount'))) ? strtolower($temp['sort']) : 'status';

        $temp['order'] = $this->input->get('order', TRUE);
        $temp['order'] = ( ! empty($temp['order']) && in_array(strtolower($temp['order']), array('asc', 'desc'))) ? strtolower($temp['order']) : 'asc';

        $temp['where'] = array(
                            'select'   => 'borrow_no,subject,type,uid,amount,months,mode,rate,receive,lowest,due_date,last_investor,last_amount,last_time,add_time,buy_time,status',
                            'where'    => array('show_time <=' => time(),'status > ' => 1,'productcategory'=>$temp['category']),
                            'order_by' => 'id desc,'.$temp['sort'].' '.$temp['order'],
                        );

        if( ! empty($temp['type']))
        {
			$string="/^[0-9]/";	
				if(preg_match($string,$temp['type'])){
					    $temp['where']['where']['type'] = $temp['type'];
				}
        }

        if( ! empty($temp['status']))
        {
				$string="/^[0-9]/";
				if(preg_match($string,$temp['status'])){
					    $temp['where']['where']['status'] = $temp['status'];
						unset($temp['where']['where']['status > ']);
				}
        }

        if( ! empty($temp['months']))
        {
			$string="/\-/";
			if(preg_match($string,$temp['months']))
			{
				$temp['months_arr']=explode('-',$temp['months']);
				$string="/^[0-9]/";	
				//if(preg_match($string,$temp['months_arr'][0])||preg_match($string,$temp['months_arr'][1])){				
				//}else{
					$temp['where']['where']['months >=']=$temp['months_arr'][0];
					$temp['where']['where']['months <=']=$temp['months_arr'][1];	
				//}
			}
        }
		
		if( ! empty($temp['rate']))
        {
			$string="/\-/";
			if(preg_match($string,$temp['rate']))
			{
				$temp['rate_arr']=explode('-',$temp['rate']);
				$string="/^[0-9]/";	
				//if(preg_match($string,$temp['rate_arr'][0])||preg_match($string,$temp['rate_arr'][1])){				
				//}else{
					$temp['where']['where']['rate >=']=$temp['rate_arr'][0];
					$temp['where']['where']['rate <=']=$temp['rate_arr'][1];	
				//}
			}
        }

        $data = $this->c->show_page(self::borrow, $temp['where']);

        if( ! empty($data))
        {
            $temp['uid'] = array();

            foreach($data['data'] as $k => $v)
            {
                if($v['status'] == 5 || ($v['status'] == 2 && $v['due_date'] < time() && $v['amount'] != $v['receive'])){  //过滤 过期 但未满标 流标 的数据   2015-05-20
                    unset($data['data'][$k]);
                }else{
					if(! empty($v['receive']))
					{
						if($v['receive'] / $v['amount'] * 100>0 && $v['receive'] / $v['amount'] * 100<1)
						{
							$data['data'][$k]['receive_rate'] =1;
						}else if(($v['receive'] / $v['amount'] * 100)>99 && ($v['receive'] / $v['amount'] * 100)<100)
						{
							$data['data'][$k]['receive_rate']=99;
						}
						else
						{
							$data['data'][$k]['receive_rate']=round($v['receive'] / $v['amount'] * 100);
						}
					
					}
					else
					{
						$data['data'][$k]['receive_rate']=0;						
					}

                    if( ! empty($v['last_investor']))
                    {
                        $temp['uid'][] = $v['last_investor'];
                    }
                }
            }

            $temp['investor'] = $this->_get_investor_info($temp['uid']);

            foreach($data['data'] as $k => $v)
            {
                $data['data'][$k]['investor'] = (isset($temp['investor'][$v['last_investor']])) ? $temp['investor'][$v['last_investor']] : array();
            }

            $data['type']  = $temp['type'];
            $data['category']  = $temp['category'];
            $data['sort']  = $temp['sort'];
            $data['order'] = ($temp['order'] == 'asc') ? 'desc' : 'asc';
            $data['status']  = $temp['status'];
            $data['months']  = $temp['months'];
            $data['rate']  = $temp['rate'];
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取媒体报道
     *
     * @access public
     * @param  integer  $number 记录数量
     * @return array
     */

    public function get_news_list($number = 10)
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select'   => 'id,title,link_url',
                            'where'    => array('cat_id' => 1, 'status' => 1),
                            'order_by' => 'id desc',
                            'limit'    => $number
                        );

        $data = $this->c->get_all(self::article, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取信用余额
     *
     * @access public
     * @return float
     */

    public function get_credit_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select'   => 'SUM(`amount`) AS `amount`,type',
                                'where'    => array('uid' => $temp['uid']),
                                'group_by' => 'type'
                            );

            $temp['data'] = $this->c->get_all(self::credit, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    if($v['type'] == 1)
                    {
                        $amount += $v['amount'];
                    }
                    else
                    {
                        $amount -= $v['amount'];
                    }
                }
            }
        }

        unset($temp);
        return $amount;
    }

    /**
     * 当前账户可用余额
     *
     * @access public
     * @return float
     */

    public function get_user_balance()
    {
        $balance = 0;
        $temp    = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select'   => 'balance',
                                'where'    => array('uid' => $temp['uid']),
                                'order_by' => 'id desc'
                            );

            $balance = $this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取会员状态
     *
     * @access public
     * @return integer
     */

    public function get_user_status()
    {
        $status = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'status',
                            'where'  => array('uid' => $temp['uid'])
                        );

        $status = $this->c->get_one(self::user, $temp['where']);

        unset($temp);
        return $status;
    }

    /**
     * 借款信息
     *
     * @access public
     * @param  float  $amount    金额
     * @param  string $borrow_no 借款编号
     * @return float
     */

    public function get_borrow_detail($amount = 0, $borrow_no = '')
    {
        $data = $temp = array();

        if( ! empty($amount) && ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select'   => '`lowest`,`amount` - `receive` AS `surplus`,`uid`,subject',
                                'where'    => array('borrow_no' => $borrow_no)
                            );

            $data = $this->c->get_row(self::borrow, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地区列表
     *
     * @access public
     * @return array
     */

    public function get_region_list()
    {
        $data = $temp = array();

        $temp['region_id'] = (int)$this->input->get('region_id');
        $temp['region_id'] = ( ! empty($temp['region_id'])) ? $temp['region_id'] : 1;

        $temp['where'] = array(
                            'select' =>'region_id,region_name',
                            'where' => array('parent_id' => $temp['region_id'])
                        );

        $data = $this->c->get_all(self::region, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 用户投资
     *
     * @access public
     * @param  float    $amount    投资金额
     * @param  string   $borrow_no 借款编号
     * @param  float    $balance   帐户可用余额
     * @return boolean
     */

    public function invest($amount = 0, $borrow_no = '', $balance = 0,$sources=0)
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($amount) && ! empty($borrow_no) && ! empty($balance))
        {
            $temp['where'] = array(
                                'select' => 'rate',
                                'where'  => array('borrow_no' => $borrow_no)
                            );

            $temp['rate'] = $this->c->get_One(self::borrow, $temp['where']);
            $temp['uid']            = $this->session->userdata('uid');

			$temp['where'] = array(
                                'select' => 'firmid,vaccid,real_name',
                                'where'  => array(
                                                'uid' =>  $temp['uid'],
                                            )
                            );
            $temp['usr'] = $this->c->get_row(self::user, $temp['where']);
            if( ! empty($temp['rate']))
            {
				$MarketSerial= $this->c->transaction_no(self::payment, 'payment_no');
				$FirmId = $temp['usr']['firmid'];//$temp['usr']['firmid'];//$FirmId;// 对公账户
				$CustName = $temp['usr']['real_name']; //$temp['usr']['real_name'];//对公账户姓名
				$VaccId = $temp['usr']['vaccid'];//$temp['usr']['vaccid'];//对公账户姓名
				//$configData = $this->pay->touzidongjie($FirmId, $CustName,$VaccId,$MarketSerial,$borrow_no,$amount);
				//现在未做返回状态判断
				// if( ($configData['ReturnInfo']['RtnInfo']=="成功!")&&($amount == $configData['Transfer']['FreezeMoney']))
				// {
					$this->db->trans_start();					

					// 添加投资记录
					$temp['payment'] = array(
										'payment_no' => $MarketSerial,
										'uid'        => $temp['uid'],
										'type'       => 1,
										'borrow_no'  => $borrow_no,
										'rate'       => $temp['rate'],
										'amount'     => $amount,
										'balance'    => price_format($balance - $amount, 0, FALSE),
										'charge'     => 0,
										'dateline'   => time(),
										'pay_time'   => time(),
										'status'     => 1,
										'automatic_type' =>$sources   //0 pc端 1 是自动投  2 取消自动投 3 app端 4 m版
									);

					$this->c->insert(self::payment, $temp['payment']);

					// 添加资金记录
					$temp['flow'] = array(
										'uid'      => $temp['uid'],
										'type'     => 4,
										'amount'   => $amount,
										'balance'  => price_format($balance - $amount, 0, FALSE),
										'source'   => $temp['payment']['payment_no'],
										'remarks'  => '',
										'dateline' => time()
									);

					$this->c->insert(self::flow, $temp['flow']);

					// 更新收款金额
					$temp['where'] = array('where' => array('borrow_no' => $borrow_no));
					$temp['data']  = array('field' => 'receive', 'value' => '`receive` + '.$amount);

					$this->c->set(self::borrow, $temp['where'], $temp['data']);

					// 更新投资人信息
					$temp['data']  = array(
										'last_investor' => $temp['uid'],
										'last_amount'   => $amount,
										'last_time'     => time()
									);

					$this->c->update(self::borrow, $temp['where'], $temp['data']);

					$this->db->trans_complete();
					$query = $this->db->trans_status();

					if( ! empty($query))
					{
						// 可用余额
						$temp['data'] = array('balance' => round($balance - $amount, 2));
						
						$this->session->set_userdata($temp['data']);

						$this->_set_borrow_status(); // 更新记录状态

						$temp['content'] = sprintf('您好，您投资的%s元资金已经冻结。请等待标地结束。', $amount);
						
						$this->send_message($temp['uid'], '您好，您投资的金额已经冻结！', $temp['content']);

						$this->user->add_user_log('invest', '投资'.price_format($amount).'(项目编号：'.$borrow_no.')');
					}
				// }
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 发送信息
     *
     * @access public
     * @param  integer $uid     会员ID
     * @param  string  $subject 主题
     * @param  string  $content 消息内容
     * @return boolean
     */

    public function send_message($uid = 0, $subject = '', $content = '')
    {
        $query = FALSE;
        $data  = array();

        if( ! empty($uid) && ! empty($subject) && ! empty($content))
        {
            $data = array(
                        'uid'       => $uid,
                        'subject'   => $subject,
                        'content'   => $content,
                        'send_time' => time()
                    );

            $query = $this->c->insert(self::message, $data);
        }

        unset($data);
        return $query;
    }

    /**
     * 添加信用日志
     *
     * @access private
     * @param  float   $amount 借款金额
     * @param  string  $source 来源单号
     * @return boolean
     */

    private function _add_credit_log($amount = 0, $source = '')
    {
        $query = FALSE;
        $data = array();

        if( ! empty($amount) && !empty($source))
        {
            $data = array(
                        'uid'      => $this->session->userdata('uid'),
                        'type'     => 2,
                        'amount'   => $amount,
                        'source'   => $source,
                        'dateline' => time()
                    );

            $query = $this->c->insert(self::credit, $data);
        }

        unset($data);
        return $query;
    }

    /**
     * 计算天数
     *
     * @access private
     * @param  integer  $months 月份
     * @return integer
     */

    private function _days($months = 1)
    {
        $data = array();
        $data = array('days' => 0, 'date' => '');

        if( ! empty($months))
        {
            $data['date'] = strtotime('+ '.$months.'months');
            $data['days'] = ($data['date'] - time()) / 86400;
            $data['days'] = intval($data['days']);
        }

        return $data;
    }

    /**
     * 获取投资者信息
     *
     * @access private
     * @param  array   $uid 会员ID
     * @return array
     */

    private function _get_investor_info($uid = array())
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $uid = array_unique($uid);

            $temp['where'] = array(
                                'select'   => 'uid,user_name,avatar,mobile',
                                'where_in' => array('field' => 'uid', 'value' => $uid)
                            );

            $temp['data'] = $this->c->get_all(self::user, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach ($temp['data'] as $k => $v)
                {
                    $data[$v['uid']] = array(
                                            'user_name' => $v['user_name'],
                                            'avatar'    => $v['avatar'],
                                            'mobile'    => $v['mobile']
                                        );
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户基础信息
     *
     * @access private
     * @param  integer  $uid 用户ID
     * @return array
     */

    private function _get_base_info($uid = 0)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select' => 'user_name',
                                'where' => array('uid' => $uid)
                            );

            $data = $this->c->get_one(self::user, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取信用记录
     *
     * @access private
     * @param  integer  $uid  用户ID
     * @return array
     */

    private function _get_credit_info($uid = 0)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            // 借款成功
            $temp['where'] = array(
                                'where' => array('uid' => $uid, 'type' => 6),
                            );

            $data['succeed'] = $this->c->count(self::flow, $temp['where']);

            // 还清借款
            $temp['where']  = array('where' => array('uid' => $uid, 'status' => 7));
            $data['finish'] = $this->c->count(self::borrow, $temp['where']);

            // 申请借款和借款总额
            $temp['where'] = array(
                                'select' => 'COUNT(*) AS `number`,SUM(`amount`) AS `amount`',
                                'where'  => array('uid' => $uid),
                            );

            $data['borrow'] = $this->c->get_row(self::borrow, $temp['where']);

            // 逾期金额和笔数
            $temp['where'] = array(
                                'select' => 'COUNT(*) AS `number`,SUM(`amount`) AS `amount`',
                                'where'  => array(
                                                'uid'    => $uid,
                                                'type'   => 2,
                                                'status' => 0
                                            ),
                            );

            $data['overdue'] = $this->c->get_row(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取投标记录
     *
     * @access private
     * @param  string  $borrow_no 借款编号
     * @return array
     */

    private function _get_borrow_log($borrow_no = '')
    {
        $data = $temp = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select'   => join_field('user_name,mobile', self::user).','.join_field('uid,rate,amount,charge,dateline,pay_time,automatic_type', self::payment),
                                'where'    => array(
                                                    join_field('borrow_no', self::payment) => $borrow_no,
                                                    join_field('type', self::payment) => 1
                                                ),
                                'join'     => array(
                                                    'table' => self::user,
                                                    'where' => join_field('uid', self::user).' = '.join_field('uid', self::payment)
                                                ),
                                'order_by' => join_field('id', self::payment).' desc'
                            );

            $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $data[] = array(
                                'uid'       => $v['uid'],
                                'user_name' => $v['user_name'],
                                'mobile' => $v['mobile'],
                                'rate'      => $v['rate'],
                                'amount'    => $v['amount'],
                                'interest'  => round($v['amount'] * $v['rate'] / 36500,2),
                                'dateline'  => $v['dateline'],
								'automatic_type'  => $v['automatic_type'],
                                'pay_time'  => $v['pay_time']
                            );
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取抵押物信息
     *
     * @access private
     * @return array
     */

    private function _get_collateral_list()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'type,key,value',
                                'where'  => array('borrow_no' => $temp['borrow_no'])
                            );

            $temp['data'] = $this->c->get_all(self::collateral, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $data[$v['type']][] = array('key' => $v['key'], 'value' => $v['value']);
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地区名称
     *
     * @access private
     * @param  integer $region_id 地区ID
     * @return string
     */

    private function _get_region_name($region_id = 0)
    {
        $region_name = '';
        $temp        = array();

        if( ! empty($region_id))
        {
            $temp['where'] = array(
                                'select' => 'region_name',
                                'where'  => array('region_id' => $region_id)
                            );

            $region_name = $this->c->get_one(self::region, $temp['where']);
        }

        unset($temp);
        return $region_name;
    }

    /**
     * 获取用户地址信息
     *
     * @access private
     * @param  integer  $uid  用户ID
     * @param  array    $type 记录类型
     * @return array
     */

    private function _get_user_address($uid = 0, $type = array())
    {
        $data = $temp = array();

        if( ! empty($uid) && ! empty($type))
        {
            $type = (is_array($type)) ? $type : explode(',', $type);

            $temp['where'] = array(
                                'select'   => 'type,province,city,district,address',
                                'where'    => array('uid' => $uid),
                                'where_in' => array('field' => 'type', 'value' => $type)
                            );

            $temp['data'] = $this->c->get_all(self::address, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    $data[$v['type']] = $v;
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户扩展信息
     *
     * @access private
     * @param  integer  $uid  用户ID
     * @param  array    $type 记录类型
     * @return array
     */

    private function _get_user_info($uid = 0, $type = array())
    {
        $data = $temp = array();

        if( ! empty($uid) && ! empty($type))
        {
            $type = (is_array($type)) ? $type : explode(',', $type);

            $temp['where'] = array(
                                'select'   => 'type,key,value',
                                'where'    => array('uid' => $uid),
                                'where_in' => array('field' => 'type', 'value' => $type)
                            );

            $temp['data'] = $this->c->get_all(self::info, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    $data[$v['type']][$v['key']] = $v['value'];
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 更新记录状态
     *
     * @access private
     * @return boolean
     */

    private function _set_borrow_status()
    {
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
     * wsb-2015.5.13 验证是否通过了企业认证
     * 返回 true false
     */
    private function _check_enterprise(){
        $query=FALSE;

        return $query;

    }

/*****************************2015.06.09 hi还款计划相关wsb****************************************/

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
     * 等额本息还款 计划记录
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return array   二维数组
     */
    public function _get_debx_repayment_list($amount,$rate,$months){
        $query=$temp=array();

        if( ! empty($amount) && ! empty($rate) && ! empty($months)){
            $temp['m_rate']=($rate/100)/12;//月利率
            $temp['m_amount']=$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额 (a*i*(1+i)^n)/((1+i)^n-1)
            $temp['pay_principal']=0;//已付本金

            for($i=1;$i<=$months;$i++){
                if($i != $months){
                    $temp['data']['amount']=round($temp['m_amount'],2);//月付本金和利息总额
                    $temp['data']['interest']=($amount*$temp['m_rate']-$temp['m_amount'])*pow((1+$temp['m_rate']),$i-1)+$temp['m_amount'];//月付利息 a*i-b *(1+i)^(n-1)+b
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
    public function _get_debx_all_interest($amount,$rate,$months){
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
    public function _get_debj_repayment_list($amount,$rate,$months){
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
    public function _get_debj_all_interest($amount,$rate,$months){
        return round(($months+1)*$amount*(($rate/100)/12)/2,2);
    }

    /**
     * 一次性本息 利息
     */
    public function _get_ycxbx_interest($amount,$rate,$months){
        return round($amount*(($rate/100)/360)*($months*30),2);
    }

    /**
     * 先息后本 还款计划列表
     * @param $amount float 数量
     * @param $rate  float 利率
     * @param $months float 期数
     * @param $repay_type int 付息方式  按日3 按月1
     */
    public function _get_xxhb_repayment_list($amount=0,$rate=0,$months=0,$repay_type=1){
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
     * 获得还款计划数据
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
     * 获取计划还款日
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
     * 动态生产还款计划
     * @param $borrow_no
     * @return array
     */
    public function set_repay_plan($borrow_no){
        $query=array();
        $temp=array();

        if( ! empty($borrow_no)){
            //查询该单号数据是否存在
            $temp['where']=array(
                'select'=>'mode,due_date,months,repay,amount,rate,deduct',
                'where'=>array('borrow_no'=>$borrow_no)
            );
            $temp['borrow_info']=$this->c->get_row(self::borrow,$temp['where']);
			
			if($temp['borrow_info']['mode'] == 1 ){
				$time = date('Ym',time())."05";
				$time = strtotime($time);
				$temp['borrow_info']['due_date'] = $time;				
			}
			

			
            if( ! empty($temp['borrow_info'])){
				
                $temp['plan_data']=$this->get_borrow_plan($temp['borrow_info']['mode'], $temp['borrow_info']['amount'], $temp['borrow_info']['rate'], $temp['borrow_info']['months']);

                if( ! empty($temp['plan_data']))$temp['plan_date']=$this->_get_repayment_date($temp['borrow_info']['due_date'],$temp['borrow_info']['months'],$temp['borrow_info']['mode'],$temp['borrow_info']['repay']);// 计划时间

                //数据和时间都有
                if( ! empty($temp['plan_date'])){
                    $temp['plan_data_count']=count($temp['plan_data']);

                    foreach($temp['plan_data'] as $k=>$v){ //data和date的k都是从1开始的
                        if($temp['borrow_info']['mode'] == 3 && $k == $temp['plan_data_count']){ //一次性还款付息 最后一起还本金的时间
                            $temp['data']['plan_date']=$temp['plan_date'][1];
                            $temp['data']['repay_index']=$k-1;
                            $temp['data']['repay_type']=2;
                        }elseif($temp['borrow_info']['mode'] == 1 && $k == $temp['plan_data_count']){ //先息后本 最后一起还本金的时间
                            $temp['data']['plan_date']=$temp['plan_date'][$k-1];
                            $temp['data']['repay_index']=$k-1;
                            $temp['data']['repay_type']=2;
                        }else{
                            $temp['data']['repay_date']=$temp['plan_date'][$k];
                            $temp['data']['repay_index']=$k;
                            $temp['data']['repay_type']=((($temp['borrow_info']['mode'] == 1) || ($temp['borrow_info']['mode'] == 3))?1:3);
                        }

                        $temp['data']['repay_amount']=$v['amount'];
                        $temp['data']['repay_principal']=$v['principal'];
                        $temp['data']['repay_interest']=$v['interest'];
                        $temp['data']['repay_surplus_principal']=$v['surplus_principal'];
                        $temp['data']['rapay_time']=($temp['borrow_info']['deduct'] > $k?time():0);
                        $temp['data']['status']=($temp['borrow_info']['deduct'] > $k?3:0);

                        $temp['insert_data'][]=$temp['data'];
                    }

                    $query=$temp['insert_data'];
                }
            }
        }
        unset($temp);
        return $query;
    }

    /**
     * 获得已生产的还款计划
     * @param $borrow_no
     * @return array
     */
    public function get_repay_plan($borrow_no){
        $query=$temp=array();

        if( ! empty($borrow_no)){
            $temp['where']=array(
                'where'=>array('borrow_no'=>$borrow_no)
            );
            $temp['data']=$this->c->get_all(self::repay,$temp['where']);
            if(! empty($temp['data']))$query=$temp['data'];
        }

        unset($temp);
        return $query;
    }
}