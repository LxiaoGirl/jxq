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
 * 用户授权
 *
 * 如果验证多个功能模块$module请使用英文逗号分隔
 *
 * @access public
 * @param  string   $module  功能模块
 * @param  string   $action  用户操作
 * @return boolean
 */

if( ! function_exists('authorize'))
{
    function authorize($module = '', $action = '')
    {
        $query = FALSE;
        $rules = array();

        if( ! empty($module))
        {
            $ci    = & get_instance();
            $rules = $ci->session->userdata('authorized');

            if( ! empty($rules))
            {
                if( ! empty($action))
                {
                    $module = (stripos($module, '/') === FALSE) ? $module.'/home' : $module;
                    $query  = (isset($rules[$module]) && in_array($action, $rules[$module])) ? TRUE : FALSE;
                }
                else
                {
                    $module = (stripos($module, ',') !== FALSE) ? explode(',', $module) : array($module);
                    $module = array_intersect($module, array_keys($rules));
                    $query  = ( ! empty($module)) ? TRUE : FALSE;
                }
            }
        }

        unset($rules);
        return $query;
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
 * 地址信息
 *
 * @access public
 * @param  array   $address 地址
 * @param  integer $type    记录类型
 * @return string
 */

if( ! function_exists('address'))
{
    function address($address = array(), $type = 1)
    {
        $str = '';

        if( ! empty($address))
        {
            foreach($address as $v)
            {
                if($v['type'] == $type)
                {
                    $str = $v['province'].$v['city'].$v['district'].$v['address'];
                    break;
                }
            }
        }

        return $str;
    }
}

/**
 * 单位性质
 *
 * @access public
 * @param  integer  $nature 单位性质
 * @return string
 */

if( ! function_exists('nature'))
{
    function nature($nature = 0)
    {
        $str = '';

        switch ($nature) {
            case '1':
                $str = '个人';
                break;
            case '2':
                $str = '企业';
                break;
            default:
                $str = '保密';
        }

        return $str;
    }
}

/**
 * 性别
 *
 * @access public
 * @param  integer  $gender 性别
 * @return string
 */

if( ! function_exists('gender'))
{
    function gender($gender = 0)
    {
        $str = '';

        switch ($gender) {
            case '1':
                $str = '男';
                break;
            case '2':
                $str = '女';
                break;
            default:
                $str = '保密';
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
    			$str = '已撤回';
    			break;
    		case '2':
    			$str = '已审核';
    			break;
    		case '3':
    			$str = '满标';
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
                $str = '交易结束';
                break;
    		default:
    			$str = '待审核';
    			break;
    	}

    	return $str;
    }
}

/**
 * 借款类型
 *
 * @access public
 * @param  integer  $type 借款类型
 * @return string
 */

if( ! function_exists('borrow_type'))
{
    function borrow_type($type = 0)
    {
        $str = '';

        switch ($type) {
            case '1':
                $str = '信用借款';
                break;
            case '2':
                $str = '抵押借款';
                break;
            default:
                $str = '担保借款';
        }

        return $str;
    }
}

/**
 * 充值类型
 *
 * @access public
 * @param  integer  $type 充值类型
 * @return string
 */

if( ! function_exists('recharge_type'))
{
    function recharge_type($type = 0)
    {
        $str = '';

        switch ($type)
        {
            case '1':
                $str = '线下充值';
                break;
            case '2':
                $str = '凯塔平台';
                break;
            default:
                $str = '';
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
                $str = ($type == 1) ? '待审核' : '充值失败';
                break;
        }

        return $str;
    }
}

/**
 * 通用状态
 *
 * @access public
 * @param  integer  $status 记录状态
 * @return string
 */

if( ! function_exists('status'))
{
    function status($status = 0)
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
                $str = '未审核';
        }

        return $str;
    }
}

/**
 * 资金类型
 *
 * @access public
 * @param  integer  $type 记录类型
 * @return string
 */

if( ! function_exists('type'))
{
    function flow_type($type = 0)
    {
        $str = '';

        switch ($type) {
            case '2':
                $str = '提现';
                break;
            case '3':
                $str = '冻结';
                break;
            case '4':
                $str = '解冻';
                break;
            case '5':
                $str = '投资';
                break;
            case '6':
                $str = '借款';
                break;
            case '7':
                $str = '利息收益';
                break;
            case '8':
                $str = '支付利息';
                break;
            case '9':
                $str = '偿还本金';
                break;
            case '10':
                $str = '会员还款';
                break;
            case '11':
                $str = '服务费';
                break;
            default:
                $str = '充值';
        }

        return $str;
    }
}

/**
 * 交易支付类型
 *
 * @access public
 * @param  integer  $type 记录类型
 * @return string
 */

if( ! function_exists('payment_type'))
{
    function payment_type($type = 0)
    {
        $str = '';

        switch ($type) {
            case '1':
                $str = '投资记录';
                break;
            case '2':
                $str = '还款记录';
                break;
            case '3':
                $str = '收益记录';
                break;
            default:
                $str = '还款记录';
        }

        return $str;
    }
}

/**
 * 支付状态
 *
 * @access public
 * @param  integer  $status 支付状态
 * @return string
 */

if( ! function_exists('payment_status'))
{
    function payment_status($type = 0)
    {
        $str = '';

        switch ($type) {
            case '1':
                $str = '已支付';
                break;
            default:
                $str = '未支付';
        }

        return $str;
    }
}

/**
 * 婚姻状况
 *
 * @access public
 * @param  integer $value 婚姻状况
 * @return string
 */

if( ! function_exists('marry'))
{
    function marry($value = 0)
    {
        $str = '';

        switch ($value)
        {
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
                $str = '未婚';
        }

        return $str;
    }
}

/**
 * 有无子女
 *
 * @access public
 * @param  integer $value 有无子女
 * @return string
 */

if( ! function_exists('offspring'))
{
    function offspring($value = 0)
    {
        $str = '';

        switch ($value)
        {
            case '1':
                $str = '一个';
                break;
            case '2':
                $str = '二个';
                break;
            case '3':
                $str = '二个以上';
                break;
            default:
                $str = '无';
        }

        return $str;
    }
}

/**
 * 是否有房
 *
 * @access public
 * @param  integer $value 是否有房
 * @return string
 */

if( ! function_exists('estates'))
{
    function estates($value = 0)
    {
        $str = '';

        switch ($value)
        {
            case '1':
                $str = '商品房(有贷款)';
                break;
            case '2':
                $str = '商品房(无贷款)';
                break;
            case '3':
                $str = '与父母同住';
                break;
            case '4':
                $str = '租房';
                break;
            default:
                $str = '无';
        }

        return $str;
    }
}

/**
 * 是否有车
 *
 * @access public
 * @param  integer $value 是否有车
 * @return string
 */

if( ! function_exists('vehicle'))
{
    function vehicle($value = 0)
    {
        $str = '';

        switch ($value)
        {
            case '1':
                $str = '是';
                break;
            default:
                $str = '否';
        }

        return $str;
    }
}

/**
 * 附件类型
 *
 * @access public
 * @param  integer $type 附件类型
 * @return string
 */

if( ! function_exists('attachment_type'))
{
    function attachment_type($type = 0)
    {
        $str = '';

        switch ($type)
        {
            case '1':
                $str = '抵押权证';
                break;
            case '2':
                $str = '借款人证件';
                break;
            default:
                $str = '合同文件';
        }

        return $str;
    }
}

/**
 * 会员认证
 *
 * @access public
 * @param  integer $value 认证情况
 * @return string
 */

if( ! function_exists('user_status'))
{
    function user_status($value = 0)
    {
        $str = '';

        switch($value)
        {
            case 0:
                $str = '禁止登录';
                break;
            case 1:
                $str = '允许登录';
                break;
            case 2:
                $str = '账户冻结';
                break;
            case 3:
                $str = '账户停用';
                break;
            default:
                $str = '';
        }

        return $str;
    }
}

/**
 * 资金来源
 *
 * @access public
 * @param  string  $source 来源单号
 * @param  integer $type   记录类型
 * @return string
 */

if( ! function_exists('flow_source'))
{
    function flow_source($source = '', $type = 0)
    {
        $str = '';

        switch ($type)
        {
            case 1:
                $str = site_url('finance/recharge?keyword='.$source);
                break;
            case 2:
                $str = site_url('finance/transaction?keyword='.$source);
                break;
            case 3:
                $str = 'javascript:void(0);';
                break;
            case 4:
                $str = 'javascript:void(0);';
                break;
            case 5:
                $str = site_url('finance/trade?keyword='.$source);
                break;
            case 6:
                $str = 'javascript:void(0);';
                break;
            case 11:
                $str = 'javascript:void(0);';
                break;
            default:
                $str = site_url('finance/trade?keyword='.$source);
                break;
        }

        return $str;
    }
}

/**
 * 结算状态
 *
 * @access public
 * @param  integer $value 认证情况
 * @return string
 */

if( ! function_exists('checkout_status'))
{
    function checkout_status($value = 0)
    {
        $str = '';

        if( ! empty($value))
        {
            $str = '已结算';
        }
        else
        {
            $str = '未结算';
        }

        return $str;
    }
}

/**
 * 还款日期
 *
 * @access public
 * @param  integer $confirm_time  满标日期
 * @param  integer $months        月份
 * @return array
 */

if( ! function_exists('repayment_date'))
{
    function repayment_date($confirm_time = 0, $months = 0)
    {
        $iDay = array();

        if( ! empty($confirm_time) && ! empty($months))
        {
            $day    = date('j', $confirm_time); //发布日天数
            $month  = date('n', $confirm_time); //发布日月数
            $year   = date('Y', $confirm_time); //发布日年数
            $hours  = date('H', $confirm_time); //发布日小时
            $minute = date('i', $confirm_time); //发布日分钟
            $second = date('s', $confirm_time); //发布日秒

            //如果大于28号(29, 30, 31)
            if($day > 28)
            {
                $lastDay = date('t', mktime($hours, $minute, $second, $month + $months, 1, $year));

                if ($day < $lastDay)
                {
                    $deadline = mktime($hours, $minute, $second, $month + $months, $day, $year);

                    $iDay['deadline'] = $deadline;
                    $iDay['exp_date'] = date('Ymd', $deadline);
                }
                else
                {
                    $deadline = mktime($hours, $minute, $second, $month + $months, $lastDay, $year);

                    $iDay['deadline'] = $deadline;
                    $iDay['exp_date'] = date('Ymd', $deadline);
                }
            }
            else
            {
                $deadline = mktime($hours, $minute, $second, $month + $months, $day, $year);

                $iDay['deadline'] = $deadline;
                $iDay['exp_date'] = date('Ymd', $deadline);
            }
        }

        return $iDay;
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
            if(item('oss_public'))return site_url('home/image?f='.urlencode(item('oss_bind_hostname').'/'.ltrim($file,'/')));

            $ci=&get_instance();
            return site_url('home/image?f='.urlencode($ci->c->get_oss_image($file)));
        }

        return base_url('admin/'.$file);
    }
}


/**
 * 还款方式
 *
 * @access public
 * @param  integer $value 还款方式
 * @return string
 */

if( ! function_exists('mode_status'))
{
    function mode_status($value = 0)
   {
        $str = '';

        switch ($value)
        {
            case 1:
                $str = "";
                break;
            case 2:
                $str = "";
                break;
			case 3:
                $str = "一次性还本付息";
                break;
            default:
                $str = "";
                break;
        }

        return $str;
    }
}


/**
 * 还款方式
 *
 * @access public
 * @param  integer $value 还款方式
 * @return string
 */

if( ! function_exists('productcategory'))
{
    function productcategory($value = 0)
   {
        $str = '';

        switch ($value)
        {
            case 1:
                $str = "车贷宝";
                break;
            case 2:
                $str = "聚农贷";
                break;
			case 3:
                $str = "一次性还本付息";
                break;
            default:
                $str = "";
                break;
        }

        return $str;
    }
}


/**
 * 截取节点‘/’前
 *
 * 
 * @param  string  $authorized 性别
 * @return string
 */

if( ! function_exists('authorized'))
{
    function authorized($authorized = '')
    {
        $str = strpos($authorized , "/");

        $str = substr($authorized , 0 , $str);

        return $str;
    }
}


/**
 * 节点名称
 *
 * 
 * @param  string  $node 节点
 * @return string
 */
if( ! function_exists('interpret_node'))
{
function  interpret_node($node='') {
	$str = '';
     switch ($node)
        {
            case 'user/home':
                $str = "用户管理";
                break;
            case 'user/group':
                $str = "部门管理";
                break;
			case 'user/role':
                $str = "职位管理";
                break;
			case 'user/node':
                $str = "节点管理";
                break;
            case 'member/home':
                $str = "会员列表";
                break;
			case 'member/commission':
                $str = "佣金提成";
                break;
			case 'member/card':
                $str = "银行卡";
                break;
            case 'member/log':
                $str = "操作日志";
                break;
			case 'member/group':
                $str = "会员分组";
                break;
			case 'borrow/apply':
                $str = "借款申请";
                break;
            case 'borrow/review':
                $str = "资料审核";
                break;
			case 'cron/repayment':
                $str = "会员还款";
                break;
			case 'finance/home':
                $str = "资金明细";
                break;
            case 'finance/trade':
                $str = "投资还款";
                break;
			case 'finance/payment':
                $str = "会员借款";
                break;
			case 'finance/recharge':
                $str = "会员充值";
                break;
            case 'finance/transaction':
                $str = "会员提现";
                break;
			case 'other/home':
                $str = "文章管理";
                break;
			case 'other/category':
                $str = "文章分类";
                break;
            case 'other/region':
                $str = "地区管理";
                break;
			case 'other/log':
                $str = "操作日志";
                break;
			case 'other/productcategory':
                $str = "产品类别管理";
                break;
			case 'member/authen':
                $str = "个人认证";
                break;
			case 'member/authen/enterprise':
                $str = "企业认证";
                break;
			case 'member/invite':
                $str = "居间人列表";
                break;
			case 'borrow/home':
                $str = "借款记录";
                break;
			case 'borrow/home/create':
                $str = "发布标的";
                break;
			case 'finance/lianlian':
                $str = "连连付转账到凯塔";
                break;
            default:
                $str = "未设置";
                break;
        }

        return $str;
}
}



/**
 * 节点css命名
 *
 * 
 * @param  string  $node 节点
 * @return string
 */
if( ! function_exists('css_node'))
{
function  css_node($node='') {
	$str = '';
     switch ($node)
        {
            case 'user':
                $str = "fa-sitemap";
                break;
            case 'member':
                $str = "fa-comments";
                break;
			case 'borrow':
                $str = "fa-list-ol";
                break;
			case 'finance':
                $str = "fa-credit-card";
                break;
            case 'other':
                $str = "fa-tasks";
                break;
			case 'cron':
                $str = "wjss_hk";
                break;
            default:
                $str = "unfound";
                break;
        }

        return $str;
}
}