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
          <li class="active"><?php if(get('borrow_no')):?>标地更新<?php else:?>发布标地<?php endif;?></li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('borrow');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($borrow_no)) ? $borrow_no : '';?></h4>
            <div class="options"> </div>
          </div>
          <form id="myform" action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <div class="form-group">
                <label class="col-sm-3 control-label">借款标题</label>
                <div class="col-sm-6">
                  <input type="text" name="subject" value="<?php echo (isset($subject)) ? $subject : '';?>" class="form-control" placeholder="请输入借款标题"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">手机号码</label>
                <div class="col-sm-6 control-label"><?php echo (isset($user['user_name'])) ? $user['user_name'] : '';?><?php echo ( ! empty($user['real_name'])) ? ' ['.$user['real_name'].']' : '';?></div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">借款类型</label>
                <div class="col-sm-6 control-label">
                <?php if($type == 1):?>
                  信用借款
                <?php elseif($type == 2):?>
                  抵押借款
                <?php else:?>
                  担保借款
                <?php endif;?>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">借款金额</label>
                <div class="col-sm-6 control-label">
                  <?php echo (isset($amount)) ? price_format($amount) : '';?>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">最低投资金额</label>
                <div class="col-sm-6">
                  <div class="input-group">
                      <span class="input-group-addon">¥</span>
                      <input type="text" name="lowest" value="<?php echo (isset($lowest)) ? $lowest : '';?>" class="form-control" placeholder="请输入最低投资金额"/>
                      <span class="input-group-addon">.00</span>
                  </div>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">年利率</label>
                <div class="col-sm-6 control-label">
                  <?php echo (isset($rate)) ? $rate : '';?>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">实收利率</label>
                <div class="col-sm-6 control-label">
                  <?php echo (isset($real_rate)) ? $real_rate : '';?>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">还款方式</label>
                <div class="col-sm-6 control-label">先还息 后还本</div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">借款期限</label>
                <div class="col-sm-6 control-label">
                  <?php echo (isset($months)) ? $months : '';?>月
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">利息处理方式</label>
                <div class="col-sm-6 control-label">
                <?php if($repay == 1):?>
                按月扣除
                <?php else:?>
                一次性扣除
                <?php endif;?>
                </div>
              </div>
            <?php if($repay == 1):?>
             <div class="form-group">
                <label class="col-sm-3 control-label">预扣期数</label>
                <div class="col-sm-6 control-label">
                  <?php echo (isset($deduct)) ? $deduct : '';?>期
                </div>
              </div>
             <?php endif;?>
             <div class="form-group">
                <label class="col-sm-3 control-label">显示日期</label>
                <div class="col-sm-6">
                  <input type="text" name="show_time" id="show_time" value="<?php echo (isset($show_time)) ? my_date($show_time, 2) : date('Y-m-d');?>" class="form-control" placeholder="在网站显示的时间" readonly="true" onClick="WdatePicker();}'})"/>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">预约购买时间</label>
                <div class="col-sm-6">
                  <input type="text" name="buy_time" id="buy_time" value="<?php echo (isset($buy_time)) ? my_date($buy_time, 2) : '';?>" class="form-control" placeholder="可购买时间" readonly="true" onClick="WdatePicker()"/>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">投资结束时间</label>
                <div class="col-sm-6">
                  <input type="text" name="due_date" id="due_date" value="<?php echo (isset($due_date)) ? my_date($due_date, 2) : '';?>" class="form-control" placeholder="投资结束时间" readonly="true" onClick="WdatePicker()"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">借款用途</label>
                <div class="col-sm-6">
                  <textarea name="summary" id="summary" class="form-control ckeditor" cols="100" rows="5" style="width:100%; height:250px;" placeholder="请输入借款用途"><?php echo (isset($summary)) ? $summary : '';?></textarea>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">还款来源</label>
                <div class="col-sm-6">
                  <textarea name="repayment" id="repayment" class="form-control ckeditor" cols="100" rows="5" style="width:100%; height:250px;" placeholder="请输入还款资金来源"><?php echo (isset($repayment)) ? $repayment : '';?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">借款描述</label>
                <div class="col-sm-6">
                  <textarea name="content" id="content" class="form-control ckeditor" cols="100" rows="5" style="width:100%; height:250px;" placeholder="借款描述"><?php echo (isset($content)) ? $content : '';?></textarea>
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