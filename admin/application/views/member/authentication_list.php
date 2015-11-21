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
          <li class="active">认证开户</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员昵称或者手机号码!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('member/home');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>会员ID</th>
                      <th>姓名</th>
                      <th>手机</th>
                      <th>身份证</th>
                      <th>身份证照片</th>
                      <th>认证费</th>
                      <th>支付状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td><a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看详情"><?php echo $v['user_name'];?></a><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo $v['nric'];?></td>
                      <td><a href="<?php echo item('application_domain').'/'.$v['nric_image'] ?>"> <?php echo $v['nric_image'];?></a></td>
                        <td><?php echo $v['amount'];?></td>
                        <td><?php if($v['recharge_status'] == 1):echo '已充值';else:echo '未充值';endif;?></td>
                      <td>
                          <?php if($v['recharge_status'] == 1):?>
                            <a href="<?php echo site_url('member/home/authentication?uid='.$v['uid']);?>" title="认证"><i class="fa fa-laptop"></i>开户</a>
                          <?php else:?>
                              <i class="fa fa-laptop"></i>开户
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