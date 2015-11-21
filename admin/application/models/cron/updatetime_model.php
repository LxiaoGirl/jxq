<?php

/**
 *
 *
 * Created by PhpStorm.
 * User: fainle
 * Date: 14-11-20
 * Time: 下午2:35
 */
class UpdateTime_model extends CI_Model
{
    const borrow = 'borrow'; // 交易记录

    /**
     * 更新还款日期(29，30，31特殊情况 会提前到月末还款)
     */
    public function processing()
    {
        $query = TRUE;
        $temp = array();

        // 获取需要处理的记录
        $temp['borrow_list'] = $this->_get_borrow_list();

        if (!empty($temp['borrow_list'])) {

            foreach ($temp['borrow_list'] as $key => $value) {

                $temp['pay_date'] = $this->_get_repayment_date($value['confirm_time'], $value['months']); //计算还款日子

                $temp['where'] = array(
                                    'where' => array('borrow_no' => $value['borrow_no'], 'status' => 4),
                                );

                $temp['data'] = array(
                                    "deadline"    => $temp['pay_date']['deadline'],
                                    "exp_date"    => $temp['pay_date']['exp_date'],
                                    'is_interest' => 1
                                );

                $this->c->set(self::borrow, $temp['where'], $temp['data']);
            }
        }

        return $query;
    }

    /**
     * 查询需要处理的借款记录
     */
    private function _get_borrow_list()
    {
        $temp = array();

        $temp['where'] = array(
            'select' => 'borrow_no,months,confirm_time,deadline,exp_date'
        );

        //查询所有投资记录
        $data = $this->c->get_all(self::borrow, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 获取还款日
     *
     * @access private
     * @param  integer $confirm_time 确认时间
     * @param  integer $months 还款期数
     * @return integer
     */

    private function _get_repayment_date($confirm_time = 0, $months = 0)
    {

        $iDay = array();

        $day = date('j', $confirm_time); //发布日天数
        $month = date('n', $confirm_time); //发布日月数
        $year = date('Y', $confirm_time); //发布日年数
        $hours = date('H', $confirm_time); //发布日小时
        $minute = date('i', $confirm_time); //发布日分钟
        $second = date('s', $confirm_time); //发布日秒

        //如果大于28号(29, 30, 31)
        if ($day > 28) {
            $lastDay = date('t', mktime($hours, $minute, $second, $month + $months, 1, $year));

            if ($day < $lastDay) {
                $deadline = mktime($hours, $minute, $second, $month + $months, $day, $year);
                $iDay['deadline'] = $deadline;
                $iDay['exp_date'] = date('Ymd', $deadline);
            } else {
                $deadline = mktime($hours, $minute, $second, $month + $months, $lastDay, $year);
                $iDay['deadline'] = $deadline;
                $iDay['exp_date'] = date('Ymd', $deadline);
            }

        } else {
            $deadline = mktime($hours, $minute, $second, $month + $months, $day, $year);
            $iDay['deadline'] = $deadline;
            $iDay['exp_date'] = date('Ymd', $deadline);
        }

        return $iDay;
    }
}