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

class Apply extends Login_Controller
{

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('borrow/apply_model', 'apply');
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
        $data = $this->apply->show();
        $this->c->cache_clean();
        $this->c->add_cache('apply_excel_data',$data['data'],600);
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/apply', $data);
    }

    /**
     * 申请详情
     *
     * @access public
     * @return void
     */

    public function detail()
    {
        $data = $this->apply->get_apply_detail();
		$data['sidebar']=$this->user->get_node_navigation();
        $this->load->view('borrow/apply_detail', $data);
    }

    /**
     * 下载 2015.5.13
     */
    public function down(){
        $query=$temp=array();

        $temp['data']=$this->c->get_cache('apply_excel_data');
        $temp['region']=$this->c->get_all('region');
        if($temp['region']){
            $temp['new_region']=array();
            foreach($temp['region'] as $v){
                $temp['new_region'][$v['region_id']]=$v['region_name'];
            }
        }
        $temp['title']=array(
                'apply_no'=>'ID',
                'user_name'=>'客户姓名',
                'mobile'=>'手机号码',
                'type'=>'借款主体',
                'amount'=>'所需资金',
                'dateline'=>'洽谈时间',
                'province'=>'省份',
                'city'=>'城市',
                'district'=>'区',
                'p_type'=>'借款类型'
        );
        if(! empty($temp['data'])){
            $query[0]=$temp['title'];
            $temp['idArr']=array();
            foreach ($temp['data'] as $k=>$v) {
                $v['type']=nature($v['type']);
                $v['p_type']=borrow_type($v['p_type']);
                $v['dateline']=date('Y-m-d H:i:s',$v['dateline']);
                $v['province']=$temp['new_region'][$v['province']];
                $v['city']=$temp['new_region'][$v['city']];
                $v['district']=$temp['new_region'][$v['district']];
                unset($v['id']);
                unset($v['from']);
                unset($v['operator']);
                unset($v['add_time']);
                unset($v['update_time']);
                unset($v['status']);
                $query[]=$v;
                $temp['idArr'][]=$v['id'];
            }
            $this->c->write_excel($query);

            $temp['idStr']=implode(',',$temp['idArr']);

            $temp['where'] = array(
                'where'    => array('status' => 0),
                'where_in' => array(
                    'field' => 'id',
                    'value' => $temp['idStr']
                )
            );
            $temp['set']   = array('status' => 1);
            $this->c->update('borrow_apply', $temp['where'], $temp['set']);
        }

        unset($temp);
    }
}