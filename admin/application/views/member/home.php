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
          <li class="active">会员列表</li>
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
                      <th>邮箱</th>
                      <th>手机</th>
                      <th>身份证</th>
                      <th>最近登录</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td>
					 <?php if(authorize('member/home', 'check')):?>
					  <a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看详情"><?php echo $v['user_name'];?></a><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?>
					  <?php else:?>
					  <?php echo $v['user_name'];?></a><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?>
					  <?php endif;?>
					  </td>
                      <td><?php echo $v['email'];?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo $v['nric'];?></td>
                      <td><?php echo ($v['last_date'] - $v['reg_date'] > 0) ? my_date($v['last_date']) : '-';?></td>
                      <td>
					  <?php if(authorize('member/home', 'check')||authorize('member/home', 'update')||authorize('member/home', 'automatic')):?>
					  <?php if(authorize('member/home', 'check')):?>
                      <a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看详情"><i class="fa fa-laptop"></i></a>
					 <?php endif;?>
					  <?php if(authorize('member/home', 'update')):?>
                      <a href="<?php echo site_url('member/home/update?uid='.$v['uid']);?>" title="修改资料"><i class="fa fa-edit"></i></a>
					  <?php endif;?>
					  <?php if(authorize('member/home', 'automatic')):?>
					  <a href="<?php echo site_url('member/home/automatic_set?uid='.$v['uid']);?>" title="自动投标"><i class="fa fa-edit-new"></i></a>
					  <?php endif;?>
					  <?php else:?>
					  -
					  <?php endif;?>
                      
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="7"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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