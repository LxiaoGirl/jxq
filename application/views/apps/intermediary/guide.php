<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>邀请好友</title>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="stylesheet" type="text/css" href="/assets/css/app/m-swipe.css">
</head>
<body class="page-swipe">
<header>
  <div id="slider" class="swipe" style="visibility: visible;">
    <div class="swipe-wrap">
      <figure>
        <div class="wrap">
          <div class="image" style="background:url(../../../../../assets/images/app/page1.jpg) center top no-repeat;background-size: 100% 100%">
              <div class="container">
                <img src="/assets/images/app/page1_02.png" style="width:100%; margin-top:1rem;">
                <p style="text-align:center; color:#fff; font-size:1.4rem; line-height:2.8rem;">2015-10-10 10:00 至 2015-10-30 10:00</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.8rem;">活动期间内您每邀请一个好友成功投资后</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.8rem;">可获得现金红包<font>20</font>元</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.8rem;">每天派发<font>30</font>个红包</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.8rem;">先到先得，发完为止，可重复领取</p>
                <div class="bott">
                    <p style="width:80%; margin-left:10%; text-align:left; color:#fff; font-size:1.6rem; line-height:1.8rem;">无上限奖金等你拿（无限期）</p>
                    <p style="width:80%; margin-left:10%; text-align:left; color:#fff; font-size:1.4rem; line-height:1.8rem;">您推荐的好友每月有效投资总额，我们在下月的25号为您核算收益，收益为好友投资总额的0.1%</p>
                </div>
              </div>
          </div>
        </div>
      </figure>
      <figure>
        <div class="wrap">
          <div class="image" style="background:url(../../../../../assets/images/app/page2.jpg) center top no-repeat;background-size: cover">
              <div class="container">
                    <div class="btmk">
                        <div class="btmk_top">
                            <p class="tit">投资人加息活动：</p>
                            <p class="blac">2015-10-10 10:00 至 2015-11-30 10:00</br>拥有推荐人的投资人，投资就享受加息。</p>
                            <p class="tit">投资加息规则如下：</p>
                            <p class="blac">通过您邀请注册的投资人，享受<font style="font-size:1.6rem;">加息0.5%</font></p>
                        </div>
                        <p class="tit" style="font-size:1.2rem; padding-left:1rem">*注：只有首次投资标的，加息一次</p>                        
                    </div>
                    <a href="<?php echo site_url('mobiles/intermediary/apply?inviter_no='.$inviter_no); ?>" class="butan"><img src="/assets/images/app/page2_03.png" width="100%"></a>
              </div>
          </div>
        </div>
      </figure>
    </div>
  </div>
  <nav>
    <ul id="position">
      <li class="on"></li>
      <li class=""></li>
    </ul>
  </nav>
</header>
<script src="/assets/js/app/jquery-1.11.3.min.js"></script>
<script src="/assets/js/app/swipe.js"></script>
<script>
var hh=$(window).height();
$(".wrap").height(hh);
var slider =
  Swipe(document.getElementById('slider'), {
    auto: false, //设置自动切换时间，单位毫秒
    continuous: true,  //无限循环的图片切换效果
    disableScroll: true,  //阻止由于触摸而滚动屏幕
    stopPropagation: false,  //停止滑动事件
    callback: function(pos) {

      var i = bullets.length;
      while (i--) {
        bullets[i].className = ' ';
      }
      bullets[pos].className = 'on';

    }
  });
var bullets = document.getElementById('position').getElementsByTagName('li');
</script>
</body>
</html>