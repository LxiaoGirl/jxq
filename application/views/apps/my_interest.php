<!DOCTYPE html>
<html>
<head lang="en">
    <title>回款计划</title>
    <?php $this->load->view('common/apps/app_head') ?>
</head>
<body>
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
            <tr id="list-noData">
                <td colspan="4" style="text-align: center;">暂无相关信息！</td>
            </tr>
            <tr id="list-noMoreData">
                <td colspan="4" style="text-align: center;">没有更多信息了！</td>
            </tr>
            <tr id="list-loadingData">
                <td colspan="4" style="text-align: center;">加载中...</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>
</body>
<?php $this->load->view('common/apps/app_footer') ?>
<script>
    var page_id = 1, page_size = 20;
    $(function () {
        var list_view = new wb_listview({
            'id': 'list',
            'showLoading': true
        });
        var get_data = function () {
            var condition = '';
            condition += '?per_page=' + ((page_id - 1) * page_size) + '&limit=' + page_size;
            $(window).unbind('scroll');
            $.post('/index.php/apps/home/my_interest' + condition, {}, function (result) {
                list_view.set_pageid(page_id);
                list_view.list(result.data, function (obj, v) {
                    obj.find(':first').attr('onclick', 'window.location.href="<?php echo site_url("apps/home/project_detail?borrow_no="); ?>' + v.borrow_no + '"');
                    if (v.type == 1) {
                        obj.find('.i_type').removeClass('c_red').addClass('c_green').text('回款成功');
                    }
                });
                if (result.data) {
                    page_id++;
                    $(window).bind('scroll', function () {
                        scroll_fun(function () {
                            get_data();
                        });
                    });
                }
            }, 'json');
        };
        get_data();
    });
</script>
</html>