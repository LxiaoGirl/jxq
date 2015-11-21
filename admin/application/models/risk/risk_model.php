<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 风险控制
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class risk_model extends CI_Model
{
    const user    = 'user'; // 会员
    const borrow  = 'borrow'; // 借款记录

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
     * 获取借款列表
     *
     * @access public
     * @return array
     */

    public function get_borrow_list()
    {
        $data = $temp = array();

        if( ! empty($temp['mobile']) && ! empty($temp['nric']))
        {

        }

        unset($temp);
        return $data;
    }

    /**
     * 发布标的
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('borrow/create') == TRUE)
        {
            $temp['mobile'] = $this->input->post('mobile');

            $temp['data'] = array(
                                'borrow_no' => $this->c->transaction_no(self::borrow, 'borrow_no'),
                                'subject'    => $this->input->post('subject', TRUE),
                                'remarks'    => $this->input->post('remarks', TRUE),
                                'amount'     => $this->input->post('amount', TRUE),
                                'rate'       => $this->input->post('rate', TRUE),
                                's_rate'     => $this->input->post('s_rate', TRUE),
                                'g_rate'     => $this->input->post('g_rate', TRUE),
                                'mode'       => (int)$this->input->post('mode'),
                                'payment'    => (int)$this->input->post('payment'),
                                'repayment'  => $this->input->post('repayment', TRUE),
                                'start_date' => $this->input->post('start_date', TRUE),
                                'buy_time'   => $this->input->post('buy_time', TRUE),
                                'content'    => $this->input->post('content', TRUE),
                            );

            $temp['data']['start_date'] = ( ! empty($temp['data']['start_date'])) ? strtotime($temp['data']['start_date']) : time();
            $temp['data']['buy_time'] = ( ! empty($temp['data']['buy_time'])) ? strtotime($temp['data']['buy_time']) : time();

            p($temp);
        }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }
}