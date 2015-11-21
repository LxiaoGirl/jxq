<?php

/**
 * 邮件配置文件
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

$config = array();
$config['protocol']     = 'smtp';
//$config['smtp_host']    = 'smtp.exmail.qq.com';
//$config['smtp_user']    = 'service@zgwjjf.com';
//$config['smtp_pass']    = 'et40066*8*>(A';
//$config['smtp_port']    = '465';


/**
 * wsb-2015.5.12 测试邮箱 配置
 */
$config['smtp_host']    = 'smtp.qq.com';
$config['smtp_user']    = 'service@zgwjjf.com';
$config['smtp_pass']    = 'wjjf208';
$config['smtp_port']    = '25';
$config['smtp_timeout'] = '5';
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['mailtype']     = 'html';