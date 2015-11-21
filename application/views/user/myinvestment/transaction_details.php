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
        <a href="">首页</a>&nbsp;>&nbsp;<a href="">我的投资</a>&nbsp;>&nbsp;<a href="">交易明细</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            
            <h1>交易明细<a href="">为什么只能查最近半年的记录？</a></h1>
            <div style="border-top:none" class="pre_mon_tra_det tra_note">
                <ul class="tab_title tab_title_bule">
                    <font>查询范围：</font>
                    <li class="active">今天</li>
                    <li>一周</li>
                    <li>一个月</li>
                    <li>三个月</li>
                    <li>半年</li>
                    <li>自定义</li>
                    <select name="year">
                        <option value="2012">2012年</option>
                        <option value="2013">2013年</option>
                        <option value="2014">2014年</option>
                        <option value="2015">2015年</option>
                    </select>
                    <select name="moth">
                        <option value="1">1月</option>
                        <option value="2">2月</option>
                        <option value="3">3月</option>
                        <option value="4">4月</option>
                        <option value="5">5月</option>
                        <option value="6">6月</option>
                        <option value="7">7月</option>
                        <option value="8">8月</option>
                        <option value="9">9月</option>
                        <option value="10">10月</option>
                        <option value="11">11月</option>
                        <option value="12">12月</option>
                    </select>
                </ul>
                <ul class="tab_con tab_title_bule">
                    <li class="active">
                        
                        <div class="flr">
                            <div class="fl">当前范围总计流入：<font>0.00</font>元</div><div class="fr">当前范围总计流出：<font>0.00</font>元</div>
                        </div>
                        <!--下面表格具体内容不确定-->
                        <p class="title"><span>交易日期</span><span class="wid20">流水号</span><span>借款编号</span><span>交易描述</span><span>交易类型</span><span class="wid20">金额明细（元）</span><span>金额（元）</span></p>
                        <p class="lie"><span>2015-09-21</span><span style="width:20%">464989761316</span><span>B15091758028772</span><span>礼金收益</span><span>礼金收益</span><span style="width:20%">200.00</span><span>50</span></p>
                        <p class="lie"><span>2015-09-21</span><span style="width:20%">464989761316</span><span>B15091758028772</span><span>礼金收益</span><span>礼金收益</span><span style="width:20%">200.00</span><span>50</span></p>
                        <p class="lie"><span>2015-09-21</span><span style="width:20%">464989761316</span><span>B15091758028772</span><span>礼金收益</span><span>礼金收益</span><span style="width:20%">200.00</span><span>50</span></p>
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
	<?php $this->load->view('common/footer'); ?>
    <!--底部-->                   
</body>
<script src="<?php echo base_url('assets/js/jquery/jquery-1.8.5.min.js')?>"></script>
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        tab($('.pre_mon_tra_det'));
    });
</script>
</html>