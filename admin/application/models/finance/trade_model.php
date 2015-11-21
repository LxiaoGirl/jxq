<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 投资还款
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Trade_model extends CI_Model
{
    const payment = 'borrow_payment'; // 交易支付记录
    const user    = 'user'; // 会员

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

        $temp['where'] = array(
                            'select'   => join_field('payment_no,type,borrow_no,rate,amount,balance,charge,dateline', self::payment).','.join_field('user_name',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::payment).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::payment).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'P') === 0) ? join_field('payment_no', self::payment) : join_field('user_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }

        $data = $this->c->show_page(self::payment, $temp['where']);

        unset($temp);
        return $data;
    }
}