define(function (require, exports, module) {
    var $ = require('jquery');

    var pluginName = 'iptTip';
    var config = {
        classStore: 'tip'
    };

    var Plugin = function (element, options) {
        var global = this,
            elem = element,
            opts = $.extend({}, config, options);

        //初始化
        var init = function () {
            var ipt = '<div class="' + opts.classStore + '"><span class="span-iptTip">' + elem.data('tip') + '</span></div>';
            ipt = $(ipt);
            elem.parent().append(ipt);

            ipt.on('click', function () {
                elem.trigger('focus');
            });
            elem.on({
                'focus': function () {
                    ipt.hide();
                },
                'blur': function () {
                    if (elem.val() === '') {
                        ipt.show();
                    }
                }
            });
            if (elem.val() !== '') {
                ipt.hide();
            }
        }();

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
});