<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>
            网加金服后台管理系统
        </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php $this->
            load->view('common/header');?>
    </head>
    
    <body class="">
        <?php $this->
            load->view('common/topbar');?>
            <div id="page-container">
                <?php $this->
                    load->view('common/sidebar');?>
                    <div id="page-content">
                        <div id='wrap'>
                            <div id="page-heading">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="<?php echo site_url();?>" title="返回首页">
                                            首页
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('borrow');?>" title="借款管理">
                                            借款管理
                                        </a>
                                    </li>
                                    <li class="active">
                                            优化配置
                                    </li>
                                </ol>
                                <h1>
                                </h1>
                                <div class="options">
                                    <a href="<?php echo site_url('borrow');?>" class="btn btn-default">
                                        <i class="fa fa-reply-all">
                                        </i>
                                        返回列表
                                    </a>
                                </div>
                            </div>
                            <div class="container">
                                <div class="panel panel-midnightblue">
                                    <div class="panel-heading">
                                        <h4>
												优化配置-<span id="borrow_no"><?php echo $borrow_no;?></span>
                                               <!-- <?php echo (isset($borrow_no)) ? $borrow_no : '';?>-->
                                                
                                        </h4>
                                        <div class="options">
                                        </div>
                                    </div>
                                    <form id="myform" action="" method="post" class="form-horizontal row-border"
                                    style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
									
                                        <div class="panel-body collapse in">
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
											
                                                 项目名称：<span id="xmmc"><?php echo $data['subject']?></span>	
                                                </div>
                                                
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
                                                    <div style="float:left">
                                                        融资金融：<span name="rzje" id="rzje"><?php echo $data['amount']?></span>	
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
                                                    <div style="float:left">
                                                        闲置配资金额：<span  id="zzpzje">
														<?php if($allbalance['balance_ye_all']!=''):echo $allbalance['balance_ye_all'];else: echo '0';endif;?></span>	
                                                    </div>

                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
										
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 100%;float:left;">
                                                    <div style="float:left">
                                                        自动投配额比例： <input type="text" width="200px" value="80"  id="pbed" onchange="ed()">
                                                        </div>
														%<span name="pzje" id="pzje"><?php echo floor($data['amount']*0.8/100)*100?></span>
                                                    </div>
														
                                                </div>
                                               <div class="form-group" style="width: 100%;">
													<label class="radio-inline">
                                                            <input type="checkbox" id="yh" value="1" checked onclick="sub()">投标优化（开启可避免一个用户秒表的情况）
                                                        </label>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
									   
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <div class="btn-toolbar">
                                                            <a href="<?php echo site_url('borrow/home/verify_form?borrow_no='.get('borrow_no'));?>" title="自动投标"  class="btn btn-primary" id='sub'>自动投标</a>
															<a href="<?php echo site_url('borrow/home/verify?borrow_no='.get('borrow_no'));?>" title="正常发布"  class="btn btn-primary">正常发布</a>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view('common/copyright');?>
            </div>
            <?php $this->
                load->view('common/footer');?>
<script>
function ed(){
	var pbed = document.getElementById("pbed").value;
	var rzje = document.getElementById("rzje").innerHTML;
	if(pbed>=0&&pbed<=100){
		document.getElementById("pzje").innerHTML=parseInt(rzje*pbed/10000)*100;
		sub();
	}else{
		alert("自动配额比例在0-100之间");
	}
	
}
//提交
function sub(){
	var zzpzje = document.getElementById("zzpzje").innerHTML;
	var pzje = document.getElementById("pzje").innerHTML;
	var rzje = document.getElementById("rzje").innerHTML;
	var href='';
	if(document.getElementById("yh").checked){
		href=document.getElementById("sub").href;
		href=href.substring(0,href.indexOf("&"));
		document.getElementById("sub").href=href+'&yh=1&pzje='+pzje+'&zzpzje='+zzpzje+'&rzje='+rzje;
	}else{
		href=document.getElementById("sub").href;
		href=href.substring(0,href.indexOf("&"));
		document.getElementById("sub").href=href+'&yh=0&pzje='+pzje+'&zzpzje='+zzpzje+'&rzje='+rzje;
	}
	
}

window.onload=function(){
	var zzpzje = document.getElementById("zzpzje").innerHTML;
	var pzje = document.getElementById("pzje").innerHTML;
	var rzje = document.getElementById("rzje").innerHTML;
	var href='';
	href=document.getElementById("sub").href;
	if(document.getElementById("yh").checked){
		document.getElementById("sub").href=href+'&yh=1&pzje='+pzje+'&zzpzje='+zzpzje+'&rzje='+rzje;
	}else{		

		href1=href.substring(0,href.indexOf("&"));
		if(href1==''){
		document.getElementById("sub").href=href+'&yh=0&pzje='+pzje+'&zzpzje='+zzpzje+'&rzje='+rzje;
		}else{
		document.getElementById("sub").href=href1+'&yh=0&pzje='+pzje+'&zzpzje='+zzpzje+'&rzje='+rzje;
		}
	}
}
</script>

              


    </body>

</html>