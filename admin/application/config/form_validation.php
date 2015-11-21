<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 表单验证规则
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-13
 * @updated     2014-10-13
 * @version     1.0.0
 */

$config = array();

$config['passport/index'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_mobile'),
	array('field' => 'password', 'label' => '登录密码', 'rules' => 'required|min_length[5]')
);

$config['passport/sign_up'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'admin_name', 'label' => '用户名', 'rules' => 'required|min_length[2]'),
	array('field' => 'password', 'label' => '登录密码', 'rules' => 'required|min_length[5]'),
	array('field' => 'retype', 'label' => '确认密码', 'rules' => 'required|matches[password]')
);

$config['borrow/create'] = array(
	array('field' => 'subject', 'label' => '借款标题', 'rules' => 'required'),
	array('field' => 'amount', 'label' => '借款金额', 'rules' => 'required|numeric'),
	array('field' => 'lowest', 'label' => '最低投资金额', 'rules' => 'required|numeric'),
	array('field' => 'mobile', 'label' => '用户手机号', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'rate', 'label' => '年利率', 'rules' => 'required|numeric'),
	array('field' => 'real_rate', 'label' => '管理费', 'required|numeric'),
	array('field' => 'mode', 'label' => '还款方式', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'repay', 'label' => '利息支付', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'show_time', 'label' => '生效时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'buy_time', 'label' => '预约购买时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'content', 'label' => '借款描述', 'rules' => 'required')
);

$config['borrow/update'] = array(
	array('field' => 'subject', 'label' => '借款标题', 'rules' => 'required'),
	array('field' => 'amount', 'label' => '借款金额', 'rules' => 'required|numeric'),
	array('field' => 'lowest', 'label' => '最低投资金额', 'rules' => 'required|numeric'),
	array('field' => 'mobile', 'label' => '用户手机号', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'rate', 'label' => '年利率', 'rules' => 'required|numeric'),
	array('field' => 'real_rate', 'label' => '管理费', 'rules' => 'required|numeric'),
	array('field' => 'mode', 'label' => '还款方式', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'repay', 'label' => '利息支付', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'show_time', 'label' => '生效时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'buy_time', 'label' => '预约购买时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'content', 'label' => '借款描述', 'rules' => 'required')
);

$config['borrow/modify'] = array(
	array('field' => 'subject', 'label' => '借款标题', 'rules' => 'required'),
	array('field' => 'summary', 'label' => '借款用途', 'rules' => 'required'),
	array('field' => 'lowest', 'label' => '最低投资金额', 'rules' => 'required|numeric'),
	array('field' => 'show_time', 'label' => '生效时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'buy_time', 'label' => '预约购买时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'buy_time', 'label' => '投资结束时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'content', 'label' => '借款描述', 'rules' => 'required')
);

$config['category/create'] = array(
	array('field' => 'category', 'label' => '分类名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'parent_id', 'label' => '上级分类', 'rules' => 'required|is_natural'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'required|is_natural'),
	array('field' => 'description', 'label' => '分类描述', 'rules' => 'min_length[2]')
);

$config['category/update'] = array(
	array('field' => 'category', 'label' => '分类名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'parent_id', 'label' => '上级分类', 'rules' => 'required|is_natural'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'required|is_natural'),
	array('field' => 'description', 'label' => '分类描述', 'rules' => 'min_length[2]'),
	array('field' => 'cat_id', 'label' => '分类ID', 'rules' => 'required|is_natural_no_zero')
);

$config['article/create'] = array(
	array('field' => 'title', 'label' => '文章标题', 'rules' => 'required|min_length[2]'),
	array('field' => 'cat_id', 'label' => '文章分类', 'rules' => 'required|is_natural'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'required|is_natural'),
	array('field' => 'content', 'label' => '文章内容', 'rules' => 'min_length[2]')
);

$config['article/update'] = array(
	array('field' => 'title', 'label' => '文章标题', 'rules' => 'required|min_length[2]'),
	array('field' => 'cat_id', 'label' => '文章分类', 'rules' => 'required|is_natural'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'required|is_natural'),
	array('field' => 'content', 'label' => '文章内容', 'rules' => 'min_length[2]'),
	array('field' => 'id', 'label' => '文章ID', 'rules' => 'required|is_natural_no_zero')
);

$config['payment/pay_now'] = array(
	array('field' => 'charge', 'label' => '手续费', 'rules' => 'required|numeric'),
	//array('field' => 'card_no', 'label' => '支付帐户', 'rules' => 'required'),
	array('field' => 'status', 'label' => '支付状态', 'rules' => 'required|is_natural')
);

$config['member/update'] = array(
	array('field' => 'user_name', 'label' => '用户名', 'rules' => 'required|min_length[2]'),
	array('field' => 'gender', 'label' => '性别', 'rules' => 'is_natural'),
	array('field' => 'type', 'label' => '会员类型', 'rules' => 'required|is_natural'),
	array('field' => 'group_id', 'label' => '会员分组', 'rules' => 'is_natural'),
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_mobile'),
	array('field' => 'real_name', 'label' => '真实姓名', 'rules' => 'rrequired|min_length[2]'),
	array('field' => 'nric', 'label' => '身份证号码', 'rules' => 'exact_length[18]'),
	array('field' => 'phone', 'label' => '电话号码', 'rules' => 'callback_is_phone'),
	array('field' => 'email', 'label' => '常用邮箱', 'rules' => 'valid_email'),
	array('field' => 'rate', 'label' => '提成比例', 'rules' => 'numeric'),
	array('field' => 'uid', 'label' => '会员ID', 'rules' => 'required|is_natural_no_zero')
);
//自动投表单验证
$config['member/automatic_update'] = array(
	array('field' => 'sy_min', 'label' => '收益最小', 'rules' => 'required|numeric'),
	array('field' => 'sy_max', 'label' => '收益最大', 'rules' => 'required|numeric'),
	array('field' => 'jk_min', 'label' => '最小有效期', 'rules' => 'required'),
	array('field' => 'jk_max', 'label' => '最大有效期', 'rules' => 'required'),
	array('field' => 'pzsj_start', 'label' => '最小期限', 'rules' => 'required'),
	array('field' => 'pzsj_end', 'label' => '最大期限', 'rules' => 'required'),
	array('field' => 'pzje', 'label' => '配置金额百分比', 'rules' => 'required|numeric'),
	array('field' => 'group_id', 'label' => '项目类型', 'rules' => 'is_natural'),
	array('field' => 'statue', 'label' => '按钮开启关闭', 'rules' => 'required'),
	array('field' => 'uid', 'label' => '会员ID', 'rules' => 'required|is_natural_no_zero')
);

$config['recharge/refill'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'amount', 'label' => '充值金额', 'rules' => 'required|numeric'),
	array('field' => 'remarks', 'label' => '充值描述', 'rules' => 'max_length[255]')
);

$config['proflie/index'] = array(
	array('field' => 'admin_name', 'label' => '真实姓名', 'rules' => 'required'),
	array('field' => 'gender', 'label' => '性别', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'email', 'label' => '邮箱地址', 'rules' => 'valid_email')
);

$config['proflie/password'] = array(
	array('field' => 'current', 'label' => '当前登录密码', 'rules' => 'callback_is_valid_password'),
	array('field' => 'password', 'label' => '密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '确认密码', 'rules' => 'required|matches[password]')
);

$config['borrow/collateral'] = array(
	array('field' => 'base[key][]', 'label' => '选项名称', 'rules' => 'required'),
	array('field' => 'base[value][]', 'label' => '选项值', 'rules' => 'required')
);

$config['node/create'] = array(
	array('field' => 'node_name', 'label' => '节点名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'parent_id', 'label' => '上级节点', 'rules' => 'is_natural'),
	array('field' => 'link_url', 'label' => '访问地址', 'rules' => 'min_length[2]'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
	array('field' => 'actions', 'label' => '节点操作', 'rules' => 'min_length[2]'),
);

$config['node/update'] = array(
	array('field' => 'node_name', 'label' => '节点名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'parent_id', 'label' => '上级节点', 'rules' => 'is_natural'),
	array('field' => 'link_url', 'label' => '访问地址', 'rules' => 'min_length[2]'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
	array('field' => 'actions', 'label' => '节点操作', 'rules' => 'min_length[2]'),
	array('field' => 'node_id', 'label' => '节点ID', 'rules' => 'is_natural_no_zero')
);

$config['group/create'] = array(
	array('field' => 'group_name', 'label' => '部门名称', 'rules' => 'required|is_unique[admin_group.group_name]'),
	array('field' => 'parent_id', 'label' => '上级节点', 'rules' => 'is_natural'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
);

$config['group/update'] = array(
	array('field' => 'group_name', 'label' => '部门名称', 'rules' => 'callback_is_valid_group_name'),
	array('field' => 'parent_id', 'label' => '上级节点', 'rules' => 'is_natural'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
	array('field' => 'group_id', 'label' => '部门ID', 'rules' => 'is_natural_no_zero')
);

$config['role/create'] = array(
	array('field' => 'role_name', 'label' => '职位名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'group_id', 'label' => '所属部门', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
);

$config['role/update'] = array(
	array('field' => 'role_name', 'label' => '职位名称', 'rules' => 'required|min_length[2]'),
	array('field' => 'group_id', 'label' => '所属部门', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
	array('field' => 'role_id', 'label' => '职位ID', 'rules' => 'is_natural_no_zero')
);

$config['role/authorization'] = array(
	array('field' => 'authorized', 'label' => '用户权限', 'rules' => 'required'),
	array('field' => 'role_id', 'label' => '职位ID', 'rules' => 'is_natural_no_zero')
);

$config['user/create'] = array(
	array('field' => 'admin_name', 'label' => '用户姓名', 'rules' => 'required|min_length[2]'),
	array('field' => 'gender', 'label' => '用户性别', 'rules' => 'is_natural_no_zero'),
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'parent_id', 'label' => '上级主管', 'rules' => 'is_natural'),
	array('field' => 'role_id', 'label' => '用户职位', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'status', 'label' => '允许登录', 'rules' => 'is_natural')
);

$config['user/update'] = array(
	array('field' => 'admin_name', 'label' => '用户姓名', 'rules' => 'required|min_length[2]'),
	array('field' => 'gender', 'label' => '用户性别', 'rules' => 'is_natural_no_zero'),
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_valid_mobile'),
	array('field' => 'parent_id', 'label' => '上级主管', 'rules' => 'is_natural'),
	array('field' => 'role_id', 'label' => '用户职位', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'status', 'label' => '允许登录', 'rules' => 'is_natural'),
	array('field' => 'admin_id', 'label' => '用户ID', 'rules' => 'required|is_natural_no_zero'),
);

$config['member/group/create'] = array(
	array('field' => 'group_name', 'label' => '分组名称', 'rules' => 'required|is_unique[user_group.group_name]'),
	array('field' => 'parent_id', 'label' => '上级分组', 'rules' => 'is_natural'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
);

$config['member/group/update'] = array(
	array('field' => 'group_name', 'label' => '分组名称', 'rules' => 'callback_is_valid_group_name'),
	array('field' => 'parent_id', 'label' => '上级分组', 'rules' => 'is_natural'),
	array('field' => 'sort_order', 'label' => '显示排序', 'rules' => 'callback_is_valid_sort_order'),
	array('field' => 'remarks', 'label' => '备注信息', 'rules' => 'min_length[2]'),
	array('field' => 'status', 'label' => '记录状态', 'rules' => 'is_natural'),
	array('field' => 'group_id', 'label' => '分组ID', 'rules' => 'is_natural_no_zero')
);
/********** apiuser *********************************************************************************************/
$config['apiuser/create'] = array(
    array('field' => 'uname', 'label' => '用户名称', 'rules' => 'required|min_length[2]'),
    array('field' => 'authentication', 'label' => '权限', 'rules' => 'required'),
    array('field' => 'status', 'label' => '允许登录', 'rules' => 'is_natural')
);

$config['apiuser/update'] = array(
    array('field' => 'uname', 'label' => '用户名称', 'rules' => 'required|min_length[2]'),
    array('field' => 'authentication', 'label' => '权限', 'rules' => 'required'),
    array('field' => 'status', 'label' => '允许登录', 'rules' => 'is_natural')
);
$config['vote/create'] = array(
    array('field' => 'title', 'label' => '标题', 'rules' => 'required|min_length[2]'),
    array('field' => 'category', 'label' => '类别', 'rules' => 'required|is_natural_no_zero'),
    array('field' => 'start_time', 'label' => '开始时间', 'rules' => 'required'),
    array('field' => 'end_time', 'label' => '结束时间', 'rules' => 'required'),
    array('field' => 'counts', 'label' => '结束时间', 'rules' => 'required')
);
$config['vote/update'] = array(
    array('field' => 'title', 'label' => '标题', 'rules' => 'required|min_length[2]'),
    array('field' => 'category', 'label' => '类别', 'rules' => 'required|is_natural_no_zero'),
    array('field' => 'start_time', 'label' => '开始时间', 'rules' => 'required'),
    array('field' => 'end_time', 'label' => '结束时间', 'rules' => 'required'),
    array('field' => 'counts', 'label' => '结束时间', 'rules' => 'required')
);