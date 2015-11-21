<?php

/**
 * ����������֤
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/15
 * Time: 21:20
 */
class CI_Regex{
    public function __construct(){

    }
    /**
     * ��֤�û��ֻ�����
     *
     * @access private
     * @param  string  $mobile �ֻ�����
     * @return boolean
     */
    public function is_mobile($mobile = ''){
        return ( ! empty($mobile) && preg_match('/^1[345789](\d){9}$/', $mobile)) ? TRUE : FALSE;
    }

    /**
     * ��֤�����ʽ
     * @param string $email
     * @return bool
     */
    public function is_email($email=''){
        return ( ! empty($email) && preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $email)) ? TRUE : FALSE;
    }

    /**
     * ��֤�Ƿ�Ϊָ�����ȵ���ĸ/�������
     * @param string $str �ַ���
     * @param int $num1 ��С
     * @param int $num2 ���
     * @return bool
     */

    public function str_range_length($str='', $num1=1, $num2=2){
        return (preg_match("/^[a-zA-Z0-9]{".$num1.",".$num2."}$/",$str))?TRUE:FALSE;
    }

    /**
     * ��֤�Ƿ�Ϊָ����������
     * @param string $number
     * @param int $num1
     * @param int $num2
     * @return bool
     */

    public function number_range_length($number='', $num1=1, $num2=2){
        return (preg_match("/^[0-9]{".$num1.",".$num2."}$/i",$number))?TRUE:FALSE;
    }

    /**
     * ��֤�Ƿ�Ϊָ�����Ⱥ���
     * @param string $str
     * @param int $num1
     * @param int $num2
     * @return bool
     */

    public function china_range_length($str='', $num1=1, $num2=2){
        return (preg_match("/^([\x81-\xfe][\x40-\xfe]){".$num1.",".$num2."}$/",$str))?TRUE:FALSE;
    }

    /**
     * ��֤�Ƿ���ȷ��ʽ���֤����
     * @param string $nric
     * @return bool
     */

    public function is_nric($nric=''){
//        return (preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/',$nric))?TRUE:FALSE;
        $city_array = array(
            11=>"����",
            12=>"���",
            13=>"�ӱ�",
            14=>"ɽ��",
            15=>"���ɹ�",
            21=>"����",
            22=>"����",
            23=>"������",
            31=>"�Ϻ�",
            32=>"����",
            33=>"�㽭",
            34=>"����",
            35=>"����",
            36=>"����",
            37=>"ɽ��",
            41=>"����",
            42=>"����",
            43=>"����",
            44=>"�㶫",
            45=>"����",
            46=>"����",
            50=>"����",
            51=>"�Ĵ�",
            52=>"����",
            53=>"����",
            54=>"����",
            61=>"����",
            62=>"����",
            63=>"�ຣ",
            64=>"����",
            65=>"�½�",
            71=>"̨��",
            81=>"���",
            82=>"����",
            91=>"����"
        );
        //������֤
        if( !preg_match('/^\d{17}(\d|x)$/i',$nric) && !preg_match('/^\d{15}$/i',$nric)){
            return false;
        }
        //������֤
        if(!array_key_exists(intval(substr($nric,0,2)),$city_array)){
            return false;
        }

        // 15λ���֤��֤���գ�ת��Ϊ18λ
        if (strlen($nric) == 15){
            $birthday = '19'.substr($nric,6,2).'-'.substr($nric,8,2).'-'.substr($nric,10,2);
            $d = new DateTime($birthday);
            $dd = $d->format('Y-m-d');
            if($birthday != $dd){
                return false;
            }
            $nric = substr($nric,0,6)."19".substr($nric,6,9);//15to18
            $bit18 = $this->_get_verify_bit($nric);//�����18λУ����
            $nric = $nric.$bit18;
        }
        // �ж��Ƿ����2078�꣬С��1900��
        $year = substr($nric,6,4);
        if ($year<1900 || $year>2078 ){
            return false;
        }

        //18λ���֤����
        $birthday = substr($nric,6,4).'-'.substr($nric,10,2).'-'.substr($nric,12,2);
        $d = new DateTime($birthday);
        $dd = $d->format('Y-m-d');
        if($birthday != $dd){
            return false;
        }
        //���֤����淶��֤
        $nric_base = substr($nric,0,17);
        if(strtoupper(substr($nric,17,1)) != $this->_get_verify_bit($nric_base)){
            return false;
        }
        return true;
    }

    // �������֤У���룬���ݹ��ұ�׼GB 11643-1999
    protected function _get_verify_bit($nric_base){
        if(strlen($nric_base) != 17){
            return false;
        }
        //��Ȩ����
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //У�����Ӧֵ
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
     * ��֤�Ƿ���ȷ��ʽ�ʱ�
     * @param string $zipcode
     * @return bool
     */

    public function is_zipcode($zipcode=''){
        return (preg_match("/^[1-9]\d{5}$/",$zipcode))?TRUE:FALSE;
    }

    /**
     * ��֤�Ƿ���ȷ��ʽurl��ַ
     * @param string $url
     * @return bool
     */

    public function is_url($url=''){
        return (preg_match("/^(http|https|ftp):\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/",$url))?TRUE:FALSE;
    }
}