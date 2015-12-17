//特别说明：事件需自己绑定，以下只展现调用代码。

//初体验
layer.alert('内容')

//第三方扩展皮肤
layer.alert('内容', {
    icon: 1,
    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
})

//询问框
layer.confirm('您是如何看待前端开发？', {
    btn: ['重要','奇葩'] //按钮
}, function(){
    layer.msg('的确很重要', {icon: 1});
}, function(){
    layer.msg('奇葩么么哒', {shift: 6});
});

//提示层
layer.msg('玩命提示中');

//墨绿深蓝风
layer.alert('墨绿风格，点击确认看深蓝', {
    skin: 'layui-layer-molv' //样式类名
    ,closeBtn: 0
}, function(){
    layer.alert('偶吧深蓝style', {
        skin: 'layui-layer-lan'
        ,closeBtn: 0
        ,shift: 4 //动画类型
    });
});

//捕获页
layer.open({
    type: 1,
    shade: false,
    title: false, //不显示标题
    content: $('.layer_notice'), //捕获的元素
    cancel: function(index){
        layer.close(index);
        this.content.show();
        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
    }
});

//页面层
layer.open({
    type: 1,
    skin: 'layui-layer-rim', //加上边框
    area: ['420px', '240px'], //宽高
    content: 'html内容'
});

//自定页
layer.open({
    type: 1,
    skin: 'layui-layer-demo', //样式类名
    closeBtn: false, //不显示关闭按钮
    shift: 2,
    shadeClose: true, //开启遮罩关闭
    content: '内容'
});

//tips层
layer.tips('Hi，我是tips', '吸附元素选择器，如#id');

//iframe层
layer.open({
    type: 2,
    title: 'layer mobile页',
    shadeClose: true,
    shade: 0.8,
    area: ['380px', '90%'],
    content: 'http://layer.layui.com/mobile/' //iframe的url
});

//iframe窗
layer.open({
    type: 2,
    title: false,
    closeBtn: false,
    shade: [0],
    area: ['340px', '215px'],
    offset: 'rb', //右下角弹出
    time: 2000, //2秒后自动关闭
    shift: 2,
    content: ['test/guodu.html', 'no'], //iframe的url，no代表不显示滚动条
    end: function(){ //此处用于演示
        layer.open({
            type: 2,
            title: '很多时候，我们想最大化看，比如像这个页面。',
            shadeClose: true,
            shade: false,
            maxmin: true, //开启最大化最小化按钮
            area: ['1150px', '650px'],
            content: 'http://fly.layui.com/'
        });
    }
});

//加载层
var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2

//loading层
var index = layer.load(1, {
    shade: [0.1,'#fff'] //0.1透明度的白色背景
});

//小tips
layer.tips('我是另外一个tips，只不过我长得跟之前那位稍有些不一样。', '吸附元素选择器', {
    tips: [1, '#3595CC'],
    time: 4000
});

//prompt层
layer.prompt({
    title: '输入任何口令，并确认',
    formType: 1 //prompt风格，支持0-2
}, function(pass){
    layer.prompt({title: '随便写点啥，并确认', formType: 2}, function(text){
        layer.msg('演示完毕！您的口令：'+ pass +' 您最后写下了：'+ text);
    });
});

//tab层
layer.tab({
    area: ['600px', '300px'],
    tab: [{
        title: 'TAB1',
        content: '内容1'
    }, {
        title: 'TAB2',
        content: '内容2'
    }, {
        title: 'TAB3',
        content: '内容3'
    }]
});

//相册层
$.getJSON('test/photos.json?v='+new Date, function(json){
    layer.photos({
        photos: json //格式见API文档手册页
    });
});


//信息框-例1
layer.alert('见到你真的很高兴', {icon: 6});

//信息框-例2
layer.confirm('你确定你很帅么？', {icon: 3}, function(index){
    layer.close(index);
    alert('自恋狂');
});

//信息框-例3
layer.msg('这是最常用的吧');

//信息框-例4
layer.msg('不开心。。', {icon: 5});

//信息框-例4
layer.msg('玩命卖萌中', function(){
//关闭后的操作
});

//页面层-自定义
layer.open({
    type: 1,
    title: false,
    closeBtn: false,
    shadeClose: true,
    skin: 'yourclass',
    content: '自定义HTML内容'
});

//页面层-佟丽娅
layer.open({
    type: 1,
    title: false,
    closeBtn: false,
    area: '516px',
    skin: 'layui-layer-nobg', //没有背景色
    shadeClose: true,
    content: $('#tong')
});

//iframe层-父子操作
layer.open({
    type: 2,
    area: ['700px', '530px'],
    fix: false, //不固定
    maxmin: true,
    content: 'test/iframe.html'
});

//iframe层-多媒体
layer.open({
    type: 2,
    title: false,
    area: ['630px', '360px'],
    shade: 0.8,
    closeBtn: false,
    shadeClose: true,
    content: 'http://player.youku.com/embed/XMjY3MzgzODg0'
});
layer.msg('点击遮罩任意处关闭');

//iframe层-禁滚动条
layer.open({
    type: 2,
    area: ['360px', '500px'],
    skin: 'layui-layer-rim', //加上边框
    content: ['http://layer.layui.com/mobile', 'no']
});

//加载层-默认风格
layer.load();
//此处演示关闭
setTimeout(function(){
    layer.closeAll('loading');
}, 2000);

//加载层-风格2
layer.load(1);
//此处演示关闭
setTimeout(function(){
    layer.closeAll('loading');
}, 2000);

//加载层-风格3
layer.load(2);
//此处演示关闭
setTimeout(function(){
    layer.closeAll('loading');
}, 2000);

//加载层-风格4
layer.msg('加载中', {icon: 16});

//打酱油
layer.msg('尼玛，打个酱油', {icon: 4});

//tips层-上
layer.tips('上', '#id或者.class', {
    tips: [1, '#0FA6D8'] //还可配置颜色
});

//tips层-右
layer.tips('默认就是向右的', '#id或者.class');

//tips层-下
layer.tips('下', '#id或者.class', {
    tips: 2
});

//tips层-左
layer.tips('左边么么哒', '#id或者.class', {
    tips: [4, '#78BA32']
});

//tips层-不销毁之前的
layer.tips('不销毁之前的', '#id或者.class', {
    tipsMore: true
});

//默认prompt
layer.prompt(function(val){
    layer.msg('得到了'+val);
});

//屏蔽浏览器滚动条
layer.open({
    content: '浏览器滚动条已锁',
    scrollbar: false
});

//弹出即全屏
var index = layer.open({
    type: 2,
    content: 'http://www.layui.com',
    area: ['300px', '195px'],
    maxmin: true
});
layer.full(index);

//正上方
layer.msg('灵活运用offset', {
    offset: 0,
    shift: 6
});

//还该列举什么呢
layer.msg('等我想想…');
