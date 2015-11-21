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
          <li class="active">会员借款</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员姓名或者流水号!">
                </div>
              </div>
              <div class="form-group col-xs-2">
                记录状态：
                  <input type="radio" name="status" value="1" <?php if($status == 1):echo 'checked';endif; ?>/>已支付
                  <input type="radio" name="status" value="0" <?php if($status == 0 || $status == ''):echo 'checked';endif; ?>/>未支付
              </div>
                <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">项目类别</div>
              <div class="form-group col-xs-1">
                <select name="productcategory" class="form-control">
                    <option value="">全部</option>
                    <?php foreach($productcategory as $v):?>
                        <option value="<?php echo $v['cat_id'];?>" <?php if($productcategory_select == $v['cat_id']):echo 'selected';endif; ?>><?php echo $v['category'];?></option>
                    <?php endforeach;?>
                </select>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('finance/payment');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
              </div>
            </form>
          </div>
          <div class="col-xs-12">
            <div class="panel panel-midnightblue">
              <div class="panel-heading">
                <div class="options"> </div>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>会员姓名</th>
                      <th>支付金额</th>
                      <th>手续费</th>
                      <th>开户银行</th>
                      <th>银行账号</th>
                      <th>申请时间</th>
                      <th>记录状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['payment_no'];?></td>
                      <td>
					  <?php if(authorize('finance/payment', 'vipcheck')):?>
					  <a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看会员详情" target="_blank"><?php echo $v['real_name'];?></a>
					  <?php else:?>
					  <?php echo $v['real_name'];?>
					  <?php endif;?>
					  </td>
                      <td><?php echo price_format($v['amount'] - $v['charge']);?></td>
                      <td><?php echo price_format($v['charge']);?></td>
                      <td><?php echo $v['bank_name'];?></td>
                      <td><?php echo $v['account'];?></td>
                      <td><?php echo my_date($v['add_time']);?></td>
                      <td><?php echo payment_status($v['status']);?></td>
                      <td>
                      <?php if($v['status'] == 0):?>
					  <?php if(authorize('finance/payment', 'pay_now')):?>
                      <a href="<?php echo site_url('finance/payment/pay_now?payment_no='.$v['payment_no']);?>" title="立即支付"><i class="fa fa-credit-card"></i></a>
					  <?php endif;?>
                    <?php else:?>
					<?php if(authorize('finance/payment', 'check')):?>
                       <a href="<?php echo site_url('finance/payment/detail?payment_no='.$v['payment_no']);?>" title="查看明细">查看明细</a>
					<?php endif;?>
                    <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="9"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                  </tbody>
                </table>
                <div class="pull-right">
                  <div class="tab-pane active" id="dompaginate">
                    <?php echo $links;?>
                  </div>
                </div>
              </div>
            </div>
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