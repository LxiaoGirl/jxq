<!DOCTYPE html>
<html>
<head lang="en">
    <title>借款申请</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <div class="mt10">
        <div role="form">
            <!--我要借贷1-->
            <div class="jiedai1" id="jiedai1">
                <div class="row  tonglan_input  form-group-lg">
                    <p class="ml10">
                        借款性质：
                    </p>
                    <input type="hidden" name="type" id="JS_co_input" class="" value="1">
                    <ul class="clearfix row choose_one" id="JS_chooseone">
                        <li class="col-xs-5 current"><a href="javascript:void(0)" val="1">个人借款</a></li>
                        <li class="col-xs-5"><a href="javascript:void(0)" val="2">公司借款</a></li>
                    </ul>
                    <p class="ml10">
                        所需资金(万元)：
                    </p>

                    <div class="bg_white">
                        <input name="amount" placeholder="请输入您的所需资金(万元)" type="number"
                               class="input-group-lg form-control">
                    </div>
                    <p class="ml10 mt10">
                        洽谈时间：
                    </p>

                    <div class="bg_white">
                        <input id="appDate" name="dateline" placeholder="洽谈时间,如2015-7-7" type="text"
                               class="input-group-lg form-control" readonly="">
                    </div>
                </div>

                <div class="container">
                    <div class="row mt20 mb20">
                        <button type="button" id="next" class="btn btn-lg btn-danger btn-block">下一步</button>
                    </div>
                </div>
            </div>
            <!--我要借贷1 end-->
            <!-- 我要借贷2 -->
            <div class="jiedai2" id="jiedai2" style="display:none;">
                <div class="row  tonglan_input  form-group-lg">
                    <p class="ml10">
                        所在地：
                    </p>

                    <div class="bg_white">
                        <input id="city" readonly placeholder="请输入您所在地" type="text" class="input-group-lg form-control">
                        <input name="province" id="province" type="hidden" value="">
                        <input name="city" id="city1" type="hidden" value="">
                        <input name="district" id="district" type="hidden" value="">
                    </div>
                    <p class="ml10 mt10">
                        真实姓名：
                    </p>

                    <div class="bg_white">
                        <input name="user_name" value="<?php echo profile('real_name'); ?>" placeholder="请输入您的真实姓名"
                               type="text" class="input-group-lg form-control" readonly>
                    </div>

                    <p class="ml10 mt10">
                        手机号码：
                    </p>

                    <div class="bg_white">
                        <input name="mobile" value="<?php echo profile('mobile'); ?>" placeholder="请输入您的手机号码"
                               type="number" class="input-group-lg form-control" readonly>
                    </div>

                    <p class="ml10 mt10">
                        短信验证码：
                    </p>

                    <div class="bg_white">
                        <input name="authcode" placeholder="请输入短信验证码" type="number" class="input-group-lg form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6  mt20">
                        <button class="btn btn-success btn-block send-authcode" send-type="sms">发送短信</button>
                    </div>

                    <div class="col-xs-6  mt20">
                        <button class="btn    btn-info btn-block send-authcode" send-type="voice">收听语音验证码</button>
                    </div>
                </div>

                <div class="container">
                    <div class="row mt20 mb20">
                        <button id="submit" class="btn btn-lg btn-danger btn-block ajax-submit-button">确认提交</button>
                    </div>
                </div>
            </div>
            <!-- 我要借贷2 end -->
        </div>
    </div>
</div>
<!--效果html开始-->
<div id="datePlugin">
    <div id="dateshadow" style="display: none;"></div>
    <div id="datePage" class="page" style="display: none;">
        <section>
            <div id="datetitle"><h1>选择地址</h1></div>
            <div id="datemark"><a id="markyear"></a><a id="markmonth"></a><a id="markday"></a></div>
            <div id="timemark"><a id="markhour"></a><a id="markminut"></a><a id="marksecond"></a></div>
            <div id="datescroll">
                <div id="yearwrapper" style="overflow: hidden;">
                    <ul style="-webkit-transition-property: -webkit-transform; transition-property: -webkit-transform; -webkit-transition-duration: 0ms; transition-duration: 0ms; -webkit-transform-origin: 0px 0px 0px; -webkit-transform: translate3d(0px, 0px, 0px);">
                        <li name="123">&nbsp;</li>
                        <li name="123">重庆</li>
                        <li name="123">重庆</li>
                        <li name="123">重庆</li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
                <div id="monthwrapper" style="overflow: hidden;">
                    <ul style="-webkit-transition-property: -webkit-transform; transition-property: -webkit-transform; -webkit-transition-duration: 0ms; transition-duration: 0ms; -webkit-transform-origin: 0px 0px 0px; -webkit-transform: translate3d(0px, 0px, 0px);">
                        <li>&nbsp;</li>
                        <li name="123">重庆</li>
                        <li name="123">重庆</li>
                        <li name="123"> 重庆</li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
                <div id="daywrapper" style="overflow: hidden;">
                    <ul style="-webkit-transition-property: -webkit-transform; transition-property: -webkit-transform; -webkit-transition-duration: 0ms; transition-duration: 0ms; -webkit-transform-origin: 0px 0px 0px; -webkit-transform: translate3d(0px, 0px, 0px);">
                        <li>&nbsp;</li>
                        <li>重庆</li>
                        <li>重庆</li>
                        <li>重庆</li>
                        <li>&nbsp;</li>
                    </ul>
                </div>
            </div>
            <div id="datescroll_datetime">
                <div id="Hourwrapper">
                    <ul></ul>
                </div>
                <div id="Minutewrapper">
                    <ul></ul>
                </div>
                <div id="Secondwrapper">
                    <ul></ul>
                </div>
            </div>
        </section>
        <footer id="dateFooter">
            <div id="setcancle">
                <ul>
                    <li id="dateconfirm">确定</li>
                    <li id="datecancle">取消</li>
                </ul>
            </div>
        </footer>
    </div>
</div>
<!--结束-->
<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<!--日历-->
<script src="/assets/js/app/date/mobiscroll_002.js"></script>
<script src="/assets/js/app/date/mobiscroll.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/js/app/date/mobiscroll_002.css">
<link rel="stylesheet" type="text/css" href="/assets/js/app/date/mobiscroll.css">
<link rel="stylesheet" type="text/css" href="/assets/js/app/date/mobiscroll_003.css">
<script src="/assets/js/app/city.js"></script>

<script src="/assets/js/app/addr/addr.js"></script>
<script src="/assets/js/app/addr/isscroll.js"></script>
<script type="text/javascript">
    //地址插件的初始值
    var cityone = "<li name='123'>&nbsp;</li><?php if($province):foreach($province as $v):echo "<li name='".$v['region_id']."'>".$v['region_name']."</li>";endforeach;endif; ?><li>&nbsp;</li>";
    var citytwo = "<li>&nbsp;</li><li>&nbsp;</li>"
    var citythree = "<li>&nbsp;</li><li>&nbsp;</li>"

    var amount = 0, dateline = ''; //数量 和 时间
    $(function () {
        $('#city').addr();
        var currYear = (new Date()).getFullYear();
        var opt = {};
        opt.date = {preset: 'date'};
        opt.datetime = {preset: 'datetime'};
        opt.time = {preset: 'time'};
        opt.default = {
            theme: 'android-ics light', //皮肤样式
            display: 'modal', //显示方式
            mode: 'scroller', //日期选择模式
            dateFormat: 'yyyy-mm-dd',
            lang: 'zh',
            showNow: true,
            nowText: "今天",
            startYear: currYear - 10, //开始年份
            endYear: currYear + 10 //结束年份
        };

        $("#appDate").mobiscroll($.extend(opt['date'], opt['default']));
        //发送验证码处理
        $('input[name="mobile"]').on('blur', function () {
            $('.send-authcode').unbind('tap');
            //发送验证码处理
            send_authcode($('input[name="mobile"]').val(), 'apply');
        });

        //下一步 和提交 处理
        $("#next").on('tap', function () {
            amount = $(':input[name="amount"]').val();
            dateline = $(':input[name="dateline"]').val();

            if (!amount || amount <= 0) {
                my_alert('请输入所需资金！');
                return false;
            }
            if (!dateline) {
                my_alert('请选择洽谈时间');
                return false;
            }
            $("#jiedai1").hide();
            $("#jiedai2").show();
        });
        $('#submit').on('tap', function () {
            check_to_login();
            var phone = /^1[3456789](\d){9}$/;
            var authcode = /[0-9]{6}/;//6位至20位数字、字母和特殊字符组成
            if (!$(':input[name="province"]').val()) {
                my_alert('请选择省份！');
                return false;
            }
            if (!$(':input[name="city"]').val()) {
                my_alert('请选择城市！');
                return false;
            }
            if (!$(':input[name="district"]').val()) {
                my_alert('请选择地区！');
                return false;
            }
            if (!$(':input[name="user_name"]').val()) {
                my_alert('请输入姓名！');
                return false;
            }
            if (!phone.test($(':input[name="mobile"]').val())) {
                my_alert('请输入正确格式手机号码！');
                return false;
            }
            if (!authcode.test($(':input[name="authcode"]').val())) {
                my_alert('请输入6位数字验证码！');
                return false;
            }
            $.ajax({
                url: '/index.php/apps/home/borrow',
                dataType: 'json',
                type: 'post',
                data: {
                    'amount': amount,
                    'dateline': dateline,
                    'type': $(":input[name='type']").val(),
                    'p_type':<?php echo $p_type; ?>,
                    'province': $(":input[name='province']").val(),
                    'city': $(":input[name='city']").val(),
                    'district': $(":input[name='district']").val(),
                    'user_name': $(":input[name='user_name']").val(),
                    'mobile': $(":input[name='mobile']").val(),
                    'from': '---',
                    'authcode': $(":input[name='authcode']").val()
                },
                success: function (resut) {
                    if (resut.code == 0) {
                        window.location.replace('<?php echo site_url('apps/home/borrow_success'); ?>');
                    } else {
                        my_alert(resut.msg);
                    }
                }
            });
            return false;
        });
    });
</script>
<!--日历 end-->
</html>