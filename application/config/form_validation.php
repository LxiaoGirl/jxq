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

$config['login/index'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_mobile'),
	array('field' => 'password', 'label' => '登录密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'captcha', 'label' => '验证码', 'rules' => 'exact_length[5]')
);

$config['login/register'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'required|is_unique[user.mobile]'),
	array('field' => 'password', 'label' => '登录密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '确认密码', 'rules' => 'required|matches[password]'),
	array('field' => 'referrer', 'label' => '推荐人', 'rules' => 'callback_is_valid_referrer'),
	array('field' => 'captcha', 'label' => '验证码', 'rules' => 'exact_length[5]')

);

$config['login/forget'] = array(
	array('field' => 'mobile', 'label' => '手机号码', 'rules' => 'callback_is_mobile'),
	array('field' => 'authcode', 'label' => '验证码', 'rules' => 'required|exact_length[6]|is_natural'),
	array('field' => 'captcha', 'label' => '验证码', 'rules' => 'required|exact_length[5]')
);

$config['login/password'] = array(
	array('field' => 'password', 'label' => '密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '确认密码', 'rules' => 'required|matches[password]')
);

$config['authentication/email'] = array(
	array('field' => 'email', 'label' => '邮箱地址', 'rules' => 'required|valid_email')
);

$config['profile/index'] = array(
	array('field' => 'user_name', 'label' => '邮箱地址', 'rules' => 'required'),
	array('field' => 'gender', 'label' => '性别', 'rules' => 'required|is_natural'),
	array('field' => 'phone', 'label' => '电话号码', 'rules' => 'callback_is_valid_phone'),
	array('field' => 'province', 'label' => '省份', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'city', 'label' => '城市', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'district', 'label' => '地区', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'address', 'label' => '详细地址', 'rules' => 'required|min_length[6]'),
);

$config['authentication/index'] = array(
	array('field' => 'real_name', 'label' => '密码', 'rules' => 'required|min_length[2]'),
	array('field' => 'nric', 'label' => '确认密码', 'rules' => 'callback_is_valid_nric')
);

$config['transaction/index'] = array(
	array('field' => 'amount', 'label' => '充值金额', 'rules' => 'required|numeric'),
);

$config['transaction/recharge'] = array(
	array('field' => 'amount', 'label' => '充值金额', 'rules' => 'required|numeric'),
);
$config['transaction/recharge5'] = array(
	array('field' => 'amount', 'label' => '充值金额', 'rules' => 'required|numeric'),
);
$config['transaction/recharge10'] = array(
	array('field' => 'amount', 'label' => '充值金额', 'rules' => 'required|numeric'),
);


$config['transaction/transfer'] = array(
	array('field' => 'amount', 'label' => '提现金额', 'rules' => 'required|numeric'),
	array('field' => 'card_no', 'label' => '银行卡', 'rules' => 'required|exact_length[15]'),
	array('field' => 'authcode', 'label' => '验证码', 'rules' => 'required|exact_length[6]|is_natural_no_zero'),
	array('field' => 'security', 'label' => '资金密码', 'rules' => 'required|min_length[6]'),
);

$config['account/create'] = array(
	array('field' => 'bank_id', 'label' => '开户银行', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'bankaddr', 'label' => '营业网点', 'rules' => 'required|min_length[3]'),
	array('field' => 'account', 'label' => '银行账号', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '银行账号', 'rules' => 'required|matches[account]'),
	array('field' => 'password', 'label' => '资金密码', 'rules' => 'required|min_length[6]')
);

$config['security/index'] = array(
	array('field' => 'authcode', 'label' => '验证码', 'rules' => 'required|exact_length[6]|is_natural_no_zero'),
	array('field' => 'security', 'label' => '资金密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '确认资金密码', 'rules' => 'required|matches[security]')
);

$config['security/password'] = array(
	array('field' => 'current', 'label' => '原始密码', 'rules' => 'callback_is_valid_password'),
	array('field' => 'password', 'label' => '新密码', 'rules' => 'required|min_length[6]'),
	array('field' => 'retype', 'label' => '确认密码', 'rules' => 'required|matches[password]')
);

$config['credit/index'] = array(
	array('field' => 'amount', 'label' => '申请金额', 'rules' => 'required|numeric'),
	array('field' => 'remarks', 'label' => '申请说明', 'rules' => 'required|min_length[2]')
);

$config['authentication/enterprise'] = array(
	array('field' => 'organization', 'label' => '单位名称', 'rules' => 'required'),
	array('field' => 'industry', 'label' => '行业', 'rules' => 'required'),
	array('field' => 'property', 'label' => '单位性质', 'rules' => 'required'),
	array('field' => 'reg_date', 'label' => '成立时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'license', 'label' => '营业执照编号', 'rules' => 'required'),
	array('field' => 'tax_no', 'label' => '税务登记证编号', 'rules' => 'required'),
	array('field' => 'turnover', 'label' => '年营业额', 'rules' => 'numeric'),
	array('field' => 'staff', 'label' => '公司规模', 'rules' => 'required'),
	array('field' => 'phone', 'label' => '办公电话', 'rules' => 'required'),
	array('field' => 'province', 'label' => '省份', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'city', 'label' => '城市', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'district', 'label' => '地区', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'address', 'label' => '详细地址', 'rules' => 'required')
);

$config['authentication/base'] = array(
	array('field' => 'education', 'label' => '学历', 'rules' => 'required'),
	array('field' => 'school', 'label' => '毕业院校', 'rules' => 'required'),
	array('field' => 'graduation_date', 'label' => '毕业时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'is_marry', 'label' => '婚姻状况', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'offspring', 'label' => '有无子女', 'rules' => 'is_natural'),
	array('field' => 'estates', 'label' => '是否有房', 'rules' => 'is_natural'),
	array('field' => 'vehicle', 'label' => '已经购车', 'rules' => 'is_natural'),
	array('field' => 'registered[province]', 'label' => '户籍省份', 'rules' => 'is_natural_no_zero'),
	array('field' => 'registered[city]', 'label' => '户籍城市', 'rules' => 'is_natural_no_zero'),
	array('field' => 'registered[district]', 'label' => '户籍地区', 'rules' => 'is_natural_no_zero'),
	array('field' => 'registered[address]', 'label' => '户籍详细地址', 'rules' => 'required'),
	array('field' => 'place[province]', 'label' => '居住地省份', 'rules' => 'is_natural_no_zero'),
	array('field' => 'place[city]', 'label' => '居住地城市', 'rules' => 'is_natural_no_zero'),
	array('field' => 'place[district]', 'label' => '居住地地区', 'rules' => 'is_natural_no_zero'),
	array('field' => 'place[address]', 'label' => '居住地址', 'rules' => 'required'),
);

$config['authentication/company'] = array(
	array('field' => 'organization', 'label' => '单位名称', 'rules' => 'required'),
	array('field' => 'industry', 'label' => '行业', 'rules' => 'required'),
	array('field' => 'property', 'label' => '公司性质', 'rules' => 'required'),
	array('field' => 'staff', 'label' => '公司规模', 'rules' => 'required'),
	array('field' => 'hiredate', 'label' => '入职时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'job', 'label' => '职务', 'rules' => 'required'),
	array('field' => 'province', 'label' => '省份', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'city', 'label' => '城市', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'district', 'label' => '地区', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'address', 'label' => '祥细地址', 'rules' => 'required'),
);

$config['authentication/contacts'] = array(
	array('field' => 'name1', 'label' => '直系亲属1姓名', 'rules' => 'required'),
	array('field' => 'phone1', 'label' => '直系亲属1电话', 'rules' => 'required|callback_is_valid_phone'),
	array('field' => 'name2', 'label' => '直系亲属2姓名', 'rules' => 'required'),
	array('field' => 'phone2', 'label' => '直系亲属2电话', 'rules' => 'required|callback_is_valid_phone'),
	array('field' => 'spouse_phone', 'label' => '配偶电话', 'rules' => 'callback_is_valid_phone'),
	array('field' => 'colleague_name', 'label' => '同事姓名', 'rules' => 'required'),
	array('field' => 'colleague_phone', 'label' => '同事电话', 'rules' => 'required|callback_is_valid_phone'),
	array('field' => 'friend_name', 'label' => '朋友姓名', 'rules' => 'required'),
	array('field' => 'friend_phone', 'label' => '朋友电话', 'rules' => 'required|callback_is_valid_phone'),
	array('field' => 'contact_name', 'label' => '紧急联系人姓名', 'rules' => 'required'),
	array('field' => 'contact_phone', 'label' => '紧急联系人电话', 'rules' => 'required|callback_is_valid_phone'),
);

$config['borrow/apply'] = array(
	array('field' => 'user_name', 'label' => '借款标题', 'rules' => 'required|min_length[2]'),
	array('field' => 'mobile', 'label' => '借款金额', 'rules' => 'callback_is_valid_phone'),
	array('field' => 'type', 'label' => '借款类型', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'amount', 'label' => '所需资金', 'rules' => 'required|numeric'),
	//array('field' => 'dateline', 'label' => '洽谈时间', 'rules' => 'callback_is_valid_date'),
	array('field' => 'province', 'label' => '省份', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'city', 'label' => '城市', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'district', 'label' => '地区', 'rules' => 'required|is_natural_no_zero'),
	array('field' => 'from', 'label' => '来源途径', 'rules' => 'required|min_length[2]')
);

