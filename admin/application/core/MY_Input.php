<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自定义输入类
 *
 * 主要功能是过滤用户输入的前后空格，全半角转换，修复获取Cookie没前缀的问题
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

class MY_Input extends CI_Input
{

    /**
     * 修复CI获取COOKIE没有添加前缀的问题
     *
     * @access  public
     * @param   string
     * @param   bool
     * @return  string
     */

    function cookie($index = '', $xss_clean = FALSE)
    {
        if (config_item('cookie_prefix') != '')
        {
            $index = config_item('cookie_prefix').ltrim($index, config_item('cookie_prefix'));
        }

        return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
    }

    /**
    * Fetch an item from either the GET array or the POST
    *
    * @access   public
    * @param    string  The index key
    * @param    bool    XSS cleaning
    * @return   string
    */

    function get_post($index = NULL, $xss_clean = FALSE)
    {
        if($index == NULL)
        {
            $data = array('get' => $this->get(NULL, $xss_clean), 'post' => $this->post(NULL, $xss_clean));

            $data['get'] = ( ! empty($data['get'])) ? $data['get'] : array();
            $data['post'] = ( ! empty($data['post'])) ? $data['post'] : array();

            return array_merge($data['get'], $data['post']);
        }
        else
        {
            if ( ! isset($_POST[$index]) )
            {
                return $this->get($index, $xss_clean);
            }
            else
            {
                return $this->post($index, $xss_clean);
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Clean Input Data
     *
     * This is a helper function. It escapes data and
     * standardizes newline characters to \n
     *
     * @access  private
     * @param   string
     * @return  string
     */

    function _clean_input_data($str)
    {
        if (is_array($str))
        {
            $new_array = array();
            foreach ($str as $key => $val)
            {
                $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
            return $new_array;
        }

        /* We strip slashes if magic quotes is on to keep things consistent

           NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
             it will probably not exist in future versions at all.
        */
        if ( ! is_php('5.4') && get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }

        // Clean UTF-8 if supported
        if (UTF8_ENABLED === TRUE)
        {
            $str = $this->uni->clean_string($str);
        }

        // Remove control characters
        $str = remove_invisible_characters($str);

        // Should we filter the input data?
        if ($this->_enable_xss === TRUE)
        {
            $str = $this->security->xss_clean($str);
        }

        // Standardize newlines if needed
        if ($this->_standardize_newlines == TRUE)
        {
            if (strpos($str, "\r") !== FALSE)
            {
                $str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
            }
        }

        return trim($str);
    }
}
// END Input class

/* End of file Input.php */
/* Location: ./system/core/Input.php */