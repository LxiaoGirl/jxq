<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 投资相关处理控制器
 * Class Invest
 */
class Invest extends MY_Controller{

	const page_size = 5;//项目列表 分页数量

	public function __construct(){
		parent::__construct();
		$this->load->model('api/project_model','project');
		$this->load->model('api/cash_model','cash');
	}

	/**
	 * Ͷ投资-项目列表
	 */
	public function index(){
		$data = $temp = array();

		//过滤条件
		$data['category'] 	= $this->input->get('c',TRUE)?(int)$this->input->get('c',TRUE):1;
		$data['rate'] 		= $this->input->get('r',TRUE)?$this->input->get('r',TRUE):'';
		$data['months'] 	= $this->input->get('m',TRUE)?$this->input->get('m',TRUE):'';

		//项目列表
		$temp['page_id'] 	= $this->c->get_page_id(self::page_size);
		$temp['project'] 	= $this->project->get_project_list($data['category'],'',$data['months'],$data['rate'],'','',$temp['page_id'],self::page_size);
		if($temp['project']['status'] == '10000'){
			$data['project']= $temp['project']['data']['data'];
			$data['links'] 	= $this->c->get_links($temp['project']['data']['total'],$temp['page_id'],self::page_size);

			//处理排序
			if($data['project']){
				usort($data['project'],function($a,$b){
					$x=$a['new_status'];
					$y=$b['new_status'];
					if($x == 1)$x=2;
					if($x == 2)$x=1;
					if($y == 1)$y=2;
					if($y == 2)$y=1;
					if($x == $y)return 0;
					return $x<$y?-1:1;
				});
			}
		}

		//项目分类�
		$temp['category'] 	= $this->project->get_project_category();
		if($temp['category']['status'] == '10000'){
			$data['category_list'] = $temp['category']['data'];
		}

		$this->load->view('invest/home',$data);
	}

	/**
	 * Ͷ投资-项目详情
	 */
	public function detail(){
		$data = $temp =array();

		$temp['borrow_no'] = $this->input->get('borrow_no',TRUE)?$this->input->get('borrow_no',TRUE):'';
		if($temp['borrow_no'] == '')redirect('','location');

		$temp['project_info'] = $this->project->get_project_info($temp['borrow_no']);
		if($temp['project_info']['status'] == '10000'){
			$data['project'] = $temp['project_info']['data'];
		}else{
			redirect('','location');
		}

		//获取余额
		if($this->session->userdata('uid')){
			$temp['balance'] = $this->cash->get_user_balance($this->session->userdata('uid'));
			if($temp['balance']['status'] == '10000'){
				$data['balance'] = $temp['balance']['data']['balance'];
			}
		}else{
			$data['balance'] = 0;
		}

		unset($temp);

		if($data['project']['category'] == '聚保宝'){
			$this->load->view('invest/invest_detail_jbb',$data);
		}else{
			$this->load->view('invest/invest_detail',$data);
		}
	}

	/**
	 * 投资-投资的处理ajax
	 */
	public function ajax_invest(){
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->project->project->project_invest($this->session->userdata('mobile'), (float)$this->input->post('amount',TRUE), $this->input->post('security',TRUE),$this->input->post('borrow_no',TRUE));
			exit(json_encode($data));
		}

	}

	/**
	 * 获取项目投资记录的ajax处理方法
	 */
	public function ajax_get_invest_list(){
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->project->get_project_invest_list($this->input->post('borrow_no',TRUE));
			exit(json_encode($data));
		}
	}

	/**
	 * 获取项目还款记录的ajax处理方法
	 */
	public function ajax_get_repay_list(){
		if($this->input->is_ajax_request() == TRUE){
			$data = $this->project->get_project_repayment_list($this->input->post('borrow_no',TRUE));
			exit(json_encode($data));
		}
	}

	/**
	 * 投资榜详细榜单
	 */
	public function ranking_list(){

	}
}