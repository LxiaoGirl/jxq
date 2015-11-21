<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
</head>
<body class="">
<?php $this->load->view('common/topbar');?>
<div id="page-container">
  <?php $this->load->view('common/sidebar');?>
  <div id="page-content">
    <div id='wrap'>
      <div id="page-heading">
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url();?>" title="返回首页">首页</a></li>
          <li><a href="<?php echo site_url('other');?>" title="其它功能">其它功能</a></li>
          <li class="active">文章管理</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('other/home');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo ( ! empty($title)) ? $title : '发表文章';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <?php if( ! empty($operator)):?>
              <div class="alert alert-dismissable alert-info"> <?php echo $operator;?> 于 <?php echo my_date($update_time);?> 更新
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <?php endif;?>
              <div class="form-group">
                <label class="col-sm-3 control-label">文章标题</label>
                <div class="col-sm-6">
                  <input type="text" name="title" value="<?php echo ( ! empty($title)) ? $title : '';?>" class="form-control" placeholder="请输入文章标题"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">关键字</label>
                <div class="col-sm-6">
                  <input type="text" name="keywords" value="<?php echo ( ! empty($keywords)) ? $keywords : '';?>" class="form-control" placeholder="请输入关键字"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文章来源</label>
                <div class="col-sm-6">
                  <input type="text" name="source" value="<?php echo ( ! empty($source)) ? $source : '';?>" class="form-control" placeholder="请填写文章来源"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">链接地址</label>
                <div class="col-sm-6">
                  <input type="text" name="link_url" value="<?php echo ( ! empty($link_url)) ? $link_url : '';?>" class="form-control" placeholder="请填写绝对地址"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">描述信息</label>
                <div class="col-sm-6">
                  <input type="text" name="description" value="<?php echo ( ! empty($description)) ? $description : '';?>" class="form-control" placeholder="请输入描述信息"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文章分类</label>
                <div class="col-sm-6">
                <select name="cat_id" id="cat_id" class="form-control">
                  <option value="0">请选择</option>
                <?php foreach($cat_list as $k => $v):?>
                  <option value="<?php echo $v['cat_id'];?>" <?php echo (isset($cat_id)) ? selected($cat_id, $v['cat_id']) : '';?>><?php echo str_repeat('|-', $v['deep']);?><?php echo $v['category'];?></option>
                <?php endforeach;?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文章状态</label>
                <div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" <?php echo (isset($status)) ? checked(0, $status) : checked(0, 0);?>>
                    待审核 </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" <?php echo (isset($status)) ? checked(1, $status) : '';?>>
                    已审核 </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文章内容</label>
                <div class="col-sm-6">
                  <textarea name="content" id="content" class="form-control ckeditor" cols="100" rows="10" style="width:100%; height:350px;"><?php echo ( ! empty($content)) ? $content : '';?></textarea>
                </div>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                      <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '';?>">
                      <input type="submit" value="确认提交" class="btn btn-primary">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
<script type="text/javascript" src="/admin/assets/plugins/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="/admin/assets/plugins/kindeditor/lang/zh_CN.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
  editor = K.create('textarea[name="content"]', {
   resizeType : 1,
   allowFileManager : true
  });
});
</script>
</body>
</html>