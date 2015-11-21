<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自定义配置文件
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

$config = array();
$config['site_name']         = '网加金服'; // 网站名称
$config['title']             = '网加金服'; // 网站标题
$config['keywords']          = '网加金服'; // 关键字
$config['description']       = '网加金服'; // 描述信息
$config['theme']             = ''; // 默认主题
$config['skin']              = ''; // 默认皮肤
$config['language']          = 'en_us'; // 默认语言
$config['domain']            = 'http://%s.p2p.com/'; // 附件访问域名 示例：http://%s.aiws.net/
$config['prefix']            = ''; // 域名前缀
$config['cluster']           = 0; //是否使用集群 0表示不启用
$config['limit']             = 10; // 每页显示记录数量
$config['is_gzip']           = FALSE; // 是否开启JS和CSS压缩功能
$config['is_cache']          = TRUE; // 是否开启数据缓存
$config['ttl']               = 86400; //缓存时间(秒)


$config['oss_bucket_img'] = 'wjjf-public'; //bucket的名称
$config['oss_access_id']            = 'ZDtkFsslMIU6eJ65'; // id
$config['oss_access_key']           = '7yZK8GxonnDePd4dDqrTwb5TrilG6E'; //key
$config['oss_upload']                = TRUE; //FALSE   是否启用oss上传
$config['oss_public']                = TRUE; //FALSE
$config['oss_bind_hostname']       = 'http://image.zgwjjf.com'; //FALSE