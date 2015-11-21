/**
 * Created by Administrator on 2014/10/28.
 */
define(function (require, exports, module) {

    var $ = require('jquery');
    var pluginName = 'dueTime';
    var config = {
        stepTime: 2000
    };
    var Plugin = function (element, options) {
        var global = this,
            elem = element,
            opts = $.extend({}, config, options);
        var endTime =  elem.attr('data-time');
        //初始化
        var init = function () {
            var cureent = (new Date()).getTime();
            var between = parseInt(endTime)-parseInt(cureent)/1000;
            if(between>0){
                var second=Math.floor(between%60);
                var minite=Math.floor((between/60)%60);
                var hour=Math.floor((between/60/60)%24);
                var day=Math.floor((between/60/60)/24);
                elem.html("");
                elem.html("剩"+day+"天"+hour+"时"+minite+"分"+second+"秒")}else{
                elem.html("已经结束");}
        };
        setTimeout(init,0);
        var stepTime = parseInt(opts.stepTime) || 1000 ;
        setInterval(init,stepTime);
        return global;
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            var self = $(this);
            if (!self.data('plugin_' + pluginName)) {
                self.data('plugin_' + pluginName, new Plugin(self, options));
            }
        });
    };
})
