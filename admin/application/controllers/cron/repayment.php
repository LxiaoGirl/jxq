<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户还款
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-09-29
 * @updated     2014-09-29
 * @version     1.0.0
 */
class Repayment extends MY_Controller
{
    /**
     * 初始化
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('cron/repayment_model', 'repayment');
        $this->load->model('cron/interest_model', 'interest');
		$this->load->model('user/user_model', 'user');
    }

    /**
     * wsb-2015.5.16
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data['data'] = $this->repayment->show();
        $data['productcategory']=$this->c->get_all('product_category');
        $data['productcategory_select']=isset($_GET['productcategory'])?$this->input->get('productcategory',true):'';

        if( ! empty($data['data'])){
            $borrow_idarr=array();
            foreach($data['data'] as $v){
                $borrow_idarr[]=$v['borrow_no'];
            }
            //$data['interest']=$this->interest->get_interest_list($borrow_idarr);
        }
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('cron/repayment',$data);
    }
	
	public function detail()
    {
        $data['data'] = $this->repayment->show_one();
        $data['productcategory']=$this->c->get_all('product_category');
        $data['productcategory_select']=isset($_GET['productcategory'])?$this->input->get('productcategory',true):'';
        $data['interest']=$this->interest->get_interest_list($data['data']['borrow_no']);
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('cron/detail',$data);
    }

    /**
     * wsb-2015.5.16
     * 一键全部还款
     *
     */
    public function repay(){
        //$query= $this->repayment->processing();
        //if($query)$this->interest->processing();
		$this->interest->processing();
       // redirect('cron/repayment/index','refresh');

    }

    /**
     * 单个还款
     */
    public function repay_one(){
        //$query= $this->repayment->processing($this->input->get("borrow_no",true));
        //if($query)$this->interest->processing($this->input->get("borrow_no",true));
        $this->interest->processing($this->input->get("borrow_no",true));
       // redirect('cron/repayment/index','refresh');
    }
}