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
    <link rel="stylesheet" type="text/css" href="/assets/activity_wish/active_yx/css/index-3.css">
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
    <audio  autoplay="autoplay">
        <source src="/assets/activity_wish/active_yx/yxjbj.mp3" type="audio/mpeg">
    </audio>
    <div class="body">
        <div class="con">
            <img id="prize" src="/assets/activity_wish/active_yx/images/20.jpg" width="100%" alt="">
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
                    <div class="lqbut"></div>
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
                        <?php for($i=$desk_count;$i>=1;$i--): ?>
                            <option value="<?php echo $i; ?>" <?php if($i == $desk_id): echo 'selected';endif; ?>>第<?php echo $i; ?>桌</option>
                        <?php endfor; ?>
                    <?php else: ?>
                        <option value="1">第1桌</option>
                    <?php endif; ?>
                </select>
                <input type="text" id="zhuo-input" name="zhuo" value="第<?php echo $desk_id; ?>桌" readonly="readonly">
            </div>
        </div>
        <div class="con conwz cus-name" style="width: 100%;"></div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/23.jpg" width="100%" alt="">
            <div class="fx"></div>
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/31.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/32.jpg" width="100%" alt="">
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/33.jpg" width="100%" alt="">
        </div>
        <div class="con conwz" style="text-align: right;width: 100%;">
            <a href="/active/yx_2016/index.html" style="color: #DD9559;">查看详情 &nbsp;&nbsp;&gt;</a>
        </div>
        <div class="con">
            <img src="/assets/activity_wish/active_yx/images/34.jpg" width="100%" alt="">
            <div class="download" style="width: 90%;height: 0.47rem; position: absolute;margin-left: 5%;margin-top: 0.8rem;"></div>
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
    <div class="popo1">
        <div class="zwp"></div>
        <img src="/assets/activity_wish/active_yx/images/35.png" width="100%" alt="" class="close">
        <div class="txpo">
            <span><img class="popo1-customer-headimgurl" src="" width="100%" alt=""></span>
        </div>
        <p class="jrmc popo1-customer-name"></p>
        <div class="anorwz popo1-yes">
            <img src="/assets/activity_wish/active_yx/images/36.png" width="100%" alt="">
            <div class="pabbut popo1-yes-btn"></div>
        </div>
        <div class="anorwz popo1-no">
            <p>未领取团圆饭</p>
        </div>
    </div>
    <div class="pop3">
        <div class="porpop3">
            <div class="pop3close"></div>
            <div class="poabpop3">
                <div class="conpop3">
                    <img src="/assets/activity_wish/active_yx/images/45.png" width="100%" alt="">
                    <div class="conpop3ab">
                        <div class="pop3close"></div>
                        <input type="text" id="mobile" name="mobile" class="inp1" placeholder="手机号/用户名/邮箱" value="">
                        <input type="password" id="password" name="password" class="inp2" placeholder="登录密码" value="">
                        <input type="text" id="captcha" name="captcha" class="inp3" placeholder="验证码" value="">
                        <div class="tpyzm">
                            <img id="imgCode" src="<?php echo site_url('send/captcha'); ?>" width="78" height="34" alt="验证码"
                                                onclick="javascript:this.src = '<?php echo site_url('send/captcha'); ?>?t='+ new Date().valueOf()"
                                                title="点击更换验证码"/></div>
                        <div class="butdl"></div>
                        <p class="zcwj"><a href="/index.php/mobiles/home/register">立即注册</a><a href="/index.php/mobiles/home/forget" class="fr">忘记密码</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pop2">
        <div class="popnr">
            <!--未中奖--><img id="prize-flag" src="/assets/activity_wish/active_yx/images/41.png" alt="" width="100%"><!--未中奖-->
            <!--已领取<img src="/assets/activity_wish/active_yx/images/42.png" alt="" width="100%">-->
            <div class="pab"></div>
            <div class="popnr_nr">
                <p class="pop4tit hava"><a href="/index.php/mobiles/yx/txsm">《红包提现说明》</a></p>
                <p class="totp"><span>一等奖(200元)</span></p>
                <p class="cap dave4"><span>排名</span><span>微信昵称</span><span>桌数</span><span>人数</span></p>
                <div class="prize-1"></div>
                <p class="totp"><span>二等奖(100元)</span></p>
                <p class="cap dave4"><span>排名</span><span>微信昵称</span><span>桌数</span><span>人数</span></p>
                <div class="prize-2"></div>
                <p class="totp"><span>三等奖(30元)</span></p>
                <p class="cap dave4"><span>排名</span><span>微信昵称</span><span>桌数</span><span>人数</span></p>
                <div class="prize-3"></div>
                <p class="totp"><span>幸运奖(1~20元)</span></p>
                <p class="cap dave4"><span>序号</span><span>微信昵称</span><span></span><span>奖金</span></p>
                <div class="prize-4"></div>
            </div>
            <img src="/assets/activity_wish/active_yx/images/44.png" style="margin-top:-1px;" width="100%" alt="">
        </div>
    </div>
    <div class="pop4" style="display: none;">
        <div class="pop4pre">
            <img src="/assets/activity_wish/active_yx/images/46.png" width="100%" alt="">
            <div class="pop4pab">
                <p class="p1">恭喜您获得</p>
                <p class="p2"><?php echo $prize; ?></p>
                <p class="p3"><?php echo $prize=='随机红包'?'(1~20元)':'现金红包'; ?></p>
            </div>
        </div>
        <p class="pop4tit hava"><a href="/index.php/mobiles/yx/txsm">《红包提现说明》</a></p>
        <button class="chb open-prize">拆红包</button>
        <p class="pop4tit"><font></font><font class="bor_no">领奖须知</font><font></font></p>
        <p class="pop4cell">
            1.拆开后红包将发放到您的聚雪球账户中；</br>
            2.登录聚雪球平台->个人中心->我的红包中领取；</br>
            3.劲爆大奖红包将在3月24日-28日内发放到您的聚雪球账户中。</br>
        </p>
    </div>

    <div class="pop pop1">
        <div class="popnr">
            <img src="/assets/activity_wish/active_yx/images/11.png" alt="" width="100%">
            <div class="pab"></div>
            <div class="popnr_nr ranking-list">
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
    $('.close').click(function(){
        $(".popo1").fadeOut();
    });
//-----------------------------------------------------------
    $('.pop2').click(function(){
        $(".pop2").fadeOut();
    });
    $('.pop4').click(function(){
        $(".pop4").fadeOut();
    });
    $('.pop3').find('.pop3close').click(function(){
        $(".pop3").fadeOut();
    });

    var cus = [];
    var get_customer = function(id){
        now_id = id;
        $('#zhuo-input').val('第'+id+'桌');
        if(typeof  cus[id] != "undefined"){
            var cus_name = '',cus_max = 1;
            $(cus[id]).each(function(i,v){
                $(".kh"+(i+1)).html('<img src="'+ v.weixin_avatar+'" width="100%;" alt="'+ v.weixin_name+'"  class="cus-ls" data-wish-id="'+ (v.customer_wish_id?v.customer_wish_id:0) +'" />');
                cus_name += (cus_name?'、':'')+v.weixin_name;
                cus_max = i+1;
            });
            if(cus_max < 8){
                for(var j=cus_max+1;j<=8;j++){
                    $(".kh"+j).html('');
                }
            }
            $(".cus-name").html(cus_name);
            $(".cus-ls").unbind('clcik').bind('click',function(){
                var wid = $(this).data('wishId');
                if($(this).data('wishId') == 0){
                    $(".popo1-no").show();
                    $(".popo1-yes").hide();
                }else{
                    $(".popo1-no").hide();
                    $(".popo1-yes").show();
                    $(".popo1-yes-btn").unbind('click').bind('click',function(){
                        window.location.href='/index.php/mobiles/yx/detail?wish_id='+wid;
                    });
                }
                $(".popo1-customer-name").html($(this).attr('alt'));
                $(".popo1-customer-headimgurl").attr('src',$(this).attr('src'));
                $(".popo1").fadeIn();
            });
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
                            $(".kh"+(i+1)).html('<img src="'+ v.weixin_avatar+'" width="100%;" alt="'+ v.weixin_name+'" class="cus-ls" data-wish-id="'+ (v.customer_wish_id?v.customer_wish_id:0) +'" />');
                            cus_name += (cus_name?'、':'')+v.weixin_name;
                            cus_max = i+1;
                        });
                        if(cus_max < 8){
                            for(var j=cus_max+1;j<=8;j++){
                                $(".kh"+j).html('');
                            }
                        }
                        $(".cus-name").html(cus_name);
                        $(".cus-ls").unbind('clcik').bind('click',function(){
                            var wid = $(this).data('wishId');
                            if($(this).data('wishId') == 0){
                                $(".popo1-no").show();
                                $(".popo1-yes").hide();
                            }else{
                                $(".popo1-no").hide();
                                $(".popo1-yes").show();
                                $(".popo1-yes-btn").unbind('click').bind('click',function(){
                                    window.location.href='/index.php/mobiles/yx/detail?wish_id='+wid;
                                });
                            }
                            $(".popo1-customer-name").html($(this).attr('alt'));
                            $(".popo1-customer-headimgurl").attr('src',$(this).attr('src'));
                            $(".popo1").fadeIn();
                        });
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
            $('#zhuo-input').val('第'+$(this).val()+'桌');
            get_customer($(this).val());
        }
    });
    get_customer(desk_id);

//-----------------------------------------------------
    var openid = '<?php echo $wish['openid']; ?>';
    var is_login = false;
    var get_lucky_list = function(){
        $.ajax({
            url:'/index.php/mobiles/yx/ajax_get_lucky_list',
            type:'post',
            dataType:'json',
            success:function(rs){
                if(rs){
                    $('.prize-4').html('');
                    $(rs).each(function(i,v){
                        var self_class = v.openid == openid?' self':'';
                        $('.prize-4').append('<p class="ceal dave4'+self_class+'"><span>'+(i+1)+'</span><span>'+ v.weixin_name+'</span><span></span><span>'+ v.prize+'元</span></p>');//(Math.ceil(v.ranking_value/8))  '+ v.ranking_value+'
                    });
                }
            }
        });
    };
    //领奖
    var get_prize = function(){
        _ajax_lo();
        $.ajax({
            url:'/index.php/mobiles/yx/ajax_get_wish_prize',
            type:'post',
            dataType:'json',
            data:{wish_id:'<?php echo $wish['wish_id']; ?>'},
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
                    if(rs.status == '10000'){
                        window.location.href='/index.php/mobiles/home/redbag';
                    }else{
                        _ajax_cg();
                        sys_alert(rs.msg);
                    }
                },500);
            }
        });
    };
    //显示拆红包
    var show_prize = function(){
        $(".pop4").fadeIn();
        $('.open-prize').unbind('click').bind('click',function(){
            if(is_login){
                get_prize();
            }else{
                $(".pop3").fadeIn();
                $('.butdl').unbind('click').bind('click',function(){
                    login();
                });
            }
        })
    };

    //登录
    var login = function(){
        if($("#mobile").val() == ''){
            sys_alert('请输入登录用户名!');
            return false;
        }
        if($("#password").val() == '' || $("#password").val().length < 6){
            sys_alert('请输入6位以上登录密码!');
            return false;
        }
        if($("#captcha").val() == ''){
            sys_alert('请输入验证码!');
            return false;
        }
        _ajax_lo();
        $.ajax({
            url:'/index.php/mobiles/yx/login',
            type:'post',
            dataType:'json',
            data:{mobile:$("#mobile").val(),password:$("#password").val(),captcha:$("#captcha").val()},
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
                    if(rs.status == '10000'){
                        var rs_data = rs.data;
                        is_real = rs_data.clientkind;
                        if(is_real != '1' && is_real != '2'){
                            sys_alert('请先进行实名认证哦',2,'/index.php/mobiles/home/real_name');
                        }else{
                            $(".pop3").fadeOut();
                            get_prize();
                            is_login = true;
                        }
                    }else{
                        sys_alert(rs.msg);
                    }
                },500);
            }
        });
    };

    if('<?php echo $is_self; ?>' == 'Y'&& '<?php echo $is_end; ?>' == 'Y'){ //
        $(".xf").remove();
        $("#prize").attr('src','/assets/activity_wish/active_yx/images/40.jpg');
        $('.lqbut').click(function(){
            if('<?php echo $wish['is_prize']; ?>' == '1' || '<?php echo $prize; ?>' == ''){
                if('<?php echo $wish['prize_level']; ?>' != '0')$("#prize-flag").attr('src','/assets/activity_wish/active_yx/images/42.png');
                //显示中奖名单
                $(".pop2").fadeIn();
            }else{
                show_prize();

                /*if(is_login){
                    show_prize();
                }else{
                    // 显示登录
                    $(".pop3").fadeIn();
                    $('.butdl').unbind('click').bind('click',function(){
                        login();
                    });
                }*/
            }
        });
    }
//-----------------------------------------------------

    wx_share.conf.title = '祝元宵节快乐！阖家团圆';
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
                var ranking_value_prev = 0;
                var ranking = 0;
                $(rs).each(function(i,v){
                    if(v.ranking_value != ranking_value_prev)ranking += 1;
                    $('.ranking-list').append('<p><font>'+ranking+'</font><font style="color:#d03432;">'+ v.weixin_name+'</font><font>'+(Math.ceil(v.ranking_value/8))+'桌</font><font>'+ v.ranking_value+'人</font></p>');
                    ranking_value_prev = v.ranking_value;

                    var self_class = v.openid == openid?' self':'';
                    if(ranking >= 1 && ranking <= 5){
                        $('.prize-1').append('<p class="ceal dave4'+self_class+'"><span>'+ranking+'</span><span>'+ v.weixin_name+'</span><span>'+(Math.ceil(v.ranking_value/8))+'</span><span>'+ v.ranking_value+'</span></p>');
                    }else if(ranking > 5 && ranking <= 15){
                        $('.prize-2').append('<p class="ceal dave4'+self_class+'"><span>'+ranking+'</span><span>'+ v.weixin_name+'</span><span>'+(Math.ceil(v.ranking_value/8))+'</span><span>'+ v.ranking_value+'</span></p>');
                    }else if(ranking > 15 && ranking <= 35){
                        $('.prize-3').append('<p class="ceal dave4'+self_class+'"><span>'+ranking+'</span><span>'+ v.weixin_name+'</span><span>'+(Math.ceil(v.ranking_value/8))+'</span><span>'+ v.ranking_value+'</span></p>');
                    }
                });
            }
        }
    });
    get_lucky_list();

    $(".download").click(function(){
        window.location.href = 'http://www.appurl.cc/631410';
    });
    if('<?php echo $wish['ranking_value']==0&&$wish['openid']==$this->session->userdata('openid'); ?>' && '<?php echo $is_end; ?>' == 'N'){
        $('.popo').fadeIn();
    }
});
</script>
</html>