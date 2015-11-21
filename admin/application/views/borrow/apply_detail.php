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
          <li class="active">借款申请</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('borrow/apply');?>" class="btn btn-default" title="返回列表"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($apply_no)) ? $apply_no : '';?></h4>
          </div>
          <div class="panel-body">
            <div class="alert alert-dismissable alert-info"> 申请时间：<?php echo my_date($add_time);?> 最近操作：<?php echo (isset($operator)) ? $operator.'['.my_date($update_time).']' : '';?>
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
            <table class="table">
              <tbody>
                <tr>
                  <td class="col-md-3 col-md-pull-9">客户姓名</td>
                  <td><?php echo $user_name;?></td>
                </tr>
                <tr>
                  <td>借款类型：</td>
                  <td><?php echo nature($type);?></td>
                </tr>
                <tr>
                  <td>借款金额：</td>
                  <td><?php echo price_format($amount);?></td>
                </tr>
                <tr>
                  <td>申请时间：</td>
                  <td><?php echo my_date($dateline, 2);?></td>
                </tr>
                <tr>
                  <td>来源途径：</td>
                  <td><?php echo $from;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>