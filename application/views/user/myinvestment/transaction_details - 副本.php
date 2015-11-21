<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <link rel="icon" href="../../favicon.ico" mce_href="../../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/common.css')?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/index.css')?>">
    <script src="<?php echo base_url('assets/js/jquery/jquery-2.1.1.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/sys/sys.js')?>"></script>
    <script src="<?php echo base_url('assets/js/echarts.js')?>"></script>
</head>
<body>
    <!--head start-->
      <?php $this->load->view('common/head');?>   
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的投资</a>&nbsp;>&nbsp;<a href="">交易明细</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <div class="user_left">
            <div class="user_center_pro">
                <div class="user_icon"><img src="<?php echo base_url('assets/images/common/my_icon.jpg')?>" width="100"></div>
                <p class="tc">上午好，韩俊博<span>vip<font>0</font></span></p>
                <p class="str_img">
                    <a href=""><img src="<?php echo base_url('assets/images/common/user_left_1.png')?>"></a><a href=""><img src="<?php echo base_url('assets/images/common/user_left_2.png')?>"></a><a href=""><img src="<?php echo base_url('assets/images/common/user_left_3.png')?>"></a>
                </p>
                <p>安全等级<a href="">去提升</a><font class="fr">低</font></p>
                <p class="pre"><span><span style=" width:30%;"></span></span></p>
            </div>
            <ul>
                <a class="leaders" href=""><li class="leaders"><img src="<?php echo base_url('assets/images/common/user_left_icon_1.png')?>">我的账户</li></a>
                <a href="<?php echo site_url('user/user/account_home');?>"><li>资金总览<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>我的等级<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/my_xq');?>"><li>我的雪球<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/my_redbag');?>"><li>我的红包<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>充值提现<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/information');?>"><li>消息中心<font class="fr">></font></li></a>
                <a href="" class="leaders" href=""><li class="leaders"><img src="<?php echo base_url('assets/images/common/user_left_icon_2.png')?>">我的投资</li></a>
                <a href="<?php echo site_url('user/user/transaction_details');?>"><li>交易明细<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/transaction_note');?>"><li>投资记录<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/auto');?>"><li>自动投标<font class="fr">></font></li></a>
              <!--  <a href="" class="leaders" href=""><li class="leaders"><img src="<?php echo base_url('assets/images/common/user_left_icon_3.png')?>">我的借贷</li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>我的借款<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/recharge');?>"><li>申请借款<font class="fr">></font></li></a>-->
                <a href="" class="leaders" href=""><li class="leaders"><img src="<?php echo base_url('assets/images/common/user_left_icon_4.png')?>">账户设置</li></a>
                <a href="<?php echo site_url('user/user/account_information');?>"><li>基本信息<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/card');?>"><li>银行卡管理<font class="fr">></font></li></a>
                <a href="<?php echo site_url('user/user/invite');?>"><li>邀请好友<font class="fr">></font></li></a>
            </ul>
        </div>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            
            <!--<h1>交易明细</h1>
            <div id="tra_det_main"></div>
            -->
            <div class="pre_mon_tra_det">
                <p class="h2">每月交易详情</p>
                <ul class="tab_title ">
                    <li class="active">2015年9月</li>
                    <li>2015年8月</li>
                    <li>2015年7月</li>
                    <li>2015年6月</li>
                    <li>2015年5月</li>
                    <li>2015年4月</li>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <p class="title"><font>详情</font><font>类型</font><font>金额（元）</font><font>时间</font></p>
                        <p class="lie"><font>充值</font><font>收入</font><font class="green">+1,000</font><font>2015-09-18 18:30:26</font></p>
                        <p class="lie"><font>投资车贷宝1号-26</font><font>支出</font><font></font><font>2015-09-18 18:30:26</font></p>
                        <p class="lie"><font>提现</font><font>支出</font><font class="red">-500</font><font>2015-09-18 18:30:26</font></p>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
<div class="quick_f">
   <div class="quick_content">
   <p class="fl">想快速赚钱？快来</p>
   <a href="#"><div class="f_login tc fl">登录/注册</div></a>
    <p class="fl">吧</p>
   </div>
</div>
<div class="footer">
<div class="row clearfix">
    <div class="fo1 clearfix">
        <div class="fo1_l fl clearfix">
        <dl class="fo_dl">
        <dt>我的钱包</dt>    
        <a href="#"><dd>如何注册</dd></a>
        <a href="#"><dd>修改或找回密码</dd></a>
        <a href="#"><dd>个人资料修改</dd></a>
        <a href="#"><dd>充值与提现</dd></a>
        </dl>
        <dl class="fo_dl fo_dd">
        <dt>投资和借款</dt>    
        <a href="#"><dd>聚雪球投款标的类型</dd></a>
        <a href="#"><dd>聚雪球借款标的类型</dd></a>
        <a href="#"><dd>投资人的资格</dd></a>
        <a href="#"><dd>借款人的资格</dd></a>
        <a href="#"><dd>投资的额度</dd></a>
        <a href="#"><dd>借款人资料填写码</dd></a>
        <a href="#"><dd>投标后能否取消</dd></a>
        <a href="#"><dd>还款方式</dd></a>
        </dl>
        <dl class="fo_dl">
        <dt>资费说明</dt>    
        <a href="#"><dd>第三方费用</dd></a>
        <a href="#"><dd>借款用户费用</dd></a>
        <a href="#"><dd>投资用户费用</dd></a>
        </dl>
        <dl class="fo_dl">
        <dt>还款</dt>    
        <a href="#"><dd>如何还款</dd></a>
        <a href="#"><dd>借款到期后能否延期还款</dd></a>
        <a href="#"><dd>逾期还款的处理办法</dd></a>
        <a href="#"><dd>如何提前还款</dd></a>
        </dl>
        </div>
        <div class="fo1_r fr">
        <dl class="fo_dl"><dt>手机玩聚雪球</dt></dl>
        <div class="down_l fl">
        <a href="#"><div class="down_btn1">下载Android版</div></a>
        <a href="#"><div class="down_btn2">下载iPhone版</div></a>
        </div>
        <div class="down_2 fl"><img src="<?php echo base_url('assets/images/common/footer_erm.jpg')?>" width="100" height="100"></div>
        </div>
    </div>   
    <div class="fo2">
    <p class="fo-p1 tc"><a href="#"><img src="<?php echo base_url('assets/images/common/f_logo.png')?>" width="21" height="15">关于聚雪球</a>   <a href="#">公司资质</a>   <a href="#">媒体报道</a>   <a href="#">新闻中心 </a>  <a href="#">加入我们</a>  <a href="#"> 法律声明</a>   <a href="#">联系我们</a>   <a href="#">帮助中心</a>   <a href="#">新手指引</a>   辽ICP备15006535号</p>
    <p class="fo-p1 tc"><span>Copyright © 2009-2015 ZGWJJF 沈阳网加互联网金融服务有限公司</span>   <span>服务热线：4007 918 333（个人/企业）</span>服务时间：9:00-21:00</p>
    <p class="fo-p2 tc">友情链接：<a href="http://ln.qq.com/" target="_new">腾讯·大辽网</a> ·<a href="http://www.ce.cn/" target="_new"> 中国经济网 </a>·<a href="http://www.163.com/" target="_new"> 网易</a> ·<a href="http://cn.chinadaily.com.cn/" target="_new"> 中国日报网</a> · <a href="http://www.wangdaizhijia.com/" target="_new">网贷之家</a> ·<a href="http://www.eastmoney.com/" target="_new"> 东方财富网</a> ·<a href="http://www.eeo.com.cn/" target="_new"> 经济观察网</a></p>
    <p class="fo-p3 tc"><img src="<?php echo base_url('assets/images/common/footer1.jpg')?>">
    <img src="<?php echo base_url('assets/images/common/footer2.jpg')?>">
    <img src="<?php echo base_url('assets/images/common/footer3.jpg')?>">
    <img src="<?php echo base_url('assets/images/common/footer4.jpg')?>" ></p>
    </div>
</div>
</div>
    <!--底部-->                   
</body>
<script type="text/javascript">
//头部
        addnav($(".nav"));
        addnav($(".main_nav"));
        main_nav_pop($(".main_nav").find($(".fr")).find($("li")));
        nav_pop($(".nav_have_son"));
//交易详情
        tab($('.pre_mon_tra_det'));
//图表
// 路径配置
        require.config({
            paths: {
                echarts: '<?php echo base_url("assets/js/")?>'
            }
        });
 // 使用
        require(
            [
                'echarts',
                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            ],
function (ec) {
// 基于准备好的dom，初始化echarts图表
var myChart = ec.init(document.getElementById('tra_det_main')); 
var option = { 
    title : {
        text: '最近六个月余额一览',
        x:'center',
        textStyle:{
            fontSize: 16,
            color: '#454545'
        }
    },
    tooltip: {
        trigger: "axis",
        axisPointer:{
            type: "none"
        }
    },
    xAxis : [
        {
            type : 'category',
            data : ['2015年4月','2015年5月','2015年6月','2015年7月','2015年8月','2015年9月']
        }
    ],
    yAxis: [
        {
            type: "value",
        }
    ],
    series : [
        {
            name:'余额',
            type:'bar',
            itemStyle: {        // 系列级个性化样式，纵向渐变填充
                normal: {
                    color : "#4fc1e9"
                }
            },
            data:[1320, 1132, 601, 234, 120, 90]

        }
    ]
};
// 为echarts对象加载数据 
myChart.setOption(option); 
}
);
</script>
</html>