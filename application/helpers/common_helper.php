<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公用函数库
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

/**
 * 加解密函数
 *
 * @access public
 * @param  string  $string 字符串
 * @param  string  $key    加密密钥
 * @param  boolean $flag   数据解密
 * @param  integer $expiry 有效期
 * @return string
 */

if( ! function_exists('authcode'))
{
    function authcode($string = '', $key = '', $flag = FALSE, $expiry = 0)
    {
        $data = '';
        $temp = array();

        $temp['length'] = 4;

        $key = ( ! empty($key)) ? $key : item('encryption_key');
        $temp['keya'] = md5(substr($key, 0, 16));
        $temp['keyb'] = md5(substr($key, 16, 16));
        $temp['keyc'] = $temp['length'] ? (( ! empty($flag)) ? substr($string, 0, $temp['length']): substr(md5(microtime()), -$temp['length'])) : '';

        $cryptkey = $temp['keya'].md5($temp['keya'].$temp['keyc']);
        $temp['k_length'] = strlen($cryptkey);

        $string = ( ! empty($flag)) ? base64_decode(substr($string, $temp['length'])) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$temp['keyb']), 0, 16).$string;
        $temp['s_length'] = strlen($string);

        $temp['data'] = '';
        $temp['box'] = range(0, 255);

        $temp['rndkey'] = array();

        for($i = 0; $i <= 255; $i++)
        {
            $temp['rndkey'][$i] = ord($cryptkey[$i % $temp['k_length']]);
        }

        for($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $temp['box'][$i] + $temp['rndkey'][$i]) % 256;
            $tmp = $temp['box'][$i];
            $temp['box'][$i] = $temp['box'][$j];
            $temp['box'][$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $temp['s_length']; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $temp['box'][$a]) % 256;
            $tmp = $temp['box'][$a];
            $temp['box'][$a] = $temp['box'][$j];
            $temp['box'][$j] = $tmp;
            $temp['data'] .= chr(ord($string[$i]) ^ ($temp['box'][($temp['box'][$a] + $temp['box'][$j]) % 256]));
        }

        if( ! empty($flag))
        {
            if((substr($temp['data'], 0, 10) == 0 || substr($temp['data'], 0, 10) - time() > 0) && substr($temp['data'], 10, 16) == substr(md5(substr($temp['data'], 26).$temp['keyb']), 0, 16))
            {
                $data = substr($temp['data'], 26);
            }
        }
        else
        {
            $data = $temp['keyc'].str_replace('=', '', base64_encode($temp['data']));
        }

        unset($temp);
        return $data;
    }
}

/**
 * 编译JS和CSS文件
 *
 * 此函数只支持CSS和JS文件，用户数据以二维数组方式传递或者以英文逗
 * 号分隔的字符串。
 * 数据格式如：array('assets/css/style.css','assets/js/common.js')
 *
 * @access public
 * @param  array   $files 文件列表
 * @param  boolean $flag  是否压缩
 * @return string
 */

if( ! function_exists('build'))
{
    function build($files = array(), $flag = FALSE)
    {
        $data = '';
        $temp = array();

        if( ! empty($files))
        {
            $files = (is_array($files)) ? $files : explode(',', $files);

            $temp['data'] = array();
            $temp['domain'] = cluster();

            foreach($files as $k => $v)
            {
                if( ! empty($v) && is_file($v))
                {
                    $temp['ext'] = pathinfo($v, PATHINFO_EXTENSION);

                    if( ! empty($flag))
                    {
                        $temp['data'][$temp['ext']][] = $v;
                    }
                    else
                    {
                        $temp['ver'] = filemtime($v);
                        $temp['ver'] = ($temp['ver'] !== FALSE && time() - $temp['ver'] <= 86400) ? '?v='.date('Ymd', $temp['ver']) : '';
                        $temp['file'] = $temp['domain'].$v.$temp['ver'];

                        if($temp['ext'] == 'css')
                        {
                            // 如果是用于打印的样式文件,请在文件命名的时候包含print字符串。
                            $temp['media'] =(stripos($temp['file'], 'print')) ? ' media="print"' : '';
                            $data .= '<link href="'.$temp['file'].'" rel="stylesheet" type="text/css"'.$temp['media'].'>';
                        }
                        else if($temp['ext'] == 'js')
                        {
                            $data .= '<script src="'.$temp['file'].'" type="text/javascript"></script>';
                        }

                        $data .= "\r\n";
                    }
                }
            }

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $k => $v)
                {
                    $temp['file'] = $temp['domain'].'builder/f='.implode(',', $v);

                    if($k == 'css')
                    {
                        $data .= '<link href="'.$temp['file'].'" rel="stylesheet" type="text/css">';
                    }

                    if($k == 'js')
                    {
                        $data .= '<script src="'.$temp['file'].'" type="text/javascript"></script>';
                    }

                    $data .= "\r\n";
                }
            }
        }

        unset($temp);
        return $data;
    }
}

/**
 * 复选框/单选框(视图)
 *
 * @access public
 * @param  string  $option 选项值
 * @param  array   $value  表单值
 * @return string
 */

if( ! function_exists('checked'))
{
    function checked($option = '', $value = '')
    {
        $data = '';

        if(! empty($value) && is_array($value))
        {
            $data = (in_array($option, $value)) ? ' checked = "checked" ' : '';
        }
        else
        {
            $data = ($option == $value) ? ' checked = "checked" ' : '';
        }

        return $data;
    }
}

/**
 * 生成集群服务器地址
 *
 * @access public
 * @return string
 */

if( ! function_exists('cluster'))
{
    function cluster()
    {
        $server = '';
        $temp = array();

        $ci = & get_instance();
        $temp['domain'] = ($ci->config->item('domain') !== '' && $ci->config->item('prefix') !== '') ? $ci->config->item('domain') : '';

        if( ! empty($temp['domain']))
        {
            $temp['prefix'] = $ci->config->item('prefix');
            $temp['cluster'] = $ci->config->item('cluster');

            if( ! empty($temp['cluster']) && (int)$temp['cluster'] > 1)
            {
                $temp['prefix'] .= mt_rand(1, (int)$temp['cluster']);
            }

            $server = sprintf($temp['domain'], $temp['prefix']);
        }
        else
        {
            $server = base_url();
        }

        unset($temp);
        return $server;
    }
}

/**
 * 数组处理
 *
 * 比较原有数组和新数组之间的差异
 *
 * @access public
 * @param  array $original  原始数组
 * @param  array $user_data 新数组
 * @return array
 */

if( ! function_exists('contrast'))
{
    function contrast($original = array(), $user_data = array())
    {
        $data = array('add' => array(), 'del' => array(), 'set' => '');

        if( ! empty($original) && ! empty($user_data))
        {
            $data['add'] = array_diff($user_data, $original);
            $data['del'] = array_diff($original, $user_data);
            $data['set'] = array_intersect($original, $user_data);
        }

        return $data;
    }
}

/**
 * 格式化用户数据
 *
 * @access public
 * @param  array  $user_data 用户数据
 * @param  string $key       数组键名
 * @return string
 */

if( ! function_exists('display'))
{
    function display($user_data = '', $key = '')
    {
        if( ! empty($key))
        {
            return (isset($user_data[$key])) ? $user_data[$key] : '';
        }

        return ( ! empty($user_data) && ! in_array($user_data, array('0.00', '1000'))) ? $user_data : '';
    }
}

/**
 * 获取用户数据
 *
 * @access public
 * @param  string $item 参数名称
 * @param  boolan $flag 是否过滤
 * @return string
 */

if( ! function_exists('get'))
{
    function get($item = 'keyword', $flag = FALSE)
    {
        $ci = & get_instance();
        return $ci->input->get_post($item, (boolean)$flag);
    }
}

/**
 * 获取格林尼治时间戳
 *
 * @access public
 * @return interger
 */

if( ! function_exists('gmtime'))
{
    function gmtime()
    {
        $gmt = date('Z');
        return ($gmt > 0) ? time() - $gmt : time() + $gmt;
    }
}

/**
 * 生成图片地址
 *
 * @access public
 * @param  string  $file   文件名
 * @param  integer $width  图片宽度
 * @param  integer $height 图片高度
 * @param  boolean $remote 远程附件
 * @return string
 */

if( ! function_exists('image'))
{
    function image($file = '', $width = 100, $height = 100, $remote = FALSE)
    {
        $name = '';

        if( ! empty($file))
        {
            if($width > 0 && $height > 0)
            {
                $file = pathinfo($file);
                $name = 'image?file='.$file['dirname'].'/'.$file['filename'].'-'.$width.'-'.$height.'.'.$file['extension'];
                $name = ( ! empty($remote)) ? 'http://s.zgwjjf.com/index.php/'.$name : site_url($name);
            }
        }

        return $name;
    }
}

/**
 * 判断是否是外部链接
 *
 * @access public
 * @param  string   $linkurl 访问地址
 * @return boolean
 */

if( ! function_exists('is_external'))
{
    function is_external($linkurl = '')
    {
        $linkurl = parse_url($linkurl);
        return (isset($linkurl['host']) && $linkurl['host'] != $_SERVER['HTTP_HOST']) ? TRUE : FALSE;
    }
}

/**
 * 生成查询字段
 *
 * @access public
 * @param  string  $fields 字段名称
 * @param  array   $table  表名
 * @return string
 */

if( ! function_exists('join_field'))
{
    function join_field($fields = '', $table = '')
    {
        $field = '';
        $temp  = array();

        if( ! empty($fields) && ! empty($table))
        {
            $ci = & get_instance();
            $fields = (is_array($fields)) ? $fields : explode(',', $fields);

            foreach($fields as $k => $v)
            {
                $fields[$k] = $ci->db->dbprefix($table).'.'.$v;
            }

            $field = implode(',', $fields);
        }

        unset($temp);
        return $field;
    }
}

/**
 * 系统配置
 *
 * 获取系统的配置选项
 *
 * @access public
 * @param  string $item 配置项名称
 * @return string
 */

if ( ! function_exists('item'))
{
    function item($item = '')
    {
        $data = '';
        $ci = & get_instance();

        if( ! empty($item))
        {
            $data = $ci->config->item($item);
            $data = ( ! empty($data)) ? $data : '';
        }
        else
        {
            $data = $ci->config->config;
        }

        return $data;
    }
}

/**
 * 语言加载项
 *
 * @access public
 * @param  string  $line  键名
 * @param  string  $file  语言包文件
 * @param  boolean $flag  加载语言包
 * @return string
 */

if ( ! function_exists('lang'))
{
    function lang($line = '', $file = '', $flag = FALSE)
    {
        $data = '';

        if( ! empty($line))
        {
            $ci = & get_instance();

            $temp = array('ignore' => array('', 'common', 'message'));// 文件名在忽略列表里，KEY值不加密

            if(in_array($file, $temp['ignore']))
            {
                $temp['key'] = ( ! empty($file) && strtolower($file) == 'common') ? $line : $line.$file;
                $temp['key'] = preg_replace('/\\pP/', '', strtolower($temp['key']));
                $temp['key'] = str_replace(' ', '_', trim($temp['key']));
            }
            else
            {
                $temp['key'] = md5($line.$file);
            }

            if( ! empty($file) &&  ! empty($flag))
            {
                $temp['config'] = $ci->config->item('language');
                $temp['file'] = APPPATH.'language/'.$temp['config'].'/'.$file.'_lang.php';

                if(is_file($temp['file']))
                {
                    $ci->lang->load($file);
                }
            }

            $temp['data'] = $ci->lang->line($temp['key']);
            $data = ( ! empty($temp['data'])) ? $temp['data'] : $line;
            unset($temp);
        }

        return $data;
    }
}

/**
 * 文件加载
 *
 * 根据文件类型自动生成HTML代码，只支持CSS和JS文件
 *
 * @access public
 * @param  string  $files 文件名
 * @param  boolean $flag  是否压缩
 * @return string
 */

if( ! function_exists('load_file'))
{
    function load_file($files = '', $flag = FALSE)
    {
        $data = '';
        $temp = array();

        if( ! empty($files))
        {
            $temp['data'] = array();
            $temp['files'] = (is_array($files)) ? $files : explode(',', $files);

            foreach($temp['files'] as $k => $v)
            {
                $temp['ext'] = pathinfo($v, PATHINFO_EXTENSION);

                if( ! empty($temp['ext']) && in_array($temp['ext'], array('css', 'js')))
                {
                    $temp['data'][] = 'assets/'.$temp['ext'].'/'.$v;
                }
            }

            $flag = (item('is_gzip') !== FALSE && ! empty($flag)) ? TRUE : FALSE;
            $data = build($temp['data'], $flag);
        }

        unset($temp);
        return $data;
    }
}

/**
 * 加载JS语言包
 *
 * @access public
 * @param  string  $language 语言包文件
 * @return object
 */

if( ! function_exists('load_lang'))
{
    function load_lang($files = '')
    {
        $data = '';
        $ci = & get_instance();

        if( ! empty($files))
        {
            $temp = array('lang' => $ci->config->item('language'), 'data' => '', 'files' => (is_array($files)) ? $files : explode(',', $files));

            if(is_array($temp['files']))
            {
                foreach($temp['files'] as $v)
                {
                    $temp['file'] = APPPATH.'language/'.$temp['lang'].'/'.$v.'_lang.php';

                    if(is_file($temp['file']))
                    {
                        $temp['var'] = $ci->lang->load($v, NULL, TRUE);
                        $temp['data'] .= 'var _'.$v.' = '.json_encode($temp['var']).';';
                    }
                }
            }
            else
            {
                $temp['file'] = APPPATH.'language/'.$temp['lang'].'/'.$temp['files'].'_lang.php';

                if(is_file($temp['file']))
                {
                    $temp['var'] = $ci->lang->load($temp['files'], NULL, TRUE);
                    $temp['data'] .= 'var _'.$temp['files'].' = '.json_encode($temp['var']).';';
                }
            }

            $data = '<script type="text/javascript">'.$temp['data'].'</script>';
            unset($temp);
        }
        else
        {
            $data = '<script type="text/javascript"> var _lang = '.json_encode($ci->lang->language).'</script>';
        }

        return $data;
    }
}

/**
 * 插件加载
 *
 * @access public
 * @param  string  $plugins 插件名称
 * @param  boolean $flag    是否压缩
 * @return string
 */

if( ! function_exists('load_plugin'))
{
    function load_plugin($plugins = '', $flag = FALSE)
    {
        $data = '';

        if( ! empty($plugins))
        {
            $temp = array();

            $temp['data'] = (is_array($plugins)) ? $plugins : explode(',', $plugins);
            $temp['compress'] = (item('compress') !== FALSE) ? item('compress') : $flag;

            foreach ($temp['data'] as $v)
            {
                if(function_exists($v))
                {
                    $data .= call_user_func($v, $temp['compress']);
                }
            }

            unset($temp);
        }

        return $data;
    }
}

/**
 * 格式化日期
 *
 * @access public
 * @param  string  $date  格式化日期
 * @param  integer $flag  日期类型
 * @return string
 */

if( ! function_exists('my_date'))
{
    function my_date($date = 0, $flag = 0)
    {
        $data = '';
        $temp = array('Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d');

        if( ! empty($date) && isset($temp[$flag]))
        {
            $data = date($temp[$flag], $date);
        }

        unset($temp);
        return $data;
    }
}

/**
 * 对象转数组
 *
 * @access public
 * @param  object $obj 操作对象
 * @return array
 */

if( ! function_exists('obj2arr'))
{
    function obj2arr($obj = NULL)
    {
        $data = (is_object($obj)) ? get_object_vars($obj) : $obj;

        if( ! empty($data) && is_array($data))
        {
            foreach($data as $k => $v)
            {
                $v = (is_object($v)) ? obj2arr($v) : $v;
                $data[$k] = $v;
            }
        }

        return $data;
    }
}

/**
 * 生成链接地址
 *
 * 该方法主要用于数据列表页字段排序
 *
 * @access public
 * @param  string $field 排序字段
 * @return string
 */

if( ! function_exists('order_by'))
{
    function order_by($field = '')
    {
        $data = $temp = array();

        $data = array(
                    'port' => ($_SERVER['SERVER_PORT'] == 80) ? 'http://' : 'https://',
                    'host' => $_SERVER['HTTP_HOST'],
                    'self' => $_SERVER['PHP_SELF'],
                    'string' => $_SERVER['QUERY_STRING']
                );

        if( ! empty($field))
        {
            parse_str($data['string'], $temp);

            $temp[$field] = $field;

            if( ! empty($temp[$field]))
            {
                $temp['order'] = (isset($_GET['order']) && $_GET['order'] == 'desc') ? 'asc' : 'desc';
            }

            $data['string'] = http_build_query($temp, '', '&');
        }

        $data['string'] = ( ! empty($data['string'])) ? '?'.$data['string'] : '';
        $data = implode('', $data);

        unset($temp);
        return $data;
    }
}

/**
 * 生成订单编号
 *
 * @access public
 * @param  string  $prefix 前缀
 * @param  integer $length 长度
 * @return string
 */

if( ! function_exists('order_sn'))
{
    function order_sn($prefix = '', $length = 15)
    {
        $data = '';
        $data = ( ! empty($prefix)) ? strtoupper($prefix) : '';

        if($length > strlen($data))
        {
            $data .= date('ymd');

            mt_srand(crc32(microtime()));

            while(strlen($data) < $length)
            {
                $data .= mt_rand();
            }
        }

        $data = substr($data, 0, $length);
        return $data;
    }
}

/**
 * 调试函数
 *
 * @access public
 * @param  array   $data  调试数据
 * @param  integer $flag  调用方法
 * @param  boolean $break 中止执行
 * @return string
 */

if( ! function_exists('p'))
{
    function p($data = NULL, $flag = FALSE, $break = TRUE)
    {
        echo '<meta charset="utf-8"><pre>';

        if( ! empty($flag))
        {
            var_dump($data);
        }
        else
        {
            print_r($data);
        }

        echo '</pre>';

        if( ! empty($break))
        {
            exit();
        }
    }
}

/**
 * 显示列表分页信息
 *
 * @access public
 * @param  integer $total 记录总数
 * @return string
 */

if( ! function_exists('page_info'))
{
    function page_info($total = 0)
    {
        $data = '';

        if(is_int($total))
        {
            $temp = array();

            $temp['limit'] = (isset($_GET['limit'])) ? (int)get('limit') : (int)item('limit');
            $temp['limit'] = ( ! empty($temp['limit'])) ? $temp['limit'] : 10;
            $temp['start'] = (int)get('page') + 1;
            $temp['end'] = (int)get('page') + $temp['limit'];
            $data = sprintf('Showing %s to %s of %d entries', $temp['start'], $temp['end'], (int)$total);

            unset($temp);
        }

        return $data;
    }
}

/**
 * 格式化金额
 *
 * @access public
 * @param  float   $price   金额
 * @param  integer $format  格式类型
 * @param  boolean $flag    格式化
 * @return string
 */

if( ! function_exists('price_format'))
{
    function price_format($price = 0, $format = 0, $flag = TRUE)
    {
        $price = (is_numeric($price)) ? $price : 0;

        switch($format)
        {
            case 1: // 直接取整
                $price = (int)$price;
                break;
            case 2: // 四舍五入保留两位小数
                $price = number_format($price, 2, '.', ',');
                break;
            case 3: // 以万为单位
                $price = round($price / 10000, 2);
                break;
            case 4: // 以万为单位
                $yi=floor($price / 100000000);
                $wan=floor(($price-$yi*100000000) / 10000);
                $sy=$price-$yi*100000000-$wan*10000;
                $rs ='';
                if($yi > 0)$rs=$yi.'亿';
                if($wan > 0)$rs.=$wan.'万';
                if($sy > 0)$rs.=$sy;
                $price=$rs;
                break;
            default: // 四舍五入保留两位小数
                $price = round($price, 2);
        }

        return ( ! empty($flag)) ? sprintf('¥ %s', $price) : $price;
    }
}




/**
 * 格式化金额
 *
 * @access public
 * @param  float   $price   金额
 * @param  integer $format  格式类型
 * @param  boolean $flag    格式化
 * @return string
 */

if( ! function_exists('price_format'))
{
    function price_format($price = 0, $format = 0, $flag = TRUE)
    {
        $price = (is_numeric($price)) ? $price : 0;

        switch($format)
        {
            case 1: // 直接取整
                $price = (int)$price;
                break;
            case 2: // 四舍五入保留两位小数
                $price = number_format($price, 2, '.', ',');
                break;
            case 3: // 以万为单位
                $price = round($price / 10000, 2);
                break;
            case 4: // 以万为单位
                $yi=floor($price / 100000000);
                $wan=floor(($price-$yi*100000000) / 10000);
                $sy=$price-$yi*100000000-$wan*10000;
                $rs ='';
                if($yi > 0)$rs=$yi.'亿';
                if($wan > 0)$rs.=$wan.'万';
                if($sy > 0)$rs.=$sy;
                $price=$rs;
                break;
            default: // 四舍五入保留两位小数
                $price = round($price, 2);
        }

        return ( ! empty($flag)) ? sprintf('¥ %s', $price) : $price;
    }
}



/**
 * 投资来源img
 *
 * @access public
 * @param  int   $automatic_type   状态
 *
 */

if( ! function_exists('Investment_sources'))
{
    function Investment_sources($automatic_type = 0)
    {

        switch($automatic_type)
        {
            case 1: // 自动投
                $automatic_type = 'n_1.png';
                break;
            case 2: // 取消自动投
                $automatic_type = 'n_1.png';
                break;
            case 3: // app版
				$automatic_type = 'APP.png';
                break;
			case 4: // m版
				$automatic_type = 'WAP.png';
                break;
            default: // pc版
                $automatic_type = '';
        }

        return $automatic_type;
    }
}


/**
 * 投资来源title
 *
 * @access public
 * @param  int   $automatic_type   状态
 *
 */

if( ! function_exists('Investment_sources_alt'))
{
    function Investment_sources_alt($automatic_type = 0)
    {

        switch($automatic_type)
        {
            case 1: // 自动投
                $automatic_type = '自动投标';
                break;
            case 2: // 取消自动投
                $automatic_type = '自动投标';
                break;
            case 3: // app版
				$automatic_type = 'APP端投资';
                break;
			case 4: // m版
				$automatic_type = '手机网页端投资';
                break;
            default: // pc版
                $automatic_type = '电脑端投资';
        }

        return $automatic_type;
    }
}



/**
 * 显示个人信息
 *
 * @access public
 * @param  string  $item 选项名称
 * @return string
 */

if( ! function_exists('profile'))
{
    function profile($item = '')
    {
        $data = '';
        $ci   = & get_instance();


        if( ! empty($item))
        {
            $data = $ci->session->userdata($item);
        }
        else
        {
            $data = $ci->session->all_userdata();
        }

        return $data;
    }
}





/**
 * 生成随机字符串
 *
 * @access public
 * @param  integer $length 字符串长度
 * @param  boolean $flag   生成数值
 * @return string
 */

if( ! function_exists('random'))
{
    function random($length = 10, $flag = FALSE)
    {
        $data = '';
        $length = ( ! empty($length) && is_int($length)) ? $length : 10;

        $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, ( ! empty($flag)) ? 10 : 35);
        $seed = ( ! empty($flag)) ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));

        if( ! empty($flag))
        {
            $data = '';
        }
        else
        {
            $data = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }

        $max = strlen($seed) - 1;

        for($i = 0; $i < $length; $i++)
        {
            $data .= $seed{mt_rand(0, $max)};
        }

        unset($seed);
        return $data;
    }
}

/**
 * 下拉菜单(视图)
 *
 * @access public
 * @param  array  $option 选项值
 * @param  string $value  表单值
 * @return string
 */

if( ! function_exists('selected'))
{
    function selected($option = '', $value = '')
    {
        $data = '';

        if(! empty($value) && is_array($value))
        {
            $data = (in_array($option, $value)) ? ' selected = "selected" ' : '';
        }
        else
        {
            $data = ($option == $value) ? ' selected = "selected" ' : '';
        }

        return $data;
    }
}

/**
 * 发送请求
 *
 * @access public
 * @param  string $url       URL地址
 * @param  array  $user_data 上传数据
 * @return string
 */

if( ! function_exists('send'))
{
    function send($url = '', $user_data = array())
    {
        $data = '';

        if( ! empty($url))
        {
            $url = (substr($url, 0, 4) == 'http') ? $url :  'http://'.$url;

            if(function_exists('curl_init'))
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);

                if( ! empty($user_data))
                {
                    $user_data = (is_array($user_data)) ? http_build_query($user_data, '', '&') : $user_data;
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $user_data);
                }

                $data = curl_exec($ch);
                $data = ( ! empty($data)) ? $data : curl_error($ch);

                curl_close($ch);
            }
            else
            {
                $ci = & get_instance();

                $ci->load->library('Snoopy');
                $ci->snoopy->agent = $_SERVER['HTTP_USER_AGENT'];
                $ci->snoopy->read_timeout = 6;

                if( ! empty($user_data))
                {
                    $ci->snoopy->submit($url, $user_data);
                }
                else
                {
                    $ci->snoopy->fetch($url);
                }

                $data = $ci->snoopy->results;
            }
        }

        return $data;
    }
}

/**
 * 字符串截取
 *
 * @access public
 * @param  string  $string     字符串
 * @param  integer $length     字符长度
 * @param  integer $character  结尾字符
 * @return string
 */

if( ! function_exists('sub_str'))
{
    function sub_str($string = '', $length = 0, $character = '...')
    {
        $data = '';
        $temp = array();

        $temp['str']    = trim($string);
        $temp['length'] = strlen($temp['str']);

        if ($length == 0 || $length >= $temp['length'])
        {
            $data = $temp['str'];
        }
        elseif ($length < 0)
        {
            $length = $temp['length'] + $length;

            if ($length < 0)
            {
                $length = $temp['length'];
            }
        }

        if (function_exists('mb_substr'))
        {
            $data = mb_substr($temp['str'], 0, $length, 'UTF-8');
        }
        elseif (function_exists('iconv_substr'))
        {
            $data = iconv_substr($temp['str'], 0, $length, 'UTF-8');
        }
        else
        {
            $data = substr($temp['str'], 0, $length);
        }

        if ($temp['length'] > $length && ! empty($character))
        {
            $data .= $character;
        }

        unset($temp);
        return $data;
    }
}

/**
 * 生成IN语句
 *
 * @access  public
 * @param   string $field     字段名称
 * @param   array  $user_data 用户数据
 * @return  string
 */

if( ! function_exists('where_in'))
{
    function where_in($field = '', $user_data = array())
    {
        $sql = '';

        if( ! empty($field) && ! empty($user_data))
        {
            $user_data = (is_array($user_data)) ? array_unique($user_data) : explode(',', $user_data);
            $sql = $field.' IN (\''.implode('\',\'', $user_data).'\')';
        }

        return $sql;
    }
}

if( ! function_exists('rate_format')){
    function rate_format($rate){
        if(strpos($rate,'.') !== FALSE){ //有小数点
            $new_rate=rtrim($rate,0);
            if(strpos($new_rate,'.') == strlen($new_rate)-1){
                $new_rate=rtrim($new_rate,'.');
            }
            return $new_rate;
        }
        return $rate;
    }
}

/****************************************2.0版新增******************************************************/
if( ! function_exists('type_name_2')){
	function type_name_2($type){
		$type_name = '没有定义';

		switch($type){
			case '1';
				$type_name = '信';
				break;
			case '2';
				$type_name = '押';
				break;
			case '3';
				$type_name = '保';
				break;
			default:
		}
		return $type_name;
	}
}
if( ! function_exists('type_name_2_name')){
	function type_name_2_name($type){
		$type_name = '没有定义';

		switch($type){
			case '1';
				$type_name = '贵州祥廷';
				break;
			case '2';
				$type_name = '贵州祥廷';
				break;
			case '3';
				$type_name = '贵州祥廷';
				break;
			default:
		}
		return $type_name;
	}
}