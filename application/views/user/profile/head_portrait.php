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
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_information">账户设置</a>&nbsp;>&nbsp;<a href="javascript:void(0);">基本信息-头像上传</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            <div class="black_bg"></div>
            <h1>个人资料</h1>
            <ul class="tab_title">
                <a href="<?php echo site_url('user/user/account_information');?>"><li>账户信息<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/head_portrait');?>"><li class="active">头像上传<font class="fr">|</font></li></a>
                <a href="<?php echo site_url('user/user/account_security');?>"><li>账号安全</li></a>
            </ul>
            <ul class="tab_con">
                <li class="txsc active">
                    <div class="container">
                        <div class="imageBox">
                            <div class="thumbBox"></div>
                            <div class="spinner">
                                <div class="new-contentarea tc">
                                    <a href="javascript:void(0)" class="upload-img">
                                    <label for="upload-file"><font>+</font>选择一张照片</label>
                                    </a>
                                    <input type="file" class="" name="upload-file" id="upload-file">
                                    <p>只支持JPG、PNG、GIF，大小不超过5M</p>
                                </div>
                            </div>
                        </div>
                        <div class="action"> 
                            <a id="btnZoomOut" class="Btnsty_peyton fr"><img class="def" src="<?php echo base_url('assets/images/user/reduce.png')?>"><img class="aft" src="<?php echo base_url('assets/images/user/reduce_1.png')?>"></a>
                            <a id="btnZoomIn" class="Btnsty_peyton fr"><img class="def" src="<?php echo base_url('assets/images/user/add.png')?>"><img class="aft" src="<?php echo base_url('assets/images/user/add_1.png')?>"></a>
                        </div>
                        <div class="cropped">
                            <p>头像预览</p>
                            <div class="cropped_1">
                                <img src="<?php echo base_url('assets/images/user/mrtx.png')?>" align="absmiddle" style="width:100px;height:100px;margin-top:4px;border-radius:100px;box-shadow:0px 0px 12px #7E7E7E;"><p>100px*100px</p>
                                <img src="<?php echo base_url('assets/images/user/mrtx.png')?>" align="absmiddle" style="width:60px;height:60px;margin-top:4px;border-radius:60px;box-shadow:0px 0px 12px #7E7E7E;" ><p>60px*60px</p>
                            </div>
                            <div class="but buttxsc">
                                <input type="submit" class="Btnsty_peyton Btnsty_peyton_1 Btnsty_peyton_disabled ajax-submit-button" data-loading-msg="上传中..." value='保存' disabled="disabled">
                                <div class="new-contentarea tc">
                                    <a href="javascript:void(0)" class="upload-img">
                                    <label for="upload-file">重新选择</label>
                                    </a>
                                    <input type="file" class="" name="upload-file" id="upload-file-1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pop"><img src="<?php echo base_url('assets/images/user/txsccg.png')?>"></div>
                </li>
            </ul>
        </div>
        <!--右侧-->
    </div>
    <!--user end-->
<!--footer-->
<?php $this->load->view('common/footer');?> 
<!--footer-->
<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys','cropbox'],function(){
        $(window).load(function() {
            var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: ''
            }
            var cropper = $('.imageBox').cropbox(options);
            var img="";
            $('#upload-file').on('change', function(){
                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = $('.imageBox').cropbox(options);
                    getImg();
                }
                reader.readAsDataURL(this.files[0]);
                this.files = [];
            })
            $('#upload-file-1').on('change', function(){
                var reader = new FileReader();
                reader.onload = function(e) {
                    options.imgSrc = e.target.result;
                    cropper = $('.imageBox').cropbox(options);
                    getImg();
                }
                reader.readAsDataURL(this.files[0]);
                this.files = [];
            })
            function getImg(){
                img = cropper.getDataURL();
                $('.cropped_1').html('');
                $('.cropped_1').append('<img src="'+img+'" align="absmiddle" style="width:100px;margin-top:4px;border-radius:100px;box-shadow:0px 0px 12px #7E7E7E;"><p>100px*100px</p>');
                $('.cropped_1').append('<img src="'+img+'" align="absmiddle" style="width:60px;margin-top:4px;border-radius:60px;box-shadow:0px 0px 12px #7E7E7E;" ><p>60px*60px</p>');
                }
            $(".imageBox").on("mouseup",function(){
                getImg();
                });
            $('#btnZoomIn').on('click', function(){
                cropper.zoomIn();
            })
            $('#btnZoomOut').on('click', function(){
                    cropper.zoomOut();
                })

            //修改头像
            $(".Btnsty_peyton_1").click(function (){
                var img_str = cropper.getDataURL();
                var img_arr =img_str.split(',');
                $.post('/index.php/user/user/head_portrait',{'type':img_arr[0],'data':img_arr[1]},function(rs){
                    if(rs.status == '10000'){
                        $(".pop").fadeIn(2000).fadeOut(2000);
                        $(".user_icon").find('img').attr('src',rs.data);
                        $(".my_icon").find('img').attr('src',rs.data);
                    }else{
                        wsb_alert(rs.msg);
                    }
                },'json');

            });
        });
    });
</script>
<!--userjs end-->
                  
</body>
</html>