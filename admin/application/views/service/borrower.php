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
          <li><a href="<?php echo site_url('service');?>" title="客服专区">客服专区</a></li>
          <li class="active">借款人</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                  <input type="text" name="mobile" class="form-control" placeholder="请输入手机号码!">
                </div>
              </div>
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
                  <input type="text" name="nric" class="form-control" placeholder="请输入身份证号码!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
            </form>
          </div>
          <div class="col-xs-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="options"></div>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>姓名</th>
                      <th>身份证</th>
                      <th>手机</th>
                      <th>注册时间</th>
                      <th>最近登录</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td><?php echo $v['user_name'];?></td>
                      <td><?php echo $v['nric'];?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo my_date($v['reg_date']);?></td>
                      <td><?php echo my_date($v['last_date']);?></td>
                      <td>
                      <a href="<?php echo site_url('service/borrower/detail?uid='.$v['uid']);?>" title="查看详情"><i class="fa fa-laptop"></i>查看</a>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="7"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 你输入用户的手机号码和身份证号码查询！
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