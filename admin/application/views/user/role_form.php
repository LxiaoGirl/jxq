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
          <li><a href="<?php echo site_url('user');?>" title="组织结构">组织结构</a></li>
          <li class="active">职位管理</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('user/role');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo ( ! empty($role_name)) ? $role_name : '添加职位';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <?php if( ! empty($operator)):?>
              <div class="alert alert-dismissable alert-info"> <?php echo $operator;?> 于 <?php echo ( ! empty($update_time)) ? my_date($update_time) : '';?> 更新
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <?php endif;?>
              <div class="form-group">
                <label class="col-sm-3 control-label">职位名称</label>
                <div class="col-sm-6">
                  <input type="text" name="role_name" value="<?php echo ( ! empty($role_name)) ? $role_name : '';?>" class="form-control" placeholder="请输入职位名称"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">所属部门</label>
                <div class="col-sm-6">
                  <select name="group_id" id="group_id" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($group_list as $v):?>
                    <option value="<?php echo $v['group_id'];?>" <?php echo (isset($group_id)) ? selected($v['group_id'], $group_id) : '';?>><?php echo str_repeat('|____', $v['deep']).$v['group_name'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">记录状态</label>
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
                <label class="col-sm-3 control-label">备注信息</label>
                <div class="col-sm-6">
                  <textarea name="remarks" id="remarks" class="form-control" cols="100" rows="10" style="width:100%; height:150px;" placeholder="备注信息"><?php echo ( ! empty($remarks)) ? $remarks : '';?></textarea>
                </div>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                      <input type="hidden" name="role_id" value="<?php echo (isset($role_id)) ? $role_id : '';?>">
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