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
                                        <?php if(get( 'borrow_no')):?>
                                            标的更新
                                            <?php else:?>
                                                发布标的
                                            <?php endif;?>
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
                                            <?php if(get( 'borrow_no')):?>
                                                <?php echo (isset($borrow_no)) ? $borrow_no : '';?>
                                                    <?php else:?>
                                                        发布标的
                                                        <?php endif;?>
                                        </h4>
                                        <div class="options">
                                        </div>
                                    </div>
                                    <form id="myform" action="" method="post" class="form-horizontal row-border"
                                    style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
                                        <div class="panel-body collapse in">
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        项目类别
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <select name="productcategory" id="productcategory" class="form-control">
                                                            <option value="" selected="selected">
                                                                请选择
                                                            </option>

                                                            <?php foreach($data as $k=>
                                                                $v):?>
                                                                <option value="<?php echo $v['cat_id'];?>">
                                                                    <?php echo $v['category'];?>
                                                                </option>
                                                                <?php endforeach;?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        借款类型
                                                    </label>
                                                    <div class="col-sm-6">

                                                        <label class="radio-inline">
                                                            <input type="radio" name="type" value="2" checked="checked">  抵押借款
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio" name="type" value="3" > 担保借款
                                                        </label>
														<label class="radio-inline">
                                                            <input type="radio" name="type" value="1" > 信用借款
                                                        </label>
                                                    </div>
                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        借款标题
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <input type="text" name="subject" value="<?php echo (isset($subject)) ? $subject : set_value('subject');?>"	class="form-control" placeholder="请输入借款标题" />
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        手机号码
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <input type="text" name="mobile" id="mobile" value="<?php echo (isset($user['mobile'])) ? $user['mobile'] : set_value('mobile');?>"	class="form-control" placeholder="请输入用户的手机号(已注册)" />
                                                    </div>
                                                    <div class="col-sm-3 control-label" style="text-align: left;">
                                                        <?php echo (isset($user[ 'user_name'])) ? $user[ 'user_name'] : '';?>
                                                        <?php echo (isset($user[ 'real_name'])) ? ' ['.$user[ 'real_name'].']' : '';?>
                                                    </div>
                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        借款金额
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                ¥
                                                            </span>
                                                            <input type="text" name="amount" value="<?php echo (isset($amount)) ? $amount : set_value('amount');?>"
                                                            class="form-control" placeholder="请输入借款金额" />
                                                            <span class="input-group-addon">
                                                                .00
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        年利率
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
                                                            <input type="text" name="rate" value="<?php echo (isset($rate)) ? $rate : set_value('rate');?>"
                                                            class="form-control" placeholder="请输入借款人年利率(前台显示)" />
                                                            <span class="input-group-addon">
                                                                %
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
											 <div class="form-group" style="width: 100%;">
											      <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        管理费
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
                                                            <input type="text" name="real_rate" value="0"
                                                            class="form-control" placeholder="请输入管理费率" />
                                                            <span class="input-group-addon">
                                                                %
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        最低投资金额
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                ¥
                                                            </span>
                                                            <input type="text" name="lowest" value="<?php echo (isset($lowest)) ? $lowest : set_value('lowest');?>"
                                                            class="form-control" placeholder="请输入最低投资金额" />
                                                            <span class="input-group-addon">
                                                                .00
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        最大投资金额
                                                    </label>
                                                    <div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                ¥
                                                            </span>
                                                            <input type="text" name="max" value="<?php echo (isset($max)) ? $max : set_value('max');?>"
                                                            class="form-control" placeholder="请输入最大投资金额" />
                                                            <span class="input-group-addon">
                                                                .00
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
                                            <div class="form-group" style="width: 100%;">
                                                <div class="form-mode" style="width: 49%;float:left;">
                                                    <label class="col-sm-3 control-label" style="text-align: left;">
                                                        还款方式
                                                    </label>
                                                    <div style="width: 100%;">
                                                        <label class="radio-inline">
															<input type="radio" name="mode" value="3" <?php echo (isset($mode))
															? checked(3, $mode) : checked(3, 3);?>
															>一次性还本付息
                                                        </label>
														<label class="radio-inline">
															<input type="radio" name="mode" value="1" <?php echo (isset($mode))
															? checked(1, $mode) : '';?>
															> 先息后本
														</label>	
														<label class="radio-inline">
															<input type="radio" name="mode" value="4" <?php echo (isset($mode))
															? checked(4, $mode) : '';?>
															> 等额本金
														</label>
														<label class="radio-inline">
															<input type="radio" name="mode" value="2" <?php echo (isset($mode))
															? checked(2, $mode) : '';?>
															> 等额本息
														</label>
                                                    </div>
                                                </div>
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														借款期限
													</label>
													<div class="col-sm-3" style="width: 400px;">
                                                        <div class="input-group">
															<input type="text" name="months" id="months" value="" class="form-control" placeholder="请输入借款期限，单位为月" />	
                                                            <span class="input-group-addon">
                                                                月
                                                            </span>
                                                        </div>
                                                    </div>
												</div>
                                                <div style="clear:both;">
                                                </div>
                                            </div>
											
                                            <div style="width: 100%;">
                                                <div class="form-repay" style="width: 48%;    float: left;    border-top: 1px solid #E6E7E8;    padding: 20px 10px;    margin-bottom: 0px;    margin-left: -20px;    margin-right: -20px; line-height: 26px;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														利息处理方式
													</label>
													<div class="col-sm-3" style="width: 400px;">
														<label class="radio-inline">
															<input type="radio" name="repay"  id="repay_2" value="2" <?php echo (isset($repay))
															? checked(2, $repay) : checked(2, 2);?>
															> 一次性扣除
														</label>
														<label class="radio-inline" style="display: none;">
															<input type="radio" name="repay"  id="repay_1" value="1" <?php echo (isset($repay))
															? checked(1, $repay) : '';?>
															> 按月扣除
														</label>	
														<label class="radio-inline" style="display: none;">
															<input type="radio" name="repay"  id="repay_3" value="3" <?php echo (isset($repay))
															? checked(3, $repay) : '';?>
															> 按日扣除
														</label>
													</div>
												</div>
												<div class="form-group" style="width: 48%;    float: left;      padding: 20px 10px;    margin-bottom: 0px;    margin-left: -20px;    margin-right: -20px;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														预扣期数
													</label>
													<div class="col-sm-3" style="width: 400px;">
														<div class="input-group">
															<input type="text" name="deduct" id="deduct" value="0" class="form-control" placeholder="请输入预扣期数，单位为月" />	
															<span class="input-group-addon">
																期
															</span>
														</div>
													</div>													
												</div>
                                                <div style="clear:both;">
                                                </div>
											</div>
                                         
                                            <!--<div class="form-group" style="width: 49%;">
                                                <label class="col-sm-3 control-label" style="text-align: left;">
                                                    实收利率
                                                </label>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <input type="text" name="real_rate" value="<?php echo (isset($real_rate)) ? $real_rate : set_value('real_rate');?>"
                                                        class="form-control" placeholder="请输入实收利率(平台收费)" />
                                                        <span class="input-group-addon">
                                                            %
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>-->
											<div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														会员投标次数
													</label>
													<div class="col-sm-3" style="width: 400px;">
														<div class="input-group">
															<input type="text" name="time" id="time" value="3" class="form-control" placeholder="请输入单人最大投标次数" />
																<span class="input-group-addon">
																	次(0 代表不限)
																</span>
														</div>
													</div>
												</div>
                                              												<div style="clear:both;">
                                                </div>
                                            </div>
											<div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														显示日期
													</label>
													<div class="col-sm-3">
														<input type="text" name="show_time" id="show_time" value="<?php echo (isset($show_time)) ? my_date($show_time, 2) : date('Y-m-d');?>"
														class="form-control" placeholder="在网站显示的时间" readonly="true" onClick="WdatePicker()"
														/>
													</div>
												</div>
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														显示日期时间
													</label>
													 <div class="col-sm-3" style="width: 400px;">
                                                        <select name="due_date_type" id="due_date_type" class="form-control">
                                                                <option value="1" selected="selected">
                                                                    10:00
                                                                </option>                        
																<option value="2">
                                                                    11:00
                                                                </option>                          
																<option value="3">
                                                                    12:00
                                                                </option>                           
																<option value="4">
                                                                    13:00
                                                                </option>              
																<option value="5">
                                                                    14:00
                                                                </option>
                                                        </select>
                                                    </div>
												</div>
												<div style="clear:both;">
                                                </div>
                                            </div>
											<div class="form-group" style="width: 100%;">
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														预约购买时间
													</label>
													<div class="col-sm-3">
														<input type="text" name="buy_time" id="buy_time" value="<?php echo (isset($buy_time)) ? my_date($buy_time, 2) : set_value('buy_time');?>"
														class="form-control" placeholder="可购买时间" readonly="true" onClick="WdatePicker()"
														/>
													</div>
												</div>
                                                <div style="width: 49%;float:left;">
													<label class="col-sm-3 control-label" style="text-align: left;">
														投资结束时间
													</label>
													<div class="col-sm-3">
														<input type="text" name="due_date" id="due_date" value="<?php echo (isset($due_date)) ? my_date($due_date, 2) : set_value('due_date');?>"
														class="form-control" placeholder="投资结束时间" readonly="true" onClick="WdatePicker()"
														/>
													</div>
												</div>
												<div style="clear:both;">
                                                </div>
                                            </div>
											
											 <div class="form-group">
                                                <label class="col-sm-3 control-label" style="width:11.5%;">
                                                    用户图片上传
                                                </label>
                                                <div class="col-sm-6">
												  <textarea name="image" id="image" class="form-control ckeditor" cols="100" rows="10" style="width:100%; height:350px;"></textarea>
												</div>
											  </div>

											<div class="form-group" >
                                                <label class="col-sm-3 control-label" style="width:11.5%;">
                                                    用户信息(*必填)
                                                </label>
                                                <div class="col-sm-6">
                                                    <textarea name="content" id="content" class="form-control" cols="100"
                                                    rows="5" style="width:100%; height:250px;" placeholder="借款描述"><?php echo (isset($content)) ? $content : set_value( 'content');?>
                                                    </textarea>
                                                </div>
                                            </div>
											
                                            <div class="form-group" >
                                                <label class="col-sm-3 control-label" style="width:11.5%;">
                                                    资金用途
                                                </label>
                                                <div class="col-sm-6">
                                                    <textarea name="summary" id="summary" class="form-control" cols="100"
                                                    rows="3" style="width:100%; height:250px;" placeholder="请输入借款用途"><?php echo (isset($summary)) ? $summary : set_value( 'summary');?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            <div class="form-group" >
                                                <label class="col-sm-3 control-label" style="width:11.5%;">
                                                    风控信息
                                                </label>
                                                <div class="col-sm-6">
                                                    <textarea name="repayment" id="repayment" class="form-control" cols="100"
                                                    rows="3" style="width:100%; height:250px;" placeholder="请输入还款资金来源"><?php echo (isset($repayment)) ? $repayment : set_value( 'repayment');?>
                                                    </textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="row">
                                                    <div class="col-sm-6 col-sm-offset-3">
                                                        <div class="btn-toolbar">
                                                            <input type="submit" value="确认提交" class="btn btn-primary">
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
                    <?php $this->
                        load->view('common/copyright');?>
            </div>
            <?php $this->
                load->view('common/footer');?>
                <script type="text/javascript" src="/admin/assets/plugins/datepicker/WdatePicker.js">
                </script>
                <script type='text/javascript' src='/admin/assets/js/jquery-migrate-1.2.1.js'>
                </script>
                <script type='text/javascript' src='/admin/assets/plugins/autocomplete/jquery.autocomplete.min.js'>
                </script>
                <link rel="stylesheet" type="text/css" href="/admin/assets/plugins/autocomplete/jquery.autocomplete.css"
                />
				<script type="text/javascript" src="/admin/assets/plugins/kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="/admin/assets/plugins/kindeditor/lang/zh_CN.js"></script>

                <script>
                    $(function() {
						
						var objrepay1 = $('#repay_1').parents('.radio-inline');
						var objrepay2 = $('#repay_2').parents('.radio-inline');
						var objrepay3 = $('#repay_3').parents('.radio-inline');
                        
						$(':radio[name="mode"]').click(function() {
                            var val = $(this).val();							
                            if (val == 3) {
                                obj.hide();
								$('#repay_2').attr("checked","checked");
								document.getElementById("months").value="";
								objrepay1.hide();
								objrepay2.show();
								objrepay3.hide();
                            } 
							else if (val == 1) {                               
								obj.show();
								$('#repay_1').attr("checked","checked");
								document.getElementById("months").value="";
								objrepay1.show();
								objrepay2.hide();
								objrepay3.show();								
							}
							else{
								obj.show();
								$('#repay_1').attr("checked","checked");
								document.getElementById("months").value="";
								objrepay1.show();
								objrepay2.hide();
								objrepay3.hide();
                            }
                        });						
						
						
						
						
						
						
						
                        var obj = $('#deduct').parents('.form-group');

                        $(':radio[name="repay"]').click(function() {
                            var val = $(this).val();
                            if (val == 1||val == 3) {
                                obj.show();
								document.getElementById("deduct").value="";
                            } else {
                                obj.hide();
                            }
                        });

                        var repay = $(':checked[name="repay"]').val();

                        if (repay == 1) {
                            obj.show();
                        } else {
                            obj.hide();
                        }

                        $("#mobile").autocomplete('/index.php/borrow/home/mobile', {
                            minChars: 1,
                            width: 310,
                            matchContains: true,
                            autoFill: false,
                            dataType: 'json',
                            parse: function(data) {
                                var rows = [];
                                for (var i = 0; i < data.length; i++) {
                                    rows[i] = {
                                        data: data[i],
                                        result: data[i].mobile
                                    };
                                }
                                return rows;
                            },
                            formatItem: function(data) {
                                if (data.real_name != '') {
                                    return data.mobile + '[' + data.user_name + ' - ' + data.real_name + ']';
                                } else {
                                    return data.mobile + '[' + data.user_name + ']';
                                }
                            },
                            formatMatch: function(data) {
                                return data.mobile;
                            },
                            formatResult: function(data) {
                                return data.mobile;
                            }
                        });
                    })
                </script><script type="text/javascript" src="/admin/assets/plugins/kindeditor/lang/zh_CN.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
  editor = K.create('textarea[name="image"]', {
   resizeType : 1,
   allowFileManager : true
  });
});
</script>

    </body>

</html>