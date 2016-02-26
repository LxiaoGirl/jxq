<!DOCTYPE html>
<html>
<head lang="en">
    <title><?php echo $title; ?></title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
<div class="container-fluid">
    <!-- 选项卡标题 -->
    <div class="row">
        <ul class="index_tab_title clearfix" id="months">
            <li class="current" m=""><a href="#">全部</a></li>
            <li m="0-0.9"><a href="#">0.9个月</a></li>
            <li m="3-3"><a href="#">3个月</a></li>
            <li m="3-12"><a href="#">3-12个月</a></li>
        </ul>
    </div>
    <!-- 选项卡标题  end-->
    <!-- 选项内容 -->
    <div class="index_tab_nr" id="list" style="visibility: hidden">
        <div class="index_info">
            <span class="tap-span" onclick="">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td valign="middle" height="60" style="padding-left: 0;padding-right: 0;line-height: 1.4em;">
                            <span style="width: 30%;display:block;overflow: hidden;float:left;"><strong
                                    class="c_red f18 vm rate"></strong><i>%</i></span>
                            <span style="width: 33%;display:block;overflow: hidden;float:left;"><em
                                    class="f18 vm months"></em><i>个月</i></span>
                            <span style="width: 37%;display:block;overflow: hidden;float:left;"><em
                                    class="f18 vm amount"></em><i>万元</i></span>
                        </td>
                        <td width="60" style="padding:0;padding-top: 10px;padding-bottom: 10px">
                            <div class="bfb   bfb80 r_rate" style="float:right" data-bfb="0"></div>
                            <!-- data-bfb 为百分比数字 -->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="clears line"></div>
                <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                    <span class="vm category" style="display:block;width:20%;float:left;overflow: hidden;"></span>
                    <span class="vm subject"
                          style="display:block;width:45%;float:left;overflow: hidden;margin-left: 3%"></span>
                    <span class="vm mode"
                          style="display:block;width:32%;float:left;overflow: hidden;text-align: right;"><i
                            class="dots_green"></i></span>
                </div>
            </span>
        </div>
    </div>
    <!-- 选项内容 end-->
</div>

<?php $this->load->view('common/apps/app_alert') ?>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var g_m = '', category = '<?php echo $category; ?>';
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'pageSize': 10,
            'iscroll': true,
            'offsetHeight':function(){ return $("#months").height();},
            'funcDeal': {
                'rate': function (rate) { return rate_format(rate);},
                'amount': function (price) { return rate_format(price_format(price, 3, false));},
                'mode': function (mode) {return '<i class="dots_green"></i>' + borrow_mode(mode);}
            }
        });
        var get_data = function () {
            var condition = '?category=' + category;
            if (g_m != '') condition += '&m=' + g_m;
                list_view.init('/index.php/apps/home/get_project_list' + condition, function (obj, v) {
                    var now = Date.parse(new Date()) / 1000;
                    if (v.status == 2||v.status == 3) {
                        if (v.buy_time > now) {
                            obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>未开始</span>');
                        } else if (v.receive_rate == 100) {
                            obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>复审中</span>');
                        } else if (v.due_date < now) {
                            obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>投标结束</span>');
                        } else {
                            obj.find('.r_rate').attr('data-bfb', v.receive_rate);
                        }
                    } else if (v.status == 4) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_huankuan').html('<span>还款中</span>');
                    } else if (v.status == 7) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>交易结束</span>');
                    } else {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>' + borrow_status(v.status) + '</span>');
                    }
                    obj.find('.tap-span').attr('onclick', 'window.location.href="<?php echo site_url("apps/home/project_detail");?>?borrow_no=' + v.borrow_no + '"')
                }, function () {$("canvas").remove();bfbFun();});
        };

        $("#months li").on('tap', function () {
            $("#months li").removeClass('current');
            $(this).addClass('current');
            if (g_m != $(this).attr('m')) {
                g_m = $(this).attr('m');
                get_data();
            }
        });
        get_data();
    });
</script>
</html>