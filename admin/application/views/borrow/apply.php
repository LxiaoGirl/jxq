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
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入联系人姓名或者手机号码！">
                </div>
              </div>
              <div class="form-group col-xs-2"><input type="text" class="form-control" name="start_date" onClick="WdatePicker()" readonly="true" placeholder="请输入开始时间！" style="cursor: pointer;"></div>
              <div class="form-group col-xs-2"><input type="text" class="form-control" name="end_date" onClick="WdatePicker()" readonly="true" placeholder="请输入结束时间！" style="cursor: pointer;"></div>
                <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">处理状态</div>
                <div class="form-group col-xs-1"><select name="status" class="form-control"><option value="1" <?php if($status == 1):echo 'selected';endif; ?>>已处理</option><option value="0" <?php if($status == 0):echo 'selected';endif; ?>>未处理</option></select></div>
              <button class="btn-primary btn">搜索</button>

              <div class="pull-right">
			  <?php if(authorize('borrow/apply', 'download')):?>
                  <a href="<?php echo site_url('borrow/apply/down');?>" class="btn btn-default"><i class="fa fa-download"></i>下载</a>
				  <?php else:?>
				  <br/><br/><br/>
				  <?php endif;?>
                <a href="<?php echo site_url('borrow/apply');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                      <th>客户姓名</th>
                      <th>手机号码</th>
                      <th>借款主体</th>
                      <th>借款类型</th>
                      <th>所需资金</th>
                      <th>洽谈时间</th>
                      <th>是否处理</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr <?php if($v['status'] == 1):?>class="alert-success" <?php endif;?>>
                      <td><?php echo $v['apply_no'];?></td>
                      <td><?php echo $v['user_name'];?></td>
                      <td><?php echo $v['mobile'];?></td>
                      <td><?php echo nature($v['type']);?></td>
                      <td><?php echo borrow_type($v['p_type']);?></td>
                      <td><?php echo price_format($v['amount']);?></td>
                      <td><?php echo my_date($v['add_time']);?></td>
                      <td><?php echo vehicle($v['status']);?></td>
                      <td>
					  <?php if(authorize('borrow/apply', 'check')):?>	
                      <a href="<?php echo site_url('borrow/apply/detail?apply_no='.$v['apply_no']);?>" title="查看记录"><i class="fa fa-laptop"></i></a>
					  <?php else:?>
					  -
					  <?php endif;?>
                      </td>
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
<script src="/admin/assets/js/datepicker/WdatePicker.js"></script>
</html>