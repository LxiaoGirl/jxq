<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class App_model
 */
class App_model extends CI_Model{
    const dir        = 'apps/'; // app目录
    //数据表常量
    const user       = 'user'; // 会员
    const borrow     = 'borrow'; //借款
    const card       = 'user_card'; //银行卡
    const apply      = 'borrow_apply'; //借款申请
    const bank       = 'bank'; //银行
    const payment   = 'borrow_payment'; //投资
    const cate       = 'product_category';//分类
    const flow       = 'cash_flow';//资金表
    const log        = 'user_log'; // 会员日志
    const tranfer   = 'user_transaction'; //提现表
    const recharge   = 'user_recharge'; //提现表
	const redbag = 'cdb_redbag';   //活动表
	const payment_jbb = 'borrow_payment_jbb';   //聚保宝  

    public function __construct(){
        parent::__construct();
        $this->load->model('web_1/send_model','send');
        $this->load->library('form_validation');
        $this->lang->load('form');
    }
/*******************************************注册**********************************************/
    /**
     * 注册
     * @return array
     */
    public function register(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('login/register') == TRUE){
//            $temp['captcha']     = $this->session->userdata('captcha');
//            $temp['code']        = $this->input->post('captcha', TRUE);
            $this->is_registered();
            $temp['mobile']   = $this->input->post('mobile', TRUE);
            $temp['password'] = $this->input->post('password', TRUE);
            $temp['authcode'] = $this->input->post('authcode', TRUE);
            $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 1, 5);

            if( ! empty($temp['is_check'])){
                $temp['hash']     = random(6, FALSE);
                $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                $temp['data'] = array(
                    'user_name'   => $this->input->post('mobile', TRUE),
                    'mobile'      => $this->input->post('mobile', TRUE),
                    'password'    => $temp['password'],
                    'security'    => '',
                    'hash'        => $temp['hash'],
                    'rate'        => $this->config->item('min_rate'), // 最小提成比例
                    'inviter'     => 0, // 会员邀请人
                    'reg_date'    => time(),
                    'reg_ip'      => $this->input->ip_address(),
                    'last_date'   => 0,
                    'last_ip'     => '',
                );

                $query = $this->c->insert(self::user, $temp['data']);

                if( ! empty($query)){
                    $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                    $temp['data']  = $this->c->get_row(self::user, $temp['where']);

                    if( ! empty($temp['data'])){
                        $this->session->set_userdata($temp['data']);
                        $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你,你的账号已经注册成功！',
                            'url'  => site_url(self::dir.'home/register_success')
                        );
                    }
                }
            }else{
                $data['msg'] = '你输入的手机验证码不正确或者已过期！';
            }
        }else{
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    /**
     * 验证手机号码是否注册
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return object
     */
    public function is_registered()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '您输入的号码已注册!');

        $temp['mobile'] = $this->input->post('mobile', TRUE);

        if($this->is_mobile( $temp['mobile'] )){
            $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
            $temp['count'] = $this->c->count(self::user, $temp['where']);

            if($temp['count'] == 0){
                $data = array('code' => 0, 'msg' => '您输入的号码可以注册!');
            }
        }else{
            $data['msg'] = '您输入的号码格式不正确';
        }

        unset($temp);
        if($data['code'] == 1)exit(json_encode($data));
    }

    /**
     * 验证用户手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    public function is_mobile($mobile = ''){
        return (preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }
/*******************************************注册**********************************************/


/*******************************************主页 项目列表**********************************************/
    /**
     * 查询项目列表
     * @return array
     */
    public function get_project_list(){
        $data = $temp = array();

        $temp['type']  = (int)$this->input->get('t');
        $temp['category']  = isset($_GET['category'])?(int)$this->input->get('category'):0;
        $temp['status']  = isset($_GET['s'])?(int)$this->input->get('s'):0;
        $temp['months']  = isset($_GET['m'])?$this->input->get('m'):0;
        $temp['rate']  = isset($_GET['r'])?$this->input->get('r'):0;


        $temp['sort']  = $this->input->get('sort', TRUE);
        $temp['sort']  = ( ! empty($temp['sort']) && in_array(strtolower($temp['sort']), array('months', 'rate', 'amount'))) ? strtolower($temp['sort']) : 'id';

        $temp['order'] = $this->input->get('order', TRUE);
        $temp['order'] = ( ! empty($temp['order']) && in_array(strtolower($temp['order']), array('asc', 'desc'))) ? strtolower($temp['order']) : 'desc';

        $temp['where'] = array(
            'select'   => join_field('borrow_no,subject,type,uid,amount,months,mode,rate,receive,lowest,due_date,last_investor,last_amount,last_time,add_time,buy_time,status',self::borrow).','.join_field('category',self::cate),
            'where'    => array(join_field('show_time',self::borrow).' <=' => time(),join_field('status',self::borrow).' > ' => 1),
            'order_by' => join_field('sort_order',self::borrow).' desc,'.join_field('productcategory',self::borrow).' asc,'.join_field($temp['sort'],self::borrow).' '.$temp['order'],
            'join'=>array(
                'table'=>self::cate,
                'where'=>join_field('cat_id',self::cate).'='.join_field('productcategory',self::borrow)
            )
        );

        if( ! empty($temp['type'])){
            $string="/^[0-9]/";
            if(preg_match($string,$temp['type'])){
                $temp['where']['where'][join_field('type',self::borrow)] = $temp['type'];
            }
        }
        if( ! empty($temp['category'])){
            $string="/^[0-9]/";
            if(preg_match($string,$temp['category'])){
                $temp['where']['where'][join_field('productcategory',self::borrow)] = $temp['category'];
            }
        }

        if( ! empty($temp['status'])){
            $string="/^[0-9]/";
            if(preg_match($string,$temp['status'])){
                $temp['where']['where'][join_field('status',self::borrow)] = $temp['status'];
                unset($temp['where']['where']['status > ']);
            }
        }

        if( ! empty($temp['months'])){
            $string="/\-/";
            if(preg_match($string,$temp['months'])){
                $temp['months_arr']=explode('-',$temp['months']);
                $string="/^[0-9]/";
                //if(preg_match($string,$temp['months_arr'][0])||preg_match($string,$temp['months_arr'][1])){
                //}else{
                $temp['where']['where'][join_field('months',self::borrow).' >=']=$temp['months_arr'][0];
                $temp['where']['where'][join_field('months',self::borrow).' <=']=$temp['months_arr'][1];
                //}
            }
        }

        if( ! empty($temp['rate'])){
            $string="/\-/";
            if(preg_match($string,$temp['rate']))
            {
                $temp['rate_arr']=explode('-',$temp['rate']);
                $string="/^[0-9]/";
                //if(preg_match($string,$temp['rate_arr'][0])||preg_match($string,$temp['rate_arr'][1])){
                //}else{
                $temp['where']['where']['rate >=']=$temp['rate_arr'][0];
                $temp['where']['where']['rate <=']=$temp['rate_arr'][1];
                //}
            }
        }

        $data = $this->c->show_page(self::borrow, $temp['where']);

        if( ! empty($data)){
            $temp['uid'] = array();

            foreach($data['data'] as $k => $v){
                if($v['status'] == 5 || ($v['status'] == 2 && $v['due_date'] < time() && $v['amount'] != $v['receive'])){  //过滤 过期 但未满标 流标 的数据   2015-05-20
                    unset($data['data'][$k]);
                }else{
                    $data['data'][$k]['receive_rate'] = $this->_get_project_receive_rate($v['amount'],$v['receive']);

                    if( ! empty($v['last_investor'])){
                        $temp['uid'][] = $v['last_investor'];
                    }
                }
            }

            $data['type']  = $temp['type'];
            $data['category']  = $temp['category'];
            $data['sort']  = $temp['sort'];
            $data['order'] = ($temp['order'] == 'asc') ? 'desc' : 'asc';
            $data['status']  = $temp['status'];
            $data['months']  = $temp['months'];
            $data['rate']  = $temp['rate'];
        }

        unset($temp);
        return $data;
    }

    /**
     * 计算融资率
     * @param int $amount 总金额
     * @param int $receive 已收金额
     * @return float|int
     */
    protected function _get_project_receive_rate($amount=0,$receive=0){
        $receive_rate = 0;

        if($receive){
            if($receive / $amount * 100 >0 && $receive / $amount * 100<1){
                $receive_rate = 1;
            }else if(($receive / $amount * 100)>99 && ($receive / $amount * 100)<100){
                $receive_rate = 99;
            }else{
                $receive_rate=round($receive / $amount * 100);
            }
        }else{
            $receive_rate = 0;
        }

        return $receive_rate;

    }
/*******************************************主页 项目列表**********************************************/


/*******************************************设置相关**********************************************/
    /**
     * 修改密码
     * @return array
     */
    public function password(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');
        $temp['uid']   =  $this->session->userdata('uid');

        if( ! empty($temp['uid'])){
            $temp['where'] = array(
                'select' => 'user_name,password,hash,mobile',
                'where'  => array('uid' => $temp['uid'])
            );

            $temp['user']  = $this->c->get_row(self::user, $temp['where']);

            if( ! empty($temp['user'])){
                //验证 今天是否已修改过密码
                $temp['password_today'] = $this->c->count('user_log',array('where'=>array('uid'=>$temp['uid'],'module'=>'password','dateline >='=>strtotime(date('Y-m-d',time()).' 00:00:00'),'dateline <='=>time())));
                if($temp['password_today'] > 0){
                    $data['msg'] = '你今天已修改过一次密码了暂不能再修改！';
                    return $data;
                }

                $temp['password'] = $this->input->post('password', TRUE);
                $temp['new_password'] = $this->input->post('new_password', TRUE);
                $temp['authcode'] = $this->input->post('authcode', TRUE);
                $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);

                if($temp['password'] == $temp['user']['password']){ //比对原始密码
                    $temp['is_check'] = $this->send->validation($temp['user']['mobile'], $temp['authcode'], 5, 5);
                    if( ! empty($temp['is_check'])){
                        if(strlen($temp['new_password']) < 6){
                            $data['msg'] = '请输入6位及以上新密码';
                        }else{
                            $temp['new_password'] = $this->c->password($temp['new_password'], $temp['user']['hash']);
                            if($temp['new_password'] == $temp['user']['password']){
                                $data['msg'] = '你可以直接使用当前输入的密码登录，勿需更新!';
                            }else{
                                $temp['where'] = array('where' => array('uid' => $temp['uid']));
                                $temp['data']  = array('password' => $temp['new_password']);

                                $temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

                                if( ! empty($temp['query'])){
                                    $data = array(
                                        'code' => 0,
                                        'msg'  => '你的密码修改成功,记得使用新密码登录!',
                                        'url'  => ''
                                    );
                                    $this->user->add_user_log('password', '修改登陆密码');
                                }
                            }
                        }
                    }else{
                        $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                    }
                }else{
                    $data['msg'] = '原密码错误';
                }
            }
        }else{
            $data['msg'] = '非法访问 请先登陆';
            $data['url'] = site_url(self::dir.'home/index');
        }

        unset($temp);
        return $data;
    }

    /**
     * 资金密码
     *
     * @access public
     * @return array
     */
    public function security(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('security/index') == TRUE){
            $temp['mobile']   = $this->session->userdata('mobile');
            $temp['hash']     = $this->session->userdata('hash');
            $temp['uid']      = $this->session->userdata('uid');
            $temp['password'] = $this->session->userdata('password');

            $temp['authcode'] = $this->input->post('authcode', TRUE);
            $temp['security'] = $this->input->post('security', TRUE);
            $temp['security'] = $this->c->password($temp['security'], $temp['hash']);

            if($temp['security'] != $temp['password']){
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 3, 5);

                if( ! empty($temp['is_check'])){
                    $temp['data'] = array('security' => $temp['security']);
                    $temp['where'] = array('where' => array('uid' => $temp['uid']));

                    $temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);

                    if( ! empty($temp['query'])){
                        $this->session->set_userdata($temp['data']);
                        $this->user->add_user_log('security', '修改交易密码');

                        $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你交易密码修改成功！',
                            'url'  => ''
                        );
                    }
                }else{
                    $data['msg'] = '你输入的验证码错误或已过期！';
                }
            }else{
                $data['msg'] = '为了您的账户安全,资金密码和登录密码不能相同！';
            }
        }else{
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    /**
     * 解绑手机
     * @return array
     */
    public function phone_unbind(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');
        $temp['authcode'] = $this->input->post('authcode', TRUE);
        $temp['uid']      = $this->session->userdata('uid');
        $temp['mobile']   = $this->session->userdata('mobile');

        if( ! empty($temp['uid'])){
            $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 9, 5);
            if( ! empty($temp['is_check'])){
                $data = array(
                    'code'=>0,
                    'msg'=>'请绑定新手机',
                    'data'=>urlencode(authcode($temp['uid']))
                );
            }else{
                $data['msg'] ='你提交的验证码有误或已过期！';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 重新绑定手机
     * @return array
     */
    public function phone_bind(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');
        $temp['authcode'] = $this->input->post('authcode', TRUE);
        $temp['mobile'] = $this->input->post('mobile', TRUE);
        $temp['token'] = $this->input->post('token', TRUE);
        $temp['uid']      = $this->session->userdata('uid');

        if( ! empty($temp['uid']) && !empty($temp['token'])){
            if($temp['uid'] != authcode($temp['token'],'',true)){
                $data['msg'] ='非法访问';
            }else{
                if( ! $this->is_mobile($temp['mobile'])){
                    $data['msg'] = '请输入正确格式的手机号码！';
                }else{
                    if($temp['mobile'] == $this->session->userdata('mobile')){
                        $data['msg'] = '新手机不能与原绑定手机相同！';
                    }else{
                        $temp['is_bind'] = $this->c->get_row('user',array('where'=>array('mobile'=>$temp['mobile']),'uid !='=>$temp['uid']));

                        if( ! empty($temp['is_bind'])){
                            $data['msg'] = '该手机号码已绑定过了';
                        }else{
                            $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 8, 5);
                            if( ! empty($temp['is_check'])){
                                $temp['data'] = array('mobile' => $temp['mobile']);
                                $temp['where'] = array('where' => array('uid' => $temp['uid']));

                                $temp['query'] = $this->c->update(self::user, $temp['where'], $temp['data']);
                                $data = array(
                                    'code'=>0,
                                    'msg'=>'新手机绑定成功 记得用新手机号码登陆！'
                                );
                                $this->session->set_userdata($temp['data']);
                            }else{
                                $data['msg'] ='你提交的验证码有误或已过期！';
                            }
                        }
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }
/*******************************************设置相关**********************************************/


/*******************************************我要借款**********************************************/
    /**
     * 借款申请
     *
     * @access public
     * @return boolean
     */
    public function apply(){
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if(!$this->_is_login()){
            $data['msg']='请先登录！';
            return $data;
        }
        if(!$this->_is_realname()){
            $data['msg']='请先实名认证！';
            return $data;
        }
        if((int)$this->input->post('type') == 2 && ! $this->_is_enterprise()){ //wsb-2015.5.13 添加企业认证检查
            $data['msg']='公司借款请先通过企业认证审核';
            return $data;
        }

        if($this->form_validation->run('borrow/apply') == TRUE){

            //是否已有申请
            $temp['is_apply'] = $this->c->count(self::apply,array('where'=>array('mobile'=>$this->input->post('mobile', TRUE),'status'=>0)));
            if($temp['is_apply']){
                $data['msg'] = '您有未审批的借款，请等待风控人员与您取得联系。';
            }else{
                $temp['data'] = array(
                    'apply_no'    => $this->c->transaction_no(self::apply, 'apply_no'),
                    'user_name'   => $this->input->post('user_name', TRUE),
                    'mobile'      => $this->input->post('mobile', TRUE),
                    'type'        => (int)$this->input->post('type'),
                    'amount'      => $this->input->post('amount', TRUE),
                    'dateline'    => $this->input->post('dateline', TRUE),
                    'province'    => (int)$this->input->post('province'),
                    'city'        => (int)$this->input->post('city'),
                    'district'    => (int)$this->input->post('district'),
                    'from'        => $this->input->post('from', TRUE),
                    'add_time'    => time(),
                    'update_time' => time(),
                    'p_type'        => (int)$this->input->post('p_type'),//wsb-2015.5.13 新增
                );
                $temp['data']['dateline'] = ( ! empty($temp['data']['dateline'])) ? strtotime($temp['data']['dateline']) : time();
                $temp['authcode'] = $this->input->post('authcode',true);
                $temp['is_check'] = $this->send->validation($temp['data']['mobile'], $temp['authcode'], 10, 5);
                if( ! empty($temp['is_check'])){
                    $query = $this->c->insert(self::apply, $temp['data']);
                    if( ! empty($query)){
                        $data = array(
                            'code' => 0,
                            'msg'  => '你的借款申请已经提交成功,请等待审核!',
                            'url'  => site_url()
                        );
                    }
                }else{
                    $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                }
            }
        }else{
            $data['msg'] = $this->form_validation->error_string('-','-');
        }

        unset($temp);
        return $data;
    }

    /**
     * 验证企业认证
     * @return bool
     */
    private function _is_enterprise(){
        if($this->session->userdata('clientkind') == 0){
            return TRUE;
        }
        return false;
    }

    /**
     * 是否已登陆
     * @return bool
     */
    private function _is_login(){
        if($this->session->userdata('uid') > 0){
            return true;
        }
        return false;
    }

    /**
     * 是否已实名认证
     * @return bool
     */
    private function _is_realname(){
        if($this->session->userdata('clientkind') == 0 || $this->session->userdata('clientkind') == 1){
            return true;
        }
        return false;
    }
/*******************************************我要借款**********************************************/



/*******************************************个人中心**********************************************/
    /**
     * 解绑银行卡
     * @return array
     */
    public function unbind_card(){
        $data = array('code'=>1,'msg'=>'你提交的数据有误,请重试！','url'=>'');
        $temp = array();

        $temp['security'] = $this->input->post('security',true);
        $temp['card_no'] = $this->input->post('card_no',true);
        $temp['security'] = $this->c->password($temp['security'],$this->session->userdata('hash'));
        $temp['authcode'] = $this->input->post('authcode',true);
        $temp['my_security'] = $this->session->userdata('security');
        $temp['mobile'] = $this->session->userdata('mobile');

        if( ! empty($temp['card_no'])){
            if($temp['my_security'] == $temp['security']){
                $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 12, 5);
                if( ! empty($temp['is_check'])){
                    $temp['where'] = array('where'=>array('card_no'=>$temp['card_no']));
                    $temp['no_agree'] = $this->c->get_one(self::card,array('select'=>'remarks','where'=>array('card_no'=>$temp['card_no'])));
                    $temp['query'] = $this->c->delete(self::card,$temp['where']);
                    if( ! empty($temp['query'])){
                        $data['code'] = 0;
                        $data['msg'] ='解绑成功！';

                        if( ! empty($temp['no_agree'])){  //连连支付 解约银行卡
                            $temp['llpay_config'] = array(
                                'notify' => site_url(self::dir.'home/llpay_notify'),
                                'return_url'=> site_url(self::dir.'home/recharge_success')
                            );
                            $this->load->library('llpay',$temp['llpay_config']);
                            $this->llpay->card_unbind($this->session->userdata('uid'),$temp['no_agree']);
                        }
                    }
                }else{
                    $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                }
            }else{
                $data['msg'] = '资金密码不正确！';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 添加银行卡
     *
     * @access public
     * @return array
     */
    public function bind_card()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        $_POST['password'] = '123456';
        $_POST['retype'] = $this->input->post('account');

        if($this->form_validation->run('account/create') == TRUE)
        {
            $temp['authcode'] = $this->input->post('authcode',true);
            if(empty( $temp['authcode'])) exit(json_encode(array('code'=>1,'msg'=>'请输入短信验证码！')));
            $temp['is_check'] = $this->send->validation($this->session->userdata('mobile'), $temp['authcode'], 11, 5);
            if(empty($temp['is_check'])){
                exit(json_encode(array('code'=>1,'msg'=>'你输入短信验证码错误或已过期！')));
            }

            $temp['data'] = array(
                'card_no'   => $this->c->transaction_no(self::card, 'card_no'),
                'uid'       => $this->session->userdata('uid'),
                'real_name' => $this->session->userdata('real_name'),
                'account'   => $this->input->post('account', TRUE),
                'bank_id'   => (int)$this->input->post('bank_id'),
                'bank_name' => '',
                'bankaddr' => $this->input->post('bankaddr', TRUE),
                'province' => $this->input->post('province', TRUE),
                'city' => $this->input->post('bankaddr', TRUE),
                'remarks'   => '',
                'dateline'  => time(),
            );

            $temp['data']['account'] = str_replace(' ', '', $temp['data']['account']);

            if( ! empty($temp['data']['bank_id']))
            {
                $temp['data']['bank_name'] = $this->_get_bank_name($temp['data']['bank_id']);
            }

            $query = $this->c->insert(self::card, $temp['data']);

            if( ! empty($query))
            {
                $data = array(
                    'code' => 0,
                    'msg'  => '恭喜，你的银行卡绑定成功！',
                    'url'  => site_url('user/account')
                );
            }
        }
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取银行名称
     *
     * @access public
     * @param  integer $bank_id 银行ID
     * @return array
     */
    private function _get_bank_name($bank_id = 0)
    {
        $bank_name = '';
        $where     = array();

        if( ! empty($bank_id))
        {
            $where = array(
                'select' => 'bank_name',
                'where'  => array('bank_id' => (int)$bank_id)
            );

            $bank_name = $this->c->get_one(self::bank, $where);
        }

        unset($where);
        return $bank_name;
    }

    /**
     * 查询投资列表信息
     * @return array
     */
    public function get_invest_list($flag=false){
        $data = $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,dateline', self::payment).','.join_field('subject,status,months,mode,receive,due_date,confirm_time', self::borrow).','.join_field('amount', self::borrow).' as amounts,'.join_field('category',self::cate).',SUM('.join_field('amount',self::payment).') as amount',
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1, join_field('status', self::payment) => 1),
                
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'order_by' => array(
                    array('field'=>join_field('status', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
                ),
                'group_by'=>join_field('borrow_no',self::payment)
            );

            $temp['status'] = $this->input->get('status');
            if( ! empty($temp['status'])){
                if(strpos($temp['status'], '-') == 1){
                    $temp['status_array'] = explode('-', $temp['status']);
                    $temp['where']['where_in']  = array('field'=>join_field('status',self::borrow),'value'=>$temp['status_array']);
                }else{
                    $temp['where']['where'][join_field('status',self::borrow)]  = $temp['status'];
                }
            }

            if($flag == TRUE){
                $temp['where']['where_in'] = array('field'=>join_field('status',self::borrow),'value'=>array(4,7));
            }

            $data = $this->c->show_page(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }
/*******************************************个人中心**********************************************/

/*
 * 那么这样的话 上面我说的9个统计：
1.可用余额   ： cash_flow 的最新一条记录的balance
2.总资产       ：cash_flow中  type为（1充值 3冻结 6借款 7利息收益9偿还本金 10会员还款 '）的记录amount总数
3.冻结金额   ：cash_flow中  type为3的记录amount总数
4.提现中的金额：user_transaction中 status 为1的？ 还是cash_flow中type=2的？
5.累计收益：cash_flow 中 type=7（利息收益）的？
6.代收本金：borrow_payment中type=1的金额
7.已收本金：cash_flow 中type=10的？
8.待收利息：根据待收本机 计算  还是有表存储？
9.已收利息：cash_flow 中type=7的？
 *
 *
 * 新：2015-7.30 与寇林沟通后确认
1.可用余额   ： cash_flow 的最新一条记录的balance
2.总资产       ：
3.冻结金额   ：type=3
4.提现中的金额：type=2
5.累计收益：TYPE=8
6.代收本金：type=5
7.已收本金：TYPE=9
8.待收利息：需计算
9.已收利息：TYPE=8
 *
 */

    /**
     * 查询 用户资金信息
     * @param int $type 资金类型 （多个用数组）
     * @param bool $groupby 是否分组查询
     * @return int
     */
    public function get_user_cash($type=1,$groupby=false){
        $amount = 0;
        $temp   = array();
        $temp['uid'] = $this->session->userdata('uid');

        if( ! empty($type) && !empty($temp['uid'])){
            if($groupby && is_array($type)){
                $temp['where'] = array(
                    'select'   => 'SUM(`amount`) AS `amount`, `type`',
                    'where'  => array('uid' => $temp['uid']),
                    'where_in' => array(
                        'field'=>'type',
                        'value'=>$type
                    ),
                    'group_by'=>'type'
                );
                $amount = $this->c->get_all(self::flow, $temp['where']);
            }else{
                $temp['where'] = array(
                    'select' => 'SUM(`amount`)',
                    'where'  => array('uid' => $temp['uid'])
                );
                if(is_array($type)){
                    $temp['where']['where_in'] = array(
                        'field'=>'type',
                        'value'=>$type
                    );
                }else{
                    $temp['where']['where']['type'] = $type;
                }

                $amount = $this->c->get_one(self::flow, $temp['where']);
                if(! $amount)$amount=0;
            }
        }

        unset($temp);
        return $amount;
    }

    public function get_user_interest(){
        $interest = 0;
        $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,dateline', self::payment).','.join_field('subject,status,months,mode,receive', self::borrow).','.join_field('amount', self::borrow).' as amounts,'.join_field('category',self::cate).',SUM('.join_field('amount', self::payment).') as amount',
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
               'where_in'    => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'group_by'=>join_field('borrow_no',self::payment)
            );

//            $temp['status'] = (int)$this->input->get('status');
//            if( ! empty($temp['status'])){
//                $temp['condition']  = array(join_field('status',self::borrow)=>$temp['status']);
//                $temp['where']['where'] = array_merge($temp['where']['where'], $temp['condition']);
//            }

            $temp['invest_list'] = $this->c->get_all(self::payment, $temp['where']);

            if( ! empty($temp['invest_list'])){

                foreach($temp['invest_list'] as $v){

                    $temp['project_interest'] = 0;

                    //查询 是否 已还款完成
                    $repay_interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('borrow_no'=>$v['borrow_no'],'type'=>3,'status'=>1,'uid'=>$temp['uid'])));
                    if($repay_interest && $repay_interest>$v['amount']){
                        $temp['project_interest'] = $repay_interest-$v['amount'];
                    }else{
                        switch($v['mode']){
                            case '1':
                                $temp['project_interest'] = $this->_get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '2':
                                $temp['project_interest'] = $this->_get_debx_all_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '3':
                                $temp['project_interest'] = $this->_get_ycxbx_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                            case '4':
                                $temp['project_interest'] = $this->_get_debj_all_interest($v['amount'],$v['rate'],$v['months']);
                                break;
                        }
                    }
                    if( ! $temp['project_interest'])$temp['project_interest'] = 0;
                    $interest += $temp['project_interest'];
                }
            }
        }

        unset($temp);
        return $interest;
    }


    /**
     * 查询 borrow_payment 表  获得 已收本金 和利息
     * 累计收益  已还本金
     */
    public function get_receive_principal_interest(){
        $rs = array('receive_principal'=>0,'receive_interest'=>0);
        if($this->session->userdata('uid') > 0){
            $borrow = $this->c->get_all(self::payment,array(
                'select'   =>'borrow_no,SUM(amount) as amounts',
                'where'    =>array('uid'=>$this->session->userdata('uid'),'type'=>1),
                'group_by' =>'borrow_no'
                ));

            if( ! empty($borrow)){
                foreach ($borrow as $key => $value) {
                    $interest = $this->c->get_one(self::payment,array('select'=>'SUM(amount)','where'=>array('uid'=>$this->session->userdata('uid'),'type'=>3,'borrow_no'=>$value['borrow_no'])));
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


            // $this->db->select("sum(a.amount)  as receive_principal,sum(b.amount-a.amount) as receive_interest");
            // $this->db->from("(select * from cdb_borrow_payment where type=1 ) as a");
            // $this->db->join("(select amount,payment_no from cdb_borrow_payment where type=3 ) as b",'a.payment_no=b.payment_no','inner');
            // $this->db ->where(array('a.uid'=>$this->session->userdata('uid')));
            // $this->db ->group_by("a.uid");
            // $query = $this->db ->get();

            // if($query->num_rows() > 0){
            //     $rs = $query->row_array();
            // }
            // $query->free_result();
        }

        return $rs;
    }

    /**
     * 投资的冻结金额
     */
    public function get_user_invest_freeze(){
        $data = 0;
        $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1),
                'where_in'    => array('field'=>join_field('status',self::borrow),'value'=>array(2,3)),
                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow))
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 体现的冻结金额
     */
    public function get_user_transfer_freeze(){
        $data = 0;
        $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)',
                'where'    => array('uid' => $temp['uid'], 'status' => 0)
            );

            $data = (float)$this->c->get_one(self::tranfer, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 用户投资总额
     */
    public function get_user_invest_all(){
        $data = 0;
        $temp = array();

        $temp['uid'] = (int)$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $temp['uid'], join_field('type', self::payment) => 1, join_field('status', self::payment) => 1),
                'where_in'    => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow))
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 单个项目的利息计算
     * @param array $array
     * @return float
     */
    public function get_project_interest($array=array()){
        $interest = 0;
        if(isset($array['mode']) && isset($array['amount']) && isset($array['rate']) && isset($array['months'])){
            switch($array['mode']){
                case '1':
                    $interest = $this->_get_ycxbx_interest($array['amount'],$array['rate'],$array['months']);
                    break;
                case '2':
                    $interest = $this->_get_debx_all_interest($array['amount'],$array['rate'],$array['months']);
                    break;
                case '3':
                    $interest = $this->_get_ycxbx_interest($array['amount'],$array['rate'],$array['months']);
                    break;
                case '4':
                    $interest = $this->_get_debj_all_interest($array['amount'],$array['rate'],$array['months']);
                    break;
            }
        }
        return (float)$interest;
    }

    /**
     * 收入合计    充值总额+利息总额
     * @return [type] [description]
     */
    public function get_income_total(){
        $query = 0;
        $temp  = array();

        $temp['uid'] = $this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['my_principal_interest'] = $this->get_receive_principal_interest(); //我的已收本金和利息
            $query =  $temp['my_principal_interest']['receive_interest'];  //已收利息

            $temp['recharge_total'] = $this->c->get_one(self::recharge,array('select'=>'SUM(amount)','where'=>array('uid'=>$temp['uid'],'status'=>1)));
            $query = round($query+$temp['recharge_total'],2);
        }

        unset($temp);
        return $query;
    }

	
	
	    /**
     * 聚保宝投资
     * @param int $uid
     * @return float|int
     */
    public function jbb_all_amount($status = 0){
        $data = 0;
        $temp = array();
		$temp['uid'] = (int)$this->session->userdata('uid');
        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)',
				'where'    => array(
					'uid' => $temp['uid'],
					'status' => $status
				)
            );

            $data = (float)$this->c->get_one(self::payment_jbb, $temp['where']);
        }

        unset($temp);
        return $data;
    }	
	
	
	
    public function get_pay_total(){
        $query = 0;
        $temp  = array();
        $temp['uid'] = $this->session->userdata('uid');

        if($temp['uid'] > 0){
            $temp['where'] = array(
                'select'   => 'sum(amount)+ count(*)*2',
                'where'    => array('uid' => $temp['uid'], 'status' => 1)
            );

            $query = (float)$this->c->get_one(self::tranfer, $temp['where']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 等额本金 还款 所有利息
     * @param $amount
     * @param $rate
     * @param $months
     * @return float
     */
    public function _get_debj_all_interest($amount,$rate,$months){
        return floor((($months+1)*$amount*(($rate/100)/12)/2)*100)/100;
    }

    /**
     * 一次性本息 利息
     */
    public function _get_ycxbx_interest($amount,$rate,$months){

        return floor($amount*(($rate/100)/360)*($months*30)*100)/100;
    }

    /**
     * 等额本息 所有利息
     * @param $amount  float 贷款总额
     * @param $rate    float 年利率
     * @param $months  int 总期数
     * @return float
     */
    public function _get_debx_all_interest($amount,$rate,$months){
        $temp=array();

        $temp['m_rate']=($rate/100)/12;//月利率
        $temp['m_amount']=$amount*$temp['m_rate']*pow((1+$temp['m_rate']),$months)/(pow((1+$temp['m_rate']),$months)-1);//每月还款 金额

        return floor(($months*$temp['m_amount']-$amount)*100)/100;
    }





	    /**
     * yx 9-1
	 * ajax获得单个红包data
     * @return array
     */
    public function ajax_get_redbagdata(){
        $data = $temp = array();

         $temp['id'] = $this->input->get('id');

        $temp['where'] = array(
				'select'   => 'amount,active,source,deadline',
                'where'    => array('id' => $temp['id'])
            );
		$data = $this->c->get_all(self::redbag, $temp['where']);
		unset($temp);
        return $data;
    }



}