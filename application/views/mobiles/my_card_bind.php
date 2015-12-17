<!DOCTYPE html>
<html>
<head lang="en">
    <title>绑定银行卡</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="mt10">
        <div class="borderb row infoheader mb10">
            <p class="mb10 c_888">真实姓名：</p>

            <p class="c_333 f18 mr10 mr10"><?php echo profile('real_name'); ?></p>
        </div>

        <div role="form">
            <div class="row  tonglan_input  form-group-lg">
                <p class="ml10">
                    开户银行：
                </p>

                <div class="bg_white mb10">
                    <select id="bank_id" class="input-group-lg form-control">
                        <?php if ($bank): ?>
                            <?php foreach ($bank as $v): ?>
                                <option value="<?php echo $v['bank_id'] ?>"><?php echo $v['bank_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <p class="ml10">
                    银行卡号：
                </p>

                <div class="bg_white mb10">
                    <input name="account" placeholder="请输入银行卡号" type="number" class="input-group-lg form-control">
                </div>
                <!--                <p class="ml10">-->
                <!--                    所在地：-->
                <!--                </p>-->
                <!--                <div class="bg_white mb10">-->
                <input name="bankaddr" readonly="" placeholder="请输入所在地" type="hidden"
                       class="input-group-lg form-control" value="默认地址">
                <!--                </div>-->
                <p class="ml10">
                    短信验证码：
                </p>

                <div class="bg_white mb10">
                    <table class="table_nopd" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td>
                                <input name="authcode" placeholder="请输入短信验证码" type="text"
                                       class="input-group-lg form-control">
                            </td>
                            <td class="text-right">
                                <button class="btn no-radius btn-lg btn-info send-authcode" send-type="sms">获取验证码
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-center mt20">
                    一直收不到短信？<a href="#" class="c_blue send-authcode" send-type="voice">点此语音获取验证码</a>
                </p>

            </div>

            <div class="container">
                <div class="row mt10 mb20">
                    <button id="submit" class="btn btn-lg btn-danger btn-block">确认提交</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 弹出消息层-->
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <div class="f14 text-center mb10">请选择地址</div>
        <div class="text-center">
            <form action="#">
                <div class="bg_white">
                    <select style="width:30%" class="select" name="province" id="s1" onchange="setup();promptinfo();">
                        <option></option>
                    </select>
                    <select style="width:30%" class="select" name="city" id="s2" onchange="setup();promptinfo();">
                        <option></option>
                    </select>
                    <select style="width:30%" class="select" name="town" id="s3" onchange="promptinfo();">
                        <option></option>
                    </select>
                    <input id="address" name="address" type="hidden" value="">

                </div>

                <div class="row mb10">
                    <div class="col-xs-6  mt10">
                        <button type="button" class="btn JS_quit btn-link btn-block">取消</button>
                    </div>

                    <div class="col-xs-6  mt10">
                        <button id="city_ok" type="button" class="btn btn-link btn-block ajax-submit-button"><span
                                class="c_red">确认</span></button>
                    </div>
                </div>

        </div>
        </form>
    </div>
    <!-- cd-popup-container -->
</div>
<!-- cd-popup -->
<!-- 弹出消息层 end-->
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
<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script src="/assets/js/app/addr/addr.js"></script>
<script src="/assets/js/app/addr/isscroll.js"></script>
<script type="text/javascript">
    var cityone = "<li>&nbsp;</li><?php if($province):foreach($province as $v):echo "<li name='".$v['region_id']."'>".$v['region_name']."</li>";endforeach;endif; ?><li>&nbsp;</li>";
    var citytwo = "<li>&nbsp;</li><?php if($city):foreach($city as $v):echo "<li name='".$v['region_id']."'>".$v['region_name']."</li>";endforeach;endif; ?><li>&nbsp;</li>"
    var citythree = "<li>&nbsp;</li><?php if($distic):foreach($distic as $v):echo "<li name='".$v['region_id']."'>".$v['region_name']."</li>";endforeach;endif; ?><li>&nbsp;</li>"

    var phone = '<?php echo profile('mobile') ?>';
    $(function () {
        $('#city').addr();
        //发送验证码处理
        send_authcode(phone, 'bindcard');

        $("#submit").on('tap', function () {
            if ($("#bank_id").val() == '') {
                my_alert('请选择银行！');
            } else if ($("input[name='account']").val() == '' || $("input[name='account']").val().length < 6 || isNaN($("input[name='account']").val())) {
                my_alert('请输入正确格式银行帐号！');
            } else if ($("input[name='bankaddr']").val() == '') {
                my_alert('请选择银行地址！');
            } else {
                var auth = /^[0-9]{6}$/;
                if (!auth.test($("input[name='authcode']").val())) {
                    my_alert('请输入正确格式的验证码！');
                    return false;
                }
                $.post('/index.php/mobiles/home/my_card_bind', {
                    'account': $("input[name='account']").val(),
                    'bank_id': $("#bank_id").val(),
                    'authcode': $("input[name='authcode']").val(),
                    'bankaddr': $("input[name='bankaddr']").val()
                }, function (rs) {
                    if (rs.code == 0) {
                        window.location.href = '/index.php/mobiles/home/my_card';
                    } else {
                        my_alert(rs.msg);
                    }
                }, 'json');
            }
            return false;
        })
    });
</script>
</html>