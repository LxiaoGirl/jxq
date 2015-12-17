(function($){
	var ms = {
		init:function(obj,args){
			return (function(){
				ms.fillHtml(obj,args);
				ms.bindEvent(obj,args);
			})();
		},
		//填充html
		fillHtml:function(obj,args){
			return (function(){
				obj.empty();
				//上一页
				if(args.active > 1){
					obj.append('<span class="pre precl">上一页</span>');
				}else{
					obj.remove('.pre');
					obj.append('<span class="pre nocli">上一页</span>');
				}
				//中间页码
				if(args.active != 1 && args.active >= 4 && args.pageCount != 4){
					obj.append('<font href="javascript:;" class="tcdNumber">'+1+'</font>');
				}
				if(args.active-2 > 2 && args.active <= args.pageCount && args.pageCount > 5){
					obj.append('...&nbsp;&nbsp;');
				}
				var start = args.active -2,end = args.active+2;
				if((start > 1 && args.active < 4)||args.active == 1){
					end++;
				}
				if(args.active > args.pageCount-4 && args.active >= args.pageCount){
					start--;
				}
				for (;start <= end; start++) {
					if(start <= args.pageCount && start >= 1){
						if(start != args.active){
							obj.append('<font href="javascript:;" class="tcdNumber">'+ start +'</font>');
						}else{
							obj.append('<font class="active">'+ start +'</font>');
						}
					}
				}
				if(args.active + 2 < args.pageCount - 1 && args.active >= 1 && args.pageCount > 5){
					obj.append('...&nbsp;&nbsp;');
				}
				if(args.active != args.pageCount && args.active < args.pageCount -2  && args.pageCount != 4){
					obj.append('<font href="javascript:;" class="tcdNumber">'+args.pageCount+'</font>');
				}
				//下一页
				if(args.active < args.pageCount){
					obj.append('<span class="next nextcl">下一页</span>');
				}else{
					obj.remove('.next');
					obj.append('<span class="next nocli">下一页</span>');
				}
			})();
		},
		//绑定事件
		bindEvent:function(obj,args){
			return (function(){
				obj.on("click","font.tcdNumber",function(){
					var active = parseInt($(this).text());
					ms.fillHtml(obj,{"active":active,"pageCount":args.pageCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(active);
					}
				});
				//上一页
				obj.on("click","span.precl",function(){
					var active = parseInt(obj.children("font.active").text());
					ms.fillHtml(obj,{"active":active-1,"pageCount":args.pageCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(active-1);
					}
				});
				//下一页
				obj.on("click","span.nextcl",function(){
					var active = parseInt(obj.children("font.active").text());
					ms.fillHtml(obj,{"active":active+1,"pageCount":args.pageCount});
					if(typeof(args.backFn)=="function"){
						args.backFn(active+1);
					}
				});
			})();
		}
	}
	$.fn.createPage = function(options){
		var args = $.extend({
			pageCount : 10,
			active : 1,
			backFn : function(){}
		},options);
		ms.init(this,args);
	}
})(jQuery);