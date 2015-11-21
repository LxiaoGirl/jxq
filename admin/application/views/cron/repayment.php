<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
<script type="text/javascript" src="/admin/assets/js/jquery-1.10.2.min.js"></script>
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
          <li><a href="<?php echo site_url('cron/repayment');?>" title="借款管理">还款管理</a></li>
          <li class="active">还款列表</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">

          <div class="col-xs-12">
            <form action="">
              <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">项目类别</div>
              <div class="form-group col-xs-1">
                  <select name="productcategory" id="productcategory" class="form-control">
                      <option value="">全部</option>
                      <?php foreach($productcategory as $v):?>
                          <option value="<?php echo $v['cat_id'];?>" <?php if($productcategory_select == $v['cat_id']):echo 'selected';endif; ?>><?php echo $v['category'];?></option>
                      <?php endforeach;?>
                  </select>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
			    <?php if(authorize('cron/repayment', 'overdue')):?>
                <a href="<?php echo site_url('cron/repayment?day=-30');?>" title="逾期" class="btn btn-default">逾期</a>
                <a href="<?php echo site_url('cron/repayment?day=7');?>" title="一周内" class="btn btn-default">一周内</a>
                <a href="<?php echo site_url('cron/repayment?day=15');?>" title="半月内" class="btn btn-default">半月内</a>
                <a href="<?php echo site_url('cron/repayment?day=30');?>" title="一月内" class="btn btn-default">一月内</a>
				<?php endif;?>
				<?php if(authorize('cron/repayment', 'repayment')):?>
                <a href="<?php echo site_url('cron/repayment/repay');?>" title="还款" class="btn btn-default">全部还款</a>
				<?php endif;?>
                <a href="<?php echo site_url('cron/repayment');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
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
                          <th width="140px">#</th>
                          <th width="110px">借款人姓名</th>
                          <th width="110px">手机</th>
                          <th width="100px">还款期数</th>
                          <th width="100px">还款日期</th>
                          <th width="100px">借款总额</th>
                          <th width="100px">利率</th>
                          <th width="100px">借款期数</th>
                          <th width="100px">当前余额</th>
                          <th width="100px">应付利息</th>
                          <th width="100px">还款金额</th>
                          <th width="100px">预计还款应还金额</th>
                          <th width="100px">还款方式</th>
                          <th width="100px">还款状态</th>
                          <th width="100px">今日还款所需利息</th>
                          <th width="100px">操作</th>
                      </tr>
                      </thead>
                  </table>
                  <div  style="height: 300px;width: 100%;overflow: auto;">
                  <table class="table table-hover">
                    <tbody>
                      <?php if( ! empty($data)):?>
                      <?php foreach($data as $k => $v):?>
                            <tr <?php if($v['is_pay'] == 1):?>class="alert-success" <?php endif;?> >

                      <td width="140px"><?php echo $v['subject'];?></td>
                      <td width="110px"><?php echo $v['user_name'];?></td>
                      <td width="110px"><?php echo $v['mobile'];?></td>
                        <td width="100px"><?php echo '第'.$v['month'].'期';?></td>
                        <td width="100px"><?php echo $v['repay_date'];?></td>
                        <td width="100px"><?php echo $v['amount'];?></td>
                        <td width="100px"><?php echo $v['rate'].'%';?></td>
                        <td width="100px"><?php echo '总'.$v['months'].'期';?></td>
                        <td width="100px"><?php echo $v['balance'];?></td>
                        <td width="100px"><?php echo $v['interest'];?></td>
                        <td width="100px"><?php echo $v['sum'];?></td>
						
                        <td width="100px" style="background: red;  font-color: white;  color: #FFFFFF;"><?php echo $v['sum']+$v['amount']*0.002+$v['amount']*0.06*27/360;?></td>
						
                      <td width="100px"><?php if($v['mode'] == 3):echo '一次性还本付息'; endif;if($v['mode'] == 1):echo '先息后本'; endif;if($v['mode'] == 4):echo '等额本金'; endif;if($v['mode'] == 2):echo '等额本息'; endif;?></td>
                      <td width="100px"><?php if($v['is_pay'] == 1):echo '已还款';else:if($v['is_repay'] == 1):echo '已预付';elseif($v['balance']-$v['interest'] < 0):echo '余额不足';else:echo '已缴费，未还款'; endif; endif;?></td>
                         <td width="100px"><?php echo $v['dayinterest'];?></td>
                      <td width="100px">
					  <?php if($v['is_pay'] == 1):echo '已还款';else:if($v['is_repay'] == 1):echo '已预付';elseif($v['balance']-$v['interest'] < 0):echo '请提醒借款人充值';else: ?><?php endif; endif;?>
					  <?php if(authorize('cron/repayment', 'check')):?>
					  <a href="<?php echo site_url('cron/repayment/detail?borrow_no='.$v['borrow_no']) ?>">查看明细</a>
					  <?php endif;?>
                    </tr>
                    <?php endforeach;?>
                      <?php else:?>
                    <tr>
                      <td colspan="13"><div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        </div></td>
                    </tr>
                    <?php endif;?>
                    </tbody>
                  </table>
                  </div>

                  <table class="table" id="sub-table-head" style="display: none;">
                      <thead>
                      <tr>
                          <th width="140px">#</th>
                          <th width="140px">投资人姓名</th>
                          <th width="140px">手机</th>
                          <th width="140px">投资金额</th>
                          <th width="140px">投资比例</th>
                          <th width="140px">收益</th>
                          <th width="140px">状态</th>
                      </tr>
                      </thead>
                  </table>
                  <div style="height: 200px;width: 100%;overflow: auto;">
                      <?php if( ! empty($interest)):?>
                          <?php foreach($interest as $key => $val):?>
                        <table class="table table-hover" id="<?php echo $key; ?>" style="display: none;">
                          <tbody>
                          <?php if( ! empty($val)):?>
                              <?php foreach($val as $k => $v):?>
                                  <tr <?php if($v['is_pay'] == 1):?> class="alert-info" <?php endif;?>>
                                      <td width="140px"><?php echo $v['payment_no'];?></td>
                                      <td width="140px"><?php echo $v['user_name'];?></td>
                                      <td width="140px"><?php echo $v['mobile'];?></td>
                                      <td width="140px"><?php echo $v['amount'];?></td>
                                      <td width="140px"><?php echo $v['interest_bili'].'%';?></td>
                                      <td width="140px"><?php echo $v['interest'];?></td>
                                      <td width="140px"><?php if($v['is_pay'] == 1):echo '已支付收益';else:echo '未支付收益';endif;?></td>
                                  </tr>
                              <?php endforeach;?>
                          <?php else:?>
                              <tr>
                                  <td colspan="6">
                                      <div class="alert alert-dismissable alert-info"> <strong>小易提醒：</strong> 暂无相关记录！
                                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>
                                  </td>
                              </tr>
                          <?php endif;?>
                          </tbody>
                        </table>
                        <?php endforeach;?>
                      <?php endif;?>
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