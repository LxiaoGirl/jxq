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
          <li class="active">居间人列表</li>
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
                <a href="<?php echo site_url('member/invite');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
              </div>
			   <div class="pull-right">
			   <?php if(authorize('member/invite', 'allsettlement')):?>
                <a href="<?php echo site_url('member/invite/processing');?>" title="批量结算用户" class="btn btn-default" style="margin-right:15px;"><i class="fa fa-credit-card"></i>批量结算用户</a>
				<?php endif;?>
              </div>
            </form>
			 
          </div>
          <div class="col-xs-12">		  
            <div class="panel panel-midnightblue">
              <div class="panel-heading">
                <div class="options">                    <?php echo $start;?> 00:00:00-<?php echo $end;?> 23:59:59 居间人统计数据 </div>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>会员ID</th>
                      <th>姓名</th>
                      <th>邮箱</th>
                      <th>手机</th>
                      <th>下属会员数</th>
                      <th>LV等级</th>
                      <th>居间人收益</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td>
					  <?php if(authorize('member/invite', 'check')):?>
					  <a href="<?php echo site_url('member/home/detail?uid='.$v['uid']);?>" title="查看详情"><?php echo $v['user_name'];?></a><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?>
					  <?php else:?>
					  <?php echo $v['user_name'];?></a><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?>
					  <?php endif;?>
					  </td>
                      <td><?php echo $v['email'];?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo $v['count'];?></td>
                      <td><?php echo $v['lv'];?></td>
                      <td><?php echo $v['jujianren'];?></td>
                      <td>
					  <?php if(authorize('member/invite', 'settlement')):?>
                      <a href="<?php echo site_url('member/invite/get_one?uid='.$v['uid']);?>" title="查看详情">查看详情</a>
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