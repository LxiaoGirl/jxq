<?php

/**
 * 各种正则验证
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/15
 * Time: 21:20
 */
class CI_Regex{
    public function __construct(){

    }
    /**
     * 验证用户手机号码
     *
     * @access private
     * @param  string  $mobile 手机号码
     * @return boolean
     */
    public function is_mobile($mobile = ''){
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * 验证邮箱格式
     * @param string $email
     * @return bool
     */
    public function is_email($email=''){
        return ( ! empty($email) && preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $email)) ? TRUE : FALSE;
    }

    /**
     * 验证是否为指定长度的字母/数字组合
     * @param string $str 字符串
     * @param int $num1 最小
     * @param int $num2 最大
     * @return bool
     */

    public function str_range_length($str='', $num1=1, $num2=2){
        return (preg_match("/^[a-zA-Z0-9]{".$num1.",".$num2."}$/",$str))?TRUE:FALSE;
    }

    /**
     * 验证是否为指定长度数字
     * @param string $number
     * @param int $num1
     * @param int $num2
     * @return bool
     */

    public function number_range_length($number='', $num1=1, $num2=2){
        return (preg_match("/^[0-9]{".$num1.",".$num2."}$/i",$number))?TRUE:FALSE;
    }

    /**
     * 验证是否为指定长度汉字
     * @param string $str
     * @param int $num1
     * @param int $num2
     * @return bool
     */

    public function china_range_length($str='', $num1=1, $num2=2){
        return (preg_match("/^([\x81-\xfe][\x40-\xfe]){".$num1.",".$num2."}$/",$str))?TRUE:FALSE;
    }

    /**
     * 验证是否正确格式身份证号码
     * @param string $nric
     * @return bool
     */

    public function is_nric($nric=''){
//        return (preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/',$nric))?TRUE:FALSE;
        $city_array = array(
            11=>"北京",
            12=>"天津",
            13=>"河北",
            14=>"山西",
            15=>"内蒙古",
            21=>"辽宁",
            22=>"吉林",
            23=>"黑龙江",
            31=>"上海",
            32=>"江苏",
            33=>"浙江",
            34=>"安徽",
            35=>"福建",
            36=>"江西",
            37=>"山东",
            41=>"河南",
            42=>"湖北",
            43=>"湖南",
            44=>"广东",
            45=>"广西",
            46=>"海南",
            50=>"重庆",
            51=>"四川",
            52=>"贵州",
            53=>"云南",
            54=>"西藏",
            61=>"陕西",
            62=>"甘肃",
            63=>"青海",
            64=>"宁夏",
            65=>"新疆",
            71=>"台湾",
            81=>"香港",
            82=>"澳门",
            91=>"国外"
        );
        //长度验证
        if( !preg_match('/^\d{17}(\d|x)$/i',$nric) && !preg_match('/^\d{15}$/i',$nric)){
            return false;
        }
        //地区验证
        if(!array_key_exists(intval(substr($nric,0,2)),$city_array)){
            return false;
        }

        // 15位身份证验证生日，转换为18位
        if (strlen($nric) == 15){
            $birthday = '19'.substr($nric,6,2).'-'.substr($nric,8,2).'-'.substr($nric,10,2);
            $d = new DateTime($birthday);
            $dd = $d->format('Y-m-d');
            if($birthday != $dd){
                return false;
            }
            $nric = substr($nric,0,6)."19".substr($nric,6,9);//15to18
            $bit18 = $this->_get_verify_bit($nric);//算出第18位校验码
            $nric = $nric.$bit18;
        }
        // 判断是否大于2078年，小于1900年
        $year = substr($nric,6,4);
        if ($year<1900 || $year>2078 ){
            return false;
        }

        //18位身份证处理
        $birthday = substr($nric,6,4).'-'.substr($nric,10,2).'-'.substr($nric,12,2);
        $d = new DateTime($birthday);
        $dd = $d->format('Y-m-d');
        if($birthday != $dd){
            return false;
        }
        //身份证编码规范验证
        $nric_base = substr($nric,0,17);
        if(strtoupper(substr($nric,17,1)) != $this->_get_verify_bit($nric_base)){
            return false;
        }
        return true;
    }

    // 计算身份证校验码，根据国家标准GB 11643-1999
    protected function _get_verify_bit($nric_base){
        if(strlen($nric_base) != 17){
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($nric_base); $i++){
            $checksum += substr($nric_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    /**
     * 验证是否正确格式邮编
     * @param string $zipcode
     * @return bool
     */

    public function is_zipcode($zipcode=''){
        return (preg_match("/^[1-9]\d{5}$/",$zipcode))?TRUE:FALSE;
    }

    /**
     * 验证是否正确格式url地址
     * @param string $url
     * @return bool
     */

    public function is_url($url=''){
        return (preg_match("/^(http|https|ftp):\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$url))?TRUE:FALSE;
    }
}