<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * api用户管理
 */

class Home extends Login_Controller
{
    const API_USER = 'api_user'; // 后台用户

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('apiuser/apiuser_model', 'apiuser');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->apiuser->show();
        $this->load->view('apiuser/home', $data);
    }

    /**
     * 创建用户
     *
     * @access public
     * @return void
     */

    public function create()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run('apiuser/create') == TRUE)
        {
            $query = $this->apiuser->create();

            if( ! empty($query))
            {
                redirect('apiuser', 'refresh');
            }
        }

        $this->load->view('apiuser/user_form', $data);
    }

    /**
     * 修改记录
     *
     * @access public
     * @return void
     */

    public function update()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run('apiuser/update') == TRUE)
        {
            $query = $this->apiuser->update();

            if( ! empty($query))
            {
                redirect('apiuser', 'refresh');
            }
        }

        $data = $this->apiuser->get_apiuser_info();

        $this->load->view('apiuser/user_form', $data);
    }
    public function status()
    {
        $this->apiuser->set_user_status();
        redirect('apiuser', 'refresh');
    }
}