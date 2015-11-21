<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo load_file('assets/less/styles.less,styles.css,less.js');?></head>
<body class="focusedform">
<div class="verticalcenter"> <a href="<?php echo site_url();?>"><img src="<?php echo base_url('assets/img/logo-big.png');?>" alt="Logo" class="brand" /></a>
  <div class="panel panel-primary">
  <form action="" class="form-horizontal" style="margin-bottom: 0px !important;" method="post">
    <div class="panel-body">
      <h4 class="text-center" style="margin-bottom: 25px;">用户注册</h4>
        <div class="form-group">
          <div class="col-sm-12">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
              <input type="text" name="mobile" id="mobile" class="form-control" placeholder="手机号码">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input type="text" name="admin_name" id="admin_name" class="form-control" placeholder="姓名">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input type="password"name="password" id="password" class="form-control" placeholder="登录密码">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-12">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input type="password" name="retype" id="retype" class="form-control" placeholder="请再次确认新密码">
            </div>
          </div>
        </div>
    </div>
    <div class="panel-footer">
      <div class="pull-left"> <a href="<?php echo site_url('passport');?>" class="btn btn-default">返回登录</a> </div>
      <div class="pull-right"> <input type="submit" value="确认提交" class="btn btn-success"></div>
    </div>
    </form>
  </div>
</div>
</body>
</html>