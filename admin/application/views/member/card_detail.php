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
          <li><a href="<?php echo site_url('member');?>" title="会员管理">会员管理</a></li>
          <li class="active">银行卡</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('member/card');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($card_no)) ? $card_no : '';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <div class="table-responsive">
                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th width="30%"></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>用户姓名：</td>
                      <td><?php echo (isset($real_name)) ? $real_name : '';?></td>
                    </tr>
                    <tr>
                      <td>开户行：</td>
                      <td><?php echo (isset($bank_name)) ?  $bank_name : '';?></td>
                    </tr>
                    <tr>
                      <td>银行账号：</td>
                      <td><?php echo (isset($account)) ?  $account : '';?></td>
                    </tr>
                    <tr>
                      <td>备注信息：</td>
                      <td><?php echo (isset($remarks)) ?  $remarks : '';?></td>
                    </tr>
                    <tr>
                      <td>绑定时间：</td>
                      <td><?php echo (isset($dateline)) ? my_date($dateline, 2) : '';?></td>
                    </tr>
                  </tbody>
                </table>
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