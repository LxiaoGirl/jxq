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
          <li><a href="<?php echo site_url('apiuser');?>" title="组织结构">api用户</a></li>
          <li class="active">用户列表</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
<!--                <div class="input-group">-->
<!--                  <span class="input-group-addon"><i class="fa fa-search"></i></span>-->
<!--                  <input type="text" name="keyword" class="form-control" placeholder="请输入用户姓名或者手机号码!">-->
<!--                </div>-->
              </div>
<!--              <button class="btn-primary btn">搜索</button>-->
              <div class="pull-right">
                <a href="<?php echo site_url('user');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
<!--                --><?php //if(authorize('apiuser', 'create')):?>
                <a href="<?php echo site_url('apiuser/home/create');?>" title="添加用户" class="btn btn-default"><i class="fa fa-edit"></i>添加api用户</a>
<!--                --><?php //endif;?>
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
                      <th class="col-md-1">用户ID</th>
                      <th>名称</th>
                      <th>appid</th>
                      <th>appsecret</th>
                      <th>权限</th>
                      <th>说明</th>
                      <th>操作者</th>
                      <th>时间</th>
                      <th>状态</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td>
                      <?php echo $v['uname'];?>
                      </td>
                      <td><?php echo $v['appid'];?></td>
                      <td><?php echo $v['appsecret'];?></td>
                      <td><?php echo str_replace('4','删',str_replace('3','改',str_replace('2','增',str_replace('1','查',$v['authentication']))));?></td>
                      <td><?php echo $v['remarks'];?></td>
                      <td><?php echo $v['operator'];?></td>
                      <td><?php echo my_date($v['add_time']);?></td>
                      <td><?php echo ($v['status'] == 1)?'可访问':'拒绝访问';?></td>
                      <td>
                      <a href="<?php echo site_url('apiuser/home/update?uid='.$v['uid']);?>" title="修改资料"><i class="fa fa-edit"></i></a>
                      <?php if( ! empty($v['status'])):?>
                      <a href="<?php echo site_url('apiuser/home/status?uid='.$v['uid'].'&status=0');?>" title="账号锁定"><i class="fa fa-lock"></i></a>
                      <?php else:?>
                      <a href="<?php echo site_url('apiuser/home/status?uid='.$v['uid'].'&status=1');?>" title="解除锁定"><i class="fa fa-unlock"></i></a>
                      <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="10"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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