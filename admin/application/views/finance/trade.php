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
          <li class="active">投资还款</li>
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
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('finance/trade');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>记录类型</th>
                      <th>借款单号</th>
                      <th>交易金额</th>
                      <th>当前余额</th>
                      <th>备注信息</th>
                      <th>操作时间</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['payment_no'];?></td>
                      <td><?php echo $v['user_name'];?></td>
                      <td><?php echo payment_type($v['type']);?></td>
                      <td>
					  <?php if(authorize('finance/trade', 'ordercheck')):?>
					  <a href="<?php echo site_url('borrow?keyword='.$v['borrow_no']);?>" title="<?php echo $v['borrow_no'];?>"><?php echo $v['borrow_no'];?></a> [ <?php echo $v['rate'];?>% ]
					  <?php else:?>
					  <?php echo $v['borrow_no'];?>[ <?php echo $v['rate'];?>% ]
					  <?php endif;?>
					  </td>
                      <td><?php echo price_format($v['amount']);?></td>
                      <td><?php echo price_format($v['balance']);?></td>
                      <td><?php if($v['type'] == 1):?>月收益<?php echo $v['charge'];?><?php else:?>-<?php endif;?></td>
                      <td><?php echo my_date($v['dateline']);?></td>
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