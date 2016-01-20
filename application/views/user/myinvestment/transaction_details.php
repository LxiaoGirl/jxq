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
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/jbb">我的投资</a>&nbsp;>&nbsp;<a href="javascript:void(0);">交易明细</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            
            <h1>交易明细</h1>
            <div style="border-top:none" class="pre_mon_tra_det tra_note">
                <ul class="tab_title tab_title_bule">
                    <font>查询范围：</font>
                    <li <?php if($type == 'd'): ?>class="active"<?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=d'); ?>'">今天</li>
                    <li <?php if($type == 'w'): ?>class="active"<?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=w'); ?>'">一周</li>
                    <li <?php if($type == 'm'): ?>class="active"<?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=m'); ?>'">一个月</li>
                    <li <?php if($type == '3m'): ?>class="active"<?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=3m'); ?>'">三个月</li>
                    <li <?php if($type == '6m'): ?>class="active"<?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=6m'); ?>'">半年</li>
                    <li <?php if($type == 'auto'): ?>class="active"<?php endif; ?>
                        onclick="window.location.href='<?php echo site_url('user/user/transaction_details?type=auto&year='); ?>'+document.getElementById('year').value+'&month='+document.getElementById('month').value">自定义</li>
                    <select name="year" id="year">
                        <?php if(date('m') < '6'): ?>
                        <option value="<?php echo date('Y')-1; ?>" <?php if($year == date('Y')-1): ?>selected<?php endif; ?>><?php echo date('Y')-1; ?>年</option>
                        <?php endif; ?>
                        <option value="<?php echo date('Y'); ?>" <?php if($year == date('Y') || !$year): ?>selected<?php endif; ?>><?php echo date('Y'); ?>年</option>
                    </select>
                    <select name="moth" id="month"></select>
                </ul>
                <ul class="tab_con tab_title_bule">
                    <li class="active">
                        
                        <div class="flr">
                            <div class="fl">当前范围总计流入：<font><?php echo price_format($cash_total['income_total'],2,false); ?></font>元</div><div class="fr">当前范围总计流出：<font><?php echo price_format($cash_total['pay_total'],2,false); ?></font>元</div>
                        </div>
                        <!--下面表格具体内容不确定-->
                        <p class="title"><span>交易日期</span><span class="wid20">流水号</span><span>源编号</span><span>交易描述</span><span>交易类型</span><span class="wid20">金额明细（元）</span><span>余额（元）</span></p>
                        <?php if($log):foreach($log as $k=>$v): ?>
                            <p class="lie">
                                <span><?php echo date('Y-m-d',$v['dateline']); ?></span>
                                <span style="width:20%"><?php echo $v['id']; ?></span>
                                <span><?php echo $v['source']; ?></span>
                                <span><?php echo $v['remarks']; ?></span>
                                <span><?php echo $v['type']; ?></span>
                                <span style="width:20%"><?php echo $v['amount']; ?></span>
                                <span><?php echo $v['balance']; ?></span>
                            </p>
                        <?php endforeach;else: ?>
                            <p class="lie">暂无相关信息</p>
                        <?php endif; ?>
                        <?php echo $links; ?>
                    </li>
                </ul>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--底部-->
	<?php $this->load->view('common/footer'); ?>
    <!--底部-->                   
</body>
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($('.pre_mon_tra_det'));
        var m = parseInt('<?php echo date('m'); ?>');
        var y = parseInt('<?php echo date('Y'); ?>');
        var y1 = parseInt('<?php echo $year; ?>');
        var m0 = parseInt('<?php echo $month; ?>');
        var m1 = '',m2 = '';
        if(m < 6){
            for(var i=7+m;i<=12;i++){
                var str = '';
                if(y1 == y-1 && i == m0){
                    str = '<option value="'+i+'" selected>'+i+'</option>';
                }else{
                    str= '<option value="'+i+'">'+i+'</option>';
                }
                m1 += str;
            }
        }
        for(var j = (m<6?1:m-5);j<=m;j++){
            var str1 = '';
            if(y1 == y && j == m0){
                str1 = '<option value="'+j+'" selected>'+j+'</option>';
            }else{
                str1= '<option value="'+j+'">'+j+'</option>';
            }
            m2 += str1;
        }
        $("#month").html(!y1||y==y1?m2:m1);
        if($("#month").children().length == 1){
            $('#month').unbind('click').bind('click',function(){
                window.location.href='/index.php/user/user/transaction_details?type=auto&year='+$('#year').val()+'&month='+$('#month').val();
            });
        }
        $('#year').bind('change',function(){
            if($(this).val() == y){
                $("#month").html(m2);
            }else{
                $("#month").html(m1);
            }
            $('#month').unbind('click');
            if($("#month").children().length == 1){
                $('#month').bind('click',function(){
                    window.location.href='/index.php/user/user/transaction_details?type=auto&year='+$('#year').val()+'&month='+$('#month').val();
                });
            }
        });
        $('#month').bind('change',function(){
           window.location.href='/index.php/user/user/transaction_details?type=auto&year='+$('#year').val()+'&month='+$(this).val();
        });
    });
</script>
</html>