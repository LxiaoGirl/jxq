<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 邀请人佣金处理
 *
 * @author      Longjianghu Email:779898335@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-11-28
 * @updated     2014-11-28
 * @version     1.0.0
 */

class Commission_model extends CI_Model
{
    const borrow			= 'borrow';				// 借款记录
    const payment			= 'borrow_payment';		// 支付记录
	const user				= 'user';				// 会员表
    const commission		= 'user_commission';	// 会员佣金表
	const admin				= 'admin';				// 管理员表

	/**
	 * 佣金处理
	 *
	 * @access public
	 * @return boolean
	 */

    public function processing()
	{
        $query = TRUE;
        $temp  = array();

        // 获取需要处理的记录
		$temp['borrows']	= $this->_get_borrow_list();
		if( ! empty($temp['borrows']))
		{
			foreach($temp['borrows'] as $borrow_no=>$borrows)
			{
				// 获取借款的投资记录
				$temp['payments'] = $this->_get_payment($borrow_no);
				if(empty($temp['payments']))
				{
					continue;
				}
				
				// 计算佣金，并记录
				$this->db->trans_start();
				foreach($temp['payments'] as $payment)
				{
					// 获取要结算佣金的会员
					$temp['inviters'] = $this->_get_inviter($payment['uid']);
					if(empty($payment['amount']) || empty($temp['inviters']))
					{
						continue;
					}
					
					foreach($temp['inviters'] as $inviter)
					{
						// 计算一个月的佣金
						$temp['commission'] = round(($payment['amount']*$inviter['bonus_rate']*$borrows['months']/1200), 2);
						if(empty($temp['commission']))
						{
							continue;
						}
						
						$temp['data'] = array(
											'uid'			=> $inviter['uid'],
											'borrow_no'		=> $borrow_no,
											'investor'		=> $payment['uid'],
											'payment_no'	=> $payment['payment_no'],
											'amount'		=> $payment['amount'],
											'rate'			=> $inviter['bonus_rate'],
											'commission'	=> $temp['commission'],
											'dateline'		=> time(),
											'status'		=> 0,
										);
						
						// 循环写入每个月没个人相应的佣金
						for($i=1; $i<$borrows['months']; $i++)
						{
							$temp['data']['commission_no']	= $this->c->transaction_no(self::commission, 'commission_no');
							$temp['data']['month']			= $this->_get_to_date($i);
							
							$query = $this->c->insert(self::commission, $temp['data']);
						}
					}
				}
				
				// 修改计算佣金状态
				$temp['data']  = array('is_commission' => 1);
				$temp['where'] = array('where' => array('borrow_no' => $borrow_no));
				
				$query = $this->c->set(self::borrow, $temp['where'], $temp['data']);
				
				$this->db->trans_complete();
			}
		}
		
		unset($temp);
		return $query;
	}
	
	/**
     * 获取还款日和最后还款日
     *
     * @access private
     * @param  integer $n 次N月
     * @return integer
     */
	 
	private function _get_to_date($n)
	{
		$year	= date('Y', time());	//发布日年数
		$month	= date('n', time());	//发布日月数
		 
		$time	= mktime(0, 0, 0, $month + $n, 1, $year);
		
		return date('Ym', $time);
	}

    /**
     * 获取满足计算佣金条件的借款编号
     *
     * @access private
     * @return array
     */

    private function _get_borrow_list()
    {
        $data = $temp = array();

        $temp['where'] = array(
                            'select' => 'borrow_no,months',
                            'where'  => array(
										'is_commission'	=> 0,
										'status'		=> 4,
									),
                        );

        $temp['data'] = $this->c->get_all(self::borrow, $temp['where']);

        if( ! empty($temp['data']))
        {
            foreach($temp['data'] as $val)
            {
                $data[$val['borrow_no']] = $val;
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取投资列表
     *
     * @access private
     * @param  string  $borrow_no 借款编号
     * @return array
     */

    private function _get_payment($borrow_no)
    {
        $data = $temp = array();

        if( ! empty($borrow_no))
        {
            $temp['where'] = array(
                                'select'	=> 'uid,payment_no,amount',
								'where'		=> array(
												'borrow_no'	=> $borrow_no,
												'type'		=> 1,
												'status'	=> 1,
											),
                            );

            $data = $this->c->get_all(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取投资人被邀请的信息
     *
     * @access private
     * @param  integer $uid         投资人uid
     * @param  integer $sub_rate    下级员工佣金，回调使用
     * @param  integer $progression 员工向上层次级数，回调使用
     * @return array
     */

    private function _get_inviter($uid, $sub_rate=0, $progression=0)
    {
        $data = $temp = array();

        if( ! empty($uid))
        {
            $temp['where'] = array(
                                'select'	=> 'uid,rate,inviter',
                                'where'		=> array('uid' => $uid),
                            );

            $temp['data'] = $this->c->get_row(self::user, $temp['where']);
			
			// 0层级是投资人信息。
			if( ! empty($temp['data']) && ! empty($progression))
			{
				$temp['data']['bonus_rate'] = $temp['data']['rate'] - $sub_rate;
				
				// 佣金提成小于0的不计算
				if($temp['data']['bonus_rate'] > 0)
				{
					$data[] = $temp['data'];
				}
			}
			
			if( ! empty($temp['data']['inviter']))
			{
				// 第一个邀请人应该拿他自己的佣金比例
				if($progression != 0)
				{
					$sub_rate = $temp['data']['rate'];
				}
				
				$temp['up_inviter'] = $this->_get_inviter($temp['data']['inviter'], $sub_rate, ++$progression);
				
				$data = array_merge($data, $temp['up_inviter']);
			}
        }

        unset($temp);
        return $data;
    }
}