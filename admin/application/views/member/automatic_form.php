<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>网加金服后台管理系统</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php $this->load->view('common/header');?>
</head>
<body class="">
<?php $this->load->view('common/topbar');?>
<div id="page-container">
  <?php $this->load->view('common/sidebar');?>
  <div id="page-content">
    <div id='wrap'>
      <div id="page-heading">
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url();?>" title="返回首页">首页</a></li>
          <li><a href="<?php echo site_url('member');?>" title="会员管理">会员管理</a></li>
          <li class="active">自动投标</li>
        </ol>
        <h1></h1>
        <div class="options">
        <a href="<?php echo site_url('member');?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a>
        </div>
      </div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo  (isset($user_name)) ? $user_name : '';?><?php echo ( ! empty($real_name)) ? '['.$real_name.']' : '';?></h4>
          </div>
        <form action="" class="form-horizontal row-border" method="post" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
          <div class="panel-body collapse in">
  
              <div class="form-group">
                <label class="col-sm-3 control-label">累计投资额</label>
                <div class="col-sm-6">
                  <h5  name="allinvest" id="allinvest"><?php echo isset($allinvest)?$allinvest:'0';?></h5>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">项目类别</label>
                <div class="col-sm-6">
                  <select name="group_id" id="group_id" class="form-control" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>
                    <option value="0">全部</option>
                    <?php foreach($product_category_list as $v):?>
                    <option value="<?php echo $v['cat_id'];?>" <?php echo ($all['type']==$v['cat_id']) ? 'selected' : '';?>><?php echo $v['category'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-3 control-label">可用余额</label>
                <div class="col-sm-6">
                  <h5 name="balance" id="balance"><?php echo $balance;?></h5>
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-3 control-label">收益范围</label>
                <div class="col-sm-6">
                  <input type="text" name="sy_min" value="<?php echo (isset($all['sy_min'])) ? $all['sy_min'] : 0;?>" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>%至<input type="text" name="sy_max" value="<?php echo (isset($all['sy_max'])) ? $all['sy_max'] : 100;?>" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>%（百分比）
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-3 control-label">投标期限配置</label>
                <div class="col-sm-6">
                  <input type="text" name="jk_min" value="<?php echo (isset($all['jk_min'])) ? $all['jk_min'] : '0';?>" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>月至<input type="text" name="jk_max" value="<?php echo (isset($all['jk_max'])) ? $all['jk_max'] : '0';?>" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>月
                </div>
              </div>
			  <div class="form-group">
                <label class="col-sm-3 control-label">配置有效期限</label>
                <div class="col-sm-6">
                  <input type="text" name="pzsj_start" id="pzsj_start"  value="<?php echo (isset($all['pzsj_start'])) ? my_date($all['pzsj_start'], 2) : date('Y-m-d');?>"  placeholder="在网站显示的时间" onClick="WdatePicker()"  onchange="jk_min_change()" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>/>至<input type="text" name="pzsj_end" id="pzsj_end" value="<?php echo (isset($all['pzsj_end'])) ? my_date($all['pzsj_end'], 2) : date('Y-m-d');?>" placeholder="在网站显示的时间"  onClick="WdatePicker()" onchange="jk_max_change()" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>/>
                </div>
              </div>
             <div class="form-group">
                <label class="col-sm-3 control-label">配置金额</label>
                <div class="col-sm-6">
                  <input type="text" name="pzje" id="pzje" value="<?php echo (isset($all['pzed']))? $all['pzed']: '80';?>" onchange="sjje_do()" <?php echo ($all['statue']==1) ? 'readonly="true"' : '';?>>%<?php if($all['statue']!=1):?><span id="sjje" name="sjje"></span><?php else:?><span><?php echo $all['balance_ye'];?></span><?php endif;?>
                </div>
              </div>
              
     
            <div class="panel-footer">
              <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                  <div class="btn-toolbar">
                    <input type="hidden" name="uid" value="<?php echo (isset($uid)) ? $uid : '';?>">
					<input type="hidden" name="statue" value="<?php echo ($all['statue']==1) ? '1' : '0';?>">
                    <input type="submit" value="开启自动投" class="btn btn-primary" <?php echo ($all['statue']==1) ? 'disabled="disabled"' : '';?>>
					<input type="submit" value="关闭自动投" class="btn btn-primary" <?php echo ($all['statue']==1) ? '' : 'disabled="disabled"';?>>
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
<?php $this->load->view('common/footer');?>
<script type="text/javascript" src="/admin/assets/plugins/datepicker/WdatePicker.js"></script>
<script type='text/javascript' src='/admin/assets/js/jquery-migrate-1.2.1.js'></script>
<script type='text/javascript' src='/admin/assets/plugins/autocomplete/jquery.autocomplete.min.js'></script>
<link rel="stylesheet" type="text/css" href="/admin/assets/plugins/autocomplete/jquery.autocomplete.css" />
<script>
window.onload=function(){
		$("#balance").html();
		var sjje=Math.floor($("#balance").html()*$("#pzje").val()/10000)*100;
		$("#sjje").html(sjje);

}
function sjje_do(){
		$("#balance").html();
		var sjje=Math.floor($("#balance").html()*$("#pzje").val()/10000)*100;
		$("#sjje").html(sjje);
}
function jk_max_change(){
		var pzsj_start=$("#pzsj_start").val();
		var pzsj_end=$("#pzsj_end").val();
		if(pzsj_end<pzsj_start){
			alert('开始日期必须小于结束日期');
			$("#pzsj_end").val(pzsj_start);
		}
}
function jk_min_change(){
		var pzsj_start=$("#pzsj_start").val();
		var pzsj_end=$("#pzsj_end").val();
		if(pzsj_end<pzsj_start){
			alert('开始日期必须小于结束日期');
			$("#pzsj_start").val(pzsj_end);
		}
}
</script>
</body>
</html>