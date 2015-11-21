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
          <li><a href="<?php echo site_url('borrow');?>" title="借款管理">借款管理</a></li>
          <li class="active">资料上传</li>
        </ol>
        <h1></h1>
        <div class="options"><a href="<?php echo site_url('borrow/home/attachment?borrow_no='.$borrow_no);?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a></div>
      </div>
      <div></div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($subject)) ? $user['user_name'].' - '.$subject.'['.$borrow_no.']' : '';?></h4>
            <div class="options"></div>
          </div>
          <form id="myform" action="" method="post" enctype="multipart/form-data" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
             <div class="form-group">
                <label class="col-sm-3 control-label">资料类型</label>
                <div class="col-sm-6">
                    <label class="radio-inline">
                      <input type="radio" name="type" value="1" checked="true" /> 抵押权证
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="2" /> 借款人证件
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="3" /> 合同文件
                    </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文件上传</label>
                <div class="col-sm-3">
                  <input type="file" name="userfile[]" value="" class="form-control" accept="image/*" />
                </div>
                <div class="col-sm-3">
                  <input type="text" name="description[]" value="" class="form-control" placeholder="请输入资料描述">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文件上传</label>
                <div class="col-sm-3">
                  <input type="file" name="userfile[]" value="" class="form-control" accept="image/*" />
                </div>
                <div class="col-sm-3">
                  <input type="text" name="description[]" value="" class="form-control" placeholder="请输入资料描述">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文件上传</label>
                <div class="col-sm-3">
                  <input type="file" name="userfile[]" value="" class="form-control" accept="image/*" />
                </div>
                <div class="col-sm-3">
                  <input type="text" name="description[]" value="" class="form-control" placeholder="请输入资料描述">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">文件上传</label>
                <div class="col-sm-3">
                  <input type="file" name="userfile[]" value="" class="form-control" accept="image/*" />
                </div>
                <div class="col-sm-3">
                  <input type="text" name="description[]" value="" class="form-control" placeholder="请输入资料描述">
                </div>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
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
</body>
</html>