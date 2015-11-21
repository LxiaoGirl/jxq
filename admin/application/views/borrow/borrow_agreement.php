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
          <li class="active">委托借款协议</li>
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
                  <h1 class="trust_title">委托借款协议</h1>
                  <ul class="trust_jia">
                    <li><span>甲方（委托人）：</span>
                      <input name="agreement[1]" value="<?php echo judge_empty($agreement[1]);?>"/>
                    </li>
                    <li><span>身份证号：</span>
                      <input name="agreement[41]" value="<?php echo judge_empty($agreement[41]);?>"/>
                    </li>
                    <li><span>住所地：</span>
                      <input name="agreement[2]" value="<?php echo judge_empty($agreement[2]);?>"/>
                    </li>
                    <li><span>法定代表人：</span>
                      <input name="agreement[3]" value="<?php echo judge_empty($agreement[3]);?>"/>
                    </li>
                    <li><span>联系电话：</span>
                      <input name="agreement[4]" value="<?php echo judge_empty($agreement[4]);?>"/>
                    </li>
                  </ul>
                  <ul class="trust_jia">
                    <li><span>乙方（受托人）：</span>
                      <input name="agreement[5]" value="<?php echo judge_empty($agreement[5]);?>"/>
                    </li>
                    <li><span>身份证号：</span>
                      <input name="agreement[6]" value="<?php echo judge_empty($agreement[6]);?>"/>
                    </li>
                    <li><span>住所地：</span>
                      <input name="agreement[7]" value="<?php echo judge_empty($agreement[7]);?>"/>
                    </li>
                    <li><span>联系电话：</span>
                      <input name="agreement[8]" value="<?php echo judge_empty($agreement[8]);?>"/>
                    </li>
                  </ul>
                  <dl class="trust_content">
                    <dt>鉴于：</dt>
                    <dd>1、甲方是“网加金服”网站（<b>www.zgwjjf.com</b>、<b>www.zgwjjf.cn</b>，或简称“网加金服服务平台”）的注册用户，注册ID：
                      <input name="agreement[9]" value="<?php echo judge_empty($agreement[9]);?>"/>
                      。</dd>
                    <dd>2、
                      <input name="agreement[10]" value="<?php echo judge_empty($agreement[10]);?>"/>
                      通过网加金服服务平台发布借款（大写）
                      <input class="btn_a" name="agreement[11]" value="<?php echo judge_empty($agreement[11]);?>"/>
                      需求，并以其所有的位于
                      <input name="agreement[12]" value="<?php echo judge_empty($agreement[12]);?>"/>
                      为该笔借款提供抵押担保，该借款用于
                      <input name="agreement[40]" value="<?php echo judge_empty($agreement[40]);?>"/>
                      。因办理抵押登记的需要，甲方委托乙方以乙方名义向
                      <input name="agreement[38]" value="<?php echo judge_empty($agreement[38]);?>"/>
                      提供借款
                      <input class="btn_a" name="agreement[13]" value="<?php echo judge_empty($agreement[13]);?>"/>
                      元，并与本次共同向
                      <input name="agreement[14]" value="<?php echo judge_empty($agreement[14]);?>"/>
                      借款的其他网加金服用户委托乙方作为抵押权人办理抵押登记；</dd>
                    <dd>3、甲方根据网加金服服务平台注册及服务协议关于资金划转的约定，通过第三方支付机构划转资金，当甲方该笔资金划转至借款人账户，该笔借款即应视为甲方出借，乙方对
                      <input name="agreement[15]" value="<?php echo judge_empty($agreement[15]);?>"/>
                      名义上形成的债权及相关权益均归甲方所有。</dd>
                    <dd>甲、乙双方有着良好的合作意愿，经双方友好协商，就甲方委托乙方代为向
                      <input name="agreement[16]" value="<?php echo judge_empty($agreement[16]);?>"/>
                      提供借款事宜达成协议如下：</dd>
                  </dl>
                  <dl class="trust_content">
                    <dt>一、委托内容</dt>
                    <dd>甲方委托乙方作为向
                      <input name="agreement[17]" value="<?php echo judge_empty($agreement[17]);?>"/>
                      提供借款的名义债权人，甲方向
                      <input name="agreement[18]" value="<?php echo judge_empty($agreement[18]);?>"/>
                      提供借款人民币
                      <input class="btn_a" name="agreement[19]" value="<?php echo judge_empty($agreement[19]);?>"/>
                      元（大写：人民币
                      <input class="btn_a" name="agreement[20]" value="<?php echo judge_empty($agreement[20]);?>"/>
                      ）。该笔借款通过第三方支付从甲方账户到借款人账户，即视为甲方已实际出借此笔款项。</dd>
                  </dl>
                  <dl class="trust_content">
                    <dt>二、借款条件</dt>
                    <dd>上述借款的借款利率、借款期限、还款付息方式、担保保证等重要内容均以乙方与
                      <input name="agreement[21]" value="<?php echo judge_empty($agreement[21]);?>"/>
                      签订的《借款合同》、《担保合同》等协议约定为准，乙方同意配合签署并依约履行，对此无任何异议。</dd>
                  </dl>
                  <dl class="trust_content">
                    <dt>三、双方权利与义务</dt>
                    <dd>1、甲方作为上述债权的实际出资人，享有实际的债权人权益并有权获得相应的利息收益，甲方有权自行处分该笔债权或其派生权益（如转让、质押等）；乙方对甲方实际的债权人权益不享有任何收益权或处分权。</dd>
                    <dd>2、作为上述借款的名义债权人，乙方承诺受到本协议内容的限制；乙方承诺该笔借款所产生的全部收益和未来借款本金的归还，均通过第三方支付直接由借款人账户划转至甲方账户。</dd>
                    <dd>3、乙方确认其在本协议中对
                      <input name="agreement[22]" value="<?php echo judge_empty($agreement[22]);?>"/>
                      的债权并非乙方个人权益。乙方确认在获得甲方书面授权或许可前，不得将其上述债权及其派生的权益以其他任何方式处分，也不得实施任何损害或可能损害甲方利益的其他行为。</dd>
                    <dd>4、乙方确认其已知悉甲方与
                      <input name="agreement[23]" value="<?php echo judge_empty($agreement[23]);?>"/>
                      已签订《债权转让协议》，即甲方有权在借款期限届满日前任何时候或在借款人未按时支付本息时将该笔借款转让给
                      <input name="agreement[24]" value="<?php echo judge_empty($agreement[24]);?>"/>
                      ，乙方对此无条件配合，债权转让的款项也通过第三方支付机构直接由受让人划转至甲方账户。</dd>
                    <dd>5、甲方作为对
                      <input name="agreement[25]" value="<?php echo judge_empty($agreement[25]);?>"/>
                      债权的实际拥有人，有权对乙方不适当的受托行为进行监督和纠正。借款逾期情形下，乙方应无条件按照甲方的要求以自己的名义通过法律途径向借款人追还借款，以最大限度保障甲方的合法权益。</dd>
                    <dd>6、作为受托人，乙方不得利用名义债权人身份为自己或其利害关系人牟取任何利益，否则应相应地将该利益转交给甲方或向甲方作等值赔偿。若乙方前述行为损害公司利益的，应另行赔偿公司的损失。</dd>
                    <dd>7、乙方作为受托人及名义债权人，不收取甲方任何委托费用。</dd>
                  </dl>
                  <dl class="trust_content">
                    <dt>四、保密条款</dt>
                    <dd>协议双方对本协议履行过程中所接触或获知的对方的任何商业信息均有保密义务，除非有明显的证据证明该信息属于公知信息或者事先得到对方的书面授权。该保密义务在本协议终止后仍然继续有效。同时，乙方不得泄露其因名义股东身份获知的有关公司的非公开信息。</dd>
                  </dl>
                  <dl class="trust_content">
                    <dt>五、其他事项</dt>
                    <dd>1、因本协议所引起的任何纠纷及争议，双方先行协商解决，协商未果后双方一致同意，不论争议金额大小和争议性质，均提交重庆仲裁委员会适用重庆仲裁委员会现行仲裁规则进行仲裁。仲裁裁决为终局裁决，对双方均具有法律约束力。</dd>
                    <dd>2、双方确认并同意，网加金服服务平台的服务规则（如注册与服务协议、收费规则等）作为本协议附件，与本协议具有同等法律效力。</dd>
                    <dd> 您于
                      <input class="btn_b" name="agreement[26]" value="<?php echo judge_empty($agreement[26]);?>"/>
                      年
                      <input class="btn_b" name="agreement[27]" value="<?php echo judge_empty($agreement[27]);?>"/>
                      月
                      <input class="btn_b" name="agreement[28]" value="<?php echo judge_empty($agreement[28]);?>"/>
                      日在“网加金服”平台上确认向项目借款人
                      <input name="agreement[30]" value="<?php echo judge_empty($agreement[30]);?>"/>
                      出借
                      <input class="btn_a" name="agreement[31]" value="<?php echo judge_empty($agreement[31]);?>"/>
                      元，现该项目已达到平台规定融资的最低额度，您委托
                      <input class="btn_a" name="agreement[32]" value="<?php echo judge_empty($agreement[32]);?>"/>
                      出借
                      <input class="btn_a" name="agreement[33]" value="<?php echo judge_empty($agreement[33]);?>"/>
                      元已支付至借款人
                      <input class="btn_b" name="agreement[34]" value="<?php echo judge_empty($agreement[34]);?>"/>
                      指定的账户，年化利率是
                      <input class="btn_b" name="agreement[39]" value="<?php echo judge_empty($agreement[39]);?>"/>
                      %，《借款合同》、《抵押合同》、《委托借款协议》等相关协议均已生效并发生法律效力，您的出借款从
                      <input class="btn_b" name="agreement[35]" value="<?php echo judge_empty($agreement[35]);?>"/>
                      年
                      <input class="btn_b" name="agreement[36]" value="<?php echo judge_empty($agreement[36]);?>"/>
                      月
                      <input class="btn_b" name="agreement[37]" value="<?php echo judge_empty($agreement[37]);?>"/>
                      日开始计算利息。</dd>
                  </dl>
                  <dl class="trust_content">
                    <dd>上述协议及其修改或补充均采用通过“网加金服”网站以点击操作形成电子文本形式制成，可以有一份或者多份并且每一份具有同等法律效力，并永久保存在网加金服为此设立的专用服务器上备查和保管。各方均认可该形式的协议效力。</dd>
                  </dl>
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