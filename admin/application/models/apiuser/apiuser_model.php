<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * api用户管理
 */

class Apiuser_model extends CI_Model
{
    const API_USER = 'api_user'; //api用户表

    /**
     * 创建记录
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $temp  = array();

        $temp['data'] = array(
            'appid'          => $this->_get_appid(),
            'appsecret'     => $this->_get_appsecret(),
            'uname'     => $this->input->post('uname', TRUE),
            'remarks'   => $this->input->post('remarks', TRUE),
            'operator'     => $this->session->userdata('admin_name'),
            'authentication'  => implode('|',$this->input->post('authentication')),
            'add_time'    => time(),
            'status'       => (int)$this->input->post('status')
        );

        $query = $this->c->insert(self::API_USER, $temp['data']);

        unset($temp);
        return $query;
    }

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['data'] = array(
            'uname'     => $this->input->post('uname', TRUE),
            'remarks'   => $this->input->post('remarks', TRUE),
            'operator'     => $this->session->userdata('admin_name'),
            'authentication'  =>  implode('|',$this->input->post('authentication')),
            'add_time'    => time(),
            'status'       => (int)$this->input->post('status')
        );

        $temp['uid']=(int)$this->input->post('uid');
        if($temp['uid'] > 0){
            $temp['where']=array(
                'where'=>array('uid'=>$temp['uid'])
            );
            $query = $this->c->update(self::API_USER, $temp['where'], $temp['data']);
        }

        unset($temp);
        return $query;
    }

    private function _get_appid(){
        return uniqid('wjjf');
    }
    private function _get_appsecret(){
        return md5(uniqid().rand(9999,9999999));
    }

    /**
     * 记录列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = array();

        $data = $this->c->show_page(self::API_USER);

        return $data;
    }

    /**
     * 获取用户信息
     *
     * @access public
     * @return array
     */

    public function get_apiuser_info()
    {
        $data = $temp = array();

        $temp['uid'] = (int)$this->input->get('uid');

        if($temp['uid'] > 0)
        {
            $temp['where'] = array('where' => array('uid' => $temp['uid']));
            $data = $this->c->get_row(self::API_USER, $temp['where']);
        }

        if( ! empty($data) && ! empty($data['authentication'])){
            $data['authentication']=explode('|',$data['authentication']);
        }

        unset($temp);
        return $data;
    }

    public function set_user_status()
    {
        $query = FALSE;
        $temp  = array();

        $temp['uid'] = (int)$this->input->get('uid');
        $temp['status']   = (int)$this->input->get('status');

        if( ! empty($temp['uid']))
        {
            $temp['data']  = array('status' => $temp['status']);
            $temp['where'] = array('where' => array('uid' => $temp['uid']));

            $query = $this->c->update(self::API_USER, $temp['where'], $temp['data']);
        }

        unset($temp);
        return $query;
    }
}