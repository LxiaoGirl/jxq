<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/5
 * Time: 16:10
 * 发送类的处理和验证
 */
class Avatar extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * oss https 图片处理 的调用地址方法
	 */
	public function image(){
		$filename=urldecode($this->input->get('f',true));
		$size = getimagesize($filename); //获取mime信息
		$fp=fopen($filename, "rb"); //二进制方式打开文件
		header("Content-type: {$size['mime']}");
		if ($size && $fp) {
			fpassthru($fp); // 输出至浏览器
		}
	}
}