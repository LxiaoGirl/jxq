<!DOCTYPE html>
<html>
<head lang="en">
    <title>已投项目</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="pd10 bg_red row navbar-fixed-top" id="nav-div">
        <div style="padding-left:10px; padding-right:10px;">
            <div id="title" class="btn-group btn-group-justified">
                <span class="tab_ts btn btn-default current" data-status="2-3">投标中</span>
                <span class="tab_ts btn btn-default" data-status="4">还款中</span>
                <span class="tab_ts btn btn-default" data-status="7">已结束</span>
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
                        <span class="vm subject"style="display:block;width:45%;float:left;overflow: hidden;margin-left: 3%"></span>
                        <span class="vm "style="display:block;width:32%;float:left;overflow: hidden;text-align: right;"><i class="dots_green"></i><i class="mode"></i></span>
                    </div>

                    <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">投资时间:</span><span style="display: block;float: left;" class="invest_time"></span>
                        </span>
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                              <span style="display: block;float: left;width: 65px;">投资金额:</span><span style="display: block;float: left;" class="amount"></span>
                          </span>
                    </div>
                    <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">计息日:</span><span style="display: block;float: left;" class="interest_start_time"></span>
                        </span>
                        <span class="vm " style="display:block;width:50%;float:left;overflow: hidden;">
                            <span style="display: block;float: left;width: 65px;">还款日:</span><span style="display: block;float: left;" class="interest_lately_time"></span>
                        </span>
                    </div>
                    <div class="clears line"></div>
                </a>
                <div class="pd10" style="overflow:hidden;padding-right: 0; padding-left: 0;">
                    <span class="vm sy-span" style="display:block;width:50%;float:left;overflow: hidden;text-align:center;">
                        <span class="sy-title">收益:</span><span class="interest"></span><span class="sy-dw">元</span>
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
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        $("#nav-div").css({
            'top': $('.header').height()
        });
        $('#list').list_data({
            data : '/index.php/mobiles/home/my_project?status=2-3',
            page_size : 10,
            show_loading:true,
            event_type : 'scroll',
            value_func : {
                'rate': function (rate) {
                    return rate_format(rate);
                },
                'amounts': function (price) {
                    return rate_format(price_format(price, 3, false));
                },
                'invest_time': function (time) {
                    return unixtime_style(time,'Y-m-d');
                },
                'interest_start_time': function (time) {
                    return unixtime_style(time,'Y-m-d');
                },
                'interest_lately_time': function (time) {
                    return unixtime_style(time,'Y-m-d');
                }
            },
            list_func : function(obj,v){
                switch (parseInt(v.new_status)){
                    case 2:
                        obj.find('.r_rate').attr('data-bfb', v.receive_rate);
                        break;
                    case 4:
                        obj.find('.r_rate').removeClass('bfb').addClass('index_huankuan').html('<span>'+ v.status_name+'</span>');
                        break;
                    default:
                        obj.find('.r_rate').removeClass('bfb').addClass('index_wancheng').html('<span>'+ v.status_name+'</span>');
                }
                if(v.status == 2  || v.status == 3){
                    obj.find('.interest_start_time').html('未开始');
                    obj.find('.interest').html('未开始');
                    obj.find('.sy-dw').remove();
                    obj.find('.agree-span').remove();
                    obj.find('.sy-span').css('width','100%');
                }else{
                    obj.find('.agree-a').attr('href', '/index.php/terms?borrow_no=' + v.borrow_no+'&money='+v.amounts);
                }
                obj.find('.project-info-a').attr('href', '/index.php/mobiles/home/project_detail?borrow_no=' + v.borrow_no);
            },
            callback : function(){$("canvas").remove(); bfbFun();}
        },function(ls_func){
            $("#title span").on('tap', function () {
                $("#title span").removeClass('current');
                $(this).addClass('current');
                ls_func('/index.php/mobiles/home/my_project?status='+$(this).data('status'));
            });
        });
    });
</script>
</html>