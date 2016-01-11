<!DOCTYPE html>
<html>
<head lang="en">
    <title><?php echo $title; ?></title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 选项卡标题 -->
    <div class="row">
        <ul class="index_tab_title clearfix" id="months">
            <li class="current" data-month=""><a href="javascript:void (0);">全部</a></li>
            <li data-month="0-0.9"><a href="javascript:void (0);">0.9个月</a></li>
            <li data-month="3-3"><a href="javascript:void (0);">3个月</a></li>
            <li data-month="3-12"><a href="javascript:void (0);">3-12个月</a></li>
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

<?php $this->load->view('common/mobiles/app_alert') ?>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    category = '<?php echo $category; ?>';
    $(function () {
        $("#list").list_data({
            data : '/index.php/mobiles/home/get_project_list?category='+category,
            page_size : 10,
            is_scroll : true,
            scroll_offset_height:$('.header').height()+$('#months').height(),
            show_loading:true,
            up_load : true,
            value_func : {
                'amount': function (price) {return rate_format(price_format(price, 3, false)); },
                'mode': function (mode) {return '<i class="dots_green"></i>' + mode;}
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
                obj.find('.tap-span').attr('onclick', 'window.location.href="/index.php/mobiles/home/project_detail?borrow_no='+v.borrow_no+'"')
            },
            callback : function(){$("canvas").remove();bfbFun();}
        },function(ls_fun){
            $("#months li").on('click', function () {
                $("#months li").removeClass('current');
                var month = $(this).data('month');
                $(this).addClass('current');
                ls_fun('/index.php/mobiles/home/get_project_list?category='+category+'&m='+month);
            });
        });
    });
</script>
</html>