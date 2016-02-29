<!DOCTYPE html>
<html lang="en">
<head>
    <title>首页</title>
    <?php $this->load->view(WAP_VIEW_DIR.'common/head_file'); ?>
</head>
<body>
    <?php $this->load->view(WAP_VIEW_DIR.'common/head'); ?>

    <div class="fix_0">
        <div class="pos_r_0">
            <div class="pos_ab_0">
                <?php $this->load->view(WAP_VIEW_DIR.'common/left'); ?>
            </div>
            <div class="pos_ab_1">
               <div class="content">
                    <div class="header">
                        <p class="tc">聚雪球</p>
                        <img src="/assets/images/mobiles/1.png" class="zclan">
                        <div class="header_right">
                            <?php if( !$this->session->userdata('uid')): ?>
                                <a href="<?php echo site_url(WAP_CTRL_DIR.'login'); ?>">登录</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="content-fh"></div>
                    <div class="body">
                        <div class="zwhead"></div>
                        <div class="banner">
                            <div class="swiper-container banner-container">
                                <div class="swiper-wrapper banner-wrapper">
                                    <div class="swiper-slide"> <img src="/assets/images/mobiles/jsrd/banner1.jpg" alt="" width="100%"> </div>
                                    <div class="swiper-slide"> <img src="/assets/images/mobiles/jsrd/banner1.jpg" alt="" width="100%"> </div>
                                    <div class="swiper-slide"> <img src="/assets/images/mobiles/jsrd/banner1.jpg" alt="" width="100%"> </div>
                                </div>
                            </div>
                            <div class="pagination banner-pagination"></div>
                        </div>
                        <div class="sygg">
                            <img src="/assets/images/mobiles/17.jpg" width="100%" alt="">
                            <div class="gg-con">
                                <div class="ggnr">
                                    <p class="tc">聚雪球平台APP2.0版本升级通知0</p>
                                    <p class="tc">聚雪球平台APP2.0版本升级通知&nbsp;&nbsp;1</p>
                                    <p class="tc">聚雪球平台APP2.0版本升级通知&nbsp;&nbsp;&nbsp;&nbsp;2</p>
                                    <p class="tc">聚雪球平台APP2.0版本升级通知&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3</p>
                                </div>
                            </div>
                        </div>
                        <div class="sy_bd">
                            <div class="swiper-container sy_bd-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide" url_data="bd1">
                                            <div class="center">
                                                <p class="tit tc">车贷宝<span>1号-78</span></p>
                                                <div class="bd">
                                                    <canvas class="canvas" width="460" height="460"></canvas>
                                                    <div class="bd_mse">
                                                        <p>年化收益</p>
                                                        <p class="lv">12.0<font>%</font></p>
                                                        <p class="qx">期限 27 天</p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="swiper-slide" url_data="bd2">
                                            <div class="center">
                                                <p class="tit tc">车贷宝<span>1号-78</span></p>
                                                <div class="bd">
                                                    <canvas class="canvas" width="460" height="460"></canvas>
                                                    <div class="bd_mse">
                                                        <p>年化收益</p>
                                                        <p class="lv">12.0<font>%</font></p>
                                                        <p class="qx">期限 27 天</p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="swiper-slide" url_data="bd2">
                                            <div class="center">
                                                <p class="tit tc">车贷宝<span>1号-78</span></p>
                                                <div class="bd">
                                                    <canvas class="canvas" width="460" height="460"></canvas>
                                                    <div class="bd_mse">
                                                        <p>年化收益</p>
                                                        <p class="lv">12.0<font>%</font></p>
                                                        <p class="qx">期限 27 天</p>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pagination dis_no"></div>
                            <p class="but tc">
                                <button class="button1" id="ljtz">立即投资</button>
                            </p>                         
                        </div>
                        
                    </div>
                    <div class="footer tc">
                        <span class="ft_sp1 active"></span><span class="ft_sp2"></span><span class="ft_sp3"></span><span class="ft_sp4"></span>
                    </div>
               </div>
            </div>
        </div>
    </div>
</body>
<?php $this->load->view(WAP_VIEW_DIR.'common/footer'); ?>
<script>
jQuery(function($) {
        //进度条
        function jdtjz(i,a){
            var f = document.getElementsByClassName('bd');
            var h = f[0].offsetHeight;
            var canvas = document.getElementsByClassName('canvas');
            var canvas = canvas[i];
            var ctx = canvas.getContext('2d');
            var endjd = a/4+0.52;
            var raf;  
            var jdt = {
              y: 0.52,
              vy: 0.05,
              draw: function() {
                var lineargradient = ctx.createLinearGradient(0,125,250,125);
                lineargradient.addColorStop(0,'#0dd2db');;
                lineargradient.addColorStop(1,'#0f5faf');
                ctx.strokeStyle = lineargradient;
                ctx.lineWidth = h*0.06;
                ctx.lineCap = "round";
                ctx.beginPath();
                ctx.arc(h/2, h/2, h*0.45, 0.52 * Math.PI, this.y * Math.PI,false);
                ctx.stroke();
              }
            };
            function draw() {
                jdt.draw();
                jdt.y += jdt.vy;
                raf = window.requestAnimationFrame(draw);
                if(jdt.y>endjd){
                    window.cancelAnimationFrame(raf);
                }
            }
            raf = window.requestAnimationFrame(draw);
            jdt.draw();
        }
        //jdtjz(a,b);
        //a是标的的序号 从0开始
        //b是标的的进度值分为8个档参数为1-8；
        jdtjz(0,1);
        jdtjz(1,2);
        jdtjz(2,3);
        //banner
        var mySwiper1 = new Swiper('.banner-container',{
            pagination: '.banner-pagination',
            loop:true,
            grabCursor: true,
            paginationClickable: true
        })
        //标的转动
        var mySwiper = new Swiper('.sy_bd-container',{
            pagination: '.dis_no',
            paginationClickable: true,
            centeredSlides: true,
            slidesPerView: 1.68,
            watchActiveIndex: true,
            initialSlide :1,
        });
        //标的按钮
        $('#ljtz').click(function(){
            alert($('.sy_bd').find('.swiper-slide-visible').attr("url_data"));
        });
        // 侧边栏
        $('.zclan').click(function(){
            $(".pos_ab_1").animate({left: '83%',top:'10%',height:'80%'}, "500");
            $(".pos_ab_1").addClass('zcltch');
            $('.content-fh').show();
        })
        $('.content-fh').click(function(){
            $(".pos_ab_1").animate({left: '0%',top:'0%',height:'100%'}, "500");
            $('.content-fh').hide(500);
            $(".pos_ab_1").removeClass('zcltch');
        })
        $('body').on("swipeleft",function(){
            $(".pos_ab_1").animate({left: '0%',top:'0%',height:'100%'}, "500");
            $('.content-fh').hide(500);
            $(".pos_ab_1").removeClass('zcltch');
        })
        //公告
        var j = $('.ggnr').find('p').length;
        var i=0;
        $('.ggnr').find('p').eq(i).fadeTo(1000,0.9);
		$('.ggnr').find('p').eq(i).fadeTo(3000,1);
		$('.ggnr').find('p').eq(i).animate({top: '-100px', opacity: '0'}, 1200);
		$('.ggnr').find('p').eq(i).css("top","0px");
        i=i+1;
        function run(){
			$('.ggnr').find('p').eq(i).fadeTo(1000,0.9);
			$('.ggnr').find('p').eq(i).fadeTo(3000,1);

			$('.ggnr').find('p').eq(i).animate({top: '-100px', opacity: '0'}, 1200);
			$('.ggnr').find('p').eq(i).css("top","0px");
            i=i+1;
            if((i+1)>j){i=0;}
        }
        setInterval(run,4200);
        //注册成功提示
        sys_tspop('恭喜您，注册成功</br>是否开通资金存管账户?','稍后开启','','立即开通','#');
});     
</script>
</html>