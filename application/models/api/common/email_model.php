<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 邮箱 的发送、验证 相关model
 * Class Email_model
 */
class Email_model extends CI_Model{
    const authcode = 'authcode'; // 验证授权表
    protected $_config = array(
        'protocol'=>'smtp',
        'smtp_host'=>'smtp.qq.com',
        'smtp_user'=>'service@zgwjjf.com',
        'smtp_pass'=>'wjjf208',
        'smtp_port'=>'25',
        'smtp_timeout'=>'5',
        'newline'=>'\r\n',
        'crlf'=>'\r\n',
        'mailtype'=>'html',
    );

	/**
	 *
	 */
    public function __construct(){
        parent::__construct();
    }

	/**
	 * 发送验证邮件
	 * @access public
	 * @param int    $uid
	 * @param  string  $email 邮件地址
	 * @param string $content 内容 （为空则发送默认 验证内容）
	 * @param string $validate_url 验证邮件的地址 （邮件内容中用到）
	 * @param string $subject
	 *
	 * @return array
	 */
    public function send_email($uid=0,$email = '',$content='',$validate_url='',$subject=''){
        $data = array(
	        'name'   =>'发送邮件',
	        'status' =>'10001',
	        'msg'    =>'服务器繁忙请稍后重试!',
	        'sign'   =>'',
	        'data'   =>array()
        );
        $temp  = array();
	    //验证邮箱格式
	    if($uid <= 0){
		    $data['msg'] = '用户uid不能为空!';
		    return $data;
	    }
	    //验证邮箱格式
	    if( ! $this->is_email($email)){
		    $data['msg'] = '邮箱地址格式不正确!';
		    return $data;
	    }
	    //生成验证码
	    $temp['code']    = $this->_get_random($email, 6, FALSE);

	    //验证邮箱内容 获取默认内容
	    if($content == '' && $validate_url == ''){
		    $data['msg'] = '邮件验证地址不能为空!';
		    return $data;
	    }
	    if($content == ''){
		    $content=$this->_get_email_content($email,$temp['code'],$validate_url);
	    }

	    if($subject == ''){
		    $subject = '欢迎你注册网加金服';
	    }

        $temp['send']  = array(
            'from'    => $this->_config['smtp_user'],
            'name'    => '聚雪球',
            'to'      => $email,
            'subject' => $subject,
            'message' => $content
        );

        $query = $this->send_new_mail($temp['send']);

        if( ! empty($query)){
            $this->_add_send_log($email, $temp['code'], $content, 5,$uid);
	        $data['status'] = '10000';
	        $data['msg'] = '邮件发送成功!';
        }

        unset($temp);
        return $data;
    }

    /**
     * 发送邮件
     *
     * 抄送地址可以是数组或者以逗号分隔的字符串,附件是一维数组。
     *
     * @access public
     * @param  array   $email    邮件内容
     * @return boolean
     */

    public function send_new_mail($email = array()){
        $query = FALSE;

        if( ! empty($email)){
            $temp = array();
            $this->load->library('api/Email');

            $temp['from']    = ( ! empty($email['from'])) ? $email['from'] : '';
            $temp['name']    = ( ! empty($email['name'])) ? $email['name'] : '';
            $temp['to']      = ( ! empty($email['to'])) ? $email['to'] : '';
            $temp['cc']      = ( ! empty($email['cc'])) ? $email['cc'] : '';
            $temp['subject'] = ( ! empty($email['subject'])) ? $email['subject'] : '';
            $temp['message'] = ( ! empty($email['message'])) ? $email['message'] : '';
            $temp['attach']  = ( ! empty($email['attach'])) ? $email['attach'] : '';

            $this->email->from($temp['from'], $temp['name']);
            $this->email->to($temp['to']);

            if( ! empty($temp['cc'])){
                $this->email->cc($temp['cc']);
            }
            $this->email->subject($temp['subject']);
            $this->email->message($temp['message']);

            if( ! empty($temp['attachment'])){
                foreach($temp['attachment'] as $v){
                    if(is_file($v)){
                        $this->email->attach($v);
                    }
                }
            }

            $query = $this->email->send();
            unset($temp);
        }

        return $query;
    }

    /**
     * 检查验证码是否有效
     *
     * @access public
     * @param  string  $target  目标地址
     * @param  string  $code    验证码
     * @param  integer $minute  分钟
     * @return integer
     */
    public function validation_email($target = '', $code = '', $minute = 0){
	    $data = array(
		    'name'   =>'验证邮件',
		    'status' =>'10001',
		    'msg'    =>'服务器繁忙请稍后重试!',
		    'sign'   =>'',
		    'data'   =>array()
	    );
        $temp = array();

	    if($target == ''){
		    $data['msg'] = '邮箱地址不能为空!';
		    return $data;
	    }
	    if($code == ''){
		    $data['msg'] = '验证码不能为空!';
		    return $data;
	    }

        $temp['where'] = array(
            'select' => 'uid',
            'where'  => array(
                'code'         => $code,
                'send_time >=' => $this->_timestamp($minute),
                'target'       => $target,
                'type'         => 5
            )
        );
        $temp['result'] = $this->c->get_one(self::authcode, $temp['where']);

	    if($temp['result']){
		    $data['status'] = '10000';
		    $data['msg'] = '邮箱验证成功!';
	    }else{
		    $data['msg'] = '邮箱验证失败!';
	    }

        unset($temp);
        return $data;
    }

    /**
     * 添加手机发送日志
     *
     * @access private
     * @param  string  $target  目标地址
     * @param  string  $code    授权码
     * @param  string  $content 发送内容
     * @param  integer $type    记录类型
     * @param  integer $uid     会员ID
     * @return boolean
     */
    private function _add_send_log($target = '', $code = '', $content = '', $type = 1, $uid = 0){
        $query = FALSE;
        $logs  = array();

        if( ! empty($target) && ! empty($code) && ! empty($content)){
            $logs = array(
                'code'        => $code,
                'ip_address' => $this->input->ip_address(),
                'send_time'  => time(),
                'uid'         => $uid,
                'type'        => $type,
                'target'      => $target,
                'content'     => $content
            );
            $query = $this->c->insert(self::authcode, $logs);
        }

        unset($logs);
        return $query;
    }

    /**
     * 获取授权码
     *
     * @access private
     * @param  string   $target  目标地址
     * @param  integer  $length  授权码位数
     * @param  boolean  $flag    纯数字
     * @return string
     */
    private function _get_random($target = '', $length = 6, $flag = TRUE){
        $code = '';
        $temp = array();

        if( ! empty($length)){
            $code = random($length, $flag);

            $temp['where'] = array(
                'where' => array(
                    'code'         => $code,
                    'send_time >=' => $this->_timestamp(),
                    'target'       => $target
                )
            );

            $temp['count'] = $this->c->count(self::authcode, $temp['where']);

            if($temp['count'] > 0){
                $code = $this->_get_random($target, $length, $flag);
            }
        }

        unset($temp);
        return $code;
    }

    /**
     * 获取当前时间倒回的分钟数的时间戳 不传值默认60分计算
     * @access private
     * @param  integer  $minute 分钟
     * @return integer
     */
    private function _timestamp($minute = 60){
        return time() - ($minute * 60);
    }

    private function _get_email_content($email='', $code='', $url=''){
        $content= '';
        if($email && $code && $url){
            $content = '<p>尊敬的客户,您好！</p>
                    <p>您于'.date('Y-m-d H:i:s').'申请了邮箱认证。请点击以下链接完成邮箱验证：</p>
                    <p><a href="'.$url.'?from=' . $email . '&code=' . $code.'"title="立即验证">'.$url.'?from=' . $email . '&code=' . $code.'</a></p>
                    <p>如果无法点击此链接，可将它复制到浏览器地址栏后访问。</p>
                    <p>温馨提示：</p>
                    <p>1、为了保障您的账号安全，请在48小时内完成认证，本链接将在您完成认证后失效。</p>
                    <p>2、认证完成后请尽快删除此邮件，以防账户被盗。</p>
                    <p>聚雪球</p>
                    <p>'.date('Y-m-d H:i:s').'</p>
            ';
        }
        return $content;
    }

	/**
	 * 验证邮箱格式
	 * @param string $email
	 * @return bool
	 */
	public function is_email($email=''){
		return ( ! empty($email) && preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $email)) ? TRUE : FALSE;
	}
}