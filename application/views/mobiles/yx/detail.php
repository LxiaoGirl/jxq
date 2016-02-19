<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="viewport" content="initial-scale=1, maximum-scale=3, minimum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="/assets/activity_wish/active_yx/css/index.css">
    <title>阖家团圆</title>
</head>
<body>
    <!--懒加载-->
    <div class="lazy">
        <div class="ajx_nr ajx_nr_1">
            <div class="ajx_logo">
                <div class="ajx_tu">
                    <div class="ajx_quan"></div>
                    <div class="four_one"></div>
                </div>
                <div class="ajx_j">J</div>
            </div>
            <div class="ajx_wz">处理中...</div>
        </div>
        <!--有的页面要底部-->
        <div class="footer tc">
            <span class="ft_sp1 active"></span><span class="ft_sp2"></span><span class="ft_sp3"></span><span class="ft_sp4"></span>
        </div>
        <!--有的页面要底部-->
    </div>
    <!--懒加载-->

    <div class="body">
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/20.jpg" width="100%" alt="">
            <div class="pab1">
                <div class="pre">
                    <div class="kh kh1"></div>
                    <div class="kh kh2"></div>
                    <div class="kh kh3"></div>
                    <div class="kh kh4"></div>
                    <div class="kh kh5"></div>
                    <div class="kh kh6"></div>
                    <div class="kh kh7"></div>
                    <div class="kh kh8"></div>
                    <div class="njrxx">
                        <div class="imgco">
                            <img src="/assets/activity_wish/active_yx/images/28.png" width="100%" alt="">
                            <div class="posa">
                                <p><?php if($join == 'Y'): ?>你加入<?php endif; ?><?php echo $wish['weixin_name']; ?>的团圆饭</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="con con2">
            <img src="/assets/activity_wish/active_yx/images/21.jpg" width="100%" alt="">
            <div class="pab">
                <p><?php echo $wish['weixin_name']; ?>当前排名第<font><?php echo $ranking; ?></font>位<span class="but">查看排行榜</span></p>
            </div>
        </div>
        <div class="con con3">
            <img src="/assets/activity_wish/active_yx/images/22.jpg" width="100%" alt="">
            <div class="pab">
                <select name="zhuo1">
                    <?php if($desk_count > 1): ?>
                        <?php for($i=1;$i<=$desk_count;$i++): ?>
                            <option value="<?php echo $i; ?>" <?php if($i == $desk_id): echo 'selected';endif; ?>>第<?php echo $i; ?>桌</option>
                        <?php endfor; ?>
                    <?php else: ?>
                        <option value="1">第1桌</option>
                    <?php endif; ?>
                </select>
                <input type="text" name="zhuo" value="第<?php echo $desk_id; ?>桌">
            </div>
        </div>
        <div class="con conwz cus-name" style="width: 100%;"></div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/23.jpg" width="100%" alt="">
            <div class="fx"></div>
            <div class="download" style="width: 90%;height: 0.47rem; position: absolute;margin-left: 5%;margin-top: 1.5rem;"></div>
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/24.jpg" width="100%" alt="">
        </div>
        <div class="con conbj conbj2">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/25.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/26.jpg" width="100%" alt="">
        </div>
    </div>
    <div class="popo">
        <img src="/assets/activity_wish/active_yx/images/30.png" width="100%" alt="">
    </div>
    <div class="pop pop1">
        <div class="popnr">
            <img src="/assets/activity_wish/active_yx/images/11.png" alt="" width="100%">
            <div class="pab"></div>
            <div class="popnr_nr">
                <p class="tit"><span>排行榜</span></p>
            </div>
            <img src="/assets/activity_wish/active_yx/images/13.png" style="margin-top:-1px;" width="100%" alt="">
        </div>
    </div>
    <div class="xf"><a href="/index.php/mobiles/yx/index"><img src="/assets/activity_wish/active_yx/images/29.png" width="100%" alt=""></a></div>
</body>
<script src="/assets/activity_wish/active_yx/js/jquery-2.1.1.min.js"></script>
<script src="/assets/activity_wish/active_yx/js/sys.js"></script>
<script src="/assets/js/app/wx-1.js"></script>
<script>
jQuery(function($) {
    window.onload = function(){
        setTimeout(function(){
            $('.ajx_nr_1').hide(1);
            $('.lazy').hide(2);
            $('.njrxx').css("top","85%");
        },500);
    };
    $('.but').click(function(e){
        $('.pop').fadeIn()
    });
    $('.pop').click(function(){
            $(".pop").fadeOut();
    });
    $('.fx').click(function(e){
        $('.popo').fadeIn()
    });
    $('.popo').click(function(){
        $(".popo").fadeOut();
    });

    var cus = [];
    var get_customer = function(id){
        now_id = id;
        $('input').val('第'+id+'桌');
        if(typeof  cus[id] != "undefined"){
            var cus_name = '',cus_max = 1;
            $(cus[id]).each(function(i,v){
                $(".kh"+(i+1)).html('<img src="'+ v.weixin_avatar+'" width="100%;" alt="'+ v.weixin_name+'" />');
                cus_name += (cus_name?'、':'')+v.weixin_name;
                cus_max = i+1;
            });
            if(cus_max < 8){
                for(var j=cus_max+1;j<=8;j++){
                    $(".kh"+j).html('');
                }
            }
            $(".cus-name").html(cus_name);

        }else{
            if(id != desk_id)_ajax_lo();
            $.ajax({
                url:'/index.php/mobiles/yx/ajax_get_help_log',
                type:'post',
                dataType:'json',
                data:{page_id:id,wish_id:'<?php echo $wish['wish_id']; ?>'},
                error:function(){
                    var tt1 = setTimeout(function(){
                        clearTimeout(tt1);
                        _ajax_cg();
                        sys_alert('通信异常,请检查网络或关闭页面稍后重试!');
                    },500);
                },
                success:function(rs){
                    //加了500毫秒延迟 保证加载动画
                    var tt1 = setTimeout(function(){
                        clearTimeout(tt1);
                        _ajax_cg();
                        var cus_name = '',cus_max = 1;
                        $(rs.data).each(function(i,v){
                            $(".kh"+(i+1)).html('<img src="'+ v.weixin_avatar+'" width="100%;" alt="'+ v.weixin_name+'" />');
                            cus_name += (cus_name?'、':'')+v.weixin_name;
                            cus_max = i+1;
                        });
                        if(cus_max < 8){
                            for(var j=cus_max+1;j<=8;j++){
                                $(".kh"+j).html('');
                            }
                        }
                        $(".cus-name").html(cus_name);
                        cus[id] = rs.data;
                    },500);
                }
            });
        }

    };
    var desk_id = parseInt('<?php echo $desk_id; ?>');
    var now_id = desk_id;
    $('select').change(function(){
        if($(this).val() != now_id){
            $('input').val('第'+$(this).val()+'桌');
            get_customer($(this).val());
        }
    });
    get_customer(desk_id);
    wx_share.conf.img = 'https://www.juxueqiu.com/assets/activity_wish/active_yx/images/yx_share.jpg';
    wx_share.conf.ticket = '/index.php/mobiles/yx/ajax_get_ticket';
    wx_share.conf.desc = '一年一次，<?php echo $wish['weixin_name']; ?>请亲朋友好友来吃团圆饭了，都来捧场，棒棒哒!';
    wx_share.share({
        title:'一年一次，<?php echo $wish['weixin_name']; ?>请亲朋友好友来吃团圆饭了，都来捧场，棒棒哒!',
        trigger:function(){},
        success:function(){},
        cancle:function(){}
    });
    $.ajax({
        url:'/index.php/mobiles/yx/ajax_get_ranking_list',
        type:'post',
        dataType:'json',
        error:function(){
            var tt1 = setTimeout(function(){
                clearTimeout(tt1);
            },500);
        },
        success:function(rs){
            if(rs){
                $(rs).each(function(i,v){
                    $('.popnr_nr').append('<p><font>'+(i+1)+'</font><font>'+ v.weixin_name+'</font><font>'+(Math.ceil(v.ranking_value/8))+'桌</font><font>'+ v.ranking_value+'人</font></p>');
                });
            }
        }
    });
    $(".download").click(function(){
        window.location.href = 'http://www.appurl.cc/631410';
    })
});
</script>
</html>