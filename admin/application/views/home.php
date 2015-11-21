<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
<style>.info-tiles .tiles-body{font-size: 25px;}</style>
</head>
<body class="">
<?php $this->load->view('common/topbar');?>
<div id="page-container">
  <?php $this->load->view('common/sidebar');?>
  <div id="page-content">
    <div id='wrap'>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <div class="col-md-3"> <a class="info-tiles tiles-midnightblue" href="#">
              <div class="tiles-heading">
                <div class="pull-left">会员数量</div>
                <div class="pull-right"></div>
              </div>
              <div class="tiles-body">
                <div class="pull-left"><i class="fa fa-user"></i></div>
                <div class="pull-right"><?php echo $member;?></div>
              </div>
              </a> </div>
            <div class="col-md-3"> <a class="info-tiles tiles-midnightblue" href="#">
              <div class="tiles-heading">
                <div class="pull-left">融资金额</div>
                <div class="pull-right"></div>
              </div>
              <div class="tiles-body">
                <div class="pull-left"><i class="fa fa-cny"></i></div>
                <div class="pull-right"><?php echo (isset($borrow)) ? price_format($borrow, 2, FALSE) : 0;?></div>
              </div>
              </a> </div>
            <div class="col-md-3"> <a class="info-tiles tiles-midnightblue" href="#">
              <div class="tiles-heading">
                <div class="pull-left">投资金额</div>
                <div class="pull-right"></div>
              </div>
              <div class="tiles-body">
                <div class="pull-left"><i class="fa fa-cny"></i></div>
                <div class="pull-right"><?php echo (isset($invest)) ? price_format($invest, 2, FALSE) : 0;?></div>
              </div>
              </a> </div>
            <div class="col-md-3"> <a class="info-tiles tiles-midnightblue" href="#">
              <div class="tiles-heading">
                <div class="pull-left">平均利率</div>
                <div class="pull-right"></div>
              </div>
              <div class="tiles-body">
                <div class="pull-left"><i class="fa fa-bar-chart-o"></i></div>
                <div class="pull-right"><?php echo $rate;?>%</div>
              </div>
              </a> </div>
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