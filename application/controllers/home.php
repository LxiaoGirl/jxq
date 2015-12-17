<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 主页相关控制器
 * Class Home
 */
class Home extends MY_Controller{

	/**
	 * 构造函数加载必要model
	 * Home constructor.
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('api/project_model','project');
		$this->load->model('api/cash_model','cash');
	}

	/**
	 * 主页
	 */
	public function index(){
		$data = $temp = array();

		//项目列表�
		$temp['category'] = $this->project->get_project_category();
		if($temp['category']['status'] == '10000' && $temp['category']['data']){
			$data['category'] = $temp['category']['data'];
			foreach($data['category'] as $key=>$val){
				$temp['project'] = $this->project->get_project_list($val['cat_id'],'2,3,4,7','','','','',1,1,0);
				if($temp['project']['status'] == '10000' && $temp['project']['data']['data']){
					$data['category'][$key]['project'] = $temp['project']['data']['data'];
				}
			}
		}

		//资金统计
		$temp['total'] = $this->cash->get_cash_total();
		if($temp['total']['status'] == '10000'){
			$data['total'] = $temp['total']['data'];
		}

		//公益基金
		$data['public_fund'] = '100000';

		//活动标
		$temp['active_project'] = $this->project->get_project_list('','2,3,4,7','','','','',1,1,1);
		if($temp['active_project']['status'] == '10000' && $temp['active_project']['data']['data']){
			$temp['active_category'] = array(
				'cat_id'=>1,
				'category'=>'活动标',
				'project'=>$temp['active_project']['data']['data']
			);
			array_unshift($data['category'],$temp['active_category']);
		}

		$this->load->view('home',$data);
	}

	/**
	 * 获取投资榜信息ajax
	 */
	public function ajax_get_user_invest_total_list(){
		if($this->input->is_ajax_request() == TRUE) {
			$temp = $data = array();
			//月投资 type='month' 总投资 为''或不传
			$temp['type'] = $this->input->post('type', true) ? $this->input->post('type', true) : '';

			if($temp['type'] == 'month'){
				//获取上一个月投资榜信息
				$temp['invest_total_list_month'] = $this->cash->get_user_invest_total_list(date('Ym', strtotime("-1 month")), 1, 5);
				if ($temp['invest_total_list_month']['status'] == '10000') {
					$data['data'] = $temp['invest_total_list_month']['data']['data'];
				}
			}else{
				//获取投资总榜信息
				$temp['invest_total_list'] = $this->cash->get_user_invest_total_list('', 1, 5);
				if ($temp['invest_total_list']['status'] == '10000') {
					$data['data'] = $temp['invest_total_list']['data']['data'];
				}
			}

			unset($temp);
			exit(json_encode($data));
		}
	}
}