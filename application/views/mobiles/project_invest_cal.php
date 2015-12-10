<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0,user-scalable=no"/>
    <title>web1</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap33/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="js/flexslide/css/flexslider-m.css">
    <link rel="stylesheet" type="text/css" href="css/radialindicator.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
    <link rel="stylesheet" type="text/css" href="css/custom-media.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/ie8/html5shiv.min.js"></script>
    <script src="js/ie8/respond.min.js"></script>
    <![endif]-->
    <script src="js/functions.js"></script>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="bg_red_jb row mb10 c_fff">
        <div class="fl f16 mt10 ml10">
            车贷宝1号1351 <!-- 名字 -->
        </div>
        <div class="fr mt10 mr10">
            状态：投标中  <!-- 状态 -->
        </div>
        <div class="clears"></div>

        <div class="big_huan huan_jd_0" id="big_huan">
            <span class="big_huan_jd" id="big_huan_jd">50%</span> <!--投标进度-->
            <div class="big_fz_d">
                <span class="big_fz">54.39</span>%    <!--年化收益率-->
            </div>
            <span class="f16">年化收益率</span>
        </div>
        <script>
            huanxingJDT();
            /*控制环形进度条*/
        </script>
        <table class="c_fff f12 table_pdtb mt15 mb15" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td valign="bottom" class="text-center" width="34%">
                    <div>
                        <strong class="f18">15,000,000</strong>元
                    </div>
                    <span>融资金额</span>
                </td>
                <td style="border-left:1px #ffe8e7 solid; border-right:1px #ffe8e7 solid;" valign="bottom"
                    class="text-center" width="33%">
                    <div>
                        <strong class="f18">0.9</strong>个月
                    </div>
                    <span>还款期</span>
                </td>
                <td valign="bottom" class="text-center" width="33%">
                    <div>
                        <strong class="f18">15.6</strong>万元
                    </div>
                    <span>剩余可投金额</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 顶部导航  end-->

    <p class="text-right">
        预期收益
    </p>

    <div class="row">
        <div class="clears borderb"></div>
        <table class="bijiao_jdt" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">聚雪球车贷宝</span>
                        <span id="bg_a" class="shouyi_bg" style="background:#f86960"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_a" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">货币基金</span>
                        <span id="bg_b" class="shouyi_bg" style="background:#00acee"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_b" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="rel">
                        <span class="abs mz">银行活期</span>
                        <span id="bg_c" class="shouyi_bg" style="background:#88cb5a"></span><!--背景-->
                    </div>
                </td>
                <td width="100" class="text-right">
                    <span id="v_c" class="f16 text-nowrap">0.00元</span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 底部按钮  -->
    <div class="row rel">
        <div class="bg_white">
            <table class="table_nopd" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td>
                        <div class="bg_white">
                            <input id="jine" placeholder="请输入100元以上的金额" style="display:block; border:none;" type="text"
                                   class="no-radius form-control  input-lg">
                        </div>
                    </td>
                    <td width="80">
                        <button onclick="JS_calc(0.1288,0.1,0.03)" type="button"
                                class="btn fr no-radius btn-danger btn-lg">投标
                        </button>
                        <!-- 车贷宝收益，基金收益，银行活期利率：我随便写的值 -->
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- 底部按钮  end-->
</div>

<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.mobile.custom.min.js"></script>
<script src="css/bootstrap33/js/bootstrap.min.js"></script>
<script src="js/radialIndicator.min.js"></script>
<script src="js/flexslide/jquery.flexslider.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
