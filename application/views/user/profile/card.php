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
    <!--银行卡管理-->
    <!--user start-->
    <div class="user_nav row">
        <a href="/index.php">首页</a>&nbsp;>&nbsp;<a href="/index.php/user/user/account_information">账户设置</a>&nbsp;>&nbsp;<a href="javascript:void(0);">银行卡管理</a>
    </div>
    <div class="row user">
        <!--左侧通用-->
        <?php $this->load->view('common/user_left');?>
        <!--左侧通用-->
        <!--右侧-->
        <div class="user_right">
            <div class="black_bg"></div>
            <h1>银行卡管理<a href="/index.php/about/help?cat_id=47&id=486" target="_blank">常见问题</a></h1>
            <ul class="yhk">
			<?php if($bank['status']==10001):?>
                <li class="no_binding">
                    <div class="fl">
                        <div class="addcard">+</div>
                    </div>
                    <div class="fr">
                        <p>呃～这儿什么都没有</p>
                        <p class="font_18">快去<font class="addcard">绑定一张银行卡</font>吧</p>
                    </div>
                </li>
				<?php else:?>
                <li>
                    <div class="top"><img src="/assets/images/bank/<?php echo $bank['data']['code']?>.png"></div>
                    <div class="center">
                        <p>开户姓名：<?php echo $bank['data']['real_name']?></p>
                        <p>银行卡号：<span class="account-info"><?php echo secret($bank['data']['account'],11)?></span>
                            <a href="javascript:void (0);" style="float: right;margin-right: 10px;" class="card-show" data-card-no="<?php echo $bank['data']['card_no']; ?>">显示卡号</a>
                        </p>
                    </div>            
                    <div class="bottom">
                        <font class="fr card-unbind ajax-submit-button" data-card-no="<?php echo $bank['data']['card_no']; ?>" data-loading-msg="信息检查中...">解除绑定</font>
                    </div>
                </li>
				<?php endif;?>
            </ul>
            <!--银行卡弹出-->
            <div class="yhk_pop user_data_pop">
                <div class="title">
                    <span>银行卡绑定</span><font class="fr close">×</font>
                </div>
                <form id="myform" action="" method="">
                <div class="yhk_popbody">
                    <div class="yhkxz_left fl">
                        选择银行：
                    </div>
                    <div class="yhkxz_right fl">
					<?php if($all_bank['status']=='10000'):?>
						<?php if(!empty($all_bank['data'])):?>
						<?php foreach($all_bank['data'] as $k => $v):?>
                        <div class="yhsection <?php echo $k==0?'active':''; ?>" data-bank-name="<?php echo $v['bank_name']; ?>" data-bank-id="<?php echo $v['bank_id'];?>"><img src="<?php echo base_url('assets/images/bank/'.$v['code'].'.png')?>"></div>
						<?php endforeach;?>
						<?php endif;?>
						<?php endif;?>
                    </div>
					
                    <div class="yhkxz_left_xian fl"></div>
                    <div class="yhkxz_right_xian fr"></div>
					<div class="tip_qx"></div>
                    <input class="yhks" type="hidden" name="" value="" />
                    <div class="yhk_left fl">
                        银行卡号：
                    </div>
                    <div class="yhk_right fl">
                        <input class="yhkh_inp" type="text" name="account" value="" placeholder="请输入银行卡号"/>
                    </div>
					<div class="tip_qx_1"></div>
                   <!--<div class="yhk_left fl">
                        预留手机号：
                    </div>
                    <div class="yhk_right fl">
                        <input class="yhk_sjh_inp" type="text" name="user_1" value="" placeholder="请输入预留手机号"/>
                    </div>-->
                    <div class="but"><button type="button" id="sub">绑定</button></div>
                </div>    
                </form>
            </div>
            <!--银行卡弹出-->
            <!--成功弹出-->
            <div class="bdyhk_cg">
                <div class="title">
                    <span>绑定银行卡</span><font class="fr close">X</font>
                </div>
                <div class="popbody tc">
                    <div class="p smrz_p">
                        <div class="fl tr">
                            <img src="<?php echo base_url('assets/images/user/xrt.png')?>">
                        </div>
                        <div class="fr tl">
                            <div class="popbody_p shb">银行卡添加成功</div>
                            <div class="popbody_p xb"><?php echo profile('user_name'); ?><font>，您已成功添加尾号为</font><span class="account-last-4"></span><font>的</font><span class="account-name"></span><font>银行卡！</font></font></div>
                        </div>
                    </div>
                    <button type="button" class="close" >完成</button>
                </div>
            </div>
            <!--成功弹出-->

            <!--    解绑银行卡弹出     -->
            <div class="user_data_pop unbind-modal">
                <div class="title">
                    <span>解绑银行卡</span><font class="fr close">×</font>
                </div>
                <div class="popbody tc">
                    <div class="p smrz_p">
                        <div class="yhk_left fl" style=" width:20%;line-height:45px;">
                            资金密码：
                        </div>
                        <div class="yhk_right fl" style=" width:68%;line-height:45px;">
                            <input class="unbind-zjmm ifhav" style=" width:95%; height:36px; text-indent:10px;" type="password" name="zjmm" value="" placeholder="请输入资金密码"/>
                        </div>
                    </div>
                    <button type="button" class="unbind-do ajax-submit-button ls" style="width: 120px;height: 40px;margin: 10px;" data-loading-msg="解绑中..." >解除绑定</button>
                </div>
            </div>

            <!--    显示银行卡弹出     -->
            <div class="user_data_pop card-show-modal">
                <div class="title">
                    <span>显示银行卡号</span><font class="fr close">×</font>
                </div>
                <div class="popbody tc">
                    <div class="p smrz_p">
                        <div class="yhk_left fl" style=" width:20%;line-height:45px;">
                            验证码：
                        </div>
                        <div class="yhk_right fl" style=" width:68%;line-height:45px;">
                            <input class="card-show-authcode ifhav" style=" width:95%; height:36px; text-indent:10px;" type="text" name="authcode" value="" placeholder="请输入验证码"/>
                        </div>
                    </div>
                    <div class="p smrz_p" style="margin-top:10px;">
                        <button class="send-sms green" style="width: 120px;height: 40px;margin: 0 10px;">发生短信</button>
                        <button class="send-voice ls" style="width: 120px;height: 40px;margin: 0 10px;">发生语音</button>
                    </div>
                    <button type="button" style="width: 120px;height: 40px;margin: 10px;" class="card-show-do ajax-submit-button ls" data-loading-msg="查询中..." >显示</button>
                </div>
            </div>
        </div>
        <!--右侧-->
    </div>
    <!--银行卡管理-->
<!--footer-->
<?php $this->load->view('common/footer');?> 
<!--footer-->
<!--userjs start-->
<script type="text/javascript">
    seajs.use(['jquery','sys','validator','wsb_sys'],function(){
        $('.ifhav').focus(function(){
            $(this).addClass('hav');
        });
        $('.ifhav').blur(function(){
            if($.trim($(this).val())==''){
                $(this).removeClass('hav');
            }
        });
        yhkxz_tab($('.yhkxz_right'),$('.yhks'));
        pop($('.addcard'),$('.yhk_pop'),$('.yhk_pop').find('.close'));
        var card_bind = function(bankId,account){
            $('#sub').text('提交中...').attr('disabled',true).css('background-color','#DDDDDD');
            $.post('/index.php/user/user/card',{bank_id:bankId,account:account},function(result){
                $('#sub').text('绑定').removeAttr('disabled').css('background-color','#0287d4');
                if(result.status == '10000'){
                    $('.account-last-4').text(account.substr(account.length-4));
                    $('.account-name').text($('.active').data('bankName'));
                    $('.yhk_pop').find('.but').parents('.user_data_pop').fadeOut('normal',function () {
                        $(".black_bg").fadeIn();
                        $('.bdyhk_cg').fadeIn();
                    });
                    //pop_sub($('.yhk_pop').find('.but'),$('.bdyhk_cg'),$('.bdyhk_cg').find('.close'));
                    $('.bdyhk_cg'),$('.bdyhk_cg').find('.close').click(function(){
                        $(".black_bg").fadeOut();
                        $('.bdyhk_cg').fadeOut('normal',function(){
                            window.location.reload();
                        });
                    });
                    /*$('.yhk').html('<li><div class="top"><i class="zhaoshang"></i>' +
                        '<span>'+result.data.bank_name+'</span>' +
//                        '<font>'+result.data.content+'</font>' +
                        '</div><div class="center">' +
                        '<p>开户姓名：'+result.data.real_name+'</p>'+
                        '<p>银行卡号：'+result.data.account+'</p>'+
                        '</div><div class="bottom"><font class="fr card-unbind" data-card-no="'+result.data.card_no+'">解除绑定</font></div></li>');
                    $(".card-unbind").bind('click',function(){
                        wsb_alert('请致电客服中心申请银行卡解绑!',3);
                    });*/
                }else{
                    wsb_alert(result.msg,3);
                }

            },'json');
        };
		$('#sub').click(function(){
			if($('.active').length !=1){
				wsb_alert('请选择所属银行！',2);
			}else{
                var card_id = $('.active').data('bankId');
                var account = $("input[name='account']").val();

                var account_reg = /^[1-9][0-9]{5,}$/;
                if (!account_reg.test(account)) {
                    wsb_alert('请输入正确格式的银行账号!',2);
                    return false;
                }
                $('#sub').text('检测中...').attr('disabled',true).css('background-color','#DDDDDD');;
                $.post('/index.php/user/user/ajax_check_card_bin', {'account': account}, function (rs) {
                    var is_ok = true;
                    if (rs.status == '10000') {
                        if (rs.data.card_type == '3') {
                            wsb_alert('不支持信用卡充值，请更换借记卡进行充值!',2);
                            $('#sub').text('绑定').removeAttr('disabled').css('background-color','#0287d4');;
                        } else {
                            //核对银行名称
                            if($('.active').data('bankName') != rs.data.bank_name){
                                if($(".yhsection[data-bank-name='"+rs.data.bank_name+"']").length == 1){
                                    $(".yhsection[data-bank-name='"+rs.data.bank_name+"']").click();
                                    card_id = $('.active').data('bankId');
                                }else{
                                    wsb_alert('暂不支持该银行账户,请重新输入!',2);
                                    $('#sub').text('绑定').removeAttr('disabled').css('background-color','#0287d4');;
                                    is_ok = false;
                                }
                            }
                           if(is_ok) card_bind(card_id,account);
                        }
                    } else {
                        wsb_alert(rs.msg,3);
                        $('#sub').text('绑定').removeAttr('disabled').css('background-color','#0287d4');;
                    }
                }, 'json');
			}
		});
        $(".card-unbind").bind('click',function(){
            var card_no = $(this).data('cardNo');
            $.post('/index.php/user/user/ajax_check_card_unbind_enable',{card_no:card_no},function(rs){
                switch (rs.status){
                    case '10000':
                        $(".black_bg").fadeIn();
                        $('.unbind-modal').fadeIn().find('.close').click(function(){
                            $(".black_bg").fadeOut();
                            $('.unbind-modal').fadeOut();
                        });
                        $('.unbind-do').click(function(){
                            if($('.unbind-zjmm').val() != ''){
                                $.post('/index.php/user/user/ajax_card_unbind',{card_no:card_no,security:$('.unbind-zjmm').val()},function(rs){
                                    if(rs.status == '10000'){
                                        wsb_alert('解绑成功!',1);
                                        setTimeout(function(){
                                            window.location.reload();
                                        },1000);
                                    }else{
                                        wsb_alert(rs.msg,2)
                                    }
                                },'json');
                            }else{
                                wsb_alert('请输入资金密码!',2)
                            }
                        });
                        break;
                    case '10001':
                        wsb_alert('出错了,'+rs.msg,2);
                        break;
                    case '10002':
                        wsb_alert('请致电客服中心申请银行卡解绑!',3);
                        break;
                    default:
                }
            },'json');
        });
        $('.card-show').click(function(){
            $(".black_bg").fadeIn();
            $('.card-show-modal').fadeIn().find('.close').click(function(){
                $(".black_bg").fadeOut();
                $('.card-show-modal').fadeOut();
            });
        });
        $('.send-sms').send_sms('sms','<?php echo profile('mobile'); ?>','showcard');
        $('.send-voice').send_sms('voice','<?php echo profile('mobile'); ?>','showcard');
        $('.card-show-do').click(function(){
            if($('.card-show-authcode').val() != ''){
                $.post('/index.php/user/user/ajax_card_show_account',{card_no:$('.card-show').data('cardNo'),authcode:$('.card-show-authcode').val()},function(result){
                    if(result.status == '10000'){
                        $('.account-info').text(result.data);
                        $(".black_bg").fadeOut();
                        $('.card-show-modal').fadeOut();
                        $('.card-show').remove()
                    }else{
                        wsb_alert(result.msg,3);
                    }
                },'json');
            }else{
                wsb_alert('请输入短信验证码!',2)
            }
        });
    });
</script>
<!--userjs end-->
</body>   
</html>