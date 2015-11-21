<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自定义加载类
 *
 * 让CI可以支持自定义主题，在控制器调用$this->load->set_theme('default');
 * 即可设置视图模板目录根目录为：application/view/default/
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

class MY_Loader extends CI_Loader
{
    /**
     * 初始化
     *
     * @access public
     * @return vioid
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 设置主题目录
     *
     * @access public
     * @param  string $dir 目录名称
     * @return vioid
     */

    public function set_theme($dir = '')
    {
        $theme = APPPATH.'views/'.$dir;

        if( ! is_dir($theme))
        {
            mkdir($theme, 0777, TRUE);
        }

        if( ! empty($dir) && is_really_writable($theme))
        {
            $this->_ci_view_paths = array(APPPATH.'views/'.rtrim($dir, '/').'/' => TRUE);
        }

        unset($theme);
    }

    /**
     * 载入视图
     *
     * 用户数据中默认会检测键名为data的键值,也可以手工指定键名为_field键值
     * 手工指定的键名为_field键值为字符串(以半角状态下的逗号分隔)或者一维数组。
     *
     * @param string  $view   视图文件
     * @param array   $vars   用户数据
     * @param boolean $return 返回数据
     */

    public function view($view, $vars = array(), $return = FALSE)
    {
        if(isset($vars['_field']))
        {
            $vars['_field'] = (is_array($vars['_field'])) ? $vars['_field'] : explode(',', $vars['_field']);

            if( ! empty($vars['_field']))
            {
                foreach($vars['_field'] as $v)
                {
                    if(empty($vars[$v]))
                    {
                        show_error('No data available in table');
                    }
                }
            }
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }
}