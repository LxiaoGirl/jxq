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
          <li><a href="<?php echo site_url('cron/repayment');?>" title="还款列表">还款列表</a></li>
          <li class="active">还款明细</li>
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
                <a href="<?php echo site_url('cron/repayment?day=7');?>" title="一周内" class="btn btn-default">一周内</a>
                <a href="<?php echo site_url('cron/repayment?day=15');?>" title="半月内" class="btn btn-default">半月内</a>
                <a href="<?php echo site_url('cron/repayment?day=30');?>" title="一月内" class="btn btn-default">一月内</a>
                <a href="<?php echo site_url('cron/repayment/repay');?>" title="还款" class="btn btn-default">全部还款</a>
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
							<?php  $start = strtotime(date('Y-m-d',$data['confirm_time']));?>
							<?php  $end = strtotime(date('Y-m-d'));?>
							<?php  $Days = round(($end-$start)/3600/24)+1;?>
                      <thead>                     
                      <td colspan="8"  style="text-align:center;font-size:24px;  background: rgb(221, 221, 221);" >还款明细</td>
                      <tr>
							<th width="140px">项目名称</th>
							<td width="140px"><?php echo($data['subject']);?></td>
							<th width="140px">项目编号</th>
							<td width="140px"><?php echo($data['borrow_no']);?></td>
							<th width="140px">还款方式</th>
							<td width="140px"><?php echo mode_status($data['mode']);?></td>
							<th width="140px">还款期数</th>
							<td width="140px">第<?php echo($data['repay_index']);?>期</td>
					  </tr>
					   <tr>
							<th width="140px">借款人</th>
							<td width="140px"><?php echo($data['real_name']);?>(<?php echo($data['mobile']);?>)</td>
							<th width="140px">借款金额</th>
							<td width="140px"><?php echo($data['amount']);?></td>
							<th width="140px">放款时间</th>
							<td width="140px"><?php echo date('Y/m/d',$data['confirm_time']-24*60*60);?></td>
							<th width="140px">三方支付(0.2%)</th>
							<td width="140px"><?php echo((float)$data['charge'] - round($data['amount']*0.002,2));?></td>
					  </tr>
					   <tr>
							<th width="140px">计划还款日期</th>
							<td width="140px"><?php echo date('Y/m/d',$data['confirm_time']+$data['months']*30*24*60*60-24*60*60);?></td>
							<th width="140px">计划借款天数</th>
							<td width="140px"><?php echo($data['months']*30);?></td>
							<th width="140px">实际还款日期</th>
							<td width="140px"><?php echo date('Y/m/d',$data['rapay_time']);?></td>
							<th width="140px">实际借款天数</th>
							<td width="140px"><?php echo $Days;?>
							</td>
					  </tr>
					   <tr>
							<th width="140px">利率</th>
							<td width="140px"><?php echo($data['rate']);?>%</td>
							<th width="140px">预收管理费(<?php echo($data['real_rate']);?>%)</th>
							<td width="140px"><?php echo $yushou=($data['amount']*$data['real_rate']/100*$data['months']*30/360);?></td>
							<th width="140px">实收管理费(6%)</th>
							<td width="140px"><?php echo $shishou=($data['amount']*6/100*$Days/360);?></td>
							<th width="140px">管理费差额</th>
							<td width="140px"><?php echo round($shishou-$yushou,2);?></td>
					  </tr>
					   <tr>
							<th width="140px">计划利息</th>
							<td width="140px"><?php echo($data['amount']*$data['rate']/100*$data['months']*30/360);?></td>
							<th width="140px">实际利息</th>
							<td width="140px"><?php echo($data['interest'] = sprintf("%.2f",$data['amount']*$data['rate']/100*$Days/360));?></td>
							<th width="140px">应还本金</th>
							<td width="140px"><?php echo($data['amount']);?></td>
							<th width="140px">应还本息合计</th>
							<td width="140px"><?php echo($data['amount']+$data['amount']*$data['rate']/100*$Days/360);?></td>
					  </tr>
					  <tr>
							<th width="140px">三方账户真实资金余额</th>
							<td width="140px"><?php echo($data['CurrentBalance']);?></td>
							<th width="140px">三方账户在途资金</th>
							<td width="140px"><?php echo($data['TransferLimit']);?></td>
							<th width="140px">应还本息管理费<br>计划费用合计<?php echo($data['amount']+$data['amount']*($data['rate']/100)*($data['months']*30)/360-((float)$data['charge'] - round($data['amount']*0.002,2)));?></th>
							<td width="140px"><?php echo($data['amount']+$data['interest']+ round($shishou-$yushou,2)-((float)$data['charge'] - round($data['amount']*0.002,2)));?></td>
							<th width="140px">还款后剩余金额</th>
							<td width="140px"><?php echo $shengyu = ($data['CurrentBalance']-$data['amount']+$data['amount']*$data['rate']/100*$Days/360+$data['amount']*0.06*$Days/360-$data['amount']*$data['real_rate']/100*$data['months']*30/360);?></td>
					  </tr>
					  
					   </thead>
                  </table>
				  <table class="table table-hover" style="margin-top:10px;">
                      <thead> 
                      <td colspan="8"  style="text-align:center;font-size:24px;  background: rgb(221, 221, 221);margin: 10px 0;" >投资收益合计</td>
					   <tr>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px">本金合计</td>
							<th width="140px">利息合计</th>
							<th width="140px"></td>
					  </tr>
					  <tr>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px"><?php echo $interest['sum'];?></td>
							<th width="140px"><?php echo $interest['interest'];?></th>
							<th width="140px"></td>
					  </tr>
					  </thead>
                  </table>
				  <table class="table table-hover" style="margin-top:10px;">
                      <thead> 
					  <tr>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px"></th>
							<th width="140px"></td>
							<th width="140px">
							<?php if( $shengyu>0):?>					  
							<a href="<?php echo site_url('cron/repayment/repay_one?borrow_no='.$data['borrow_no']) ?>" title="还款" class="btn btn-default">还款</a>
							<?php endif;?>					  
							</th>
							<th width="140px"></td>
					  </tr>
					  </thead>
                  </table>
				  <table class="table table-hover">
                      <thead> 
					   <td colspan="8"  style="text-align:center;font-size:24px;  background: rgb(221, 221, 221);" >投资人列表</td>
					   <tr>
							<th width="140px">账户状态</th>
							<th width="140px">联系电话</td>
							<th width="140px">投资人</th>
							<th width="140px">投资时间</td>
							<th width="140px">投资比例</th>
							<th width="140px">投入本金</td>
							<th width="140px">投资收益</th>
							<th width="140px">还款状态</td>
					  </tr>
					  </thead>
                  </table>		
					<div style="height: 200px;width: 100%;overflow: auto;">
                       <table class="table table-hover" id="B15061201249323" >
							<?php if( ! empty($interest)):?>					  
							  <?php foreach($interest as $key => $val):?>
							  <?php if( ! empty($val)):?>
								  <?php foreach($val as $k => $v):?>
									  <tr <?php if($v['is_pay'] == 1):?> class="alert-info" <?php endif;?> <?php if($v['statusid'] == 2):?> style="" <?php endif;?> >
										  <td width="140px"><?php echo $v['status'];?></td>
										  <td width="140px"><?php echo $v['mobile'];?></td>
										  <td width="140px"><?php echo $v['user_name'];?></td>
										  <td width="140px"><?php echo date('Y/m/d H:m',$v['pay_time']);?></td>
										  <td width="140px"><?php echo $v['interest_bili'].'%';?></td>
										  <td width="140px"><?php echo $v['amount'];?></td>
										  <td width="140px"><?php echo $v['interest'];?></td>
										  <td width="140px"><?php if($v['is_pay'] == 1):echo '已支付';else:echo '未支付';endif;?></td>
									  </tr>
								  <?php endforeach;?>
							  <?php else:?>
								  <tr>
									  <td colspan="6">
										  <div class="alert alert-dismissable alert-info"> <strong>小聚提醒：</strong> 暂无相关记录！
										  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>
									  </td>
								  </tr>
							  <?php endif;?>
							<?php endforeach;?>
						  <?php endif;?>					  
						  </div>
						</table>						
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