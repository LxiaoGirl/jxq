<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 认证管理
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Authentication_model extends CI_Model
{
    const user    = 'user'; // 会员表
    const info    = 'user_info'; // 扩展信息
    const address = 'user_address'; // 用户地址
    const region  = 'region'; // 地区管理
    const user_renzheng  = 'user_renzheng'; // 地区管理
     const enterprise = 'enterprise';//企业

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pay');
        $this->lang->load('form');
    }

    /**
     * 实名认证
     *
     * @access public
     * @return array
     */

    public function real_name()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run('authentication/index') == TRUE)
        {

			$temp['uid'] = $this->session->userdata('uid');

            if( ! empty($temp['uid']))
            {
				$nric = $this->input->post('nric', TRUE);
				$real_name = $this->input->post('real_name', TRUE);
				$temp['where'] = array(
                                'select' => 'uid,isok,code,sex',
                                'where'  => array(
                                                'isok'  => "1",
                                                'code'  => "1",
                                                'nric'  => $nric,
                                                'user_name'  => $real_name,
                                            )
                            );
				$temp['is_check'] = $this->c->get_one(self::user_renzheng, $temp['where']);
				
				if(empty($temp['is_check'])){		
				
					$shenfen = $this->pay->shenfenyanzheng($this->input->post('real_name', TRUE),$this->input->post('nric', TRUE));
		
					// $shenfen['data']['err']="123312";
					// $shenfen['data']['address']="123312";
					// $shenfen['data']['sex']="123312";
					// $shenfen['data']['address']="123312";
					// $shenfen['data']['birthday']="123312";
						
					$temp['data'] = array(								   
											'uid' => $temp['uid'],
											'user_name' => $this->input->post('real_name', TRUE),
											'nric' => $this->input->post('nric', TRUE),
											'isok' => $shenfen['isok'],
											'nric_err'      => $shenfen['data']['err'],
											'nric_add'      => $shenfen['data']['address'],
											'sex'      => $shenfen['data']['sex'],
											'reg_date'      => time(),
											'cert_lock'      => "2",
											'cert_err'      => "2",
											'birthday'  =>$shenfen['data']['birthday'],
											'code'  => $shenfen['code'],
										);
					$this->c->insert(self::user_renzheng, $temp['data']);
	            }else{
					$shenfen['isok']="1";
					$shenfen['code']="1";
				}
				$temp['where'] = array(
                                'select' => 'cert_error',
                                'where'  => array(
                                                'uid'  => $temp['uid'],
                                            )
                            );
				$temp['cert_error'] = $this->c->get_one(self::user, $temp['where']);
				$data = array(
										'code' => 1,
										'msg'  => "您的身份证信息有误",
										'url'  => ""
					);	
				 if($shenfen['isok']==1&&$shenfen['code']==1){
					$ceshi = $this->pay->create_user($this->input->post('real_name', TRUE),$this->input->post('nric', TRUE));
					if($shenfen['data']['sex']=="M"){
						$gender ="1";						
					}else{
						$gender ="2";
					}
					
					$str = $ceshi['FundAcc']['VaccId'];
					if(strlen($str)>13)
					$str=substr($str,0,8); 
					//var_dump($str);
					if($str=="30200394"){
					 if( ! empty($ceshi['FundAcc']['VaccId'])) //if( ! empty($ceshi['data']['sex']))// if( ! empty($ceshi['FundAcc']['VaccId']))
					 {

								   $temp['data'] = array(								   
										'gender' => $gender,
										'user_name' => $this->input->post('real_name', TRUE),
										'real_name' => $this->input->post('real_name', TRUE),
										'nric'      => $this->input->post('nric', TRUE),
										'firmid'  => $ceshi['FundAcc']['FirmId'],
										'vaccid'  => $ceshi['FundAcc']['VaccId'],
										'certtype' => $ceshi['Client']['CertType'],
										'certdate' => $ceshi['Client']['CertDate'],
										'bankacc' => $ceshi['BankAcc']['BankAcc'],
										//'platserial' => $ceshi['BankAcc']['PlatSerial'],
										'clientkind'  => "1",
									);
						$temp['where'] = array('where' => array('uid' => $temp['uid']));
						$query = $this->c->update(self::user, $temp['where'], $temp['data']);

						if( ! empty($query))
						{
							$this->session->set_userdata($temp['data']);
							$this->user->add_user_log('profile', '更新个人资料！');

							$temp['clientkind'] = $this->session->userdata('clientkind');
							if($temp['clientkind']=="-1"){
								if(isset($_POST['act']) && $this->input->post('act',true) == 'reg'){
									$temp['msg']   = '你的认证资料已经提交，下一步请绑定银行卡!';
									$temp['url']   = '';
									//$temp['code']=3;
								}else{
									//$temp['msg']   = '你的认证资料已经提交，下一步请支付10元开户费用!';
									$temp['msg']   = '你的认证资料已经提交，聚雪球上线庆祝，减免您的开户费用!';
									$temp['url']   = 'user/';
									$temp['code']=0;
								}
							}elseif($temp['clientkind']=="1"){
									//$temp['msg']   = '你的认证资料已经提交，下一步请支付10元开户费用!';
									$temp['msg']   = '你的认证资料已经提交，聚雪球上线庆祝，减免您的开户费用!';
									$temp['url']   = 'user/';
									$temp['code']=0;
								}			
							$data = array(
										'code' => $temp['code'],
										'msg'  => $temp['msg'],
										'url'  => site_url($temp['url'])
									);
						}else{
						$data = array(
										'code' => 1,
										'msg'  => '这也错了!',
										'url'  => ""
							);
						}
					}else{
						$data = array(
										'code' => 1,
										'msg'  => '银行线路繁忙，请稍后提交信息!',
										'url'  => ""
									);					
					}
				}else{
						$data = array(
										'code' => 1,
										'msg'  => '银行线路繁忙，请稍后提交信息!',
										'url'  => ""
									);					
					}
				}elseif($shenfen['isok']==1&&$shenfen['code']==50){
								$data = array(
										'code' => 1,
										'msg'  => '身份证号码无效',
										'url'  => ""
									);								
				}elseif($shenfen['isok']==1&&$shenfen['code']==2){
					$temp['cert_error'] +=1;
					switch ($temp['cert_error'])
						{
							case '1':
								$time = "30秒";
								$temp['cert_lock']=time()+30;
								break;
							case '2':
								$time = "30分钟";
								$temp['cert_lock']=time()+30*60;
								break;
							case '3':
								$time = "24小时";
								$temp['cert_lock']=time()+60*60*24;
								break;

						}
					$temp['data'] = array('cert_error'  => $temp['cert_error'],'cert_lock'  => $temp['cert_lock']);
					$temp['where'] = array('where' => array('uid' => $temp['uid']));
					$query = $this->c->update(self::user, $temp['where'], $temp['data']);
					$this->session->set_userdata($temp['data']);
					$data = array(
										'code' => 1,
										'msg'  => '身份证姓名号码不一致，请仔细填写，锁定'.$time.'后可以再次提交',
										'url'  => ""
					);					
				} 
				elseif($shenfen['isok']==1&&$shenfen['code']==3){
					$temp['cert_error'] +=1;
					switch ($temp['cert_error'])
						{
							case '1':
								$time = "30秒";
								$temp['cert_lock']=time()+30;
								break;
							case '2':
								$time = "30分钟";
								$temp['cert_lock']=time()+30*60;
								break;
							case '3':
								$time = "24小时";
								$temp['cert_lock']=time()+60*60*24;
								break;
							default:
								$time = array();
						}
					$temp['data'] = array('cert_error'  => $temp['cert_error'],'cert_lock'  => $temp['cert_lock']);
					$temp['where'] = array('where' => array('uid' => $temp['uid']));
					$query = $this->c->update(self::user, $temp['where'], $temp['data']);
					$this->session->set_userdata($temp['data']);
					$data = array(
										'code' => 1,
										'msg'  => '无此身份证号码，请仔细填写，锁定'.$time.'后可以再次提交',
										'url'  => ""
					);						
				}

            }
        }
		
		
		
/*         if($this->form_validation->run() == TRUE)
        {
            $temp['data'] = array(
                                'real_name' => $this->input->post('real_name', TRUE),
                                'nric'      => $this->input->post('nric', TRUE)
                            );

            $temp['uid'] = $this->session->userdata('uid');

            if( ! empty($temp['uid']))
            {
                $temp['where'] = array('where' => array('uid' => $temp['uid']));
                $query = $this->c->update(self::user, $temp['where'], $temp['data']);

                if( ! empty($query))
                {
                    $this->session->set_userdata($temp['data']);
                    $this->user->add_user_log('profile', '更新个人资料！');

                    $temp['email'] = $this->session->userdata('email');
                    $temp['msg']   = ( ! empty($temp['email'])) ? '你的认证资料已经提交请等待审核！' : '你的认证资料已经提交，下一步请验证你的邮箱!';
                    $temp['url']   = ( ! empty($temp['email'])) ? 'user' : 'user/authentication/email';

                    $data = array(
                                'code' => 0,
                                'msg'  => $temp['msg'],
                                'url'  => site_url($temp['url'])
                            );
                }
            }
        } */
        else
        {
            $data['msg'] = $this->form_validation->error_string();
        }

        unset($temp);
        return $data;
    }

    /**
     * 邮箱验证
     *
     * @access public
     * @return array
     */

    public function email()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $temp['email'] = $this->input->post('email', TRUE);

            $query = $this->send->send_email($temp['email']);

            if( ! empty($query))
            {

                $data = array(
                            'code' => 0,
                            'msg'  => '验证邮件已经发送到'.$temp['email'].',请注意查收！',
                            'url'  =>  site_url('user/authentication/email?from='.base64_encode($temp['email']))
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
     * 企业验证
     *
     * @access public
     * @return array
     */

//    public function enterprise()
//    {
//        
//        $data = $temp = array();
//
//        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');
//
//        if($this->form_validation->run() == TRUE)
//        {
//           
//            $query = $this->add_user_info(1);
//            $this->c->update(self::user, array("where"=>array('uid' => $this->session->userdata('uid'))) , array('status'=>1));
//            
//            if( ! empty($query))
//            {
//                
//                $data = array(
//                            'code' => 0,
//                            'msg'  => '恭喜你,你的认证资料提交成功,请等待审核！',
//                            'url'  =>  site_url('user/authentication/enterprise')
//                        );
//            }
//        }
//        else
//        {
//            $data['msg'] = $this->form_validation->error_string();
//        }
//           
//        unset($temp);
//        return $data;
//    }
	  public function enterprise()
     {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

//        if($this->form_validation->run() == TRUE)
//        {
//        	
			
	        $temp['data'] = array(
	                            'organization' => $this->input->post('organization', TRUE),
	                            'industry'     => $this->input->post('industry', TRUE),
	                            'property'     => $this->input->post('property', TRUE),
	                            'reg_date'     => $this->input->post('reg_date', TRUE),
	                            'license'      => $this->input->post('license', TRUE),
	                            'tax_no'       => $this->input->post('tax_no', TRUE),
	                            'turnover'     => $this->input->post('turnover', TRUE),
	                            'staff'        => $this->input->post('staff', TRUE),
	                            'phone'        => $this->input->post('phone', TRUE),
	                            'address'      => $this->input->post('address', TRUE),
	        					'add_time'	   => time(),
 	                        );
	        $temp['data']['uid'] = $this->session->userdata('uid');
	        $temp['data']['status'] = -1;
	        $status = $this->get_enterprise_status();
	        if(!empty($status[0]['status']) && $status[0]['status'] == -2){
	        	$temp['where'] = array(
	        					'where' => array('uid'=>$temp['data']['uid'])
	        	);
	        	$this->db->trans_start();
	        	$query = $this->c->update(self::enterprise,$temp['where'],$temp['data']);
	        	$this->db->trans_complete();
	        }elseif(empty($status[0]['status'])){
		        if( ! empty($temp['data'])){
		                $this->db->trans_start();
		                
		                $query = $this->c->insert(self::enterprise, $temp['data']);
		                $this->db->trans_complete();
		        }
	        }
            if( ! empty($query))
            {
                $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你,你的认证资料提交成功,请等待审核！',
                            'url'  =>  site_url('user/authentication/enterprise')
                        );
            }
//        }
//        else
//        {
//            $data['msg'] = $this->form_validation->error_string();
//        }
           
        unset($temp);
        
        return $data;
    }

    /**
     * 基础资料
     *
     * @access public
     * @return array
     */

    public function base()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->add_user_info(2);

            if( ! empty($query))
            {
                $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你,你的认证资料提交成功,下一步请填写工作单位！',
                            'url'  =>  site_url('user/authentication/company')
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
     * 工作单位
     *
     * @access public
     * @return array
     */

    public function company()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->add_user_info(3);

            if( ! empty($query))
            {
                $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你,你的认证资料提交成功,下一步请填写联系人信息！',
                            'url'  =>  site_url('user/authentication/contacts')
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
     * 联系人
     *
     * @access public
     * @return array
     */

    public function contacts()
    {
        $data = $temp = array();

        $data = array('code' => 1, 'msg' => '你提交的数据有误,请重试！', 'url' => '');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->add_user_info(4);

            if( ! empty($query))
            {
                $data = array(
                            'code' => 0,
                            'msg'  => '恭喜你,你的认证资料提交成功,下一步请上传你的证明材料！',
                            'url'  =>  site_url('user/authentication/attachment')
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
     * 添加扩展信息
     *
     * @access public
     * @param  integer $type 记录类型
     * @return integer
     */

    public function add_user_info($type = 0)
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($type))
        {
            $temp['exist'] = $this->_get_user_keys($type);

            switch ($type)
            {
                case '1':
                    $temp['data'] = $this->_get_enterprise_data($temp['exist'], $type);
                    break;
                case '2':
                    $temp['data'] = $this->_get_extend_data($temp['exist'], $type);
                    break;
                case '3':
                    $temp['data'] = $this->_get_company_data($temp['exist'], $type);
                    break;
                case '4':
                    $temp['data'] = $this->_get_contacts_data($temp['exist'], $type);
                    break;
                default:
                    $temp['data'] = array();
            }

            if( ! empty($temp['data']))
            {
                $this->db->trans_start();
                $temp['uid'] = $this->session->userdata('uid');

                if(isset($temp['data']['add']))
                {
                    $this->c->insert(self::info, $temp['data']['add']);
                }

                if(isset($temp['data']['del']) && ! empty($temp['data']['del']))
                {
                    $temp['where'] = array(
                                        'where'    => array('uid' => $temp['uid'], 'type' => $type),
                                        'where_in' => array('field' => 'key', 'value' => $temp['data']['del'])
                                    );

                    $this->c->delete(self::info, $temp['where']);
                }

                if(isset($temp['data']['set']))
                {
                    $temp['where'] = array(
                                        'where' => array('uid' => $temp['uid'], 'type' => $type)
                                    );

                    $this->c->update(self::info, $temp['where'], $temp['data']['set'], 'key');
                }

                $this->db->trans_complete();
                $query = $this->db->trans_status();

                $temp['logs'] = array(
                                    1 => array('module' => 'enterprise', 'action' => '更新企业认证资料!'),
                                    2 => array('module' => 'credit', 'action' => '更新会员基础资料！'),
                                    3 => array('module' => 'company', 'action' => '更新工作单位资料！'),
                                    4 => array('module' => 'contacts', 'action' => '更新联系人资料！')
                                );

                $temp['logs'] = (isset($temp['logs'][$type])) ? $temp['logs'][$type] : '';

                if( ! empty($query) && isset($temp['logs']))
                {
                    $this->user->add_user_log($temp['logs']['module'], $temp['logs']['action']);
                }
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取扩展信息
     *
     * @access public
     * @param  integer $type 字段类型
     * @param  integer $uid  会员ID
     * @return array
     */

    public function get_user_info($type = 0, $uid = 0)
    {
        $data = $temp = array();

        $temp['uid'] = ( ! empty($uid)) ? (int)$uid : $this->session->userdata('uid');

        if( ! empty($temp['uid']))
        {
            $temp['where'] = array(
                                'select' => 'key,value',
                                'where' => array('uid' => $temp['uid'], 'type' => $type)
                            );

            $temp['data']  = $this->c->get_all(self::info, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    if($type == 1)
                    {
                        $data[$v['key']] = ($v['key'] == 'address') ? $this->_get_user_address($v['value']) : $v['value'];
                    }
                    elseif($type== 2)
                    {
                        $data[$v['key']] = (in_array($v['key'], array('registered','place','address'))) ? $this->_get_user_address($v['value']) : $v['value'];
                    }
                    else
                    {
                        $data[$v['key']] = $v['value'];
                    }
                }
            }
        }
        //把状态获取到
        $data["s"]=$this->c->get_all(self::user,array('select'=>'status',"where"=>array('uid' => $temp['uid'])));
        unset($temp);
        return $data;
    }

    /**
     * 获取地址信息
     *
     * @access public
     * @param  array    $region_id 地区ID
     * @return array
     */

    public function get_region_info($region_id = array())
    {
        $data = $temp = array();

        if( ! empty($region_id))
        {
            natsort($region_id);

            $temp['where'] = array(
                                'select' => 'region_id,region_name',
                                'where_in' => array('field' => 'region_id', 'value' => $region_id)
                            );

            $temp['data'] = $this->c->get_all(self::region, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    $data[$v['region_id']] = $v['region_name'];
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取地址信息字符串
     *
     * @access public
     * @return string
     */

    public function get_region_str($address = array())
    {
        $str = '';
		$temp = array();
		
        if( ! empty($address) && is_array($address))
        {
			$temp['ids'] = array();
			$temp['ids'][] = $address['province'];
			$temp['ids'][] = $address['city'];
			$temp['ids'][] = $address['district'];
			$temp['arr'] = $this->get_region_info($temp['ids']);
			
			$str = (in_array($address['province'], array(2,25,27,32)) ? '' : $temp['arr'][$address['province']])
			//		. '->'
					. $temp['arr'][$address['city']]
			//		. '->'
					. $temp['arr'][$address['district']]
			//		. '->'
					. $address['address'];
		}
		
        unset($temp);
        return $str;
    }
	
    /**
     * 获取地区列表
     *
     * @access public
     * @param  integer $parent_id 父级ID
     * @return array
     */

    public function get_region_list($parent_id = 1)
    {
        $data = $temp = array();

        if( ! empty($parent_id))
        {
            $temp['where'] = array(
                                'select' => 'region_id,region_name',
                                'where'  => array('parent_id' => $parent_id)
                            );

            $data = $this->c->get_all(self::region, $temp['where']);
        }

        unset($temp);
        return $data;
    }

  
    /**
     * 证件上传
     *
     * @access public
     * @param  integer $type 字段类型
     * @return boolean
     */

    public function upload($type = 1, $key = '')
    {
        $query = FALSE;
        $temp  = array();

        $temp['key']  = ( ! empty($key)) ? $key : $this->input->post('key', TRUE);
        $temp['allow'] = array('nric','income','bank','credit','certificate','technica','driving','relation','marriage','stock','business','tax','assets');
        $temp['required'] = array('nric','income','bank','credit','certificate');

        if(isset($_FILES['userfile']) && in_array($temp['key'], $temp['allow']))
        {
            $temp['path'] = $this->session->userdata('uid').'/certificate';
            $temp['result'] = $this->c->upload($temp['path'], '', 'png|jpg|gif');

            $temp['data']=$temp['result']['data'];

            if( ! empty($temp['data'])) //有文件上传成功
            {

                $temp['path'] = '';

                if(isset($temp['data'][0]))
                {
                    foreach($temp['data'] as $v)
                    {
                        $temp['path'][] = $v['full_path'];
                    }

                    $temp['path'] = implode('|', $temp['path']);
                }
                else
                {
                    $temp['path'] = $temp['data']['full_path'];
                }

                $temp['data'] = array(
                                    'uid'      => $this->session->userdata('uid'),
                                    'type'     => $type,
                                    'key'      => $temp['key'],
                                    'value'    => $temp['path'],
                                    'required' => (in_array($temp['key'], $temp['required'])) ? 1 : 0
                                );

                $temp['original'] = $this->get_user_value($temp['key'], $type);

                if( ! empty($temp['original']))
                {
                    $this->load->library('oss',array('access_id'=>item('oss_access_id'),'access_key'=>item('oss_access_key')));
                    if(is_array($temp['original']))
                    {
                        $temp['objects']=array();
                        foreach($temp['original'] as $v)
                        {
                           item('oss_upload')? $temp['objects'][]=$v:delete_files($v);
                        }
                       if(item('oss_upload')) $this->oss->delete_objects(item('oss_bucket_img'),$temp['objects']);
                    }
                    else
                    {
                        item('oss_upload')?  $this->oss->delete_object(item('oss_bucket_img'),$temp['original']):delete_files($temp['original']);
                    }

                    $temp['where'] = array(
                                        'where' => array(
                                                        'uid' => $this->session->userdata('uid'),
                                                        'type'  => $type,
                                                        'key'   => $temp['key'],
                                                    )
                                    );

                    $query = $this->c->update(self::info, $temp['where'], $temp['data']);
                }
                else
                {
                    $query = $this->c->insert(self::info, $temp['data']);
                }

                if( empty($query) && item('oss_upload')){ //如果数据库操作失败  删除oss上已上传的数据
                    $this->load->library('oss',array('access_id'=>item('oss_access_id'),'access_key'=>item('oss_access_key')));
                    $this->oss->delete_objects(item('oss_bucket_img'),explode('/',$temp['path']));
                }
            }

            if( ! empty($query) && empty($temp['result']['query'])){ //数据库操作成功 有上传失败  显示？
                exit($temp['result']['info']);
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取扩展信息
     *
     * @access public
     * @param  string  $key  关键字
     * @param  integer $type 字段类型
     * @return array
     */

    public function get_user_value($key = '', $type = 0)
    {
        $data = $temp = array();

        if( ! empty($key) && ! empty($type))
        {
            $temp['uid'] = $this->session->userdata('uid');

            $temp['where'] = array(
                                'select' => 'value',
                                'where'  => array(
                                                'uid'  => $temp['uid'],
                                                'key'  => $key,
                                                'type' => $type
                                            )
                            );

            $temp['value'] = $this->c->get_one(self::info, $temp['where']);

            if( ! empty($temp['value']))
            {
                $data = (stripos($temp['value'], '|')) ? explode('|', $temp['value']) : $temp['value'];
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 添加用户地址
     *
     * @access private
     * @param  integer  $type  地址类型
     * @param  string   $field 字段名称
     * @return integer
     */

    private function _add_user_address($type = 0, $field = '')
    {
        $id   = 0;
        $temp = array();

        if( ! empty($type))
        {
            $temp['uid'] = $this->session->userdata('uid');

            if( ! empty($field))
            {
                $temp[$field] = $this->input->post($field, TRUE);

                if( ! empty($temp[$field]))
                {
                    $temp['data'] = array(
                                        'uid'      => $temp['uid'],
                                        'type'     => $type,
                                        'province' => (isset($temp[$field]['province'])) ? (int)$temp[$field]['province'] : 0,
                                        'city'     => (isset($temp[$field]['city'])) ? (int)$temp[$field]['city'] : 0,
                                        'district' => (isset($temp[$field]['district'])) ? (int)$temp[$field]['district'] : 0,
                                        'address'  => (isset($temp[$field]['address'])) ? $temp[$field]['address'] : '',
                                    );
                }
            }
            else
            {
                $temp['data'] = array(
                                'uid'      => $temp['uid'],
                                'type'     => $type,
                                'province' => (int)$this->input->post('province'),
                                'city'     => (int)$this->input->post('city'),
                                'district' => (int)$this->input->post('district'),
                                'address'  => $this->input->post('address', TRUE),
                            );
            }

            if( ! empty($temp['data']) )
            {
                $temp['where'] = array(
                                    'select' => 'id',
                                    'where' => array('uid' => $temp['uid'], 'type' => (int)$type)
                                );

                $id = $this->c->get_one(self::address, $temp['where']);

                if( ! empty($id))
                {
                    $temp['where'] = array('where' => array('id' => $id));
                    $this->c->update(self::address, $temp['where'], $temp['data']);
                }
                else
                {
                    $id = $this->c->insert(self::address, $temp['data']);
                }
            }
        }

        unset($temp);
        return $id;
    }

    /**
     * 获取用户地址
     *
     * @access public
     * @param  integer $id 地址ID
     * @return array
     */

    private function _get_user_address($id = 0)
    {
        $data = $temp = array();

        $temp['uid'] = $this->session->userdata('uid');

        $temp['where'] = array(
                            'select' => 'province,city,district,address',
                            'where'  => array('uid' => $temp['uid'], 'id' => $id)
                        );

        $data = $this->c->get_row(self::address, $temp['where']);

        unset($temp);
        return $data;
    }

    /**
     * 企业认证
     *
     * @access public
     * @param  array   $exist 字段名称
     * @param  type    $type  字段类型
     * @return integer
     */

    private function _get_enterprise_data($exist = array(), $type = 0)
    {
        $data = $temp = array();

        $temp['addr'] = $this->_add_user_address($type);

        $temp['data'] = array(
                            'organization' => $this->input->post('organization', TRUE),
                            'industry'     => $this->input->post('industry', TRUE),
                            'property'     => $this->input->post('property', TRUE),
                            'reg_date'     => $this->input->post('reg_date', TRUE),
                            'license'      => $this->input->post('license', TRUE),
                            'tax_no'       => $this->input->post('tax_no', TRUE),
                            'turnover'     => $this->input->post('turnover', TRUE),
                            'staff'        => $this->input->post('staff', TRUE),
                            'phone'        => $this->input->post('phone', TRUE),
                            'address'      => $temp['addr'],
                        );

        $temp['keys'] = array_keys($temp['data']);

        if( ! empty($temp['data']))
        {
            $temp['ignore'] = array('turnover', 'staff');
            $data = $this->_get_build_data($temp['data'], $type, $exist, $temp['keys'], $temp['ignore']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 基础资料
     *
     * @access public
     * @param  array   $exist 字段名称
     * @param  type    $type  字段类型
     * @return integer
     */

    private function _get_extend_data($exist = array(), $type = 0)
    {
        $data = $temp = array();

        $temp['registered'] = $this->_add_user_address(1, 'registered');
        $temp['place']      = $this->_add_user_address(2, 'place');

        $temp['data'] = array(
                            'education'       => $this->input->post('education', TRUE),
                            'school'          => $this->input->post('school', TRUE),
                            'working_age'     => $this->input->post('working_age', TRUE),
                            'income_range'    => $this->input->post('income_range', TRUE),
                            'graduation_date' => $this->input->post('graduation_date', TRUE),
                            'is_marry'        => (int)$this->input->post('is_marry', TRUE),
                            'offspring'       => (int)$this->input->post('offspring', TRUE),
                            'estates'         => (int)$this->input->post('estates', TRUE),
                            'vehicle'         => (int)$this->input->post('vehicle', TRUE),
                            'vehicle_type'    => $this->input->post('vehicle_type', TRUE),
                            'registered'      => $temp['registered'],
                            'place'           => $temp['place']
                        );

        $temp['keys'] = array_keys($temp['data']);

        if( ! empty($temp['data']))
        {
            $temp['ignore'] = array('vehicle_type');
            $data = $this->_get_build_data($temp['data'], $type, $exist, $temp['keys'], $temp['ignore']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 工作单位
     *
     * @access public
     * @param  array   $exist 字段名称
     * @param  type    $type  字段类型
     * @return integer
     */

    private function _get_company_data($exist = array(), $type = 0)
    {
        $data = $temp = array();

        $temp['data'] = array(
                            'organization' => $this->input->post('organization', TRUE),
                            'industry'     => $this->input->post('industry', TRUE),
                            'property'     => $this->input->post('property', TRUE),
                            'staff'        => $this->input->post('staff', TRUE),
                            'hiredate'     => $this->input->post('hiredate', TRUE),
                            'job'          => $this->input->post('job', TRUE),
                            'province'     => (int)$this->input->post('province', TRUE),
                            'city'         => (int)$this->input->post('city', TRUE),
                            'district'     => (int)$this->input->post('district', TRUE),
                            'address'      => $this->input->post('address', TRUE),
                        );

        $temp['keys'] = array_keys($temp['data']);

        if( ! empty($temp['data']))
        {
            $temp['ignore'] = array();
            $data = $this->_get_build_data($temp['data'], $type, $exist, $temp['keys'], $temp['ignore']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 工作单位
     *
     * @access public
     * @param  array   $exist 字段名称
     * @param  type    $type  字段类型
     * @return integer
     */

    private function _get_contacts_data($exist = array(), $type = 0)
    {
        $data = $temp = array();

        $temp['data'] = array(
                            'name1'           => $this->input->post('name1', TRUE),
                            'phone1'          => $this->input->post('phone1', TRUE),
                            'name2'           => $this->input->post('name2', TRUE),
                            'phone2'          => $this->input->post('phone2', TRUE),
                            'spouse_name'     => $this->input->post('spouse_name', TRUE),
                            'spouse_phone'    => $this->input->post('spouse_phone', TRUE),
                            'colleague_name'  => $this->input->post('colleague_name', TRUE),
                            'colleague_phone' => $this->input->post('colleague_phone', TRUE),
                            'friend_name'     => $this->input->post('friend_name', TRUE),
                            'friend_phone'    => $this->input->post('friend_phone', TRUE),
                            'contact_name'    => $this->input->post('contact_name', TRUE),
                            'contact_phone'   => $this->input->post('contact_phone', TRUE)
                        );

        $temp['keys'] = array_keys($temp['data']);

        if( ! empty($temp['data']))
        {
            $temp['ignore'] = array('spouse_name', 'spouse_phone');
            $data = $this->_get_build_data($temp['data'], $type, $exist, $temp['keys'], $temp['ignore']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取处理后的数据
     *
     * @access private
     * @param  array   $user_data 用户数据
     * @param  integer $type      记录类型
     * @param  array   $exist     已存在的字段
     * @param  array   $required  必填字段
     * @param  array   $ignore    非必填字段
     * @return array
     */

    private function _get_build_data($user_data = array(), $type = 0, $exist = array(), $required = array(), $ignore = array())
    {
        $data = $temp = array();

        if( ! empty($user_data))
        {
            if( ! empty($exist))
            {
                $temp['keys'] = contrast($exist, $required);

                foreach ($user_data as $k => $v)
                {
                    $temp['act'] = 'add';

                    if(in_array($k, $temp['keys']['set']))
                    {
                        $temp['act'] = 'set';
                    }

                    $data[$temp['act']][] = array(
                                                'uid'      => $this->session->userdata('uid'),
                                                'type'     => $type,
                                                'key'      => $k,
                                                'value'    => $v,
                                                'required' => ( ! in_array($k, $ignore)) ? 1 : 0
                                            );
                }

                $data['del'] = $temp['keys']['del'];
            }
            else
            {
                foreach ($user_data as $k => $v)
                {
                    $data['add'][] = array(
                                        'uid'      => $this->session->userdata('uid'),
                                        'type'     => $type,
                                        'key'      => $k,
                                        'value'    => $v,
                                        'required' => ( ! in_array($k, $ignore)) ? 1 : 0
                                    );
                }
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取扩展信息关键字
     *
     * @access public
     * @param  integer $type 记录类型
     * @return integer
     */

    private function _get_user_keys($type = 0)
    {
        $data = $temp = array();

        if( ! empty($type))
        {
            $temp['uid']  = $this->session->userdata('uid');

            $temp['where'] = array(
                                'select' => 'key',
                                'where'  => array('uid' => $temp['uid'], 'type' => $type),
                            );

            $temp['data'] = $this->c->get_all(self::info, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    $data[] = $v['key'];
                }
            }
        }

        unset($temp);
        return $data;
    }
    /**
     * 查询企业表状态
     * Enter description here ...
     * @return unknown
     */
    public function get_enterprise_status(){
    	$uid = $this->session->userdata('uid');
    	$temp['where'] = array(
    							'select' => 'status',
    							'where'  => array('uid' => $uid)
    						);
    	$data = $this->c->get_all(self::enterprise, $temp['where']);
    	return $data;
    }
    /**
     * 查询企业认证记录
     * Enter description here ...
     */
	public function get_enterprise(){
    	$uid = $this->session->userdata('uid');
    	$temp['where'] = array(
    							'select' => '*',
    							'where'  => array('uid' => $uid)
    						);
    	$data = $this->c->get_all(self::enterprise, $temp['where']);
    	return $data;
    }
}