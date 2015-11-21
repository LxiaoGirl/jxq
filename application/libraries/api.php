<?php

/**
 * Class Api0
 */
class CI_Api{

    /**
     *每次访问携带的时间 与接收到时的时间间隔秒数
     */
    const REQUEST_TIME_LIMT=300;
    /**
     *携带的签名的变量名称
     */
    const REQUEST_SIGN='sign';

    private $_connector='@';

    /**
     * 访问段 可接收的类型（即：返回类型）
     * @var string
     */
    private $_accept='application/json';

    /**
     * 请求的帐号id
     * @var string
     */
    private $_requestAppid='';

    /**
     * 请求的时间 yyyymmddHHiiss
     * @var int
     */
    private $_requestTime=0;

    /**
     * 请求的签名
     * @var string
     */
    private $_requestSign='';

    /**
     * 请求的帐号信息（数据库查询结果）
     * @var array
     */
    private $_account=array();

    /**
     *构造函数
     */
    public function __construct(){
        //同意服务端和访问短时区
        date_default_timezone_set('PRC');
    }

    /**
     *主方法
     */
    public function index(){
        header('Content-Type:application/json;charset=utf-8');
        $data=array('data'=>array(),'msg'=>'ok','status'=>0);
        $temp=array();

        $this->_init();

        //验证必要信息
        $temp['check']=$this->_request_check();
        if($temp['check']['status'] == 1){
            $this->api_return($temp['check']);
        }

        //验证帐号信息
        $temp['account']=$this->_get_account();
        if( $temp['account']['status'] == 1){
            $this->api_return($temp['account']);
        }

        //验证签名
        $temp['sign']=$this->_check_sign();
        if($temp['sign']['status'] == 1){
            $this->api_return($temp['sign']);
        }
    }

    /**
     *初始化 各项值的处理
     */
    private function _init(){
        $data=$temp=array();
        //获取签名
        $this->_requestSign= isset($_GET[self::REQUEST_SIGN])?$_GET[self::REQUEST_SIGN]:'';

        if( ! empty($this->_requestSign)){
            $temp['sign_str']=base64_decode($this->_requestSign);
            if(strpos( $temp['sign_str'],$this->_connector) !== FALSE){
                $temp['sign_array']=explode($this->_connector, $temp['sign_str']);
                $this->_requestSign=$temp['sign_array'][0];

                if( ! empty($temp['sign_array'][1])){
                    $temp['app_str']=base64_decode($temp['sign_array'][1]);

                    if( ! empty($temp['app_str']) && strpos($temp['app_str'],':') !== FALSE){
                        $temp['app_array']=explode(':',$temp['app_str']);
                        $this->_requestAppid=$temp['app_array'][0];
                        $this->_requestTime=$temp['app_array'][1];
                    }
                }
            }
        }

        unset($temp);
    }

    /**
     * 获得头部信息
     * @return mixed
     */
    private function _get_headers(){
        return apache_request_headers();
    }

    /**
     * 请求的各项值的验证
     * @return array
     */
    private function _request_check(){
        $data=array('data'=>'','msg'=>'','status'=>0);

        //验证appid
        if( empty($this->_requestAppid) ){
            $data['msg']=$this->line('REQUEST_APPID_IS_NULL');
            $data['status']=1;
            return $data;
        }

        //验证时间
        if( empty($this->_requestTime) ){
            $data['msg']=$this->line('REQUEST_TIME_IS_NULL');
            $data['status']=1;
            return $data;
        }

        //验证时间有效期
        $diff = date('YmdHis',time())-$this->_requestTime;
        if( $diff > self::REQUEST_TIME_LIMT || $diff < -300){ //正负300秒  安卓时间受手机时间限制  允许手机时间慢5分钟以内（300）
//            $data['msg']=$this->line('REQUEST_TIME_IS_INVALID');
//            $data['status']=1;
//            return $data;
        }

        //验证签名
        if( empty($this->_requestSign) ){
            $data['msg']=$this->line('REQUEST_SING_IS_NULL');
            $data['status']=1;
            return $data;
        }

        return $data;
    }

    /**
     * 查询帐号信息
     * @return array
     */
    private function _get_account(){
        $data=array('data'=>'','msg'=>$this->line('REQUEST_APPID_IS_NOT_EXISTS'),'status'=>1);

        if( ! empty($this->_requestAppid)){
            //查询数据库 获得 帐户信息
            $ci=&get_instance();
            $this->_account=$ci->c->get_row('api_user',array('where'=>array('appid'=>$this->_requestAppid)));

            if( ! empty($this->_account) && $this->_requestAppid === $this->_account['appid']){
                if($this->_account['status'] == 1){
                    $data['status']=0;
                    $data['msg']='';
                }else{
                    $data['msg']=$this->line('REQUEST_APPID_IS_NO_ACCESSAHTH');
                }
            }
        }

        return $data;
    }

    /**
     * 生成签名
     * @return string
     */
    private function _sign(){
        return md5($this->_account['appid'].$this->_account['appsecret'].$this->_requestTime);
    }

    /**
     * 验证签名
     * @return array
     */
    private function _check_sign(){
        $data=array('data'=>'','msg'=>$this->line('REQUEST_SIGN_IS_WRONG'),'status'=>1);
        $temp=array();

        $temp['sign']=$this->_sign();
        if($temp['sign'] === $this->_requestSign){
            $data['msg']='';
            $data['status']=0;
        }

        unset($temp);
        return $data;
    }

    /**
     * 返回 结果
     * @param array $data
     */
    public function api_return($data=array()){
        switch($this->_accept){
            case 'application/json':
                if(isset($_GET['callback'])){
                    $callback = $_GET['callback'];
                    echo $callback.'('.json_encode($data).')';
                }elseif(isset($_GET['type']) && $_GET['type']=='js'){
                    echo 'var data='.json_encode($data);
                }else{
                    echo json_encode($data);
                }
                break;
            case 'application/xml':
                echo 'sorry! it just  Support json now!';
                break;
            default:
                echo 'sorry! it just  Support json now!';
        }

        exit();
    }

    /**
     * @param $msg
     * @return mixed
     */
    private function line($msg){
        $lang=$this->lang();
        if( ! empty($msg) && isset($lang[$msg])){
            return $lang[$msg];
        }
        return $msg;
    }

    /**
     * @return mixed
     */
    private function lang(){
        $config['REQUEST_APPID_IS_NULL']='访问出错：接口帐户为空';
        $config['REQUEST_APPID_IS_NOT_EXISTS']='访问出错：接口帐户不存在';
        $config['REQUEST_TIME_IS_NULL']='访问出错：接口时间为空';
        $config['REQUEST_TIME_IS_INVALID']='访问出错：接口时间无效';
        $config['REQUEST_SING_IS_NULL']='访问出错：接口签名为空';
        $config['REQUEST_SIGN_IS_WRONG']='访问出错：接口签名错误';
        $config['REQUEST_FUNCTION_IS_NULL']='访问出错：接口请求方法为空';
        $config['REQUEST_FUNCTION_IS_NOT_EXISTS']='访问出错：接口请求方法不存在';
        $config['SERVICE_CLASS_IS_NOT_EXISTS']='访问出错：接口服务类不存在';
        $config['REQUEST_APPID_IS_NO_ACCESSAHTH']='访问出错：该用户接口访问已锁定，请联系管理员！';
        return $config;
    }

    /**
     * curd权限验证
     * @param int $r 查询read  0 | 1
     * @param int $c 增加 cread  0 | 1
     * @param int $u 修改 update  0 | 1
     * @param int $d 删除 delete  0 | 1
     */
    public function check_curd($c=0,$u=0,$r=0,$d=0){
        $auth = '';
        //查询用户增删改查权限
        $auth = $this->_account['authentication'];
        if( ! empty($auth) && strpos($auth,'|') !== FALSE){
            $auth = explode('|',$auth);
        }
        if($r === 1 && ((is_array($auth) && !in_array(1,$auth)) || $auth != 1)){
            $this->api_return(array('data'=>array(),'msg'=>$this->line('REQUEST_NO_AUTH'),'status'=>1));
        }
        if($c === 1 && ((is_array($auth) && !in_array(2,$auth)) || $auth != 2)){
            $this->api_return(array('data'=>array(),'msg'=>$this->line('REQUEST_NO_AUTH'),'status'=>1));
        }
        if($u === 1 && ((is_array($auth) && !in_array(3,$auth)) || $auth != 3)){
            $this->api_return(array('data'=>array(),'msg'=>$this->line('REQUEST_NO_AUTH'),'status'=>1));
        }
        if($d === 1 && ((is_array($auth) && !in_array(4,$auth)) || $auth != 4)){
            $this->api_return(array('data'=>array(),'msg'=>$this->line('REQUEST_NO_AUTH'),'status'=>1));
        }
    }
}
