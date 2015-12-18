<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
    <style>
        .tra_note .tab_con p span.wid20 {
            width: 16%;
        }
    </style>
</head>
<body>
<!--head start-->
  <?php $this->load->view('common/head');?>
    <!--head end-->
    <!--user start-->
    <div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/jbb">我的投资</a>&nbsp;>&nbsp;<a href="javascript:void(0);">投资记录</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--右侧-->
        <div class="user_right">
            <h1>投资记录</h1>
            <div class="tra_note">
                <ul class="tab_title tab_title_small">
                    <li <?php if($type == ''): ?> class="active" <?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_note?start_time='.$start_time.'&end_time='.$end_time); ?>'">全部<font class="fr">|</font></li>
                    <li <?php if($type == '4'): ?> class="active" <?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_note?type=4&start_time='.$start_time.'&end_time='.$end_time); ?>'">还款中<font class="fr">|</font></li>
                    <li <?php if($type == '7'): ?> class="active" <?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_note?type=7&start_time='.$start_time.'&end_time='.$end_time); ?>'">回款完成<font class="fr">|</font></li>
                    <li <?php if($type == '2'): ?> class="active" <?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_note?type=2&start_time='.$start_time.'&end_time='.$end_time); ?>'">热售中<font class="fr">|</font></li>
                    <li <?php if($type == '3'): ?> class="active" <?php endif; ?> onclick="window.location.href='<?php echo site_url('user/user/transaction_note?type=3&start_time='.$start_time.'&end_time='.$end_time); ?>'">复审中</li>
                        <div class="section">                            
                            <font style="margin-left:30px;">选择日期：</font><input type="text" id="start-time" class="date_picker_1 ifhav" value="<?php echo $start_time?date('Y-m-d',$start_time):date('Y-m-d',strtotime('-60 day')); ?>">
                            <font>至&nbsp;&nbsp;</font><input type="text" id="end-time" class="date_picker_2 ifhav" value="<?php echo $end_time?date('Y-m-d',$end_time):''; ?>">
                            <button class="ls" onclick="window.location.href='<?php echo site_url('user/user/transaction_note?type='.$type); ?>'+'&start_time='+document.getElementById('start-time').value+'&end_time='+document.getElementById('end-time').value">查询</button>
                        </div>
                </ul>
                <ul class="tab_con">
                    <li class="active">
                        <p class="title"><span class="wid20">投资项目</span><span>年收益率</span><span class="wid20">投资金额（元）</span><span>投资时间</span><span>计息日</span><span>还款日</span><span>状态</span><span style="width: 60px;">投资协议</span></p>
                        <?php if($project):foreach($project as $k=>$v): ?>
                            <p class="lie">
                                <span class="wid20"><font><?php echo $v['subject']; ?></font></br>编号:<?php echo $v['borrow_no']; ?></span>
                                <span><?php echo $v['rate']; ?>%</span>
                                <span class="wid20"><font><?php echo $v['amount']; ?></font></br><?php if($v['status'] == '还款完成'): ?> 已<?php else: ?>预计<?php endif; ?>收益：<?php echo $v['interest']; ?></span>
                                <span><?php echo date('Y-m-d',$v['invest_time']); ?></span>
                                <span><?php echo date('Y-m-d',$v['interest_start_time']); ?></span>
								<span><?php echo date('Y-m-d',strtotime($v['interest_lately_time'])); ?></span>
								<span><?php echo $v['status']; ?></span>
                                <span style="width: 60px;text-decoration: underline;"><a href="<?php echo site_url('terms/index?borrow_no='.$v['borrow_no']); ?>" target="_blank">查看</a> </span>
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

<!--userjs start-->
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/plugins/jquery.date_input.pack.js')?>"></script> 
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        //INPUT框变色
        $('.ifhav').focus(function(){
            $(this).addClass('hav');
        });
        $('.ifhav').blur(function(){
            if($.trim($(this).val())==''){
                $(this).removeClass('hav');
            }
        });
        tab($('.tra_note'));
    });
    $('.date_picker_1').date_input();
    $('.date_picker_2').date_input();
</script>
</body> 
</html>