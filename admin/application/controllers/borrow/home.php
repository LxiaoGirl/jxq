<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 借款申请
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2014-10-03
 * @updated     2014-10-03
 * @version     1.0.0
 */

class Home extends Login_Controller
{
    const user = 'user'; // 会员表
	const borrow = 'borrow'; // 借款表
	const automatic = 'user_automatic';//自动投标

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('borrow/borrow_model', 'borrow');
        $this->load->model('other/pcategory_model', 'category');
		$this->load->model('user/user_model', 'user');
    }

    /**
     * 首页
     *
     * @access public
     * @return void
     */

    public function index()
    {
        $data = $this->borrow->show();
        $data['productcategory']=$this->c->get_all('product_category');
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/home', $data);
    }

    /**
     * 债权转让合同编辑
     *
     * @access public
     * @return void
     */

    public function claims()
    {
        $data = $temp = array();

        $this->load->library('form_validation');

        $temp['borrow_no']	= $this->input->get('borrow_no', TRUE);

		if(empty($temp['borrow_no']))
		{
			redirect('borrow', 'refresh');
		}

        $temp['submit'] = $this->input->post('submit', TRUE);
        if( ! empty($temp['submit']))
        {
			$query = $this->borrow->set_claims();

			if( ! empty($query))
            {
                redirect('borrow', 'refresh');
            }
        }

        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_claims', $data);
    }

    /**
     * 委托合同编辑
     *
     * @access public
     * @return void
     */

    public function agreement()
    {
        $data = $temp = array();

        $this->load->library('form_validation');

        $temp['borrow_no']	= $this->input->get('borrow_no', TRUE);

		if(empty($temp['borrow_no']))
		{
			redirect('borrow', 'refresh');
		}

        $temp['submit'] = $this->input->post('submit', TRUE);
        if( ! empty($temp['submit']))
        {
			$query = $this->borrow->set_agreement();

			if( ! empty($query))
            {
                redirect('borrow', 'refresh');
            }
        }

        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_agreement', $data);
    }

    /**
     * 抵押物信息
     *
     * @access public
     * @return void
     */

    public function collateral()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run('borrow/collateral') == TRUE)
        {
            $query = $this->borrow->collateral();

           if( ! empty($query))
           {
                redirect('borrow', 'refresh');
           }
        }

        $data = $this->borrow->get_borrow_info();
        $data['collateral'] = $this->borrow->get_collateral_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_collateral', $data);
    }

    /**
     * 发布标的
     *
     * @access public
     * @return void
     */

    public function create()
    {
        $data = array();
        $data = $this->category->show();

        $this->load->library('form_validation');

        if($this->form_validation->run('borrow/create') == TRUE)
        {
            $query = $this->borrow->create();

           if( ! empty($query))
           {
                redirect('borrow', 'refresh');
           }
        }
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_form', $data);
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

        if($this->form_validation->run('borrow/update') == TRUE)
        {
            $query = $this->borrow->update();

           if( ! empty($query))
           {
                redirect('borrow', 'refresh');
           }
        }

        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_form', $data);
    }

    /**
     * 修改记录
     *
     * @access public
     * @return void
     */

    public function modify()
    {
        $data = array();

        $this->load->library('form_validation');

        if($this->form_validation->run() == TRUE)
        {
            $query = $this->borrow->modify();

           if( ! empty($query))
           {
                redirect('borrow', 'refresh');
           }
        }

        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_modify', $data);
    }

    /**
     * 删除借款记录
     *
     * @access public
     * @return void
     */

    public function delete()
    {
        $this->borrow->delete();
        redirect('borrow', 'refresh');
    }

    /**
     * 借款祥情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_detail', $data);
    }

    /**
     * 资料管理
     *
     * @access public
     * @return void
     */

    public function attachment()
    {
        $data = $this->borrow->get_borrow_info();
        $data['attachment'] = $this->borrow->get_attachment_list();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_attachment', $data);
    }

    /**
     * 删除文件
     *
     * @access public
     * @return void
     */

    public function remove()
    {
        $url  = 'borrow';
        $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        $this->borrow->remove();

        if( ! empty($temp['borrow_no']))
        {
            $url = 'borrow/home/attachment?borrow_no='.$temp['borrow_no'];
        }

        unset($temp);
        redirect($url, 'refresh');
    }

    /**
     * 资料上传
     *
     * @access public
     * @return void
     */

    public function upload()
    {
        $url  = '';
        $temp = array();

        $temp['borrow_no'] = $this->input->get('borrow_no', TRUE);

        $query = $this->borrow->upload();

        if( ! empty($query))
        {
            $url  = 'borrow';

            if( ! empty($temp['borrow_no']))
            {
                $url = 'borrow/home/attachment?borrow_no='.$temp['borrow_no'];
            }

            redirect($url, 'refresh');
        }

        $data = $this->borrow->get_borrow_info();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/borrow_upload', $data);
    }

    /**
     * 借款审核
     *正常投标
     * @access public
     * @return void
     */

    public function verify()
    {
        $this->borrow->verify();
        redirect('borrow/home', 'refresh');
    }

	 /**
     * 借款审核
     *自动投标
     * @access public
     * @return void
     */

    public function verify_form()
    {
      $query = $this->borrow->verify_form();;
      redirect('borrow/home', 'refresh');  
    }

    /**
     * 借款审核
     *
     * @access public
     * @return void
     */

    public function verify_do()
    {
	   $temp=array();
	   $data['borrow_no']=$this->input->get('borrow_no', TRUE);
	   $temp['where'] = array(
                                'select' => 'uid,type,amount,months,subject,rate,lowest,productcategory',
                                'where'  => array('borrow_no' => $data['borrow_no'], 'status' => 0)
                            );

		$temp['data'] = $data['data']  = $this->c->get_row(self::borrow,$temp['where']);
		$lowset=$temp['data']['lowest'];
		$temp['where_balance'] = array(
                                'select' => 'sum(floor(balance_ye/'.$lowset.')*'.$lowset.') as balance_ye_all',
                                'where'  => array( 'statue' => 1 , 'jk_min <=' => $temp	 ['data']['months'] , 'jk_max >=' => $temp['data']['months'] , 'sy_min <= ' => $temp['data']['rate'], 'sy_max >=' =>$temp['data']['rate'] , 'pzsj_start <=' => time() , 'pzsj_end >=' => time()),
								'query' =>'`balance` >= `balance_ye`',
								'where_in' => array('field' => 'type', 'value' => array(0,$temp['data']['productcategory']))			
                            );
	   $data['allbalance']  = $this->c->get_row(self::automatic,$temp['where_balance']);
	   $data['sidebar']=$this->user->get_node_navigation();
	   unset($temp);
       $this->load->view('borrow/borrow_do', $data);
    }

    /**
     * 获取手机号码
     *
     * @access public
     * @return string
     */

    public function mobile()
    {
        $data = $temp = array();

        $temp['q'] = $this->input->get('q', TRUE);

        if( ! empty($temp['q']))
        {
            $temp['where'] = array(
                                'select' => 'mobile,user_name,real_name',
                                'like'   => array('field' => 'mobile', 'match' => $temp['q']),
                                'limit'  => 10
                            );

            $data  = $this->c->get_all(self::user, $temp['where']);
        }

        unset($temp);
        exit(json_encode($data));
    }

    /**
     * 借款审核
     *
     * @access public
     * @return void
     */

    public function finish()
    {
        $this->borrow->finish();
        redirect('borrow/home', 'refresh');
    }

    /**
     * 验证实收利率
     *
     * @access public
     * @param  string  $rate 实收利率
     * @return boolean
     */

    public function real_rate($rate = 0)
    {
        $query = FALSE;
        $temp  = array();

        $temp['rate'] = $this->input->post('rate', TRUE);

        if($temp['rate'] <= $rate)
        {
             $query = TRUE;
        }

        unset($temp);
        return $query;
    }

    /**
     * 验证手机号码
     *
     * @access public
     * @param  string  $mobile 手机号码
     * @return boolean
     */

    public function is_valid_mobile($mobile = '')
    {
        $query = FALSE;
        $temp  = array();

        if(preg_match('/^1[345789](\d){9}$/', $mobile) == TRUE)
        {
            $temp['where'] = array('where' => array('mobile' => $mobile));
            $temp['count'] = $this->c->count(self::user, $temp['where']);

            $query = ( ! empty($temp['count'])) ? TRUE : FALSE;
        }

        unset($temp);
        return $query;
    }

    /**
     * 验证日期格式
     *
     * @access public
     * @param  string  $date 日期
     * @return boolean
     */

    public function is_valid_date($date = '')
    {
        return (strtotime($date) !== FALSE) ? TRUE : FALSE;
    }
}