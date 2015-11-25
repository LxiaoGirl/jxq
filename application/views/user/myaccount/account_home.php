<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
    <script src="../../../../../assets/js/jquery/jquery-2.1.1.min.js"></script>
    <script src="../../../../../assets/js/sys/sys.js"></script>
    <script src="../../../../../assets/js/echarts.js"></script>
</head>
<body>
    <!--head start-->

<?php $this->load->view('common/head');?>
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的账户</a>&nbsp;>&nbsp;<a href="">资金总览</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            <div class="account">
                <div class="zctcqwd">
                    <div class="left">
                        <div class="height_half col_333 font_size_22 line_hei80 text_in">
                            账户总资产（元）
                        </div>
                        <div class="height_half col_333 font_size_40 font_w_bold line_hei80 text_in">
                            <?php echo $data['property_total'];?>
                        </div>
                    </div>
                    <div class="center">
                        <div class="height_half">
                            <a href="<?php echo site_url('user/user/recharge');?>"><button class="cz">充值</button></a><a href="<?php echo site_url('user/user/withdrawals');?>"><button class="tx">提现</button></a><a href="<?php echo site_url('user/user/transaction_details');?>">查看交易明细</a><a href="<?php echo site_url('invest');?>">去投资</a>
                        </div>
                    </div>
                    <div class="right">
                        <div class="height_half">
                            <div class="height_half">
                                <span>我的雪球：<?php echo $snowball_num?>个</span>
                            </div>
                            <div class="height_half">
                                <a href="<?php echo site_url('about/help'); ?>">如何获得雪球</a><a href="<?php echo site_url('about/help'); ?>">雪球能做什么</a>
                            </div>
                        </div>
                        <div class="height_half">
                            <div class="height_half">
                                <span>待领取的红包：<?php echo $red_bag['data']['num']?>个</span>
                            </div>
                            <div class="height_half">
                                <a href="<?php echo site_url('user/user/my_redbag');?>">去领取</a><a href="<?php echo site_url('user/user/my_redbag_lq');?>">查看已领取的红包</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stre_pre">
                    <em></em>
                    <div class="precent_q">
                        <div class="precent_ec" id="acc_mian_1"></div>
                        <div class="top">可用余额（元）</div>
                        <div class="botton color_3cb5ec"><?php echo $data['balance']?></div>
                        <div class="pre_sz"><?php echo percent($data['property_total'],$data['balance']);?>%</div>
                    </div>
                    <div class="jiah">+</div>
                    <div class="precent_q">
                        <div class="precent_ec" id="acc_mian_2"></div>
                        <div class="top">待收本金（元）</div>
                        <div class="botton"><?php echo $data['wait_principal_total']?></div>
                        <div class="pre_sz"><?php echo percent($data['property_total'],$data['wait_principal_total']);?>%</div>
                    </div>
                    <div class="jiah">+</div>
                    <div class="precent_q">
                        <div class="precent_ec" id="acc_mian_3"></div>
                        <div class="top">冻结金额（元）</div>
                        <div class="botton"><?php echo $data['invest_freeze_total']+$data['transfer_freeze_total']?></div>
                        <div class="pre_sz"><?php echo percent($data['property_total'],$data['invest_freeze_total']+$data['transfer_freeze_total']); ?>%</div>
                    </div>
                </div>
                <div class="dssy">
                    <div class="wid33">
                        <div class="top">累计投资（元）</div>
                        <div class="botton"><?php echo $data['invest_total']?></div>
                    </div>
                    <div class="wid33">
                        <div class="top">累计收益（元）</div>
                        <div class="botton"><?php echo $data['receive_interest_total']?></div>
                    </div>
                    <div class="wid33">
                        <div class="top">预计收益（元）</div>
                        <div class="botton"><?php echo $data['wait_interest_total']?></div>
                    </div>
                </div>
                <p class="line_h86">投资收益<a href="<?php echo site_url('user/user/transaction_note');?>">查看投标记录</a></p>
                <div class="tzfb_ec" id="tzfb_ec"></div>
            </div>
        </div>
		
		
        <!--右侧-->
    </div>
<!--底部--> 
 <?php $this->load->view('common/footer');?> 
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
                'echarts/chart/bar',
                'echarts/chart/pie',
                'echarts/chart/line',
            ],
function (ec) {
// 基于准备好的dom，初始化echarts图表
var labelTop = {
    normal : {
        color:'#3cb5ec',
        label : {
            show : false
        },
        labelLine : {
            show : false
        }
    }
};
var labelBottom = {
    normal : {
        color: '#ccc',
        label : {
            show : false,
        },
        labelLine : {
            show : false
        }
    }
};
option = {
    calculable : false,
    series : [
        {
            name:'',
            type:'pie',
            radius : ['80%', '100%'],
            center : ["50%", "50%"],
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                }
            },
            data:[
            {value:<?php echo ($data['property_total']!=0)?$data['property_total']-$data['balance']:1;?>, name:'sy',itemStyle : labelBottom},
            {value:<?php echo $data['balance'];?>, name:'bf',itemStyle : labelTop}
            ]
        }
    ]
};
option1 = {
    calculable : false,
    series : [
        {
            name:'',
            type:'pie',
            radius : ['80%', '100%'],
            center : ["50%", "50%"],
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                }
            },
            data:[
            {value:<?php echo ($data['property_total']!=0)?$data['property_total']-$data['wait_principal_total']:0.1;?>, name:'sy',itemStyle : labelBottom},
            {value:<?php echo $data['wait_principal_total'];?>, name:'bf',itemStyle : labelTop}
            ]
        }
    ]
};
option2 = {
    calculable : false,
    series : [
        {
            name:'',
            type:'pie',
            radius : ['80%', '100%'],
            center : ["50%", "50%"],
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                }
            },
            data:[
            {value:<?php echo ($data['property_total']!=0)?$data['property_total']-$data['invest_freeze_total']-$data['transfer_freeze_total']:1;?>, name:'sy',itemStyle : labelBottom},
            {value:<?php echo $data['invest_freeze_total']+$data['transfer_freeze_total']?>, name:'bf',itemStyle : labelTop}
            ]
        }
    ]
};
var option3 = { 
    title : {
        text: '最近六个月投资收益一览',
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
            data : [<?php echo $months;?>]
        }
    ],
    yAxis: [
        {
            type: "value",
            name : '金额',
        },
        {
                            type : 'value',
                            name : '金额',
                            axisLabel : {
                                formatter: '{value}'
                            }
        }
    ],
    series : [
        {
            name:'投资总额',
            type:'bar',
            itemStyle: {        // 系列级个性化样式，纵向渐变填充
                normal: {
                    color : "#4fc1e9"
                }
            },
            data:[<?php echo $tz;?>]

        },
        {
                            name:'投资收益',
                            type:'line',
                            itemStyle: {        // 系列级个性化样式，纵向渐变填充
                                normal: {
                                    color : "#599b30"
                                }
                            },
                            yAxisIndex: 1,
                            data:[<?php echo $sy;?>]
        }
    ]
};
// 为echarts对象加载数据 
var myChart = ec.init(document.getElementById('acc_mian_1'));
var myChart1 = ec.init(document.getElementById('acc_mian_2'));
var myChart2 = ec.init(document.getElementById('acc_mian_3'));
var myChart3 = ec.init(document.getElementById('tzfb_ec'));
myChart.setOption(option); 
myChart1.setOption(option1);
myChart2.setOption(option2); 
myChart3.setOption(option3); 
}
);
</script>
</html>
