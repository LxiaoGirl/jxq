<!DOCTYPE html>
<html>
<head lang="en">
    <title>回款计划</title>
    <?php $this->load->view('common/mobiles/app_head') ?>
</head>
<body>
<!-- 公共头部导航-->
<?php $this->load->view('common/mobiles/app_common_head') ?>

<div class="container-fluid">
    <div class="row">
        <table class="c_666 tbb1px" width="100%" border="0" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
                <td class="text-center">项目名称</td>
                <td class="text-center text-nowrap">计划回款金额(元)</td>
                <td class="text-center">状态</td>
                <td class="text-center">&nbsp;</td>
            </tr>
            </thead>
            <tbody id="list" style="visibility: hidden">
            <tr>
                <td class="text-center"><span class="c_333 t16line"><a href="#"><span class="subject"></span></a></span>
                </td>
                <td class="text-center"><span class="c_333 interest"></span></td>
                <td class="text-center"><span class="c_red text-nowrap i_type">回款中</span></td>
                <td class="text-center"><span class="iconfont icon-xiangyou1"></span></td>
            </tr>
            <tr id="no-data">
                <td colspan="4" style="text-align: center;" class="no-data-msg">暂无相关信息！</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
</body>
<?php $this->load->view('common/mobiles/app_footer') ?>
<script>
    $(function () {
        $("#list").list_data({
            data : '/index.php/mobiles/home/my_interest',
            page_size : 20,
            show_loading : true,
            event_type : 'scroll',
            list_func : function (obj, v) {
                obj.find(':first').attr('onclick', 'window.location.href="/index.php/mobiles/home/project_detail?borrow_no=' + v.borrow_no + '"');
                if (v.interest == v.interest_receive) {
                    obj.find('.i_type').removeClass('c_red').addClass('c_green').text('回款成功');
                }
                obj.find('.interest').text(parseFloat(v.interest)+parseFloat(v.amount));
            }
        });
    });
</script>
</html>