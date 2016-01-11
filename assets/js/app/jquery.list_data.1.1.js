/**
 * Created by Administrator on 2015/12/18.
 */
;
(function($){
    /**
     * 前端数据循环
     * @param option
     */
    var list_data = function(option){
        //默认配置
        this.option = {
            id           : 'list-data',
            list_one     : false, //是否单循环  否则多循环
            page_id      : 1,      //如果分页 默认第一页
            page_size    : false, //分页的每页数量 默认不分页
            data         : '',     //数据来源 json 或链接
            param        : {},     //数据来源参数{}
            value_func   : {},     //循环中用到的数据处理函数 {'键名':function(键值){ return 处理后的键值;}
            list_func    : {},     //循环中用到的单条处理函数 {'键名':function(键值){ return 处理后的键值;}
            callback     : false, //全部处理完后的回调函数
            is_scroll    : false, //是否启用滑动
            scroll_offset_height    : 0, //需扣掉的相对高度
            down_refresh : false,     //是否 下拉刷新
            down_refresh_offset : 0, //是否 下拉刷新 拉动距离
            up_load      : false, //上拉 加载更多
            up_load_offset: 0,     //上拉 加载更多 拉动距离
            show_loading : false, //是否显示加载中效果
            unique_key   : false, //数据中唯一键名
            event_type   : 'click',//分页触发事件 默认click
            loading_delay: 0,      //加载中 延迟时间
            list_fadein  : 0,      //单条延迟加载显示
            list_fadein_page_id  : 1,  //单条延迟加载显示 第几页开始
            append       : true,
            btn          : false  //ajax 触发按钮选择器字符
        };
        this.option = $.extend(this.option,option || {});

        if(typeof this.option.id == 'string')this.option.id = $('#'+this.option.id);
        if(!this.option.id) return;

        this.option.up_load = this.option.is_scroll && this.option.page_size;

        //html数据
        this.html_data = {
            html         : '',       //循环的html代码
            no_data      : false,   //没有数据的显示html代码
            no_more      : false,   //没有更多数据的 显示html代码
            loading      : false,   //加载中的现实html代码
            more_button  : false,   //更多数据的按钮代码
            pull_down    : false,   //下拉的 html
            pull_up      : false,   //上拉的 html
            loading_img  : 'data:image/gif;base64,R0lGODlhIAAgALMAAP///7Ozs/v7+9bW1uHh4fLy8rq6uoGBgTQ0NAEBARsbG8TExJeXl/39/VRUVAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFBQAAACwAAAAAIAAgAAAE5xDISSlLrOrNp0pKNRCdFhxVolJLEJQUoSgOpSYT4RowNSsvyW1icA16k8MMMRkCBjskBTFDAZyuAEkqCfxIQ2hgQRFvAQEEIjNxVDW6XNE4YagRjuBCwe60smQUDnd4Rz1ZAQZnFAGDd0hihh12CEE9kjAEVlycXIg7BAsMB6SlnJ87paqbSKiKoqusnbMdmDC2tXQlkUhziYtyWTxIfy6BE8WJt5YEvpJivxNaGmLHT0VnOgGYf0dZXS7APdpB309RnHOG5gDqXGLDaC457D1zZ/V/nmOM82XiHQjYKhKP1oZmADdEAAAh+QQFBQAAACwAAAAAGAAXAAAEchDISasKNeuJFKoHs4mUYlJIkmjIV54Soypsa0wmLSnqoTEtBw52mG0AjhYpBxioEqRNy8V0qFzNw+GGwlJki4lBqx1IBgjMkRIghwjrzcDti2/Gh7D9qN774wQGAYOEfwCChIV/gYmDho+QkZKTR3p7EQAh+QQFBQAAACwBAAAAHQAOAAAEchDISWdANesNHHJZwE2DUSEo5SjKKB2HOKGYFLD1CB/DnEoIlkti2PlyuKGEATMBaAACSyGbEDYD4zN1YIEmh0SCQQgYehNmTNNaKsQJXmBuuEYPi9ECAU/UFnNzeUp9VBQEBoFOLmFxWHNoQw6RWEocEQAh+QQFBQAAACwHAAAAGQARAAAEaRDICdZZNOvNDsvfBhBDdpwZgohBgE3nQaki0AYEjEqOGmqDlkEnAzBUjhrA0CoBYhLVSkm4SaAAWkahCFAWTU0A4RxzFWJnzXFWJJWb9pTihRu5dvghl+/7NQmBggo/fYKHCX8AiAmEEQAh+QQFBQAAACwOAAAAEgAYAAAEZXCwAaq9ODAMDOUAI17McYDhWA3mCYpb1RooXBktmsbt944BU6zCQCBQiwPB4jAihiCK86irTB20qvWp7Xq/FYV4TNWNz4oqWoEIgL0HX/eQSLi69boCikTkE2VVDAp5d1p0CW4RACH5BAUFAAAALA4AAAASAB4AAASAkBgCqr3YBIMXvkEIMsxXhcFFpiZqBaTXisBClibgAnd+ijYGq2I4HAamwXBgNHJ8BEbzgPNNjz7LwpnFDLvgLGJMdnw/5DRCrHaE3xbKm6FQwOt1xDnpwCvcJgcJMgEIeCYOCQlrF4YmBIoJVV2CCXZvCooHbwGRcAiKcmFUJhEAIfkEBQUAAAAsDwABABEAHwAABHsQyAkGoRivELInnOFlBjeM1BCiFBdcbMUtKQdTN0CUJru5NJQrYMh5VIFTTKJcOj2HqJQRhEqvqGuU+uw6AwgEwxkOO55lxIihoDjKY8pBoThPxmpAYi+hKzoeewkTdHkZghMIdCOIhIuHfBMOjxiNLR4KCW1ODAlxSxEAIfkEBQUAAAAsCAAOABgAEgAABGwQyEkrCDgbYvvMoOF5ILaNaIoGKroch9hacD3MFMHUBzMHiBtgwJMBFolDB4GoGGBCACKRcAAUWAmzOWJQExysQsJgWj0KqvKalTiYPhp1LBFTtp10Is6mT5gdVFx1bRN8FTsVCAqDOB9+KhEAIfkEBQUAAAAsAgASAB0ADgAABHgQyEmrBePS4bQdQZBdR5IcHmWEgUFQgWKaKbWwwSIhc4LonsXhBSCsQoOSScGQDJiWwOHQnAxWBIYJNXEoFCiEWDI9jCzESey7GwMM5doEwW4jJoypQQ743u1WcTV0CgFzbhJ5XClfHYd/EwZnHoYVDgiOfHKQNREAIfkEBQUAAAAsAAAPABkAEQAABGeQqUQruDjrW3vaYCZ5X2ie6EkcKaooTAsi7ytnTq046BBsNcTvItz4AotMwKZBIC6H6CVAJaCcT0CUBTgaTg5nTCu9GKiDEMPJg5YBBOpwlnVzLwtqyKnZagZWahoMB2M3GgsHSRsRACH5BAUFAAAALAEACAARABgAAARcMKR0gL34npkUyyCAcAmyhBijkGi2UW02VHFt33iu7yiDIDaD4/erEYGDlu/nuBAOJ9Dvc2EcDgFAYIuaXS3bbOh6MIC5IAP5Eh5fk2exC4tpgwZyiyFgvhEMBBEAIfkEBQUAAAAsAAACAA4AHQAABHMQyAnYoViSlFDGXBJ808Ep5KRwV8qEg+pRCOeoioKMwJK0Ekcu54h9AoghKgXIMZgAApQZcCCu2Ax2O6NUud2pmJcyHA4L0uDM/ljYDCnGfGakJQE5YH0wUBYBAUYfBIFkHwaBgxkDgX5lgXpHAXcpBIsRADs='
        };
        //其他数据
        this.temp_data = {
            ajax_fail         : false,  //ajax失败标识
            ajax_no_data      : false,  //ajax无数据标识
            ajax_no_more      : false,  //ajax无更多数据标识
            ajax_data         : '',     //ajax数据
            list_fadein_time  : 0,      //单条渐隐中时间
            reset             : false, // iscroll是否重置了窗口大小
            scroll_object     : '',     // scroll对象
            msg_no_data       : '暂无相关信息!',
            msg_no_more       : '没有更多信息了!',
            msg_loading       : '加载中...',
            msg_down_refresh_default  : '下拉刷新..',
            msg_down_refresh_able     : '松手开始更新...',
            msg_down_refresh_loading  : '正在刷新..',
            msg_up_load_default       : '上拉加载更多...',
            msg_up_load_able          : '松手开始更新...',
            msg_up_load_loading       : '加载中..',
            is_down_refresh     : false,
            links               : '',    //ajax请求回来的链接html代码
            page_num_max        : false,//最大页码
            ajax_over           : true //用于callback中的执行事件 的执行
        };

        if(this.option.show_loading == 'img')this.temp_data.msg_loading = '<img src="'+this.html_data.loading_img+'" style="width: 15px;height: 15px;">';
        if(this.option.show_loading == 'img-msg')this.temp_data.msg_loading = '<img src="'+this.html_data.loading_img+'" style="width: 15px;height: 15px;margin-right: 10px;">加载中...';

        var that = this;
        /**
         * 初始化 iscroll html 结构的方法
         * @param obj
         */
        var scroll_html_init = function(){
            var html = that.option.id.clone();
            var scroll_html = document.createElement('div');
            scroll_html.setAttribute('id',that.option.id.attr('id')+'-scroller');

            if(that.option.down_refresh)$(scroll_html).append('<div id="pullDown" style="text-align:center;visibility:hidden;padding-bottom:10px;"><span class="pullDownIcon"></span><span class="pullDownLabel">'+that.temp_data.msg_down_refresh_default+'</span></div>');
            $(scroll_html).append(html[0]);
            if(that.option.page_size>0 && that.option.up_load)$(scroll_html).append('<div id="pullUp" style="text-align:center;visibility:hidden;padding-top:10px;"><span class="pullUpIcon"></span><span class="pullUpLabel">'+that.temp_data.msg_up_load_default+'</span></div>');

            that.option.id.before('<div id="'+(that.option.id.attr('id')+'-warp')+'"></div>').remove();
            $('#'+(that.option.id.attr('id')+'-warp')).html(scroll_html).find('#'+that.option.id.attr('id'));
            that.option.id = $('#'+(that.option.id.attr('id')+'-warp')).find('#'+that.option.id.attr('id'));
        };

        //验证是单循环还是多循环 处理html数据结构 #no-data #no-more #loading more-button .no-data-msg
        if( this.option.list_one){
            this.option.is_scroll?scroll_html_init():this.html_data.loading = this.option.id.find('#loading').length?this.option.id.find('#loading').clone().remove():$('<img src="'+this.html_data.loading_img+'" style="width: 15px;height: 15px;">');
        }else{
            //在 非滑动时 获取 循环主题 没有数据 没有更多数据 加载中时 更多的button 的 html数据
            if( ! this.option.is_scroll){
                this.html_data.no_data = this.option.id.find('#no-data').length?this.option.id.find('#no-data').clone():false;
                this.html_data.no_more = this.option.id.find('#no-more').length?this.option.id.find('#no-more').clone():(this.option.id.find('#no-data').length?this.option.id.find('#no-data').clone():false);
                if(this.html_data.no_more && this.html_data.no_more.attr('id')=='no-data')this.html_data.no_more.attr('id','no-more').find('.no-data-msg').html(this.temp_data.msg_no_more);
                this.html_data.loading = this.option.id.find('#loading').length?this.option.id.find('#loading').clone():(this.option.id.find('#no-data').length?this.option.id.find('#no-data').clone():false);
                if(this.html_data.loading && this.html_data.loading.attr('id')=='no-data')this.html_data.loading.attr('id','loading').find('.no-data-msg').html(this.temp_data.msg_loading);
                this.html_data.more_button = this.option.id.find('#more-button').length?this.option.id.find('#more-button').clone():false;
                this.option.id.find('#no-data').remove().find('#no-more').remove().find('#loading').clone().find('#more-button').remove();
            }
            this.html_data.html = this.option.id.clone();
            if( ! this.option.is_scroll){
                this.html_data.no_data = this.html_data.no_data || this.html_data.html.find(':first').clone().html(this.temp_data.msg_no_data).css({'text-align':'center','padding':'10px 0'}).attr('id','no-data');
                this.html_data.no_more = this.html_data.no_more || this.html_data.no_data.clone().html(this.temp_data.msg_no_more).css({'text-align':'center','padding':'10px 0'}).attr('id','no-more');
                this.html_data.loading = this.html_data.loading || this.html_data.no_data.clone().html(this.temp_data.msg_loading).css({'text-align':'center','padding':'10px 0'}).attr('id','loading');
            }
            //启用滑动时 处理滑动的html结构
            if(this.option.is_scroll){
                scroll_html_init();
                this.html_data.loading = $('<p id="loading" style="text-align:center;padding-top:10px;">'+this.temp_data.msg_up_load_loading+'</p>');
            }
            this.option.id.html('').css('visibility','visible');
        }
    };
    list_data.prototype = {
        'init':function(url,params,callback){
            if(!this.option.id) return;
            this.option.data = url || this.option.data;
            this.option.param = params || this.option.param;
            this.option.callback = callback || this.option.callback;
            this.option.page_id = 1;
            this.option.id.find('#no-data').remove();
            this._init();
        },
        '_loading':function(type,flag){
            if( !this.option.show_loading) return;

            if(this.option.list_one){
                this.option.id.find('.list-value').html(type == 'error'?'数据加载失败!':this.html_data.loading);
            }else{
                if(this.option.is_scroll){
                    switch (type){
                        case 'no-more':
                            if(this.option.up_load){
                                if(this.option.page_id == 1){
                                    $("#pullUp").css('visibility','hidden')
                                }else{
                                    if(this.option.list_fadein > 0  && this.temp_data.list_fadein_time > 0){
                                        $("#pullUp").css('visibility','hidden');
                                        var list_fadein_t1 = setTimeout(function(){
                                            clearTimeout(list_fadein_t1);
                                            $("#pullUp").css('visibility','visible');
                                        },this.temp_data.list_fadein_time);
                                    }else{
                                        $("#pullUp").css('visibility','visible');
                                    }
                                }
                            }
                            break;
                        case 'no-data':
                            if(this.option.up_load){
                                if(this.option.list_fadein > 0  && this.temp_data.list_fadein_time > 0){
                                    var list_fadein_t3 = setTimeout(function(){
                                        clearTimeout(list_fadein_t3);
                                        $("#pullUp").css('visibility','visible');
                                    },this.temp_data.list_fadein_time);
                                }else{
                                    $("#pullUp").css('visibility','visible');
                                }
                            }else{
                                this.option.id.after(this.html_data.loading.clone().html(this.temp_data.msg_no_data)[0]);
                            }
                            break;
                        case 'loading':
                            if(this.option.up_load){
                                if( ! this.temp_data.is_down_refresh){
                                    if(flag){
                                        if(this.option.list_fadein > 0  && this.temp_data.list_fadein_time > 0){
                                            var list_fadein_t2 = setTimeout(function(){
                                                clearTimeout(list_fadein_t2);
                                                $("#pullUp").css('visibility','visible');
                                            },this.temp_data.list_fadein_time);
                                        }else{
                                            $("#pullUp").css('visibility','visible').find('.pullUpLabel').html(this.temp_data.msg_up_load_loading);
                                        }
                                    }else{
                                        $("#pullUp").css('visibility','hidden');
                                    }
                                }
                            }else{
                                if(flag){
                                    this.option.id.after(this.html_data.loading[0]);
                                }else{
                                    this.option.id.siblings('#loading').remove();
                                }
                            }
                            break;
                        case 'error':
                            //this.option.id.after(this.html_data.loading.clone().html('数据加载失败!')[0]);
                            break;
                        default:
                    }
                }else{
                    switch (type){
                        case 'no-data':
                            this.option.id.append(this.html_data.no_data[0]);
                            break;
                        case 'no-more':
                            if(this.option.page_id > 1){
                                if(this.option.list_fadein > 0  && this.temp_data.list_fadein_time > 0){
                                    var that = this;
                                    var list_fadein_t = setTimeout(function(){
                                        clearTimeout(list_fadein_t);
                                        that.option.id.append(that.html_data.no_more[0]);
                                    },this.temp_data.list_fadein_time);
                                }else{
                                    this.option.id.append(this.html_data.no_more[0]);
                                }
                            }
                            break;
                        case 'loading':
                            if(flag){
                                this.option.id.append(this.html_data.loading[0]);
                            }else{
                                this.option.id.find('#loading').remove();
                            }
                            break;
                        case 'error':
                            //this.option.id.append(this.html_data.no_data.clone().html('数据加载失败!')[0]);
                            break;
                        default :
                    }
                }
            }
        },
        '_event':function(type){
            var that = this;
            //是否开启滑动
            if( ! this.option.is_scroll){
                if(this.option.event_type != false && this.option.page_size > 0){
                    //验证配置的事件类型
                    if(this.option.event_type == 'scroll'){
                        if(type){
                            if(this.option.list_fadein > 0  && this.temp_data.list_fadein_time > 0){
                                var list_fadein_t = setTimeout(function(){
                                    clearTimeout(list_fadein_t);
                                    //滑动到底部时的加载事件处理
                                    $(window).bind('scroll',function(){
                                        if($(this).scrollTop() + $(this).height() >= $(document).height()-5)that._init();
                                    });
                                },this.temp_data.list_fadein_time);
                            }else{
                                //滑动到底部时的加载事件处理
                                $(window).bind('scroll',function(){
                                    if($(this).scrollTop() + $(this).height() >= $(document).height()-5)that._init();
                                });
                            }
                        }else{
                            $(window).unbind('scroll');
                        }
                    }else{
                        //是否有加载更多的按钮
                        if(this.html_data.more_button){
                            if(type){
                                //如果有渐隐效果 延时加载更多按钮和时间绑定
                                if(this.option.list_fadein > 0 && this.temp_data.list_fadein_time > 0){
                                    var list_fadein_t = setTimeout(function(){
                                        clearTimeout(list_fadein_t);
                                        that.option.id.append(that.html_data.more_button[0]);
                                        that.option.id.find('#more-button').bind(that.option.event_type,function(){
                                            that._init();
                                        });
                                    },this.temp_data.list_fadein_time);
                                }else{
                                    this.option.id.append(this.html_data.more_button[0]);
                                    this.option.id.find('#more-button').bind(this.option.event_type,function(){
                                        that._init();
                                    });
                                }
                            }else{
                                this.option.id.find('#more-button').unbind(this.option.event_type).remove();
                            }
                        }
                    }
                }
            }else{

            }
        },
        '_init':function(){
            if( !this.option.list_one && !this.option.is_scroll && !this.option.append && this.option.show_loading)this.option.id.html('');
            this._loading('loading',true);
            this._event(false);

            if(typeof this.option.data == 'object'){
                this.temp_data.ajax_data = this.option.data;
                if(this.option.list_one){
                    this._list_one();
                }else{
                    this._list();
                }
            }else{
                this.temp_data.ajax_data = '';
                var ajax_params = this.option.param;
                var that = this;
                this.temp_data.ajax_no_data = false;
                this.temp_data.ajax_no_more = false;

                if(this.option.page_size > 0){
                    ajax_params.page_id   = this.option.page_id;
                    ajax_params.page_size = this.option.page_size;
                    this.option.data += this.option.data.indexOf('?')>-1?'&page_id='+this.option.page_id:'?page_id='+this.option.page_id;
                    this.option.data += '&page_size='+this.option.page_size;
                    this.option.data += '&per_page='+((this.option.page_id-1)*this.option.page_size);
                    this.option.data += '&limit='+this.option.page_size;
                }
                this.temp_data.ajax_fail = false;
                this.temp_data.ajax_over = false;
                $.ajax({
                    type    : 'POST',
                    url     : that.option.data,
                    data    : ajax_params,
                    btn     : that.option.btn,
                    dataType: 'json',
                    error   :function(a,b,c){
                        that.temp_data.ajax_over = true;
                        that.temp_data.ajax_fail = true;
                        that._loading('loading',false);
                        that._loading('error');
                        console.log('ajax访问出错:'+that.option.data+'['+b+'-'+c+']');
                    },
                    success : function (result) {
                        if(that.option.list_one){
                            if(result && result.data){
                                that.temp_data.ajax_data = result.data;
                            }
                            that._list_one();
                        }else{
                            if(result && result.data.length){
                                that.temp_data.links = result.links || '';
                                that.temp_data.page_num_max = result.total&&that.option.page_size?Math.ceil(result.total/that.option.page_size):false;
                                that.temp_data.ajax_data = result.data;
                                if(that.option.page_size > 0 && result.data.length < that.option.page_size){
                                    that.temp_data.ajax_no_more = true;
                                }
                            }else {
                                if(that.option.page_id == 1){
                                    that.temp_data.ajax_no_data = true;
                                }else{
                                    that.temp_data.ajax_no_more = true;
                                }
                            }
                            that._list();
                        }
                    }
                });
            }
        },
        '_html':function(){
            var that = this;
            var data = this.temp_data.ajax_data;

            if( ! data)return '';

            var all_html = '';//所有循环的html代码

            $(data).each(function(i,v){
                var html = that.html_data.html.clone();

                $(html).find(":first").addClass("loop"); //为循环的html item 加上loop class
                v['key'] = that.option.page_size*(that.option.page_id-1)+i+1;
                for(var key in v){
                    var val = v[key];
                    //使用数据处理函数
                    if(that.option.value_func){
                        var func=that.option.value_func[key];
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

                //为每个item 执行 自定义funtion
                if(typeof that.option.list_func == "function")that.option.list_func($(html),v);

                //赋值html
                all_html+=$(html).html();

                //渐隐效果 大于第一页时启用
                if(that.option.list_fadein > 0 && that.option.page_id > that.option.list_fadein_page_id-1){
                    if((that.option.page_id == 1 && i == 0) || !that.option.append)that.option.id.html('');
                    that.option.id.append($(html).html());
                    $(".loop:last").hide().delay(i*that.option.list_fadein*1000).fadeIn(that.option.list_fadein*1000);
                    that.temp_data.list_fadein_time = i*that.option.list_fadein*1000+1000;
                }
            });
            return all_html;
        },
        '_list':function(){
            var that = this;
            var html = '';

            if(this.option.loading_delay > 0){
                var delay_t1 = setTimeout(function(){
                    clearTimeout(delay_t1);

                    html = that._html();
                    that._loading('loading',false);

                    if(that.temp_data.ajax_no_data){
                        that.option.id.html('');
                        that._loading('no-data',true);
                    }else{
                        if(html != ''){
                            if(that.option.list_fadein == 0 || that.option.page_id <= that.option.list_fadein_page_id-1){
                                if(that.option.page_id == 1 || !that.option.append){
                                    that.option.id.html(html);
                                    that.option.id.find('.loop').hide().fadeIn(500);
                                }else{
                                    that.option.id.append(html);
                                }
                            }
                        }

                        that.temp_data.ajax_over = true;

                        if(that.temp_data.ajax_no_more){
                            that._loading('no-more',true);
                        }else{
                            that.option.page_id++ ;
                            that._event(true);

                            if(that.option.is_scroll)that._loading('more');
                        }
                    }
                    if(typeof that.option.callback == 'function')that.option.callback(function(page_num){
                        if(page_num && that.temp_data.ajax_over){
                            switch (page_num){
                                case 'prev':
                                    if(that.option.page_id == 2) return;
                                    that.option.page_id -=2;
                                    break;
                                case 'next':
                                    break;
                                case 'home':
                                    if(that.option.page_id == 2) return;
                                    that.option.page_id = 1;
                                    break;
                                case 'end':
                                    if( !that.temp_data.page_num_max)return;
                                    that.option.page_id = that.temp_data.page_num_max;
                                    break;
                                default:
                                    if(that.option.page_id == parseInt(page_num)) return;
                                    that.option.page_id = parseInt(page_num) || 1;
                            }
                            that._init();
                        }
                    },that.temp_data.links);
                    if(that.option.is_scroll)that._scroll_refresh();
                },that.option.loading_delay*1000);
            }else{
                html = this._html();

                that._loading('loading',false);

                if(that.temp_data.ajax_no_data){
                    that.option.id.html('');
                    that._loading('no-data',true);
                }else{
                    if(html != ''){
                        if(that.option.list_fadein == 0 || that.option.page_id <= that.option.list_fadein_page_id-1){
                            if(that.option.page_id == 1 || !that.option.append){
                                that.option.id.html(html);
                                that.option.id.find('.loop').hide().fadeIn(500);
                            }else{
                                that.option.id.append(html);
                            }
                        }
                    }

                    that.temp_data.ajax_over = true;

                    if(that.temp_data.ajax_no_more){
                        that._loading('no-more',true);
                    }else{
                        that.option.page_id++ ;
                        that._event(true);
                    }
                }
                if(typeof that.option.callback == 'function')that.option.callback(function(page_num){
                    if(page_num && that.temp_data.ajax_over){
                        switch (page_num){
                            case 'prev':
                                if(that.option.page_id == 2) return;
                                that.option.page_id -=2;
                                break;
                            case 'next':
                                break;
                            case 'home':
                                if(that.option.page_id == 2) return;
                                that.option.page_id = 1;
                                break;
                            case 'end':
                                if( !that.temp_data.page_num_max)return;
                                that.option.page_id = that.temp_data.page_num_max;
                                break;
                            default:
                                if(that.option.page_id == parseInt(page_num)) return;
                                that.option.page_id = parseInt(page_num) || 1;
                        }
                        that._init();
                    }
                },that.temp_data.links);
                if(that.option.is_scroll)that._scroll_refresh();
            }
        },
        '_list_one':function(){
            var that = this;
            var data = this.temp_data.ajax_data;

            var deal = function(){
                if(data){
                    for(var key in data){
                        var val = data[key];
                        //使用数据处理函数
                        if(that.option.value_func){
                            var func=that.option.value_func[key];
                            if(func)val=func(val);//处理数据
                        }
                        //循环查询 带data键名为class的标签
                        var obj=that.option.id.find("."+key);
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
                }
            };
            if(this.option.loading_delay > 0){
                var list_one_tt= setTimeout(function(){
                    clearTimeout(list_one_tt);
                    deal();

                    if(typeof that.option.callback == "function")that.option.callback();
                    if(that.option.is_scroll)that._scroll_refresh();
                },this.option.loading_delay*1000)
            }else{
                deal();
                if(typeof that.option.callback == "function")that.option.callback();
                if(that.option.is_scroll)that._scroll_refresh();
            }
        },
        '_scroll_init':function(){
            //定义scroll窗体的高度
            if(typeof this.option.scroll_offset_height == "function")this.option.scroll_offset_height = this.option.scroll_offset_height();
            if(this.option.scroll_offset_height > 0){
                $("#"+this.option.id.attr('id')+'-warp').height(window.innerHeight-this.option.scroll_offset_height);
                this.temp_data.reset = true;
            }else{
                $("#"+this.option.id.attr('id')+'-warp').height(window.innerHeight);
            }
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
            //窗口重置绑定事件
            $(window).unbind('resize').bind('resize',function(){
                if(that.temp_data.reset)$("#"+that.option.id.attr('id')+'-warp').height(window.innerHeight-that.option.scroll_offset_height);
            });

            //定义上拉下拉对象
            var pullDownEl, pullDownOffset,pullUpEl, pullUpOffset,that = this;
            if(this.option.down_refresh){
                pullDownEl = document.getElementById('pullDown');
                pullDownOffset = pullDownEl.offsetHeight;
            }
            if( !this.option.list_one && this.option.page_size > 0 && this.option.up_load){
                pullUpEl = document.getElementById('pullUp');
                pullUpOffset = pullUpEl.offsetHeight;
            }

            //上拉下拉的处理
            var pull_down_deal = function() {
                if(that.option.down_refresh) {
                    that.temp_data.is_down_refresh = true;
                    that.init();
                    that.temp_data.is_down_refresh = false;
                }
            },pull_up_deal = function() {
                if( !that.option.list_one && that.option.page_size>0 && !that.temp_data.ajax_no_data && !that.temp_data.ajax_no_more){
                    that._init();
                }else{
                    that.temp_data.scroll_object.refresh();
                }
            };

            //实例化 滑动对象
            this.temp_data.scroll_object = new iScroll(this.option.id.attr('id')+'-warp', {
                hScroll:false,
                hScrollBar:false,
                vScrollBar:false,
                topOffset: pullDownOffset,
                onRefresh: function () {
                    if( ! that.option.list_one && that.option.up_load) {
                        if (that.temp_data.ajax_no_data) {
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_no_data;
                        } else {
                            if (that.temp_data.ajax_no_more) {
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_no_more;
                            } else {
                                pullUpEl.className = '';
                                pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_up_load_default;
                            }
                        }
                    }
                    if (that.option.down_refresh && pullDownEl.className.match('loading')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = that.temp_data.msg_down_refresh_default;

                        $("#pullDown").css('visibility','hidden');
                    } else if ( !that.option.list_one && that.option.up_load && pullUpEl.className.match('loading')) {
                        if(that.temp_data.ajax_no_data){
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_no_data;
                        }else if(that.temp_data.ajax_no_more){
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_no_more;
                        }else{
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_up_load_default;
                        }
                    }
                },
                onScrollMove: function () {
                    if(that.option.down_refresh && this.y < 2 && this.y > 0){
                        if(that.option.show_loading){
                            $("#pullDown").css('visibility','visible');
                            $("#pullUp").css('visibility','hidden');
                        }
                    }
                    if(that.option.up_load && this.y < this.maxScrollY-20){
                        if(that.option.show_loading)$("#pullUp").css('visibility','visible');
                    }

                    if (that.option.down_refresh && this.y > (10+that.option.down_refresh_offset) && !pullDownEl.className.match('flip')) {
                        pullDownEl.className = 'flip';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = that.temp_data.msg_down_refresh_able;
                        this.minScrollY = 0;
                    } else if (that.option.down_refresh && this.y < (10+that.option.down_refresh_offset) && pullDownEl.className.match('flip')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = that.temp_data.msg_down_refresh_default;
                        this.minScrollY = -pullDownOffset;
                    } else if (!that.option.list_one && that.option.up_load && this.y < (this.maxScrollY - (50+that.option.up_load_offset)) && !pullUpEl.className.match('flip')) {
                        if( ! that.temp_data.ajax_no_data && !that.temp_data.ajax_no_more) {
                            pullUpEl.className = 'flip';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_up_load_able;
                            this.maxScrollY = this.maxScrollY;
                        }
                    } else if (!that.option.list_one && that.option.up_load && this.y > (this.maxScrollY - (50+that.option.up_load_offset)) && pullUpEl.className.match('flip')) {
                        if( ! that.temp_data.ajax_no_data && !that.temp_data.ajax_no_more) {
                            pullUpEl.className = '';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_up_load_default;
                            this.maxScrollY = pullUpOffset;
                        }
                    }
                },
                onScrollEnd: function () {
                    if (that.option.down_refresh && pullDownEl.className.match('flip')) {
                        pullDownEl.className = 'loading';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = that.temp_data.msg_down_refresh_loading;
                        pull_down_deal();	// Execute custom function (ajax call?)
                    } else if (!that.option.list_one && that.option.up_load && pullUpEl.className.match('flip')) {
                        if( ! that.temp_data.ajax_no_data && !that.temp_data.ajax_no_more) {
                            pullUpEl.className = 'loading';
                            pullUpEl.querySelector('.pullUpLabel').innerHTML = that.temp_data.msg_up_load_loading;
                            pull_up_deal();	// Execute custom function (ajax call?)
                        }
                    }
                }
            });
        },
        '_scroll_refresh':function(){
            if(this.option.is_scroll){
                if(this.temp_data.scroll_object == ''){
                    this._scroll_init();
                }else{
                    this.temp_data.scroll_object.refresh();
                }
            }
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
    $.fn.list_data = function(option,callback){
        option = option || {};
        option.id = this;
        var this_obj = new list_data(option);
        this_obj.init();
        if(typeof callback == "function")callback(function(url,params,callback1){
            this_obj.init(url,params,callback1);
        });
    };

    /**
     * jquery 倒计时 两个时间 放在对象内标签（data-start-time/data-end-time ） 开标时间 和截至时间 对应两个时间截至到处理函数
     * @param callback1 开标时间截至到处理函数
     * @param callback2 标的结束的处理函数
     * @param func 处理函数
     */
    $.fn.count_down = function(callback1,callback2,func,now_time){
        var curren_run_time = 1;
        if( ! now_time)now_time = Date.parse(new Date())/1000;
        var count_down = function() {this.tt=0;this.now_time=null};
        count_down.prototype = {
            'go':function(e,end_time,callback,deal_func) {
                if(this.now_time == null)this.now_time=now_time;
                var time = this.now_time;//Date.parse(new Date())/1000;
                var time_space =end_time-time;
                var s = 0,m = 0,h = 0,d = 0;
                if(time_space > 0){
                    s = time_space%60;
                    m = Math.floor(time_space/60)%60;
                    h = Math.floor(Math.floor(time_space/60)/60)%24;
                    d = Math.floor(Math.floor(Math.floor(time_space/60)/60)/24);
                    if(s<10)s="0"+s;
                    if(m<10)m="0"+m;
                    if(h<10)h="0"+h;
                    if(d<10)d="0"+d;
                    if(typeof deal_func == 'function'){
                        deal_func(curren_run_time,e,d,h,m,s);
                    }else {
                        e.find('.s').text(s);
                        e.find('.m').text(m);
                        e.find('.h').text(h);
                        e.find('.d').text(d);
                    }
                    var _this = this;
                    this.tt=setTimeout(function(){_this.go(e,end_time,callback,deal_func);},1000);
                }else{
                    if(typeof deal_func == 'function'){
                        deal_func(curren_run_time,e,d,h,m,s);
                    }else{
                        e.find('.s').text('00');
                        e.find('.m').text('00');
                        e.find('.h').text('00');
                        e.find('.d').text('00');
                    }

                    clearTimeout(this.tt);
                    if(typeof callback == 'function')callback();
                }
                this.now_time++;
            }
        };
        if(this.length > 1){
            $(this).each(function(i,v){
                var _this =$(v);
                var time1 = _this.attr('data-start-time') | 0;
                var time2 = _this.attr('data-end-time') | 0;
                var cd = new count_down();
                if(_this.css('visibility') == 'hidden')_this.css('visibility','visible');
                cd.go(_this,time1,function(){
                    if(typeof callback1 == 'function')callback1(_this);
                    curren_run_time = 2;
                    cd.go(_this,time2,function(){if(typeof callback2 == 'function')callback2(_this);},func);
                },func);
            });
        }else{
            var _this =this;
            var time1 = _this.attr('data-start-time') | 0;
            var time2 = _this.attr('data-end-time') | 0;
            var cd = new count_down();
            if(_this.css('visibility') == 'hidden')_this.css('visibility','visible');
            cd.go(_this,time1,function(){
                if(typeof callback1 == 'function')callback1(_this);
                curren_run_time = 2;
                cd.go(_this,time2,function(){if(typeof callback2 == 'function')callback2(_this);},func);
            },func);
        }
        return this;
    };

    $.fn.send_sms = function(type,mobile,action,sms_callback){
        var wait = 60,last_send_time_go = '',tag_default_msg = '',is_input = false;
        if(typeof g_sms_apace_time != 'undefined'){
            wait = g_sms_apace_time;
        }
        if(type == 'sms' && typeof g_sms_last_time != 'undefined' && g_sms_last_time > 0){
            last_send_time_go = Date.parse(new Date())/1000 - g_sms_last_time;
        }
        if(type == 'voice' && typeof g_voice_last_time != 'undefined' && g_voice_last_time > 0){
            last_send_time_go = Date.parse(new Date())/1000 - g_voice_last_time;
        }

        if( ! mobile){
            my_alert('电话号码不能为空!',2);
            return;
        }
        if(this.data('waitTime') != 'undefined' && parseInt(this.data('waitTime')) > 0){
            wait = parseInt(this.data('waitTime'));
        }
        if(this.data('lastTime') != 'undefined' && parseInt(this.data('lastTime')) > 0){
            last_send_time_go = Date.parse(new Date())/1000 - parseInt(this.data('lastTime'));
        }
        if(this.get(0).tagName == 'INPUT'){
            tag_default_msg = this.val();
            is_input = true;
        }else{
            tag_default_msg = this.html();
        }

        var _this = this;
        //倒计时 效果处理
        var sms_count_down = function(e,space_time,all_time,callback){
            var wait=space_time;
            var t = 0;
            var time = function(o){
                if (wait == 0) {
                    o.removeAttr("disabled");
                    if(is_input){
                        o.val(tag_default_msg);
                    }else{
                        o.html(tag_default_msg);
                    }

                    wait = all_time;
                    clearTimeout(t);
                    _this.bind('click',function(){send_event();});
                    if(typeof callback == "function"){
                        callback();
                    }
                } else {
                    o.attr("disabled","true");
                    _this.unbind('click');
                    if(is_input){
                        o.val("" + wait + "秒后再次发送");
                    }else{
                        o.html("" + wait + "秒后再次发送");
                    }

                    wait--;
                    t = setTimeout(function() {
                        time(o)
                    },1000)
                }
            };
            time(e);
        };

        //发送到ajax事件
        var send_event = function(){
            _this.unbind('click');
            $.ajax({
                type: 'POST',
                url: '/index.php/send/index',
                data: {'type':type,'mobile':mobile,'action':action},
                dataType: 'json',
                success: function (result) {
                    if(result.status == '10000'){
                        if(type == 'voice'){
                            my_alert('稍后聚雪球将通过电话4007-918-333拨打' +
                                '您的手机'+mobile+'告知验证码!',1);
                        }else{
                            my_alert('短信已发送,请注意查收!',1);
                        }
                        //发送成功 执行显示效果
                        sms_count_down(_this,wait,wait,function(){ _this.bind('click',function(){send_event();});});
                    }else{
                        my_alert(result.msg ,2);
                    }
                    if(typeof  sms_callback == "function")sms_callback(result);
                }
            });
        };
        //验证上一次发送到时间间隔
        if(last_send_time_go !== '' && last_send_time_go < wait){
            sms_count_down(this,wait-last_send_time_go,wait,function(){_this.unbind('click').bind('click',function(){send_event();});});
        }else{
            _this.unbind('click').bind('click',function(){send_event();});
        }
        return this;
    }
})(jQuery);

/**
 * 全局ajax效果
 * 【如果是按钮 需要ajax参数中加 btn：.calss name|#id name  如果禁用全局 btn:没有的class或id即可】
 * @param type 效果类型 1【按钮效果】2【遮罩效果】3【二选一优先按钮】其他【全部】
 * @param bg_ch_enable 按钮背景变化启用标识
 * @param end_delay 按钮恢复延时
 */
var ajax_loading_style = function(type,bg_ch_enable,end_delay){
    var temp_data = [],ajax_submit_button_load_msg = '提交中...',
    ajax_start_deal = function(str){
        if( str && $(str).length && $(str).get(0).tagName){
            var key = str.substr(1).replace('-','_');
            if(temp_data[key])return false;
            temp_data[key] = [];
            temp_data[key]['loading_msg'] = $(str).data('loadingMsg') || ajax_submit_button_load_msg;
            temp_data[key]['text'] = $(str).val() || $(str).html();
            if($(str).val()){
                $(str).removeAttr('disabled').attr('disabled', true).val(temp_data[key]['loading_msg']);
            }else{
                $(str).removeAttr('disabled').attr('disabled', true).html(temp_data[key]['loading_msg']);
            }
            //处理按钮背景变化
            if(bg_ch_enable == true){
                temp_data[key]['background-color'] = $(str).css('background-color');
                $(str).css('background-color','#999');
            }
        }
    },
    ajax_end_deal = function(str){
        if( str && $(str).length && $(str).get(0).tagName){
            var key = str.substr(1).replace('-','_');
            if(temp_data[key]){
                if(parseFloat(end_delay) > 0){
                    var t= setTimeout(function(){
                        if($(str).val()){
                            $(str).removeAttr('disabled').val(temp_data[key]['text']);
                        }else{
                            $(str).removeAttr('disabled').html(temp_data[key]['text']);
                        }
                        //恢复按钮背景变化
                        if(bg_ch_enable == true)$(str).css('background-color',temp_data[key]['background-color']);
                        temp_data[key] = undefined;
                        clearTimeout(t);
                    },parseFloat(end_delay)*1000);
                }else{
                    if($(str).val()){
                        $(str).removeAttr('disabled').val(temp_data[key]['text']);
                    }else{
                        $(str).removeAttr('disabled').html(temp_data[key]['text']);
                    }
                    //恢复按钮背景变化
                    if(bg_ch_enable == true)$(str).css('background-color',temp_data[key]['background-color']);
                    temp_data[key] = undefined;
                }
            }
        }
    };
    $(document).ajaxSend(function(e,x,option){
        switch (parseInt(type)){
            case 1://只是按钮触发
                if(option.btn)ajax_start_deal(option.btn);
                break;
            case 2://遮罩触发
                if(layer)layer.load(2);
                break;
            case 3://自动二选一 优先按钮
                if(option.btn){
                    ajax_start_deal(option.btn);
                }else{
                    if(layer)layer.load(2);
                }
                break;
            default://二者全部
                if(option.btn)ajax_start_deal(option.btn);
                if(layer)layer.load(2);
        }

    }).ajaxComplete(function(e,x,option){
        switch (parseInt(type)){
            case 1://只是按钮触发
                if(option.btn)ajax_end_deal(option.btn);
                break;
            case 2://遮罩触发
                if(layer)layer.closeAll('loading');
                break;
            case 3://自动二选一 优先按钮
                if(option.btn){
                    ajax_end_deal(option.btn);
                }else{
                    if(layer)layer.closeAll('loading');
                }
                break;
            default://二者全部
                if(option.btn)ajax_end_deal(option.btn);
                if(layer)layer.closeAll('loading');
        }
    });
};
