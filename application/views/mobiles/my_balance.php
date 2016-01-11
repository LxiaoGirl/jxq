<!DOCTYPE html>
<html>
<head lang="en">
    <title>可用余额</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <!-- 顶部导航  -->
    <div class="bg_red_jb row  c_fff balance-v">
        <div class="fl f16 mt10 ml10">
            可用余额：
        </div>
        <div class="clears"></div>

        <div class="text-right mr10 ml10">
            <div class="superbig my_balance list-value">0</div>

        </div>
    </div>
    <!-- 顶部导航  end-->
    <div class="row balance-v" id="my_balance">
        <table class="f12  mt15 mb15" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td style="border-bottom:1px #ddd solid;border-right:1px #ddd solid;" valign="bottom"
                    class="text-center" width="50%">
                    <span>投资中冻结金额(元)</span>

                    <div>
                        <strong class="f18 my_invest_freeze">0</strong>
                    </div>
                </td>

                <td style="border-bottom:1px #ddd solid" valign="bottom" class="text-center" width="50%">
                    <span>提现中冻结金额(元)</span>

                    <div>
                        <strong class="f18 my_transfer_freeze">0</strong>
                    </div>
                </td>
            </tr>

            <tr>
                <td style="border-right:1px #ddd solid;" valign="bottom" class="text-center" width="50%">
                    <span>待收本金(元)</span>

                    <div>
                        <strong class="f18 my_wait_principal">0</strong>
                    </div>
                </td>

                <td valign="bottom" class="text-center" width="50%">
                    <span>总资产(元)</span>

                    <div>
                        <strong class="f18 my_amount">0</strong>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="clears line"></div>

    <div class="row">
        <table class="c_666 tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tbody id="list" style="visibility: hidden">
            <tr>
                <td class="text-center"><span class="c_333 t16line add_time"></span></td>
                <td class="text-center"><span class="c_333 amount"></span></td>
                <td class="text-center"><span class="c_blue text-nowrap status"></span></td>
            </tr>
            <tr id="no-data"><td colspan="3" style="text-align: center;" class="no-data-msg">暂无相关信息!</td></tr>
            </tbody>
        </table>
    </div>
</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    /**
     * 处理精度的减法
     * @param arg1
     * @param arg2
     * @returns {string}
     */
    function accSub(arg1, arg2) {
        var r1, r2, m, n;
        try {
            r1 = arg1.toString().split(".")[1].length;
        }
        catch (e) {
            r1 = 0;
        }
        try {
            r2 = arg2.toString().split(".")[1].length;
        }
        catch (e) {
            r2 = 0;
        }
        m = Math.pow(10, Math.max(r1, r2)); //last modify by deeka //动态控制精度长度
        n = (r1 >= r2) ? r1 : r2;
        return ((arg1 * m - arg2 * m) / m).toFixed(n);
    }
    $(function () {
        $('.balance-v').list_data({
            list_one:true,
            data:'/index.php/mobiles/home/my_balance',
            show_loading:true,
            btn:true,
            'value_func': {
                'my_amount': function (data) {return data ? price_format(data, 2, false) : 0;},
                'my_invest_freeze': function (data) {return data ? price_format(data, 2, false) : 0; },
                'my_transfer_freeze': function (data) { return data ? price_format(data, 2, false) : 0;},
                'my_wait_principal': function (data) {return data ? price_format(data, 2, false) : 0;},
                'my_balance':function(data){
                    var my_balance = '';
                    var wan = data ? Math.floor(parseFloat(data) / 10000) : 0;
                    if (data && wan > 1) {
                        my_balance = '<span>' + wan + '</span>万' +
                            '<span>' + accSub(parseFloat(data), wan * 10000) + '</span>元';
                    } else {
                        if (!data)data = 0;
                        my_balance = '<span>' + parseFloat(data) + '</span>元';
                    }
                    return my_balance;
                }
            },
            callback:function(){
                $("#list").list_data({
                    data:'/index.php/mobiles/home/ajax_get_recharge_list',
                    page_size:20,
                    //show_loading:true,
                    event_type:'scroll',
                    value_func: {
                        'add_time': function (time) {return unixtime_style(time, 'Y-m-d');}
                    },
                    list_func:function(obj,v){
                        if(v.status == '充值失败'){
                            obj.find('.status').removeClass('c_blue').addClass('c_red').css('color','#FF7100');
                            //重新提交
                            if(v.type == '3'){
                                obj.find('.status').html('<a href="/index.php/mobiles/home/recharge_confirm?recharge_no='+v.recharge_no+'" style="color:#FF7100;" target="_self">充值失败</a>');
                            }
                        }
                    }
                });
            }
        });
    });
</script>
</html>