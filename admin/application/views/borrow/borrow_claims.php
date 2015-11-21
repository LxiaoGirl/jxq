<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
li { list-style: none; }
.m-trust { width: auto; float: left; height: auto; background: #fff; margin: 20px 0; }
.m-trust .trust_title { font-size: 18px; color: #f57503; padding: 20px; }
.m-trust .trust_jia { float: left; padding: 0 20px; color: #666666; }
.m-trust .trust_jia li { height: 30px; float: left; line-height: 30px; }
.m-trust .trust_content { height: auto; float: left; padding: 10px 20px; line-height: 30px; color: #666666; }
.m-trust .trust_content dt { font-size: 16px; color: #f57503; }
.m-trust .trust_content dd { margin-top: 10px; text-indent: 2em; }
.m-trust .trust_pa dd { width: 500px; height: 120px; float: left; padding: 20px; color: #666666; }
.m-trust .trust_pa dd p { width: 500px; height: 40px; float: left; line-height: 40px; font-size: 16px; padding-bottom: 20px; }
.m-trust .trust_pa dd span { width: 500px; height: 24px; float: left; }
.m-trust .trust_time { text-align: right; height: 30px; float: left; padding-bottom: 20px; font-size: 16px; }
.m-trust .trust_jia span { width: 150px; height: 24px; float: left; text-align: right; padding-right: 20px; }
.m-trust input { width: 250px; height: 24px; border-bottom: 1px solid #666666; padding: 0 10px; font-weight: normal; border-top: none; border-left: none; border-right: none; }
.m-trust .btn_a { width: 100px; }
.m-trust .btn_b { width: 60px; }
.g-wrap { margin: 0 auto; zoom: 1; }
.m-trust .trust_jia span.w190 { width: 170px; height: 22px; line-height: 22px; display: block; text-align: center; border-bottom: 1px solid #666; font-size: 14px; }
span.wa { line-height: 24px; display: inline-block; text-align: center; text-decoration: underline; padding: 0 4px; font-size: 14px; display: inline-block; }
.panel dl dd { line-height: 30px; }
</style>
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
          <li class="active">债权转让协议</li>
        </ol>
        <h1></h1>
        <div class="options"><a href="<?php echo site_url('borrow/home?borrow_no='.$borrow_no);?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a></div>
      </div>
      <div></div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($subject)) ? $user['user_name'].' - '.$subject.'['.$borrow_no.']' : '';?></h4>
            <div class="options"></div>
          </div>
          <div class="panel-body">
          <form id="myform" action="" method="post">
            <div class="g-body">
            <div class=" g-wrap">
            <div class="m-trust">
            <h1 class="trust_title">债权转让协议</h1>
            <ul class="trust_jia">
              <li><span>甲方（出让人）：</span>
                <input name="claims[1]" value="<?php echo judge_empty($claims[1]);?>"/>
              </li>
              <li><span>身份证号：</span>
                <input name="claims[2]" value="<?php echo judge_empty($claims[2]);?>"/>
              </li>
              <li><span>住所地：</span>
                <input name="claims[3]" value="<?php echo judge_empty($claims[3]);?>"/>
              </li>
              <li><span>联系电话：</span>
                <input name="claims[4]" value="<?php echo judge_empty($claims[4]);?>"/>
              </li>
            </ul>
            <ul class="trust_jia">
              <li><span>乙方（受让人）：</span>
                <input name="claims[5]" value="<?php echo judge_empty($claims[5]);?>"/>
              </li>
              <li><span>住所地：</span>
                <input name="claims[6]" value="<?php echo judge_empty($claims[6]);?>"/>
              </li>
              <li><span>法定代表人：</span>
                <input name="claims[7]" value="<?php echo judge_empty($claims[7]);?>"/>
              </li>
              <li><span>联系电话：</span>
                <input name="claims[8]" value="<?php echo judge_empty($claims[8]);?>"/>
              </li>
            </ul>
            <dl class="trust_content">
            <dd>根据《中华人民共和国民法通则》、《中华人民共和国合同法》等相关法律、法规的规定，甲、乙双方遵循自愿、公平、诚实信用的原则，经友好协商，甲方向乙方转让其对第三方
              <input name="claims[10]" value="<?php echo judge_empty($claims[10]);?>"/>
              拥有的债权,现就相关事宜达成一致如下约定：</dd>
            <dd>1、甲方为“网加金服”网站（<b>www.zgwjjf.com</b>、<b>www.zgwjjf.cn</b>，或简称“网加金服服务平台”）的注册用户，注册ID：
              <input name="claims[11]" value="<?php echo judge_empty($claims[11]);?>"/>
              。</dd>
            <dd>2、
              <input name="claims[12]" value="<?php echo judge_empty($claims[12]);?>"/>
              通过网加金服服务平台发布借款
              <input class="btn_a" name="claims[13]" value="<?php echo judge_empty($claims[13]);?>"/>
              元的需求，并以其所有的位于
              <input name="claims[14]" value="<?php echo judge_empty($claims[14]);?>"/>
              为该笔借款提供抵押担保，该笔借款借款期限为
              <input class="btn_b" name="claims[15]" value="<?php echo judge_empty($claims[15]);?>"/>
              月，即从
              <input class="btn_b" name="claims[16]" value="<?php echo judge_empty($claims[16]);?>"/>
              年
              <input class="btn_b" name="claims[17]" value="<?php echo judge_empty($claims[17]);?>"/>
              月
              <input class="btn_b" name="claims[18]" value="<?php echo judge_empty($claims[18]);?>"/>
              日起至
              <input class="btn_b" name="claims[19]" value="<?php echo judge_empty($claims[19]);?>"/>
              年
              <input class="btn_b" name="claims[20]" value="<?php echo judge_empty($claims[20]);?>"/>
              月
              <input class="btn_b" name="claims[21]" value="<?php echo judge_empty($claims[21]);?>"/>
              日止，借款年利率为
              <input class="btn_a" name="claims[22]" value="<?php echo judge_empty($claims[22]);?>"/>
              %。甲方决定以委托借款方式，以
              <input name="claims[23]" value="<?php echo judge_empty($claims[23]);?>"/>
              的名义向
              <input name="claims[24]" value="<?php echo judge_empty($claims[24]);?>"/>
              提供借款
              <input class="btn_a" name="claims[25]" value="<?php echo judge_empty($claims[25]);?>"/>
              元，并与本次共同向
              <input name="claims[26]" value="<?php echo judge_empty($claims[26]);?>"/>
              借款的其他网加金服用户委托
              <input name="claims[27]" value="<?php echo judge_empty($claims[27]);?>"/>
              作为抵押权人办理抵押登记。</dd>
            <dd>3、乙方确认已知悉甲方根据网加金服服务平台注册及服务协议关于资金划转的约定，通过第三方支付机构划转资金，当甲方该笔资金划转至借款人账户时，该笔借款即应视为甲方出借，
              <input name="claims[28]" value="<?php echo judge_empty($claims[28]);?>"/>
              名义上形成的债权及相关权益均归甲方所有。根据甲方与受托出借人
              <input name="claims[29]" value="<?php echo judge_empty($claims[29]);?>"/>
              的约定，甲方有权直接将该笔债权转让给乙方，乙方对此予以确认。</dd>
            <dd>4、乙方承诺在该笔借款清偿完前任何时候均无条件受让该笔债权，甲方只需通过网加金服服务平台确认发出《债权转让确认通知书》，乙方应在接到该通知书之日起三个工作日内将转让价款通过第三方支付平台划转至甲方账户。若甲方在借款期限届满之日以前将该笔债权转让给乙方，转让价款为余下全部本金及利息（根据网加金服服务平台服务协议，按借款合同计算至甲方发出《债权转让通知书》之日止）×0.98，即全部本金及利息的2%作为对受让人的补偿。</dd>
            <dd>5、甲乙双方确认甲方在出借期限期满后借款方未按时偿还本息转让该笔债权时，转让价款为余下全部本金及利息（根据网加金服服务平台服务协议，按借款合同计算至甲方发出《债权转让通知书》。乙方应在接到该通知书之日起五个工作日内将转让价款通过第三方支付平台划转至甲方账户。无需支付任何费用。</dd>
            <dd>6、该笔债权转让后，甲方对该笔债权已无任何权利或派生权利，所有权利由乙方主张，乙方确认其自行与甲方的受托出借人根据受托借款协议的约定行使相关权利。</dd>
            <dd>7、因本协议所引起的任何纠纷及争议，双方先行协商解决，协商未果后双方一致同意，不论争议金额大小和争议性质，均提交重庆仲裁委员会适用重庆仲裁委员会现行仲裁规则进行仲裁。仲裁裁决为终局裁决，对双方均具有法律约束力。</dd>
            <dd>8、双方确认并同意，网加金服服务平台的服务规则（如注册与服务协议、收费规则等）作为本协议附件，与本协议具有同等法律效力。</dd>
            <dd>9、上述协议及其修改或补充均采用通过“网加金服”网站以点击操作形成电子文本形式制成，可以有一份或者多份并且每一份具有同等法律效力，并永久保存在丁方为此设立的专用服务器上备查和保管。各方均认可该形式的协议效力。</dd>
            </div>
            </div>
            </div>
            </div>
            <div class="panel-footer">
              <div class="row">
                <div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
                  <div class="btn-toolbar">
                    <input name="submit" type="submit" value="保存合同" class="btn btn-primary">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
</body>
</html>