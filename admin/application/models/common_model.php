<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 公用模型
 *
 * 封装常用的方法以及数据库操作方法，请勿随意修改此文件！
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.1.0
 */
class Common_model extends CI_Model
{
    private $_is_cache = FALSE; // 开启缓存

    /**
     * 初始化
     *
     * @access public
     * @return void
     */

    public function __construct()
    {
        $this->_is_cache = $this->config->item('is_cache');

        if (!empty($this->_is_cache)) {
            $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        }
    }

    /**
     * 数据缓存
     *
     * @access public
     * @param  string $key 键名
     * @param  array $user_data 键值
     * @param  integer $ttl 存活期
     * @return boolean
     */

    public function add_cache($key = '', $user_data = array(), $ttl = 0)
    {
        if (!empty($this->_is_cache) && !empty($key) && !empty($user_data) && (int)$ttl > 0) {
            return $this->cache->save($key, $user_data, (int)$ttl);
        }

        return FALSE;
    }

    /**
     * 用户授权
     *
     * @access public
     * @return boolean
     */

    public function authorize()
    {
        $query = FALSE;
        $temp  = array();

        $authorized = $this->session->userdata('authorized');

        if( ! empty($authorized))
        {
            $temp['uri']    = $this->uri->uri_string();
            $temp['method'] = $this->router->fetch_method();

            $temp['uri'] = (stripos($temp['uri'], '/') === FALSE) ? $temp['uri'].'/home' : $temp['uri'];
            $temp['uri'] = (stripos($temp['uri'], $temp['method']) !== FALSE) ? substr($temp['uri'], 0, stripos($temp['uri'], $temp['method']) - 1) : $temp['uri'];

            if(isset($authorized[$temp['uri']]))
            {
                $query = (in_array($temp['method'], $authorized[$temp['uri']])) ? TRUE : FALSE;
            }

            if($query === FALSE)
            {
                redirect('', 'refresh');
            }
        }

        unset($authorized, $temp);
        return $query;
    }

    /**
     * 清空所有缓存
     *
     * @access public
     * @return boolean
     */

    public function cache_clean()
    {
        if (!empty($this->_is_cache)) {
            return $this->cache->clean();
        }

        return FALSE;
    }

    /**
     * 获取所有缓存
     *
     * @access public
     * @return array
     */

    public function cache_info()
    {
        if (!empty($this->_is_cache)) {
            return $this->cache->cache_info();
        }

        return array();
    }

    /**
     * 获取链接地址
     *
     * 用于列表页链接地址生成
     *
     * @access public
     * @param  integer $total 记录总数
     * @param  integer $page 每页显示数量
     * @return string
     */

    public function create_links($total = 0, $page = 10)
    {
        $this->load->library('pagination');

        $config['base_url'] = $this->show_url(TRUE);
        $config['total_rows'] = (int)$total;
        $config['per_page'] = (int)$page;
        $config['num_links'] = 2;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);" class="active">';
        $config['cur_tag_close'] = '</a></li>';
        $config['first_link'] = '首页';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '上一页';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '下一页';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = '尾页';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'per_page';

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /**
     * 显示当前页地址
     *
     * @access public
     * @param  boolean $flag 是否分页
     * @return string
     */

    public function show_url($flag = FALSE)
    {
        $data = $temp = array();

        $data = array(
            'port' => ($_SERVER['SERVER_PORT'] == 80) ? 'http://' : 'https://',
            'host' => $this->input->server('HTTP_HOST'),
            'self' => $this->input->server('REQUEST_URI'),
            'string' => $this->input->server('QUERY_STRING')
        );

        if (!empty($data['string'])) {
            parse_str($data['string'], $temp);

            if (isset($temp['per_page']) && !empty($flag)) {
                unset($temp['per_page']);
            }

            $data['self'] = substr($data['self'], 0, stripos($data['self'], '?'));
            $data['string'] = (!empty($temp)) ? '?' . http_build_query($temp, '', '&') : '';
        }

        $data = implode('', $data);

        unset($temp);
        return $data;
    }

    /**
     * 获取符合条件的记录数
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 搜索条件
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return integer
     */

    public function count($table = '', $where = array(), $key = '', $ttl = 0)
    {
        $total = 0;
        $temp  = array();

        if (!empty($table))
        {
            if(isset($where['limit']))
            {
                unset($where['limit']);
            }

            $this->_where($where);

            $temp['sql']   = $this->db->count_all_results($table, TRUE);
            $temp['count'] = $this->query($temp['sql'], $key, $ttl);

            if(COUNT($temp['count']) > 1)
            {
                $total = COUNT($temp['count']);
            }
            elseif(isset($temp['count'][0]['numrows']))
            {
                $total = $temp['count'][0]['numrows'];
            }
        }

        unset($temp);
        return $total;
    }

    /**
     * 数据删除
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 查询条件
     * @return boolean
     */

    public function delete($table = '', $where = array())
    {
        $query = FALSE;

        if (!empty($table) && !empty($where)) {
            $this->_where($where);
            $this->db->delete($table);
            $query = ($this->db->affected_rows()) ? TRUE : FALSE;
        }

        return $query;
    }

    /**
     * 删除缓存
     *
     * 如果键名为空则清空所有缓存
     *
     * @access public
     * @param  string $key 键名
     * @return boolean
     */

    public function delete_cache($key = '')
    {
        if (!empty($this->_is_cache) && !empty($key)) {
            return ($this->cache->get_metadata($key) !== FALSE) ? $this->cache->delete($key) : FALSE;
        }

        return FALSE;
    }

    /**
     * 获取多条记录
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 查询条件
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return array
     */

    public function get_all($table = '', $where = array(), $key = '', $ttl = 0)
    {
        $data = array();

        if (!empty($table)) {
            $this->_where($where);
            $data = $this->db->get($table, NULL, NULL, TRUE);
            $data = $this->query($data, $key, $ttl);
        }

        return $data;
    }

    /**
     * 获取数据缓存
     *
     * @access public
     * @param  string $key 键名
     * @return array
     */

    public function get_cache($key = '')
    {
        if (!empty($this->_is_cache) && !empty($key)) {
            return $this->cache->get($key);
        }

        return FALSE;
    }


    /**
     * 获取单个字段
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 查询条件
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return array
     */

    public function get_one($table = '', $where = array(), $key = '', $ttl = 0)
    {
        $data = array();

        if (!empty($table)) {
            $data = $this->get_row($table, $where, $key, $ttl);
            $data = (is_array($data)) ? array_shift($data) : $data;
        }

        return $data;
    }

    /**
     * 获取单条记录
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 查询条件
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return array
     */

    public function get_row($table = '', $where = array(), $key = '', $ttl = 0)
    {
        $data = array();
        if (!empty($table)) {
            $this->_where($where);
            $this->db->limit(1);
            $data = $this->db->get($table, NULL, NULL, TRUE);
            $data = $this->query($data, $key, $ttl);
        }

        return (!empty($data)) ? array_shift($data) : array();
    }

    /**
     * 数据写入
     *
     * @access public
     * @param  string $table 表名
     * @param  array $user_data 用户数据
     * @return integer
     */

    public function insert($table = '', $user_data = array())
    {
        $data = 0;

        if (!empty($table) && !empty($user_data)) {
            if (isset($user_data[0]) && is_array($user_data[0])) {
                $this->db->insert_batch($table, $user_data);
            } else {
                $this->db->insert($table, $user_data);
            }

            $data = $this->db->insert_id();
        }

        return $data;
    }

    /**
     * 模糊查询
     *
     * 字段用英文逗号分隔
     *
     * @access public
     * @param  array $where 搜索条件
     * @param  string $field 搜索字段
     * @return array
     */

    function like($where = array(), $field = '')
    {
        $data = $temp = array();

        $data = (!empty($where)) ? $where : array();
        $temp['keyword'] = $this->input->get('keyword', TRUE);

        if (!empty($temp['keyword']) && !empty($field)) {
            $temp['field'] = explode(',', $field);
            $data['query'] = (isset($data['query'])) ? $data['query'] . ' AND ' : ' ';

            if (!empty($temp['field'])) {
                $data['query'] .= 'CONCAT(';

                foreach ($temp['field'] as $v) {
                    $data['query'] .= '`' . $v . '`,';
                }

                $data['query'] = rtrim($data['query'], ',') . ') LIKE \'%' . $temp['keyword'] . '%\'';
            }
        }

        unset($temp);
        return $data;
    }

    /**
     * 生成密码
     *
     * @access public
     * @param  string $password 登录密码
     * @param  string $hash 哈稀码
     * @return boolean
     */

    public function password($password = '', $hash = '')
    {
        return md5(md5($password) . $hash . $this->config->item('encryption_key'));
    }

    /**
     * 手工查询
     *
     * 如果开启缓存可以统一在这里处理,加载缓存的时候请确认安装有对应的PHP扩展
     *
     * @access public
     * @param  string $sql SQL语句
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return array
     */

    public function query($sql = '', $key = '', $ttl = 0)
    {
        $data = array();

        if (!empty($sql)) {
            if ((int)$ttl > 0) {
                $key = (!empty($key)) ? $key : md5($sql);
                $data = $this->get_cache($key);

                if (empty($data)) {
                    $data = $this->db->query($sql)->result_array();
                    $this->add_cache($key, $data, (int)$ttl);
                }
            } else {
                $data = $this->db->query($sql)->result_array();
            }
        }

        return $data;
    }

    /**
     * 读取Excel文档
     *
     * @access public
     * @param  string $file Excel文件
     * @return array
     */

    public function read_excel($file = '')
    {
        $data = $temp = array();

        if (!empty($file) && is_file($file)) {
            include APPPATH . 'libraries/PHPExcel.php';
            $temp['reader'] = PHPExcel_IOFactory::createReader('Excel2007');
            $temp['reader']->setReadDataOnly(true);

            if (!$temp['reader']->canRead($file)) {
                $temp['reader'] = PHPExcel_IOFactory::createReader('Excel5');

                if (!$temp['reader']->canRead($file)) {
                    return 'Cannot read this file!';
                }
            }

            $temp['obj'] = $temp['reader']->load($file);
            $temp['number'] = $temp['obj']->getSheetCount();

            for ($n = 0; $n < $temp['number']; $n++) {
                $temp['sheet'] = $temp['obj']->getSheet($n);
                $temp['highestRow'] = $temp['sheet']->getHighestRow();
                $temp['highestColumn'] = $temp['sheet']->getHighestColumn();

                for ($r = 1; $r <= $temp['highestRow']; $r++) {
                    for ($c = 'A'; $c <= $temp['highestColumn']; $c++) {
                        $temp['data'][$n][$r][] = (string)$temp['sheet']->getCell($c . $r)->getValue();
                    }
                }
            }

            foreach ($temp['data'] as $k => $v) {
                if (count($v) == 1 && isset($v[1][0]) && empty($v[1][0])) {
                    unset($temp['data'][$k]);
                } else {
                    $data[] = $v;
                }
            }

            $data = (count($data) > 1) ? $data : $data[0];
        }

        unset($temp);
        return $data;
    }

    /**
     * 字段更新
     *
     * 支持MySQL函数,此方法不会进行转义操作。
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 更新条件
     * @param  array $user_data 用户数据
     * @return boolean
     */

    public function set($table = '', $where = array(), $user_data = array())
    {
        $query = FALSE;

        if (!empty($table) && !empty($where) && !empty($user_data)) {
            foreach ($user_data as $k => $v) {
                $this->db->set($k, $v, FALSE);
            }

            $this->_where($where);

            $this->db->update($table);

            $query = ($this->db->affected_rows() != -1) ? TRUE : FALSE;
        }

        return $query;
    }

    /**
     * 数据分页
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 搜索条件
     * @param  string $key 键名
     * @param  integer $ttl 存活期
     * @return array
     */

    public function show_page($table = '', $where = array(), $key = '', $ttl = 0)
    {
        $data = $temp = array();

        $temp['total'] = $this->count($table, $where);
        $temp['limit'] = (isset($_GET['limit'])) ? (int)$this->input->get('limit') : (int)$this->config->item('limit');
        $temp['limit'] = (!empty($temp['limit'])) ? $temp['limit'] : 10;

        $temp['where'] = (!empty($where)) ? $where : array();
        $temp['where']['limit'] = array('limit' => $temp['limit'], 'offset' => (int)$this->input->get('per_page'));
        $temp['sort'] = $this->input->get('sort', TRUE);
        $temp['order'] = $this->input->get('order', TRUE);

        if (!empty($temp['sort']) && in_array(strtolower($temp['order']), array('asc', 'desc'))) {
            $temp['where']['order_by'] = $temp['sort'] . ' ' . $temp['order'];
        }

        $data['data'] = $this->get_all($table, $temp['where'], $key, $ttl);
        $data['total'] = $temp['total'];
        $data['links'] = $this->create_links($temp['total'], $temp['limit']);

        unset($temp);
        return $data;
    }

    /**
     * 发送邮件
     *
     * 抄送地址可以是数组或者以逗号分隔的字符串,附件是一维数组。
     *
     * @access public
     * @param  array $email 邮件内容
     * @return boolean
     */

    public function send_mail($email = array())
    {
        $query = FALSE;

        if (!empty($email)) {
            $temp = array();

            $this->load->library('email');

            $temp['from'] = (!empty($email['from'])) ? $email['from'] : '';
            $temp['name'] = (!empty($email['name'])) ? $email['name'] : '';
            $temp['to'] = (!empty($email['to'])) ? $email['to'] : '';
            $temp['cc'] = (!empty($email['cc'])) ? $email['cc'] : '';
            $temp['subject'] = (!empty($email['subject'])) ? $email['subject'] : '';
            $temp['message'] = (!empty($email['message'])) ? $email['message'] : '';
            $temp['attach'] = (!empty($email['attach'])) ? $email['attach'] : '';

            $this->email->from($temp['from'], $temp['name']);
            $this->email->to($temp['to']);

            if (!empty($temp['cc'])) {
                $this->email->cc($temp['cc']);
            }

            $this->email->subject($temp['subject']);
            $this->email->message($temp['message']);

            if (!empty($temp['attachment'])) {
                foreach ($temp['attachment'] as $v) {
                    if (is_file($v)) {
                        $this->email->attach($v);
                    }
                }
            }

            $query = $this->email->send();
            unset($temp);
        }

        return $query;
    }

    /**
     * 提示信息
     *
     * URL地址格式：array('txt' => 'url', 'txt2' => 'url2')
     * 提示类型：success info warning
     *
     * @access public
     * @param  string $message 提示文字
     * @param  array $url 跳转网址
     * @param  string $type 提示类型
     * @return void
     */

    public function message($message = '', $url = array(), $type = 'success')
    {
        $data = array(
            'message' => $message,
            'url' => $url,
            'type' => $type
        );

        $data = $this->load->view('common/message', $data, TRUE);
        exit($data);
    }

    /**
     * 缩略图
     *
     * 如果未指定缩略图存储路径，程序会动态输出缩略图
     *
     * @access public
     * @param  string $filename 文件名称
     * @param  string $dynamic 动态输出
     * @param  integer $width 缩略图宽度
     * @param  integer $height 缩略图高度
     * @param  string $path 存储路径
     * @return boolean
     */

    public function thumb($filename = '', $dynamic = FALSE, $width = 100, $height = 100, $path = '')
    {
        $query = FALSE;

        if (!empty($filename) && is_file($filename)) {
            $config = array();

            $config['image_library'] = 'gd2';
            $config['source_image'] = $filename;
            $config['maintain_ratio'] = TRUE;
            $config['dynamic_output'] = $dynamic;
            $config['master_dim'] = 'auto';
            $config['width'] = $width;
            $config['height'] = $height;

            if (!empty($path)) {
                $config['new_image'] = rtrim($path, '/') . '/thumb/';

                if (!is_dir($config['new_image'])) {
                    @mkdir($config['new_image'], 0755, TRUE);
                }
            }

            $this->load->library('image_lib');

            $this->image_lib->initialize($config);
            $query = $this->image_lib->resize();

            $this->image_lib->clear();

            unset($config);
        }

        return $query;
    }

    /**
     * 交易编号
     *
     * @access public
     * @param  string $table 数据表名
     * @param  string $field 字段名称
     * @param  integer $length 编号长度
     * @return string
     */

    public function transaction_no($table = '', $field = '', $length = 8)
    {
        $transaction_no = '';

        if (!empty($table) && !empty($field)) {
            $temp = array();

            $transaction_no .= substr($field, 0, 1);
            $transaction_no .= date('ymd') . random($length, TRUE);

            $temp['where'] = array('where' => array($field => $transaction_no));
            $temp['count'] = $this->c->count($table, $temp['where']);

            if ($temp['count'] > 0) {
                $this->transaction_no($table, $field, $length);
            }

            $transaction_no = strtoupper($transaction_no);

            unset($temp);
        }

        return $transaction_no;
    }

    /**
     * 判断值是否唯一
     *
     * @access public
     * @param  string  $table 数据表名
     * @param  string  $field 字段名称
     * @param  string  $value 表单值
     * @return boolean
     */

    public function unique($table = '', $field = '', $value = '')
    {
        $query = FALSE;

        if( ! empty($table) && ! empty($field) && ! empty($value))
        {
            $temp = array();

            $temp['where']  = array('where' => array($field => $value));
            $temp['count']  = $this->count($table, $temp['where']);

            $query = (empty($temp['count'])) ? TRUE : FALSE;

            unset($temp);
        }

        return $query;
    }

    /**
     * 数据更新
     *
     * 如果要一次性更新多条记录$data是一个二维数组, $index指定更新的条件字段如 uid
     *
     * @access public
     * @param  string $table 表名
     * @param  array $where 更新条件
     * @param  array $user_data 用户数据
     * @param  string $field 索引字段
     * @return boolean
     */

    public function update($table = '', $where = array(), $user_data = array(), $field = '')
    {
        $query = FALSE;

        if (!empty($table) && !empty($user_data)) {
            $this->_where($where);

            if (!empty($field)) {
                $this->db->update_batch($table, $user_data, $field);
            } else {
                $this->db->update($table, $user_data);
            }

            $query = ($this->db->affected_rows() != -1) ? TRUE : FALSE;
        }

        return $query;
    }

    /**
     * 上传文件
     *
     * 文件名称不包括扩展名,附件类型示例如：pdf|xls|xlsx|png|jpg|gif|zip
     * 指定文件名称只对单文件上传有效
     *
     * @access public
     * @param  string $path 上传路径
     * @param  string $filename 文件名称
     * @param  string $types 附件类型
     * @param  string $userfile 表单名称
     * @return array
     */

    public function upload_old($path = '', $filename = '', $types = '*', $userfile = 'userfile')
    {
        $data = $temp = array();

        $temp['path'] = './uploads/' . ltrim($path, '/');
        $config['upload_path'] = $temp['path'];
        $config['allowed_types'] = $types;
        $config['overwrite'] = TRUE;
        $config['file_name'] = (!empty($filename)) ? $filename : md5(uniqid(mt_rand()));

        if (!is_dir($temp['path'])) {
            @mkdir($temp['path'], 0755, TRUE);
        }

        if (isset($_FILES[$userfile]['name'])) {
            if (is_array($_FILES[$userfile]['name'])) {
                unset($config['file_name']);
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
            } else {
                $temp['file'] = pathinfo($_FILES[$userfile]['name']);

                if (isset($temp['file']['extension'])) {
                    $config['file_name'] = $config['file_name'] . '.' . strtolower($temp['file']['extension']);
                }
            }
        }

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($userfile)) {
            $data = $this->upload->display_errors();
        } else {
            $data = $this->upload->data();

            if (!isset($data['full_path'])) {
                foreach ($data as $k => $v) {
                    if (isset($v['full_path'])) {
                        $data[$k]['full_path'] = substr($v['full_path'], stripos($v['full_path'], 'uploads'));
                        $data[$k]['md5'] = md5($data[$k]['full_path']);
                        $data[$k]['hash'] = md5_file($data[$k]['full_path']);
                    }
                }
            } else {
                $data['full_path'] = substr($data['full_path'], stripos($data['full_path'], 'uploads'));
                $data['md5'] = md5($data['full_path']);
                $data['hash'] = md5_file($data['full_path']);
            }
        }

        unset($temp);
        return $data;
    }
    public function upload($path = '', $filename = '', $types = '*', $userfile = 'userfile')
    {
        $data = $temp = array();

        $temp['path']            = item('oss_upload')?'uploads/'.ltrim($path, '/'):'./uploads/'.ltrim($path, '/');//2015.5.29 update => item('oss_upload')?'uploads/'.ltrim($path, '/'):
        $config['upload_path']   = $temp['path'];
        $config['allowed_types'] = $types;
        $config['overwrite']     = TRUE;
        $config['file_name']     = ( ! empty($filename)) ? $filename : md5(uniqid(mt_rand()));

        if( ! item('oss_upload') && ! is_dir($temp['path'])) //2015.5.29 update =》 ! item('oss_upload') &&
        {
            @mkdir($temp['path'], 0755, TRUE);
        }

        if(isset($_FILES[$userfile]['name']))
        {
            if(is_array($_FILES[$userfile]['name']))
            {
                unset($config['file_name']);
                $config['overwrite']    = FALSE;
                $config['encrypt_name'] = TRUE;
            }
            else
            {
                $temp['file'] = pathinfo($_FILES[$userfile]['name']);

                if(isset($temp['file']['extension']))
                {
                    $config['file_name'] = $config['file_name'].'.'.strtolower($temp['file']['extension']);
                }
            }
        }
        //根据配置 加载不同的上传类
        if( item('oss_upload')){
            $this->load->library('ossupload', $config, 'upload');
            $this->upload->initialize($config);
        }else{
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
        }

        $query=$this->upload->do_upload($userfile);//执行上传
        $err = $this->upload->display_errors();//错误信息
        $data = $this->upload->data();//上传的数据信息  （多文件上传时 返回上传失败  但正确的已上传 也会有数据）

        if( ! isset($data['full_path']))
        {
            foreach($data as $k => $v)
            {
                if(isset($v['full_path']))
                {
                    $data[$k]['full_path'] = substr($v['full_path'], stripos($v['full_path'], 'uploads'));
                    $data[$k]['md5']       = md5($data[$k]['full_path']);
                    $data[$k]['hash']      = '';//md5_file($data[$k]['full_path']);
                }
            }
        }
        else
        {
            $data['full_path'] = substr($data['full_path'], stripos($data['full_path'], 'uploads'));
            $data['md5']       = md5($data['full_path']);
            $data['hash']      = '';//md5_file($data['full_path']);
        }

        unset($temp);
        return array('data'=>$data,'info'=>$err,'query'=>$query);
    }

    /**
     * 水印处理
     *
     * 如果$mark是图像路径，则启用图片水印否则使用文字水印
     *
     * @access public
     * @param  string $picture 图片路径
     * @param  string $mark 水印信息
     * @param  string $align 水平位置 可选项：left, center, right
     * @param  string $vertical 垂直位置 可选项：top, middle, bottom
     * @return boolean
     */

    public function watermark($picture = '', $mark = '', $align = 'right', $vertical = 'bottom')
    {
        $query = FALSE;

        if (!empty($picture) && is_file($picture)) {
            $config['source_image'] = $picture;

            if (!empty($mark) && is_file($mark)) {
                $config['wm_type'] = 'overlay';
                $config['wm_overlay_path'] = $mark;
                $config['wm_opacity'] = '50';
            } else {
                $config['wm_type'] = 'text';
                $config['wm_text'] = $mark;
                $config['wm_font_path'] = './system/fonts/DroidSansFallback.ttf';
                $config['wm_font_size'] = '16';
                $config['wm_font_color'] = 'ffffff';
            }

            $config['padding'] = '5px';
            $config['wm_hor_alignment'] = $align;
            $config['wm_vrt_alignment'] = $vertical;

            $this->load->library('image_lib');
            $this->image_lib->clear();
            $this->image_lib->initialize($config);

            $query = $this->image_lib->watermark();
        }

        return $query;
    }

    /**
     * 生成Excel文档
     *
     * 用户数据必须是二维数组
     *
     * @access public
     * @param  array $user_data 用户数据
     * @param  string $filename 文件名
     * @return void
     */

    public function write_excel($user_data = array(), $filename = '')
    {
        include(APPPATH . 'libraries/PHPExcel.php');

        $filename = (!empty($filename)) ? rtrim($filename, '.xls') : date('YmdHis');
        $user_data = (!empty($user_data)) ? $user_data : array();

        $excel = new PHPExcel();
        $write = new PHPExcel_Writer_Excel5($excel);

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()->setTitle('Sheet');
        $excel->getActiveSheet()->fromArray($user_data, NULL, 'A1');

        header('Content-Type:application/force-download');
        header('Content-Type:application/octet-stream');
        header('Content-Type:application/download');
        header('Content-Disposition:inline;filename=' . $filename . '.xls');
        header('Content-Transfer-Encoding: binary');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');

        unset($excel);
        $write->save('php://output');
    }

    /**
     * 生成SQL查询条件
     *
     * @access private
     * @param  array $where 搜索条件
     * @return void
     */

    private function _where($where = array())
    {
        if (!empty($where) && !is_array($where)) {
            return;
        }

        if (isset($where['select'])) {
            // $this->db->select('title, content, date');
            if (isset($where['select'][0]) && is_array($where['select'])) {
                $this->db->select($where['select'][0], FALSE);
            } else {
                $this->db->select($where['select']);
            }
        }

        if (!empty($where['where'])) {
            // $this->db->where('name', $name); $this->db->where('name !=', $name);
            // $array = array('name' => $name, 'title' => $title, 'status' => $status); $this->db->where($array);
            $this->db->where($where['where']);
        }

        if (isset($where['query'])) {
            $this->db->where($where['query'], NULL, FALSE);
        }

        if (isset($where['join']) && is_array($where['join'])) {
            // $this->db->join('comments', 'comments.id = blogs.id', 'left'); 第三个参数来指定。可选项
            // 包括：left, right, outer, inner, left outer, 以及 right outer
            if (isset($where['join'][0]) && is_array($where['join'][0])) {
                foreach ($where['join'] as $v) {
                    if (isset($v['table']) && isset($v['where'])) {
                        if (isset($v['flag']) && in_array($v['flag'], array('left', 'right', 'outer', 'inner', 'left outer', 'right outer'))) {
                            $this->db->join($v['table'], $v['where'], $v['flag']);
                        } else {
                            $this->db->join($v['table'], $v['where'], 'left');
                        }
                    }
                }
            } else {
                if (isset($where['join']['table']) && isset($where['join']['where'])) {
                    if (isset($where['join']['flag']) && in_array($where['join']['flag'], array('left', 'right', 'outer', 'inner', 'left outer', 'right outer'))) {
                        $this->db->join($where['join']['table'], $where['join']['where'], $where['join']['flag']);
                    } else {
                        $this->db->join($where['join']['table'], $where['join']['where'], 'left');
                    }
                }
            }
        }

        if (isset($where['between'])) {
            // $this->db->where('name BETWEEN 10 AND 20');
            if (is_array($where['between'])) {
                foreach ($where['between'] as $v) {
                    if (!empty($v)) {
                        $this->db->where($v);
                    }
                }
            } else {
                $this->db->where($where['between']);
            }
        }

        if (isset($where['not_between'])) {
            $this->db->where($where['not_between']);
        }

        if (isset($where['where_in']) && is_array($where['where_in'])) {
            // $names = array('Frank', 'Todd', 'James'); $this->db->where_in('username', $names);
            if (isset($where['where_in'][0]) && is_array($where['where_in'][0])) {
                foreach ($where['where_in'] as $v) {
                    if (isset($v['field']) && isset($v['value'])) {
                        $this->db->where_in($v['field'], $v['value']);
                    }
                }
            } else {
                if (isset($where['where_in']['field']) && $where['where_in']['value']) {
                    $this->db->where_in($where['where_in']['field'], $where['where_in']['value']);
                }
            }
        }

        if (isset($where['where_not_in'])) {
            // $names = array('Frank', 'Todd', 'James'); $this->db->where_not_in('username', $names);
            if (isset($where['where_not_in']['field']) && isset($where['where_not_in']['value'])) {
                $this->db->where_not_in($where['where_not_in']['field'], $where['where_not_in']['value']);
            }
        }

        if (isset($where['or_where'])) {
            // 本函数与上面的where几乎完全相同，唯一的区别是本函数生成的子句是用 OR 来连接
            $this->db->or_where($where['or_where']);
        }

        if (isset($where['or_where_in'])) {
            // 本函数与上面的where_in几乎完全相同，唯一的区别是本函数生成的子句是用 OR 来连接
            if (isset($where['or_where_in']['field']) && isset($where['or_where_in']['value'])) {
                $this->db->or_where_in($where['or_where_in']['field'], $where['or_where_in']['value']);
            }
        }

        if (isset($where['like']) && is_array($where['like'])) {
            // $this->db->like('title', 'match');  $this->db->like('title', 'match', 'before'); 可用的选项是 'before', 'after' 以及 'both' (这是默认值)。
            if (isset($where['like'][0]) && is_array($where['like'][0])) {
                foreach ($where['like'] as $v) {
                    if (isset($v['field']) && isset($v['match'])) {
                        if (isset($v['flag']) && in_array($v['flag'], array('before', 'after', 'both'))) {
                            $this->db->like($v['field'], $v['match'], $v['flag']);
                        } else {
                            $this->db->like($v['field'], $v['match']);
                        }
                    }
                }
            } else {
                if (isset($where['like']['field']) && isset($where['like']['match'])) {
                    if (isset($where['like']['flag']) && in_array($v['flag'], array('before', 'after', 'both'))) {
                        $this->db->like($where['like']['field'], $where['like']['match'], $where['like']['flag']);
                    } else {
                        $this->db->like($where['like']['field'], $where['like']['match']);
                    }
                }
            }
        }

        if (isset($where['or_like']) && is_array($where['or_like'])) {
            // 本函数与上面的where_in几乎完全相同，唯一的区别是本函数生成的子句是用 OR 来连接。
            if (isset($where['or_like'][0]) && is_array($where['or_like'][0])) {
                foreach ($where['or_like'] as $v) {
                    if (isset($v['field']) && isset($v['match'])) {
                        if (isset($v['flag']) && in_array($v['flag'], array('before', 'after', 'both'))) {
                            $this->db->or_like($v['field'], $v['match'], $v['flag']);
                        } else {
                            $this->db->or_like($v['field'], $v['match']);
                        }
                    }
                }
            } else {
                if (isset($where['or_like']['field']) && isset($where['or_like']['match'])) {
                    if (isset($where['or_like']['flag']) && in_array($v['flag'], array('before', 'after', 'both'))) {
                        $this->db->or_like($where['or_like']['field'], $where['or_like']['match'], $where['or_like']['flag']);
                    } else {
                        $this->db->or_like($where['or_like']['field'], $where['or_like']['match']);
                    }
                }
            }
        }

        if (isset($where['group_by'])) {
            // $this->db->group_by(array("title", "date")); $this->db->group_by("title");
            $this->db->group_by($where['group_by']);
        }

        if (isset($where['having'])) {
            // $this->db->having('user_id', 45); $this->db->having('user_id = 45');
            if (isset($where['having'][0]) && is_array($where['having'][0])) {
                foreach ($where['having'] as $v) {
                    if (isset($v['field']) && isset($v['value'])) {
                        $this->db->limit($v['field'], $v['value']);
                    }
                }
            } else {
                $this->db->having($where['having']);
            }
        }

        if (isset($where['order_by'])) {
            // $this->db->order_by("title", "desc"); $this->db->order_by('title desc, name asc');
            if (isset($where['order_by'][0]) && is_array($where['order_by'][0])) {
                foreach ($where['order_by'] as $v) {
                    if (isset($v['field']) && isset($v['value']) && in_array($v['value'], array('asc', 'desc'))) {
                        $this->db->order_by($v['field'], $v['value']);
                    }
                }
            } else {
                $this->db->order_by($where['order_by']);
            }
        }

        if (isset($where['limit'])) {
            // $this->db->limit(10); $this->db->limit(10, 20);

            if (is_array($where['limit'])) {
                if (isset($where['limit']['limit']) && isset($where['limit']['offset'])) {
                    $this->db->limit($where['limit']['limit'], $where['limit']['offset']);
                }
            } else {
                $this->db->limit($where['limit']);
            }
        }
    }


    /**
     * 2015.5.28 oss获得图片链接
     * @param string $path
     * @param string $bucket
     * @param int $timeout
     * @return mixed
     */
    public function get_oss_image($path='',$bucket='',$timeout=3600){
        $query='';
        if( ! empty($path)){
            if(item('oss_upload')){

                if(item('oss_public'))return item('oss_bind_hostname').'/'.ltrim($path,'/');

                if( empty($bucket)){
                    $bucket=item('oss_bucket_img');
                }
                $params=array('access_id'=>item('oss_access_id'),'access_key'=>item('oss_access_key'));
                $this->load->library('oss',$params);
                $response = $this->oss->get_sign_url($bucket,$path,$timeout);
                if($response['status'] == 1){
                    $query=$response['data'];
                }
            }else{
                $query='/'.ltrim($path,'/');
            }
        }

        return $query;
    }
}