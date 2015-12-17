var wb_listview = function(option){
    if(typeof option == "object"){
        this.id         = option.id || 'wb-list-view'; //循环内容的容器id
        this.funcDeal   = option.funcDeal;              //需要 函数处理的字段 {field:functionName,field:functionName}
        if(! option.listone){ // 未设置 一条  则是循环  设置了则是一条的数据填充
            this.idField    = option.idField || 'id';      //循环内容的s数据id字段
            this.pageSize   = option.pageSize;              //分页的条数  如果不设置即视为不分页
            this.fadeIn     = option.fadeIn;                //渐隐出现  即一条一条加载
            this.pageId     = option.pageId || 1;
            if($("#"+this.id+"-noData").length){
                this.noData = $("#"+this.id+"-noData").clone();
                $("#"+this.id+"-noData").remove();
            }
            if($("#"+this.id+"-noMoreData").length){
                this.noMoreData = $("#"+this.id+"-noMoreData").clone();
                $("#"+this.id+"-noMoreData").remove();
            }
            if($("#"+this.id+"-loadingData").length){
                this.loadingData = $("#"+this.id+"-loadingData").clone();
                $("#"+this.id+"-loadingData").remove();
            }
            this.html       = $('#'+this.id).clone();      //要循环的html代码是从容器克隆
            //没有数据 和没有更多数据
            if(!this.noData){
                this.noData = this.html.find(':first').clone();
                this.noData.html('暂无相关信息！').css({'text-align':'center','padding':'10px 0 10px 0'});
            }
            if(!this.noMoreData){
                this.noMoreData = this.html.find(':first').clone();
                this.noMoreData.attr('id',this.id+'-noMoreData').html('没有更多信息了！').css({'text-align':'center','padding':'10px 0 10px 0'});
            }
            if(!this.loadingData){
                this.loadingData = this.html.find(':first').clone();
                this.loadingData.attr('id',this.id+'-loadingData').html('加载中...').css({'text-align':'center','padding':'10px 0 10px 0'});
            }
            if(option.showLoading){
                var _this = this;
                $(document).ajaxStart(function(){
                    $("#"+_this.id).append(_this.loadingData[0]);
                    /*
                     if($(this).find('#wait-img').length == 0){
                     $('body').append('<img id="wb-img" src="/assets/images/app/wait.gif" style="position: absolute;z-index:9999;"/>');
                     $('#wait-img').css({'top':(window.innerHeight-100)/2,'left':(window.innerWidth-100)/2})
                     }
                     */
                });
                $(document).ajaxStop(function(){
                    $('#'+_this.id+'-loadingData').remove();
                });
            }
            $('#'+this.id).html('').css('visibility','visible');
        }
    }
};
wb_listview.prototype = {
    'list':function(data,fun,callback){
        if(data && typeof data == "object"){
            var htmls = '',_this = this;//所有循环的html代码
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
                var uniq = v[_this.idField]?v[_this.idField]:i;
                $(html).find(":first").attr('id',_this.id+'-'+uniq);
                //为每个item 执行 自定义funtion
                if(typeof fun == "function")fun(html,v);
                //赋值html
                htmls+=$(html).html();
                if(_this.fadeIn && _this.pageId>1){
                    $("#"+_this.id).append($(html).html());
                    $("."+_this.id+"-loop:last").hide().delay(i*_this.fadeIn).fadeIn(_this.fadeIn);
                }
            });
            if(_this.pageId == 1){
                $("#"+_this.id).html(htmls);
            }else{
                if( ! _this.fadeIn)$("#"+_this.id).append(htmls);
            }
            if(typeof callback == "function")callback();
            this.pageId++;
        }else{
            if(this.pageId == 1){
                $('#'+this.id).html(this.noData[0]);
            }else{
                if($("#"+this.id+"-noMoreData").length == 0){
                    $('#'+this.id).append(this.noMoreData[0]);
                }
            }
        }
    },
    'set_pageid':function(pageid){
        this.pageId = pageid;
    },
    'list_one':function(data,callback){
        if(data && typeof data == "object"){
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
    }
};