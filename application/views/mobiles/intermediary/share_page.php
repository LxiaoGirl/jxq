<!DOCTYPE !!>
<html>
<head>
    <title></title>
    <meta name="description" content=""/>
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta charset="utf-8"/>
    <meta name="baidu-site-verification" content="NWiIzGM1AG"/>
    <link href="https://www.zgwjjf.com/assets/css/pages/mycss.css" rel="stylesheet" type="text/css">
    <script src="https://www.zgwjjf.com/assets/js/seajs/sea.js" type="text/javascript"></script>
    <script src="https://www.zgwjjf.com/assets/js/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="https://www.zgwjjf.com/assets/js/jquery/myjs.js" type="text/javascript"></script>
    <style type="text/css">
        #imgbox{
            position: relative;
        }
        #dw{
            position: absolute;
            top: 54%;
            left: 53%;
            width:35%;
            overflow: hidden;
            color: #333;
            font-size: 1.2rem;
        }
        #dw p{
            display: inline-block;
        }
    </style>
</head>

<body class="content_wrap">
<div class="wrap">
    <div class="header">
        <h1>聚雪球理财平台</h1>
        <a class="hlogo" href="#"><span>聚雪球</span></a>
    </div>
    <div id="imgbox">
        <img src="https://www.zgwjjf.com/assets/images/jujianren/pic1.jpg" width="100%" alt="聚雪球"/>
        <div id="dw">
            <img src="<?php echo $headimgurl?$headimgurl:'/assets/images/app/mrtx.png'; ?>" width="57%">
            <p><b><?php echo $nickname?$nickname:''; ?></b></p>
            </br>
            我已经在平台理财了,小伙伴们快快加入吧！
        </div>
    </div>
    <div class="reg_btn">
        <?php if (!empty($inviter_no)): ?>
            <a href="https://www.zgwjjf.com/index.php/jujianren/?inviter_no=<?php echo $inviter_no; ?>"></a>
        <?php else: ?>
            <?php echo "您没有邀请人哦"; ?>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
