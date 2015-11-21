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
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="mobile" class="form-control" placeholder="请输入会员姓名!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('member/commission');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>借款编号</th>
                      <th>投资金额</th>
                      <th>提成时间</th>
                      <th>提成金额</th>
                      <th>结算状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['commission_no'];?></td>
                      <td><?php echo $v['user_name'];?><?php if( ! empty($v['real_name'])):?>[<?php echo $v['real_name'];?>]<?php endif;?></td>
                      <td><?php echo $v['borrow_no'];?></td>
                      <td><?php echo price_format($v['amount'], 2);?></td>
                      <td><?php echo my_date($v['dateline'], 2);?></td>
                      <td><?php echo price_format($v['commission'], 2);?> [<?php echo $v['rate'];?>%]</td>
                      <td><?php echo checkout_status($v['status']);?></td>
                      <td>
                      <a href="<?php echo site_url('member/commission/detail?commission_no='.$v['commission_no']);?>" title="查看详情"><i class="fa fa-laptop"></i></a>
                      <?php if($v['status'] == 0):?>
					  <?php if(authorize('member/commission', 'checkout')):?>
                      <a href="<?php echo site_url('member/commission/checkout?commission_no='.$v['commission_no']);?>" title="立即结算"><i class="fa fa-check-square-o"></i></a>
					  <?php endif;?>
                      <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="8"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                  </tbody>
                </table>
                <div class="pull-right">
                  <div class="tab-pane active" id="dompaginate">
                    <?php echo (isset($links)) ? $links : '';?>
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