<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 关于我们相关处理 控制器 类
 * Class About
 */

class About extends MY_Controller{
    const article      = 'article';         // 文章
    const announcement = 'announcement';    // 公告
    const page_size    = 10;                //媒体报道 分页数量

	/**
	 *构造函数
     * 加载必要model
	 */
    public function __construct(){
        parent::__construct();
        $this->load->model('api/other_model', 'other');
    }

/******************************************关于我们*********************************************************************/
    /**
     * 关于我们 首页
     *
     * @access public
     * @return void
     */
    public function index(){
        $data = $temp = array();

        //参数处理
        $temp['category']       = item('about_major_event_cat_id')?item('about_major_event_cat_id'):0;
        $temp['page_id']        = 1;
        $temp['page_size']      = 100;
        $temp['order_by']       = 'update_time DESC';
        $temp['keyword']        = '';
        //查询数据
        $temp['company_event']  = $this->other->get_news($temp['category'],$temp['page_id'],$temp['page_size'],$temp['order_by'],$temp['keyword']);

        //数据处理 取具体数据
        if($temp['company_event']['status'] == '10000'){
            $data['data'] = $temp['company_event']['data']['data'];
        }else{
            $data['data'] = array();
        }

        unset($temp);
        $this->load->view('about/company', $data);
    }

    /**
     * 团队
     */
	public function team(){
		$this->load->view('about/team');
	}

    /**
     * 合作伙伴
     */
	public function partners(){
		$this->load->view('about/partner');
	}

    /**
     * 媒体报道
     */
	public function media(){
        $data = $temp = array();
        $data['media'] = array();
        $data['links'] = '';

        //参数处理  分页 分类id
        $data['page_id'] = $this->c->get_page_id(self::page_size);
        $temp['media_cat_id'] = item('about_media_cat_id')?item('about_media_cat_id'):0;
        //查询数据
        $temp['media'] = $this->other->get_news($temp['media_cat_id'],$data['page_id'],self::page_size);

        //处理具体数据 和获取分页
        if($temp['media']['status'] == '10000'){
            $data['media'] = $temp['media']['data']['data'];
            $data['links'] = $this->c->get_links($temp['media']['data']['total'],$data['page_id'],self::page_size);
        }

		$this->load->view('about/media',$data);
	}

    /**
     * 新闻动态
     */
	public function news(){
		$this->load->view('about/news');
	}

    /**
     * 新闻详情
     */
    public function news_detail(){
        $data['data'] = array();
        $temp = array();

        //接收参数id 没有则跳回新闻动态首页
        $temp['id'] = $this->input->get('id');
        if( ! $temp['id']){
            redirect('about/news');
        }

        //根据id查询具体详情
        $temp['detail'] = $this->other->get_news_detail($temp['id']);
        if($temp['detail']['status'] == '10000'){
            $data['data'] = $temp['detail']['data'];
            //上一条和下一条处理
            $temp['prev_next'] = $this->other->get_news_prev_next($temp['id']);
            if($temp['prev_next']['status'] == '10000'){
                $data = array_merge($temp['prev_next']['data'],$data);
            }
        }

        unset($temp);
        $this->load->view('about/news_detail', $data);
    }

    /**
     * 公司资质
     */
    public function certificates(){
        $this->load->view('about/certificates');
    }

    /**
     * 加入我们
     */
	public function join(){
		$this->load->view('about/join');
	}

    /**
     * 联系我们
     *
     * @access public
     * @return void
     */
    public function contact(){
        $this->load->view('about/contact');
    }
/*****************************************关于我们**********************************************************************/


/*****************************************安全保障**********************************************************************/
	/**
	 *安全保障
	 *
	 * @access public
	 * @return void
	 */
	public function safe(){
		$this->load->view('about/safe');
	}
/******************************************安全保障********************************************************************/


/******************************************帮助中心********************************************************************/
	/**
	 * 帮助中心 主页
	 */
	public function help(){
        //获取分类导航数据
        $data['category_list'] = $this->_get_category_list();

        //查询热点问题用的帮助类cat_id 数组
        $temp['search_cat_id_array'] = array();
        if($data['category_list']){
            //循环去cat_id
            foreach($data['category_list'] as $v1){
                $temp['search_cat_id_array'][] = $v1['cat_id'];
                if(isset($v1['child'])){
                    //如果有子分类 循环去子分类cat_id
                    foreach($v1['child'] as $v2){
                        $temp['search_cat_id_array'][] = $v2['cat_id'];
                    }
                }
            }
        }
        $data['cat_id_str'] = implode(',',$temp['search_cat_id_array']);

		$this->load->view('about/help', $data);
	}

    /**
     * 帮助中心-某一分类列表
     */
    public function help_list(){
        $data['cat_id'] = $this->input->get('cat_id',true);

        if($data['cat_id']){
            //获取分类导航数据
            $data['category_list'] = $this->_get_category_list();

            //获取帮助列表具体数据
            $data['help_list'] = $this->other->get_news($data['cat_id'],1,100,'','');
            if($data['help_list']['status'] == '10000'){
                $data['help_list'] = $data['help_list']['data']['data'];
            }

            //获取该分类具体信息 用于显示
            $data['category'] = $this->other->get_news_category_detail($data['cat_id']);
            if($data['category']['status'] == '10000'){
                $data['category'] = $data['category']['data'];
                $data['cat_pid']  = $data['category']['parent_id'];
            }
        }else{
            redirect('about/help');
        }
        $this->load->view('about/help_list', $data);
    }

    /**
     * 帮助中心-帮助信息详情
     */
    public function help_detail(){
        $temp = $data =array();

        $temp['id']     = $this->input->get('id',true);
        $data['cat_id'] = $this->input->get('cat_id',true);

        if($temp['id'] > 0){
            //获取分类导航数据
            $data['category_list'] = $this->_get_category_list();

            //获取帮助的新闻数据
            $data['info'] = $this->other->get_news_detail($temp['id']);
            if($data['info']['status'] == '10000'){
                $data['info'] = $data['info']['data'];
            }

            //获取该分类具体信息 用于显示
            $data['category'] = $this->other->get_news_category_detail($data['cat_id']);
            if($data['category']['status'] == '10000'){
                $data['category'] = $data['category']['data'];
                $data['cat_pid']  = $data['category']['parent_id'];
            }

            $this->_set_help_news_click($temp['id']);
        }else{
            redirect('about/help');
        }

        unset($temp);
		$this->load->view('about/help_detail', $data);
	}

    /**
     * 帮助中心-搜索结果
     */
    public function help_search(){
        $temp = $data =array();

        $data['keyword'] = $this->input->get('keyword',true);
        $data['cat_id']  = '';
        $data['cat_pid'] = '';

        //获取分类导航数据
        $data['category_list'] = $this->_get_category_list();

        if($data['keyword']){
            //获取 帮助类分类的查询用cat_id 数组
            $temp['search_cat_id_array'] = array();
            if($data['category_list']){
                foreach($data['category_list'] as $v1){
                    $temp['search_cat_id_array'][] = $v1['cat_id'];
                    if(isset($v1['child'])){
                        foreach($v1['child'] as $v2){
                            $temp['search_cat_id_array'][] = $v2['cat_id'];
                        }
                    }
                }
            }
            //查询特定关键词的帮助信息
            $temp['news'] = $this->other->get_news(implode(',',$temp['search_cat_id_array']),1,10,'',$data['keyword']);
            if($temp['news']['status'] == '10000'){
                $data['news'] = $temp['news']['data']['data'];
            }
        }else{
            $data['news'] = array();
        }



        unset($temp);
        $this->load->view('about/help_search_list', $data);
    }

    /**
     * 帮助之分类导航数据查询处理
     * @return mixed
     */
    protected function _get_category_list(){
        $temp = array();
        $temp['cat_pid'] = item('help_news_cat_id')?item('help_news_cat_id'):2;
        $temp['category'] = $this->other->get_news_category($temp['cat_pid'] );

        if($temp['category']['status'] == '10000' && $temp['category']['data']){
            $temp['category_id_arr'] = array();
            foreach($temp['category']['data'] as $k=>$v){
                $temp['category_id_arr'][] = $v['cat_id'];
            }

            $temp['category_array_all'] = $this->other->get_news_category(implode(',',$temp['category_id_arr']));
            if($temp['category_array_all']['status'] == '10000' && $temp['category_array_all']['data']){
                foreach($temp['category']['data'] as $k=>$v) {
                    foreach ($temp['category_array_all']['data'] as $k1 => $v1) {
                        if ($v['cat_id'] == $v1['parent_id']){
                            $temp['category']['data'][$k]['child'][]=$v1;
                        }
                    }
                }
            }
        }

        return $temp['category']['data'];
    }

    /**
     * 单击量自增
     * @param int $id
     */
    protected function _set_help_news_click($id=0){
        if($id > 0){
            $this->c->set(self::article,array('where'=>array('id'=>$id)),array('field'=>'rank','value'=>'`rank`+1'));
        }
    }
/*****************************************帮助中心**********************************************************************/


/*****************************************协议**********************************************************************/
	/**
	 * 注册协议
	 *
	 * @access public
	 * @return void
	 */
	public function register_agreement(){
		$data = array();
		$this->load->view('about/register_agreement', $data);
	}

    /**
     * 投资协议
     */
    public function invest_agreement(){
        $data = array();
        $this->load->view('about/invest_agreement', $data);
    }
/***************************************协议************************************************************************/


    /**
     * 获取新闻的ajax方法
     */
    public function ajax_get_news(){
        if($this->input->is_ajax_request() == TRUE){
            $temp = array();
            $temp['category']  = $this->input->post('category',true)?$this->input->post('category',true):0;
            $temp['page_id']   = $this->input->post('page_id',true)?$this->input->post('page_id',true):0;
            $temp['page_size'] = $this->input->post('page_size',true)?$this->input->post('page_size',true):0;
            $temp['order_by']  = $this->input->post('order_by',true)?$this->input->post('order_by',true):0;
            $temp['keyword']   = $this->input->post('keyword',true)?$this->input->post('keyword',true):0;
            $data = $this->other->get_news($temp['category'],$temp['page_id'],$temp['page_size'],$temp['order_by'],$temp['keyword']);
            if($data['status'] == '10000' && $data['data'])$data['data']=$data['data']['data'];
            exit(json_encode($data));
        }
    }


/***************************************公告************************************************************************/
    /**
     * 官方公告列表
     */
    public function announcement(){
        echo '公告列表';
    }

    /**
     * 官方公告详情
     */
    public function announcement_detail(){
        echo '公告详情';
    }
/***************************************公告************************************************************************/

/***************************************新手指引************************************************************************/
    /**
     * 新手指引主页
     */
    public function guide(){
        $this->load->view('about/xinshou_1');
    }

    /**
     * 新手常见问题
     */
    public function guide_common_problem(){
        $this->load->view('about/xinshou_2');
    }
/***************************************新手指引************************************************************************/
	/**
     * 手机APP下载
     *
     */
    public function download()
    {
        $this->load->view('download/download');
    }
}