<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cash extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('api/cash_model','cash_API');
    }

    /**
     * ���ݷ��صĴ���
     * @param array $data ����
     */
    protected function _return($data=array()){
        exit(json_encode($data));
    }

    /**
     * ���
     */
    public function get_cash_balance(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_balance($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ���ʲ�
     */
    public function get_cash_total_property(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_total_property($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * �ʽ��¼
     */
    public function get_cash_list(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_list($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ��Ͷ��
     */
    public function get_cash_total_investment(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_total_investment($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ������
     */
    public function get_cash_total_income(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_total_income($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ��������
     */
    public function get_cash_today_income(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_today_income($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * Ԥ������
     */
    public function get_cash_anticipated_income (){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_anticipated_income($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ��Ŀ����
     */
    public function get_cash_project_income(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_project_income($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ���ձ���
     */
    public function get_cash_received_principal(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_received_principal($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ���ձ���
     */
    public function get_cash_not_received_principal(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_not_received_principal($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * Ͷ�ʶ���
     */
    public function get_cash_investment_freeze(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_investment_freeze($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ���ֶ���
     */
    public function get_cash_transaction_freeze(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->cash_API->get_cash_transaction_freeze($temp);

        //���
        unset($temp);
        $this->_return($data);
    }
}