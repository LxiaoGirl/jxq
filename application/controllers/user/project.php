<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends Api_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('api/project_model','project_API');
		$this->load->model('api/user_model','userinfo_API');
    }

    /**
     * ���ݷ��صĴ���
     * @param array $data ����
     */
    protected function _return($data=array()){
        $this->api_return($data);
    }


    public function login(){
        $data = $temp = array();

        //����
          $data = $this->userinfo_API->login();
		  
        //���
        unset($temp);
        $this->_return($data);
    }
    /**
     * ��� ��Ŀ�б�
     * ��Ҫ����:page_id ��ҳ  page_size ҳ�� category��� status ��Ϊ�������� Ҳ��Ϊ �Զ������ӵ��ַ���
     * months�·ݷ�Χ ��Ϊ���� ��Ϊx-x��ʽ rate ͬ�·�
     */
    public function get_project_list(){
        $data = $temp = array();

        //���ܲ���
        $temp['page_id']  = isset($_POST['page_id'])?$this->input->post('page_id', TRUE):1;//ҳ��
        $temp['page_size']  = isset($_POST['page_size'])?$this->input->post('page_size', TRUE):10;//ҳ��
        $temp['category']  = isset($_POST['category'])?$this->input->post('category', TRUE):'';//
        $temp['status']  = isset($_POST['status'])?$this->input->post('status', TRUE):'';//״̬
        $temp['months']  = isset($_POST['months'])?$this->input->post('months', TRUE):'';//����
        $temp['rate']  = isset($_POST['rate'])?$this->input->post('rate', TRUE):'';//����
        $temp['mode']  = isset($_POST['mode'])?$this->input->post('mode', TRUE):'';//ģʽ
        $temp['type']  = isset($_POST['type'])?$this->input->post('type', TRUE):'';//����

        //����
        $data = $this->project_API->get_project_list($temp['page_id'],$temp['page_size'],$temp['category'],$temp['status'],$temp['months'],$temp['rate'],$temp['mode'],$temp['type']);
        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ��ȡ ��Ŀ����
     * ��ѡ���� category_id ��Ϊ��ʱ ��ѯ��category��Ϣ  Ϊ��ʱ��ѯȫ��
     */
    public function get_project_category(){
        $data = $temp = array();

        //���ܲ���
        $temp['category_id']  = isset($_POST['category_id'])?$this->input->post('category_id', TRUE):0;

        //����
        $data = $this->project_API->get_project_category($temp['category_id']);

        //���
        unset($temp);
        $this->_return($data);
    }

    /**
     * ��ȡ �ض�id�µ���Ŀ����
     */
    public function get_project_info(){
        $data = $temp = array();

        //���ܲ���
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //����
        $data = $this->project_API->get_project_info($temp['borrow_no']);

        //���
        unset($temp);
        $this->_return($data);
    }

    public function project_invest(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->project_API->project_invest($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

	/**
	 * ��ȡ��Ŀ Ͷ�ʼ�¼
	 * ��Ҫ���� borrow_no
	 */
    public function get_project_invest_list(){
        $data = $temp = array();

        //���ܲ���
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //����
        $data = $this->project_API->get_project_invest_list($temp['borrow_no']);

        //���
        unset($temp);
        $this->_return($data);
    }

	/**
	 * ��ȡ��Ŀ�����¼
	 * ��Ҫ���� borrow_no
	 */
    public function get_project_repayment_list(){
        $data = $temp = array();

        //���ܲ���
        $temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

        //����
        $data = $this->project_API->get_project_repayment_list($temp['borrow_no']);

        //���
        unset($temp);
        $this->_return($data);
    }

	/**
	 * ��ȡ��Ŀ����
	 * ��Ҫ���� borrow_no
	 */
	public function get_project_attachment(){
		$data = $temp = array();

		//���ܲ���
		$temp['borrow_no']  = isset($_POST['borrow_no'])?$this->input->post('borrow_no', TRUE):'';

		//����
		$data = $this->project_API->get_project_attachment($temp['borrow_no']);

		//���
		unset($temp);
		$this->_return($data);
	}


    public function get_project_apply(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->project_API->get_project_apply($temp);

        //���
        unset($temp);
        $this->_return($data);
    }

    public function get_project_apply_category(){
        $data = $temp = array();

        //���ܲ���
        $temp['account']  = $this->input->post('account', TRUE);

        //����
        $data = $this->project_API->get_project_apply_category($temp);

        //���
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