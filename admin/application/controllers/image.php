<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 动态图片处理
 *
 * 使用动态生成缩略图功能，请勿自动加载SESSION类。
 *
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-06-25
 * @updated     2014-06-25
 * @version     1.0.0
 */

class Image extends CI_Controller
{
    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model', 'c');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $image = array();

        $image['file']  = $image['name'] = '';
        $image['width'] = $image['height'] = 0;
        $image['args']  = (isset($_GET['file'])) ? $this->input->get('file', TRUE) : '';

        if( ! empty($image['args']))
        {
            $image['args'] = pathinfo($image['args']);
            list($image['name'], $image['width'], $image['height']) = explode('-', $image['args']['filename']);
            $image['file'] = $image['args']['dirname'].'/'.$image['name'].'.'.$image['args']['extension'];
        }

        if(empty($image['file']) || ! is_file($image['file']))
        {
            exit();
        }

        $image['etag'] = $this->_get_etag($image['file']);

        if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $image['etag'])
        {
            header('HTTP/1.1 304 Not Modified');
            exit();
        }
        else
        {
            header('Etag:'.$image['etag']);
        }

        $this->c->thumb($image['file'], TRUE, $image['width'], $image['height']);
    }

    /**
     * 获取ETAG信息
     *
     * @access private
     * @param  string  $image 图片名称
     * @return string
     */

    private function _get_etag($image = '')
    {
        $etag = '';
        $temp = array();

        if( ! empty($image))
        {
            $temp['ip']    = $this->input->ip_address();
            $temp['agent'] = $this->input->server('HTTP_USER_AGENT');
            $etag          = md5($image.$temp['ip'].$temp['agent']);
        }

        unset($temp);
        return $etag;
    }
}