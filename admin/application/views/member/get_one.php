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
            </form>
			 
          </div>   
           <form action="" style="width:100%">
			<input type="text" name="uid" value = "<?php echo $user['uid'];?>" style="display:none;">
		  <div style="width: 40%;float:left;">
				<label class="col-sm-3 control-label" style="width:15%;text-align: left;">
					开始日期
				</label>
				<div class="col-sm-3">
					<input type="text" name="start_time" id="start_time" class="form-control" value ="<?php echo $start;?>" placeholder="在网站显示的时间" onclick="WdatePicker()">
				</div>
		  </div>
		  <div style="width: 40%;float:left;">
				<label class="col-sm-3 control-label" style="width:15%;text-align: left;">
					结束日期
				</label>
				<div class="col-sm-3">
					<input type="text" name="end_time" id="end_time" class="form-control" value ="<?php echo $end;?>" placeholder="在网站显示的时间" onclick="WdatePicker()">
				</div>
		  </div>
          <button class="btn-primary btn">搜索</button>
          </form>
		 
          <div class="col-xs-12">
		  <table class="table table-hover">
				<thead>                     
                      <tr><td colspan="8" style="text-align:center;font-size:24px;  background: rgb(221, 221, 221);">居间人用户明细</td>
                      </tr><tr>
							<th width="140px">居间人姓名</th>
							<td width="140px"><?php echo $user['real_name'];?></td>
							<th width="140px">居间人手机号</th>
							<td width="140px"><?php echo $user['mobile'];?></td>
							<th width="140px">居间人下属人数</th>
							<td width="140px"><?php echo $total;?></td>
							<th width="140px">居间人等级</th>
							<td width="140px">lv<?php echo $user['lv'];?></td>
					  </tr>
					  <tr>
							<th width="140px">居间人已得收益</th>
							<td width="140px"><?php echo $sumjiesuan;?></td>
							<th width="140px">居间人预计收益</th>
							<td width="140px"><?php echo $sum;?></td>	
							<th width="140px"></th>
							<td width="140px"></td>
							<th width="140px"></th>
							<td width="140px">
							<div class="pull-right">
							<form action="<?php echo site_url('member/invite/ruku_one');?>" style="width:100%">
							<input type="text" name="uid" value = "<?php echo $user['uid'];?>" style="display:none;">
							<input type="text" name="start_time" value ="<?php echo $start;?>" style="display:none;">
							<input type="text" name="end_time" value ="<?php echo $end;?>" style="display:none;">
							<button class="btn-primary btn">结算</button>
							</form></div>
							</td>
					  </tr>
					  </thead>
                  </table>
            <div class="panel panel-midnightblue">
              <div class="panel-heading">
                <div style ="float: left !important;">下属会员列表(注：你查询的只能是整月的区间,你结算只能是上月数据)</div>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>会员ID</th>
                      <th>姓名</th>
                      <th>手机</th>
                      <th>有效收益</th>
                      <th>结算状态</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr>
                      <td><?php echo $v['uid'];?></td>
                      <td><?php echo $v['user_name'];?><?php if($v['real_name'] != ''):?>[<?php echo $v['real_name'];?>]<?php endif;?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo $v['jujianren'];?></td>
                      <td><?php if($v['status'] == '1'):?>已结算<?php else:?>未结算<?php endif;?></td>
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
   <script type="text/javascript" src="/admin/assets/plugins/datepicker/WdatePicker.js">
                </script>
                <script type='text/javascript' src='/admin/assets/js/jquery-migrate-1.2.1.js'>
                </script>
                <script type='text/javascript' src='/admin/assets/plugins/autocomplete/jquery.autocomplete.min.js'>
                </script>
                <link rel="stylesheet" type="text/css" href="/admin/assets/plugins/autocomplete/jquery.autocomplete.css"
                />
				<script type="text/javascript" src="/admin/assets/plugins/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="/admin/assets/plugins/kindeditor/lang/zh_CN.js"></script>

  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>