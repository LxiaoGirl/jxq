<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 银行卡管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Card_model extends CI_Model
{
	const card = 'user_card'; // 银行卡
	const user = 'user'; // 银行卡
	
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
    }
    /**
     * 获取列表
     *
     * @access public
     * @return array
     */

    public function get_card_list()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);

        $temp['where'] = array(
                            'select'   => 'card_no,real_name,account,bank_name,dateline,status',
                            'order_by' => 'id desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['where']['like'] = array('field' => 'real_name', 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::card, $temp['where']);

        unset($temp);
        return $data;
    }
	 /**
     * 银行卡审核
     *
     * @access public
     * @return array
     */

    public function finish()
    {
        $data = $temp = array();

        $temp['card_no'] = $this->input->get('card_no');

        if( ! empty($temp['card_no']))
        {
            $temp['where'] = array('where' => array('card_no' => $temp['card_no']));
            $data = $this->c->get_row(self::card, $temp['where']);
			$temp['where'] = array('where' => array('uid' => $data['uid']));
            $user = $this->c->get_row(self::user, $temp['where']);
			$Flag1="1";
			$res = $this->pay->tixian($data['card_no'],$data['account'],$data['real_name'],$data['bank_id'],$data['bank_name'],$data['bankaddr'],$user['firmid'],"1","0",$user['nric'],"0",$Flag1,$data['province'],$data['city']);
			if($res['ReturnInfo']['RtnCode']=="000000"){
				$temp['data']  = array('status' => "1");
				$temp['where'] = array('where' => array('card_no' =>  $temp['card_no']));
				$query = $this->c->update(self::card, $temp['where'], $temp['data']);
			}elseif($res['ReturnInfo']['RtnInfo']=="客户存在代付信息，不能新增！"){
				$temp['data']  = array('status' => "-2");
				$temp['where'] = array('where' => array('card_no' =>  $temp['card_no']));
				$query = $this->c->update(self::card, $temp['where'], $temp['data']);				
			}else{
				$temp['data']  = array('status' => "-1");
				$temp['where'] = array('where' => array('card_no' =>  $temp['card_no']));
				$query = $this->c->update(self::card, $temp['where'], $temp['data']);	
				
			}
		}
        // unset($temp);
        // return $data;
    }
	 /**
     * 银行卡修改
     *
     * @access public
     * @return array
     */

    public function modify()
    {
        $data = $temp = array();

        $temp['card_no'] = $this->input->get('card_no');

        if( ! empty($temp['card_no']))
        {
            $temp['where'] = array('where' => array('card_no' => $temp['card_no']));
            $data = $this->c->get_row(self::card, $temp['where']);
			$temp['where'] = array('where' => array('uid' => $data['uid']));
            $user = $this->c->get_row(self::user, $temp['where']);
			$Flag1="2";
			var_dump($data);
			$res = $this->pay->tixian($data['card_no'],$data['account'],$data['real_name'],$data['bank_id'],$data['bank_name'],$data['bankaddr'],$user['firmid'],"1","0",$user['nric'],"0",$Flag1,$data['province'],$data['city']);
			if($res['ReturnInfo']['RtnCode']=="000000"){
				$temp['data']  = array('status' => "1");
				$temp['where'] = array('where' => array('card_no' =>  $temp['card_no']));
				$query = $this->c->update(self::card, $temp['where'], $temp['data']);
			}else{
				$temp['data']  = array('status' => "-2");
				$temp['where'] = array('where' => array('card_no' =>  $temp['card_no']));
				$query = $this->c->update(self::card, $temp['where'], $temp['data']);				
			}
		}
        // unset($temp);
        // return $data;
    }

    /**
     * 获取详情
     *
     * @access public
     * @return array
     */

    public function get_card_info()
    {
        $data = $temp = array();

        $temp['card_no'] = $this->input->get('card_no');

        if( ! empty($temp['card_no']))
        {
            $temp['where'] = array('where' => array('card_no' => $temp['card_no']));
            $data = $this->c->get_row(self::card, $temp['where']);
        }

        unset($temp);
        return $data;
    }
}