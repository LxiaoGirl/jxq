define(function (require, exports, module) {
    require('jquery');
    var pluginName = 'scroll';
    var config = {
        pageCount: 0,
        moveCount: 0,
        moveDrift: 0,
        play: false,
        playTime: 5000,
        playCss: '',
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
                size  = slides.children().size(),
                maxDrift = (size - opts.pageCount) * opts.moveDrift,
                maxPage = Math.ceil(size / opts.moveCount) - (opts.pageCount - opts.moveCount);

            var page = '<ul class="slider-page">';
            for (var i = 0; i < maxPage; i++) {
                page += '<li ' + (i === 0 ? 'class="z-sel"' : '') + '></li>';
            }
            page += '</ul>';
            page = $(page);

            var prev = $('<a class="slider-prev" style="display: none;" href="javascript:;"></a>'),
                next = $('<a class="slider-next" style="display: none;" href="javascript:;"></a>');

            //不需要生成
            //elem.append(page).append(prev).append(next);

            if (maxPage > 1) {
                next.show();
            }

            var pages = page.children(),
                time = null,
                animateObj = null;

            pages.on(opts.playEvent, function () {
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
                if (current < maxPage - 1) {
                    change(10);
                }
            });

            var change = function (delay) {
                clearTimeout(time);
                time = setTimeout(function () {
                    current++;
                    if (current == maxPage) {
                        current = 0;
                    }

                    prev.hide();
                    next.hide();

                    if (current > 0) {
                        prev.show();
                    }
                    if (current < maxPage - 1) {
                        next.show();
                    }
                    var drift = current * opts.moveCount * opts.moveDrift;
                    drift = Math.abs(drift) > Math.abs(maxDrift) ? maxDrift : drift;

                    pages.removeClass('z-sel').eq(current).addClass('z-sel');
                    animateObj = {};
                    animateObj[opts.playCss] = drift;
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
});