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

class User_model extends CI_Model
{
    const user        = 'user'; // 会员表
    const borrow      = 'borrow'; // 借款
    const log         = 'user_log'; // 用户日志
    const flow        = 'cash_flow'; // 流水记录
    const payment     = 'borrow_payment'; // 支付记录
    const address     = 'user_address'; // 会员地址
    const transaction = 'user_transaction'; // 提现
	const cate       = 'product_category';//分类

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
		$this->load->model('web_1/borrow_model','borrow');
        $this->load->library('form_validation');
        $this->lang->load('form');
    }

    /**
     * 个人资料
     *
     * @access public
     * @return array
     */

    public function profile()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $temp['data'] = array(
                                'user_name' => $this->input->post('user_name', TRUE),
                                'gender'    => (int)$this->input->post('gender'),
                                'phone'     => $this->input->post('phone', TRUE)
                            );

            $temp['where'] = array(
                                'where' => array('uid' => $this->session->userdata('uid'))
                            );

            $this->db->trans_start();

            $this->c->update(self::user, $temp['where'], $temp['data']);
            $this->_set_user_address();

            $this->db->trans_complete();

            $query = $this->db->trans_status();

            if( ! empty($query))
            {
                $this->session->set_userdata($temp['data']);
                $this->user->add_user_log('profile', '更新个人资料！');

                $data = array(
                            'code' => 0,
                            'msg'  => '你的资料已经更新成功',
                            'url'  => site_url('user')
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
     * 添加会员日志
     *
     * @access public
     * @param  string   $module    模块名称
     * @param  string   $content   日志内容
     * @param  integer  $uid       会员ID
     * @param  string   $user_name 会员姓名
     * @return boolean
     */

    public function add_user_log($module = '', $content = '', $uid = 0, $user_name = '')
    {
        $query = FALSE;
        $logs  = array();

        if( ! empty($module) && ! empty($content))
        {
            $logs = array(
                        'uid'       => ( ! empty($uid)) ? $uid : $this->session->userdata('uid'),
                        'user_name' => ( ! empty($user_name)) ? $user_name : $this->session->userdata('user_name'),
                        'module'    => $module,
                        'content'   => $content,
                        'dateline'  => time()
                    );

            if( ! empty($logs['uid']) && ! empty($logs['user_name']))
            {
                $query = $this->c->insert(self::log, $logs);
            }
        }

        unset($logs);
        return $query;
    }

    /**
     * 获取待收本金
     *
     * @access public
     * @return float
     */

    public function get_cost_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'SUM('.join_field('amount', self::payment).') AS amount',
                                'join'   => array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                                            ),
                                'where'  => array(
                                                join_field('uid', self::payment)   => $temp['uid'],
                                                join_field('type', self::payment)  => 1,
                                                join_field('status', self::borrow)  => 4
                                            )
                            );

            $amount = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取待收收益
     *
     * @access public
     * @return array
     */

    public function get_income_amount()
    {
        $data = $temp = array();
        $data = array('amount' => 0, 'payment' => 0);

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select'   => join_field('amount,rate', self::payment).','.join_field('months', self::borrow),
                                'join'     => array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                                            ),
                                'where_in' => array(
                                                'field' => join_field('status', self::borrow),
                                                'value' => array(2,3,4)
                                            ),
                                'where'    => array(
                                                join_field('uid', self::payment)   => $temp['uid'],
                                                join_field('type', self::payment)  => 1
                                            )
                            );

            $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $temp['interest'] = round($v['amount'] * $v['rate'] / 36000 , 2);
                   //$temp['total']    = floor(($v['deadline'] - $v['confirm_time']) / 86400);
                  //  $temp['days']     = ($v['deadline'] > time()) ? floor(($v['deadline'] - time()) / 86400) : 0;

                   // $data['amount']   += round($temp['interest'] * $temp['total'], 2);

                    if( ! empty($v['months']))
                    {
                        $data['payment'] += round($temp['interest'] * $v['months']*30, 2);
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取冻结金额
     *
     * @access public
     * @return float
     */

    public function get_freeze_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'SUM('.join_field('amount', self::payment).') AS amount',
                                'join'   => array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                                            ),
                                'where_in' => array(
                                                'field' => join_field('status', self::borrow),
                                                'value' => array(2, 3)
                                            ),
                                'where'  => array(
                                                join_field('uid', self::payment)   => $temp['uid'],
                                                join_field('type', self::payment)  => 1
                                            )
                            );


            $amount = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取用户金额
     *
     * @access public
     * @return float
     */

    public function get_transcation_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'SUM(amount) AS amount',
                                'where'  => array('uid' => $temp['uid'], 'status' => 0)
                            );

            $amount = (float)$this->c->get_one(self::transaction, $temp['where']);
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取用户金额
     *
     * @access public
     * @param  integer $type 记录类型
     * @return float
     */

    public function get_user_amount($type = 1)
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($type))
        {
            if($type != 3&&$type != 5&&$type != 7&&$type != 8)
            {
				//var_dump(123456);

                $temp['where'] = array(
                                    'select' => 'SUM(`amount`)',
                                    'where'  => array('uid' => $temp['uid'], 'type' => $type)
                                );

                $amount = $this->c->get_one(self::flow, $temp['where']);
            }elseif($type == 5)
            {
				//var_dump(123456);
                $temp['where'] = array(
                                    'select' => 'SUM(`amount`)',
                                    'where'  => array('uid' => $temp['uid'], 'type' => 1)
                                );

                $amount = $this->c->get_one(self::payment, $temp['where']);
            }elseif($type == 7)
            {
				
				                $temp['where'] = array(
                                    'where'  => array('uid' => $temp['uid'], 'type' => 3)
                                );

                $payment = $this->c->get_all(self::payment, $temp['where']);
				 if( ! empty($payment))
                {
                    foreach($payment as $k => $v)
                    {
						$temp['where'] = array(
                                    'where'  => array('uid' => $v['uid'], 'type' => 1, 'payment_no' => $v['payment_no'])
                        );

						$income_amount = $this->c->get_row(self::payment, $temp['where']);
						$interest = $v['amount']-$income_amount['amount'];

						$amount += $interest;
						
						
                        // if($v['type'] == 1)
                        // {
                            // $amount += $v['amount'];
                        // }
                        // else
                        // {
                            // $amount -= $v['amount'];
                        // }
                    }
                }	
                
				// $temp['where'] = array(
                                    // 'select'   => 'SUM(`amount`) AS `amount`, `type`',
                                    // 'where'    => array('uid' => $temp['uid']),
                                    // 'where_in' => array('field' => 'type', 'value' => array(1, 3)),
                                    // 'group_by' => 'type'
                                // );

                // $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

                // if( ! empty($temp['data']))
                // {
                    // foreach($temp['data'] as $k => $v)
                    // {
                        // if($v['type'] == 1)
                        // {
                            // $amount += $v['amount'];
                        // }
                        // else
                        // {
                            // $amount -= $v['amount'];
                        // }
                    // }
                // }				
				
            }elseif($type == 8)
            {
				
				                $temp['where'] = array(
                                    'select' => 'SUM(`amount`)',
                                    'where'  => array('uid' => $temp['uid'], 'type' => 3)
                                );

                $amount = $this->c->get_one(self::payment, $temp['where']);
                $temp['where'] = array(
                                    'select'   => 'SUM(`amount`) AS `amount`, `type`, `borrow_no`',
                                    'where'    => array('uid' => $temp['uid']),
                                    'where_in' => array('field' => 'type', 'value' => array(1, 3)),
                                    'group_by' => 'type'
                                );

                $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

                if( ! empty($temp['data']))
                {
                    foreach($temp['data'] as $k => $v)
                    {
                        if($v['type'] == 1)
                        {
							$temp['where'] = array(
                                    'select' => 'months,rate',
                                    'where'  => array('borrow_no' => $v['borrow_no'])
                                );
							$payment = $this->c->get_one(self::borrow, $temp['where']);
							//var_dump($payment);
				
                            $amount += $v['amount'];
                        }
                        else
                        {
                            $amount -= $v['amount'];
                        }
                    }
                }				
				
            }

            else
            {
                // 冻结金额 = 冻结总额 - 解冻总额
                $temp['where'] = array(
                                    'select'   => 'SUM(`amount`) AS `amount`, `type`',
                                    'where'    => array('uid' => $temp['uid']),
                                    'where_in' => array('field' => 'type', 'value' => array(3, 4)),
                                    'group_by' => 'type'
                                );

                $temp['data'] = $this->c->get_all(self::flow, $temp['where']);

                if( ! empty($temp['data']))
                {
                    foreach($temp['data'] as $k => $v)
                    {
                        if($v['type'] == 3)
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
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取用户最近一次还款金额
     *
     * @access public
     * @return float
     */

    public function get_last_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'amount',
                            'where'  => array('uid' => $temp['uid'], 'type' => 2)
                        );

        $amount = $this->c->get_one(self::payment, $temp['where']);

        unset($temp);
        return $amount;
    }

    /**
     * 近期待还款总额
     *
     * @access public
     * @return float
     */

    public function get_refund_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid']   = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'amount,rate,months,payment,confirm_time,deadline',
                            'where'  => array('uid' => $temp['uid'], 'status' => 4)
                        );

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data']))
        {
            foreach($temp['data'] as $v)
            {
                if($v['months'] == 1)
                {
                    $amount += $v['payment'];
                }
                else
                {
                    $amount += round($v['amount'] * $v['rate'] / 1200);

                    if($v['deadline'] == time())
                    {
                        $amount += $v['amount'];
                    }
                }
            }
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取当日收益
     *
     * @access public
     * @return float
     */

    public function get_today_amount()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => join_field('amount,rate', self::payment),
                                'join'   => array(
                                                'table' => self::borrow,
                                                'where' => join_field('borrow_no', self::payment) .' = '.join_field('borrow_no', self::borrow)
                                            ),
                                'where'  => array(
                                                join_field('uid', self::payment)   => $temp['uid'],
                                                join_field('type', self::payment)  => 1,
                                                join_field('status', self::borrow) => 4
                                            )
                            );

            $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $amount += round($v['amount'] * $v['rate'] / 36000, 2);

                }
            }
        }

        unset($temp);
        return $amount;
    }

    /**
     * 获取账户余额
     *
     * @access public
     * @return float
     */

    public function get_user_balance()
    {
        $balance = 0;
        $temp    = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'balance',
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'id desc'
                        );

        $balance = $this->c->get_one(self::flow, $temp['where']);

        unset($temp);
        return $balance;
    }

    /**
     * 获取用户信息
     *
     * @access public
     * @param  string  $fields 字段名称
     * @return array
     */

    public function get_user_info($fields = '*')
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => $fields,
                            'where'    => array('uid' => $temp['uid']),
                            'order_by' => 'uid desc'
                        );

        $data = $this->c->get_row(self::user, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 最近一笔投资金额
     *
     * @access public
     * @return boolean
     */

    public function get_last_invest()
    {
        $amount = 0;
        $temp   = array();

        $temp['uid']   = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => 'amount',
                            'where'    => array('uid' => $temp['uid'], 'type' => 5),
                            'order_by' => 'id desc'
                        );

        $amount = $this->c->get_one(self::flow, $temp['where']);

        unset($temp);
        return $amount;
    }

    /**
     * 获取记录数量
     *
     * @access public
     * @param  string  $type 数据类型
     * @return integer
     */

    public function get_total_number($type = '')
    {
        $total = 0;
        $temp  = array();

        $temp['uid']   = $this->session->userdata('uid');

        if($type == 'invest') // 投资笔数
        {
            $temp['table'] = self::flow;
            $temp['where'] = array('where'  => array('uid' => $temp['uid'], 'type' => 5));
        }
        elseif($type == 'freeze') // 冻结总数
        {
            $temp['table'] = self::flow;
            $temp['where'] = array('where'  => array('uid' => $temp['uid'], 'type' => 3));
        }
        elseif($type == 'borrow') // 借款总数
        {
            $temp['table'] = self::flow;
            $temp['where'] = array('where'  => array('uid' => $temp['uid'], 'type' => 6));
        }

        $total = $this->c->count($temp['table'], $temp['where']);

        unset($temp);
        return $total;
    }

    /**
     * 获取起止时间
     *
     * @access public
     * @return array
     */

    public function get_search_condition()
    {
        $data = $temp = array();

        $temp['start_date'] = $this->input->get('start_date',TRUE);
        $temp['end_date']   = $this->input->get('end_date', TRUE);

        if( ! empty($temp['start_date']) && ! empty($temp['end_date']))
        {
            $temp['start_date'] = strtotime($temp['start_date']);
            $temp['end_date']   = strtotime($temp['end_date']);

            if($temp['start_date'] === FALSE || $temp['start_date'] === FALSE)
            {
                return FALSE;
            }

            $data = array(
                        'start_date' =>  $temp['start_date'],
                        'end_date'   => ($temp['end_date'] <= $temp['start_date']) ? $temp['start_date'] + 86400 : $temp['end_date']
                    );

        }

        unset($temp);
        return $data;
    }

    /**
     * 最近一笔还款日
     *
     * @access public
     * @return integer
     */

    public function get_last_date()
    {
        $date = 0;
        $temp = array();

        $temp['uid']   = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'confirm_time,months,deadline',
                            'where'  => array('uid' => $temp['uid'], 'status' => 4),
                            'order_by' => 'deadline asc',
                        );

        $temp['date'] = $this->c->get_row(self::borrow, $temp['where']);

        if( ! empty($temp['date']))
        {
            if($temp['date']['months'] > 1)
            {
                $temp['date']['month'] = abs(floor((time() - $temp['date']['confirm_time']) / 86400 / 30));//wsb-2015.5.12 加 abs（） 可能出现负数 comfirm time 是+1days
                $temp['date']['month'] = ( ! empty($temp['date']['month'])) ? $temp['date']['month'] : 1;

                $date = strtotime('+ '.$temp['date']['month'].' months', $temp['date']['confirm_time']);
            }
            else
            {
                $date = $temp['date']['deadline'];
            }
        }

        unset($temp);
        return $date;
    }

    /**
     * 获取投资记录
     *
     * @access public
     * @param  integer  $number 记录数量
     * @return array
     */

    public function get_invest_list($number = 5)
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','.join_field('subject,months,status', self::borrow),
                            'where'    => array(
                                            join_field('uid', self::payment)   => $temp['uid'],
                                            join_field('type', self::payment)  => 1,
                                            join_field('status', self::borrow) => 4
                                        ),
                            'join'     => array(
                                            'table' => self::borrow,
                                            'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)
                                            ),
                            'order_by' => join_field('id', self::payment).' desc',
                            'limit'    => $number
                        );

        $temp['data'] = $this->c->get_all(self::payment, $temp['where']);

        if( ! empty($temp['data']))
        {
            $data['amount'] = 0;

            foreach($temp['data'] as $v)
            {
                $v['interest']  = round($v['amount'] * $v['rate'] / 36000 ,2);
                $data['amount'] += $v['interest'];

                $data['list'][] = array(
                                    'subject'    => $v['subject'],
                                    'payment_no' => $v['payment_no'],
                                    'borrow_no'  => $v['borrow_no'],
                                    'amount'     => $v['amount'],
                                    'rate'       => $v['rate'],
                                    'months'     => $v['months'],
                                    'interest'   => $v['interest'],
                                    'dateline'   => $v['dateline'],
                                    'status'     => $v['status'],
                                );
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取借款记录
     *
     * @access public
     * @param  integer  $number 记录数量
     * @return array
     */

    public function get_borrow_list($number = 5)
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'borrow_no,subject,amount,rate,payment,months,deadline,add_time,confirm_time,status',
                            'where'  => array('uid' => $temp['uid']),
                            'limit'  => $number
                        );

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data']))
        {
            $data['amount'] = 0;
            $data['list']   = array();

            foreach($temp['data'] as $v)
            {
                $data['amount'] += $v['amount'];
                $temp['refund'] = ($v['months'] == 1) ? $v['payment'] : round($v['amount'] * $v['rate'] / 1200,2);
                $temp['date']   = $this->_get_repayment_date($v['confirm_time'], $v['deadline'], $v['months']);

                $data['list'][] = array(
                                    'borrow_no' => $v['borrow_no'],
                                    'subject'   => $v['subject'],
                                    'amount'    => $v['amount'],
                                    'refund'    => $temp['refund'],
                                    'date'      => $temp['date'],
                                    'status'    => $v['status'],
                                    'add_time'  => $v['add_time']
                                );
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地址信息
     *
     * @access public
     * @param  integer $type 记录类型
     * @return integer
     */

    public function get_user_address($type = 0, $uid = 0)
    {
        $data = $temp = array();

        $temp['uid'] = ( ! empty($uid)) ? (int)$uid : $this->session->userdata('uid');

        if( ! empty($temp['uid']) && ! empty($type))
        {
            $temp['where'] = array(
                                'select' => 'province,city,district,address',
                                'where' => array('uid' => $temp['uid'], 'type' => $type)
                            );

            $data = $this->c->get_row(self::address, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取还款日
     *
     * @access private
     * @param  integer  $confirm_time 确认时间
     * @param  integer  $deadline     还款截止时间
     * @param  integer  $months       还款期数
     * @return integer
     */

    private function _get_repayment_date($confirm_time = 0, $deadline = 0, $months = 0)
    {
        $date = $month = 0;

        if($months > 1)
        {
            $month = ceil((time() - $confirm_time) / 86400 / 30);
            $date = strtotime('+ '.$month.' months', $confirm_time);
        }
        else
        {
            $date = $deadline;
        }

        $date = date('Y-m-d', $date);

        unset($month);
        return $date;
    }

    /**
     * 更新用户地址
     *
     * @access public
     * @return boolean
     */

    private function _set_user_address()
    {
        $query = FALSE;
        $temp  = array();

        $temp['uid']   = $this->session->userdata('uid');

        $temp['data'] = array(
                            'uid'      => $temp['uid'],
                            'type'     => 3,
                            'province' => (int)$this->input->post('province'),
                            'city'     => (int)$this->input->post('city'),
                            'district' => (int)$this->input->post('district'),
                            'address'  => $this->input->post('address', TRUE),
                        );

        $temp['where'] = array('where' => array('uid' => $temp['uid'], 'type' => 3));
        $temp['count'] = $this->c->count(self::address, $temp['where']);

        if( ! empty($temp['count']))
        {
            $query = $this->c->update(self::address, $temp['where'], $temp['data']);
        }
        else
        {
            $query = $this->c->insert(self::address, $temp['data']);
        }

        unset($temp);
        return $query;
    }
	/**
     *获得雪球数量
     */

  public function get_num_amount($id='',$table='',$column=''){
	$temp=array();
	$temp['where'] = array(
                            'select'   => $column,
                            'where'    => array('uid' => $id),
                            'order_by' => 'id desc'
                        ); 
	$balance = $this->c->get_one($table, $temp['where']);
	unset($temp);
	return $balance;
  }



   /**
     * 查询 borrow_payment 表  获得 已收本金 和利息
     * 累计收益  已还本金
     */
    public function get_receive_principal_interest(){
        $rs = array('receive_principal'=>0,'receive_interest'=>0);
        if($this->session->userdata('uid') > 0){
            $this->db->select("sum(a.amount)  as receive_principal,sum(b.amount-a.amount) as receive_interest");
            $this->db->from("(select * from cdb_borrow_payment where type=1 ) as a");
            $this->db->join("(select amount,payment_no from cdb_borrow_payment where type=3 ) as b",'a.payment_no=b.payment_no','inner');
            $this->db ->where(array('a.uid'=>$this->session->userdata('uid')));
            $this->db ->group_by("a.uid");
            $query = $this->db ->get();

            if($query->num_rows() > 0){
                $rs = $query->row_array();
            }
            $query->free_result();
        }

        return $rs;
    }

   /**
     * 计算所有利息
     * yx8-27 修改
	 *yx9-8
     */
public function get_user_interest(){
        $interest = 0;
        $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','.join_field('subject,status,months,mode,receive', self::borrow).','.join_field('amount', self::borrow).' as amounts,'.join_field('category',self::cate),
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
                'where_in'    => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'order_by' => array(
                    array('field'=>join_field('status', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
                )
            );

            $temp['status'] = (int)$this->input->get('status');
            if( ! empty($temp['status'])){
                $temp['condition']  = array(join_field('status',self::borrow)=>$temp['status']);
                $temp['where']['where'] = array_merge($temp['where']['where'], $temp['condition']);
            }

            $temp['invest_list'] = $this->c->show_page(self::payment, $temp['where']);

            if( ! empty($temp['invest_list']['data'])){
                foreach($temp['invest_list']['data'] as $v){
                    $temp['project_interest'] = 0;
                    switch($v['mode']){
                        case '1':
                            $temp['project_interest'] = sprintf("%.2f",substr(sprintf("%.3f",$v['amount']*(($v['rate']/100)/12)*$v['months']), 0, -1));
                            break;
                        case '2':
                            $temp['project_interest'] = $this->borrow->_get_debx_all_interest($v['amount'],$v['rate'],$v['months']);
                            break;
                        case '3':
                            $temp['project_interest'] = sprintf("%.2f",substr(sprintf("%.3f",$v['amount']*(($v['rate']/100)/12)*$v['months']), 0, -1));
                            break;
                        case '4':
                            $temp['project_interest'] = 
							$this->borrow->_get_debj_all_interest($v['amount'],$v['rate'],$v['months']);
                            break;
                    }
                    if( ! $temp['project_interest'])$temp['project_interest'] = 0;
                    $interest += $temp['project_interest'];
                }
            }
        }

        unset($temp);
        return $interest;
    }
   //public function get_user_interest(){
        //$interest = 0;
        //$temp = array();

        //$temp['uid'] = (int)$this->session->userdata('uid');
		//yx加confirm_time 字段查找
        //if($temp['uid'] > 0){
            //$temp['where'] = array(
                //'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','.join_field('subject,status,months,mode,receive,confirm_time,due_date', self::borrow).','.join_field('amount', self::borrow).' as amounts,'.join_field('category',self::cate),
                //'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
                //'where_in'    => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                //'join'     => array(
                   // array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = //'.join_field('borrow_no', self::borrow)),
                    //array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = //'.join_field('productcategory', self::borrow))
               // ),
                //'order_by' => array(
                    //array('field'=>join_field('status', self::borrow),'value'=>'asc'),
                  //  array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
                //    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
              //  )
            //);

            

            //$temp['invest_list'] = $this->c->show_page(self::payment, $temp['where']);

            //if( ! empty($temp['invest_list']['data'])){
				//foreach($temp['invest_list']['data'] as $v){
					//yx算实际还款天数

				//	if($v['status']=='2'||$v['status']=='3'){
				//	$start_time=$v['due_date'];
				//}
				//if($v['status']=='4'||$v['status']=='7'){
				//	 $start_time=$v['confirm_time']-86400;
				//}
				
					//$temp['repay_plan']=($v['status'] == 4 || $v['status'] == //7)?$this->borrow->get_repay_plan($v['borrow_no']):$this->borrow->set_repay_plan($v['borrow_no']);
				//foreach($temp['repay_plan'] as $k1 => $v1)
				//{
				//	$last_time=strtotime($v1['repay_date']);
				//	break;
				//}	
				
				

				//$day=round(($last_time-$start_time)/86400);

                    //$temp['project_interest'] = 0;
					//yx算实际还款天数得到的利息
                    //switch($v['mode']){
                      //  case '1':								
                            //$temp['project_interest'] = $this->borrow->_get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
					//	$temp['project_interest'] = sprintf("%.2f",substr(sprintf("%.3f",$v['amount']*(($v['rate']/100)/360)*($day)), 0, -1));
                    //        break;
                    //    case '2':
                  //          $temp['project_interest'] = $this->borrow->_get_debx_all_interest($v['amount'],$v['rate'],$v['months']);
                   //         break;
                  //      case '3':
							//$temp['project_interest'] = $this->borrow->_get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
                 //           $temp['project_interest'] = //sprintf("%.2f",substr(sprintf("%.3f",$v['amount']*(($v['rate']/100)/360)*($day)), 0, -1));
                 //           break;
                //        case '4':
               //             $temp['project_interest'] = //$this->borrow->_get_debj_all_interest($v['amount'],$v['rate'],$v['months']);
             //               break;
             //       }
           //         if( ! $temp['project_interest'])$temp['project_interest'] = 0;
           //         $interest += $temp['project_interest'];
         //       }
       //     }
    //    }
		
		//$temp['borrow_interest']=$this->borrow->_get_borrow_interest();

     //   unset($temp);
    //    return $interest;
   // }

 
   /**
	 *yx8-28
     * 查询cash_flow支出总金额
     * 累计收益  已还本金
     */
    public function get_cash_come(){
		$temp=array();
		$cash_come = 0;
		$temp['uid'] = $this->session->userdata('uid');
        $temp['where'] = array(
                                    'select' => 'SUM(`amount`)',
                                    'where'  => array('uid' => $temp['uid']),
									'where_in'=>array(
									     'field'=>'type',
									     'value'=>array(2,10)
									 )
                                );

                $cash_come = $this->c->get_one(self::flow, $temp['where']);

        return $cash_come;
    }

	   /**
	 *yx8-28
     * 查询cash_flow收入总金额
     * 累计收益  已还本金
     */
    public function get_cash_in(){
		$temp=array();
		$cash_in = 0;
		$temp['uid'] = $this->session->userdata('uid');
        $temp['where'] = array(
                                    'select' => 'SUM(`amount`)',
                                    'where'  => array('uid' => $temp['uid'],'type'=>'1')
                                );

                $cash_in = $this->c->get_one(self::flow, $temp['where']);

        return $cash_in;
    }

}