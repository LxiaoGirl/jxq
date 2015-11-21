<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends Api_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('api/project_model','project_API');
		$this->load->model('api/user_model','userinfo_API');
    }

    /**
     * 数据返回的处理
     * @param array $data 数据
     */
    protected function _return($data=array()){
        $this->api_return($data);
    }


    public function login(){
        $data = $temp = array();

        //处理
          $data = $this->userinfo_API->login();
		  
        //输出
        unset($temp);
        $this->_return($data);
    }
    /**
     * 获得 项目列表
     * 需要参数:page_id 分页  page_size 页数 category类别 status 可为单个数字 也可为 以逗号连接的字符串
     * months月份范围 可为单个 可为x-x形式 rate 同月份
     */
    public function get_project_list(){
        $data = $temp = array();

        //接受参数
        $temp['page_id']  = isset($_POST['page_id'])?$this->input->post('page_id', TRUE):1;//页码
        $temp['page_size']  = isset($_POST['page_size'])?$this->input->post('page_size', TRUE):10;//页数
        $temp['category']  = isset($_POST['category'])?$this->input->post('category', TRUE):'';//
        $temp['status']  = isset($_POST['status'])?$this->input->post('status', TRUE):'';//状态
        $temp['months']  = isset($_POST['months'])?$this->input->post('months', TRUE):'';//期限
        $temp['rate']  = isset($_POST['rate'])?$this->input->post('rate', TRUE):'';//利率
        $temp['mode']  = isset($_POST['mode'])?$this->input->post('mode', TRUE):'';//模式
        $temp['type']  = isset($_POST['type'])?$this->input->post('type', TRUE):'';//类型

        //处理
        $data = $this->project_API->get_project_list($temp['page_id'],$temp['page_size'],$temp['category'],$temp['status'],$temp['months'],$temp['rate'],$temp['mode'],$temp['type']);
        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 获取 项目类型
     * 可选参数 category_id 不为空时 查询该category信息  为空时查询全部
     */
    public function get_project_category(){
        $data = $temp = array();

        //接受参数
        $temp['category_id']  = isset($_POST['category_id'])?$this->input->post('category_id', TRUE):0;

        //处理
        $data = $this->project_API->get_project_category($temp['category_id']);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 获取 特定id下的项目详情
     */
    public function get_project_info(){
        $data = $temp = array();

        //接受参数
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //处理
        $data = $this->project_API->get_project_info($temp['borrow_no']);

        //输出
        unset($temp);
        $this->_return($data);
    }

    public function project_invest(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->project_API->project_invest($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

	/**
	 * 获取项目 投资记录
	 * 需要参数 borrow_no
	 */
    public function get_project_invest_list(){
        $data = $temp = array();

        //接受参数
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //处理
        $data = $this->project_API->get_project_invest_list($temp['borrow_no']);

        //输出
        unset($temp);
        $this->_return($data);
    }

	/**
	 * 获取项目还款记录
	 * 需要参数 borrow_no
	 */
    public function get_project_repayment_list(){
        $data = $temp = array();

        //接受参数
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //处理
        $data = $this->project_API->get_project_repayment_list($temp['borrow_no']);

        //输出
        unset($temp);
        $this->_return($data);
    }

	/**
	 * 获取项目附件
	 * 需要参数 borrow_no
	 */
	public function get_project_attachment(){
		$data = $temp = array();

		//接受参数
		$temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

		//处理
		$data = $this->project_API->get_project_attachment($temp['borrow_no']);

		//输出
		unset($temp);
		$this->_return($data);
	}


    public function get_project_apply(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->project_API->get_project_apply($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    public function get_project_apply_category(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->project_API->get_project_apply_category($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

	public function get_project_borrow_total(){

	}
	public function get_project_invest_total(){

	}
	public function get_project_interest_total(){

	}

	public function get_project_user_total(){

	}
}