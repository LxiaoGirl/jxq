<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('api/cash_model','cash_API');
    }

    /**
     * 数据返回的处理
     * @param array $data 数据
     */
    protected function _return($data=array()){
        exit(json_encode($data));
    }

    /**
     * 余额
     */
    public function get_cash_balance(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_balance($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 总资产
     */
    public function get_cash_total_property(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_total_property($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 资金记录
     */
    public function get_cash_list(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_list($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 总投资
     */
    public function get_cash_total_investment(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_total_investment($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 总收益
     */
    public function get_cash_total_income(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_total_income($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 今日收益
     */
    public function get_cash_today_income(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_today_income($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 预期收益
     */
    public function get_cash_anticipated_income (){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_anticipated_income($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 项目收益
     */
    public function get_cash_project_income(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_project_income($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 已收本金
     */
    public function get_cash_received_principal(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_received_principal($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 待收本金
     */
    public function get_cash_not_received_principal(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_not_received_principal($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 投资冻结
     */
    public function get_cash_investment_freeze(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_investment_freeze($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }

    /**
     * 提现冻结
     */
    public function get_cash_transaction_freeze(){
        $data = $temp = array();

        //接受参数
        $temp['account']  = $this->input->post('account', TRUE);

        //处理
        $data = $this->cash_API->get_cash_transaction_freeze($temp);

        //输出
        unset($temp);
        $this->_return($data);
    }
}