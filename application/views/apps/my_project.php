<!DOCTYPE html>
<html>
<head lang="en">
    <title>已投项目</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="pd10 bg_red row navbar-fixed-top">
        <div style="padding-left:10px; padding-right:10px;">
            <div id="title" class="btn-group btn-group-justified">
                <span class="tab_ts btn btn-default current" status="2-3">投标中</span>
                <span class="tab_ts btn btn-default" status="4">还款中</span>
                <span class="tab_ts btn btn-default" status="7">已结束</span>
            </div>
        </div>
    </div>
    <!-- 顶部导航  end-->

    <div class="mb20" style="padding-top:70px; ">
        <!-- 投标中  -->
        <div class="tab_block" id="list" style="visibility: hidden">
            <div class="index_info">
                <a class="project-info-a" href="#">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td valign="middle" height="60"
                                style="padding-left: 0;padding-right: 0;line-height: 1.4em;">
                                <span style="width: 30%;display:block;overflow: hidden;float:left;"><strong
                                        class="c_red f18 vm rate"></strong><i>%</i></span>
                                <span style="width: 33%;display:block;overflow: hidden;float:left;"><em
                                        class="f18 vm months"></em><i>个月</i></span>
                                <span style="width: 37%;display:block;overflow: hidden;float:left;"><em
                                        class="f18 vm amounts"></em><i>万元</i></span>
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
                              style="display:block;width:32%;float:left;overflow: hidden;text-align: right;">
                              <i class="dots_green"></i></span>
                    </div>

                    <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">投资时间:</span><span style="display: block;float: left;" class="dateline"></span>
                        </span>
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                              <span style="display: block;float: left;width: 65px;">投资金额:</span><span style="display: block;float: left;" class="amount"></span>
                          </span>
                    </div>
                    <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">计息日:</span><span style="display: block;float: left;" class="start_time"></span>
                        </span>
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">还款日:</span><span style="display: block;float: left;" class="last_time"></span>
                        </span>
                    </div>
                    <div class="clears line"></div>
                </a>
                <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                    <span class="vm sy-span" style="display:block;width:50%;float:left;overflow: hidden;text-align:center;">
                        <span class="sy-title">收益:</span><span class="project_interest"></span><span class="sy-dw">元</span>
                    </span>
                    <span class="vm agree-span" style="display:block;width:50%;float:left;overflow: hidden;text-align:center;">
                        <a class="agree-a" href="#" style="text-decoration:underline;">借款协议</a>
                    </span>
                </div>
            </div>
        </div>
        <!-- 投标中  end-->
    </div>
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var g_m = '2-3';
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'pageSize': 10,
            'funcDeal': {
                'rate': function (rate) { return rate_format(rate); },
                'amounts': function (price) {  return rate_format(price_format(price, 3, false));},
                'mode': function (mode) { return '<i class="dots_green"></i>' + borrow_mode(mode);}
            }
        });
        var get_data = function () {
            var condition = '';
            if (g_m != '')condition += '?status=' + g_m;
            list_view.init('/index.php/apps/home/my_project' + condition, function (obj, v) {
                var now = Date.parse(new Date()) / 1000;
                var receive_rate = v.receive?Math.round(v.receive/v.amounts*100):0;
                if (v.status == 2) {
                    if (v.buy_time > now) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>未开始</span>');
                    } else if (receive_rate == 100) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>融资完成</span>');
                    } else if (v.due_date < now) {
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>投标结束</span>');
                    } else {
                        obj.find('.r_rate').attr('data-bfb', receive_rate);
                    }
                } else if (v.status == 4) {
                    obj.find('.r_rate').removeClass('bfb').addClass('index_huankuan').html('<span>还款中</span>');
                } else if (v.status == 7) {
                    obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>交易结束</span>');
                } else {
                    obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>' + borrow_status(v.status) + '</span>');
                }

                if(v.status == 2  || v.status == 3){
                    obj.find('.start_time').html('未开始');
                    obj.find('.project_interest').html('未开始');
                    obj.find('.sy-dw').remove();
                    obj.find('.agree-span').remove();
                    obj.find('.sy-span').css('width','100%');
                }else{
                    // obj.find('.agree-a').attr('href', '<?php echo site_url("apps/home/terms?borrow_no=") ?>' + v.borrow_no);
                    obj.find('.agree-a').attr('href', '/index.php/terms?borrow_no=' + v.borrow_no+'&money='+v.amounts);
                }
                obj.find('project-info-a').attr('href', '<?php echo site_url("apps/home/project_detail?borrow_no=") ?>' + v.borrow_no);
            }, function () { $("canvas").remove();bfbFun();});
        };
        $("#title span").on('tap', function () {
            $("#title span").removeClass('current');
            $(this).addClass('current');
            if (g_m != $(this).attr('status')) {
                g_m = $(this).attr('status');
                get_data();
            }
        });
        get_data();
    });
</script>
</html>