<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 借款记录
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-03
 * @updated     2014-09-03
 * @version     1.0.0
 */

class Borrow_model extends CI_Model
{
    const flow        = 'cash_flow'; // 资金记录
    const borrow      = 'borrow'; // 借款管理
    const user        = 'user'; // 会员
    const card        = 'user_card'; // 银行卡
    const collateral  = 'borrow_collateral'; // 抵押物信息
    const payment     = 'borrow_payment'; // 支付记录
    const payment_log = 'payment_log'; // 借款支付记录
    const attachment  = 'borrow_attachment'; // 上传图片
	const automatic   = 'user_automatic'; // 自动投标表
	const message     = 'message'; // 系统消息


	const repay      = 'borrow_repay_plan'; // 还款计划
    const redbag      = 'redbag'; // 红包
    const redbag_cash_flow      = 'redbag_cash_flow'; // 红包

 
 
 
	/**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pay');
        $this->lang->load('form');
        $this->config->load('due_date_type');//加载时间设置
		$this->load->model('other/pcategory_model', 'category');
    }
    /**
     * 债权转让合同编辑
     *
     * @access public
     * @return boolean
     */

    public function set_claims()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);
        $temp['claims']    = $this->input->post('claims', TRUE);
        $temp['claims']    = json_encode($temp['claims']);

        $temp['where']     = array('where' => array('borrow_no' => $temp['borrow_no']));
        $temp['set']       = array('claims' => $temp['claims']);

		$query = $this->c->update(self::borrow, $temp['where'], $temp['set']);

        unset($temp);
        return $query;
    }

    /**
     * 委托合同编辑
     *
     * @access public
     * @return boolean
     */

    public function set_agreement()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no']	= $this->input->get('borrow_no', TRUE);
		$temp['agreement'] 	= $this->input->post('agreement', TRUE);
		$temp['agreement']	= json_encode($temp['agreement']);

		$temp['where']	= array('where' => array('borrow_no' => $temp['borrow_no']));
		$temp['set']	= array('agreement' => $temp['agreement']);

		$query = $this->c->update(self::borrow, $temp['where'], $temp['set']);

        unset($temp);
        return $query;
    }

    /**
     * 抵押物信息
     *
     * @access public
     * @return boolean
     */

    public function collateral()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);
        $temp['base']      = $this->input->post('base');
        $temp['price']     = $this->input->post('price');

        if( ! empty($temp['base']))
        {
            $temp['data']  = array();
            $temp['base']  = array_combine($temp['base']['key'], $temp['base']['value']);

            foreach($temp['base'] as $k => $v)
            {
                $temp['data'][$k] = $v;
            }

            $query = $this->_add_collateral_info($temp['borrow_no'], $temp['data'], 1);
        }

        if( ! empty($temp['price']))
        {
            $temp['data']  = array();
            $temp['price'] = array_combine($temp['price']['key'], $temp['price']['value']);

            foreach($temp['price'] as $k => $v)
            {
                $temp['data'][$k] = $v;
            }

            $query = $this->_add_collateral_info($temp['borrow_no'], $temp['data'], 2);
        }

        unset($temp);
        return $query;
    }

    /**
     * 借款申请
     *
     * @access public
     * @return boolean
     */

    public function create()
    {
        $query = FALSE;
        $temp  = array();

        $temp['mobile'] = $this->input->post('mobile', TRUE);
        $buy_time = $this->input->post('buy_time', TRUE);
        $due_date_type = $this->input->post('due_date_type', TRUE);
		$buy_time = strtotime($buy_time);
		switch ($due_date_type)
		{
			case '1':
			$buy_time = $buy_time + 10*60*60;
			break;		
			case '2':
			$buy_time = $buy_time + 11*60*60;
			break;	
			case '3':
			$buy_time = $buy_time + 12*60*60;
			break;			
			case '4':
			$buy_time = $buy_time + 13*60*60;
			break;			
			case '5':
			$buy_time = $buy_time + 14*60*60;
			break;	
		}

        $temp['data'] = array(
                             'borrow_no'  => $this->c->transaction_no(self::borrow, 'borrow_no'),
                             'subject'    => $this->input->post('subject', TRUE),
                             'uid'        => $this->_get_user_id($temp['mobile']),
                             'type'       => (int)$this->input->post('type'),
                             'productcategory'       => (int)$this->input->post('productcategory'),
                             'months'     => (float)$this->input->post('months'),
                             'mode'       => (int)$this->input->post('mode'),
                             'amount'     => (float)$this->input->post('amount'),
                             'lowest'     => (float)$this->input->post('lowest'),
                             'image'     => $this->input->post('image'),
                             'max'     => (int)$this->input->post('max'),
                             'rate'       => (float)$this->input->post('rate'),
                             'charge'       => "(float)$this->input->post('charge')",
                             'real_rate'  => (float)$this->input->post('real_rate'),
                             'repay'      => (int)$this->input->post('repay'),
                             'deduct'     => (int)$this->input->post('deduct'),
                             'time'     => (int)$this->input->post('time'),
                             'repayment'  => $this->input->post('repayment', TRUE),
                             'summary'    => $this->input->post('summary', TRUE),
                             'content'    => $this->input->post('content', TRUE),
							 'jkr_name'    => $this->input->post('jkr_name', TRUE),
			                 'jkr_idcard'    => $this->input->post('jkr_idcard', TRUE),
							 'jkr_province'    => $this->input->post('jkr_province', TRUE),
                             'operator'   => $this->session->userdata('admin_name'),
                             'add_time'   => time(),
                             'show_time'  => $this->input->post('show_time', TRUE),
                             'buy_time'   => $buy_time,
                             'due_date'   => $this->input->post('due_date', TRUE)
                        );

        if($temp['data']['repay'] == 1)
        {
            if($temp['data']['deduct'] >= $temp['data']['months'])
            {
                $temp['data']['repay']  = 2;
                $temp['data']['deduct'] = 0;
            }
        }
        else
        {
            $temp['data']['deduct'] = 0;
        }

        $temp['data']['show_time'] = strtotime($temp['data']['show_time']);
        //$temp['data']['buy_time']  = strtotime($temp['data']['buy_time']);
        $temp['data']['due_date']  = strtotime($temp['data']['due_date']);

        $query = $this->c->insert(self::borrow, $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 基础资料更新
     *
     * @access public
     * @return boolean
     */

    public function modify()
    {
        $query = FALSE;
        $temp  = array();

        $temp['mobile']    = $this->input->post('mobile', TRUE);
        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        $temp['data'] = array(
                         'subject'     => $this->input->post('subject', TRUE),
                         'lowest'      => (float)$this->input->post('lowest'),
                         'repayment'   => $this->input->post('repayment', TRUE),
                         'summary'     => $this->input->post('summary', TRUE),
                         'content'     => $this->input->post('content', TRUE),
                         'show_time'   => $this->input->post('show_time', TRUE),
                         'buy_time'    => $this->input->post('buy_time', TRUE),
                         'due_date'    => $this->input->post('due_date', TRUE),
						 'jkr_name'    => $this->input->post('jkr_name', TRUE),
						 'jkr_idcard'    => $this->input->post('jkr_idcard', TRUE),
                         'jkr_province'    => $this->input->post('jkr_province', TRUE),
                         'operator'    => $this->session->userdata('admin_name'),
                         'update_time' => time()
                    );

        $temp['data']['show_time'] = strtotime($temp['data']['show_time']);
        $temp['data']['buy_time']  = strtotime($temp['data']['buy_time']);
        $temp['data']['due_date']  = strtotime($temp['data']['due_date']);

        $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

        $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 借款更新
     *
     * @access public
     * @return boolean
     */

    public function update()
    {
        $query = FALSE;
        $temp  = array();

        $temp['mobile']    = $this->input->post('mobile', TRUE);
        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        $temp['data'] = array(
                         'subject'     => $this->input->post('subject', TRUE),
                         'uid'         => $this->_get_user_id($temp['mobile']),
                         'type'        => (int)$this->input->post('type'),
                         'months'      => (float)$this->input->post('months'),
                         'mode'        => (int)$this->input->post('mode'),
                         'amount'      => (float)$this->input->post('amount'),
                         'lowest'      => (float)$this->input->post('lowest'),
                         'rate'        => (float)$this->input->post('rate'),
                         'real_rate'   => (float)$this->input->post('real_rate'),
                         'repay'       => (int)$this->input->post('repay'),
                         'deduct'      => (int)$this->input->post('deduct'),
                         'repayment'   => $this->input->post('repayment', TRUE),
                         'summary'     => $this->input->post('summary', TRUE),
                         'content'     => $this->input->post('content', TRUE),
                         'operator'    => $this->session->userdata('admin_name'),
                         'update_time' => time(),
                         'show_time'   => $this->input->post('show_time', TRUE),
                         'buy_time'    => $this->input->post('buy_time', TRUE),
			 			 'jkr_name'        => $this->input->post('jkr_name', TRUE),
			 			 'jkr_idcard'        => $this->input->post('jkr_idcard', TRUE),
						 'jkr_province'        => $this->input->post('jkr_province', TRUE),
                         'due_date'    => $this->input->post('due_date', TRUE)
                    );

        if($temp['data']['repay'] == 1)
        {
            if($temp['data']['deduct'] >= $temp['data']['months'])
            {
                $temp['data']['repay']  = 2;
                $temp['data']['deduct'] = 0;
            }
        }
        else
        {
            $temp['data']['deduct'] = 0;
        }

        $temp['data']['show_time'] = strtotime($temp['data']['show_time']);
        $temp['data']['buy_time']  = strtotime($temp['data']['buy_time']);
        $temp['data']['due_date']  = strtotime($temp['data']['due_date']);

        $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

        $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

    /**
     * 删除记录
     *
     * @access public
     * @return boolean
     */

    public function delete()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['files'] = $this->get_attachment_list();

            if( ! empty($temp['files']))
            {
                foreach($temp['files'] as $v)
                {
                    delete_files($v['link_url']);
                }
            }

            $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));
            $query = $this->c->delete(self::borrow, $temp['where']);
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取抵押物信息
     *
     * @access public
     * @return array
     */

    public function get_collateral_info()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        $temp['where'] = array(
                            'select'   => 'type,key,value',
                            'where'    => array('borrow_no' => $temp['borrow_no']),
                        );

        $temp['data'] = $this->c->get_all(self::collateral, $temp['where']);

        if( ! empty($temp['data']))
        {
            foreach($temp['data'] as $k => $v)
            {
                $data[$v['type']][$v['key']] = $v['value'];
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 删除附件
     *
     * @access public
     * @return boolean
     */

    public function remove()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);
        $temp['id']        = (int)$this->input->get('id');

        if( ! empty($temp['borrow_no']) && ! empty($temp['id']))
        {
            $temp['where'] = array(
                                'select' => 'link_url',
                                'where'  => array(
                                                'id'        => $temp['id'],
                                                'borrow_no' => $temp['borrow_no']
                                            )
                            );

            $temp['data'] = $this->c->get_row(self::attachment, $temp['where']);

            if( ! empty($temp['data']))
            {
                foreach($temp['data'] as $v)
                {
                    if(is_file($v))
                    {
                        delete_files($v);
                    }
                }

                $temp['where'] = array(
                                    'where'  => array(
                                                    'id'        => $temp['id'],
                                                    'borrow_no' => $temp['borrow_no']
                                                )
                                );

                $query = $this->c->delete(self::attachment, $temp['where']);
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 资料上传
     *
     * @access public
     * @return boolean
     */

    public function upload()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no']   = $this->input->get('borrow_no', TRUE);
        $temp['type']        = (int)$this->input->post('type');
        $temp['description'] = $this->input->post('description', TRUE);

        if(isset($_FILES['userfile']) && ! empty($temp['borrow_no']))
        {
            $temp['path']  = $temp['borrow_no'];
            $temp['result'] = $this->c->upload($temp['path'], '', 'png|jpg|gif');
            $temp['files']=$temp['result']['data'];

            if( ! empty($temp['files']))
            {
                $temp['data'] = array();

                if(item('oss_upload')){  //oss 时  不处理水印和裁剪
                    foreach($temp['files'] as $k => $v)
                    {
//                        $this->load->library('oss',array('access_id'=>item('oss_access_id'),'access_key'=>item('oss_access_key')));
//                        $tmpdir="./uploads/temp".time().".jpg";
//                        $this->oss->get_object(item('oss_bucket_img'),$v['full_path'],array('fileDownload' => $tmpdir));

                        $temp['desc']      = (isset($temp['description'][$k])) ? $temp['description'][$k] : '';
                        $temp['watermark'] = 'admin/assets/img/watermark.png';

//                            $this->c->thumb($tmpdir, FALSE, 800, 600);
//                            $this->c->watermark($tmpdir, $temp['watermark'], 'center', 'middle');

//                        if(item('oss_upload')){
//                            $this->oss->upload_file_by_file(item('oss_bucket_img'),$v['full_path'],$tmpdir);
//                            delete_files($tmpdir);
//                        }

                        $temp['data'][] = array(
                            'borrow_no'   => $temp['borrow_no'],
                            'type'        => $temp['type'],
                            'link_url'    => $v['full_path'],
                            'description' => $temp['desc'],
                            'operator'    => $this->session->userdata('admin_name'),
                            'dateline'    => time()
                        );
//                    $temp['path'][]=$v['full_path'];
                    }
                }else{
                    foreach($temp['files'] as $k => $v)
                    {
                        if(is_file($v['full_path']))  //有修改 元 is_file($tmpdir)
                        {
                            $temp['desc']      = (isset($temp['description'][$k])) ? $temp['description'][$k] : '';
                            $temp['watermark'] = 'admin/assets/img/watermark.png';

                            $this->c->thumb($v['full_path'], FALSE, 800, 600);
                            $this->c->watermark($v['full_path'], $temp['watermark'], 'center', 'middle');

                            $temp['data'][] = array(
                                'borrow_no'   => $temp['borrow_no'],
                                'type'        => $temp['type'],
                                'link_url'    => $v['full_path'],
                                'description' => $temp['desc'],
                                'operator'    => $this->session->userdata('admin_name'),
                                'dateline'    => time()
                            );
                        }
                    }
                }

                if( ! empty($temp['data']))
                {
                    $query = $this->c->insert(self::attachment, $temp['data']);
                }

                //wjjf-public oss 删除 开启了手机验证  此处注释掉了
//                if( empty($query)  && item('oss_upload')){ //如果数据库操作失败  删除oss上已上传的数据
//                    $this->load->library('oss',array('access_id'=>item('oss_access_id'),'access_key'=>item('oss_access_key')));
//                    $this->oss->delete_objects(item('oss_bucket_img'),$temp['path']);
//                }
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 借款记录审核
     *正常发布
     * @access public
     * @return boolean
     */

    public function verify()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
			
            $temp['where'] = array(
                                'select' => 'uid,type,amount,months',
                                'where'  => array('borrow_no' => $temp['borrow_no'], 'status' => 0)
                            );

            $temp['borrow']  = $this->c->get_row(self::borrow,$temp['where']);
			
            if( ! empty($temp['borrow']))
            {
                //$temp['day']      = ($temp['borrow']['type'] == 1) ? 7 : 14;
                //$temp['due_date'] = strtotime('+'.$temp['day'].' days');
				
				
                $temp['data']  = array(
                                    'uid'         => $temp['borrow']['uid'],
                                    'operator'    => $this->session->userdata('admin_name'),
                                    'update_time' => time(),
                                    'verify_time' => time(),
                                    'status'      => 2
                                );

                $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

                $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

                if( ! empty($query))
                {
                    $temp['subject'] = sprintf('您好，你申请%s的借款，已经通过“审核”！', $temp['borrow']['amount']);
                    $temp['content'] = sprintf('您好，你申请%s的借款，已经通过“审核”。我们会在48小时内开始招募投资人。如需帮助请拨打专属客户经理电话。',$temp['borrow']['amount']);

                    $this->send_message($temp['borrow']['uid'], $temp['subject'], $temp['content']);
                }
            }
        }

        unset($temp);
        return $query;
    }




 /**
     * 借款记录确定审核
     *自动配标
     * @access public
     * @return boolean
     */

    public function verify_form()
    {
        $query = FALSE;
        $temp  = array();
        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);//投资编号
		$temp['pzje'] = $this->input->get('pzje', TRUE);//自动投金额
		$temp['zzpzje'] = $this->input->get('zzpzje', TRUE);//可用投资总金额
		$temp['rzje'] = $this->input->get('rzje', TRUE);//融资金融
		$temp['yh'] = $this->input->get('yh', TRUE);//优化标签
		$temp['pzje_sy'] = $temp['pzje'];//自动投金额
        if( ! empty($temp['borrow_no']))
        {
			$temp['where'] = array(
                                'select' => 'uid,type,amount,months,subject,rate,lowest,productcategory',
                                'where'  => array('borrow_no' => $temp['borrow_no'], 'status' => 0)
                            );
				
				$temp['data']   = $this->c->get_row(self::borrow,$temp['where']);
				 $lowest=$temp['data']['lowest'];
				$f=0;
				$i=0;

			if($temp['yh']==0){//未点击优化按钮
							
				while($f==0){
				//得到自动表匹配的数据   
					$temp['where_all'] = array(
                                'select' => '*,floor(balance_ye/'.$lowest.')*'.$lowest.' as balance_ye_one',
                                'where'  => array( 'statue' => 1 , 'jk_max >=' => $temp['data']['months'] , 'sy_min <=' =>$temp['data']['rate'] , 'pzsj_start <=' => time() , 'pzsj_end >=' => time(),'balance >='=>100,'balance_ye >='=>100),
								'where_in' => array('field' => 'type', 'value' => array(0,$temp['data']['productcategory'])),
								'limit'    => array('limit' => '40', 'offset' => $i),
								'order_by' => 'allamount desc,balance desc'
                            );
			   $temp['data_all']  = $this->c->get_all(self::automatic,$temp['where_all']);

			   if($temp['data_all']!=null){
					foreach($temp['data_all'] as $k => $v){
					$uid=$v['uid'];
					$balance1=$v['balance'];
					$balance=$v['balance_ye_one'];
					
					if($v['mode']==1){
						$balance=$v['balance'];
					}
					if($v['mode']==2){
						if($balance>=$balance1){
							$balance=$v['balance'];
						}else if($balance<$balance1){
							$balance=$v['balance_ye_one'];
						}
					}
					
					$temp['pzje']=$temp['pzje']-$balance;
					if($temp['pzje']>0){
						$this->invest($balance, $temp['borrow_no'], $balance1 , $uid);
					}else{//自动资金充足
						$balance=$temp['pzje']+$balance;
						$this->invest($balance, $temp['borrow_no'], $balance1 , $uid);
						$f=1;
					}
					
				}
				 $i=$i+40;
			   }else{
				 $f=1;
			   }
				}
				
				//die();//测试
			}else{//点击优化按钮
				while($f==0){
				//得到自动表匹配的数据   
					$temp['where_all'] = array(
                                'select' => '*,floor(balance_ye/'.$lowest.')*'.$lowest.' as balance_ye_one',
                                'where'  => array( 'statue' => 1 , 'jk_max >=' => $temp['data']['months'] , 'sy_min <=' =>$temp['data']['rate'] , 'pzsj_start <=' => time() , 'pzsj_end >=' => time(), 'balance >='=>100,'balance_ye >='=>100),
								'where_in' => array('field' => 'type', 'value' => array(0,$temp['data']['productcategory'])),
								'limit'    => array('limit' => '40', 'offset' => $i),
								'order_by' => 'allamount desc,balance desc'
                            );
				$temp['data_all']  = $this->c->get_all(self::automatic,$temp['where_all']);
			   if($temp['data_all']!=null){
					foreach($temp['data_all'] as $k => $v){
					$uid=$v['uid'];
					$balance_all=$v['balance'];
					$balance=$v['balance_ye_one'];
					$pzje=floor($temp['pzje']/2/100)*100;//50% 最大投资额


					if($v['mode']==1){
						$balance=$v['balance'];
					}
					if($v['mode']==2){
						if($balance>=$balance_all){
							$balance=$v['balance'];
						}else if($balance<$balance_all){
							$balance=$v['balance_ye_one'];
						}
					}
					//判断剩余自动金额是否符合最大投资
					if($balance>=$pzje){
						$balance=$pzje;			
					}
					$temp['pzje_sy']=$temp['pzje_sy']-$balance;
					//剩余金额判断
					if($temp['pzje_sy']>=0){
						$this->invest($balance, $temp['borrow_no'], $balance_all , $uid);
					}else{
						$balance=$temp['pzje_sy']+$balance;
						if($balance>0){
						$this->invest($balance, $temp['borrow_no'], $balance_all , $uid);
						}
						$temp['pzje_sy']=0;
						$f=1;
						break;
						
					}
				}
				 $i=$i+40;
			  }else{
				   if($temp['pzje_sy']>0){//第一轮结束还有余额
				 $temp['where_balance'] = array(
                                'select' => 'sum(floor(balance_ye/'.$lowest.')*'.$lowest.') as balance_ye_all',
                                'where'  => array( 'statue' => 1 , 'jk_max >=' => $temp['data']['months'] , 'sy_min <=' =>$temp['data']['rate'] , 'pzsj_start <=' => time() , 'pzsj_end >=' => time()),
								'query' =>'`balance` >= `balance_ye`',
								'where_in' => array('field' => 'type', 'value' => array(0,$temp['data']['productcategory']))			
                            );
				$temp['allbalance1']  = $this->c->get_row(self::automatic,$temp['where_balance']);
				$temp['where_balance'] = array(
                                'select' => 'sum(floor(balance/'.$lowest.')*'.$lowest.') as balance_ye_all',
                                'where'  => array( 'statue' => 1 , 'jk_max >=' => $temp['data']['months'] , 'sy_min <=' =>$temp['data']['rate'] , 'pzsj_start <=' => time() , 'pzsj_end >=' => time()),
								'query' =>'`balance` < `balance_ye`',
								'where_in' => array('field' => 'type', 'value' => array(0,$temp['data']['productcategory']))			
                            );
				$temp['allbalance2']  = $this->c->get_row(self::automatic,$temp['where_balance']);
			    $temp['allbalance']=$temp['allbalance1']['balance_ye_all']+$temp['allbalance2']['balance_ye_all'];
				if( $temp['allbalance']*100>0){
					$i=0;
				}else{
					 $f=1;		
				}}else{//第一轮结束没有余额
					 $f=1;
				   }

			   }//else结束
				}
				//die();//测试
			}//优化结束

			
            $temp['where'] = array(
                                'select' => 'uid,type,amount,months',
                                'where'  => array('borrow_no' => $temp['borrow_no'], 'status' => 0)
                            );

            $temp['borrow']  = $this->c->get_row(self::borrow,$temp['where']);
			
            if( ! empty($temp['borrow']))
            {
                
				
                $temp['data']  = array(
                                    'uid'         => $temp['borrow']['uid'],
                                    'operator'    => $this->session->userdata('admin_name'),
                                    'update_time' => time(),
                                    'verify_time' => time(),
                                    'status'      => 2
                                );

                $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

                $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

                if( ! empty($query))
                {

                    $temp['subject'] = sprintf('您好，你申请%s的借款，已经通过“审核”！', $temp['borrow']['amount']);
                    $temp['content'] = sprintf('您好，你申请%s的借款，已经通过“审核”。我们会在48小时内开始招募投资人。如需帮助请拨打专属客户经理电话。',$temp['borrow']['amount']);
                    $this->send_message($temp['borrow']['uid'], $temp['subject'], $temp['content']);
                }
            }
        }

        unset($temp);
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
     * 满标记录审核
     *
     * @access public
     * @return boolean
     */

    public function finish()
    {
        $query = FALSE;
        $temp  = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);
        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'select' => 'uid,borrow_no,deduct,amount,months,rate,real_rate,repay',
                                'where'  => array('borrow_no' => $temp['borrow_no'], 'status' => 3)
                            );

            $temp['borrow']  = $this->c->get_row(self::borrow,$temp['where']);
			
			$temp['where'] = array(
                                'select' => 'uid,amount',
                                'where'  => array('borrow_no' => $temp['borrow_no'], 'status' => 1)
                            );
            $temp['payment']  = $this->c->get_all(self::payment,$temp['where']);
			//var_dump($temp['payment']);
			foreach ($temp['payment'] as $k => $v)	
			{
				//echo $v['uid']."金额：".$v['amount']."</br>";
 				if(!isset($r[$v['uid']])) 
					$r[$v['uid']] = $v;
				else 
					$r[$v['uid']]['amount'] += intval ($v['amount']); 
			}
			$i = 0;
			foreach ($r as $k => $v){
				$i = $i+1;
				$MarketSerial="R".date('YmdHis').$i;
				$temp['where'] = array(
                                'select' => 'firmid,real_name',
                                'where'  => array('uid' => $v['uid'])
                            );
				$user  = $this->c->get_row(self::user,$temp['where']);
				$configData = $this->pay->zanghuchaxun($MarketSerial,$user['firmid'],$user['real_name']);
				$configData['Transfer']['CurrentBalance'] = intval($configData['Transfer']['CurrentBalance'])/100;
				if($configData['Transfer']['CurrentBalance']<intval($v['amount'])){
					$error_amount[$v['uid']]= $user['real_name']."投资金额为".intval($v['amount'])."实际金额为".$configData['Transfer']['CurrentBalance'];					
				}
			}

            //if( ! empty($temp['borrow']))
           if(empty($error_amount))
            {
                $temp['where'] = array(
                                        'select' => join_field('card_no,real_name,account,bank_name', self::card),
                                        'join'   => array(
                                                        'table' => self::card,
                                                        'where' => join_field('card_no', self::user).' = '.join_field('card_no', self::card)
                                                    ),
                                        'where'  => array(join_field('uid', self::user) => $temp['borrow']['uid'])
                                    );

                $temp['account']    = $this->c->get_row(self::user, $temp['where']);
                $temp['payment_no'] = $this->c->transaction_no(self::payment_log, 'payment_no');

                $this->db->trans_start();

                $temp['interest'] = round($temp['borrow']['amount'] * $temp['borrow']['real_rate'] / 1200, 2);
                $temp['amount']   = $temp['borrow']['amount'];

                if($temp['borrow']['repay'] == 1)
                {
                    $temp['amount'] = round($temp['amount'] - $temp['interest'] * $temp['borrow']['deduct'], 2);
                }
                else
                {
                    $temp['amount'] = round($temp['amount'] - $temp['interest'] * $temp['borrow']['months'], 2);
                }

                $temp['data']  = array('payment' => $temp['amount'], 'payment_no' => $temp['payment_no']);
                $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

                $this->c->update(self::borrow, $temp['where'], $temp['data']);
                $temp['data'] = array(
                                    'payment_no' => $temp['payment_no'],
                                    'uid'        => $temp['borrow']['uid'],
                                    'borrow_no'  => $temp['borrow_no'],
                                    'card_no'    => (isset($temp['account']['card_no'])) ? $temp['account']['card_no'] : '',
                                    'amount'     => $temp['amount'],
                                    'charge'     => $temp['borrow']['amount']*0.002,
                                    'real_name'  => (isset($temp['account']['real_name'])) ? $temp['account']['real_name'] : '',
                                    'bank_name'  => (isset($temp['account']['bank_name'])) ? $temp['account']['bank_name'] : '',
                                    'account'    => (isset($temp['account']['account'])) ? $temp['account']['account'] : '',
                                    'auditor'    => $this->session->userdata('admin_name'),
                                    'add_time'   => time(),
                                );

                $this->c->insert(self::payment_log, $temp['data']);

                $temp['data']  = array(
                                    'operator'    => $this->session->userdata('admin_name'),
                                    'update_time' => time()
                                );

                $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));

                $this->c->update(self::borrow, $temp['where'], $temp['data']);

                $this->db->trans_complete();

                $query = $this->db->trans_status();
				//紅包插入開始
				
				if(!empty($query)) {
					$temp['where']['where'] = array('dateline' => date('Ymd'));
					$count = $this->c->count(self::redbag_cash_flow, $temp['where']);
					if ($count == 0) {
						$temp['redbag_cash_flow'] = array('dateline' =>date('Ymd'), 'amount' =>"30", 'source' =>"" );
						$query = $this->c->insert(self::redbag_cash_flow, $temp['redbag_cash_flow']);
						$limit = 30;
					}else{
						$temp['where']['where'] = array('dateline' =>date('Ymd'));
						$query = $this->c->get_row(self::redbag_cash_flow, $temp['redbag_cash_flow']);
						$limit = $query['amount'];
					} 

					//查询符合条件的用户
					$sqlquery = $this ->db ->query("SELECT `p2p`.`cdb_user`.inviter,b.amount,b.payment_no,b.uid,`p2p`.`cdb_borrow`.subject,`p2p`.`cdb_user`.real_name,`p2p`.`cdb_user`.real_name,`p2p`.`cdb_user`.mobile FROM  (select * from `p2p`.`cdb_borrow_payment` where pay_time >= 1444406400 and borrow_no = '". $temp['borrow_no']."' and amount >= 500 and uid in(SELECT uid FROM `p2p`.`cdb_user` where inviter<>0 and firmid is not null and is_redbag is null) order by amount desc limit 0,".$limit.") as b join `p2p`.`cdb_user` on `p2p`.`cdb_user`.uid = b.uid join `p2p`.`cdb_borrow` on `p2p`.`cdb_borrow`.borrow_no = b.borrow_no ");
					$payment = $sqlquery ->result();
					$object = json_decode(json_encode($payment), true);
					$i = 0;
					//var_dump("查询需要发放红包的人数");
					//var_dump($object);
					foreach($object as $o =>$p) {
						$temp['where'] = array();
						$temp['where']['where'] = array('uid' =>$p['inviter']);
						$inviteruser = $this ->c ->get_row(self::user, $temp['where']);

						$MarketSerial = time('YmdHis');
						$PVaccId = "30200394000014";
						$PCustName = "沈阳网加互联网金融服务有限公司";
						$RVaccId = $inviteruser['vaccid'];
						$RCustName = $inviteruser['real_name'];
						$amount = "20";
						$TransferCharge = 0;
						$hongbao = $this ->pay ->zhifu($MarketSerial, $PVaccId, $PCustName, $RVaccId, $RCustName, $amount * 100, $TransferCharge);
						var_dump($hongbao);
						echo "</br>";
						$hongbao['ReturnInfo']['RtnCode'] = "000000";
						if ($hongbao['ReturnInfo']['RtnCode'] == "000000") {
							//发放红包
							$temp['redbag'] = array('uid' =>$p['inviter'], 'flag' =>"2", 'Remark' =>"居间人邀请活动", 'active' =>"邀请红包", 'amount' =>"20", 'source' =>$p['real_name'].$p['subject']."投资".$p['amount'], 'balance' =>"", 'contract_time' =>time(),'receive_time' =>"",  'deadline' =>"", 'status' =>"0");
							$query = $this ->c ->insert(self::redbag, $temp['redbag']);

							var_dump("查询发放红包是否成功");
							var_dump($query);
							echo "</br>";

							//对用户的状态进行修改，添加is_redbag = 1，添加加息标记
							$temp['where']['where'] = array('uid' =>$p['uid']);
							$temp['is_redbag'] = array('is_redbag' =>"1");
							$query = $this ->c ->update(self::user, $temp['where'], $temp['is_redbag']);
							var_dump("查询更新发放红包标记");
							var_dump($temp['where']);
							var_dump($temp['is_redbag']);
							//var_dump($this->db->last_query());
							echo "</br>";

							//对用户的状态进行修改，添加加息标记
							$temp['where']['where'] = array('uid' =>$p['uid'], 'payment_no' =>$p['payment_no'], );
							$temp['increase_interest'] = array('increase_interest' =>"1", 'increase_interest_rate' =>"0.005");
							$query = $this ->c ->update(self::borrow_payment, $temp['where'], $temp['increase_interest']);
							var_dump("查询加息标记是否成功");
							var_dump($query);
							echo "</br>";

							$i++;
						}
					}

					var_dump("更新红包发放数目");
					var_dump($i);
					echo "</br>";

					$temp['where']['where'] = array('dateline' =>date('Ymd'));
					$temp['redbag_cash_flow'] = array('amount' =>$limit - $i, 'source' =>$redbag_cash_flow['borrow_no'].','.$temp['borrow_no'], );
					$query = $this ->c ->update(self::redbag_cash_flow, $temp['where'], $temp['redbag_cash_flow']);
					//更新日期红包表的发放数目												
				}				
				//紅包插入結束
				
				
				
				
            } 
        }

        unset($temp);
        return $error_amount;
    }

    /**
     * 获取附件信息
     *
     * @access public
     * @return array
     */

    public function get_attachment_list()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array(
                                'where'    => array('borrow_no' => $temp['borrow_no']),
                                'order_by' => 'id desc'
                            );

            $data = $this->c->get_all(self::attachment, $temp['where']);
        }

        unset($temp);
        return $data;
    }

    /**
     * 获取借款列表
     *
     * @access public
     * @return array
     */

    public function get_borrow_info()
    {
        $data = $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        if( ! empty($temp['borrow_no']))
        {
            $temp['where'] = array('where' => array('borrow_no' => $temp['borrow_no']));
            $data = $this->c->get_row(self::borrow, $temp['where']);

            if( ! empty($data))
            {
				$data['agreement'] = json_decode($data['agreement'], TRUE);
				$data['claims'] = json_decode($data['claims'], TRUE);

                $temp['where']   = array(
                                        'select' => 'mobile,user_name,real_name',
                                        'where'  => array('uid' => $data['uid'])
                                    );

                $data['user'] = $this->c->get_row(self::user, $temp['where']);
            }
        }
		 $data['data']=$this->category->show();
        unset($temp);
        return $data;
    }


    /**
     * 获取借款列表
     *
     * @access public
     * @return array
     */

    public function show()
    {
        $data = $temp = array();

        $temp['keyword']   = $this->input->get('keyword', TRUE);
        $temp['productcategory']   = $this->input->get('productcategory', TRUE);

        $temp['where'] = array(
                            'select'   => join_field('borrow_no,subject,type,months,amount,rate,real_rate,receive,payment_no,add_time,status', self::borrow).','.join_field('user_name,real_name',self::user),
                            'join'     => array('table' => self::user, 'where' => join_field('uid', self::borrow).' = '.join_field('uid', self::user)),
                            'order_by' => join_field('id', self::borrow).' desc'
                        );

        if( ! empty($temp['keyword']))
        {
            $temp['field'] = (stripos($temp['keyword'], 'B') === 0) ? join_field('borrow_no', self::borrow) : join_field('user_name', self::user);
            $temp['where']['like'] = array('field' => $temp['field'], 'match' => $temp['keyword']);
        }
        $temp['where']['where']=array();
        if(isset($_GET['status']) && $_GET['status'] != -1){
            $temp['where']['where']=array(join_field('status',self::borrow)=>(int)$this->input->get('status'));
        }
        if( ! empty($temp['productcategory'])){
            $temp['where']['where']=array_merge($temp['where']['where'],array(join_field('productcategory',self::borrow)=>$temp['productcategory']));
        }

        $data = $this->c->show_page(self::borrow, $temp['where']);

        $data['status']=isset($_GET['status'])?(int)$this->input->get('status'):'';
        $data['productcategory_select']=isset($temp['productcategory'])?$temp['productcategory']:'';

        unset($temp);
        return $data;
    }

    /**
     * 添加抵押物信息
     *
     * @access public
     * @param  string  $borrow_no 借款编号
     * @param  arrray  $user_data 扩展信息
     * @param  integer $type      记录类型
     * @return boolean
     */

    private function _add_collateral_info($borrow_no = '', $user_data = array(), $type = 1)
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($borrow_no) && ! empty($user_data))
        {
            $temp['where'] = array(
                                'select' => 'key',
                                'where'  => array('borrow_no' => $borrow_no, 'type' => (int)$type)
                            );

            $temp['data'] = $this->c->get_all(self::collateral, $temp['where']);

            if( ! empty($temp['data']))
            {
                $temp['exists'] = array();

                foreach($temp['data'] as $k => $v)
                {
                    $temp['exists'][] = $v['key'];
                }

                $temp['keys']     = array_keys($user_data);
                $temp['contrast'] = contrast($temp['exists'], $temp['keys']);

                if( ! empty($temp['contrast']['add']))
                {
                    $temp['add'] = array();

                    foreach($user_data as $k => $v)
                    {
                        if(in_array($k, $temp['contrast']['add']))
                        {
                            $temp['add'][] = array(
                                                'borrow_no' => $borrow_no,
                                                'type'      => (int)$type,
                                                'key'       => $k,
                                                'value'     => $v
                                            );
                        }
                    }

                    $query = $this->c->insert(self::collateral, $temp['add']);
                }

                if( ! empty($temp['contrast']['del']))
                {
                    $temp['where'] = array(
                                        'where'    => array('borrow_no' => $borrow_no, 'type' => $type),
                                        'where_in' => array(
                                                        'field' => 'key',
                                                        'value' => $temp['contrast']['del']
                                                    )
                                    );

                    $query = $this->c->delete(self::collateral, $temp['where']);
                }

                if( ! empty($temp['contrast']['set']))
                {
                    $temp['set'] = array();

                    foreach($user_data as $k => $v)
                    {
                        if(in_array($k, $temp['contrast']['set']))
                        {
                            $temp['set'][] = array(
                                                'key'   => $k,
                                                'value' => $v
                                            );
                        }
                    }

                    $temp['where'] = array('where' => array('borrow_no' => $borrow_no));
                    $query = $this->c->update(self::collateral, $temp['where'], $temp['set'], 'key');
                }
            }
            else
            {
                $temp['add'] = array();

                foreach($user_data as $k => $v)
                {
                    $temp['add'][] = array(
                                        'borrow_no' => $borrow_no,
                                        'type'      => (int)$type,
                                        'key'       => $k,
                                        'value'     => $v
                                    );
                }

                $query = $this->c->insert(self::collateral, $temp['add']);
            }
        }

        unset($temp);
        return $query;
    }

    /**
     * 获取会员ID
     *
     * @access public
     * @param  string $mobile 手机号码
     * @return string
     */

    private function _get_user_id($mobile = '')
    {
        $uid  = 0;
        $temp = array();

        $temp['where'] = array(
                            'select' => 'uid',
                            'where' => array('mobile' => $mobile)
                        );

        $uid = (int)$this->c->get_one(self::user, $temp['where']);

        unset($temp);
        return $uid;
    }


    /**
     * 用户投资
     *
     * @access public
     * @param  float    $amount    投资金额
     * @param  string   $borrow_no 借款编号
     * @param  float    $balance   帐户可用余额
     * @return boolean
     */

    public function invest($amount = 0, $borrow_no = '', $balance = 0 , $uid = 0)
    {
        $query = FALSE;
        $temp  = array();

        if( ! empty($amount) && ! empty($borrow_no) && ! empty($balance))
        {
            $temp['where'] = array(
                                'select' => 'rate',
                                'where'  => array('borrow_no' => $borrow_no)
                            );

            $temp['rate'] = $this->c->get_One(self::borrow, $temp['where']);
			$temp['where'] = array(
                                'select' => 'buy_time',
                                'where'  => array('borrow_no' => $borrow_no)
                            );

            $temp['buy_time'] = $this->c->get_One(self::borrow, $temp['where']);
            $temp['uid']            = $uid;

			$temp['where'] = array(
                                'select' => 'firmid,vaccid,real_name',
                                'where'  => array(
                                                'uid' =>  $temp['uid'],
                                            )
                            );
            $temp['usr'] = $this->c->get_row(self::user, $temp['where']);
            if( ! empty($temp['rate']))
            {
				$MarketSerial= $this->c->transaction_no(self::payment, 'payment_no');
				$FirmId = $temp['usr']['firmid'];//$temp['usr']['firmid'];//$FirmId;// 对公账户
				$CustName = $temp['usr']['real_name']; //$temp['usr']['real_name'];//对公账户姓名
				$VaccId = $temp['usr']['vaccid'];//$temp['usr']['vaccid'];//对公账户姓名
				//$configData = $this->pay->touzidongjie($FirmId, $CustName,$VaccId,$MarketSerial,$borrow_no,$amount);
				//现在未做返回状态判断
				// if( ($configData['ReturnInfo']['RtnInfo']=="成功!")&&($amount == $configData['Transfer']['FreezeMoney']))
				// {
					$this->db->trans_start();					
					
					

					// 添加投资记录
					$temp['payment'] = array(
										'payment_no' => $MarketSerial,
										'uid'        => $temp['uid'],
										'type'       => 1,
										'borrow_no'  => $borrow_no,
										'rate'       => $temp['rate'],
										'amount'     => $amount,
										'balance'    => price_format($balance - $amount, 0, FALSE),
										'charge'     => 0,
										'dateline'   => time(),
										'pay_time'   => $temp['buy_time']+60,
										'automatic_type'     => 1,
										'status'     => 1
									);

					$this->c->insert(self::payment, $temp['payment']);

					// 添加资金记录
					$temp['flow'] = array(
										'uid'      => $temp['uid'],
										'type'     => 3,
										'amount'   => $amount,
										'balance'  => price_format($balance - $amount, 0, FALSE),
										'source'   => $temp['payment']['payment_no'],
										'remarks'  => '',
										'dateline' => time()
									);

					$this->c->insert(self::flow, $temp['flow']);

					// 更新收款金额
					$temp['where'] = array('where' => array('borrow_no' => $borrow_no ));
					$temp['data']  = array( 'receive' => '`receive` + '.$amount);

					$this->c->set(self::borrow, $temp['where'], $temp['data']);

					// 更新投资人信息
					$temp['data']  = array(
										'last_investor' => $temp['uid'],
										'last_amount'   => $amount,
										'last_time'     => time()
									);

					$this->c->update(self::borrow, $temp['where'], $temp['data']);

					$this->db->trans_complete();
					$query = $this->db->trans_status();

					if( ! empty($query))
					{
						// 可用余额
						$temp['data'] = array('balance' => round($balance - $amount, 2));
						
						$this->session->set_userdata($temp['data']);

						$this->_set_borrow_status(); // 更新记录状态

					//	$temp['content'] = sprintf('您好，您投资的%s元资金已经冻结。请等待标地结束。', $amount);
						
					//	$this->send_message($temp['uid'], '您好，您投资的金额已经冻结！', $temp['content']);

					//	$this->user->add_user_log('invest', '投资'.price_format($amount).'(项目编号：'.$borrow_no.')');
					}
				// }
            }
        }

        unset($temp);
        return $query;
    }


    /**
     * 更新记录状态
     *
     * @access private
     * @return boolean
     */

    private function _set_borrow_status()
    {
        $query = FALSE;
        $temp = array();

        $temp['data']  = array('status' => 3, 'sort_order' => 900);
        $temp['where'] = array(
                            'where_in' =>array('field' => 'status', 'value' => array(0,2)),
                            'query' =>'`amount` = `receive`'
                        );

        $query = $this->c->update(self::borrow, $temp['where'], $temp['data']);

        unset($temp);
        return $query;
    }

}




