<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台系统</title>
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
          <li class="active">个人资料</li>
        </ol>
        <h1></h1>
        <div class="options">
        <a href="<?php echo site_url();?>" class="btn btn-default" title="返回首页"><i class="fa fa-reply-all"></i>返回首页</a>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4>
            个人资料
            </h4>
          </div>
        <form action="<?php echo site_url('proflie');?>" class="form-horizontal row-border" method="post" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
          <div class="panel-body collapse in">
          <div class="alert alert-dismissable alert-info">
                <p>注册时间：<?php echo (isset($reg_date)) ? my_date($reg_date).' ['.$reg_ip.']' : '';?> 最近登录：<?php echo ( ! empty($last_date)) ? my_date($last_date).' ['.$last_ip.']' : '-';?></p>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">姓名</label>
                <div class="col-sm-6">
                  <input type="text" name="admin_name" value="<?php echo  (isset($admin_name)) ? $admin_name : '';?>" class="form-control" placeholder="请输入你的真实姓名">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">性别</label>
                <div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="gender" value="1" <?php echo (isset($gender)) ? checked(1, $gender) : '';?>/>
                    男 </label>
                  <label class="radio-inline">
                    <input type="radio" name="gender" value="2" <?php echo (isset($gender)) ? checked(2, $gender) : '';?>/>
                    女 </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">手机号码</label>
                <div class="col-sm-6">
                  <input type="text" name="mobile" value="<?php echo (isset($mobile)) ? $mobile : '';?>" class="form-control" maxlength="11" placeholder="最多只能输入11位">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">电子邮箱：</label>
                <div class="col-sm-6">
                  <input type="text" name="email" value="<?php echo (isset($email)) ? $email : '';?>" class="form-control" maxlength="60" placeholder="请输入常用的电子邮箱地址" />
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
<script type='text/javascript' src='/admin/assets/js/jquery-migrate-1.2.1.js'></script>
<script type='text/javascript' src='/admin/assets/plugins/autocomplete/jquery.autocomplete.min.js'></script>
<link rel="stylesheet" type="text/css" href="/admin/assets/plugins/autocomplete/jquery.autocomplete.css" />
<script>
$(function(){
  $("#admin_id").autocomplete('/index.php/member/home/mobile?m=1', {
      minChars: 1,
      width: 310,
      matchContains: true,
      autoFill: false,
      dataType:'json',
      parse: function(data){
        var rows = [];
        for(var i=0; i<data.length; i++){
           rows[rows.length] = {
               data:data[i],
               result:data[i].mobile
           };
        }
        return rows;
      },
      formatItem: function(data){
        return data.mobile + '[' + data.admin_name + ']';
      },
      formatMatch:function(data){return data.mobile;},
      formatResult: function(data){return data.mobile;}
  });
  $("#parent_id").autocomplete('/index.php/member/home/mobile', {
      minChars: 1,
      width: 310,
      matchContains: true,
      autoFill: false,
      dataType:'json',
      parse: function(data){
        var rows = [];
        for(var i=0; i<data.length; i++){
           rows[rows.length] = {
               data:data[i],
               result:data[i].mobile
           };
        }
        return rows;
      },
      formatItem: function(data){
        if(data.real_name != ''){
          return data.mobile + '[' + data.user_name + ' - ' + data.real_name + ']';
        }else{
          return data.mobile + '[' + data.user_name + ']';
        }
      },
      formatMatch:function(data){return data.mobile;},
      formatResult: function(data){return data.mobile;}
  });
})
</script>
</body>
</html>