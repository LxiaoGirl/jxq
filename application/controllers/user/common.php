<?php
if( !defined('BASEPATH') ) exit('No direct script access allowed');

/**
 *公用API接口
 */
class Common extends Api_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('api/common_model', 'common_API');
	}

	/**
	 * 发送短信  需要post参数：mobile captcha act（默认为注册） uid（默认0 忘记密码 注册等 其他有uid的需传）
	 * @return array
	 */
	public function send_sms() {
		$data = $temp = array();

		//接受参数
		$temp['action'] = $this->input->post('act', TRUE);
		$temp['mobile'] = $this->input->post('mobile', TRUE);
		$temp['code']   = $this->input->post('captcha', TRUE);
		$temp['uid']    = $this->input->post('uid', TRUE);

		//处理
		$data = $this->common_API->send_sms($temp);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 发送短信  需要post参数：mobile captcha act（默认为注册） uid（默认0 忘记密码 注册等 其他有uid的需传）
	 * @return array
	 */
	public function send_voice() {
		$data = $temp = array();

		//接受参数
		$temp['action'] = $this->input->post('act', TRUE);
		$temp['mobile'] = $this->input->post('mobile', TRUE);
		$temp['code']   = $this->input->post('captcha', TRUE);
		$temp['uid']    = $this->input->post('uid', TRUE);

		//处理
		$data = $this->common_API->send_voice($temp);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 发送邮件 需要邮件地址  内容（默认是验证模板内容） 验证链接（默认为空）
	 * @return array
	 */
	public function send_email() {
		$data = $temp = array();

		//接受参数
		$temp['email']   = $this->input->post('email', TRUE);
		$temp['content'] = $this->input->post('content', TRUE);
		$temp['url']     = $this->session->userdata('url');

		//处理
		$data = $this->common_API->send_email($temp);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 验证短信码 需要目标 短信码 类型 时间（默认60分钟） uid（无则传0）
	 * @return array
	 */
	public function validation_authcode() {
		$data = $temp = array();

		//接受参数
		$temp['type']   = $this->input->post('type', TRUE);
		$temp['mobile'] = $this->input->post('mobile', TRUE);
		$temp['ninute'] = $this->input->post('ninute', TRUE);

		//处理
		$data = $this->common_API->validation_authcode($temp);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 验证邮箱码 需要目标 短信码 时间（默认60分钟）
	 * @return array
	 */
	public function validation_email() {
		$data = $temp = array();

		//接受参数
		$temp['eamil'] = $this->input->post('eamil', TRUE);
		$temp['code']  = $this->input->post('captcha', TRUE);

		//处理
		$data = $this->common_API->validation_email($temp);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 获取银行信息 无bank_id 则全部查询  有 则查询一条
	 *
	 * @param string $bank_id
	 *
	 * @return array
	 */
	public function get_bank() {
		$data = $temp = array();

		//接受参数
		$temp['bank_id'] = $this->input->post('bank_id', TRUE);

		//处理
		$data = $this->common_API->get_bank($temp['bank_id']);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 根据地区 parent_id 获取该parent_id 下地区列表
	 *
	 * @param int $region_pid 地区父id
	 *
	 * @return array
	 */
	public function get_region() {
		$data = $temp = array();

		//接受参数
		$temp['region_pid'] = $this->input->post('region_pid', TRUE);

		//处理
		$data = $this->common_API->get_region($temp['region_pid']);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 根据 地区id 查询地区名称信息
	 *
	 * @param int $region_id
	 *
	 * @return array
	 */
	public function get_region_info() {
		$data = $temp = array();

		//接受参数
		$temp['region_id'] = $this->input->post('region_id', TRUE);

		//处理
		$data = $this->common_API->get_region_info($temp['region_id']);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 查询银行卡 bin信息   需要连连支付library
	 *
	 * @param string $account 银行卡账号
	 *
	 * @return mixed 数组
	 */
	public function get_bankcard_bin() {
		$data = $temp = array();

		//接受参数
		$temp['account'] = $this->input->post('account', TRUE);

		//处理
		$data = $this->common_API->get_bankcard_bin($temp['account']);

		//输出
		unset($temp);
		$this->_return($data);
	}

	/**
	 * 返回
	 * @param array $data
	 */
	protected function _return($data = array()) {
		$this->api_return($data);
	}
}