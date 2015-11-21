<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>
<!--head start-->
  <?php $this->load->view('common/head');?>      
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的投资</a>&nbsp;>&nbsp;<a href="">投资记录</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>投资记录</h1>
            <div class="tra_note">
                <ul class="tab_title tab_title_small">
                    <li class="active">全部<font class="fr">|</font></li>
                    <li class="">回款中<font class="fr">|</font></li>
                    <li class="">回款完成<font class="fr">|</font></li>
                    <li class="">热售中<font class="fr">|</font></li>
                    <li class="">已售馨</li>
                        <div class="section">                            
                            <font style="margin-left:30px;">选择日期：</font><input type="text" class="date_picker_1">
                            <font>至&nbsp;&nbsp;</font><input type="text" class="date_picker_2">
                            <button>查询</button>
                        </div>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <p class="title"><span class="wid20">投资项目</span><span>年收益率</span><span class="wid20">投资金额（元）</span><span>投资时间</span><span>计息日</span><span>还款日</span><span>状态</span></p>
                        <p class="lie"><span class="wid20"><font>车贷宝1号-27</font></br>编号：464989761316</span><span>12%</span><span class="wid20"><font>20,000.00</font></br>预计收益：200.00</span><span>2015-09-21</span><span>2015-09-21</span><span>2015-09-21</span><span>收益中</span></p>
                        <p class="lie"><span class="wid20"><font>车贷宝1号-27</font></br>编号：464989761316</span><span>12%</span><span class="wid20"><font>20,000.00</font></br>预计收益：200.00</span><span>2015-09-21</span><span>2015-09-21</span><span>2015-09-21</span><span>收益中</span></p>
                        <p class="lie"><span class="wid20"><font>车贷宝1号-27</font></br>编号：464989761316</span><span>12%</span><span class="wid20"><font>20,000.00</font></br>预计收益：200.00</span><span>2015-09-21</span><span>2015-09-21</span><span>2015-09-21</span><span>收益中</span></p>
                    </li>
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
    <?php $this->load->view('common/footer'); ?>
    <!--底部-->       

<!--userjs start-->
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($('.tra_note'));
    });
    $('.date_picker_1').date_input();
    $('.date_picker_2').date_input();
</script>
</body> 
</html>