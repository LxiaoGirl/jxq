<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 帮助类 新闻类 处理model
 * Class Other_model
 */
class Other_model extends CI_Model{
	const article = 'article'; // 新闻
	const article_category = 'article_category'; // 新闻分类
	const product = 'product_content';//聚保宝投资详情

	private $_page_size = '10';//分页每页记录数

    public function __construct(){
        parent::__construct();
    }

	/**
     * 运行天数
     *
    */
	public function Running_days($system_run_date = '2015-06-12'){
		$data = $temp = array();
		$data = array('status'=>'10001','msg'=>'没有相关信息!','data'=>array());
		$temp['system_run_date'] = $system_run_date;
		$temp['system_run_date'] = strtotime($temp['system_run_date']);
		$temp['now'] = time();
		$data['date'] = ceil(($temp['now'] - $temp['system_run_date'])/3600/24);
		if($data['data']>0){
			$data = array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => array(
					'Running_days' => $data['date']
				)
				);
		}else{
			$data['msg'] = '系统错误！';		
		}
		unset($temp);
		return $data;
	 }

	/**
     * 新闻(带轮播图)  轮播图 32
     *$cat_id 新闻归类
	 *$limit 条数
	 *$order 排序 （asc desc）
	 *$lie 排序名称
    */
	public function news($cat_id='',$limit='',$order='',$lie='id'){
		$data = $temp = array();
		$data = array('status'=>'10001','msg'=>'没有相关信息!','data'=>array());
		$temp['where'] = array(
			'select' => 'id,title,cat_id,link_url,source,content'
		);
		
		if(!empty($limit)){
			$temp['where']['limit']=$limit;
		}
		if(!empty($order)){
			$temp['where']['order_by']=$lie.' '.$order;
		}
		if(!empty($cat_id)){
			$temp['where']['where']=array('cat_id'=> $cat_id);
		}
		$temp['user']  = $this->c->get_all(self::article, $temp['where']);	
		if(!empty($temp['user'])){
			$data= array(
				'status' => '10000',
				'msg' => 'ok',
				'data' => $temp['user']
				);
		}
		
		
		unset($temp);
		return $data;
	}




	/**
     * 聚保宝投资详情列表
     *
    */
	public function jbb_details($type_code = ''){
		$data = $temp = array();
		$data = array('status'=>'10001','msg'=>'没有相关信息!','data'=>array());
		$temp['where'] = array(
			'select' => '*',
			'where'  => array(
				'type_code' => $type_code	
			)
		);
		if($type_code != ''){
		$temp['details']  = $this->c->get_all(self::product, $temp['where']);
		if(!empty($temp['details'])){
			$data= array(
				'status' => '10000',
				'msg'	 => 'ok',
				'data'	 => $temp['details'] 
				);
		}
		}
		unset($temp);
		return $data;
	}

	/**
     * 爱心公益基金(未定)
     *
    */
	public function Charity_Fund(){

	}

/*********************************wsb-2015-11-04********************************************/

	/**
	 * 获取新闻列表
	 * @param string $cat_id 分类id
	 * @param int $page_id 页码
	 * @param int $page_size 页记录
	 * @param string $order_by 排序
	 * @param string $keyword 关键字
	 * @param array $condition
	 * @return array
	 */
	public function get_news($cat_id='',$page_id=0,$page_size=0,$order_by='',$keyword='',$condition=array()){
		$temp = array();
		$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array('data'=>array()));
		$this->_set_cutpage_params($page_id,$page_size);

		$temp['where'] =array(
			'where'=>array('status'=>1),
			'select'=>'id,title,description,link_url,add_time,cat_id,source,update_time,content',
			'order_by'=>'sortrank DESC,update_time DESC'
		);

		if($cat_id){
			if(is_string($cat_id) && strpos($cat_id,',')){
				$cat_array = explode(',',$cat_id);
				$temp['where']['where_in'] = array(
						'field'=>'cat_id',
						'value'=>$cat_array
				);
			}else{
				if(is_numeric($cat_id) && $cat_id > 0){
					$temp['where']['where']['cat_id'] = $cat_id;
				}
			}
		}

		if($order_by != '')$temp['where']['order_by'] = $order_by;
		if($keyword != '')$temp['where']['like'] = array('field'=>'title','match'=>$keyword,'flag'=>'both');

		if($condition)$temp['where']['where'] = array_merge($temp['where']['where'],$condition);

		$temp['data'] = $this->c->show_page(self::article,$temp['where']);

		if($temp['data'] && $temp['data']['data']){
			unset($temp['data']['links']);
			$data['data'] = $temp['data'];
			$data['status'] = '10000';
			$data['msg'] = 'ok!';
		}else{
			$data['status'] = '10000';
			$data['msg'] = '暂无相关信息!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取新闻详情
	 * @param int $id
	 * @return array
	 */
	public function get_news_detail($id=0){
		$temp = array();
		$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		if($id > 0){
			$temp['data'] = $this->c->get_row(self::article,array('where'=>array('id'=>$id)));
			if($temp['data']){
				$data['data'] = $temp['data'];
				$data['status'] = '10000';
				$data['msg'] = 'ok!';
			}else{
				$data['status'] = '10000';
				$data['msg'] = '暂无相关信息!';
			}
		}else{
			$data['msg'] = '新闻id为空!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取新闻分类信息列表
	 * @param int $pid 父级id
	 * @return array
	 */
	public function get_news_category($pid=''){
		$temp = array();
		$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		$temp['where'] = array('order_by'=>'sort_order desc,cat_id desc');

		if($pid){
			if(is_string($pid) && strpos($pid,',')){
				$pid_array = explode(',',$pid);
				$temp['where']['where_in'] = array(
						'field'=>'parent_id',
						'value'=>$pid_array
				);
			}else{
				if(is_numeric($pid) && $pid >= 0){
					$temp['where']['where']['parent_id'] = $pid;
				}
			}
		}

		$temp['data'] = $this->c->get_all(self::article_category,$temp['where']);
		if($temp['data']){
			$data['data'] = $temp['data'];
			$data['status'] = '10000';
			$data['msg'] = 'ok!';
		}else{
			$data['status'] = '10000';
			$data['msg'] = '暂无相关信息!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取新闻特定分类的具体详情
	 * @param int $cat_id
	 * @return array
	 */
	public function get_news_category_detail($cat_id=0){
		$temp = array();
		$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		if($cat_id > 0){
			$data['data'] = $this->c->get_row(self::article_category,array('where'=>array('cat_id'=>$cat_id)));
			if($data['data']){
				$data['status'] = '10000';
				$data['msg'] = 'ok!';
			}else{
				$data['status'] = '10000';
				$data['msg'] = '暂无相关信息!';
			}
		}else{
			$data['msg'] = '分类id为空!';
		}

		unset($temp);
		return $data;
	}

	/**
	 * 获取当前新闻的上一条和下一条
	 * @param int $id
	 * @param int $cat_id
	 * @return array
	 */
	public function get_news_prev_next($id=0,$cat_id=10){
		$temp = array();
		$data = array('status'=>'10001','msg'=>'服务器繁忙请稍后重试!','sign'=>'','data'=>array());

		if($id > 0){
			$data['data']['prev'] = $this->c->get_row(self::article,array('where'=>array('cat_id'=>$cat_id,'status'=>1,'id <'=>$id),'select'=>'id,cat_id,title,link_url','order_by'=>'id DESC'));
			$data['data']['next'] = $this->c->get_row(self::article,array('where'=>array('cat_id'=>$cat_id,'status'=>1,'id >'=>$id),'select'=>'id,cat_id,title,link_url','order_by'=>'id ASC'));
			if($data['data']){
				$data['status'] = '10000';
				$data['msg'] = 'ok!';
			}else{
				$data['status'] = '10000';
				$data['msg'] = '暂无相关信息!';
			}
		}else{
			$data['msg'] = '新闻id为空!';
		}
		unset($temp);
		return $data;
	}

	/**
     * 设置修正分页的参数
     * @param int $page_id
     * @param int $page_size
     */
    protected function _set_cutpage_params($page_id=0,$page_size=0){
		if(isset($_GET['limit']) && !$page_size){
			$page_size = (int)$this->input->get('limit');
		}else{
			if( !$page_size || !is_numeric($page_size))$page_size = $this->_page_size;
			$_GET['limit'] = (int)$page_size;
		}

		if( !isset($_GET['per_page'])){
			if(!is_numeric($page_id) || $page_id<=0)$page_id=1;
			$_GET['per_page'] = (((int)$page_id-1)*(int)$page_size);
		}
    }

	/***** 爱心公益相关 *****/
	/**
	 * 获取爱心公益项目列表
	 * @param int $page_id
	 * @param int $page_size
	 * @param string $order_by
	 * @return array
	 */
	public function get_commonweal($page_id=0,$page_size=0,$order_by=''){
		$data = array('name'=>'爱心公益项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试','data'=>array('data'=>array()));
		$temp = array();

		unset($temp);
		return $data;
	}

	public function get_commonweal_detail($id=0){
		$data = array('name'=>'爱心公益项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试','data'=>array('data'=>array()));
		$temp = array();

		unset($temp);
		return $data;
	}

	public function get_commonweal_log($id=0){
		$data = array('name'=>'爱心公益项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试','data'=>array('data'=>array()));
		$temp = array();

		unset($temp);
		return $data;
	}

	public function get_commonweal_total(){
		$data = array('name'=>'爱心公益项目列表','status'=>'10001','msg'=>'服务器繁忙请稍后重试','data'=>array('data'=>array()));
		$temp = array();

		unset($temp);
		return $data;
	}
	/***** 爱心公益相关 *****/

/*********************************wsb-2015-11-04********************************************/
}