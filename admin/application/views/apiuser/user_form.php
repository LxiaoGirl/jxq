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
          <li><a href="<?php echo site_url('apiuser');?>" title="组织结构">api用户</a></li>
          <li class="active">用户管理</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('apiuser');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo ( ! empty($uname)) ? $uname : '添加用户';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <div class="form-group">
                <label class="col-sm-3 control-label">用户名称</label>
                <div class="col-sm-6">
                  <input type="text" name="uname" value="<?php echo ( ! empty($uname)) ? $uname :set_value('uname');?>" class="form-control" placeholder="请输入用户名称"/>
                    <?php echo form_error('uname'); ?>
                </div>
              </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">说明</label>
                    <div class="col-sm-6">
                        <input type="text" name="remarks" value="<?php echo ( ! empty($remarks)) ? $remarks : set_value('remarks');?>" class="form-control" placeholder="请输入用户说明"/>
                        <?php echo form_error('remarks'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo form_error('authentication'); ?>
                    <label class="col-sm-3 control-label">权限</label>
                    <div class="col-sm-6">
                        <select name="authentication[]" multiple>
                            <option value="1" <?php if(empty($authentication)):echo 'selected';else:if(is_array($authentication)):echo in_array(1,$authentication)?'selected':'';else:echo $authentication==1?'selected':'';endif;endif; ?>>查</option>
                            <option value="2" <?php if( ! empty($authentication)):if(is_array($authentication)):echo in_array(2,$authentication)?'selected':'';else:echo $authentication==2?'selected':'';endif;endif; ?>>增</option>
                            <option value="3" <?php if( ! empty($authentication)):if(is_array($authentication)):echo in_array(3,$authentication)?'selected':'';else:echo $authentication==3?'selected':'';endif;endif; ?>>改</option>
                            <option value="4" <?php if( ! empty($authentication)):if(is_array($authentication)):echo in_array(4,$authentication)?'selected':'';else:echo $authentication==4?'selected':'';endif;endif; ?>>删</option>
                        </select>
                    </div>
                </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">允许访问</label>
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
                    <input type="hidden" name="uid" value="<?php echo (isset($uid)) ? $uid : '';?>">
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