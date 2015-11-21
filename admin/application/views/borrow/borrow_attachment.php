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
          <li><a href="<?php echo site_url('borrow');?>" title="借款管理">借款管理</a></li>
          <li class="active">借款申请</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-8">
               <?php echo (isset($subject)) ? $user['user_name'].' - '.$subject.' ['.$borrow_no.']' : '';?>
              </div>
              <div class="pull-right">
                <a href="<?php echo site_url('borrow');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
                <a href="<?php echo site_url('borrow/home/upload?borrow_no='.$borrow_no);?>" data-toggle="modal" title="上传资料" class="btn btn-default"><i class="fa fa-upload">上传资料</i></a>
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
                      <th>链接地址</th>
                      <th>资料类型</th>
                      <th>描述信息</th>
                      <th>上传人员</th>
                      <th>上传时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($attachment)):?>
                    <?php foreach($attachment as $k => $v):?>
                    <tr>
                      <td><?php echo $v['id'];?></td>
                      <td><?php echo 'admin/'.$v['link_url'];?> <a href="<?php echo $this->c->get_oss_image($v['link_url']);?>" title="<?php echo $v['description'];?>" target="_blank"><i class="fa fa-chain"></i></a></td>
                      <td><?php echo attachment_type($v['type']);?></td>
                      <td><?php echo $v['description'];?></td>
                      <td><?php echo $v['operator'];?></td>
                      <td><?php echo my_date($v['dateline']);?></td>
                      <td><a href="<?php echo site_url('borrow/home/remove?borrow_no='.$v['borrow_no'].'&id='.$v['id']);?>" title="删除附件"><i class="fa fa-trash-o"></i></a></td>
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
                  <div class="tab-pane active" id="dompaginate"></div>
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