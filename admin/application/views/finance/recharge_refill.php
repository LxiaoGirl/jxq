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
          <li><a href="<?php echo site_url('finance');?>" title="资金管理">资金管理</a></li>
          <li><a href="<?php echo site_url('finance/recharge');?>" title="会员充值">会员充值</a></li>
          <li class="active">手动充值</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('finance/recharge');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4>手动充值</h4>
            <div class="options"> </div>
          </div>
          <form id="myform" action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <div class="form-group">
                <label class="col-sm-3 control-label">手机号码</label>
                <div class="col-sm-3">
                  <input type="text" name="mobile" id="mobile" value="" class="form-control" placeholder="请输入用户的手机号(已注册用户)"/>
                </div>
                <div class="col-sm-3 control-label"></div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">充值金额</label>
                <div class="col-sm-6">
                  <div class="input-group">
                      <span class="input-group-addon">¥</span>
                      <input type="text" name="amount" value="" class="form-control" placeholder="请输入充值金额"/>
                      <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">备注信息</label>
                <div class="col-sm-6">
                  <textarea name="remarks" id="remarks" class="form-control ckeditor" cols="100" rows="5" style="width:100%; height:250px;" placeholder="备注信息"></textarea>
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
<script type="text/javascript" src="/admin/assets/plugins/datepicker/WdatePicker.js"></script>
<script type='text/javascript' src='/admin/assets/js/jquery-migrate-1.2.1.js'></script>
<script type='text/javascript' src='/admin/assets/plugins/autocomplete/jquery.autocomplete.min.js'></script>
<link rel="stylesheet" type="text/css" href="/admin/assets/plugins/autocomplete/jquery.autocomplete.css" />
<script>
$(function(){
  $("#mobile").autocomplete('/index.php/borrow/home/mobile', {
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