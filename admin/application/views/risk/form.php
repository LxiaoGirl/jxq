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
          <li><a href="<?php echo site_url('risk');?>" title="风控部">风控部</a></li>
          <li class="active">发布标地</li>
        </ol>
        <h1></h1>
        <div class="options">
        <a href="<?php echo site_url('risk');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h4>发布标的</h4>
          </div>
        <form action="<?php echo site_url('risk/home/create');?>" class="form-horizontal row-border" enctype="multipart/form-data" method="post" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
          <div class="panel-body collapse in">
              <div class="form-group">
                <label class="col-sm-3 control-label">借款标题</label>
                <div class="col-sm-6">
                  <input type="text" name="subject" value="借10000买车" class="form-control">
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-3 control-label">借款用途</label>
                <div class="col-sm-6">
                  <input type="text" name="remarks" value="买车" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">借款金额</label>
                <div class="col-sm-6">
                  <input type="text" name="amount" value="10000" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">用户手机号</label>
                <div class="col-sm-6">
                  <input type="text" name="mobile" value="13508388928" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">标地年利率</label>
                <div class="col-sm-6">
                  <input type="text" name="rate" value="15" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">实收利率</label>
                <div class="col-sm-6">
                  <input type="text" name="s_rate" value="14" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">担保公司</label>
                <div class="col-sm-6">
                  <input type="text" name="company" value="网加金服担保" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">担保利率</label>
                <div class="col-sm-6">
                  <input type="text" name="g_rate" value="1" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">还款方式</label>
                <div class="col-sm-6">
                    <label class="radio-inline">
                      <input type="radio" name="mode" value="1" checked="true"> 按月还息到期还本
                    </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">利息支付</label>
                  <div class="col-sm-6">
                    <label class="radio-inline">
                      <input type="radio" name="payment" value="1" checked="true"> 一次性支付
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="payment" value="2"> 按月支付
                    </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">还款来源</label>
                <div class="col-sm-6">
                  <input type="text" name="repayment" value="工资收入" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">生效时间</label>
                <div class="col-sm-6">
                  <input type="text" name="start_date" value="2014-10-01" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">预约购买时间</label>
                <div class="col-sm-6">
                  <input type="text" name="buy_time" value="2014-10-20" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">资料上传</label>
                <div class="col-sm-6">
                  <input type="file" name="userfile[]" value="" class="form-control">
                  <input type="file" name="userfile[]" value="" class="form-control">
                  <input type="file" name="userfile[]" value="" class="form-control">
                  <input type="file" name="userfile[]" value="" class="form-control">
                  <input type="file" name="userfile[]" value="" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">借款描述</label>
                <div class="col-sm-6">
                  <textarea name="content" class="form-control autosize" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 200px;">
                    123123
                  </textarea>
                </div>
              </div><?php echo validation_errors(); ?>
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