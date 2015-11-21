define(function (require, exports, module) {
    var $ = require('jquery');

    //系统插件
    plugin = function () {
        /**
         * jqueryCSS3相关方法注入
         * @private
         */
        var _methodInject = function () {
            $.fn.transform = function (transform) {
                for (var i = 0; i < this.length; i++) {
                    var elStyle = this[i].style;
                    elStyle.webkitTransform = elStyle.MsTransform = elStyle.msTransform = elStyle.MozTransform = elStyle.OTransform = elStyle.transform = transform;
                }
                return this;
            };
            $.fn.transition = function (key, value) {
                for (var i = 0; i < this.length; i++) {
                    var elStyle = this[i].style;
                    elStyle['webkitTransition' + key] = elStyle['MsTransition' + key] = elStyle['msTransition' + key] = elStyle['MozTransition' + key] = elStyle['OTransition' + key] = elStyle['transition' + key] = value;
                }
                return this;
            };
            $.fn.transitionEnd = function (callback) {
                var events = ['webkitTransitionEnd', 'transitionend', 'oTransitionEnd', 'MSTransitionEnd', 'msTransitionEnd'],
                    i, j, dom = $(this[0]);

                function fireCallBack(e) {
                    callback.call(this, e);
                    for (i = 0; i < events.length; i++) {
                        dom.off(events[i], fireCallBack);
                    }
                }

                if (callback) {
                    for (i = 0; i < events.length; i++) {
                        dom.on(events[i], fireCallBack);
                    }
                }
                return this;
            };
            $.fn.animationEnd = function (callback) {
                var events = ['webkitAnimationEnd', 'OAnimationEnd', 'MSAnimationEnd', 'animationend'],
                    i, j, dom = $(this[0]);

                function fireCallBack(e) {
                    callback.call(this, e);
                    for (i = 0; i < events.length; i++) {
                        dom.off(events[i], fireCallBack);
                    }
                }

                if (callback) {
                    for (i = 0; i < events.length; i++) {
                        dom.on(events[i], fireCallBack);
                    }
                }
                return this;
            };
        };
        /**
         * jquery 自定义事件outerClick
         * @private
         */
        var _outerClick = function () {
            var elements = [];
            var check = function (event) {
                var count = elements.length,
                    elem = null;
                for (var i = 0; i < count; i++) {
                    elem = elements[i];
                    //确定事件源和事件产生对象的关系
                    if (!!elem && elem !== event.target && !(elem.contains ? elem.contains(event.target) : elem.compareDocumentPosition ? elem.compareDocumentPosition(event.target) & 16 : 1)) {
                        $.event.trigger('outerClick', event, elem);
                    }
                }
            };
            $.event.special['outerClick'] = {
                /**
                 * 初始化事件处理器 - this指向元素
                 * @param 附加的数据
                 * @param 事件类型命名空间
                 * @param 回调函数
                 */
                setup: function (data, namespaces, eventHandle) {
                    if (elements.length === 0) {
                        $(document).on('click', check);
                    }
                    if ($.inArray(this, elements) < 0) {
                        elements.push(this);
                    }
                },
                /**
                 * 卸载事件处理器 - this指向元素
                 * @param 事件类型命名空间
                 */
                teardown: function (namespaces) {
                    var index = $.inArray(this, elements);
                    if (index >= 0) {
                        elements.splice(index, 1);
                        if (elements.length === 0) {
                            $(document).off('click', check);
                        }
                    }
                }
            };
        };

        var _slider = function () {
            var pluginName = 'sysSlider';
            var config = {
                pageCount: 0,
                play: false,
                playTime: 5000,
                playCss: '',
                playDrift: 0,
                playEvent: 'mouseenter'
            };

            function Plugin(element, options) {
                var global = this,
                    elem = element,
                    opts = $.extend({}, config, options);

                global.elem = elem;
                global.opts = opts;

                var init = function () {
                    var slides = elem.find('.slides');
                    var current = 0,
                        size = Math.ceil(slides.children().size() / opts.pageCount);

                    var page = '<ul class="slider-page">';
                    for (var i = 0; i < size; i++) {
                        page += '<li ' + (i === 0 ? 'class="z-sel"' : '') + '></li>';
                    }
                    page += '</ul>';
                    page = $(page);

                    var prev = $('<a class="slider-prev" style="display: none;" href="javascript:;"></a>'),
                        next = $('<a class="slider-next" style="display: none;" href="javascript:;"></a>');

                    elem.append(page).append(prev).append(next);

                    if (size > 1) {
                        next.show();
                    }

                    var pages = page.children(),
                        time = null,
                        animateObj = null;

                    pages.on(opts.playEvent, function() {
                        current = $(this).index() - 1;
                        change(10);
                    });

                    prev.on('click', function () {
                        if (current > 0) {
                            current -= 2;
                            change(10);
                        }
                    });

                    next.on('click', function () {
                        if (current < size -1) {
                            change(10);
                        }
                    });

                    var change = function (delay) {
                        clearTimeout(time);
                        time = setTimeout(function () {
                            current++;
                            if (current == size) {
                                current = 0;
                            }

                            prev.hide();
                            next.hide();

                            if (current > 0) {
                                prev.show();
                            }
                            if (current < size - 1) {
                                next.show();
                            }

                            pages.removeClass('z-sel').eq(current).addClass('z-sel');
                            animateObj = {};
                            animateObj[opts.playCss] = current * opts.playDrift;
                            slides.stop(true, true).animate(animateObj, 500, function () {
                                if (opts.play) {
                                    change();
                                }
                            });
                        }, delay || opts.playTime);
                    };
                    if (opts.play) {
                        change();
                    }
                }();

                return global;
            }

            $.fn[pluginName] = function (options) {
                return this.each(function () {
                    var self = $(this);
                    if (!self.data('plugin_' + pluginName)) {
                        self.data('plugin_' + pluginName, new Plugin(self, options));
                    }
                });
            };
        };
        return{
            init: function () {
                _methodInject();
                _outerClick();
                _slider();
            }
        }
    }();

    var sys = function () {
        /**
         * 获得IE版本号
         * @returns {number}
         */
        var getIeVersion = function () {
            if (typeof(window.ieVersion) !== 'undefined') {
                return window.ieVersion;
            }
            var version = -1,
                ua, re;
            if (navigator.appName === 'Microsoft Internet Explorer') {
                ua = navigator.userAgent;
                re = new RegExp('MSIE ([0-9]{1,})');
                if (re.exec(ua) !== null) {
                    version = parseInt(RegExp.$1);
                }
            }
            window.ieVersion = version;
            return version;
        };
        /**
         * 事件延迟操作
         * @param fn 调用函数
         * @param timeout 延迟时间
         * @returns {Function}
         */
        var throttle = function (fn, timeout) {
            var timer;

            return function () {
                var self = this,
                    args = arguments;

                clearTimeout(timer);

                timer = setTimeout(function () {
                    fn.apply(self, args);
                }, timeout);
            };
        };
        /**
         * css3属性支持判断
         * @param style
         * @returns {boolean}
         */
        var supportCss3 = function (style) {
            var prefix = ['webkit', 'Moz', 'ms', 'o'],
                i,
                humpString = [],
                htmlStyle = document.documentElement.style,
                _toHumb = function (string) {
                    return string.replace(/-(\w)/g, function ($0, $1) {
                        return $1.toUpperCase();
                    });
                };

            for (i in prefix)
                humpString.push(_toHumb(prefix[i] + '-' + style));

            humpString.push(_toHumb(style));

            for (i in humpString)
                if (humpString[i] in htmlStyle) return true;

            return false;
        };
        /**
         * 快速获得图片大小 大约100ms左右
         * @param src 图片路径
         * @param fn 回调函数(width,height)
         */
        var getImgSize = function (src, fn) {
            var img = new Image();
            img.src = src;

            var check = function () {
                if (img.width > 0 || img.height > 0) {
                    fn(img.width, img.height);
                    clearInterval(time);
                }
            };

            var time = setInterval(check, 40);
        };
        return {
            supportCss3: supportCss3,
            throttle: throttle,
            ieVersion: getIeVersion(),
            getImgSize: getImgSize
        }
    }();

    /**
     * 对话框漂浮层次
     * @type {number}
     */
    var dialogIndex = 1000;
    /**
     * 对话框动画列表
     * @type {{}}
     */
    var dialogEffectList = {};
    dialogEffectList['fade'] = {
        'inClassName': 'fx-fadeIn',
        'outClassName': 'fx-fadeOut'
    };
    dialogEffectList['fadeDown'] = {
        'inClassName': 'fx-fadeInDown',
        'outClassName': 'fx-fadeOutUp'
    };
    dialogEffectList['bounce'] = {
        'inClassName': 'fx-bounceIn',
        'outClassName': 'fx-bounceOut'
    };
    sys.dialog = function (options) {
        function model(options) {
            var params = {
                template: null,
                position: 'center',
                top: 0,
                left: 0,
                effect: 'bounce',
                lifespan: null,
                hasOverlay: true,
                overlayTran: false,
                overlayClass: false,
                outerClick: false,
                onLoad: false,
                onBeforeUnload: false,
                onUnload: false
            };
            var global = this;
            var opts = $.extend(params, options);

            global.opts = opts;

            var body = $('body');

            if (global.opts.hasOverlay) {
                global.overlay = $('<div class="u-dialog-overlay' + (global.opts.overlayTran ? ' s-transparent' : '') + (global.opts.overlayClass ? ' ' + global.opts.overlayClass : '') + '" style="z-index:' + (++dialogIndex) + '"></div>');
                body.append(global.overlay);
            }

            global.dialog = $('<div class="u-dialog" style="z-index:' + (++dialogIndex) + '"></div>');
            global.dialog.append(global.opts.template);
            body.append(global.dialog);

            global.supportCss3 = sys.supportCss3('animation');
            global.effect = dialogEffectList[global.opts.effect];

            //初始化弹出框的定位
            if (global.opts.position === 'center') {
                global.dialog.css({
                    'position': 'fixed',
                    'top': '50%',
                    'left': '50%',
                    'marginLeft': parseInt(global.dialog.width() / -2) + global.opts.left,
                    'marginTop': parseInt(global.dialog.height() / -2) + global.opts.top
                });
            } else {
                global.dialog.css({
                    'position': global.opts.position,
                    'top': global.opts.top,
                    'left': global.opts.left
                });
            }

            //简化了动画部分,在现代浏览器用css3执行动画操作(fade,fadeDown,bounce),而在IE789中无动画
            if (global.supportCss3 && global.effect) {
                global.dialog.addClass(global.effect.inClassName);
                if (global.overlay) {
                    global.overlay.addClass('fx-fadeIn');
                }
            }

            //是否开启了outerClick
            if (global.opts.outerClick) {
                //假如该对话框是在某元素的点击事件下产生的，可能会出现在该事件的冒泡阶段就引发了outerClick
                //所以给事件加上一个延迟异步绑定
                setTimeout(function () {
                    global.dialog.on('outerClick', function () {
                        global.close();
                    });
                }, 0);
            }

            //设置计时器
            if (typeof(global.opts.lifespan) === "number") {
                global.timer = setTimeout(function () {
                    global.close();
                }, global.opts.lifespan);
            }

            //加载成功调用回调函数
            if (global.opts.onLoad) {
                global.opts.onLoad.call(global);
            }

            return this;
        }

        model.prototype.close = function () {
            var global = this;
            if (global.ifRemove) {
                return false;
            }
            global.ifRemove = true;
            if (global.timer) {
                clearTimeout(global.timer);
            }
            var callback = function () {
                global.dialog.remove();
                dialogIndex -= global.overlay ? 2 : 1;
                if (global.overlay) {
                    global.overlay.remove();
                }
                //关闭完成触发的事件
                if (global.opts.onUnload) {
                    global.opts.onUnload.call(global);
                }
            };
            var close = function () {
                if (global.supportCss3 && global.effect) {
                    global.dialog.addClass(global.effect.outClassName);
                    if (global.overlay) {
                        global.overlay.addClass('fx-fadeOut');
                    }
                    var count = 0;
                    global.dialog.animationEnd(function () {
                        count++;
                        if (count === 1) {
                            callback();
                        }
                    });
                } else {
                    callback();
                }
            };
            //关闭前触发的事件
            if (global.opts.onBeforeUnload) {
                //如果再该事件return false 就阻止关闭
                if (global.opts.onBeforeUnload.call(global) !== false) {
                    close();
                } else {
                    global.ifRemove = false;
                }
            } else {
                close();
            }
        };

        return new model(options);
    };
    sys.alert = function () {
        var params = {
            title: '',
            template: null,
            lifespan: null,
            skin: ''
        };
        if (arguments.length > 0) {
            if (typeof (arguments[0]) === 'object') {
                params = $.extend(params, arguments[0]);

                template = '<div class="u-dialog-alert ' + params.skin + '">';
                if (typeof (params.title) !== 'undefined') {
                    template += '<div class="u-dialog-title"><span class="text">' + params.title + '</span><a class="u-dialog-close" href="javascript:;"></a></div>'
                }
                template += '<div class="u-dialog-inner"></div></div>';
                template = $(template);
                template.find('.u-dialog-inner').append(params.template);

                params.template = template;
                //重构onLoad事件 加入关闭按钮监听
                var onLoad = params.onLoad;
                params.onLoad = function () {
                    var dialog = this;
                    //初始化绑定
                    template.find('.u-dialog-close').on('click', function () {
                        dialog.close();
                    });
                    if (onLoad) {
                        onLoad.call(dialog);
                    }
                };
            } else if (typeof (arguments[0]) === 'string') {
                template = '<div class="u-dialog-msg ' + (typeof (arguments[1]) === 'string' ? arguments[1] : '') + '"><div class="inner">' + arguments[0] + '</div></div>';
                template = $(template);

                params.template = template;
                params.lifespan = typeof (arguments[1]) === 'number' ? arguments[1] : typeof (arguments[2]) === 'number' ? arguments[2] : 2000;//默认2秒后自动关闭
                params.hasOverlay = false;
            }
            debugger;
           return sys.dialog(params);
        }
    };
    sys.confirm = function () {
        var params = {
            overlayTran: true,
            effect: 'fadeDown',
            skin: 's-confirm'
        };
        var text, title , func;
        text = arguments.length > 0 ? arguments[0] : '';
        title = arguments.length > 1 && typeof(arguments[1]) === "string" ? arguments[1] : '提示';
        func = arguments.length > 1 && typeof(arguments[1]) === "function" ? arguments[1] : false;
        func = arguments.length > 2 && typeof(arguments[2]) === "function" ? arguments[2] : func;

        var template = '<div class="u-dialog-confirm-text">' + text + '</div><div class="u-dialog-confirm-foot"><a class="yes js_btn" href="javascript:;">是</a><a class="no js_btn" href="javascript:;">否</a></div>';
        template = $(template);
        template.find('a.js_btn').on('click', function () {
            dialog.close();
            func($(this).hasClass('yes'));
        });
        params.template = template;
        params.title = title;
        var dialog = sys.alert(params);
    };

    //资金密码确认
    sys.dialog.codeValidator = function(borrow_no){
        var temp = $('<div class="m-dialog-codeValidator"><p><input type="password" id="password" placeholder="请输入资金密码"/></p><p class="agreement"><span><input type="checkbox" name="agreement" value="1" checked="true" /></span><span>接受<a href="/index.php/terms?borrow_no=' + borrow_no + '&money=' + $('#p-'+borrow_no).val() + '" title="委托借款协议" target="_blank">委托借款协议</a>和<a href="/index.php/terms/claims?borrow_no=' + borrow_no + '&money=' + $('#p-'+borrow_no).val() + '" title="债权转让协议" target="_blank">债权转让协议</a></span></p><a class="button" id="pay_btn" href="javascript:;">确定</a></div>');
        var dialog = sys.alert({
            title: '资金密码验证',
            template: temp,
            onLoad: function () {
                temp.find('#pay_btn').click(function(){
                  var obj  = new Object();
                  obj.btn  = $('#b-'+borrow_no);
                  obj.val  = $('#p-'+borrow_no).val();

                  obj.max  = obj.btn.attr('data-max');
                  obj.min  = obj.btn.attr('data-min');
                  obj.val  = parseFloat(obj.val);
                  obj.pass = $('#password').val();

                  if(obj.pass.length > 0){
                      if( ! isNaN(obj.val)) {
                    if(obj.val >= obj.min && obj.val <= obj.max){
                          $.ajax({
                             type: 'POST',
                             url: '/index.php/home/invest',
                             data: {amount:obj.val,borrow_no:borrow_no,password:obj.pass},
                             dataType:'json',
                             success: function(result){
                              if(result.code == 0){
                                dialog.close();
                                sys.alert(result.msg);
                                setTimeout(function(){window.location.reload();},2000);
                              }else{
                                sys.alert(result.msg,'z-error');
                                if(result.targeturl!=''){
                                    setTimeout(function(){window.location.href=result.targeturl;},2000);
                                }
                                dialog.close();
                              }
                             }
                          });
                        }else{
                          sys.alert('你最多可以投'+obj.max+'元(最少需要投'+obj.min+')','z-error');
                          dialog.close();
                        }
                      }else{
                        sys.alert('请重新输入你的投资金额','z-error');
                        dialog.close();
                      }
                  }else{
                    sys.alert('资金密码不能为空！','z-error');
                  }
                })
            }
        });
    };

    //解除银行卡绑定
    sys.dialog.unbind = function(card_no){
        var temp = $('<div class="m-dialog-codeValidator"><input type="password" id="password" placeholder="请输入资金密码"/><a class="button" id="pay_btn" href="javascript:;">确定</a></div>');
        var dialog = sys.alert({
            title: '资金密码验证',
            template: temp,
            onLoad: function () {
                temp.find('#pay_btn').click(function(){
                  var obj  = new Object();
                  obj.card_no = card_no;
                  obj.pass = $('#password').val();
                  if(obj.pass.length > 0 && obj.card_no.length > 0){
                          $.ajax({
                             type: 'POST',
                             url: '/index.php/user/account/unbind',
                             data: {card_no:obj.card_no,password:obj.pass},
                             dataType:'json',
                             success: function(result){
                                if(result.code == 0){
                                    sys.alert(result.msg);
                                    setTimeout(function(){window.location.reload();},2000);
                                }else{
                                    sys.alert(result.msg,'z-error');
                                    dialog.close();
                                }
                             }
                          });

                  }else{
                    sys.alert('资金密码不能为空！','z-error');
                  }
                })
            }
        });
    };

    /*手机注册*/
    sys.dialog.smscode = function(mobile,pass,referrer,invite_code){
                var temp = $('<div class="m-dialog-codeValidator" style="width:300px;" ><table cellpadding="5"><tr><td><input type="text" id="txtsmscode" name="authcode" value="" maxlength="6"  style="width:120px;" data-tip="" /></td><td><input class="u-form-send-before" id="send-sms" type="button" style="width:130px;height:40px;color:#fff" value="获取短信验证码"/></td></tr></table><br/><a class="button" id="pay_btn" href="javascript:;">确 定</a> </div>');

                var dialog = sys.alert({
                    title: '请输入短信验证码',
                    template: temp,
                    onLoad: function () {
                        temp.find('#pay_btn').click(function(){
                          var smscode=temp.find("#txtsmscode").val();
                          if(smscode=="")
                          {
                            sys.alert('请输入验证码','z-error');
                          }
                          else if(!/^[0-9]{6}$/.test(smscode))
                          {
                            sys.alert('请输入有效验证码','z-error');
                          }
                          else{
                              var obj  = new Object();
                              obj.mobile = mobile;
                              obj.pass = pass;

                              if(obj.pass.length > 0 && obj.mobile.length > 0){
                                  $.ajax({
                                     type: 'POST',
                                     url: '/index.php/login/register',
                                     data: {mobile:obj.mobile,password:obj.pass,referrer:referrer,retype:obj.pass,authcode:smscode,invite_code:invite_code},
                                     dataType:'json',
                                     success: function(result){
                                        if(result.code == 0){
                                            sys.alert(result.msg);
                                            setTimeout(function(){window.location.href='/index.php';},1000);
                                        }else{
                                            sys.alert(result.msg,'z-error');
                                        }
                                     }
                                  });

                              }
                          }

                        });

                        temp.find('#send-sms').click(function(){
                          var obj = temp.find('#send-sms');
                          var myreg = /^1[0-9]+\d{9}$/;
                         var wait = 60;

                           $.ajax({
                              url:'/index.php/send/sms',
                              dataType:'json',
                              data:{act:'regsiter',mobile:mobile,captcha:$("#js_captcha").val()},
                              success:function(result)
                              {
                                if(result.code == 0){
                                  sys.alert(result.msg);
                                  $(obj).attr("disabled", true);
                                  time(obj);
                                  $(obj).removeClass();
                                  $(obj).addClass("u-form-send");
                                }else{
                                  sys.alert(result.msg,'z-error');
                                  setTimeout(function(){window.location.href='/index.php/login/register';},2000);
                                }
                                 //sys.alert($("#js_captcha").val());
							

                                function time(obj) {
                                    if (wait == 0) {
                                        $(obj).attr("disabled",false);
                                        $(obj).css("color",'#fff');
                                        $(obj).val("重新获取验证码");
                                        wait = 60;
                                        $(obj).removeClass();
                                        $(obj).addClass("u-form-send-before");
                                    } else {
                                            $(obj).css("color",'#000');
                                         $(obj).val("重新发送(" + wait + ")");
                                         $(obj).attr("disabled", true);
                                        wait--;
                                        setTimeout(function() {time(obj)},1000)
                                    }

                                }
                              }
                            })

                        });

                        temp.find('#send-sms').click();

                    }
                });


     };

    /**
     * 图片懒加载
     */
    sys.lazyLoad = (function (global, $, document, undefined) {
        var lazyStore = [];

        var load = function () {
            lazyStore = [];
            $('img[data-lazy]').each(function (index, obj) {
                lazyStore.push(obj);
            });
        };

        var _intoView = function (elem) {
            var coords = elem.getBoundingClientRect();
            return ((coords.top >= 0 && coords.left >= 0 && coords.top) <= (window.innerHeight || document.documentElement.clientHeight));
        };

        var _listen = function () {
            $(window).on('scroll', sys.throttle(function () {
                for (var i = 0; i < lazyStore.length; i++) {
                    var self = lazyStore[i];
                    if (_intoView(self)) {
                        self.src = self.getAttribute('data-lazy');
                        lazyStore.splice(i, 1);
                    }
                }
            }, 200)).trigger('scroll');
        };

        load();
        _listen();

        return {
            refresh: load
        };
    })(this, $, document);

    //返回顶部
    var backTop = function () {
        var $back = $('<a href="javascript:;" class="u-backTop"></a>'),
            $win = $(window);
        $('body').append($back);
        var ifShow = false;
        
        if ($win.scrollTop() >= 900) {
            ifShow = true;
            $back.fadeIn();
        }
        $win.on('scroll', function () {
            if ($win.scrollTop() >= 900 && !ifShow) {
                ifShow = true;
                $back.fadeIn();
            } else if ($win.scrollTop() < 900 && ifShow) {
                ifShow = false;
                $back.fadeOut();
            }
        }).trigger('scroll');
    };

    //系统初始化
    var init = function () {
        plugin.init();
        // backTop();
    };
    init();


    //bug修复
    bug = function () {
        //修复表单中行在IE7层次引起的bug
        if (sys.ieVersion === 7) {
            $('form').each(function () {
                var rows = $(this).children('.row'),
                    rowsCount = rows.size();
                rows.each(function (index) {
                    this.style.zIndex = rowsCount - index;
                });
                rows = $(this).children('.u-form-row');
                rowsCount = rows.size();
                rows.each(function (index) {
                    this.style.zIndex = rowsCount - index;
                });
            });
        }
    }();

    $(function(){
        $('.u-backTop').on('click',function(){
            $('body,html').stop().animate({
                'scrollTop': 0
            }, 500);
        });
    });

    window.sys = sys;
});