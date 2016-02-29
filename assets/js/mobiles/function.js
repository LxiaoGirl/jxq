;(function($){
    $.fn.html_repeat = function(src){
        var html = $(this).clone();

        var _html_repaet = function(json_data){
            var html_all = '';
            if(json_data){
                $(json_data).each(function(i,v){
                    var _html = html.clone();
                    for(var key in v){
                        var val = v[key];
                        val['key'] = i+1;
                        //循环查询 带data键名为class的标签
                        var obj=$(_html).find("."+key);
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
                    html_all = _html[0];
                });
            }
            return html_all;
        }
    }
})(jQuery);
