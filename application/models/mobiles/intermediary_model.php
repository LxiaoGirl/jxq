<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 居间人相关model
 * Class Intermediary_model
 */
class Intermediary_model extends CI_Model{
    //数据表常量
    const borrow  = 'borrow';           //借款
    const payment = 'borrow_payment';   //投资
    const cate    = 'product_category'; //分类
    const settle  = 'jujian_jiesuan';   //居间人 结算表
    const user    = 'user';             //用户表
    const redbag  = 'redbag';           //红包

    public function __construct(){
        parent::__construct();
        $this->load->model('web_1/send_model', 'send'); //发送短信 验证短信
    }

    /**
     * 居间人 用户
     * $type 是否分页 TRUE分页  false不分页
     * @return array
     */
    public function get_intermediary_user($show_page=TRUE,$uid=0){
        $data = $temp = array();
        $uid = $uid?$uid:$this->session->userdata('uid');

        if($uid){

            if(!$show_page){
                $temp['data'] = $this->c->get_all(self::user,array('select'=>'user_name,uid,last_date','where'=>array('inviter'=>$uid)));
            }else{
                $temp['all_data'] = $this->c->show_page(self::user,array('select'=>'user_name,uid,last_date','where'=>array('inviter'=>$uid)));
                $temp['data'] = $temp['all_data']['data'];
            }

            if($temp['data']){
                $sort_arr = array();//排序用数组

                foreach($temp['data'] as $k=>$v){
                    $v['amount']       = $this->get_user_invest_all($v['uid']);
                    // $v['active_level'] = $this->_get_active_level($v['last_date']);
                    $v['active_level'] = date('Y-m-d',$v['last_date']).'<br/>'.date('H:i:s',$v['last_date']);
                    $v['ralation']     = $this->_get_intermediary_ralation($v['amount']);
                    $temp['data'][$k]          = $v;
                    $sort_arr[]        = $v['amount'];
                }
                //排序
                array_multisort($sort_arr, SORT_DESC, $temp['data']);

                if($show_page){
                    $temp['all_data']['data'] = $temp['data'];
                    $data = $temp['all_data'];
                }else{
                    $data = $temp['data'];
                }
            }
        }

        return $data;
    }

    /**
     * 居间人 用户 投资列表
     * @param int $uid
     * @return array
     */
    public function get_invest_list($uid=0){
        $data = $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => join_field('payment_no,borrow_no,rate,amount as invest_amount,dateline', self::payment).','
                                .join_field('subject as subject_1,status,months,mode,receive', self::borrow).','
                                .join_field('amount', self::borrow).' as all_amounts,'.join_field('category',self::cate),
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
                'join'     => array(
                    array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    array('table' => self::cate, 'where' => join_field('cat_id', self::cate).' = '.join_field('productcategory', self::borrow))
                ),
                'order_by' => array(
                    // array('field'=>join_field('status', self::borrow),'value'=>'asc'),
                    // array('field'=>join_field('productcategory', self::borrow),'value'=>'asc'),
                    array('field'=>join_field('dateline', self::payment),'value'=>'desc')
                )
            );

            $data = $this->c->show_page(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 未结算总金额
     * @return [type] [description]
     */
    public function get_not_settle_amount(){
        $amounts = 0;

        //加载居间人等级配置  获得结算系数
        $this->config->load('lv');
        $rate = $this->config->item('lv'.$this->session->userdata('lv'))?$this->config->item('lv'.$this->session->userdata('lv')):0;

        $temp = array();

        //查询推荐的用户id
        $temp['user'] = $this->c->get_all(self::user,array('where'=>array('inviter'=>$this->session->userdata('uid')),'select'=>'uid'));

        if( ! empty($temp['user'])){

            foreach($temp['user'] as $v){
                $temp['uid_array'][] = $v['uid'];
            }

            if($temp['uid_array']){
                //查询所以推荐用户的投资信息  以项目分组统计总额
                $temp['where'] = array(
                    'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','
                                            .join_field('confirm_time,status,months,mode', self::borrow)
                                            .',SUM('.join_field('amount',self::payment).') as amounts',
                    'where'    => array(join_field('type', self::payment) => 1),
                    'where_in'=>array(
                        array('field'=>join_field('uid',self::payment),'value'=>$temp['uid_array']),
                        array('field'=>join_field('status',self::borrow),'value'=>array(4,7))
                    ),
                    'join'     =>array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    'group_by'=>join_field('borrow_no',self::payment)
                );

                //查询用户的投资的项目列表
                $temp['project'] = $this->c->get_all(self::payment,$temp['where']);

                //计算 已结算部分
                if($temp['project']){

                    foreach($temp['project'] as $k1=>$v1){
                        //查询该项目的结算情况
                        $temp['settle'] = (float)$this->c->get_one(self::settle,
                            array('where'=>array('borrow_no'=>$v1['borrow_no'],'status'=>1,'inviter'=>$this->session->userdata('uid')),'select'=>'SUM(amount)')
                        );

                        $temp['settle_all'] = $this->_settle_calculation($v1['amounts'],$rate,$v1['months']);;

                        if($temp['settle_all'] > $temp['settle']){ //未结算完  或 未结算
                            $amounts += round($temp['settle_all'] - $temp['settle'],2);
                        }
                    }
                }
            }
        }

        unset($temp);
        return $amounts;
    }

    /**
     * 未结算的列表
     * @return array
     */
    public function get_not_settle_list(){
        //加载居间人等级配置  获得结算系数
        $this->config->load('lv');
        $rate = $this->config->item('lv'.$this->session->userdata('lv'))?$this->config->item('lv'.$this->session->userdata('lv')):0;

        $data = array('data'=>array(),'links'=>'');
        $temp = array();

        //查询推荐的用户id
        $temp['user'] = $this->c->get_all(self::user,array('where'=>array('inviter'=>$this->session->userdata('uid')),'select'=>'uid'));

        if( ! empty($temp['user'])){
            //组成查询用用户uid数组
            foreach($temp['user'] as $v){
                $temp['uid_array'][] = $v['uid'];
            }

            if($temp['uid_array']){
                //查询所以推荐用户的投资信息  以项目分组统计总额
                $temp['where'] = array(
                    'select'   => join_field('payment_no,borrow_no,rate,amount,dateline', self::payment).','
                                            .join_field('confirm_time,status,months,mode', self::borrow)
                                            .',SUM('.join_field('amount',self::payment).') as amounts',
                    'where'    => array(join_field('type', self::payment) => 1),
                    'where_in'=>array(
                        array('field'=>join_field('uid',self::payment),'value'=>$temp['uid_array']),
                        array('field'=>join_field('status',self::borrow),'value'=>array(4,7))
                    ),
                    'join'     =>array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                    'group_by'=>join_field('borrow_no',self::payment)
                );

                //查询用户的投资的项目列表
                $temp['project'] = $this->c->get_all(self::payment,$temp['where']);

                //计算 过滤已结算部分
                if($temp['project']){

                    foreach($temp['project'] as $k1=>$v1){
                        //查询该项目的结算情况
                        $temp['settle'] = (float)$this->c->get_one(self::settle,
                            array('where'=>array('borrow_no'=>$v1['borrow_no'],'status'=>1,'inviter'=>$this->session->userdata('uid')),'select'=>'SUM(amount)')
                        );
                        
                        //计算应该结算的金额
                        $temp['settle_all'] = $this->_settle_calculation($v1['amounts'],$rate,$v1['months']);

                        //统计 未结算完或未结算
                        if($temp['settle_all'] > $temp['settle']){ 
                            $temp['ajax_data']['start_time'] = 0;
                            $temp['ajax_data']['pay_time']   = 0;
                            $temp['ajax_data']['status']     = 0;
                            $temp['ajax_data']['borrow_no']  = $v1['borrow_no'];
                            $temp['ajax_data']['amount']     = round($temp['settle_all'] - $temp['settle'],2);
                            $data['data'][] = $temp['ajax_data'];
                        }
                    }

                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 按项目 查询 未结算部分的用户投资列表
     * @return [type] [description]
     */
    public function get_not_settle_invest_list($borrow_no=''){
        $temp = $data = array();

        if($borrow_no == '') return $data;

        //查询推荐的用户id
        $temp['user'] = $this->c->get_all(self::user,array('where'=>array('inviter'=>$this->session->userdata('uid')),'select'=>'uid'));

        //所有推荐用户的uid数组
        if( ! empty($temp['user'])){
            foreach($temp['user'] as $v){
                $temp['uid_array'][] = $v['uid'];
            }
        }

        if( ! empty($temp['uid_array'])){
            //查询所有推荐用户对该项目的投资信息
            $temp['where'] = array(
                'select'   => join_field('dateline,payment_no', self::payment).','
                                .join_field('subject,months', self::borrow).','
                                .join_field('user_name', self::user)
                                .',SUM('.join_field('amount', self::payment).') as invest_amount',
                'where'    => array(join_field('type', self::payment) => 1),
                'where_in' => array(
                    array('field'=>join_field('uid',self::payment),'value'=>$temp['uid_array']),
                    array('field'=>join_field('borrow_no',self::payment),'value'=>$borrow_no)
                ),
                'join'     => array(
                        array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow)),
                        array('table' => self::user, 'where'   => join_field('uid', self::user).' = '.join_field('uid', self::payment))
                ),
                'group_by' => join_field('uid',self::payment)
            );

            $temp['project'] = $this->c->get_all(self::payment,$temp['where']);

            if( ! empty($temp['project'])){
                //加载居间人等级配置 获得结算系数
                $this->config->load('lv');
                $rate = $this->config->item('lv'.$this->session->userdata('lv'));

                foreach($temp['project'] as $k1=>$v1){

                    $temp['settle'] = (float)$this->c->get_one(self::settle,array('where'=>array('borrow_no'=>$borrow_no,'payment_no'=>$v1['payment_no'],'status'=>1,'inviter'=>$this->session->userdata('uid')),'select'=>'SUM(amount)'));
                    
                    $temp['settle_all'] = $this->_settle_calculation($v1['invest_amount'],$rate,$v1['months']);
                    if($temp['settle_all'] > $temp['settle']){ //未结算完  或 未结算
                        $temp['project'][$k1]['settle_amount'] = round($temp['settle_all'] - $temp['settle'],2);
                    }else{
                        unset($temp['project'][$k1]);
                    }
                }
                $data['data'] = $temp['project'];
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 居间人 结算总额
     * @param int $uid
     * @return int
     */
    public function get_settle_amount($uid=0){
        $data = 0;
        $temp = array();

        $temp['uid'] = $uid?(int)$uid:$this->session->userdata('uid');

        if($temp['uid'] > 0){
            $data = $this->c->get_one(self::settle,array('select'=>'SUM(jujian_amount)','where'=>array('inviter'=>$temp['uid'],'status'=>1)));
        }

        if(!$data) $data = 0;

        unset($temp);
        return $data;
    }

    /**
     * 居间人 结算记录
     * @param int $uid
     * @return array
     */
    public function get_settle_list($uid=0){
        $data = $temp = array();

        $temp['uid'] = $uid?(int)$uid:$this->session->userdata('uid');

        $temp['this_month'] = date('Ym',time());

        if($temp['uid'] > 0){
            $data = $this->c->show_page(self::settle,array(
                'select'  => 'real_month,SUM(jujian_amount) as amount,pay_time,status',
                'where'   =>array(
                    'inviter' =>$temp['uid'],
                    'real_month <='=>$temp['this_month']
                    ),
                'group_by'=>'real_month',
                'order_by'=>'real_month DESC'
                )
            );
        }

        unset($temp);
        return $data;
    }

    /**
     * 结算部分 按结算时间 的用户投资列表
     * @param  integer $start_time [description]
     * @return [type]              [description]
     */
    public function get_settle_invest_list($real_month=0){
        $data = $temp =array();

        if(empty($real_month)) return $data;

        $temp['where'] = array(
            'where'=>array(
                join_field('real_month',self::settle)=>$real_month,
                join_field('inviter',self::settle)=>$this->session->userdata('uid')
            ),
            'select'=>join_field('subject',self::borrow).','
                .join_field('dateline',self::payment).','
                .join_field('amount',self::settle).' as invest_amount,'
                .join_field('user_name',self::user).','
                .join_field('jujian_amount',self::settle).' as settle_amount,'
                .join_field('real_day',self::settle),
            'join'=>array(
                array('table' => self::borrow, 'where' => join_field('borrow_no', self::settle).' = '.join_field('borrow_no', self::borrow)),
                array('table' => self::user, 'where' => join_field('uid', self::settle).' = '.join_field('uid', self::user)),
                array('table' => self::payment, 'where' => join_field('payment_no', self::payment).' = '.join_field('payment_no', self::settle).' and '.join_field('type', self::payment).'=1')
            ),
            'order_by'=>join_field('user_name',self::user)   
        );

        $data = $this->c->show_page(self::settle,$temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 用户投资总额
     * @param int $uid
     * @return float|int
     */
    public function get_user_invest_all($uid=0){
        $data = 0;
        $temp = array();

        if($uid > 0){
            $temp['where'] = array(
                'select'   => 'SUM('.join_field('amount', self::payment).')',
                'where'    => array(join_field('uid', self::payment) => $uid, join_field('type', self::payment) => 1),
                'where_in' => array('field'=>join_field('status',self::borrow),'value'=>array(4,7)),
                'join'     => array('table' => self::borrow, 'where' => join_field('borrow_no', self::payment).' = '.join_field('borrow_no', self::borrow))
            );

            $data = (float)$this->c->get_one(self::payment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    public function apply(){
        $data = array('data'=>'','msg'=>'你提交的数据有误！','code'=>1);
        $temp = array();
        
        $temp['mobile']   = $this->input->post('mobile',true);
        $temp['password'] = $this->input->post('password',true);
        $temp['authcode'] = $this->input->post('authcode',true);
        $temp['source']   = $this->input->post('source',true);
        
        if($this->_is_mobile($temp['mobile'])){

            //已注册过和未注册两种情况的短信验证
            $temp['is_check'] = $this->send->validation($temp['mobile'], $temp['authcode'], 7, 5);
            if(!$temp['is_check']){
                $temp['where'] = array(
                    'select' => 'uid',
                    'where'  => array(
                                    'code'         => $temp['authcode'],
                                    'send_time >=' => time()-30000,
                                    'target'       => $temp['mobile'],
                                    'type'         => 7
                                )
                );
                $temp['is_check'] = $this->c->count('authcode', $temp['where']);
            }
            if(empty($temp['is_check'])){
                $data['msg'] = '你输入的手机验证码不正确或者已过期！';
                exit(json_encode($data));
            }

            $temp['where'] = array('where' => array('mobile' =>  $temp['mobile'] ));
            $temp['user']  = $this->c->get_row(self::user, $temp['where']);

            if($temp['user'] && $temp['user']['inviter_no']){  //用户信息 存在 并且是居间人
                $data['msg'] = '您提交的号码已是居间人了无须再申请';
            }else{
                if($temp['user']){
                    $temp['update_data'] = array(
                        'inviter_no' =>$this->c->transaction_no(self::user,'inviter_no'),
                        'lv'         =>1
                    );

                    if($temp['source'])$temp['update_data']['address'] = $temp['source'];

                    if($temp['user']['password']){ //有密码 未登录 验证密码
                        $temp['password'] = $this->c->password($temp['password'], $temp['user']['hash']);
                        if(!profile('uid') && $temp['user']['password'] != $temp['password']){
                            $data['msg'] = '你的输入的密码不正确！';
                            exit(json_encode($data));
                        }
                    }else{
                        //没有密码
                        $temp['hash']     = random(6, FALSE);
                        $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                        $temp['update_data']['password'] = $temp['password'];
                        $temp['update_data']['hash']     = $temp['hash'];
                    }

                    $query = $this->c->update(self::user,array('where'=>array('uid'=>$temp['user']['uid'])),$temp['update_data']);

                    if($query){

                        $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                        $temp['user']  = $this->c->get_row(self::user, $temp['where']);

                        if( ! empty($temp['user'])){
                            $this->session->set_userdata($temp['user']);
                        }

                        $data['msg'] = '申请成功！';
                        $data['code'] = 0;

                        if($temp['user']['clientkind'] != 1){
                            $data['data'] = site_url('mobiles/intermediary/real_name');
                        }else{
                            $data['data'] = site_url('mobiles/intermediary/apply_success');
                        }

                    }else{
                        $data['msg'] = '服务器繁忙请稍后重试！';
                    }
                }else{
                    //没有信息 注册一个
                    $temp['hash']     = random(6, FALSE);
                    $temp['password'] = $this->c->password($temp['password'], $temp['hash']);

                    $temp['data'] = array(
                        'user_name'  => $temp['mobile'],
                        'mobile'     => $temp['mobile'],
                        'password'   => $temp['password'],
                        'security'   => '',
                        'hash'       => $temp['hash'],
                        'rate'       => $this->config->item('min_rate'), // 最小提成比例
                        'inviter'    => '', // 会员邀请人
                        'inviter_no' => $this->c->transaction_no(self::user,'inviter_no'),
                        'lv'         => 1, // 会员邀请人
                        'reg_date'   => time(),
                        'reg_ip'     => $this->input->ip_address(),
                        'last_date'  => 0,
                        'last_ip'    => '',
                    );

                    if($temp['source'])$temp['data']['address'] = $temp['source'];

                    $query = $this->c->insert(self::user,$temp['data']);

                    if($query){
                        $temp['where'] = array('where' => array('mobile' => $temp['mobile']));
                        $temp['data']  = $this->c->get_row(self::user, $temp['where']);

                        if( ! empty($temp['data'])){
                            $this->session->set_userdata($temp['data']);
                            $data['msg']  = '申请成功！';
                            $data['code'] = 0;
                            $data['data'] = site_url('mobiles/intermediary/real_name');
                        }else{
                            $data['msg'] = '服务器繁忙请稍后重试！';
                        }

                    }else{
                        $data['msg'] = '服务器繁忙请稍后重试！';
                    }
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 红包
     *
     * @access public
     * @param  int  $limit 条数
     * @return array
     */
    public function get_redbag($limit=20){
        $data = $temp = array();

        $_GET['limit'] = $limit;

        $temp['where'] = array(
            'select'=>join_field('receive_time,amount',self::redbag).','.join_field('mobile',self::user),
            'join'=>array(
                'table'=>self::user,
                'where'=>join_field('uid',self::redbag).'='.join_field('uid',self::user)
            ),
            'order_by'=>join_field('receive_time',self::redbag).' DESC'
        );
        $data = $this->c->show_page(self::redbag,$temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 验证用户手机号码格式
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    protected function _is_mobile($mobile = ''){
        return (preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * 居间人 推荐的用户的活跃等级
     * @param int $lastDate
     * @return string
     */
    protected function _get_active_level($lastDate=0){
        $str      = '';
        $dateNow  = date('Ymd',time());
        $lastDate = date('Ymd',$lastDate);

        $exp = $dateNow - $lastDate;

        switch($exp){
            case $exp>=0&&$exp<=1:
                $str = '很活跃';
                break;
            case $exp>1&&$exp<=7:
                $str = '活跃';
                break;
            case $exp>7&&$exp<=15:
                $str = '不活跃';
                break;
            default:
                $str = '已远离';
        }

        return $str;
    }

    /**
     * 结算的计算公式
     * @param  integer $amounts [总金额]
     * @param  integer $rare    [系数]
     * @param  integer $months  [时间 月数]
     * @return [type]           [结算金额]
     */
    protected function _settle_calculation($amounts=0,$rate=0,$months=0){
        return $amounts*($rate/3000)*$months*30;;
    }

    /**
     * 居间人 客户 关系
     * @param  integer $amounts [总金额]
     * @return [string]         [关系]
     */
    protected function _get_intermediary_ralation($amount=0){
        $ralation = '--';
        if($amount>0&&$amount<=10000){
            $ralation = '过客';
        }else if($amount>10000&&$amount<=100000){
            $ralation = '朋友';
        }else if($amount>100000&&$amount<=500000){
            $ralation = '闺蜜';
        }else if ($amount>500000&&$amount<=5000000){
            $ralation = '亲人';
        }else if($amount>5000000&&$amount<=10000000){
            $ralation = '挚友';
        }else if($amount>10000000&&$amount<=50000000){
            $ralation = '福将';
        }else if ($amount>50000000){
            $ralation = '财神';
        }
        return $ralation;
    }
}