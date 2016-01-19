<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
    <style type="text/css">
        .recharge-button{
            color: #3cb5ec;
            font-size: 12px;
            font-family: '宋体';
            background: none;
            padding: 0 10px;
            vertical-align: middle;
        }
        .recharge-button:hover{
            color: #ff7700;
        }
    </style>
</head>
<body>
<!--	加载头部文件-->
<?php $this->load->view('common/head'); ?>
    <div id="step-6" class="row gs_zc4 step">
    <h1>企业用户注册</h1>
    <img src="../../../../assets/images/passport/step_5.jpg" alt="">
    <h2>
        <?php if(profile('clientkind') == '-4'): ?>您的申请正在审核中！<?php endif; ?>
        <?php if(profile('clientkind') == '-5'): ?>您的申请审核未通过！<?php endif; ?>
        <?php if(profile('clientkind') == '2'): ?>恭喜你,你的企业账户申请已通过审核！<?php endif; ?>
    </h2>
    <p class="ti411">企业名称：<font class="company-name-show"><?php echo isset($info['company_name'])?$info['company_name']:''; ?></font></p>
    <p class="ti411">营业执照号码：<font class="company-code-show"><?php echo isset($info['company_code'])?$info['company_code']:''; ?></font></p>
    <p class="ti411">企业开户银行：<font class="company-bank-name-show"><?php echo isset($info['company_bank_name'])?$info['company_bank_name']:''; ?></font></p>
    <p class="ti411">企业银行账号：<font class="company-bank-account-show"><?php echo isset($info['company_bank_account'])?$info['company_bank_account']:''; ?></font></p>
    <p class="ti411">联系人姓名：<font><?php echo profile('real_name')?profile('real_name'):''; ?></font></p>
    <p class="ti411">联系人身份证：<font><?php echo profile('nric')?profile('nric'):''; ?></font></p>
    <p class="ti411">上传的证件：
        <font>
        <a href="<?php echo $this->c->get_oss_image($info['business_license']); ?>" target="_blank"><img class="ap-img" src="<?php echo $this->c->get_oss_image($info['business_license']); ?>" style="width: 100px;height: 100px;"/></a>
        <a href="<?php echo $this->c->get_oss_image($info['account_permit']); ?>" target="_blank"><img class="ap-img" src="<?php echo $this->c->get_oss_image($info['account_permit']); ?>" style="width: 100px;height: 100px;"/></a>
        <a href="<?php echo $this->c->get_oss_image($info['nric_copy']); ?>" target="_blank"><img class="ap-img" src="<?php echo $this->c->get_oss_image($info['nric_copy']); ?>" style="width: 100px;height: 100px;"/></a>
        </font>
    </p>
    <p class="ti411" style="font-size: 16px;color:red;margin: 10px auto;">
        <?php if(profile('clientkind') == '-4'): ?>您提交的以上信息我们将在1-5个工作日内完成对您信息的审核<?php endif; ?>
        <?php if(profile('clientkind') == '-5'): echo '您本次审核失败，原因如下:'.$error_msg; endif; ?>
    </p>
    <?php if(profile('clientkind') == '-5'): ?>
        <p class="tc"><button style="width:250px;" class="ls retry ajax-submit-button" data-loading-msg="处理中..." type="button">重新提交</button></p>
    <?php endif; ?>
    <?php if(profile('clientkind') == '2'): ?>
        <p class="tc"><button style="width:250px;" class="ls"onclick="window.location.href='/index.php'" type="button">返回主页</button></p>
    <?php endif; ?>
</div>
</body>
<!--	加载头部文件-->
<?php $this->load->view('common/footer'); ?>
<script>
    $(function(){
       $('.retry').click(function(){
           $.post('/index.php/login/ajax_company_apply_retry',{},function(rs){
               if(rs.status == '10000'){
                   window.location.href = '/index.php/login/company_apply';
               }else{
                   wsb_alert('出错了,请刷新重试!',2)
               }
           },'json');
       });
    });
</script>
</html>