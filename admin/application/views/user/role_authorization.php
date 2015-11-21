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
          <li class="active">职位授权</li>
        </ol>
        <h1></h1>
        <div class="options"> <a href="<?php echo site_url('user/role');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a> </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo display($role_name);?></h4>
            <div class="options"> </div>
          </div>
          <form action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
              <?php if( ! empty($operator)):?>
              <div class="alert alert-dismissable alert-info"> <?php echo $operator;?> 于 <?php echo ( ! empty($update_time)) ? my_date($update_time) : '';?> 更新
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <?php endif;?>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th class="col-md-1">ID</th>
                      <th>节点名称</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($node_list)):?>
                    <?php foreach($node_list as $key => $val):?>
                    <tr>
                      <td><?php echo $val['node_id'];?></td>
                      <td>
                      <?php if( ! empty($val['actions'])):?>
                      <?php echo str_repeat('|____', $val['deep']).$val['node_name'];?> <a href="javascript:;" title="全选">+</a>
                      <?php else:?>
                      <?php echo str_repeat('|____', $val['deep']).$val['node_name'];?>
                      <?php endif;?>
                      <?php if($val['actions']):?>
                      <?php foreach($val['actions'] as $k => $v):?>
                      <input type="checkbox" name="authorized[<?php echo $val['link_url'];?>][]" value="<?php echo $v;?>" <?php echo (isset($authorized[$val['link_url']])) ? checked($v, $authorized[$val['link_url']]) : '';?>><?php echo $k;?>
                      <?php endforeach;?>
                      <?php endif;?>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="2"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 你还没有添加节点！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                  </tbody>
                </table>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-6 col-sm-offset-3">
                    <div class="btn-toolbar">
                      <input type="hidden" name="role_id" value="<?php echo (isset($role_id)) ? $role_id : '';?>">
                      <input type="button" value="全选" class="btn btn-default" id="select-all">
                      <input type="button" value="取消" class="btn btn-default" id="unselect">
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
<script>
$(function(){
  $('td > a').click(function(){
    var obj = $(this).parent().find(':checkbox');
    obj.each(function(i,v){
       this.checked = true;
    })
  })

  $('#select-all').on('click',function(){
    $(':checkbox').each(function(i,v){
       this.checked = true;
    })
  });

  $('#unselect').on('click',function(){
    $(':checkbox').each(function(i,v){
      this.checked = false;
    })
  });
})
</script>
</body>
</html>