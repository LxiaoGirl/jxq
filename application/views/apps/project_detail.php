<!DOCTYPE html>
<html>
<head lang="en">
    <title><?php echo $subject; ?></title>
  <?php $this->load->view('common/apps/app_head') ?>
    <script src="/assets/js/app/jquery-1.11.3.min.js"></script>
</head>
<body>
<!-- 公共头部导航-->

<div class="container-fluid" style="background:#fff;">
    <div id="xm">
        <!-- 顶部导航  -->
        <div class="bg_red_jb mb10 row">
            <table class="c_fff" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td valign="top" class="text-center" width="34%">
                        <span>年化收益率</span>

                        <div class="lh40">
                            <strong class="f30"><?php echo rate_format($rate); ?></strong>%
                        </div>
                    </td>
                    <td valign="top" class="text-center" width="33%">
                        <span>借款天数</span>

                        <div class="lh40">
                            <span><?php echo $months; ?></span>个月
                        </div>
                    </td>
                    <td valign="top" class="text-center" width="33%">
                        <span>融资金额</span>

                        <div class="lh40">
                            <span><?php echo rate_format(price_format($amount, 3, FALSE)); ?></span>万元
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- 顶部导航  end-->
        <div class="mb20 row">
            <table class="bfb_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td width="120" class="text-center" valign="top">
                        <span class="c_666">投标进度</span>

                        <div class="clears"></div>
                        <div class="bfb  mt10  bfb80" data-bfb="
			<?php echo ($buy_time < time()) ? $receive_rate : 0; ?>
			"></div>
                        <!-- data-bfb 为百分比数字 -->
                        <span class="f12 c_666">*享受本金保障</span>
                    </td>
                    <td style="border-right:none;" valign="top">
                        <p class="c_666"
                           id="sysj-title"><?php if ($buy_time < time()): ?>投标剩余时间<?php else: ?>开标剩余时间<?php endif; ?></p>
                        <?php if ($buy_time > time() || ($status == 2 && $due_date > time() && $receive_rate < 100)): ?>
                            <p class="c_blue2" id="js_time">
                                <span id="ts" class="f20">0</span><span class="f12">天</span>
                                <span id="xs" class="f20">0</span><span class="f12">小时</span>
                                <span id="fs" class="f20">0</span><span class="f12">分</span>
                                <span id="ms" class="f20">0</span><span class="f12">秒</span>
                            </p>
                            <script>
                                var jst1 = '';
                                function djs1() {
                                    var mbtime = '<?php echo $buy_time>time()?$buy_time:$due_date; ?>';
                                    mbtime = parseInt(mbtime) * 1000;
                                    //目标时间，年，月（比实际要少1），日，十，分，秒
                                    var now = new Date();
                                    var dis = mbtime - now.getTime();
                                    if (dis <= 1000) {
                                        document.getElementById("sysj-title").innerText = '投标剩余时间';
                                        jst3 = setTimeout("djs3()", 1000);
                                        return null;
                                    }
                                    dis = Math.floor(Math.abs(dis) / 1000);
                                    //总的秒数
                                    var miao = dis % 60; // 秒
                                    var zf = Math.floor(dis / 60);
                                    //总分钟
                                    var fen = zf % 60;  //分钟数
                                    var zx = Math.floor(zf / 60);
                                    //总小时
                                    var xs = zx % 24;      //小时
                                    var tian = Math.floor(zx / 24);
                                    //天
                                    document.getElementById("ts").innerHTML = tian;
                                    document.getElementById("xs").innerHTML = xs;
                                    document.getElementById("fs").innerHTML = fen;
                                    document.getElementById("ms").innerHTML = miao;
                                    jst1 = setTimeout("djs1()", 1000);
                                }
                                var jst3 = '';
                                function djs3() {
                                    var mbtime = '<?php echo $due_date; ?>';
                                    mbtime = parseInt(mbtime) * 1000;
                                    //目标时间，年，月（比实际要少1），日，十，分，秒
                                    var now = new Date();
                                    var dis = mbtime - now.getTime();
                                    if (dis <= 1000) {
                                        var js_time = document.getElementById("js_time");
                                        var js_over = document.getElementById("js_over");
                                        js_time.parentNode.removeChild(js_time);
                                        js_over.style.display = "block";

                                        $("#invest-button").attr('href', 'javascript:void(0);').text("投标已结束");
                                        $("#invest-button").prop('disabled', true);
                                        $("#invest-button").attr('disabled', true);
                                        return null;
                                    }
                                    dis = Math.floor(Math.abs(dis) / 1000);
                                    //总的秒数
                                    var miao = dis % 60; // 秒
                                    var zf = Math.floor(dis / 60);
                                    //总分钟
                                    var fen = zf % 60;  //分钟数
                                    var zx = Math.floor(zf / 60);
                                    //总小时
                                    var xs = zx % 24;      //小时
                                    var tian = Math.floor(zx / 24);
                                    //天
                                    document.getElementById("ts").innerHTML = tian;
                                    document.getElementById("xs").innerHTML = xs;
                                    document.getElementById("fs").innerHTML = fen;
                                    document.getElementById("ms").innerHTML = miao;
                                    jst3 = setTimeout("djs3()", 1000);
                                }
                                djs1();
                            </script>
                        <?php else: ?>
                            <p class="c_blue2" id="js_over" style="display: block;">
                            <span class="f20 c_orange">
                                <?php if ($status == 2 && $due_date >= time() && $receive_rate < 100): ?>
                                    立即投标
                                <?php elseif ($status == 2 && $due_date < time()): ?>
                                    投标已结束
                                <?php elseif ($status == 2 && $receive_rate == 100): ?>
                                    融资完成
                                <?php elseif ($status == 2 && $buy_time > time()): ?>
                                    尚未开始
                                <?php else: echo borrow_status($status); endif; ?>
                            </span>
                            </p>
                        <?php endif; ?>

                        <div class="clears borderb mb10"></div>
                        <p class="mb10">还款方式</p>

                        <div class="f24">
                            <?php echo borrow_mode($mode); ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="clears borderb mt10"></div>
        <table class="tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td width="120">发布日期：</td>
                <td>
                    <span><?php echo my_date($add_time, 2); ?></span>
                </td>
            </tr>
            <tr>
                <td width="120">起投金额：</td>
                <td>
                    <span><?php echo rate_format(price_format($lowest, 2, false)); ?>元</span>
                </td>
            </tr>
            <tr>
                <td width="120">剩余可投金额：</td>
                <td>
                    <span><?php echo rate_format(price_format($amount - $receive, 2, false)); ?>元</span>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="mt10">
            <a id="showDetail" href="javascript:void(0)" class="btn btn-block btn-lg btn-default">
                <span class="vm">查看项目详情</span><span class="vm iconfont icon-xiangyou1"></span>
            </a>
        </p>
    </div>
    <div id="xmxq" class="row" style="height:1px; overflow:scroll;">
        <div class="xmxq_c" style="margin-top:3px">
            <!--顶部导航-->
            <div class="pd10 bg_red">
                <div style="padding-left:10px; padding-right:10px;">
                    <div id="tab_title" class="btn-group btn-group-justified">
                        <a href="javascript:void(0)" class="tab_ts btn btn-default current">项目详情</a>
                        <a href="javascript:void(0)" class="tab_ts btn btn-default">风险控制</a>
                        <a href="javascript:void(0)" class="tab_ts btn btn-default">还款计划</a>
                        <a href="javascript:void(0)" class="tab_ts btn btn-default">投资列表</a>
                    </div>
                </div>
            </div>
            <!--顶部导航-->
            <div id="tab_nr" class="pd10">
                <!-- 项目详情  -->
                <div class="tab_block">
                    <table class="c_666 tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td width="120">
                                发布日期：
                            </td>
                            <td>
                                <span class="c_333 f16"><?php echo my_date($add_time, 2); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120">
                                起投金额：
                            </td>
                            <td>
                                <span class="c_333 f16"><?php echo rate_format(price_format($lowest, 2, false)); ?>
                                    元</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120">
                                剩余可投金额：
                            </td>
                            <td>
                                <span
                                    class="c_333 f16"><?php echo rate_format(price_format($amount - $receive, 2, false)); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120">
                                预计还款时间：
                            </td>
                            <td>
                                <span
                                    class="c_333 f16"><?php echo date('Y-m-d', strtotime($repay_plan[0]['repay_date'])); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120">
                                融资人：
                            </td>
                            <td>
                                <span class="c_333 f16">
                                    <?php if($add_time > strtotime('2016-10-16 00:00:00')): ?>
                                        <?php $replace = "*";
         $temp['arr']   = preg_split('//u', $real_name, -1, PREG_SPLIT_NO_EMPTY);

		for($i = 1; $i <  count($temp['arr']); $i++)
        {
            $temp['arr'][$i] = $replace;
        }

       echo  $str = implode('', $temp['arr']);
?>
                                    <?php else: ?>
                                        <?php $replace = "*";
         $temp['arr']   = preg_split('//u', $real_name, -1, PREG_SPLIT_NO_EMPTY);

		for($i = 1; $i <  count($temp['arr']); $i++)
        {
            $temp['arr'][$i] = $replace;
        }

       echo  $str = implode('', $temp['arr']);?>
                                    <?php endif; ?>
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td width="120">
                                借款人介绍：
                            </td>
                            <td>
                                <span class="c_333 f16"><?php echo (!empty($content)) ? nl2br($content) : '-'; ?></span>
                            </td>
                        </tr>

                        <tr>
                            <td width="120">
                                借款用途：
                            </td>
                            <td>
                                <span class="c_333 f16"><?php echo (!empty($summary)) ? nl2br($summary) : '-'; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="120">
                                本期融资资金：
                            </td>
                            <td>
                                <span class="c_333 f16"><?php echo rate_format($amount); ?>元</span>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!-- 项目详情  -->

                <!-- 风险控制  -->
                <div class="tab_block" style="display: none;">
                    <div class="article">
                        <h1 class="text-center f20 c_red">【项目风险保障方案】</h1>
                        <?php echo (!empty($repayment)) ? nl2br($repayment) : '-'; ?>
                    </div>
                </div>
                <!-- 风险控制  -->

                <!-- 还款计划  -->
                <div class="tab_block" style="display: none;">
                    <table class="c_666 tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td width="50" class="text-center">期数</td>
                            <td class="text-center">还款日期</td>
                            <td class="text-center">还款金额(元)</td>
                            <td class="text-center">类型</td>
                        </tr>
                        <?php if (!empty($repay_plan)): ?>
                            <?php foreach ($repay_plan as $k => $v): ?>
                                <tr>
                                    <td class="text-center"><?php echo $v['repay_index']; ?></td>
                                    <td class="text-center"><span
                                            class="c_333"><?php echo my_date(strtotime($v['repay_date']), 2); ?></span>
                                    </td>
                                    <td class="text-center"><span
                                            class="c_333"><?php echo price_format($v['repay_amount'], 2, false); ?></span>
                                    </td>
                                    <td class="text-center"><span
                                            class="c_333"><?php if ($v['repay_type'] == 1):echo '利息';
                                            else:echo '本金';endif; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="4">没有相关信息</td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
                <!-- 还款计划  -->

                <!-- 投资列表  -->
                <div class="tab_block" style="display: none;">
                    <table class="c_666 tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td class="text-center">用户</td>
                            <td class="text-center">投资金额(元)</td>
                            <td class="text-center">投资时间</td>
                        </tr>
                        <?php if (!empty($log)&&$buy_time<time()): ?>

                           <?php foreach ($log as $v): ?>
                                <tr>
                                    <td class="text-center"><span
                                            class="c_333"><?php echo secret($v['mobile'], 5); ?></span></td>
                                    <td class="text-center"><span
                                            class="c_333"><?php echo price_format($v['amount'], 2, false); ?></span>
                                    </td>
                                    <td class="text-center"><span
                                            class="c_333"><?php echo my_date($v['dateline'], 2); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">等你来领投哦~~</td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
                <!-- 投资列表  -->
            </div>
        </div>
    </div>
    <div style="height:50px"></div>
    <!-- 底部按钮  -->
    <div class="row navbar-fixed-bottom">
        <a id="invest-button"
           href="<?php echo profile('uid') ? (($status == 2 && $due_date > time() && $buy_time < time() && $receive_rate < 100) ? site_url('apps/home/project_invest?borrow_no=' . $borrow_no) : 'javascript:void(0)') : 'javascript:check_to_login();'; ?>"
           class="btn btn-lg btn-danger btn-block no-radius" <?php if (profile('uid') > 0 && ($status > 2 || $due_date < time() || $receive_rate == 100 || $buy_time > time())):echo 'disabled';endif; ?>>
            <?php if (profile('uid') > 0): ?>
                <?php if ($status == 2||$status == 3): ?>
                    <?php if ($buy_time > time()): ?>
                        <script>
                            var jst2 = '';
                            function djs2() {
                                var mbtime = '<?php echo $buy_time; ?>';
                                mbtime = parseInt(mbtime) * 1000;
                                //目标时间，年，月（比实际要少1），日，十，分，秒
                                var now = new Date();
                                var dis = mbtime - now.getTime();
                                if (dis <= 1000) {
                                    $("#invest-button").attr('href', '<?php echo site_url('apps/home/project_invest?borrow_no='.$borrow_no); ?>').text("立即投标");
                                    $("#invest-button").prop('disabled', false);
                                    $("#invest-button").attr('disabled', false);
                                    return null;
                                }
                                dis = Math.floor(Math.abs(dis) / 1000);
                                //总的秒数
                                var miao = dis % 60; // 秒
                                var zf = Math.floor(dis / 60);
                                //总分钟
                                var fen = zf % 60;  //分钟数
                                var zx = Math.floor(zf / 60);
                                //总小时
                                var xs = zx % 24;      //小时
                                var tian = Math.floor(zx / 24);
                                //天
                                document.getElementById("invest-button").innerHTML = (tian ? tian + '天' : '') + (xs ? xs + '时' : '') + (fen ? fen + '分' : '') + (miao ? miao + '秒' : '');
                                jst2 = setTimeout("djs2()", 1000);
                            }
                            djs2();
                        </script>
                    <?php elseif ($due_date < time()): ?>
                        投标已结束
                    <?php elseif ($receive_rate == 100): ?>
                        融资完成
                    <?php else: ?>
                        立即投标
                    <?php endif; ?>
                <?php else:echo borrow_status($status); endif; ?>
            <?php else: ?>
                请登录后进行投资
            <?php endif; ?>
        </a>
    </div>
    <!-- 底部按钮  end-->
</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    $(function () {
        bfbFun();
        $("#showDetail").on("tap", function (event) {
            event.preventDefault();
            var dis = $("#xmxq").offset().top + 3;
            var h = $("#xmxq>.xmxq_c").height();
            $('body,html').animate({scrollTop: dis}, 500);
            $("#xmxq").animate({
                minHeight: $(window).height() - 40
            }, 500);
            setTimeout(function () {
//                $("#xm").hide();
                $(".xmxq_c").css('margin-top', 0);
            }, 500);
        });
    });
</script>
</html>