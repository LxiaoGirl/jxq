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
          <li class="active">会员详情</li>
        </ol>
        <h1></h1>
        <div class="options">
          <a href="<?php echo site_url('member');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
          <a href="<?php echo site_url('member/home/update?uid='.$uid);?>" class="btn btn-default"><i class="fa fa-edit"></i>编辑</a>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-primary">
          <div class="panel-heading">会员详情</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6"> <img src="<?php if( ! empty($avater)):?><?php echo $avater;?><?php else:?>/admin/assets/demo/avatar/johansson.png<?php endif;?>" alt="" class="pull-left" style="margin: 0 20px 20px 0">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th width="30%"></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>昵称：</td>
                        <td><?php echo $user_name;?></td>
                      </tr>
                      <tr>
                        <td>手机号码：</td>
                        <td><?php echo $mobile;?></td>
                      </tr>
                      <tr>
                        <td>身份证号码：</td>
                        <td><?php echo $nric;?></td>
                      </tr>
                      <tr>
                        <td>注册时间：</td>
                        <td><?php echo my_date($reg_date);?></td>
                      </tr>
                      <tr>
                        <td>上次登录：</td>
                        <td><?php echo my_date($last_date);?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
              </div>
            </div>
            <div class="row">
            <div class="alert alert-dismissable alert-info">
                <strong>友情提示：</strong> 点击编号可以查看数据详情,只显示最近10条记录！
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <div class="col-md-12">
                <div class="tab-container tab-success">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#investborrow" data-toggle="tab">投资记录</a></li>
                  </ul>
                  <div class="tab-content" style="border:none;padding: 10px 0;">
                    <div class="tab-pane active" id="borrow">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>投资项目</th>
                              <th>年利率</th>
                              <th>借款金额</th>
                              <th>账户余额</th>
                              <th>最近应还款</th>
                              <th>最近应还款时间</th>
                              <th>标的完成时间</th>
                              <th>到期还款日</th>
                              <th>累计已还利息</th>
                              <th>备注</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if(! empty($borrow)):?>
                            <?php foreach($borrow as $k => $v):?>
                            <tr>
                              <td><a href="<?php echo site_url('borrow/home/detail?borrow_no='.$v['borrow_no']);?>" title="查看详情"><?php echo $v['borrow_no'];?></a></td>
                              <td><?php echo $v['subject'];?></td>
                              <td><?php echo borrow_type($v['type']);?></td>
                              <td><?php echo $v['months'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo $v['rate'];?>%</td>
                              <td><?php echo price_format($v['receive']);?></td>
                              <td><?php echo my_date($v['add_time']);?></td>
                              <td><?php echo borrow_status($v['status']);?></td>
                            </tr>
                            <?php endforeach;?>
                          <?php else:?>
                            <tr><td colspan="11">暂无相关记录!</td></tr>
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
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>