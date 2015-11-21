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
          <li class="active">节点管理</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('user/node');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo ( ! empty($node_name)) ? $node_name : '添加节点';?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <?php if( ! empty($operator)):?>
              <div class="alert alert-dismissable alert-info"> <?php echo $operator;?> 于 <?php echo ( ! empty($update_time)) ? my_date($update_time) : '';?> 更新
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <?php endif;?>
              <div class="form-group">
                <label class="col-sm-3 control-label">节点名称</label>
                <div class="col-sm-6">
                  <input type="text" name="node_name" value="<?php echo ( ! empty($node_name)) ? $node_name : '';?>" class="form-control" placeholder="请输入节点名称"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">上级节点</label>
                <div class="col-sm-6">
                  <select name="parent_id" id="parent_id" class="form-control">
                    <option value="0">请选择</option>
                    <?php foreach($node_list as $v):?>
                    <option value="<?php echo $v['node_id'];?>" <?php echo (isset($parent_id)) ? selected($v['node_id'], $parent_id) : selected($v['node_id'], get('node_id'));?>><?php echo str_repeat('|____', $v['deep']).$v['node_name'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">访问地址</label>
                <div class="col-sm-6">
                  <input type="text" name="link_url" value="<?php echo ( ! empty($link_url)) ? $link_url : '';?>" class="form-control" placeholder="示例：user/node"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">显示排序</label>
                <div class="col-sm-6">
                  <input type="text" name="sort_order" value="<?php echo ( ! empty($sort_order)) ? $sort_order : '';?>" class="form-control" placeholder="值越大越靠前(最大值65535)"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">记录状态</label>
                <div class="col-sm-6">
                  <label class="radio-inline">
                    <input type="radio" name="status" value="0" <?php echo (isset($status)) ? checked(0, $status) : checked(0, 0);?>>
                    待审核 </label>
                  <label class="radio-inline">
                    <input type="radio" name="status" value="1" <?php echo (isset($status)) ? checked(1, $status) : '';?>>
                    已审核 </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">节点操作</label>
                <div class="col-sm-6">
                  <textarea name="actions" id="actions" class="form-control" cols="100" rows="10" style="width:100%; height:150px;" placeholder="每行一条记录，示例：创建节点|create"><?php echo ( ! empty($actions)) ? $actions : '';?></textarea>
                </div>
              </div>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                    <input type="hidden" name="node_id" value="<?php echo (isset($node_id)) ? $node_id : '';?>">
                      <input type="submit" value="确认提交" class="btn btn-primary">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>