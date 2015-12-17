/**
 * 多循环dom内容 单填充dom内容  加滑动
 *
 * 结构1：
 * <div id="list"> 其他dom内容</div>
 *
 * 或结构2：页面自定义 list-wap 位置
 * <div id="list-warp">
 *   其他dom内容
 *   <div id="list"> 其他dom内容</div>
 *   其他dom内容
 *   </div>
 *
 * 作用：取id=list的里面内容进行数据填充循环（如果是listone 则不循环只是填充数据）
 *
 * 不启用iscroll 则只取id=list的dom内容结构不会变化  可在里面取id=list-noData 作为没有数据的显示（不设置则会取id=list的第一个子元素做为标签进行显示） noMoreData  loadingData  同理
 *
 * 启用iscroll时 dom结构会变化 会在id=xx的外层加上id=xx-warp的div作为iscroll的容器 里面包含 id=xx-scroller的华东容器 和id=pullDown 和 id=pushUP 的上拉下拉容器（根据配置信息可以关闭）
 * 㐓取结构1或结构2 最后生成如：
 *     <div id="list-warp">
 *      <div id="list-scroller">
 *         <div id="pushDown"> </div>
 *         <div id="list"> 其他dom内容</div>
 *         <div id="pullUp"> </div>
 *      </div>
 *     </div>
 *
 * 使用：var myList = wb_listview({
 *  id:'',容器id
 *  iscroll:true(默认是false)，是否用iscroll滑动
 *  showLoading:false(默认是true)，是否显示加载中 非iscroll有效
 *  downFresh:false(默认true)， 是否启用下拉刷新 iscroll启用有效
 *  upLoad:false(默认true) 是否启用上拉加载更多 iscroll有效
 *  funcDeal：{ 对每个处理数据的附加处理函数
 *      字段：function
 *      字段：function
 *      字段：function
 *  }
 *  listone:true(默认false) listone 单数据填充
 *  pageSize：10，‘分页 每页记录数量 内部查询数据分页时用到’ 不分页则不设置
 * });
 *
 * myList.init(json数据|ajax的地址，单个循环的处理含税，最后的处理函数);
 * 如：myList.init('index.php/app/api/ajax_get_list');
 *
 * @param option 相关配置 json
 * {
 * id：‘dom 容器id ’
 * listone:true|false  填充一个dom  |循环 没人false
 * funcDeal：function   每个数据的预处理函数 {field:functionName,field:functionName}
 *  idField：‘数据的id字段名’
 *  pageSize：‘分页 每页记录数量 内部查询数据分页时用到’
 *  fadeIn：‘int 渐隐出现  即一条一条加载’
 *  pageId：‘初始页码 1 2 3.。。’
 *  idField：‘数据的id字段名’
 * }
 */
var wb_listview = function(option){

    //验证配置信息 和初始化配置信息
    if(typeof option == "object"){
        this.id  = option.id || 'wb-list-view'; //循环内容的 容器id
        this.funcDeal   = option.funcDeal;              //需要函数处理的字段 {field:functionName,field:functionName}

        this.downFresh = option.downFresh==undefined?true:option.downFresh; //是否下拉刷新
        this.upLoad = option.upLoad==undefined?true:option.upLoad;            //是否上拉加载更多

        this.iscroll = option.iscroll || false;    //是否启用iscroll 启用后有上啦加载更多和下拉刷新   没有启用则没有下拉刷新  上啦到底加载  或按键加载
        this.iscrollId = this.id+'-warp'; //iscroll最外的容器id

        this.listOne = option.listone || false;
        // 未设置 一条  则是循环  设置了则是一条的数据填充
        if(! this.listOne){
            this.idField    = option.idField || 'id';  //循环内容的s数据id字段 string
            this.pageSize   = option.pageSize;         //分页的条数  如果不设置即视为不分页 int
            this.fadeIn     = option.fadeIn;           //渐隐出现  即一条一条加载 int
            this.pageId     = 1;                        //初始分页id=1  可通过set-pageid 设置

            //如果没有启用iscroll  获取页面的没有数据 加载中 没有更多数据 html代码和初始化
            if( ! this.iscroll){

                //如果有 没有数据 的设置  则获取代码并去掉页面代码
                if($("#"+this.id+"-noData").length){
                    this.noData = $("#"+this.id+"-noData").clone();
                    $("#"+this.id+"-noData").remove();
                }

                //如果有 没有更多数据 的设置  则获取代码并去掉页面代码
                if($("#"+this.id+"-noMoreData").length){
                    this.noMoreData = $("#"+this.id+"-noMoreData").clone();
                    $("#"+this.id+"-noMoreData").remove();
                }

                //如果有 加载中 的设置  则获取代码并去掉页面代码
                if($("#"+this.id+"-loadingData").length){
                    this.loadingData = $("#"+this.id+"-loadingData").clone();
                    $("#"+this.id+"-loadingData").remove();
                }

                //如果有 更多 的按钮设置 则获取代码并去掉页面代码
                this.moreButton = $("#"+this.id+"-moreButton").length?true:false; //是否有 获取更多的 按钮
                this.eventType = option.eventType || 'click';                         //按钮事件

                this.showLoading = option.showLoading || true;                         //是否显示 加载中 效果
            }

            this.html       = $('#'+this.id).clone();                           //克隆要循环的html代码

            //没有数据 和没有更多数据 的初始化
            if( ! this.iscroll){

                //如果没有获取都页面html代码 则从克隆的待循环的html中生成
                if(!this.noData){
                    this.noData = this.html.find(':first').clone();
                    this.noData.html('暂无相关信息！').css({'text-align':'center','padding':'10px 0 10px 0'});
                }

                //如果没有获取都页面html代码 则从克隆的待循环的html中生成
                if(!this.noMoreData){
                    this.noMoreData = this.html.find(':first').clone();
                    this.noMoreData.attr('id',this.id+'-noMoreData').html('没有更多信息了！').css({'text-align':'center','padding':'10px 0 10px 0'});
                }

                //如果没有获取都页面html代码 则从克隆的待循环的html中生成
                if(!this.loadingData){
                    this.loadingData = this.html.find(':first').clone();
                    this.loadingData.attr('id',this.id+'-loadingData').html('加载中...').css({'text-align':'center','padding':'10px 0 10px 0'});
                }
            }

            //初始化页面代码
            if(this.iscroll){   //如果有启用iscroll 则增加 下拉刷新  循环容器  和上拉加载更多 div  并显示
                //验证是否启用禁用下拉刷新和上拉加载更多
                if($("#"+this.iscrollId).length == 0){

                    var html_copy = this.html.clone();
                    var htmlDom = document.createElement('div');

                    htmlDom.setAttribute('id',this.iscrollId+'-scroller');
                    if(this.downFresh)$(htmlDom).append('<div id="pullDown" style="text-align: center;visibility: hidden;padding: 10px 0 5px 0;"><span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span></div>');
                    $(htmlDom).append(html_copy[0]);
                    if(this.upLoad)$(htmlDom).append('<div id="pullUp" style="text-align: center;visibility: hidden;padding: 5px 0 10px 0;"><span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span></div>');

                    $('#'+this.id).before('<div id="'+this.iscrollId+'"></div>').remove();
                    $('#'+this.iscrollId).html(htmlDom);
                }else{
                    if($("#"+this.iscrollId+'-scroller').length == 0){
                        var html = $("#"+this.iscrollId).clone();
                        $("#"+this.iscrollId).html('<div id="'+this.iscrollId+'-scroller"></div>');
                        $("#"+this.iscrollId+'-scroller').html($(html).html());
                    }
                    if(this.downFresh)$("#"+this.iscrollId+'-scroller').prepend('<div id="pullDown" style="text-align: center;visibility: hidden;padding: 10px 0 5px 0;"><span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span></div>');
                    if(this.upLoad)$("#"+this.iscrollId+'-scroller').append('<div id="pullUp" style="text-align: center;visibility: hidden;padding: 5px 0 10px 0;"><span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多...</span></div>');
                }

                $('#'+this.id).html('').css('visibility','visible');

            }else{ //如果没有启用iscroll  则情况当前带循环的容器 并显示
                $('#'+this.id).html('').css('visibility','visible');
            }
        }else{
            //listone 情况
            if(this.iscroll){
                var html_copy = $("#"+this.id).clone();
                var htmlDom = document.createElement('div');
                htmlDom.setAttribute('id',this.iscrollId+'-scroller');
                if(this.downFresh)$(htmlDom).append('<div id="pullDown" style="text-align: center;visibility: hidden;padding: 10px 0 5px 0;"><span class="pullDownIcon"></span><span class="pullDownLabel">下拉刷新...</span></div>');
                $(htmlDom).append(html_copy[0]);

                $('#'+this.id).before('<div id="'+this.iscrollId+'"></div>').remove();
                $('#'+this.iscrollId).html(htmlDom);
            }
        }
    }

    this.dataUrl = '';             // 获取数据的地址
    this.dataParams = '';         //参数

    this.ajaxUrl = '';            // ajax获取数据的地址 拼接了pageId 和 pageSize
    this.ajaxParams = '';        //参数 拼接了pageId 和 pageSize

    this.listFunc = '';          //循环的function
    this.listCallback = '';     //循环完的回调函数

    this.iscrollObj = '';       //iscroll的实例化obj

    this._noData = false;
    this._noMoreData = false;
    this._isRefresh  = false;

    this.offsetHeight = option.offsetHeight==undefined?100:option.offsetHeight; //其他高度
    this.isResetHeight = false;  //是否有重置高度
};
wb_listview.prototype = {
    'list':function(data,fun,callback){ //循环主方法

        //第一页 初始化 默认值
        if(this.pageId == 1){
            this._noData = false;
            this._noMoreData = false;
            $("#pullUp").css('visibility','hidden');
            if(!this._isRefresh)$("#"+this.id).html('');
            if(this.iscrollObj)this.iscrollObj.destroy(); this.iscrollObj = '';
        }

        //第一页执行循环时 没有启用iscroll 并设置了显示加载中效果 则追加加载中效果代码 到循环容器内
        if(this.pageId == 1)this._set_loading(true);

        //获取 循环单条的外加函数 用于第二次数据加载时调用
        if((typeof fun == "function" && this.listFunc == '') || (typeof fun == "function" && this.pageId == 1)){
            this.listFunc = fun;
        }

        //获取 循环单条的外加函数 用于第二次数据加载时调用
        if((typeof callback == "function" && this.listCallback == '') || (typeof callback == "function" && this.pageId == 1)){
            this.listCallback = callback;
        }

        //初始化数据  如果是数据则返回进行循环 如果是地址则ajax获取数据返回
        data = this._init_data(data);

        var _this=this;

        //延迟一秒 加载数据到dom中
        var t = setTimeout(function(){

            //隐藏加载中的代码
            _this._set_loading(false);

            //循环数据
            _this._list(data,fun,callback);

            //如果数据不为空 有分页 且当前获取的数据条数等于分页的数量  说明还可能有更多数据  进行下次的加载的时间绑定
            if(data && _this.pageSize && data.length >= _this.pageSize-1)_this._set_event(true);

            //如果是启用了iscroll的进行iscroll的刷新（如果是第一次则是初始化）
            if(_this.iscroll)_this._iscroll_refresh();

            clearTimeout(t);
        },1000);
    },
    'init':function(data,fun,callback){ //初始化方法
        if(this.listOne){
            this.list_one(data,fun);
        }else{
            this.list(data,fun,callback);
        }
    },
    'set_pageid':function(pageid){ //设置当前页码
        this.pageId = pageid;
    },
    'list_one':function(data,callback){ //循环一次
        data = this._init_data(data);
        if(data && typeof data == "object"){
            this.listCallback = callback;
            for(var key in data){
                var val = data[key];
                if(this.funcDeal){
                    var func=this.funcDeal[key];
                    if(func)val=func(val);//处理数据
                }
                //data键名为class的标签
                var obj=$('#'+this.id).find("."+key);
                if(obj.length > 0){
                    if(obj.length >= 2){
                        $(obj).each(function(i,v){
                            switch ($(v).get(0).tagName){
                                case 'IMG':
                                    $(v).attr('src',val);
                                    break;
                                case 'A':
                                    if($(v).attr('tel') == 'tel'){
                                        $(v).attr('href','tel:'+val);
                                    }else{
                                        $(v).attr('href',val);
                                    }
                                    break;
                                default :
                                    $(v).html(val);
                            }
                        });
                    }else {
                        switch (obj.get(0).tagName) {
                            case 'IMG':
                                obj.attr('src', val);
                                break;
                            case 'A':
                                if (obj.attr('tel') == 'tel') {
                                    obj.attr('href', 'tel:' + val);
                                } else {
                                    obj.attr('href', val);
                                }
                                break;
                            default :
                                obj.html(val);
                        }
                    }
                }
            }

            if(typeof callback == "function")callback(obj,data);
        }
        //如果是启用了iscroll的进行iscroll的刷新（如果是第一次则是初始化）
        if(this.iscroll)this._iscroll_refresh();
    },
    'list_data':function(){ //循环数据的调用方法（第二次绑定）
        this._init_ajax_url_param();
        var data = this._get_data();
        this.list(data,this.listFunc,this.listCallback);
    },
    '_list':function(data,fun,callback){ //循环
        var _this = this;
        //验证是否有合格的数据
        if(data && typeof data == "object"){
            var htmls = '';//所有循环的html代码

            $(data).each(function(i,v){
                var html = _this.html.clone();

                $(html).find(":first").addClass(_this.id+"-loop"); //为循环的html item 加上loop class

                for(var key in v){
                    var val = v[key];
                    if(_this.funcDeal){
                        var func=_this.funcDeal[key];
                        if(func)val=func(val);//处理数据
                    }
                    //循环查询 带data键名为class的标签
                    var obj=$(html).find("."+key);
                    if(obj.length > 0){
                        if(obj.length >= 2){
                            $(obj).each(function(i1,v1){
                                switch ($(v1).get(0).tagName){
                                    case 'IMG':
                                        $(v1).attr('src',val);
                                        break;
                                    case 'A':
                                        if($(v1).attr('tel') == 'tel'){
                                            $(v1).attr('href','tel:'+val);
                                        }else{
                                            $(v1).attr('href',val);
                                        }
                                        break;
                                    default :
                                        $(v1).html(val);
                                }
                            });
                        }else{
                            switch (obj.get(0).tagName){
                                case 'IMG':
                                    obj.attr('src',val);
                                    break;
                                case 'A':
                                    if(obj.attr('tel') == 'tel'){
                                        obj.attr('href','tel:'+val);
                                    }else{
                                        obj.attr('href',val);
                                    }
                                    break;
                                default :
                                    obj.html(val);
                            }
                        }
                    }
                }
                //为每个item加个id
                var uniq = v[_this.idField]?v[_this.idField]:_this.pageId+'-'+i;

                $(html).find(":first").attr('id',_this.id+'-'+uniq);

                //为每个item 执行 自定义funtion
                if(typeof fun == "function")fun(html,v);

                //赋值html
                htmls+=$(html).html();

                //渐隐效果 大于第一页时启用
                if(_this.fadeIn && _this.pageId>1){
                    $("#"+_this.id).append($(html).html());
                    $("."+_this.id+"-loop:last").hide().delay(i*_this.fadeIn).fadeIn(_this.fadeIn);
                }
            });

            //第一页 或 飞渐隐时 加载html处理
            if(_this.pageId == 1){
                $("#"+_this.id).html(htmls);
            }else{
                if( ! _this.fadeIn)$("#"+_this.id).append(htmls);
            }

            //总的回调函数
            if(typeof callback == "function")callback();
            //页数自加
            _this.pageId++;

            //处理 加载中 样式显示
            _this._set_loading(true);

            if(_this.pageSize && data.length < _this.pageSize-1){
                this._noMoreData = true;
            }
        }else{ //没有数据  或数据不合格
            //是刷新出现 没有数据了
            if(_this.pageId == 1){
                $('#'+_this.id).html('');
            }
            //没有数据的显示处理
            if(_this.iscroll){
                if(_this.pageId == 1){
                    _this._noData = true;
                }else{
                    _this._noMoreData = true;
                }
                $("#pullUp").css('visibility','visible');
            }else{
                if(_this.pageId == 1){
                    $('#'+_this.id).html(_this.noData[0]);
                }else{
                    if($("#"+_this.id+"-noMoreData").length == 0){
                        $('#'+_this.id).append(_this.noMoreData[0]);
                    }
                }
            }
        }
    },
    '_get_data':function(){ //ajax 获取数据 json
        var data = '';
        var _this = this;
        $.ajax({
            url:this.ajaxUrl,
            dataType:'json',
            type:'post',
            async: false,
            data:this.ajaxParams,
            beforeSend:function(){
                if( ! _this.listOne)_this._set_event(false);
            },
            complete:function(){},
            success:function(resut){
                data = resut.data;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('error:'+XMLHttpRequest.status+'&'+XMLHttpRequest.readyState+'&'+textStatus);
            }
        });
        return data;
    },
    '_init_data':function(data){ //初始化数据  如果数据是个字符串 就默认是地址 用get_data方法查询  ru
        if(data && typeof data == "string"){
            this.dataUrl = data;
            if(data.indexOf('?') != -1){
                var url_array = data.split('?');
                this.dataParams = url_array[1];
            }
            this._init_ajax_url_param();
            return this._get_data();
        }else{
            return data;
        }
    },
    '_init_ajax_url_param':function(){ //构建 ajax请求分页地址和参数
        if(!this.listOne && this.pageSize){ //有分页  配置分页
            this.ajaxParams += (this.dataParams?'&':'')+'pageId='+this.pageId+'&pageSize='+this.pageSize;
            if(this.dataUrl.indexOf('?') != -1){
                this.ajaxUrl = this.dataUrl+'&pageId='+this.pageId+'&pageSize='+this.pageSize;
            }else{
                this.ajaxUrl = this.dataUrl+'?pageId='+this.pageId+'&pageSize='+this.pageSize;
            }
        }else{
            this.ajaxParams = this.dataParams;
            this.ajaxUrl = this.dataUrl;
        }
    },
    '_set_event':function(type){ //设置 事件  type=1为绑定 =2为解绑
        var _this=this;
        if( ! _this.iscroll){
            if(type){ //绑定
                if(this.moreButton){
                    $("#"+this.id+"-moreButton").bind(this.eventType,function(){
                        _this.list_data();
                    });
                }else{
                    //滑动到底部时的加载事件处理
                    $(window).bind('scroll',function(){
                        var scrollTop = $(this).scrollTop(),scrollHeight = $(document).height(),windowHeight = $(this).height();
                        if(scrollTop + windowHeight == scrollHeight){
                            _this.list_data();
                        }
                    });
                }
            }else{ //解绑
                if(this.moreButton){
                    $("#"+this.id+"-moreButton").unbind(this.eventType);
                }else{
                    $(document).unbind('scroll');
                }
            }
        }else{
            //显示上拉加载更多
            if(type)$("#pullUp").css('visibility','visible');
        }
    },
    '_set_loading':function(flag){ //显示或隐藏 加载中效果   flag = true|false
        if(flag){
            //如果是iscroll 则只需要设置第一次加载时的效果
            if(this.iscroll) {
                if(this.pageId == 1) {
                    $("#pullDown").addClass('loading');
                    if(this._isRefresh){
                        $('#pullDown .pullDownLabel').html('正在刷新...');
                    }else{
                        $('#pullDown .pullDownLabel').html('加载中...');
                    }
                    $("#pullDown").css('visibility','visible');
                }
            }else{
                //验证配置是否需要显示
                if(this.showLoading) {
                    //第一页  直接追加  以上 则验证文档数据和屏幕的高度关系  大于屏幕再追加
                    if(this.pageId == 1){
                        $("#"+this.id).append(this.loadingData[0]);
                    }else{
                        if($(document).height() > window.innerHeight)$("#"+this.id).append(this.loadingData[0]);
                    }
                }
            }
        }else{
            if( ! this.iscroll){
                if(this.showLoading)$('#'+this.id+'-loadingData').remove();
            }else{
                //$("#pullUp").css('visibility','hidden');
            }
        }
    },
    '_iscroll_refresh':function(){ //刷新或初始化iscroll
        if(this.iscrollObj == "" && this.iscroll){
            this._init_iscroll();
        }else{
            if(this.iscroll)$('.pullDownLabel').html('下拉刷新...');
            this.iscrollObj.refresh();
        }
    },
    '_init_iscroll':function(){ //初始化iscroll的具体处理

        //if($(document).height() > window.innerHeight){
            $("#"+this.iscrollId).height(window.innerHeight-this.offsetHeight);
            this.isResetHeight = true;
        //}
        document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

        var pullDownEl, pullDownOffset,
            pullUpEl, pullUpOffset,
            _this = this;

        $(window).unbind('resize').bind('resize',function(){
            if(_this.isResetHeight)$("#"+_this.iscrollId).height(window.innerHeight-_this.offsetHeight);
        });

        if(this.downFresh){
            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;
        }

        if(!this.listOne && this.upLoad){
            pullUpEl = document.getElementById('pullUp');
            pullUpOffset = pullUpEl.offsetHeight;
        }

        var pullDownAction = function() {
            if(_this.dataUrl){
                if(_this.listOne){
                    _this.list_one(_this._init_data(_this.dataUrl),_this.listCallback);
                }else{
                    _this._isRefresh = true;
                    _this.set_pageid(1);
                    _this.list_data();
                }
            }else{
                _this.iscrollObj.refresh();
            }
        },pullUpAction = function() {
            if(_this.dataUrl && !_this._noData && !_this._noMoreData){
                _this.list_data();
            }else{
                _this.iscrollObj.refresh();
            }
        };

        this.iscrollObj = new iScroll(this.iscrollId, {
                hScroll:false,
                hScrollBar:false,
                vScrollBar:false,
                topOffset: pullDownOffset,
                onRefresh: function () {
                    _this._isRefresh = false;
                    if( ! _this.listOne && _this.upLoad) {
                        if (_this._noData) {
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '暂无相关数据';
                        } else {
                            if (_this._noMoreData) {
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = '没有更多数据了';
                            } else {
                                pullUpEl.className = '';
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                            }
                        }
                    }

                    if (_this.downFresh && pullDownEl.className.match('loading')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';

                        $("#pullDown").css('visibility','hidden');
                    } else if (!_this.listOne && _this.upLoad && pullUpEl.className.match('loading')) {
                        if(_this._noData){
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '暂无相关数据';
                        }else{
                            if(_this._noMoreData){
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = '没有更多数据了';
                            }else{
                                pullUpEl.className = '';
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                            }
                        }
                    }
                },
                onScrollMove: function () {

                    if(_this.downFresh && this.y < 5){
                        $("#pullDown").css('visibility','visible');
                    }

                    if (_this.downFresh && this.y > 5 && !pullDownEl.className.match('flip')) {
                        pullDownEl.className = 'flip';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = '松手开始更新...';
                        this.minScrollY = 0;
                    } else if (_this.downFresh && this.y < 5 && pullDownEl.className.match('flip')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = '下拉刷新...';
                        this.minScrollY = -pullDownOffset;
                    } else if (!_this.listOne && _this.upLoad && this.y < (this.maxScrollY - 35) && !pullUpEl.className.match('flip')) {
                        if( ! _this._noData && !_this._noMoreData) {
                            pullUpEl.className = 'flip';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '松手开始更新...';
                            this.maxScrollY = this.maxScrollY;
                        }
                    } else if (!_this.listOne && _this.upLoad && this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                        if( ! _this._noData && !_this._noMoreData) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '上拉加载更多...';
                            this.maxScrollY = pullUpOffset;
                        }
                    }
                },
                onScrollEnd: function () {
                    if (_this.downFresh && pullDownEl.className.match('flip')) {
                        pullDownEl.className = 'loading';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = '正在刷新...';
                        pullDownAction();	// Execute custom function (ajax call?)
                    } else if (!_this.listOne && _this.upLoad && pullUpEl.className.match('flip')) {
                        if( ! _this._noData && !_this._noMoreData) {
                            pullUpEl.className = 'loading';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = '加载中...';
                            pullUpAction();	// Execute custom function (ajax call?)
                        }
                    }
                }
            });
    }
};

/**
 * iscroll
 */
(function(window, doc){
    var m = Math,
        dummyStyle = doc.createElement('div').style,
        vendor = (function () {
            var vendors = 't,webkitT,MozT,msT,OT'.split(','),
                t,
                i = 0,
                l = vendors.length;

            for ( ; i < l; i++ ) {
                t = vendors[i] + 'ransform';
                if ( t in dummyStyle ) {
                    return vendors[i].substr(0, vendors[i].length - 1);
                }
            }

            return false;
        })(),
        cssVendor = vendor ? '-' + vendor.toLowerCase() + '-' : '',

    // Style properties
        transform = prefixStyle('transform'),
        transitionProperty = prefixStyle('transitionProperty'),
        transitionDuration = prefixStyle('transitionDuration'),
        transformOrigin = prefixStyle('transformOrigin'),
        transitionTimingFunction = prefixStyle('transitionTimingFunction'),
        transitionDelay = prefixStyle('transitionDelay'),

    // Browser capabilities
        isAndroid = (/android/gi).test(navigator.appVersion),
        isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
        isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),

        has3d = prefixStyle('perspective') in dummyStyle,
        hasTouch = 'ontouchstart' in window && !isTouchPad,
        hasTransform = vendor !== false,
        hasTransitionEnd = prefixStyle('transition') in dummyStyle,

        RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
        START_EV = hasTouch ? 'touchstart' : 'mousedown',
        MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
        END_EV = hasTouch ? 'touchend' : 'mouseup',
        CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup',
        TRNEND_EV = (function () {
            if ( vendor === false ) return false;

            var transitionEnd = {
                ''			: 'transitionend',
                'webkit'	: 'webkitTransitionEnd',
                'Moz'		: 'transitionend',
                'O'			: 'otransitionend',
                'ms'		: 'MSTransitionEnd'
            };

            return transitionEnd[vendor];
        })(),

        nextFrame = (function() {
            return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function(callback) { return setTimeout(callback, 1); };
        })(),
        cancelFrame = (function () {
            return window.cancelRequestAnimationFrame ||
            //window.webkitCancelAnimationFrame ||
            window.cancelAnimationFrame ||
            window.webkitCancelRequestAnimationFrame ||
            window.mozCancelRequestAnimationFrame ||
            window.oCancelRequestAnimationFrame ||
            window.msCancelRequestAnimationFrame ||
            clearTimeout;
        })(),

    // Helpers
        translateZ = has3d ? ' translateZ(0)' : '',

    // Constructor
        iScroll = function (el, options) {
            var that = this,
                i;

            that.wrapper = typeof el == 'object' ? el : doc.getElementById(el);
            that.wrapper.style.overflow = 'hidden';
            that.scroller = that.wrapper.children[0];

            // Default options
            that.options = {
                hScroll: true,
                vScroll: true,
                x: 0,
                y: 0,
                bounce: true,
                bounceLock: false,
                momentum: true,
                lockDirection: true,
                useTransform: true,
                useTransition: false,
                topOffset: 0,
                checkDOMChanges: false,		// Experimental
                handleClick: true,

                // Scrollbar
                hScrollbar: true,
                vScrollbar: true,
                fixedScrollbar: isAndroid,
                hideScrollbar: isIDevice,
                fadeScrollbar: isIDevice && has3d,
                scrollbarClass: '',

                // Zoom
                zoom: false,
                zoomMin: 1,
                zoomMax: 4,
                doubleTapZoom: 2,
                wheelAction: 'scroll',

                // Snap
                snap: false,
                snapThreshold: 1,

                // Events
                onRefresh: null,
                onBeforeScrollStart: function (e) {
                    e.preventDefault();
                },
                onScrollStart: null,
                onBeforeScrollMove: null,
                onScrollMove: null,
                onBeforeScrollEnd: null,
                onScrollEnd: null,
                onTouchEnd: null,
                onDestroy: null,
                onZoomStart: null,
                onZoom: null,
                onZoomEnd: null
            };

            // User defined options
            for (i in options) that.options[i] = options[i];

            // Set starting position
            that.x = that.options.x;
            that.y = that.options.y;

            // Normalize options
            that.options.useTransform = hasTransform && that.options.useTransform;
            that.options.hScrollbar = that.options.hScroll && that.options.hScrollbar;
            that.options.vScrollbar = that.options.vScroll && that.options.vScrollbar;
            that.options.zoom = that.options.useTransform && that.options.zoom;
            that.options.useTransition = hasTransitionEnd && that.options.useTransition;

            // Helpers FIX ANDROID BUG!
            // translate3d and scale doesn't work together!
            // Ignoring 3d ONLY WHEN YOU SET that.options.zoom
            if ( that.options.zoom && isAndroid ){
                translateZ = '';
            }

            // Set some default styles
            that.scroller.style[transitionProperty] = that.options.useTransform ? cssVendor + 'transform' : 'top left';
            that.scroller.style[transitionDuration] = '0';
            that.scroller.style[transformOrigin] = '0 0';
            if (that.options.useTransition) that.scroller.style[transitionTimingFunction] = 'cubic-bezier(0.33,0.66,0.66,1)';

            if (that.options.useTransform) that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px)' + translateZ;
            else that.scroller.style.cssText += ';position:absolute;top:' + that.y + 'px;left:' + that.x + 'px';

            if (that.options.useTransition) that.options.fixedScrollbar = true;

            that.refresh();

            that._bind(RESIZE_EV, window);
            that._bind(START_EV);
            if (!hasTouch) {
                if (that.options.wheelAction != 'none') {
                    that._bind('DOMMouseScroll');
                    that._bind('mousewheel');
                }
            }

            if (that.options.checkDOMChanges) that.checkDOMTime = setInterval(function () {
                that._checkDOMChanges();
            }, 500);
        };

// Prototype
    iScroll.prototype = {
        enabled: true,
        x: 0,
        y: 0,
        steps: [],
        scale: 1,
        currPageX: 0, currPageY: 0,
        pagesX: [], pagesY: [],
        aniTime: null,
        wheelZoomCount: 0,

        handleEvent: function (e) {
            var that = this;
            switch(e.type) {
                case START_EV:
                    if (!hasTouch && e.button !== 0) return;
                    that._start(e);
                    break;
                case MOVE_EV: that._move(e); break;
                case END_EV:
                case CANCEL_EV: that._end(e); break;
                case RESIZE_EV: that._resize(); break;
                case 'DOMMouseScroll': case 'mousewheel': that._wheel(e); break;
                case TRNEND_EV: that._transitionEnd(e); break;
            }
        },

        _checkDOMChanges: function () {
            if (this.moved || this.zoomed || this.animating ||
                (this.scrollerW == this.scroller.offsetWidth * this.scale && this.scrollerH == this.scroller.offsetHeight * this.scale)) return;

            this.refresh();
        },

        _scrollbar: function (dir) {
            var that = this,
                bar;

            if (!that[dir + 'Scrollbar']) {
                if (that[dir + 'ScrollbarWrapper']) {
                    if (hasTransform) that[dir + 'ScrollbarIndicator'].style[transform] = '';
                    that[dir + 'ScrollbarWrapper'].parentNode.removeChild(that[dir + 'ScrollbarWrapper']);
                    that[dir + 'ScrollbarWrapper'] = null;
                    that[dir + 'ScrollbarIndicator'] = null;
                }

                return;
            }

            if (!that[dir + 'ScrollbarWrapper']) {
                // Create the scrollbar wrapper
                bar = doc.createElement('div');

                if (that.options.scrollbarClass) bar.className = that.options.scrollbarClass + dir.toUpperCase();
                else bar.style.cssText = 'position:absolute;z-index:100;' + (dir == 'h' ? 'height:7px;bottom:1px;left:2px;right:' + (that.vScrollbar ? '7' : '2') + 'px' : 'width:7px;bottom:' + (that.hScrollbar ? '7' : '2') + 'px;top:2px;right:1px');

                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:opacity;' + cssVendor + 'transition-duration:' + (that.options.fadeScrollbar ? '350ms' : '0') + ';overflow:hidden;opacity:' + (that.options.hideScrollbar ? '0' : '1');

                that.wrapper.appendChild(bar);
                that[dir + 'ScrollbarWrapper'] = bar;

                // Create the scrollbar indicator
                bar = doc.createElement('div');
                if (!that.options.scrollbarClass) {
                    bar.style.cssText = 'position:absolute;z-index:100;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);' + cssVendor + 'background-clip:padding-box;' + cssVendor + 'box-sizing:border-box;' + (dir == 'h' ? 'height:100%' : 'width:100%') + ';' + cssVendor + 'border-radius:3px;border-radius:3px';
                }
                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:' + cssVendor + 'transform;' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1);' + cssVendor + 'transition-duration:0;' + cssVendor + 'transform: translate(0,0)' + translateZ;
                if (that.options.useTransition) bar.style.cssText += ';' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1)';

                that[dir + 'ScrollbarWrapper'].appendChild(bar);
                that[dir + 'ScrollbarIndicator'] = bar;
            }

            if (dir == 'h') {
                that.hScrollbarSize = that.hScrollbarWrapper.clientWidth;
                that.hScrollbarIndicatorSize = m.max(m.round(that.hScrollbarSize * that.hScrollbarSize / that.scrollerW), 8);
                that.hScrollbarIndicator.style.width = that.hScrollbarIndicatorSize + 'px';
                that.hScrollbarMaxScroll = that.hScrollbarSize - that.hScrollbarIndicatorSize;
                that.hScrollbarProp = that.hScrollbarMaxScroll / that.maxScrollX;
            } else {
                that.vScrollbarSize = that.vScrollbarWrapper.clientHeight;
                that.vScrollbarIndicatorSize = m.max(m.round(that.vScrollbarSize * that.vScrollbarSize / that.scrollerH), 8);
                that.vScrollbarIndicator.style.height = that.vScrollbarIndicatorSize + 'px';
                that.vScrollbarMaxScroll = that.vScrollbarSize - that.vScrollbarIndicatorSize;
                that.vScrollbarProp = that.vScrollbarMaxScroll / that.maxScrollY;
            }

            // Reset position
            that._scrollbarPos(dir, true);
        },

        _resize: function () {
            var that = this;
            setTimeout(function () { that.refresh(); }, isAndroid ? 200 : 0);
        },

        _pos: function (x, y) {
            if (this.zoomed) return;

            x = this.hScroll ? x : 0;
            y = this.vScroll ? y : 0;

            if (this.options.useTransform) {
                this.scroller.style[transform] = 'translate(' + x + 'px,' + y + 'px) scale(' + this.scale + ')' + translateZ;
            } else {
                x = m.round(x);
                y = m.round(y);
                this.scroller.style.left = x + 'px';
                this.scroller.style.top = y + 'px';
            }

            this.x = x;
            this.y = y;

            this._scrollbarPos('h');
            this._scrollbarPos('v');
        },

        _scrollbarPos: function (dir, hidden) {
            var that = this,
                pos = dir == 'h' ? that.x : that.y,
                size;

            if (!that[dir + 'Scrollbar']) return;

            pos = that[dir + 'ScrollbarProp'] * pos;

            if (pos < 0) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] + m.round(pos * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                }
                pos = 0;
            } else if (pos > that[dir + 'ScrollbarMaxScroll']) {
                if (!that.options.fixedScrollbar) {
                    size = that[dir + 'ScrollbarIndicatorSize'] - m.round((pos - that[dir + 'ScrollbarMaxScroll']) * 3);
                    if (size < 8) size = 8;
                    that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px';
                    pos = that[dir + 'ScrollbarMaxScroll'] + (that[dir + 'ScrollbarIndicatorSize'] - size);
                } else {
                    pos = that[dir + 'ScrollbarMaxScroll'];
                }
            }

            that[dir + 'ScrollbarWrapper'].style[transitionDelay] = '0';
            that[dir + 'ScrollbarWrapper'].style.opacity = hidden && that.options.hideScrollbar ? '0' : '1';
            that[dir + 'ScrollbarIndicator'].style[transform] = 'translate(' + (dir == 'h' ? pos + 'px,0)' : '0,' + pos + 'px)') + translateZ;
        },

        _start: function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                matrix, x, y,
                c1, c2;

            if (!that.enabled) return;

            if (that.options.onBeforeScrollStart) that.options.onBeforeScrollStart.call(that, e);

            if (that.options.useTransition || that.options.zoom) that._transitionTime(0);

            that.moved = false;
            that.animating = false;
            that.zoomed = false;
            that.distX = 0;
            that.distY = 0;
            that.absDistX = 0;
            that.absDistY = 0;
            that.dirX = 0;
            that.dirY = 0;

            // Gesture start
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX-e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY-e.touches[1].pageY);
                that.touchesDistStart = m.sqrt(c1 * c1 + c2 * c2);

                that.originX = m.abs(e.touches[0].pageX + e.touches[1].pageX - that.wrapperOffsetLeft * 2) / 2 - that.x;
                that.originY = m.abs(e.touches[0].pageY + e.touches[1].pageY - that.wrapperOffsetTop * 2) / 2 - that.y;

                if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
            }

            if (that.options.momentum) {
                if (that.options.useTransform) {
                    // Very lame general purpose alternative to CSSMatrix
                    matrix = getComputedStyle(that.scroller, null)[transform].replace(/[^0-9\-.,]/g, '').split(',');
                    x = +(matrix[12] || matrix[4]);
                    y = +(matrix[13] || matrix[5]);
                } else {
                    x = +getComputedStyle(that.scroller, null).left.replace(/[^0-9-]/g, '');
                    y = +getComputedStyle(that.scroller, null).top.replace(/[^0-9-]/g, '');
                }

                if (x != that.x || y != that.y) {
                    if (that.options.useTransition) that._unbind(TRNEND_EV);
                    else cancelFrame(that.aniTime);
                    that.steps = [];
                    that._pos(x, y);
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);
                }
            }

            that.absStartX = that.x;	// Needed by snap threshold
            that.absStartY = that.y;

            that.startX = that.x;
            that.startY = that.y;
            that.pointX = point.pageX;
            that.pointY = point.pageY;

            that.startTime = e.timeStamp || Date.now();

            if (that.options.onScrollStart) that.options.onScrollStart.call(that, e);

            that._bind(MOVE_EV, window);
            that._bind(END_EV, window);
            that._bind(CANCEL_EV, window);
        },

        _move: function (e) {
            var that = this,
                point = hasTouch ? e.touches[0] : e,
                deltaX = point.pageX - that.pointX,
                deltaY = point.pageY - that.pointY,
                newX = that.x + deltaX,
                newY = that.y + deltaY,
                c1, c2, scale,
                timestamp = e.timeStamp || Date.now();

            if (that.options.onBeforeScrollMove) that.options.onBeforeScrollMove.call(that, e);

            // Zoom
            if (that.options.zoom && hasTouch && e.touches.length > 1) {
                c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX);
                c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY);
                that.touchesDist = m.sqrt(c1*c1+c2*c2);

                that.zoomed = true;

                scale = 1 / that.touchesDistStart * that.touchesDist * this.scale;

                if (scale < that.options.zoomMin) scale = 0.5 * that.options.zoomMin * Math.pow(2.0, scale / that.options.zoomMin);
                else if (scale > that.options.zoomMax) scale = 2.0 * that.options.zoomMax * Math.pow(0.5, that.options.zoomMax / scale);

                that.lastScale = scale / this.scale;

                newX = this.originX - this.originX * that.lastScale + this.x;
                newY = this.originY - this.originY * that.lastScale + this.y;

                this.scroller.style[transform] = 'translate(' + newX + 'px,' + newY + 'px) scale(' + scale + ')' + translateZ;

                if (that.options.onZoom) that.options.onZoom.call(that, e);
                return;
            }

            that.pointX = point.pageX;
            that.pointY = point.pageY;

            // Slow down if outside of the boundaries
            if (newX > 0 || newX < that.maxScrollX) {
                newX = that.options.bounce ? that.x + (deltaX / 2) : newX >= 0 || that.maxScrollX >= 0 ? 0 : that.maxScrollX;
            }
            if (newY > that.minScrollY || newY < that.maxScrollY) {
                newY = that.options.bounce ? that.y + (deltaY / 2) : newY >= that.minScrollY || that.maxScrollY >= 0 ? that.minScrollY : that.maxScrollY;
            }

            that.distX += deltaX;
            that.distY += deltaY;
            that.absDistX = m.abs(that.distX);
            that.absDistY = m.abs(that.distY);

            if (that.absDistX < 6 && that.absDistY < 6) {
                return;
            }

            // Lock direction
            if (that.options.lockDirection) {
                if (that.absDistX > that.absDistY + 5) {
                    newY = that.y;
                    deltaY = 0;
                } else if (that.absDistY > that.absDistX + 5) {
                    newX = that.x;
                    deltaX = 0;
                }
            }

            that.moved = true;
            that._pos(newX, newY);
            that.dirX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
            that.dirY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

            if (timestamp - that.startTime > 300) {
                that.startTime = timestamp;
                that.startX = that.x;
                that.startY = that.y;
            }

            if (that.options.onScrollMove) that.options.onScrollMove.call(that, e);
        },

        _end: function (e) {
            if (hasTouch && e.touches.length !== 0) return;

            var that = this,
                point = hasTouch ? e.changedTouches[0] : e,
                target, ev,
                momentumX = { dist:0, time:0 },
                momentumY = { dist:0, time:0 },
                duration = (e.timeStamp || Date.now()) - that.startTime,
                newPosX = that.x,
                newPosY = that.y,
                distX, distY,
                newDuration,
                snap,
                scale;

            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (that.options.onBeforeScrollEnd) that.options.onBeforeScrollEnd.call(that, e);

            if (that.zoomed) {
                scale = that.scale * that.lastScale;
                scale = Math.max(that.options.zoomMin, scale);
                scale = Math.min(that.options.zoomMax, scale);
                that.lastScale = scale / that.scale;
                that.scale = scale;

                that.x = that.originX - that.originX * that.lastScale + that.x;
                that.y = that.originY - that.originY * that.lastScale + that.y;

                that.scroller.style[transitionDuration] = '200ms';
                that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + that.scale + ')' + translateZ;

                that.zoomed = false;
                that.refresh();

                if (that.options.onZoomEnd) that.options.onZoomEnd.call(that, e);
                return;
            }

            if (!that.moved) {
                if (hasTouch) {
                    if (that.doubleTapTimer && that.options.zoom) {
                        // Double tapped
                        clearTimeout(that.doubleTapTimer);
                        that.doubleTapTimer = null;
                        if (that.options.onZoomStart) that.options.onZoomStart.call(that, e);
                        that.zoom(that.pointX, that.pointY, that.scale == 1 ? that.options.doubleTapZoom : 1);
                        if (that.options.onZoomEnd) {
                            setTimeout(function() {
                                that.options.onZoomEnd.call(that, e);
                            }, 200); // 200 is default zoom duration
                        }
                    } else if (this.options.handleClick) {
                        that.doubleTapTimer = setTimeout(function () {
                            that.doubleTapTimer = null;

                            // Find the last touched element
                            target = point.target;
                            while (target.nodeType != 1) target = target.parentNode;

                            if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') {
                                ev = doc.createEvent('MouseEvents');
                                ev.initMouseEvent('click', true, true, e.view, 1,
                                    point.screenX, point.screenY, point.clientX, point.clientY,
                                    e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
                                    0, null);
                                ev._fake = true;
                                target.dispatchEvent(ev);
                            }
                        }, that.options.zoom ? 250 : 0);
                    }
                }

                that._resetPos(400);

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            if (duration < 300 && that.options.momentum) {
                momentumX = newPosX ? that._momentum(newPosX - that.startX, duration, -that.x, that.scrollerW - that.wrapperW + that.x, that.options.bounce ? that.wrapperW : 0) : momentumX;
                momentumY = newPosY ? that._momentum(newPosY - that.startY, duration, -that.y, (that.maxScrollY < 0 ? that.scrollerH - that.wrapperH + that.y - that.minScrollY : 0), that.options.bounce ? that.wrapperH : 0) : momentumY;

                newPosX = that.x + momentumX.dist;
                newPosY = that.y + momentumY.dist;

                if ((that.x > 0 && newPosX > 0) || (that.x < that.maxScrollX && newPosX < that.maxScrollX)) momentumX = { dist:0, time:0 };
                if ((that.y > that.minScrollY && newPosY > that.minScrollY) || (that.y < that.maxScrollY && newPosY < that.maxScrollY)) momentumY = { dist:0, time:0 };
            }

            if (momentumX.dist || momentumY.dist) {
                newDuration = m.max(m.max(momentumX.time, momentumY.time), 10);

                // Do we need to snap?
                if (that.options.snap) {
                    distX = newPosX - that.absStartX;
                    distY = newPosY - that.absStartY;
                    if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) { that.scrollTo(that.absStartX, that.absStartY, 200); }
                    else {
                        snap = that._snap(newPosX, newPosY);
                        newPosX = snap.x;
                        newPosY = snap.y;
                        newDuration = m.max(snap.time, newDuration);
                    }
                }

                that.scrollTo(m.round(newPosX), m.round(newPosY), newDuration);

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            // Do we need to snap?
            if (that.options.snap) {
                distX = newPosX - that.absStartX;
                distY = newPosY - that.absStartY;
                if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) that.scrollTo(that.absStartX, that.absStartY, 200);
                else {
                    snap = that._snap(that.x, that.y);
                    if (snap.x != that.x || snap.y != that.y) that.scrollTo(snap.x, snap.y, snap.time);
                }

                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
                return;
            }

            that._resetPos(200);
            if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
        },

        _resetPos: function (time) {
            var that = this,
                resetX = that.x >= 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x,
                resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            if (resetX == that.x && resetY == that.y) {
                if (that.moved) {
                    that.moved = false;
                    if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);		// Execute custom code on scroll end
                }

                if (that.hScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.hScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.hScrollbarWrapper.style.opacity = '0';
                }
                if (that.vScrollbar && that.options.hideScrollbar) {
                    if (vendor == 'webkit') that.vScrollbarWrapper.style[transitionDelay] = '300ms';
                    that.vScrollbarWrapper.style.opacity = '0';
                }

                return;
            }

            that.scrollTo(resetX, resetY, time || 0);
        },

        _wheel: function (e) {
            var that = this,
                wheelDeltaX, wheelDeltaY,
                deltaX, deltaY,
                deltaScale;

            if ('wheelDeltaX' in e) {
                wheelDeltaX = e.wheelDeltaX / 12;
                wheelDeltaY = e.wheelDeltaY / 12;
            } else if('wheelDelta' in e) {
                wheelDeltaX = wheelDeltaY = e.wheelDelta / 12;
            } else if ('detail' in e) {
                wheelDeltaX = wheelDeltaY = -e.detail * 3;
            } else {
                return;
            }

            if (that.options.wheelAction == 'zoom') {
                deltaScale = that.scale * Math.pow(2, 1/3 * (wheelDeltaY ? wheelDeltaY / Math.abs(wheelDeltaY) : 0));
                if (deltaScale < that.options.zoomMin) deltaScale = that.options.zoomMin;
                if (deltaScale > that.options.zoomMax) deltaScale = that.options.zoomMax;

                if (deltaScale != that.scale) {
                    if (!that.wheelZoomCount && that.options.onZoomStart) that.options.onZoomStart.call(that, e);
                    that.wheelZoomCount++;

                    that.zoom(e.pageX, e.pageY, deltaScale, 400);

                    setTimeout(function() {
                        that.wheelZoomCount--;
                        if (!that.wheelZoomCount && that.options.onZoomEnd) that.options.onZoomEnd.call(that, e);
                    }, 400);
                }

                return;
            }

            deltaX = that.x + wheelDeltaX;
            deltaY = that.y + wheelDeltaY;

            if (deltaX > 0) deltaX = 0;
            else if (deltaX < that.maxScrollX) deltaX = that.maxScrollX;

            if (deltaY > that.minScrollY) deltaY = that.minScrollY;
            else if (deltaY < that.maxScrollY) deltaY = that.maxScrollY;

            if (that.maxScrollY < 0) {
                that.scrollTo(deltaX, deltaY, 0);
            }
        },

        _transitionEnd: function (e) {
            var that = this;

            if (e.target != that.scroller) return;

            that._unbind(TRNEND_EV);

            that._startAni();
        },


        /**
         *
         * Utilities
         *
         */
        _startAni: function () {
            var that = this,
                startX = that.x, startY = that.y,
                startTime = Date.now(),
                step, easeOut,
                animate;

            if (that.animating) return;

            if (!that.steps.length) {
                that._resetPos(400);
                return;
            }

            step = that.steps.shift();

            if (step.x == startX && step.y == startY) step.time = 0;

            that.animating = true;
            that.moved = true;

            if (that.options.useTransition) {
                that._transitionTime(step.time);
                that._pos(step.x, step.y);
                that.animating = false;
                if (step.time) that._bind(TRNEND_EV);
                else that._resetPos(0);
                return;
            }

            animate = function () {
                var now = Date.now(),
                    newX, newY;

                if (now >= startTime + step.time) {
                    that._pos(step.x, step.y);
                    that.animating = false;
                    if (that.options.onAnimationEnd) that.options.onAnimationEnd.call(that);			// Execute custom code on animation end
                    that._startAni();
                    return;
                }

                now = (now - startTime) / step.time - 1;
                easeOut = m.sqrt(1 - now * now);
                newX = (step.x - startX) * easeOut + startX;
                newY = (step.y - startY) * easeOut + startY;
                that._pos(newX, newY);
                if (that.animating) that.aniTime = nextFrame(animate);
            };

            animate();
        },

        _transitionTime: function (time) {
            time += 'ms';
            this.scroller.style[transitionDuration] = time;
            if (this.hScrollbar) this.hScrollbarIndicator.style[transitionDuration] = time;
            if (this.vScrollbar) this.vScrollbarIndicator.style[transitionDuration] = time;
        },

        _momentum: function (dist, time, maxDistUpper, maxDistLower, size) {
            var deceleration = 0.0006,
                speed = m.abs(dist) / time,
                newDist = (speed * speed) / (2 * deceleration),
                newTime = 0, outsideDist = 0;

            // Proportinally reduce speed if we are outside of the boundaries
            if (dist > 0 && newDist > maxDistUpper) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistUpper = maxDistUpper + outsideDist;
                speed = speed * maxDistUpper / newDist;
                newDist = maxDistUpper;
            } else if (dist < 0 && newDist > maxDistLower) {
                outsideDist = size / (6 / (newDist / speed * deceleration));
                maxDistLower = maxDistLower + outsideDist;
                speed = speed * maxDistLower / newDist;
                newDist = maxDistLower;
            }

            newDist = newDist * (dist < 0 ? -1 : 1);
            newTime = speed / deceleration;

            return { dist: newDist, time: m.round(newTime) };
        },

        _offset: function (el) {
            var left = -el.offsetLeft,
                top = -el.offsetTop;

            while (el = el.offsetParent) {
                left -= el.offsetLeft;
                top -= el.offsetTop;
            }

            if (el != this.wrapper) {
                left *= this.scale;
                top *= this.scale;
            }

            return { left: left, top: top };
        },

        _snap: function (x, y) {
            var that = this,
                i, l,
                page, time,
                sizeX, sizeY;

            // Check page X
            page = that.pagesX.length - 1;
            for (i=0, l=that.pagesX.length; i<l; i++) {
                if (x >= that.pagesX[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageX && page > 0 && that.dirX < 0) page--;
            x = that.pagesX[page];
            sizeX = m.abs(x - that.pagesX[that.currPageX]);
            sizeX = sizeX ? m.abs(that.x - x) / sizeX * 500 : 0;
            that.currPageX = page;

            // Check page Y
            page = that.pagesY.length-1;
            for (i=0; i<page; i++) {
                if (y >= that.pagesY[i]) {
                    page = i;
                    break;
                }
            }
            if (page == that.currPageY && page > 0 && that.dirY < 0) page--;
            y = that.pagesY[page];
            sizeY = m.abs(y - that.pagesY[that.currPageY]);
            sizeY = sizeY ? m.abs(that.y - y) / sizeY * 500 : 0;
            that.currPageY = page;

            // Snap with constant speed (proportional duration)
            time = m.round(m.max(sizeX, sizeY)) || 200;

            return { x: x, y: y, time: time };
        },

        _bind: function (type, el, bubble) {
            (el || this.scroller).addEventListener(type, this, !!bubble);
        },

        _unbind: function (type, el, bubble) {
            (el || this.scroller).removeEventListener(type, this, !!bubble);
        },


        /**
         *
         * Public methods
         *
         */
        destroy: function () {
            var that = this;

            that.scroller.style[transform] = '';

            // Remove the scrollbars
            that.hScrollbar = false;
            that.vScrollbar = false;
            that._scrollbar('h');
            that._scrollbar('v');

            // Remove the event listeners
            that._unbind(RESIZE_EV, window);
            that._unbind(START_EV);
            that._unbind(MOVE_EV, window);
            that._unbind(END_EV, window);
            that._unbind(CANCEL_EV, window);

            if (!that.options.hasTouch) {
                that._unbind('DOMMouseScroll');
                that._unbind('mousewheel');
            }

            if (that.options.useTransition) that._unbind(TRNEND_EV);

            if (that.options.checkDOMChanges) clearInterval(that.checkDOMTime);

            if (that.options.onDestroy) that.options.onDestroy.call(that);
        },

        refresh: function () {
            var that = this,
                offset,
                i, l,
                els,
                pos = 0,
                page = 0;

            if (that.scale < that.options.zoomMin) that.scale = that.options.zoomMin;
            that.wrapperW = that.wrapper.clientWidth || 1;
            that.wrapperH = that.wrapper.clientHeight || 1;

            that.minScrollY = -that.options.topOffset || 0;
            that.scrollerW = m.round(that.scroller.offsetWidth * that.scale);
            that.scrollerH = m.round((that.scroller.offsetHeight + that.minScrollY) * that.scale);
            that.maxScrollX = that.wrapperW - that.scrollerW;
            that.maxScrollY = that.wrapperH - that.scrollerH + that.minScrollY;
            that.dirX = 0;
            that.dirY = 0;

            if (that.options.onRefresh) that.options.onRefresh.call(that);

            that.hScroll = that.options.hScroll && that.maxScrollX < 0;
            that.vScroll = that.options.vScroll && (!that.options.bounceLock && !that.hScroll || that.scrollerH > that.wrapperH);

            that.hScrollbar = that.hScroll && that.options.hScrollbar;
            that.vScrollbar = that.vScroll && that.options.vScrollbar && that.scrollerH > that.wrapperH;

            offset = that._offset(that.wrapper);
            that.wrapperOffsetLeft = -offset.left;
            that.wrapperOffsetTop = -offset.top;

            // Prepare snap
            if (typeof that.options.snap == 'string') {
                that.pagesX = [];
                that.pagesY = [];
                els = that.scroller.querySelectorAll(that.options.snap);
                for (i=0, l=els.length; i<l; i++) {
                    pos = that._offset(els[i]);
                    pos.left += that.wrapperOffsetLeft;
                    pos.top += that.wrapperOffsetTop;
                    that.pagesX[i] = pos.left < that.maxScrollX ? that.maxScrollX : pos.left * that.scale;
                    that.pagesY[i] = pos.top < that.maxScrollY ? that.maxScrollY : pos.top * that.scale;
                }
            } else if (that.options.snap) {
                that.pagesX = [];
                while (pos >= that.maxScrollX) {
                    that.pagesX[page] = pos;
                    pos = pos - that.wrapperW;
                    page++;
                }
                if (that.maxScrollX%that.wrapperW) that.pagesX[that.pagesX.length] = that.maxScrollX - that.pagesX[that.pagesX.length-1] + that.pagesX[that.pagesX.length-1];

                pos = 0;
                page = 0;
                that.pagesY = [];
                while (pos >= that.maxScrollY) {
                    that.pagesY[page] = pos;
                    pos = pos - that.wrapperH;
                    page++;
                }
                if (that.maxScrollY%that.wrapperH) that.pagesY[that.pagesY.length] = that.maxScrollY - that.pagesY[that.pagesY.length-1] + that.pagesY[that.pagesY.length-1];
            }

            // Prepare the scrollbars
            that._scrollbar('h');
            that._scrollbar('v');

            if (!that.zoomed) {
                that.scroller.style[transitionDuration] = '0';
                that._resetPos(400);
            }
        },

        scrollTo: function (x, y, time, relative) {
            var that = this,
                step = x,
                i, l;

            that.stop();

            if (!step.length) step = [{ x: x, y: y, time: time, relative: relative }];

            for (i=0, l=step.length; i<l; i++) {
                if (step[i].relative) { step[i].x = that.x - step[i].x; step[i].y = that.y - step[i].y; }
                that.steps.push({ x: step[i].x, y: step[i].y, time: step[i].time || 0 });
            }

            that._startAni();
        },

        scrollToElement: function (el, time) {
            var that = this, pos;
            el = el.nodeType ? el : that.scroller.querySelector(el);
            if (!el) return;

            pos = that._offset(el);
            pos.left += that.wrapperOffsetLeft;
            pos.top += that.wrapperOffsetTop;

            pos.left = pos.left > 0 ? 0 : pos.left < that.maxScrollX ? that.maxScrollX : pos.left;
            pos.top = pos.top > that.minScrollY ? that.minScrollY : pos.top < that.maxScrollY ? that.maxScrollY : pos.top;
            time = time === undefined ? m.max(m.abs(pos.left)*2, m.abs(pos.top)*2) : time;

            that.scrollTo(pos.left, pos.top, time);
        },

        scrollToPage: function (pageX, pageY, time) {
            var that = this, x, y;

            time = time === undefined ? 400 : time;

            if (that.options.onScrollStart) that.options.onScrollStart.call(that);

            if (that.options.snap) {
                pageX = pageX == 'next' ? that.currPageX+1 : pageX == 'prev' ? that.currPageX-1 : pageX;
                pageY = pageY == 'next' ? that.currPageY+1 : pageY == 'prev' ? that.currPageY-1 : pageY;

                pageX = pageX < 0 ? 0 : pageX > that.pagesX.length-1 ? that.pagesX.length-1 : pageX;
                pageY = pageY < 0 ? 0 : pageY > that.pagesY.length-1 ? that.pagesY.length-1 : pageY;

                that.currPageX = pageX;
                that.currPageY = pageY;
                x = that.pagesX[pageX];
                y = that.pagesY[pageY];
            } else {
                x = -that.wrapperW * pageX;
                y = -that.wrapperH * pageY;
                if (x < that.maxScrollX) x = that.maxScrollX;
                if (y < that.maxScrollY) y = that.maxScrollY;
            }

            that.scrollTo(x, y, time);
        },

        disable: function () {
            this.stop();
            this._resetPos(0);
            this.enabled = false;

            // If disabled after touchstart we make sure that there are no left over events
            this._unbind(MOVE_EV, window);
            this._unbind(END_EV, window);
            this._unbind(CANCEL_EV, window);
        },

        enable: function () {
            this.enabled = true;
        },

        stop: function () {
            if (this.options.useTransition) this._unbind(TRNEND_EV);
            else cancelFrame(this.aniTime);
            this.steps = [];
            this.moved = false;
            this.animating = false;
        },

        zoom: function (x, y, scale, time) {
            var that = this,
                relScale = scale / that.scale;

            if (!that.options.useTransform) return;

            that.zoomed = true;
            time = time === undefined ? 200 : time;
            x = x - that.wrapperOffsetLeft - that.x;
            y = y - that.wrapperOffsetTop - that.y;
            that.x = x - x * relScale + that.x;
            that.y = y - y * relScale + that.y;

            that.scale = scale;
            that.refresh();

            that.x = that.x > 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x;
            that.y = that.y > that.minScrollY ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y;

            that.scroller.style[transitionDuration] = time + 'ms';
            that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + scale + ')' + translateZ;
            that.zoomed = false;
        },

        isReady: function () {
            return !this.moved && !this.zoomed && !this.animating;
        }
    };

    function prefixStyle (style) {
        if ( vendor === '' ) return style;

        style = style.charAt(0).toUpperCase() + style.substr(1);
        return vendor + style;
    }

    dummyStyle = null;	// for the sake of it

    if (typeof exports !== 'undefined') exports.iScroll = iScroll;
    else window.iScroll = iScroll;

})(window, document);