<!DOCTYPE html>
<html style="background:#fff;">
<head>
    <title>充值须知</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
    <?php echo load_file('app/m-common.css,app/m-ptjs.css'); ?>
 
</head>
<body style="background:#fff;">
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>
<div class="con_wap">
    <div class="stree">
        <p class="re_nt base">我可以使用哪些银行卡？<img class="bla fr"
                                              src="<?php echo assets('images/app/ptjs/re_nt_bla.png'); ?>"></p>

        <p class="re_nt active">我可以使用哪些银行卡？<img class="red fr"
                                                src="<?php echo assets('images/app/ptjs/re_nt_red.png'); ?>"></p>

        <div class="hid_hy">
		    <?php if (isset($bank)): ?>
                <?php foreach ($bank as $v): ?>
				<div class="comner">
                <div class="yh" style="background: url(<?php echo assets('images/app/yingh/'.$v['code'].'.png'); ?>) left center no-repeat;
    background-size: 3rem 3rem;"><p class="top"><?php echo $v['bank_name'] ?></p>

                    <p class="bot"><?php echo $v['content'] ?></p></div>
				</div>
				
				
                   
                <?php endforeach;endif; ?>
		
		
           
        </div>
    </div>
    <div class="stree">
        <p class="re_nt base">充值收取手续费吗？<img class="bla fr" src="<?php echo assets('images/app/ptjs/re_nt_bla.png'); ?>">
        </p>

        <p class="re_nt active">充值收取手续费吗？<img class="red fr"
                                              src="<?php echo assets('images/app/ptjs/re_nt_red.png'); ?>"></p>

        <div class="hid_hy">
            <div class="comner"><p class="answ">用户充值时，第三方托管公司将对充值金额征收2.5‰的手续费. 暂时由平台垫付。</p></div>
        </div>
    </div>
    <div class="stree">
        <p class="re_nt base">充值成功后，为什么我的钱包里没有钱？<img class="bla fr"
                                                     src="<?php echo assets('images/app/ptjs/re_nt_bla.png'); ?>"></p>

        <p class="re_nt active">充值成功后，为什么我的钱包里没有钱？<img class="red fr"
                                                       src="<?php echo assets('images/app/ptjs/re_nt_red.png'); ?>"></p>

        <div class="hid_hy">
            <div class="comner"><p class="answ">
                    如果充值时出现网络故障或没有出现充值成功的页面，可能会出现不能及时到账的情况，第三方支付通道将在第二个工作日和银行对账，对账结束后把未到账的金额充到您理财账户或者进行退款处理按原途径返还您的银行账户。</p>
            </div>
        </div>
    </div>
    <div class="stree">
        <p class="re_nt base">提现收取手续费吗？<img class="bla fr" src="<?php echo assets('images/app/ptjs/re_nt_bla.png'); ?>">
        </p>

        <p class="re_nt active">提现收取手续费吗？<img class="red fr"
                                              src="<?php echo assets('images/app/ptjs/re_nt_red.png'); ?>"></p>

        <div class="hid_hy">
            <div class="comner"><p class="answ">提现收取最低2元/笔手续费，单笔提现最高收取100元手续费。</p></div>
        </div>
    </div>
    <div class="stree">
        <p class="re_nt re_nt4 base">我在电脑上绑定了银行卡，为什么手机上用不了？<img class="bla fr"
                                                                src="<?php echo assets('images/app/ptjs/re_nt_bla.png'); ?>">
        </p>

        <p class="re_nt re_nt4 active">我在电脑上绑定了银行卡，为什么手机上用不了？<img class="red fr"
                                                                  src="<?php echo assets('images/app/ptjs/re_nt_red.png'); ?>">
        </p>

        <div class="hid_hy">
            <div class="comner"><p class="answ">
                    网站上绑定的银行卡支付通道和手机APP的支付通道不一样，所以在网站绑定的银行卡在手机APP充值的时候可能会出现充值不成功的情况，如出现类似情况请在手机APP中重新绑定银行卡。</p></div>
        </div>
    </div>
</div>
<!-- 公共尾部-->
<?php $this->load->view('common/mobiles/app_footer') ?>
</body>
<script type="text/javascript">
    $(".re_nt").click(function () {
        // body...
        $(".active").hide();
        $(".base").show();
        $(".hid_hy").hide();
        $(this).hide();
        $(this).siblings(".active").show();
        $(this).siblings(".hid_hy").show();
    })
    $(".active").click(function () {
        // body...
        $(this).hide();
        $(this).siblings(".base").show();
        $(this).siblings(".hid_hy").hide();
    })
</script>
</html>