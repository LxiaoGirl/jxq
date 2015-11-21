<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Passport_model extends CI_Model
{
    const admin   = 'admin'; // 后台用户
    const message = 'message'; // 系统消息
    const role    = 'admin_role'; // 角色管理
    const log     = 'admin_log'; // 后台日志

    /**
     * 添加用户日志
     *
     * @access public
     * @param  string   $module     模块名称
     * @param  string   $content    日志内容
     * @param  integer  $admin_id   用户ID
     * @param  string   $admin_name 用户姓名
     * @return boolean
     */

    public function add_user_log($module = '', $content = '', $admin_id = 0, $admin_name = '')
    {
        $query = FALSE;
        $logs  = array();

        if( ! empty($module) && ! empty($content))
        {
            $logs = array(
						'admin_id'   => ( ! empty($admin_id)) ? $admin_id : $this->session->userdata('admin_id'),
						'admin_name' => ( ! empty($admin_name)) ? $admin_name : $this->session->userdata('admin_name'),
						'module'     => $module,
						'content'    => $content,
						'dateline'   => time()
                    );

            if( ! empty($logs['admin_id']) && ! empty($logs['admin_name']))
            {
                $query = $this->c->insert(self::log, $logs);
            }
        }

        unset($logs);
        return $query;
    }

    /**
     * 发送信息
     *
     * @access public
     * @param  integer $uid     会员ID
     * @param  string  $subject 主题
     * @param  string  $content 消息内容
     * @return boolean
     */

    public function send_message($uid = 0, $subject = '', $content = '')
    {
        $query = FALSE;
        $data  = array();

        if( ! empty($uid) && ! empty($subject) && ! empty($content))
        {
            $data = array(
                        'uid'       => $uid,
                        'subject'   => $subject,
                        'content'   => $content,
                        'send_time' => time()
                    );

            $query = $this->c->insert(self::message, $data);
        }

        unset($data);
        return $query;
    }

    /**
     * 用户登录
     *
     * @access public
     * @return boolean
     */

    public function sign_in()
    {
        $query = FALSE;
        $temp  = array();

        $temp['mobile']   = $this->input->post('mobile', TRUE);
        $temp['password'] = $this->input->post('password', TRUE);

    	if( ! empty($temp['mobile']) && ! empty( $temp['password']))
    	{
			$temp['where'] = array(
                                'select' => join_field('*', self::admin).','.join_field('authorized', self::role),
                                'join'   => array(
                                                'table' => self::role,
                                                'where' => join_field('role_id', self::admin).' = '.join_field('role_id', self::role)
                                            ),
                                'where'  => array(
                                                join_field('mobile', self::admin) => $temp['mobile'],
                                                join_field('status', self::admin) => 1
                                            )
                            );

			$temp['data']  = $this->c->get_row(self::admin, $temp['where']);

    		if( ! empty($temp['data']))
    		{
                $temp['data']['authorized'] = json_decode($temp['data']['authorized'], TRUE);

    			$temp['password'] = $this->c->password($temp['password'], $temp['data']['hash']);

    			if($temp['data']['password'] == $temp['password'])
    			{
    				$this->session->set_userdata($temp['data']);

    				$this->_set_login_info();
    				$this->add_user_log('sign_in', '用户登录');

    				$query = TRUE;
    			}
    		}
    	}

    	unset($temp);
    	return $query;
    }

    /**
     * 用户注册
     *
     * @access public
     * @return boolean
     */

    public function sign_up()
    {
        $query = FALSE;
        $data  = array();

        $data = array(
                    'admin_name'  => $this->input->post('admin_name', TRUE),
                    'mobile'      => $this->input->post('mobile', TRUE),
                    'password'    => $this->input->post('password', TRUE),
                    'hash'        => random(6, FALSE),
                    'reg_date'    => time(),
                    'reg_ip'      => $this->input->ip_address()
                );

        $data['password'] = $this->c->password($data['password'], $data['hash']);

        $query = $this->c->insert(self::admin, $data);

        if( ! empty($query))
        {
            $this->add_user_log('sign_up', '用户注册');
        }

        unset($data);
        return $query;
    }

    /**
     * 更新登录信息
     *
     * @access public
     * @return boolean
     */

    private function _set_login_info()
    {
		$query = FALSE;
		$temp  = array();

		$temp['data'] = array(
							'last_date' => time(),
							'last_ip'   => $this->input->ip_address()
						);

 		$temp['where'] = array('where' => array('admin_id' => $this->session->userdata('admin_id')));

 		$query = $this->c->update(self::admin, $temp['where'], $temp['data']);

    	unset($temp);
    	return $query;
    }
}