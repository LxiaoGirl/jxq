<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>邀请好友</title>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="stylesheet" type="text/css" href="/assets/css/app/m-swipe.css">
<style type="text/css">
    .xuanfu1{
        background:rgba(9, 38, 89, 0.6);
        width:90%;
        margin-left: 5%;
        margin-top: 1rem;
        border-radius: 0.4rem;
        height:8rem;
        text-align: center;
        color:#fff;
        font-size: 1.6rem;
        line-height: 4rem;
        overflow: hidden;
        position: relative;
    }
    .xuanfu2{
        background:#fff;
        margin-left: 0;
        margin-top: -1px;
        border-radius: 0;
        width:100%;
        height:6rem;
        text-align: center;
        color:#666;
        font-size: 1.6rem;
        line-height: 3rem;
    }
    .xuanfu_con{
        position: absolute;
        top:0;
        width:100%;
    }
    .xuanfu1 font{
        color:#ffde00;
    }
    .xuanfu1 td{
        width:39%;
        display: inline-block;
        float: left;
    }
    table{
        width:100%;
    }
    thead{
        display:none;
    }
    table,tr,td{
        margin:0;
        padding: 0;
        border:none;
    }
    .xuanfu2 tr:first-child{
        width:20%;
    }
    .btmk{
        top:30%;
    }
</style>
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
                <p style="text-align:center; color:#fff; font-size:1.4rem; line-height:2.2rem;"></p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.4rem;">活动期间内您每邀请一个好友成功投资1000后</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.4rem;">可获得现金红包<font>20</font>元</p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.4rem;"></p>
                <p style="text-align:center; color:#fff; font-size:1.6rem; line-height:2.4rem;">先到先得，发完为止，可重复领取</p>
                <div class="xuanfu1 xuanfu3">
                    <div class="xuanfu_con xuanfu_con3">
                        <?php if( ! empty($redbag['data'])):foreach ($redbag['data'] as $key => $value):?>
                            <p><?php echo date('H:i',$value['receive_time']); ?>
                                <?php echo secret($value['mobile'],4); ?>    
                                获得<font><?php echo $value['amount']; ?></font>元红包
                            </p>
                        <?php endforeach;endif;?>
                    </div>
                </div>
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
                            <p class="blac">拥有推荐人的投资人，首次投资享受加息。</p>
                            <p class="blac">通过您邀请注册的投资人，享受<font style="font-size:1.6rem;">加息0.5%</font></p>
                        </div>
                        <p class="tit" style="font-size:1.2rem; padding-left:1rem">*注：首笔投资享受加息</p>
                        <p style="font-size:1.6rem; color:#fff; background:#b90d0d; line-height:2.4rem; text-align:left;text-indent:5%;">实时投资榜</p>
                        <p style=" background:#fff; font-size:1.6rem; margin:0; padding:0;"><font style=" display:inline-block; width:21%; color:#666">时间</font><font style=" display:inline-block; width:39%; color:#666">用户名</font><font style=" display:inline-block; width:39%; color:#666">投资金额</font></p>
                        <div class="xuanfu1 xuanfu2">
                            <div class="xuanfu_con xuanfu_con2">
                            <table>
                                <thead>
                                <tbody>
                                    <?php if( ! empty($invest_list['data'])):foreach ($invest_list['data'] as $key => $value):?>
                                        <tr>
                                            <td style="width:21%"><?php echo date('H:i',$value['dateline']); ?></td>
                                            <td><?php echo secret($value['mobile'],4); ?> </td>
                                            <td>¥<?php echo $value['amount']; ?> </td>
                                        </tr>
                                    <?php endforeach;endif;?>
                                </tbody>
                            </table>
                            </div>
                        </div>                       
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
var xfh1=$(".xuanfu_con3").height();
var xfkh1=$(".xuanfu3").height();
var xfh2=$(".xuanfu_con2").height();
var xfkh2=$(".xuanfu2").height();
if(xfh1>xfkh1){
    run1();
}
function run1() {
            interval = setInterval(chat1, "30");
        }

function chat1() {
            position = $(".xuanfu_con3").position();
            pleft = position.top - 1;
            if (-pleft + xfkh1 == xfh1) {
                pleft = 0
            }
            $(".xuanfu_con3").css("top", "" + pleft + "px");
        }
if(xfh2>xfkh2){
    run2();
}
function run2() {
            interval = setInterval(chat2, "40");
        }

function chat2() {
            position = $(".xuanfu_con2").position();
            pleft = position.top - 1;
            if (-pleft + xfkh2 == xfh2) {
                pleft = 0
            }
            $(".xuanfu_con2").css("top", "" + pleft + "px");
        }


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