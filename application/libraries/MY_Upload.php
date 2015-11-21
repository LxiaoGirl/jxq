<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 自定义上传类
 *
 * @author      Longjianghu Email:215241062@qq.com
 * @copyright   Copyright © 2013 - 2018 www.sohocn.net All rights reserved.
 * @created     2013-11-11
 * @updated     2013-11-11
 * @version     1.0.0
 */

class MY_Upload extends CI_Upload
{
    private $data = array(); // 存储变量

    /**
     * 初始化
     *
     * @access public
     * @param  array   $props 初始化参数
     * @return void
     */

    function __construct($props = array())
    {
        parent::__construct($props);
    }

    /**
     * 上传方法
     *
     * @access public
     * @param  string $field 上传字段
     * @return void
     */

    function do_upload($field = 'userfile')
    {
        if (empty($_FILES[$field]))
        {
            $this->set_error('upload_no_file_selected');
            return false;
        }
        else
        {
            if (isset($_FILES[$field]['name']) && is_array($_FILES[$field]['name']))
            {
                foreach ($_FILES[$field]['name'] as $k => $v)
                {
                    if ( ! empty($v))
                    {
                        $query = $this->do_xupload($field, $k);

                        if( ! empty($query))
                        {
                            $this->data[] = parent::data();
                        }
                        else
                        {
                            $this->data[] = 'ERROR:'.array_pop($this->error_msg);
                        }
                    }
                }

                return ( ! empty($this->data)) ? TRUE : FALSE;
            }
            else
            {
                return parent::do_upload($field);
            }
        }
    }

    /**
     * 显示用户数据
     *
     * @access public
     * @return array
     */

    public function data()
    {
        return ( ! empty($this->data)) ? $this->data : parent::data();
    }

    /**
     * 多文件上传
     *
     * @access public
     * @param  string  $field 字段名称
     * @param  integer $index 索引字段
     * @return void
     */

    private function do_xupload($field = 'userfile', $index = 0)
    {
        // Is $_FILES[$field] set? If not, no reason to continue.
        if ( ! isset($_FILES[$field]))
        {
            $this->set_error('upload_no_file_selected');
            return FALSE;
        }

        // Is the upload path valid?
        if ( ! $this->validate_upload_path())
        {
            // errors will already be set by validate_upload_path() so just return FALSE
            return FALSE;
        }

        // Was the file able to be uploaded? If not, determine the reason why.
        if ( ! is_uploaded_file($_FILES[$field]['tmp_name'][$index]))
        {
            $error = ( ! isset($_FILES[$field][$index]['error'])) ? 4 : $_FILES[$field][$index]['error'];

            switch($error)
            {
                case 1: // UPLOAD_ERR_INI_SIZE
                    $this->set_error('upload_file_exceeds_limit');
                    break;
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $this->set_error('upload_file_exceeds_form_limit');
                    break;
                case 3: // UPLOAD_ERR_PARTIAL
                    $this->set_error('upload_file_partial');
                    break;
                case 4: // UPLOAD_ERR_NO_FILE
                    $this->set_error('upload_no_file_selected');
                    break;
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $this->set_error('upload_no_temp_directory');
                    break;
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $this->set_error('upload_unable_to_write_file');
                    break;
                case 8: // UPLOAD_ERR_EXTENSION
                    $this->set_error('upload_stopped_by_extension');
                    break;
                default :   $this->set_error('upload_no_file_selected');
                    break;
            }

            return FALSE;
        }

        // Set the uploaded data as class variables
        $this->file_temp = $_FILES[$field]['tmp_name'][$index];
        $this->file_size = $_FILES[$field]['size'][$index];

        $user_file = array(
                        'name'     => $_FILES[$field]['tmp_name'][$index],
                        'type'     => $_FILES[$field]['type'][$index],
                        'tmp_name' => $_FILES[$field]['tmp_name'][$index],
                        'error'    => $_FILES[$field]['error'][$index],
                        'size'     => $_FILES[$field]['size'][$index],
                    );

        $this->_file_mime_type($user_file);

        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $_FILES[$field]['type'][$index]);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->_prep_filename($_FILES[$field]['name'][$index]);
        $this->file_ext  = $this->get_extension($this->file_name);
        $this->client_name = $this->file_name;

        // Is the file type allowed to be uploaded?
        if ( ! $this->is_allowed_filetype())
        {
            $this->set_error('upload_invalid_filetype');
            return FALSE;
        }

        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->_file_name_override != '')
        {
            $this->file_name = $this->_prep_filename($this->_file_name_override);

            // If no extension was provided in the file_name config item, use the uploaded one
            if (strpos($this->_file_name_override, '.') === FALSE)
            {
                $this->file_name .= $this->file_ext;
            }

            // An extension was provided, lets have it!
            else
            {
                $this->file_ext  = $this->get_extension($this->_file_name_override);
            }

            if ( ! $this->is_allowed_filetype(TRUE))
            {
                $this->set_error('upload_invalid_filetype');
                return FALSE;
            }
        }

        // Convert the file size to kilobytes
        if ($this->file_size > 0)
        {
            $this->file_size = round($this->file_size/1024, 2);
        }

        // Is the file size within the allowed maximum?
        if ( ! $this->is_allowed_filesize())
        {
            $this->set_error('upload_invalid_filesize');
            return FALSE;
        }

        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basdir restriction.
        if ( ! $this->is_allowed_dimensions())
        {
            $this->set_error('upload_invalid_dimensions');
            return FALSE;
        }

        // Sanitize the file name for security
        $this->file_name = $this->clean_file_name($this->file_name);

        // Truncate the file name if it's too long
        if ($this->max_filename > 0)
        {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }

        // Remove white spaces in the name
        if ($this->remove_spaces == TRUE)
        {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }

        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;

        if ($this->overwrite == FALSE)
        {
            $this->file_name = $this->set_filename($this->upload_path, $this->file_name);

            if ($this->file_name === FALSE)
            {
                return FALSE;
            }
        }

        /*
         * Run the file through the XSS hacking filter
         * This helps prevent malicious code from being
         * embedded within a file.  Scripts can easily
         * be disguised as images or other file types.
         */
        if ($this->xss_clean)
        {
            if ($this->do_xss_clean() === FALSE)
            {
                $this->set_error('upload_unable_to_write_file');
                return FALSE;
            }
        }

        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first.  If that fails
         * we'll use move_uploaded_file().  One of the two should
         * reliably work in most environments
         */
        if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
        {
            if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
            {
                $this->set_error('upload_destination_error');
                return FALSE;
            }
        }

        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        $this->set_image_properties($this->upload_path.$this->file_name);

        return TRUE;
    }

    /**
     * 设置图片属性
     *
     * @access public
     * @param  string  $path 文件路径
     * @return void
     */

    public function set_image_properties($path = '')
    {
        $this->image_width = $this->image_height = $this->image_type = $this->image_size_str    = '';

        if ( ! $this->is_image())
        {
            return;
        }

        if (function_exists('getimagesize'))
        {
            if (FALSE !== ($D = @getimagesize($path)))
            {
                $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

                $this->image_width      = $D['0'];
                $this->image_height     = $D['1'];
                $this->image_type       = ( ! isset($types[$D['2']])) ? 'unknown' : $types[$D['2']];
                $this->image_size_str   = $D['3'];  // string containing height and width
            }
        }
    }
}