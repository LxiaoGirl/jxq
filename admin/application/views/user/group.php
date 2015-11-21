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
          <li class="active">部门管理</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入部门名称!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('user/group');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
				<?php if(authorize('user/group', 'create')):?>
                <a href="<?php echo site_url('user/group/create');?>" title="添加部门" class="btn btn-default"><i class="fa fa-edit"></i>添加部门</a>
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
                      <th class="col-md-1">ID</th>
                      <th>部门名称</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($group_list)):?>
                    <?php foreach($group_list as $k => $v):?>
                    <tr>
                      <td><?php echo $v['group_id'];?></td>
                      <td><?php echo str_repeat('|____', $v['deep']).$v['group_name'];?>
					  <?php if(authorize('user/group', 'junior')||authorize('user/group', 'update')||authorize('user/group', 'delete')):?>
					  <?php if(authorize('user/group', 'junior')):?>
                      <a href="<?php echo site_url('user/group/create?group_id='.$v['group_id']);?>" title="添加下级部门"><i class="fa fa-plus"></i></a>
					  <?php endif;?>
					  <?php if(authorize('user/group', 'update')):?>
                      <a href="<?php echo site_url('user/group/update?group_id='.$v['group_id']);?>" title="修改记录"><i class="fa fa-edit"></i></a>
					  <?php endif;?>
					  <?php if(authorize('user/group', 'delete')):?>
                      <a href="<?php echo site_url('user/group/delete?group_id='.$v['group_id']);?>" title="删除记录"><i class="fa fa-trash-o"></i></a>
					  <?php endif;?>
					  <?php else:?>
					  -
					  <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="2"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                  </tbody>
                </table>
                <div class="pull-right">
                  <div class="tab-pane active" id="dompaginate">
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