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
          <li class="active">用户管理</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('user');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo ( ! empty($admin_name)) ? $admin_name : '添加用户';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <div class="alert alert-dismissable alert-info"> 用户默认密码为手机号码后6位！
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">用户姓名</label>
                <div class="col-sm-6">
                  <input type="text" name="admin_name" value="<?php echo ( ! empty($admin_name)) ? $admin_name : '';?>" class="form-control" placeholder="请输入用户姓名"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">性别</label>
                <div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="gender" value="1" <?php echo (isset($gender)) ? checked(1, $gender) : checked(0, 0);?>>
                    男 </label>
                  <label class="radio-inline">
                    <input type="radio" name="gender" value="2" <?php echo (isset($gender)) ? checked(2, $gender) : '';?>>
                    女 </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">手机号码</label>
                <div class="col-sm-6">
                  <input type="text" name="mobile" value="<?php echo ( ! empty($mobile)) ? $mobile : '';?>" class="form-control" placeholder="请输入该用户的手机号码"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">上级主管</label>
                <div class="col-sm-6">
                  <select name="parent_id" id="parent_id" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($admin_list as $k => $v):?>
                    <option value="<?php echo $v['admin_id'];?>" <?php echo (isset($parent_id)) ? selected($parent_id, $v['admin_id']) : '';?>><?php echo $v['admin_name'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">用户职位</label>
                <div class="col-sm-6">
                  <select name="role_id" id="role_id" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($role_list as $k => $v):?>
                    <option value="<?php echo $v['role_id'];?>" <?php echo (isset($role_id)) ? selected($role_id, $v['role_id']) : '';?>><?php echo $v['group_name'].' - '.$v['role_name'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">允许登录</label>
                <div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" <?php echo (isset($status)) ? checked(0, $status) : checked(0, 0);?>>
                    否 </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" <?php echo (isset($status)) ? checked(1, $status) : '';?>>
                    是 </label>
                </div>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                    <input type="hidden" name="admin_id" value="<?php echo (isset($admin_id)) ? $admin_id : '';?>">
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