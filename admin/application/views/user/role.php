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
          <li><a href="<?php echo site_url('user');?>" title="组织结构">组织结构</a></li>
          <li class="active">职位管理</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入职位名称!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('user/role');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
                <a href="<?php echo site_url('user/role/create');?>" title="添加职位" class="btn btn-default"><i class="fa fa-edit"></i>添加职位</a>
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
                      <th class="col-md-1">职位ID</th>
                      <th>职位名称</th>
                      <th>最近更新</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['role_id'];?></td>
                      <td><?php echo $v['group_name'].' - '.$v['role_name'];?></td>
                      <td><?php echo my_date($v['update_time'], 2);?></td>
                      <td>
					  <?php if(authorize('user/role', 'update')||authorize('user/role', 'delete')||authorize('user/role', 'authorization')):?>
					  <?php if(authorize('user/role', 'update')):?>
                      <a href="<?php echo site_url('user/role/update?role_id='.$v['role_id']);?>" title="修改资料"><i class="fa fa-edit"></i></a>
					  <?php endif;?>
					  <?php if(authorize('user/role', 'delete')):?>
                      <a href="<?php echo site_url('user/role/delete?role_id='.$v['role_id']);?>" title="删除记录"><i class="fa fa-trash-o"></i></a>
					  <?php endif;?>
					  <?php if(authorize('user/role', 'authorization')):?>
                      <a href="<?php echo site_url('user/role/authorization?role_id='.$v['role_id']);?>" title="角色授权"><i class="fa fa-legal"></i></a>
					  <?php endif;?>
					  <?php else:?>
					  -
					  <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="4"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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