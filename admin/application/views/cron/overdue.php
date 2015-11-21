<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
<script type="text/javascript" src="/admin/assets/js/jquery-1.10.2.min.js"></script>
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
          <li><a href="<?php echo site_url('cron/repayment');?>" title="借款管理">还款管理</a></li>
          <li class="active">逾期记录</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">

          <div class="col-xs-12">
            <form action="">
              <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">项目类别</div>
              <div class="form-group col-xs-1">
                  <select name="productcategory" id="productcategory" class="form-control">
                      <option value="">全部</option>
                      <?php foreach($productcategory as $v):?>
                          <option value="<?php echo $v['cat_id'];?>" <?php if($productcategory_select == $v['cat_id']):echo 'selected';endif; ?>><?php echo $v['category'];?></option>
                      <?php endforeach;?>
                  </select>
              </div>
              <button class="btn-primary btn">搜索</button>

              <div class="pull-right">
                <a href="<?php echo site_url('cron/repayment/repay');?>" title="还款" class="btn btn-default">全部还款</a>
                <a href="<?php echo site_url('cron/repayment');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                          <th>借款人姓名</th>
                          <th>手机</th>
                          <th>还款期数</th>
                          <th>还款日期</th>
                          <th>还款金额</th>
<!--                          <th>借款期数</th>-->
<!--                          <th>当前余额</th>-->
<!--                          <th>还款状态</th>-->
<!--                          <th>操作</th>-->
                      </tr>
                      </thead>
                    <tbody>
                      <?php if( ! empty($data)):?>
                      <?php foreach($data as $k => $v):?>
                    <tr <?php if($v['status'] == 1):?>class="alert-success" <?php endif;?> >
                      <td><?php echo $v['payment_no'];?></td>
                      <td><?php echo $v['user_name'];?></td>
                      <td><?php echo $v['mobile'];?></td>
                        <td><?php echo '第'.date('Y-m-d',strtotime($v['pay_date'])).'期';?></td>
                        <td><?php echo date('Y-m-d',strtotime($v['pay_date']));?></td>
                        <td><?php echo $v['amount'];?></td>
<!--                        <td>--><?php //echo $v['balance'];?><!--</td>-->
<!--                      <td>--><?php //if($v['is_pay'] == 1):echo '已还款';else:if($v['is_repay'] == 1):echo '已预付';elseif($v['balance']-$v['interest'] < 0):echo '余额不足';else:echo '未还款'; endif; endif;?><!--</td>-->
<!--                      <td>--><?php //if($v['is_pay'] == 1):?><!-----><?php //else:?><!--<a href="--><?php //echo site_url('cron/repayment/repay_one?borrow_no='.$v['borrow_no']) ?><!--"><i class="fa fa-credit-card"></i></a>--><?php //endif;?><!--</td>-->
                    </tr>
                    <?php endforeach;?>
                      <?php else:?>
                    <tr>
                      <td colspan="13"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                    </tbody>
                  </table>
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