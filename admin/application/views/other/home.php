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
          <li><a href="<?php echo site_url('other');?>" title="其它功能">其它功能</a></li>
          <li class="active">文章管理</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入文章名称!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('other');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
				<?php if(authorize('other/home', 'create')):?>
                <a href="<?php echo site_url('other/home/create');?>" title="发表文章" class="btn btn-default"><i class="fa fa-edit"></i>发表文章</a>
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
                      <th>ID</th>
                      <th>标题</th>
                      <th>所属分类</th>
                      <th>记录状态</th>
                      <th>最近更新</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['id'];?></td>
                      <td><?php echo $v['title'];?><?php echo ( ! empty($v['source'])) ? ' ['.$v['source'].']' : '';?></td>
                      <td><?php echo $v['category'];?></td>
                      <td><?php echo status($v['status']);?></td>
                      <td><?php echo my_date($v['update_time']);?></td>
                      <td>
					  <?php if(authorize('other/home', 'delete')||authorize('other/home', 'update')):?>
					  <?php if(authorize('other/home', 'delete')):?>
                      <a href="<?php echo site_url('other/home/delete?id='.$v['id']);?>" title="删除记录"><i class="fa fa-trash-o"></i></a>
					  <?php endif;?>
					  <?php if(authorize('other/home', 'update')):?>
                      <a href="<?php echo site_url('other/home/update?id='.$v['id']);?>" title="修改记录"><i class="fa fa-edit"></i></a>
					   <?php endif;?>
					   <?php else:?>
					   -
					   <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="6"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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