<!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge; chrome=1"/>
    <title>委托借款协议</title>
    <meta name="description" content="网加 zgwjjf.com - 提供安全、高效的互联网理财融资服务。有严格的风险控制,100%本息担保,第三方资金托管,保障资金安全。"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="Shortcut Icon" href="<?php echo assets('images/bitbug_favicon.ico'); ?>">
    <meta name="viewport"
      content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
      <?php $this->load->view('common/apps/app_head') ?>
    <?php echo load_file('style.css,style_addin.css'); ?>
    <!--[if lt IE 9]>
    <?php echo load_file('styleForIE.css');?>
    <![endif]-->
    <style type="text/css">
        .m-trust {
            width: 100%;
        }
        .m-trust .trust_jia li {
            width: 100%;
            height: auto;
        }
        .m-trust .trust_jia {
            width: 90%;
            height: auto; 
            float: none;
            padding: 20px;
            color: #666666;
        }
        .m-trust .trust_content {
            width: 90%;
        }
        .m-trust .trust_jia span {
            height: auto;
            float: none;
            padding-right: 20px;
        }
    </style>
</head>
<body>
<div class="g-body" style="width:100%;" id="g-body">
    <div class=" g-wrap" style="width:100%;">
        <div class="m-trust">
            <h1 class="trust_title">委托借款协议</h1>
            <ul class="trust_jia">
                <li>
                    <span>甲方（委托人）：</span><br/><em><?php echo judge_empty($data['agreement'][1], $data['a_real_name']); ?></em>
                </li>
                <li>
                    <span>身份证号：</span><br/><em><?php echo judge_empty($data['agreement'][41], $data['a_nric']); ?></em>
                </li>
                <li>
                    <span>住所地：</span><br/><em><?php echo judge_empty($data['agreement'][2], $data['a_addr']); ?></em>
                </li>
                <li>
                    <span>法定代表人：</span><br/><em><?php echo judge_empty($data['agreement'][3], $data['a_real_name']); ?></em>
                </li>
                <li>
                    <span>联系电话：</span><br/><em><?php echo judge_empty($data['agreement'][4], $data['a_mobile']); ?></em>
                </li>
            </ul>
            <ul class="trust_jia">
                <li>
                    <span>乙方（受托人）：</span><br/><em><?php echo judge_empty($data['agreement'][5], $data['b_real_name']); ?></em>
                </li>
                <li><span>身份证号：</span><br/><em><?php echo judge_empty($data['agreement'][6], $data['b_nric']); ?></em></li>
                <li>
                    <span>住所地：</span><br/><em><?php echo judge_empty($data['agreement'][7], $data['b_addr']); ?></em>
                </li>
                <li><span>联系电话：</span><br/><em><?php echo judge_empty($data['agreement'][8], $data['b_mobile']); ?></em>
                </li>
            </ul>
            <dl class="trust_content">

                <!--<dt>定义：</dt>
                <dd>1、互联网平台：指甲方运营管理的网站，网址www.zgwjjf.com 或 www.zgwjjf.cn，负责为互联网金融交易提供信息服务，并向交易各方提供资金清结算数据的统计服务。</dd>
                <dd>2、投资人：指通过互联网平台成功注册账户的会员，有合法来源的闲余资金，可参考互联网平台的推荐自主选择出借一定金额的资金给投资接收人，且具有完全民事权利和行为能力的自然人。</dd>
                <dd>3、合作机构：指与甲方建立合作关系的机构，包括但不限于小额贷款公司、融资性担保公司、第三方支付机构、银行等。</dd>
                <dd>4、投资接收人：指有一定的资金需求，经过担保公司信用评估后筛选推荐、在互联网平台注册账户，由互联网平台推荐投资人并得到投资人资金，且具有完全民事权利及行为能力的自然人或法人。</dd>
                <dd>5、借款：指投资人拟向投资接受人提供的资金。</dd>
                <dd>6、监管账户：以甲方名义在第三方支付机构或资金监管银行开立的、账户内资金独立于甲方其他资金的监管账户。</dd>
                <dd>7、互联网平台账户：指投资人或投资接受人以自身名义在互联网平台注册后系统自动产生的虚拟账户，通过第三方支付机构及/或其他通道进行充值或提现。</dd>
                <dd>8、《借款合同》：指通过约定由投资接受人通过甲方互联网平台向乙方借款、并由担保方承担逾期代偿责任等事宜的借款合同。</dd>
                <dd>9、投资人：指通过互联网平台成功注册账户的会员，有合法来源的闲余资金，可参考互联网平台的推荐自主选择出借一定金额的资金给投资接收人，且具有完全民事权利和行为能力的自然人。</dd>
                -->
                <dt>鉴于：</dt>
                <dd>1、甲方是“网加”网站（<b>www.zgwjjf.com</b>、<b>www.zgwjjf.cn</b>，或简称“网加服务平台”）的注册用户，注册ID：
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][9], $data['a_mobile']); ?></em>
                    。
                </dd>
                <dd>2、
                    <em><?php echo judge_empty($data['agreement'][10], $data['b_real_name_nric']); ?></em>
                    通过网加服务平台发布借款（大写）
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][11], $data['amount_upper']); ?></em>
                    需求，并以其所有的位于
                    <em><?php echo judge_empty($data['agreement'][12]); ?></em>
                    为该笔借款提供抵押担保，该借款用于
                    <em><?php echo judge_empty($data['agreement'][40]); ?></em>
                    。因办理抵押登记的需要，甲方委托乙方以乙方名义向
                    <em><?php echo judge_empty($data['agreement'][38]); ?></em>
                    提供借款
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][13], $data['invest_amount']); ?></em>
                    元，并与本次共同向
                    <em><?php echo judge_empty($data['agreement'][14], $data['b_real_name_nric']); ?></em>
                    借款的其他网加用户委托乙方作为抵押权人办理抵押登记；
                </dd>
                <dd>3、甲方根据网加服务平台注册及服务协议关于资金划转的约定，通过第三方支付机构划转资金，当甲方该笔资金划转至借款人账户，该笔借款即应视为甲方出借，乙方对
                    <em><?php echo judge_empty($data['agreement'][15], $data['b_real_name_nric']); ?></em>
                    名义上形成的债权及相关权益均归甲方所有。
                </dd>
                <dd>甲、乙双方有着良好的合作意愿，经双方友好协商，就甲方委托乙方代为向
                    <em><?php echo judge_empty($data['agreement'][16], $data['b_real_name_nric']); ?></em>
                    提供借款事宜达成协议如下：
                </dd>
            </dl>
            <dl class="trust_content">
                <dt>一、委托内容</dt>
                <dd>甲方委托乙方作为向
                    <em><?php echo judge_empty($data['agreement'][17], $data['b_nric']); ?></em>
                    提供借款的名义债权人，甲方向
                    <em><?php echo judge_empty($data['agreement'][18], $data['b_nric']); ?></em>
                    提供借款人民币
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][19], $data['invest_amount']); ?></em>
                    元（大写：人民币
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][20], $data['invest_amount_upper']); ?></em>
                    ）。该笔借款通过第三方支付从甲方账户到借款人账户，即视为甲方已实际出借此笔款项。
                </dd>
            </dl>
            <dl class="trust_content">
                <dt>二、借款条件</dt>
                <dd>上述借款的借款利率、借款期限、还款付息方式、担保保证等重要内容均以乙方与
                    <em><?php echo judge_empty($data['agreement'][21], $data['b_real_name_nric']); ?></em>
                    签订的《借款合同》、《担保合同》等协议约定为准，乙方同意配合签署并依约履行，对此无任何异议。
                </dd>
            </dl>
            <dl class="trust_content">
                <dt>三、双方权利与义务</dt>
                <dd>1、甲方作为上述债权的实际出资人，享有实际的债权人权益并有权获得相应的利息收益，甲方有权自行处分该笔债权或其派生权益（如转让、质押等）；乙方对甲方实际的债权人权益不享有任何收益权或处分权。</dd>
                <dd>2、作为上述借款的名义债权人，乙方承诺受到本协议内容的限制；乙方承诺该笔借款所产生的全部收益和未来借款本金的归还，均通过第三方支付直接由借款人账户划转至甲方账户。</dd>
                <dd>3、乙方确认其在本协议中对
                    <em><?php echo judge_empty($data['agreement'][22], $data['b_real_name_nric']); ?></em>
                    的债权并非乙方个人权益。乙方确认在获得甲方书面授权或许可前，不得将其上述债权及其派生的权益以其他任何方式处分，也不得实施任何损害或可能损害甲方利益的其他行为。
                </dd>
                <dd>4、乙方确认其已知悉甲方与
                    <em><?php echo judge_empty($data['agreement'][23]); ?></em>
                    已签订《债权转让协议》，即甲方有权在借款期限届满日前任何时候或在借款人未按时支付本息时将该笔借款转让给
                    <em><?php echo judge_empty($data['agreement'][24]); ?></em>
                    ，乙方对此无条件配合，债权转让的款项也通过第三方支付机构直接由受让人划转至甲方账户。
                </dd>
                <dd>5、甲方作为对
                    <em><?php echo judge_empty($data['agreement'][25], $data['b_real_name_nric']); ?></em>
                    债权的实际拥有人，有权对乙方不适当的受托行为进行监督和纠正。借款逾期情形下，乙方应无条件按照甲方的要求以自己的名义通过法律途径向借款人追还借款，以最大限度保障甲方的合法权益。
                </dd>
                <dd>6、作为受托人，乙方不得利用名义债权人身份为自己或其利害关系人牟取任何利益，否则应相应地将该利益转交给甲方或向甲方作等值赔偿。若乙方前述行为损害公司利益的，应另行赔偿公司的损失。</dd>
                <dd>7、乙方作为受托人及名义债权人，不收取甲方任何委托费用。</dd>
            </dl>
            <dl class="trust_content">
                <dt>四、保密条款</dt>
                <dd>
                    协议双方对本协议履行过程中所接触或获知的对方的任何商业信息均有保密义务，除非有明显的证据证明该信息属于公知信息或者事先得到对方的书面授权。该保密义务在本协议终止后仍然继续有效。同时，乙方不得泄露其因名义股东身份获知的有关公司的非公开信息。
                </dd>
            </dl>
            <dl class="trust_content">
                <dt>五、其他事项</dt>
                <dd>
                    1、因本协议所引起的任何纠纷及争议，双方先行协商解决，协商未果后双方一致同意，不论争议金额大小和争议性质，均提交重庆仲裁委员会适用重庆仲裁委员会现行仲裁规则进行仲裁。仲裁裁决为终局裁决，对双方均具有法律约束力。
                </dd>
                <dd>2、双方确认并同意，网加服务平台的服务规则（如注册与服务协议、收费规则等）作为本协议附件，与本协议具有同等法律效力。</dd>
                <dd> 您于
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('Y', $data['confirm_time'] - 24 * 60 * 60);
                        else: echo judge_empty($data['agreement'][26]); endif; ?></em>
                    年
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('m', $data['confirm_time'] - 24 * 60 * 60);
                        else: echo judge_empty($data['agreement'][27]); endif; ?></em>
                    月
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('d', $data['confirm_time'] - 24 * 60 * 60);
                        else: echo judge_empty($data['agreement'][28]); endif; ?></em>
                    日在“网加”平台上确认向项目借款人
                    <em><?php echo judge_empty($data['agreement'][30], $data['b_real_name_nric']); ?></em>
                    出借
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][31], $data['invest_amount']); ?></em>
                    元，现该项目已达到平台规定融资的最低额度，您委托
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][32], $data['b_real_name']); ?></em>
                    出借
                    <em class="btn_a"><?php echo judge_empty($data['agreement'][33], $data['invest_amount']); ?></em>
                    元已支付至借款人
                    <em><?php echo judge_empty($data['agreement'][34], $data['b_real_name_nric']); ?></em>
                    指定的账户，年化利率是
                    <em class="btn_b"><?php echo judge_empty($data['agreement'][39], $data['rate']); ?></em>
                    %，《借款合同》、《抵押合同》、《委托借款协议》等相关协议均已生效并发生法律效力，您的出借款从
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('Y', $data['confirm_time']);
                        else: echo judge_empty($data['agreement'][35]); endif; ?></em>
                    年
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('m', $data['confirm_time']);
                        else: echo judge_empty($data['agreement'][36]); endif; ?></em>
                    月
                    <em class="btn_b"><?php if (!empty($data['confirm_time'])): echo date('d', $data['confirm_time']);
                        else: echo judge_empty($data['agreement'][37]); endif; ?></em>
                    日开始计算利息。
                </dd>
            </dl>
            <dl class="trust_content">
                <dd>
                    上述协议及其修改或补充均采用通过“网加”网站以点击操作形成电子文本形式制成，可以有一份或者多份并且每一份具有同等法律效力，并永久保存在网加为此设立的专用服务器上备查和保管。各方均认可该形式的协议效力。
                </dd>
            </dl>
        </div>
    </div>
</div>
</body>