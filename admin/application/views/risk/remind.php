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
          <li><a href="<?php echo site_url('service');?>" title="客服专区">风控部</a></li>
          <li class="active">借款管理</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员姓名!">
                </div>
              </div>
              <button class="btn-primary btn">搜索</button>
            </form>
          </div>
          <div class="col-xs-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="options">
                  <ul class="nav nav-tabs">
                      <li <?php if($type != 2):?>class="active"<?php endif;?>>
                        <a href="<?php echo site_url('risk/remind');?>" title="月利息提醒">月利息提醒 </a>
                      </li>
                      <li <?php if($type == 2):?>class="active"<?php endif;?>>
                        <a href="<?php echo site_url('risk/remind?type=2');?>" title="本金还款提醒">本金还款提醒</a>
                      </li>
                    </ul>
                </div>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>项目名称</th>
                      <th>借款金额</th>
                      <th>年利率</th>
                      <th>借款人</th>
                      <th>本月利息</th>
                      <th>还款时间</th>
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
                      <a href="<?php echo site_url('service/home/detail?uid='.$v['uid']);?>" title="查看详情"><i class="fa fa-laptop"></i>查看</a>
                      </td>
                    </tr>
                    <?php endforeach;?>
                    <?php else:?>
                    <tr>
                      <td colspan="8"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
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