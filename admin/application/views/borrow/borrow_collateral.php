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
<style>.yc{display: none;}</style>
<?php $this->load->view('common/topbar');?>
<div id="page-container">
  <?php $this->load->view('common/sidebar');?>
  <div id="page-content">
    <div id='wrap'>
      <div id="page-heading">
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url();?>" title="返回首页">首页</a></li>
          <li><a href="<?php echo site_url('borrow');?>" title="借款管理">借款管理</a></li>
          <li class="active">抵押物</li>
        </ol>
        <h1></h1>
        <div class="options"><a href="<?php echo site_url('borrow/home?borrow_no='.$borrow_no);?>" class="btn btn-default"><i class="fa fa-reply-all"></i>返回列表</a></div>
      </div>
      <div></div>
      <div class="container">
        <div class="panel panel-midnightblue">
          <div class="panel-heading">
            <h4><?php echo (isset($borrow_no)) ? $borrow_no : '';?></h4>
            <div class="options"></div>
          </div>
          <form id="myform" action="" method="post" class="form-horizontal row-border" style="border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px;">
            <div class="panel-body collapse in">
            <?php if( ! empty($collateral[1])):?>
            <?php if( ! empty($collateral[1])):?>
            <?php foreach($collateral[1] as $k => $v):?>
              <div class="form-group">
                <input type="button" class="btn-jia"  value="+">
                <input type="button"  class="btn-jian" value="-">
                <label class="col-sm-3 control-label">选项名称</label>
                <div class="col-sm-3">
                  <input type="text" name="base[key][]" value="<?php echo $k;?>" class="form-control" placeholder="示例：房屋面积"/>
                </div>
                <div class="col-sm-5">
                  <input type="text" name="base[value][]" value="<?php echo $v;?>" class="form-control" placeholder="90平方">
                </div>
              </div>
            <?php endforeach;?>
            <?php endif;?>
            <?php if( ! empty($collateral[2])):?>
            <?php foreach($collateral[2] as $k => $v):?>
              <div class="form-group">
                <input type="button" class="btn-jia"  value="+">
                <input type="button"  class="btn-jian" value="-">
                <label class="col-sm-3 control-label">参考价格</label>
                <div class="col-sm-3">
                    <select name="price[key][]" id="links" class="form-control">
                      <option value="">请选择</option>
                      <option value="fang" <?php echo selected($k, 'fang');?>>搜房网</option>
                      <option value="58" <?php echo selected($k, '58');?>>58同城</option>
                      <option value="car" <?php echo selected($k, 'car');?>>汽车之家</option>
                    </select>
                </div>
                <div class="col-sm-5">
                  <input type="text" name="price[value][]" value="<?php echo $v;?>" class="form-control" placeholder="链接地址">
                </div>
              </div>
            <?php endforeach;?>
            <?php endif;?>
            <?php else:?>
              <div class="form-group" >
                <div class="form-row" style=" height:50px;">
                <input type="button" class="btn-jia"  value="+">
                <input type="button"  class="btn-jian yc" value="-">
                  <label class="col-sm-3 control-label">选项名称</label>
                  <div class="col-sm-3">
                    <input type="text" name="base[key][]" value="" class="form-control" placeholder="示例：房屋面积"/>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="base[value][]" value="" class="form-control" placeholder="90平方">
                  </div>
               </div>
              </div>
              <div class="form-group" style="padding-bottom:0;">
                <div class="form-row" style=" height:50px;">
                <input type="button" class="btn-jia"  value="+">
                <input type="button"  class="btn-jian2" value="-">
                  <label class="col-sm-3 control-label">参考价格</label>
                  <div class="col-sm-3">
                      <select name="price[key][]" id="links" class="form-control">
                        <option value="">请选择</option>
                        <option value="fang">搜房网</option>
                        <option value="58">58同城</option>
                        <option value="car">汽车之家</option>
                      </select>
                  </div>
                  <div class="col-sm-5">
                    <input type="text" name="price[value][]" value="" class="form-control" placeholder="链接地址">
                  </div>
                </div>
              </div>
              <?php endif;?>
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
  <?php $this->load->view('common/copyright');?>
</div>
<?php $this->load->view('common/footer');?>
 <script type="text/javascript">
$(function () {
  $("#myform").on("click", "input", function () {
    if ($(this).hasClass("btn-jia")) {
        var d1 = $(this).parent();
        d1.after(d1.clone(true));
        var d2 = d1.next();
        d2.find("input[type='text']").val("");
        d2.find("select").val("");
        d2.find("input.btn-jian").removeClass("yc");

    }
    if ($(this).hasClass("btn-jian")) {
        if ($(this).parent().siblings().length >= 1) {
            $(this).parent().remove();
        }
    }

    if ($(this).hasClass("btn-jian2")) {
            $(this).parent().remove();
        }
    });
});
</script>
</body>
</html>