<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 银行账号
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Account_model extends CI_Model
{
    const user        = 'user'; // 会员表
    const card        = 'user_card'; // 银行卡
    const bank        = 'bank'; // 支付银行
    const transaction = 'user_transaction'; // 提现记录

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->lang->load('form');
    }

	/**
     * 添加银行卡
     *
     * @access public
     * @return array
     */

	public function create()
	{
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('account/create') == TRUE)
        {
            $temp['security'] = $this->session->userdata('security');
            $temp['hash']     = $this->session->userdata('hash');

            $temp['password'] = $this->input->post('password', TRUE);
            $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

            if($temp['password'] == $temp['security'] || $this->input->post('act', TRUE) == 'reg')
            {
                $temp['data'] = array(
                                    'card_no'   => $this->c->transaction_no(self::card, 'card_no'),
                                    'uid'       => $this->session->userdata('uid'),
                                    'real_name' => $this->session->userdata('real_name'),
                                    'account'   => $this->input->post('account', TRUE),
                                    'bank_id'   => (int)$this->input->post('bank_id'),
                                    'bank_name' => '',
                                   'bankaddr' => $this->input->post('bankaddr', TRUE),
                                   'province' => $this->input->post('province', TRUE),
                                    'city' => $this->input->post('bankaddr', TRUE),
                                    'remarks'   => '',
                                    'dateline'  => time(),
                                );

                $temp['data']['account'] = str_replace(' ', '', $temp['data']['account']);

                if( ! empty($temp['data']['bank_id']))
                {
                    $temp['data']['bank_name'] = $this->_get_bank_name($temp['data']['bank_id']);
                }

                $query = $this->c->insert(self::card, $temp['data']);

                if( ! empty($query))
                {
                    $data = array(
                                'code' => 0,
                                'msg'  => '恭喜，你的银行卡绑定成功！',
                                'url'  => site_url('user/account')
                            );
                }
            }
            else
            {
                $data['msg'] = '你的资金密码不正确!';
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
     * 设置默认账户
     *
     * @access public
     * @return boolean
     */

    public function is_default()
    {
        $query = FALSE;
        $temp  = array();

        $temp['card_no'] = $this->input->get('card_no', TRUE);
        $temp['uid']     = $this->session->userdata('uid');

        if( ! empty($temp['card_no']) && ! empty($temp['uid']))
        {
            $temp['data']  = array('card_no' => $temp['card_no']);
            $temp['where'] = array('where' => array('uid' => $temp['uid']));

            $query = $this->c->update(self::user, $temp['where'], $temp['data']);

            if( ! empty($query))
            {
                $this->session->set_userdata($temp['data']);
            }
        }

        unset($temp);
        return $query;
    }

	/**
     * 获取银行列表
     *
     * @access public
     * @return array
     */

	public function get_bank_list()
	{
		$data = $temp = array();

		$temp['where'] = array(
							'select' => 'bank_id,bank_name',
							'where'  => array('status' => 1)
						);

		$data = $this->c->get_all(self::bank, $temp['where']);

    	unset($temp);
    	return $data;
	}

	/**
     * 获取卡号列表
     *
     * @access public
     * @return array
     */

    public function get_card_list()
    {
    	$data = $temp = array();

    	$temp['uid'] = (int)$this->session->userdata('uid');

    	if( ! empty($temp['uid']))
    	{
    		$temp['where'] = array(
								'select' => join_field('card_no,real_name,account,remarks,dateline',self::card).','.join_field('bank_name,code,content',self::bank),
                                'join'=> array('table' => self::bank,'where'=> self::bank.'.bank_id='.self::card.'.bank_id'),
								'where'  => array(self::card.'.uid' => $temp['uid'])
    						);

    		$temp['data'] = $this->c->get_all(self::card, $temp['where']);

    		if( ! empty($temp['data']))
    		{
    			$temp['amount'] = $this->_get_card_amount();

    			foreach($temp['data'] as $k => $v)
    			{
    				$v['amount'] = (isset($temp['amount'][$v['card_no']])) ? $temp['amount'][$v['card_no']] : 0;

    				$data[] = array(
                                    'card_no'   => $v['card_no'],
                                    'real_name' => $v['real_name'],
                                    'account'   => $v['account'],
                                    'bank_name' => $v['bank_name'],
                                    'content' => $v['content'],
                                    'remarks'   => $v['remarks'],
                                    'dateline'  => $v['dateline'],
									'amount'    => $v['amount'],
                                    'code'    => $v['code'],
    							);
    			}
    		}
    	}

    	unset($temp);
    	return $data;
    }

	/**
     * 获取银行名称
     *
     * @access public
     * @param  integer $bank_id 银行ID
     * @return array
     */

    private function _get_bank_name($bank_id = 0)
    {
		$bank_name = '';
		$where     = array();

    	if( ! empty($bank_id))
    	{
    		$where = array(
						'select' => 'bank_name',
						'where'  => array('bank_id' => (int)$bank_id)
    				);

			$bank_name = $this->c->get_one(self::bank, $where);
    	}

    	unset($where);
    	return $bank_name;
    }

	/**
     * 获取提现金额
     *
     * @access public
     * @return array
     */

    private function _get_card_amount()
    {
    	$data = $temp = array();

    	$temp['uid'] = (int)$this->session->userdata('uid');

    	if( ! empty($temp['uid']))
    	{
    		$temp['where'] = array(
								'select'   => 'card_no,SUM(`amount`) AS `amount`',
								'where'    => array('uid' => $temp['uid']),
								'group_by' => 'card_no'
    						);

    		$temp['data'] = $this->c->get_all(self::transaction, $temp['where']);

    		if( ! empty($temp['data']))
    		{
    			foreach($temp['data'] as $v)
    			{
    				$data[$v['card_no']] = $v['amount'];
    			}
    		}
    	}

    	unset($temp);
    	return $data;
    }
}