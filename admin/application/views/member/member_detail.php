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
          <li class="active">会员详情</li>
        </ol>
        <h1></h1>
        <div class="options">
          <a href="<?php echo site_url('member');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
		  <?php if(authorize('member/home', 'update')):?>
          <a href="<?php echo site_url('member/home/update?uid='.$uid);?>" class="btn btn-default"><i class="fa fa-edit"></i>编辑</a>
		  <?php endif;?>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
          <?php echo (isset($user_name)) ? $user_name : '';?><?php echo ( ! empty($real_name)) ? '['.$real_name.']' : '';?>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6"> <img src="<?php if( ! empty($avater)):?><?php echo $avater;?><?php else:?>/admin/assets/img/avatar.png<?php endif;?>" width="100" height="100" alt="" class="pull-left" style="margin: 0 20px 20px 0">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                      <tr>
                        <th width="30%"></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>真实姓名：</td>
                        <td><?php echo (isset($real_name)) ? $real_name : '';?></td>
                      </tr>
                      <tr>
                        <td>性别：</td>
                        <td><?php echo (isset($gender)) ? gender($gender) : '';?></td>
                      </tr>
                      <tr>
                        <td>手机号码：</td>
                        <td><?php echo (isset($mobile)) ? $mobile : '';?></td>
                      </tr>
                      <tr>
                        <td>身份证号码：</td>
                        <td><?php echo (isset($nric)) ? $nric : '';?></td>
                      </tr>
                      <tr>
                        <td>电话号码：</td>
                        <td><?php echo (isset($phone)) ? $phone : '';?></td>
                      </tr>
                      <tr>
                        <td>邮箱地址：</td>
                        <td><?php echo (isset($email)) ? $email : '';?></td>
                      </tr>
                      <tr>
                        <td>提成比例：</td>
                        <td><?php echo (isset($rate) && $rate > 0) ? $rate.'%' : '';?></td>
                      </tr>
                      <tr>
                        <td>可用余额:</td>
                        <td><?php echo (isset($balance)) ? price_format($balance, 2) : 0;?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-6">
                <h3>其它信息</h3>
                <p>会员状态：<?php echo (isset($status)) ? user_status($status) : '';?></p>
                <p>联系地址: <?php echo (isset($address)) ? address($address, 3) : '';?></p>
                <p>居住地址：<?php echo (isset($address)) ? address($address, 2) : '';?></p>
                <p>户籍地址：<?php echo (isset($address)) ?  address($address, 1) : '';?></p>
                <p>单位地址：<?php echo (isset($address)) ?  address($address, 4) : '';?></p>
                <p></p>
              </div>
            </div>
            <div class="row">
            <div class="alert alert-dismissable alert-info">
                <strong>友情提示：</strong> 点击编号可以查看数据详情,只显示最近10条记录！
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              </div>
              <div class="col-md-12">
                <div class="tab-container tab-success">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#borrow" data-toggle="tab">借款记录</a></li>
                    <li class=""><a href="#invest" data-toggle="tab">投资记录</a></li>
                    <li class=""><a href="#refund" data-toggle="tab">还款记录</a></li>
                    <li class=""><a href="#rechage" data-toggle="tab">充值记录</a></li>
                    <li class=""><a href="#transaction" data-toggle="tab">提现记录</a></li>
                  </ul>
                  <div class="tab-content" style="border:none;padding: 10px 0;">
                    <div class="tab-pane active" id="borrow">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>项目名称</th>
                              <th>类型</th>
                              <th>期限(月)</th>
                              <th>借款金额</th>
                              <th>年利率</th>
                              <th>投资总额</th>
                              <th>申请时间</th>
                              <th>状态</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if(! empty($borrow)):?>
                            <?php foreach($borrow as $k => $v):?>
                            <tr>
                              <td><a href="<?php echo site_url('borrow/home/detail?borrow_no='.$v['borrow_no']);?>" title="查看详情"><?php echo $v['borrow_no'];?></a></td>
                              <td><?php echo $v['subject'];?></td>
                              <td><?php echo borrow_type($v['type']);?></td>
                              <td><?php echo $v['months'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo $v['rate'];?>%</td>
                              <td><?php echo price_format($v['receive']);?></td>
                              <td><?php echo my_date($v['add_time']);?></td>
                              <td><?php echo borrow_status($v['status']);?></td>
                            </tr>
                            <?php endforeach;?>
                          <?php else:?>
                            <tr><td colspan="9">暂无相关记录!</td></tr>
                          <?php endif;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane clearfix" id="invest">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>投资项目</th>
                              <th>投资金额</th>
                              <th>年利率</th>
                              <th>日收益</th>
                              <th>申请时间</th>
                              <th>记录状态</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if(! empty($invest)):?>
                            <?php foreach($invest as $k => $v):?>
                            <tr>
                              <td><a href="<?php echo site_url('borrow/home/detail?borrow_no='.$v['borrow_no']);?>" title="查看详情"><?php echo $v['payment_no'];?></a></td>
                              <td><?php echo $v['subject'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo $v['rate'];?>%</td>
                              <td><?php echo price_format($v['interest']);?></td>
                              <td><?php echo my_date($v['dateline']);?></td>
                              <td><?php echo borrow_status($v['status']);?></td>
                            </tr>
                            <?php endforeach;?>
                          <?php else:?>
                            <tr><td colspan="7">暂无相关记录!</td></tr>
                          <?php endif;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane clearfix" id="refund">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>项目名称</th>
                              <th>还款金额</th>
                              <th>年利率</th>
                              <th>待还金额</th>
                              <th>还款时间</th>
                              <th>记录状态</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if(! empty($refund)):?>
                            <?php foreach($refund as $k => $v):?>
                            <tr>
                              <td><a href="<?php echo site_url('borrow/home/detail?borrow_no='.$v['borrow_no']);?>" title="查看详情"><?php echo $v['payment_no'];?></a></td>
                              <td><?php echo $v['subject'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo $v['rate'];?>%</td>
                              <td><?php echo price_format($v['balance']);?></td>
                              <td><?php echo my_date($v['dateline']);?></td>
                              <td><?php echo borrow_status($v['status']);?></td>
                            </tr>
                            <?php endforeach;?>
                          <?php else:?>
                            <tr><td colspan="7">暂无相关记录!</td></tr>
                          <?php endif;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane clearfix" id="rechage">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>充值金额</th>
                              <th>充值方式</th>
                              <th>来源编号</th>
                              <th>充值时间</th>
                              <th>记录状态</th>
                            </tr>
                          </thead>
                          <tbody>
                          <?php if(! empty($rechage)):?>
                            <?php foreach($rechage as $k => $v):?>
                            <tr>
                              <td><?php echo $v['recharge_no'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo recharge_type($v['type']);?></td>
                              <td><?php echo ( ! empty($v['source'])) ? $v['source'] : '-';?></td>
                              <td><?php echo my_date($v['add_time']);?></td>
                              <td><?php echo recharge_status($v['status'], $v['type']);?></td>
                            </tr>
                            <?php endforeach;?>
                          <?php else:?>
                            <tr><td colspan="7">暂无相关记录!</td></tr>
                          <?php endif;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane clearfix" id="transaction">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th width="5%">#</th>
                              <th>提现金额</th>
                              <th>手续费</th>
                              <th>账户信息</th>
                              <th>提现时间</th>
                              <th>记录状态</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if(! empty($transaction)):?>
                            <?php foreach($transaction as $k => $v):?>
                            <tr>
                              <td><?php echo $v['transaction_no'];?></td>
                              <td><?php echo price_format($v['amount']);?></td>
                              <td><?php echo price_format($v['charge']);?></td>
                              <td><?php echo $v['real_name'].' - '.$v['bank_name'].'['.$v['account'].']' ;?></td>
                              <td><?php echo my_date($v['add_time']);?></td>
                              <td><?php echo status($v['status']);?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php else:?>
                            <tr>
                              <td colspan="7">暂无相关记录!</td>
                            </tr>
                            <?php endif;?>
                          </tbody>
                        </table>
                      </div>
                    </div>
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