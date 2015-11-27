<!DOCTYPE html>
<html>
<head>
    <title>聚雪球_网加金服_P2P理财首选互联网金融P2P网贷平台_100元即可投资!</title>
    <?php $this->load->view('common/head_file'); ?>
</head>
<body>    <!--head start-->
<?php $this->load->view('common/head');?>      
    <!--head end-->
    <!--邮箱成功-->
    <div class="row">
        <div class="mail_sucess">
            <div class="fl">
			<?php if($status=='10000'):?>
                <div class="duigou">✔</div>
				<?php else:?>
				<div class="duigou">✘</div>
				<?php endif;?>
            </div>
            <div class="fr">
				<?php if($status=='10000'):?>
                <p><?php echo  $msg?></p>
                <p class="small"><font id="das">3</font>秒钟后自动跳转至个人中心。</p>
                <a href="<?php echo site_url('user/user/account_information'); ?>">立即跳转</a>
                <script type="text/javascript">
                var wait=3;
                function time() {
                        if (wait == 0) {
                            window.location.href="<?php echo site_url('user/user/account_information')?>";
                        } else {
                            document.getElementById("das").innerHTML="" + wait + "";
                            wait--;
                            setTimeout(function() {
                                time()
                            },
                            1000)
                        }
                    }
                time()
                </script>
				<?php else:?>
					<p><?php echo  $msg?></p>
					<p class="small">请点击重新认证返回邮箱认证页面！</p>
					<a href="http://localhost/user_api/index.php/user/user/account_information">重新认证</a>
				<?php endif;?>
            </div>
        </div>
    </div>
    <!--邮箱成功-->
<!--footer-->
<?php $this->load->view('common/footer');?> 
<!--footer-->
<!--headjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys'],function(){
        addnav($(".nav"));
        addnav($(".main_nav"));
        main_nav_pop($(".main_nav").find($(".fr")).find($("li")));
        nav_pop($(".nav_have_son"));
        
    });
</script>
<!--headjs end--> 
</body>
</html>