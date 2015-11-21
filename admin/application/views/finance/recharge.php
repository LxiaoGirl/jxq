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
          <li class="active">会员充值</li>
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
                    <option value="1">线下充值</option>
                    <option value="2">凯塔平台</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-xs-4">
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员姓名或者流水号!">
              </div>
              <div class="form-group col-xs-1">
                  <div class="checkbox">
                      <label><input type="checkbox" name="status" value="1" class="parsley-validated">充值成功</label>
                  </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('finance/recharge');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
				<?php if(authorize('finance/recharge', 'refill')):?>
                <a href="<?php echo site_url('finance/recharge/refill');?>" title="手动充值" class="btn btn-default"><i class="fa fa-credit-card"></i>线下充值</a>
				<?php endif;?>
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
                      <th>充值类型</th>
                      <th>充值金额</th>
                      <th>备注信息</th>
                      <th>充值状态</th>
                      <th>申请时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['recharge_no'];?></td>
                      <td>
                        <a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看会员详情" target="_blank"><?php echo $v['user_name'];?></a><?php if( ! empty($v['real_name'])):?>[<?php echo $v['real_name'];?>]<?php endif;?>
                      </td>
                      <td><?php echo recharge_type($v['type']);?></td>
                      <td><?php echo price_format($v['amount']);?></td>
                      <td><?php echo $v['remarks'];?></td>
                      <td><?php echo status($v['status']);?></td>
                      <td><?php echo my_date($v['add_time']);?></td>
                      <td>
                      <?php if(empty($v['status']) && $v['type'] == 1):?>
                      <a href="<?php echo site_url('finance/recharge/verify?recharge_no='.$v['recharge_no']);?>" title="审核记录"><i class="fa fa-check-square-o"></i></a>
                      <?php else:?>
                      -
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