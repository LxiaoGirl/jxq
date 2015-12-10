<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * api 接口的model
 * Class Api_model
 */

class Api_model extends CI_Model
{
    const user     = 'user'; // 会员
    const log      = 'user_log'; // 会员日志
    const flow     = 'cash_flow'; // 资金记录
    const borrow      = 'borrow'; // 借款
    const payment     = 'borrow_payment'; // 支付记录
    const authcode = 'authcode'; // 验证授权

    public function __construct()
    {
        parent::__construct();
        $this->load->model('web_1/send_model', 'send');
        $this->load->library('form_validation');
        $this->lang->load('form');
    }

    /**
     * 用户登录
     *
     * @access public
     * @return array
     */

    public function sign_in(){
        $data = $temp = array();

        $data = array('status' => 1, 'msg' => '你提交的数据有误,请重试！', 'data' => array());

        if($this->form_validation->run('login/index') == TRUE){
            $temp['mobile']   = $this->input->post('mobile', TRUE);
            $temp['password'] = $this->input->post('password', TRUE);

            $temp['user'] = $this->_get_user_info($temp['mobile']);

            if( ! empty($temp['user'])){
                $temp['minute'] = ($temp['user']['lock_time'] > time()) ? round(($temp['user']['lock_time'] - time()) / 60) : 0;

                if($temp['minute'] == 0){
                    $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);

                    if($temp['user']['password'] == $temp['password']){
                        $this->_set_login_info($temp['user']['uid']);
                        $this->_add_user_log('login', 'app-会员登录', $temp['user']['uid'], $temp['user']['user_name']);
                        $data = array(
                            'status' => 0,
                            'msg'  => '欢迎您的光临！',
                            'data'  => array(
                                'uid'=>urlencode(authcode($temp['user']['uid'])),
                                'user_name'=>$temp['user']['user_name'],
                                'avatar'=>$temp['user']['avatar']?$temp['user']['avatar']:assets('images/personal/default-faceimage.png'),
                                'mobile'=>$temp['user']['mobile'],
                                'inviter_no'=>$temp['user']['inviter_no']
                            )
                        );
//                        $this->session->set_userdata($temp['user']);
                    }else{
                        $temp['where'] = array('where' => array('mobile' => $temp['mobile']));

                        if($temp['user']['error_num'] == 2){
                            $temp['data'] = array('lock_time' => time() + 600);
                            $this->c->update(self::user, $temp['where'], $temp['data']);
                        }else{
                            $temp['data'] = array('field' => 'error_num', 'value' => '`error_num` + 1');
                            $this->c->set(self::user, $temp['where'], $temp['data']);
                        }

                        $data['code'] = 1;
                        $data['msg']  = '你输入的用户名和密码不匹配！';
                    }
                }else{
                    $data['msg'] = '当前登录账号已经锁定，请在'.$temp['minute'].'分钟后再登录！';
                }
            }else{
                $data['msg']  = '你输入的手机号码还未注册！';
            }
        }else{
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    public function logout(){
        //$this->_add_user_log('logout', 'app-注销登录');
        $this->session->sess_destroy();
    }

    /**
     *忘记密码  mobile authcode new_password
     */
    public function forget_password(){
        $data = $temp = array();

        $temp['mobile'] = $this->input->post('mobile', TRUE);
        $temp['code']   = $this->input->post('authcode', TRUE);
        $temp['password']   = $this->input->post('new_password', TRUE);

        if(empty($temp['mobile'])) return array('status' => 1, 'msg' => '手机号码不能为空！', 'data' => '');
        if(! $this->_is_mobile($temp['mobile'])) return array('status' => 1, 'msg' => '手机号码格式不正确！', 'data' => '');
        if(empty($temp['code'])) return array('status' => 1, 'msg' => '手机验证码不能为空！', 'data' => '');
        if(empty($temp['password'])) return array('status' => 1, 'msg' => '新密码不能为空！', 'data' => '');
        if(strlen($temp['password']) < 6) return array('status' => 1, 'msg' => '新密码不能少于6位字符！', 'data' => '');

        $temp['where'] = array(
            'select' => 'uid',
            'where'  => array(
                'send_time >=' => time() - 300,
                'type'         => 2,
                'code'         => $temp['code'],
                'target'       => $temp['mobile']
            )
        );

        $temp['count'] = $this->c->count(self::authcode, $temp['where']);

        if( ! empty($temp['count'])){
            $temp['where'] = array(
                'select' => 'uid,user_name,password,hash',
                'where'  => array('mobile' => $temp['mobile'])
            );

            $temp['user']  = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($temp['user'])){
                $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);

                if($temp['password'] != $temp['user']['password']){
                    $temp['where'] = array('where' => array('uid' => $temp['user']['uid']));
                    $temp['data']  = array('password' => $temp['password']);

                    $temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

                    if( ! empty($temp['query'])){
                        $data = array(
                            'status' => 0,
                            'msg'  => '你的密码修改成功,记得使用新密码登录!',
                            'data'  => ''
                        );
                    }else{
                        $data = array(
                            'status' => 1,
                            'msg'  => '修改失败!',
                            'data'  => ''
                        );
                    }
                }else{
                    $data = array(
                        'status' => 1,
                        'msg'  => '你可以直接使用当前输入的密码登录，勿需更新!',
                        'data'  => array()
                    );
                }
            }else{
                $data = array(
                    'status' => 1,
                    'msg'  => '你提交的数据有误,请重试！',
                    'data'  => array()
                );
            }
        }else{
            $data = array('status' => 1, 'msg' => '你输入的验证码不正确！', 'data' => '');
        }

        return $data;
    }

    /**
     * 获取用户余额
     *
     * @access public
     * @param  integer  $uid 用户ID
     * @return float
     */
    public function get_balance_amount($uid = 0){
        $balance = 0;
        $temp    = array();

        if( ! empty($uid)){
            $temp['where'] = array(
                'select'   => 'balance',
                'where'    => array('uid' => $uid),
                'order_by' => 'id desc'
            );
            $balance = (float)$this->c->get_one(self::flow, $temp['where']);
        }

        unset($temp);
        return $balance;
    }

    /**
     * 获取累计收益
     * @param int $uid
     * @return array
     */

    public function get_income_amount($uid = 0){
        $data = 0;
        $temp = array();

        if( ! empty($uid)){
            $borrow = $this->c->get_all("borrow_payment",array(
                'select'   =>'borrow_no,SUM(amount) as amounts',
                'where'    =>array('uid'=>$uid,'type'=>1),
                'group_by' =>'borrow_no'
                ));

            if( ! empty($borrow)){
                foreach ($borrow as $key => $value) {
                    $interest = $this->c->get_one("borrow_payment",array('select'=>'SUM(amount)','where'=>array('uid'=>$uid,'type'=>3,'borrow_no'=>$value['borrow_no'])));
                    if($interest > $value['amounts']){
                        $rs['receive_principal'] += $value['amounts'];
                        $rs['receive_interest']  += $interest - $value['amounts'];
                    }else{
                        $rs['receive_interest']  += $interest;
                    }
                }
            }
            $rs['receive_principal'] = round($rs['receive_principal'],2);
            $rs['receive_interest']  = round($rs['receive_interest'],2);

            // $this->db->select("sum(b.amount-a.amount) as receive_interest");
            // $this->db->from("(select * from cdb_borrow_payment where type=1 ) as a");
            // $this->db->join("(select amount,payment_no from cdb_borrow_payment where type=3 ) as b",'a.payment_no=b.payment_no','inner');
            // $this->db ->where(array('a.uid'=>$uid));
            // $this->db ->group_by("a.uid");
            // $query = $this->db ->get();

            // if($query->num_rows() > 0){
            //     $rs = $query->row_array();
                $data = $rs['receive_interest'];
            // }
            // $query->free_result();
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取用户信息
     *
     * @access public
     * @param  string $mobile 手机号码
     * @return integer
     */

    private function _get_user_info($mobile = ''){
        $data = $temp = array();

        if( ! empty($mobile)){
            $temp['where'] = array('where' => array('mobile' => $mobile));
            $data = $this->c->get_row(self::user, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 更新登录信息
     *
     * @access public
     * @return boolean
     */

    private function _set_login_info($uid){
        $query = FALSE;
        $temp  = array();

        $temp['data'] = array(
            'error_num' => 0,
            'lock_time' => 0,
            'last_date' => time(),
            'last_ip'   => $this->input->ip_address()
        );

        $temp['where'] = array('where' => array('uid' => $uid));

        $query = $this->c->update(self::user, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 添加会员日志
     *
     * @access private
     * @param  string   $module    模块名称
     * @param  string   $content   日志内容
     * @param  integer  $uid       会员ID
     * @param  string   $user_name 会员姓名
     * @return boolean
     */

    private function _add_user_log($module = '', $content = '', $uid = 0, $user_name = '')
    {
        $query = FALSE;
        $logs  = array();

        if( ! empty($module) && ! empty($content)){
            $logs = array(
                'uid'       => $uid,
                'user_name' => $user_name,
                'module'    => $module,
                'content'   => $content,
                'dateline'  => time()
            );

            if( ! empty($logs['uid']) && ! empty($logs['user_name'])){
                $query = $this->c->insert(self::log, $logs);
            }
        }

        unset($logs);
        return $query;
    }

    /**
     * 验证用户手机号码
     *
     * @access private
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    private function _is_mobile($mobile = ''){
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }
}