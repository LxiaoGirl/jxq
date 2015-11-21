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
          <li class="active">借款祥情</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('borrow');?>" class="btn btn-default" title="返回列表"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($borrow_no)) ? $borrow_no : '';?></h4>
          </div>
          <div class="panel-body">
            <div class="alert alert-dismissable alert-info"> 申请时间：<?php echo my_date($add_time);?> 最近操作：<?php echo (isset($operator)) ? $operator.'['.my_date($update_time).']' : '';?>
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
            <table class="table">
              <tbody>
                <tr>
                  <td class="col-md-3 col-md-pull-9">借款标题</td>
                  <td><?php echo (isset($subject)) ? $subject : '';?></td>
                </tr>
                <tr>
                  <td>手机号码</td>
                  <td><?php echo (isset($user['mobile'])) ? $user['mobile'] : '';?> - <?php echo (isset($user['user_name'])) ? $user['user_name'] : '';?><?php echo (isset($user['real_name'])) ? ' ['.$user['real_name'].']' : '';?></td>
                </tr>
                <tr>
                  <td>借款用途</td>
                  <td><?php echo (isset($summary)) ? $summary : '';?></td>
                </tr>
                <tr>
                  <td>产品类别</td>
                  <td><?php echo (isset($productcategory)) ? productcategory($productcategory) : '';?></td>
                </tr>
                <tr>
                  <td>借款金额</td>
                  <td><?php echo (isset($amount)) ? price_format($amount) : '';?></td>
                </tr>
                <tr>
                  <td>融资金额</td>
                  <td><?php echo (isset($receive)) ? price_format($receive) : '';?></td>
                </tr>
                <tr>
                  <td>最低投资金额</td>
                  <td><?php echo (isset($lowest)) ? price_format($lowest) : '';?></td>
                </tr>
                <tr>
                  <td>年利率</td>
                  <td><?php echo (isset($rate)) ? $rate : '';?>%</td>
                </tr>
                <tr>
                  <td>实收利率</td>
                  <td><?php echo (isset($real_rate)) ? $real_rate : '';?>%</td>
                </tr>
                <tr>
                  <td>投资结束时间</td>
				 <td><?php echo (isset($due_date)) ? date('Y-m-d H:i:s',$due_date) : '';?></td>

                </tr>
                <tr>
                  <td>还款金额</td>
                  <td><?php echo (isset($payment)) ? price_format($payment) : '';?></td>
                </tr>
                <tr>
                  <td>还款截止时间</td>
                  <td><?php echo (isset($deadline)) ? my_date($deadline, 2) : '';?></td>
                </tr>
                <tr>
                  <td>还款方式</td>
                  <td><?php echo (isset($type)) ? mode_status($type) : '';?></td>
                </tr>
                <tr>
                  <td>借款期限</td>
                  <td><?php echo ($months) ? $months : '';?>月</td>
                </tr>
                <tr>
                  <td>计息时间</td>
                  <td><?php echo ($confirm_time) ? my_date($confirm_time, 2) : '';?></td>
                </tr>
                <tr>
                  <td>利息处理方式</td>
                  <td><?php if($repay == 1):?>按月扣除<?php else:?>一次性扣除<?php endif;?></td>
                </tr>
                <?php if($repay == 1):?>
                <tr>
                  <td>预扣期数</td>
                  <td><?php echo ($deduct) ? $deduct : '';?>期</td>
                </tr>
                <?php endif;?>
                <tr>
                  <td>显示日期</td>
                  <td><?php echo (isset($show_time)) ? date('Y-m-d H:i:s',$show_time) : '';?></td>
                </tr>
                <tr>
                  <td>预约购买时间</td>
                  <td><?php echo (isset($buy_time)) ? date('Y-m-d H:i:s',$buy_time) : '';?></td>
                </tr>
                <tr>
                  <td>借款描述</td>
                  <td><?php echo (isset($content)) ? $content : '';?></td>
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