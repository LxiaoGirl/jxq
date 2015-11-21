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
          <li class="active">佣金提成</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('member/commission');?>" class="btn btn-default" title="返回列表"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($commission_no)) ? $commission_no : '';?></h4>
          </div>
          <div class="panel-body">
            <div class="alert alert-dismissable alert-info"> 支付人员：<?php echo display($operator);?> 支付时间：<?php echo my_date($pay_time);?>
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
            <table class="table">
              <tbody>
                <tr>
                  <td class="col-md-3 col-md-pull-9">会员姓名</td>
                  <td>
                  <?php echo display($uid, 'user_name');?>
                  <?php if(display($uid, 'real_name') != ''):?>
                  [<?php echo display($uid, 'real_name');?>]
                  <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <td>借款编号</td>
                  <td><?php echo (isset($borrow_no)) ? $borrow_no : '';?></td>
                </tr>
                <tr>
                  <td>投资人</td>
                  <td>
                  <?php echo display($investor, 'user_name');?>
                  <?php if(display($investor, 'real_name') != ''):?>
                  [<?php echo display($investor, 'real_name');?>]
                  <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <td>借款编号</td>
                  <td><?php echo (isset($borrow_no)) ? $borrow_no : '';?></td>
                </tr>
                <tr>
                  <td>支付编号</td>
                  <td><?php echo (isset($payment_no)) ? $payment_no : '';?></td>
                </tr>
                <tr>
                  <td>投资金额</td>
                  <td><?php echo (isset($amount)) ? price_format($amount,2) : '';?></td>
                </tr>
                <tr>
                  <td>提成比例</td>
                  <td><?php echo (isset($rate)) ? $rate.'%' : '';?></td>
                </tr>
                <tr>
                  <td>提成金额</td>
                  <td><?php echo (isset($commission)) ? price_format($commission,2): '';?></td>
                </tr>
                <tr>
                  <td>提成时间</td>
                  <td><?php echo (isset($dateline)) ? my_date($dateline, 2) : '';?></td>
                </tr>
                <tr>
                  <td>结算状态</td>
                  <td><?php echo (isset($status)) ? checkout_status($status) : '';?></td>
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