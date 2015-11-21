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
          <li class="active">会员打款</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('finance/payment');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a></div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
        <div class="panel-heading">
          <h4>会员打款</h4>
        </div>
        <form action="" class="form-horizontal row-border" method="post" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
          <div class="panel-body collapse in">
          <div class="alert alert-dismissable alert-info"> 审核人员：<?php echo (isset($auditor)) ? $auditor : '';?> 审核时间：<?php echo (isset($auditor)) ? my_date($add_time) : '';?>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td class="col-md-3 col-md-pull-9">会员姓名</td>
                <td>
                <?php echo (isset($real_name)) ? $real_name : '';?> <?php echo (isset($user_name)) ? '['.$user_name.']' : '';?>
                </td>
              </tr>
              <tr>
                <td class="col-md-3 col-md-pull-9">手机号码</td>
                <td><?php echo (isset($mobile)) ? $mobile : '';?></td>
              </tr>
              <tr>
                <td class="col-md-3 col-md-pull-9">借款编号</td>
                <td><?php echo (isset($borrow_no)) ? $borrow_no : '';?></td>
              </tr>
              <tr>
                <td>借款金额</td>
                <td><?php echo (isset($amount)) ? price_format($amount) : '';?></td>
              </tr>
              <tr>
                <td>手续费</td>
                <td><div class="input-group"> <span class="input-group-addon">¥</span>
                    <input type="text" name="charge" value="<?php echo (isset($charge)) ? $charge : '';?>" class="form-control" maxlength="10" placeholder="请输入手续费金额" />
                  </div>
              </td>
             </div>
         <!--   <tr>
              <td>银行账户</td>
              <td><?php if( ! empty($card_list)):?>
                <select name="card_no" id="card_no" class="form-control">
                  <option value="">请选择</option>
                  <?php foreach($card_list as $v):?>
                  <option value="<?php echo $v['card_no'];?>" <?php echo (isset($card_no)) ? selected($card_no, $v['card_no']) : '';?>><?php echo $v['bank_name'];?>[<?php echo $v['account'];?>]</option>
                  <?php endforeach;?>
                </select>
                <?php else:?>
                用户还没有绑定银行账户
                <?php endif;?></td>
            </tr>-->
            <tr>
              <td>支付状态</td>
              <td><div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" <?php echo (isset($status)) ? checked(0, $status) : '';?>/>
                    未支付 </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" <?php echo (isset($status)) ? checked(1, $status) : '';?>/>
                    已支付 </label>
                </div></td>
                </tr>
              </tbody>
          </table>
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