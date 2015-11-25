<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 项目函数库
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

/**
 * 附件地址
 *
 * @access public
 * @param  string  $path 存储路径
 * @param  integer $type 附件类型
 * @return string
 */

if( ! function_exists('attachment'))
{
    function attachment($path = '', $type = 0)
    {
        $url = '';

        switch ($type)
        {
            case 1:
                $url = sprintf('http://s.zgwjjf.com/%s', $path);
                break;
            default:
                $url = sprintf('https://www.zgwjjf.com/%s', $path);
                break;
        }

        return $url;
    }
}

/**
 * 字符串判断输出
 *
 * @access public
 * @param  string  $string 输出字符串
 * @param  string  $string 为空时的字符串
 * @return string
 */

if( ! function_exists('judge_empty'))
{
    function judge_empty(&$string, &$str='')
    {
        //$arg_list = func_get_args();

        $str = ( ! empty($str)) ? $str : '';

        return ( ! empty($string)) ? $string : $str;
    }
}

/**
 * 将人名币转换成大写
 *
 * @access public
 * @param  float    $string 人名币金额
 * @return string
 */

if( ! function_exists('num2cny'))
{
    function num2cny($number = 0)
    {
        $data = '';
        $temp = array();

        $temp['basical'] = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖");
        $temp['advanced'] = array("拾","佰","仟");

        $number = trim($number);
        if($number > 999999999999) return '数目过大';
        if($number == 0) return '零';

        $number = round($number, 2);
        $temp['number'] = explode(".", $number);

        if( ! empty($temp['number'][0]))
        {
            $temp['number_int'] = array();
            $temp['number_int_arr'] = preg_split("//u", $temp['number'][0], -1, PREG_SPLIT_NO_EMPTY);
            $temp['number_int_arr'] = array_reverse($temp['number_int_arr']);

            $zero_num = 0;
            foreach($temp['number_int_arr'] as $key=>$val)
            {
                $x = $key % 4;
                $zero = ($val == 0) ? true : false;
                if($x && $zero && $zero_num > 1) continue;

                switch($x)
                {
                    case 0:
                        if($key == 4) $temp['number_int'][] = '万';
                        if($key == 8) $temp['number_int'][] = '亿';

                        if($zero)
                        {
                            $zero_num=0;
                        }
                        else
                        {
                            $temp['number_int'][] = $temp['basical'][$val];
                            $zero_num=1;
                        }

                        break;

                    default:
                        if($zero){
                            if($zero_num==1){
                                $temp['number_int'][] = $temp['basical'][$val];
                                $zero_num++;
                            }
                        }else{
                            $temp['number_int'][] = $temp['advanced'][$x-1];
                            $temp['number_int'][] = $temp['basical'][$val];
                        }
                }
            }

            $temp['number_int'] = array_reverse($temp['number_int']);
            $temp['number_int'] = implode($temp['number_int']);

            $data .= $temp['number_int'] . '元';
        }

        if( ! empty($temp['number'][1]))
        {
            $temp['number_decimal'] = '';
            $temp['number_decimal_arr'] = preg_split("//u", $temp['number'][1], -1, PREG_SPLIT_NO_EMPTY);
            if( ! empty($temp['number_decimal_arr'][0])) $temp['number_decimal'] .= $temp['basical'][$temp['number_decimal_arr'][0]] . '角';
            if( ! empty($temp['number_decimal_arr'][1])) $temp['number_decimal'] .= $temp['basical'][$temp['number_decimal_arr'][1]] . '分';

            $data .= $temp['number_decimal'];
        }
        else
        {
            $data .= '整';
        }

        unset($temp);
        return $data;
    }
}

/**
 * 文件地址
 *
 * @access public
 * @param  string  $file 文件名
 * @return string
 */

if( ! function_exists('assets'))
{
    function assets($file = '')
    {
        return base_url('assets/'.$file);
    }
}

/**
 * 获取上传oss图片 的处理
 */
if( ! function_exists('upload_img'))
{
    function upload_img($file = '')
    {
        //配置文件设置了oss上传
        if(item('oss_upload')){
            if(item('oss_public'))return site_url('avatar/image?f='.urlencode(item('oss_bind_hostname').'/'.ltrim($file,'/')));

            $ci=&get_instance();
            return site_url('avatar/image?f='.urlencode($ci->c->get_oss_image($file)));
        }

        return base_url($file);
    }
}

/**
 * 借款状态
 *
 * @access public
 * @param  integer  $status 交易状态
 * @return string
 */

if( ! function_exists('borrow_status'))
{
    function borrow_status($status = 0)
    {
    	$str = '';

    	switch ($status) {
    		case '1':
    			$str = '已取消';
    			break;
    		case '2':
    			$str = '募集中';
    			break;
    		case '3':
    			$str = '融资完成';
    			break;
            case '4':
                $str = '还款中';
                break;
    		case '5':
    			$str = '流标';
    			break;
            case '6':
                $str = '逾期';
                break;
            case '7':
                $str = '还款完成';
                break;
    		default:
    			$str = '待审核';
    			break;
    	}

    	return $str;
    }
}

/**
 * 性别
 *
 * @access public
 * @param  integer  $status 性别
 * @return string
 */

if( ! function_exists('gender'))
{
    function gender($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '男';
                break;
            case '2':
                $str = '女';
                break;
            default:
                $str = '保密';
                break;
        }

        return $str;
    }
}

/**
 * 婚姻情况
 *
 * @access public
 * @param  integer  $status 婚姻情况
 * @return string
 */

if( ! function_exists('marry'))
{
    function marry($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '未婚';
                break;
            case '2':
                $str = '已婚';
                break;
            case '3':
                $str = '离异';
                break;
            case '4':
                $str = '丧偶';
                break;
            default:
                $str = '保密';
                break;
        }

        return $str;
    }
}

/**
 * 子女情况
 *
 * @access public
 * @param  integer  $status 子女情况
 * @return string
 */

if( ! function_exists('offspring'))
{
    function offspring($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '一个';
                break;
            case '2':
                $str = '二个';
                break;
            case '3':
                $str = '二个以下';
                break;
            default:
                $str = '无';
                break;
        }

        return $str;
    }
}

/**
 * 住房情况
 *
 * @access public
 * @param  integer  $status 住房情况
 * @return string
 */

if( ! function_exists('estates'))
{
    function estates($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '商品房(有贷款)';
                break;
            case '2':
                $str = '商品房(无贷款)';
                break;
            case '3':
                $str = '与父母同住';
                break;
            default:
                $str = '租房';
                break;
        }

        return $str;
    }
}

/**
 * 通用状态
 *
 * @access public
 * @param  integer  $status 状态
 * @return string
 */

if( ! function_exists('status'))
{
    function status($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '是';
                break;
            default:
                $str = '否';
                break;
        }

        return $str;
    }
}

/**
 * 审核状态
 *
 * @access public
 * @param  integer  $status 状态
 * @return string
 */

if( ! function_exists('verify'))
{
    function verify($status = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '已审核';
                break;
            case '2':
                $str = '已取消';
                break;
            default:
                $str = '待审核';
                break;
        }

        return $str;
    }
}

/**
 * 充值状态
 *
 * @access public
 * @param  integer  $status 状态
 * @param  integer  $type   充值类型
 * @return string
 */

if( ! function_exists('recharge_status'))
{
    function recharge_status($status = 0, $type = 0)
    {
        $str = '';

        switch ($status) {
            case '1':
                $str = '充值成功';
                break;
            default:
                $str = ($type == 1) ? '待审核' : '重新提交';
                break;
        }

        return $str;
    }
}

/**
 * 隐藏字符串
 *
 * @access public
 * @param  string  $string  待处理字符串
 * @param  integer $length  隐藏字符串数量
 * @param  string  $replace 替换字符
 * @return string
 */

if( ! function_exists('secret'))
{
    function secret($string = 0, $length = 0,$start = 0, $replace = '*')
    {
		if(empty($string)) return '';

        $str  = '';
        $temp = array();

        $temp['arr']   = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
        $temp['start'] = ($start != 0)? $start : round((count($temp['arr']) - $length) / 2);
        $temp['end']   = ($length == 0)? round(count($temp['arr'])): $temp['start'] + $length;

        for($i = $temp['start']; $i < $temp['end']; $i++)
        {
            $temp['arr'][$i] = $replace;
        }

        $str = implode('', $temp['arr']);

        unset($temp);
        return $str;
    }
}








/**
 * 获取还款日
 *
 * @access private
 * @param  integer $confirm_time 确认时间
 * @param  integer $months       还款期数
 * @return integer
 */

if( ! function_exists('repayment_date'))
{
    function repayment_date($confirm_time = 0, $months = 0)
    {
        $aDay = array();

        $day   = date('j', $confirm_time); //发布日天数
        $month = date('n', $confirm_time); //发布日月数
        $year  = date('Y', $confirm_time); //发布日年数

        //生成每个月还款日期数组
        for ($i = 1; $i <= $months; $i++)
        {
            //如果大于28号(29, 30, 31)
            if ($day > 28)
            {
                $lastDay = date('t', mktime(0, 0, 0, $month + $i, 1, $year));

                if ($day < $lastDay)
                {
                    $aDay[$i] = date('Y-m-'.$day, mktime(0, 0, 0, $month + $i, 1, $year));
                }
                else
                {
                    $aDay[$i] = date('Y-m-t', mktime(0, 0, 0, $month + $i, 1, $year));
                }
            }
            else
            {
                $aDay[$i] = date('Y-m-d', mktime(0, 0, 0, $month + $i, $day, $year));
            }
        }

        return $aDay;
    }
}

/**
 * 借款还款方式 2015.5.19
 */
if( ! function_exists('borrow_mode')){
    function borrow_mode($mode){
        $str='';
        switch($mode){
            case 1:
                $str=' 先息后本';
                break;
            case 2:
                $str='等额本息';
                break;
            case 3:
                $str='一次性本息';
                break;
            case 4:
                $str='等额本金';
                break;
        }
        return $str;
    }
}


/**
 * 借款状态
 *
 * @access public
 * @param  integer  $status 交易状态
 * @return string
 */

if( ! function_exists('borrow_status'))
{
    function borrow_status($status = 0)
    {
    	$str = '';

    	switch ($status) {
    		case '1':
    			$str = '已取消';
    			break;
    		case '2':
    			$str = '募集中';
    			break;
    		case '3':
    			$str = '融资完成';
    			break;
            case '4':
                $str = '还款中';
                break;
    		case '5':
    			$str = '流标';
    			break;
            case '6':
                $str = '逾期';
                break;
            case '7':
                $str = '还款完成';
                break;
    		default:
    			$str = '待审核';
    			break;
    	}

    	return $str;
    }
}

/**
 * 收入支出
 *
 * @access public
 * @param  integer  $type 状态
 * @return string
 */

if( ! function_exists('in_come'))
{
    function in_come($type = 0)
    {
        $str = '';

        switch ($type) {
            case '1':
                $str = '收入';
                break;
			case '2':
                $str = '支出';
                break;
			case '10':
                $str = '支出';
                break;
                break;
            default:
                $str = '收入';
                break;
        }

        return $str;
    }


/**
 * 安全等级
 *
 * @access public
 */

if( ! function_exists('safety'))
{
    function safety()
    {
        $data = '';
        $ci   = & get_instance();
		$type = 0;


       $data['user_name'] = $ci->session->userdata('user_name');
	   if( $data['user_name']!=''){
			$type++;
	   }
	   $data['mobile'] = $ci->session->userdata('mobile');
	   if( $data['mobile']!=''){
			$type++;
	   }
	   $data['email'] = $ci->session->userdata('email');
	   if($data['email']!=''){
			$type++;
	   }
	   switch($type){
			case '1';
				$type_name = '低';
				break;
			case '2';
				$type_name = '中';
				break;
			case '3';
				$type_name = '高';
				break;
			default:
		}
		return $type_name;
	}
		
}



/**
 * 安全进度条
 *
 * @access public
 */

if( ! function_exists('Grade'))
{
    function Grade()
    {
        $data = '';
        $ci   = & get_instance();
		$type = 0;


       $data['user_name'] = $ci->session->userdata('user_name');
	   if( $data['user_name']!=''){
			$type++;
	   }
	   $data['mobile'] = $ci->session->userdata('mobile');
	   if( $data['mobile']!=''){
			$type++;
	   }
	   $data['email'] = $ci->session->userdata('email');
	   if($data['email']!=''){
			$type++;
	   }
	   switch($type){
			case '1':
				$type_name = '30%';
				break;
			case '2':
				$type_name = '60%';
				break;
			case '3':
				$type_name = '100%';
				break;
			default:
				$type_name = '0%';
		}
		return $type_name;
	}
		
}
}

if( ! function_exists('percent')){
    function percent($amount=0,$receive=0){
        $receive_rate = 0;

        if($receive){
            if($receive / $amount * 100 >0 && $receive / $amount * 100<1){
                $receive_rate = 1;
            }else if(($receive / $amount * 100)>99 && ($receive / $amount * 100)<100){
                $receive_rate = 99;
            }else{
                $receive_rate=round($receive / $amount * 100);
            }
        }else{
            $receive_rate = 0;
        }

        return $receive_rate;

    }
}
