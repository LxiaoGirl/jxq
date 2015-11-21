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
          <li class="active">借款记录</li>
        </ol>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <form action="">
              <div class="form-group col-xs-4">
                <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  <input type="text" name="keyword" class="form-control" placeholder="请输入会员昵称或者借款编号!">
                </div>
              </div>
                <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">项目状态</div>
              <div class="form-group col-xs-1">
                  <select name="status" class="form-control">
                      <option value="-1" <?php if($status == -1 || $status == ''):echo 'selected';endif; ?>>全部</option>
                      <option value="0" <?php if($status === 0):echo 'selected';endif; ?>>待审核</option>
                      <option value="1" <?php if($status == 1):echo 'selected';endif; ?>>已撤回</option>
                      <option value="2" <?php if($status == 2):echo 'selected';endif; ?>>已审核</option>
                      <option value="3" <?php if($status == 3):echo 'selected';endif; ?>>满标</option>
                      <option value="4" <?php if($status == 4):echo 'selected';endif; ?>>还款中</option>
                      <option value="5" <?php if($status == 5):echo 'selected';endif; ?>>流标</option>
                      <option value="6" <?php if($status == 6):echo 'selected';endif; ?>>逾期</option>
                      <option value="7" <?php if($status == 7):echo 'selected';endif; ?>>交易结束</option>
                  </select>
              </div>
                <div  style="  display: inline-block;margin-bottom: 0;font-weight: 600;text-align: center;vertical-align: middle;background-image: none;border: 1px solid transparent;white-space: nowrap;padding: 7px 15px;font-size: 14px;line-height: 1.428571429;border-radius: 1px;-webkit-user-select: none;  float: left;">项目类别</div>
              <div class="form-group col-xs-1">
                <select name="productcategory" class="form-control">
                    <option value="">全部</option>
                    <?php foreach($productcategory as $v):?>
                        <option value="<?php echo $v['cat_id'];?>" <?php if($productcategory_select == $v['cat_id']):echo 'selected';endif; ?>><?php echo $v['category'];?></option>
                    <?php endforeach;?>
                </select>
              </div>
              <button class="btn-primary btn">搜索</button>
              <div class="pull-right">
                <a href="<?php echo site_url('borrow/home');?>" title="返回列表" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>

                <?php if(authorize('borrow/home', 'fabiao')):?>
                <a href="<?php echo site_url('borrow/home/create');?>" title="发布标的" class="btn btn-default"><i class="fa fa-edit"></i>发布标的</a>
               <?php endif;?>

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
                      <th>项目名称</th>
                      <th>会员姓名</th>
                      <th>借款金额</th>
                      <th>年利率</th>
                      <th>融资金额</th>
                      <th>申请时间</th>
                      <th>操作</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if( ! empty($data)):?>
                    <?php foreach($data as $k => $v):?>
                    <tr <?php if($v['status'] == 3):?>style="color:green;"<?php endif;?>>
                      <td><?php echo $v['borrow_no'];?></td>
                      <td>
                      <?php echo borrow_type($v['type']);?> - <?php echo $v['subject'];?>[<?php echo borrow_status($v['status']);?>]
					  <?php if(authorize('borrow/home', 'management')):?>
                      <a href="<?php echo site_url('borrow/home/attachment?borrow_no='.$v['borrow_no']);?>" title="资料管理"><i class="fa fa-chain"></i></a>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'pawn')):?>
                      <?php if($v['type'] == 2):?>
                      <a href="<?php echo site_url('borrow/home/collateral?borrow_no='.$v['borrow_no']);?>" title="抵押物祥情"><i class="fa fa-home"></i></a>
                      <?php endif;?>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'entrust')):?>
                      <a href="<?php echo site_url('borrow/home/agreement?borrow_no='.$v['borrow_no']);?>" title="委托合同编辑"><i class="fa fa-envelope-o"></i></a>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'assignment')):?>
                      <a href="<?php echo site_url('borrow/home/claims?borrow_no='.$v['borrow_no']);?>" title="债权转让合同编辑"><i class="fa fa-share-square-o"></i></a>
					  <?php endif;?>
                      </td>
                      <td><?php echo $v['user_name'];?><?php if( ! empty($v['real_name'])):?>[<?php echo $v['real_name'];?>]<?php endif;?></td>
                      <td><?php echo price_format($v['amount']);?></td>
                      <td><?php echo $v['rate'];?><?php echo (isset($v['real_rate'])) ? '['.$v['real_rate'].']' : '';?>%</td>
                      <td><?php echo price_format($v['receive']);?></td>
                      <td><?php echo my_date($v['add_time']);?></td>
                      <td>
					  <?php if(authorize('borrow/home', 'check')||authorize('borrow/home', 'update')||authorize('borrow/home', 'examine')||authorize('borrow/home', 'delete')||authorize('borrow/home', 'Auditing')):?>
					  <?php if(authorize('borrow/home', 'check')):?>
                      <a href="<?php echo site_url('borrow/home/detail?borrow_no='.$v['borrow_no']);?>" title="查看记录"><i class="fa fa-laptop"></i></a>
					  <?php endif;?>
                      <?php if($v['status'] == 0):?>
					  <?php if(authorize('borrow/home', 'update')):?>
                      <a href="<?php echo site_url('borrow/home/update?borrow_no='.$v['borrow_no']);?>" title="修改记录"><i class="fa fa-edit"></i></a>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'examine')):?>
                      <a href="<?php echo site_url('borrow/home/verify_do?borrow_no='.$v['borrow_no']);?>" title="审核记录"><i class="fa fa-check-square-o"></i></a>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'delete')):?>
                      <a href="<?php echo site_url('borrow/home/delete?borrow_no='.$v['borrow_no']);?>" title="删除记录"><i class="fa fa-trash-o"></i></a>
					  <?php endif;?>
                      <?php endif;?>
					  <?php if(authorize('borrow/home', 'Auditing')):?>
                      <?php if($v['status'] == 3 && $v['payment_no'] == ''):?>
                      <a href="<?php echo site_url('borrow/home/finish?borrow_no='.$v['borrow_no']);?>" title="满标审核"><i class="fa fa-check-square-o"></i></a>
                      <?php endif;?>
					  <?php endif;?>
					  <?php if(authorize('borrow/home', 'update')):?>
                      <?php if($v['status'] >= 2):?>
                      <a href="<?php echo site_url('borrow/home/modify?borrow_no='.$v['borrow_no']);?>" title="资料修改"><i class="fa fa-edit"></i></a>
                      <?php endif;?>
					  <?php endif;?>
					  <?php else:?>
					  -
					  <?php endif;?>
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
</html>