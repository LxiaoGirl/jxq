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
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员姓名!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('member/card');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>姓名</th>
                      <th>账户信息</th>
                      <th>绑定时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['card_no'];?></td>
                      <td><?php echo $v['real_name'];?></td>
                      <td><?php echo $v['bank_name'].'['.$v['account'].']';?></td>
                      <td><?php echo my_date($v['dateline']);?></td>
                      <td>
                      <a href="<?php echo site_url('member/card/detail?card_no='.$v['card_no']);?>" title="查看详情"><i class="fa fa-laptop"></i></a>
					  <?php if($v['status'] == "1" ):?>
					 <i class="fa fa-check-square-o">银行卡信息正确</i>
                      <?php endif;?>
					  <?php if($v['status'] == "0" ):?>
					  <a href="<?php echo site_url('member/card/finish?card_no='.$v['card_no']);?>" title="银行卡审核"><i class="fa fa-check-square-o"></i></a>
                      <?php endif;?>
					   <?php if($v['status'] == "-1" ): ?>
					    <a href="<?php echo site_url('member/card/finish?card_no='.$v['card_no']);?>" title="凯塔出错重新提交">凯塔出错重新提交</a>
						<?php endif;?>
						<?php if($v['status'] == "-2" ): ?>
					    <a href="<?php echo site_url('member/card/modify?card_no='.$v['card_no']);?>" title="该用户已经存在银行卡信息，需要修改原有信息">提交</a>
						<?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="5"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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