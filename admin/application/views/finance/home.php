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
          <li class="active">资金明细</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-2">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <select name="type" id="type" class="form-control">
                    <option value="0">请选择</option>
                    <option value="1">充值</option>
                    <option value="2">提现</option>
                    <option value="3">冻结</option>
                    <option value="4">解冻</option>
                    <option value="5">投资</option>
                    <option value="6">借款</option>
                    <option value="7">利息收益</option>
                    <option value="8">支付利息</option>
                    <option value="9">偿还本金</option>
                    <option value="10">会员还款</option>
                    <option value="11">邀请提成</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-xs-4">
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员昵称或者手机号码!">
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('finance/home');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>ID</th>
                      <th>会员姓名</th>
                      <th>记录类型</th>
                      <th>交易金额</th>
                      <th>交易后余额</th>
                      <th>来源单号</th>
                      <th>备注信息</th>
                      <th>操作时间</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['id'];?></td>
					  <?php if(authorize('finance/home', 'vipcheck')):?>
                      <td><a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看会员祥情"><?php echo $v['user_name'];?></a>
					  <?php else:?>
					  <td><?php echo $v['user_name'];?>
					  <?php endif;?></td>
                      <td><?php echo flow_type($v['type']);?></td>
                      <td><?php echo price_format($v['amount']);?></td>
                      <td><?php echo price_format($v['balance']);?></td>
                      <td>
					  <?php if(authorize('finance/home', 'ordercheck')):?>
					  <a href="<?php echo flow_source($v['source'], $v['type']);?>" title="订单来源"><?php echo $v['source'];?></a>
					  <?php else:?>
					  <?php echo $v['source'];?>
					  <?php endif;?>
					  </td>
                      <td><?php echo $v['remarks'];?></td>
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