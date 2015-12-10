<!DOCTYPE html>
<html>
<head lang="en">
  <title>我的红包</title>
    <?php $this->load->view('common/mobiles/m_app_head') ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/app/m-popularize.css">
</head>
<body>
<!-- 公共头部导航-->
    <?php $this->load->view('common/mobiles/app_common_head') ?>
    <div class="placehold"></div>
    <div class="con_wap">
       <div class="popularize_center_tab">
            <div class="tab-ctorl tcnav1">
                <div class="tcnav tcnav1 fl tc">当前</div>
                <div class="tcnav tcnav2 fr tc">历史</div>
            </div>
            <div class="tab-con tab-con1"   id="redbag_a">
				<?php foreach($receive as $k => $v):?>
                <div class="redbag">
                    <div class="redbag_bg fl">￥<?php echo $v['amount'];?></div>
                    <div class="redbag_nr fr">
                        <div class="redbag_nr_con">
                            <div class="fl">
                                <p class="col_red"><?php echo $v['active'];?></p>
                                <p class="col_gre"><?php echo $v['source'];?></p>
                                <p class="col_time">有效期：<?php if($v['deadline']=='') echo '永久';else echo $v['deadline'];?></p>
                            </div>
                            <div class="fr">
                                <p class="requ_lq" id="<?php echo $v['id'];?>" name="<?php echo $v['amount'];?>"  style="cursor:pointer">领取</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
                <!--
                <div class="no_bag">
                    <p>亲，您暂时还没有红包哦~</p>
                    <p style="color:#da251c;">您可以通过邀请好友注册或投资来获得红包~</p>
                </div>
                -->
            </div>
            <div class="tab-con tab-con2" id="redbag_b">
               <?php foreach($receive_log as $k => $v):?>
                <div class="redbag"  id="redbag_c">
                    <div class="redbag_bg fl">￥<?php echo $v['amount'];?></div>
                    <div class="redbag_nr fr">
                        <div class="redbag_nr_con">
                            <div class="fl">
                                <p class="col_red"><?php echo $v['active'];?></p>
                                <p class="col_gre"><?php echo $v['source'];?></p>
                                <p class="col_time">有效期：<?php if($v['deadline']=='') echo '永久';else echo $v['deadline'];?></p>
                            </div>
                            <div class="fr">
                                <p class="noclick">领取</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
                <!--
                <div class="no_bag">
                    <p>亲，您暂时还没有红包哦~</p>
                    <p style="color:#da251c;">您可以通过邀请好友注册或投资来获得红包~</p>
                </div>
                -->
            </div>
       </div>
    </div>
    <div class="tc_red_bag">
		<div class="tc_red_bag_bj"></div>
        <div class="tc_con">
            <div class="hongbao">
                <p class="close"></p></br>
                <p class="rmbnum">20元</p></br>
                <p class="zhanghu">红包已放入您账户</p></br></br></br>
                <p class="butbut">确定</p>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/mobiles/app_alert') ?>
</body>
  <?php $this->load->view('common/mobiles/app_footer') ?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".tcnav.fl").click(function (){
            $(".tab-ctorl").addClass("tcnav1");
            $(".tab-ctorl").removeClass("tcnav2");
            $(".tab-con1").show();
            $(".tab-con2").hide();
        })
        $(".tcnav.fr").click(function (){
            $(".tab-ctorl").addClass("tcnav2");
            $(".tab-ctorl").removeClass("tcnav1");
            $(".tab-con2").show();
            $(".tab-con1").hide();
        })
       $(".requ_lq").click(function () {
			var id= this.id;  
			var amount= $(this).attr("name") 
			var condition='?id='+id+'&amount='+amount;
			$(this).parents(".redbag").detach();
			$.post('/index.php/mobiles/home/redbag_ajax'+condition,{},function(result){
					result = eval(result);
					$("#redbag_c").remove();
					var html="";
					var html1="";
					for(var i=0 ; i < result.length ; i++){
						if(result[i].deadline==null){
							var deadline='永久';
							}else{
							var deadline=result[i].deadline;
							}
						//if(result[i].status==0){					
						//	 html+="<div class='redbag' ><div class='redbag_bg fl'>￥"+result[i].amount+"</div><div class='redbag_nr fr'><div class='redbag_nr_con'><div class='fl'><p class='col_red'>"+result[i].amount+"</p><p class='col_gre'>"+result[i].source+"</p><p class='col_time'>有效期："+deadline+"</p></div><div class='fr'><p class='requ_lq' id='"+result[i].id+"'  style='cursor:pointer'>领取</p></div></div></div></div>";
						//}
						
							 html1+="<div class='redbag' ><div class='redbag_bg fl'>￥"+result[i].amount+"</div><div class='redbag_nr fr'><div class='redbag_nr_con'><div class='fl'><p class='col_red'>"+result[i].active+"</p><p class='col_gre'>"+result[i].source+"</p><p class='col_time'>有效期："+deadline+"</p></div><div class='fr'><p class='noclick' >领取</p></div></div></div></div>";
						
					} 
					//$("#redbag_a").html(html);
					$("#redbag_b").html(html1);			
                });
			$.post('/index.php/mobiles/home/ajax_get_redbagdata'+condition,{},function(result){
					result = eval(result);				
					$(".rmbnum").html(result[0].amount+"元");	
					$(".tc_red_bag").show();  
                });

        })
		$(".tc_red_bag_bj").click(function () {
            $(".tc_red_bag").hide();
        })
        $(".close").click(function () {
            $(".tc_red_bag").hide();
        })
        $(".butbut").click(function () {
            $(".tc_red_bag").hide();
        })
    }); 
</script>
</html>