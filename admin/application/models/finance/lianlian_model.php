<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 充值记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Lianlian_model extends CI_Model
{
    const flow     = 'cash_flow'; // 资金记录
    const recharge = 'user_recharge'; // 充值记录
    const user     = 'user'; // 会员
	
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
    }
    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['keyword'] = $this->input->get('keyword', TRUE);
        $temp['status']  = (int)$this->input->get('status');
        $temp['type']    = (int)$this->input->get('type');

        $temp['where'] = array(
                            'select'   => join_field('recharge_no,uid,type,amount,source,remarks,add_time,status', self::recharge).','.join_field('user_name,real_name',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::recharge).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::recharge).' desc'
                        );
        $temp['where']['where'][join_field('type', self::recharge)] = "3";
		if( ! empty($temp['status']))
        {
			$temp['where']['where'][join_field('status', self::recharge)] = $temp['status'];
		}else{
			$temp['where']['where'][join_field('status', self::recharge)] = "2";
		}


        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'R') === 0) ? join_field('recharge_no', self::recharge) : join_field('user_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::recharge, $temp['where']);
		$data['status'] = $temp['status'];
		

        unset($temp);
        return $data;
    }

    /**
     * 手动充值
     *
     * @access public
     * @return boolean
     */

    public function handle()
	{
        $data = $temp = array();
		$temp['where'] = array(
                            'select'   => join_field('recharge_no,uid,type,amount,source,remarks,add_time,status', self::recharge).','.join_field('user_name,real_name,firmid,vaccid',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::recharge).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::recharge)
                        );
        $temp['where']['where'][join_field('type', self::recharge)] = "3";
		$temp['where']['where'][join_field('status', self::recharge)] = "2";
        $data = $this->c->get_all(self::recharge, $temp['where']);

		
		//var_dump($data);
		
		foreach($data as $k => $v)
        {
			$temp = array();
			$MarketSerial = time('YmdHis');
			$PVaccId ="30200394000014";
			$PCustName ="沈阳网加金服互联网金融服务有限公司";
			$RVaccId = $v['vaccid'];
			$RCustName = $v['real_name'];
			$amount = $v['amount'];
			$TransferCharge = 0;
			$configData = $this->pay->zhifu($MarketSerial,$PVaccId,$PCustName,$RVaccId,$RCustName,$amount*100,$TransferCharge);
			echo "平台转账给".$RCustName.$amount.$configData['ReturnInfo']['RtnInfo'];
			if($configData['ReturnInfo']['RtnCode']=="000000"){
				$temp['data'] = array(
					'status' => "1",
					'confirm_time' => time(),
				);
				$temp['where'] = array(
					'where' => array('recharge_no' => $v['recharge_no'],'uid' => $v['uid'],'uid' => $v['uid'],'status' => $v['status'],'type' => $v['type'])
                );				
				$this->c->update(self::recharge, $temp['where'], $temp['data']);
			}
		}
		
        unset($temp);
        return $data;
	}
}