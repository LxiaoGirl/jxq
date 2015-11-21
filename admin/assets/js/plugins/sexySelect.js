define(function (require, exports, module) {
    require('jquery');
    require('sys');


    var pluginName = 'sexySelect';
    var config = {
        classStore: 'u-selector',
        skin: '',
        disabled: false,
        reset: true,
        onChange: false,
        onLoad: false
    };

    var Plugin = function (element, options) {
        var global = this,
            elem = element,
            opts = $.extend({}, config, options);

        global.elem = elem;
        global.opts = opts;
        global.disabled = opts.disabled;

        //初始化
        var init = function () {
            var old_select = elem;
            if (opts.reset) {
                //初始化时默认选中第一项
                old_select.val(old_select.children().eq(0).val());
            }

            var new_select = '<div class="' + opts.classStore + ' ' + opts.skin + (opts.disabled ? ' z-dis' : '') + '">' +
                '<div class="' + opts.classStore + '-ipt">' +
                '<div class="' + opts.classStore + '-text f-toe"></div>' +
                '<div class="' + opts.classStore + '-btn"></div>' +
                '</div>' +
                '<div class="' + opts.classStore + '-menu"></div>' +
                '</div>';

            new_select = $(new_select);
            new_select_menu = new_select.find('.' + opts.classStore + '-menu');
            new_select_text = new_select.find('.' + opts.classStore + '-text');


            global.select = new_select;
            global.select_menu = new_select_menu;
            global.select_text = new_select_text;

            //取得当前选中项的文本值
            var sel_option = old_select.children().not(function () {
                return !this.selected;
            });
            new_select_text.html(sel_option.html());

            old_select.hide();
            new_select_menu.append(global.getOption());
            new_select.insertBefore(old_select).append(old_select);

            //事件绑定
            old_select.on('change', function () {
                sel_option = old_select.children().not(function () {
                    return !this.selected;
                });
                global.select_text.html(sel_option.html());
                if (opts.onChange) {
                    opts.onChange.call(this, sel_option.val(), sel_option.html());
                }
            });
            new_select.on('click', function () {
                if (!global.disabled) {
                    new_select.addClass('z-open');
                }
            }).on('outerClick', function () {
                new_select.removeClass('z-open');
            }).on('click', '.' + opts.classStore + '-opt', function (e) {
                e.stopPropagation();
                new_select.removeClass('z-open');
                old_select.val($(this).data("val")).trigger('change');
            });

            if (opts.onLoad) {
                opts.onLoad.call(this, sel_option.val(), sel_option.html());
            }

        }();

        return global;
    };

    Plugin.prototype = {
        getOption: function () {
            var global = this;
            var optionHtml = "";
            global.elem.children().each(function (index, obj) {
                var that = $(obj);
                if (that.data('hide') !== true) {
                    optionHtml += '<a class="' + global.opts.classStore + '-opt" href="javascript:;" data-val="' + that.val() + '">' + that.html() + '</a>';
                }
            });
            return optionHtml;
        },
        changeDis: function (status) {
            this.disabled = typeof (status) !== "undefined" ? status : !this.disabled;
            if (this.disabled) {
                this.select.addClass('z-dis');
            } else {
                this.select.removeClass('z-dis');
            }

            return this;
        },
        refresh: function () {
            var global = this;
            //更新选项
            global.select_menu.html(global.getOption());
            //默认选中第一项
            global.elem.val(global.elem.children().eq(0).val());
            global.select_text.html(global.elem.children().not(function () {
                return !this.selected;
            }).html());

            return global;
        }
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