define(function (require, exports, module) {
    var $ = require('jquery');

    var Plugin, defaults, pluginName;
    pluginName = "slider";
    defaults = {
        width: 940,
        height: 528,
        start: 1,
        navigation: {
            active: true,
            effect: "slide"
        },
        pagination: {
            active: true,
            effect: "slide"
        },
        play: {
            active: false,
            effect: "slide",
            interval: 5000,
            auto: false,
            swap: true,
            pauseOnHover: false,
            restartDelay: 2500
        },
        effect: {
            slide: {
                speed: 500
            },
            fade: {
                speed: 300,
                crossfade: true
            }
        },
        callback: {
            loaded: function() {},
            start: function() {},
            complete: function() {}
        }
    };
    Plugin = (function() {

        function Plugin(element, options) {
            this.element = element;
            this.options = $.extend(true, {}, defaults, options);
            this._defaults = defaults;
            this._name = pluginName;
            this.init();
        }

        return Plugin;

    })();
    Plugin.prototype.init = function() {
        var $element, nextButton, pagination, playButton, prevButton, stopButton,
            _this = this;
        $element = $(this.element);
        this.data = $.data(this);
        $.data(this, "animating", false);
        $.data(this, "total", $element.children().not(".slider-navigation", $element).length);
        $.data(this, "current", this.options.start - 1);
        $.data(this, "vendorPrefix", this._getVendorPrefix());
        if (typeof TouchEvent !== "undefined") {
            $.data(this, "touch", true);
            this.options.effect.slide.speed = this.options.effect.slide.speed / 2;
        }
        $element.css({
            overflow: "hidden"
        });
        $element.slidesContainer = $element.children().not(".slider-navigation", $element).wrapAll("<div class='slider-container'>", $element).parent().css({
            overflow: "hidden",
            position: "relative"
        });
        $(".slider-container", $element).wrapInner("<div class='slider-control'>", $element).children();
        $(".slider-control", $element).css({
            position: "relative",
            left: 0
        });
        $(".slider-control", $element).children().addClass("slider-slide").css({
            position: "absolute",
            top: 0,
            left: 0,
            width: "100%",
            zIndex: 0,
            display: "none",
            webkitBackfaceVisibility: "hidden"
        });
        $.each($(".slider-control", $element).children(), function(i) {
            var $slide;
            $slide = $(this);
            return $slide.attr("slider-index", i);
        });
        if (this.data.touch) {
            $(".slider-control", $element).on("touchstart", function(e) {
                return _this._touchstart(e);
            });
            $(".slider-control", $element).on("touchmove", function(e) {
                return _this._touchmove(e);
            });
            $(".slider-control", $element).on("touchend", function(e) {
                return _this._touchend(e);
            });
        }
        $element.fadeIn(0);
        this.update();
        if (this.data.touch) {
            this._setuptouch();
        }
        $(".slider-control", $element).children(":eq(" + this.data.current + ")").eq(0).fadeIn(0, function() {
            return $(this).css({
                zIndex: 10
            });
        });
        if (this.options.navigation.active) {
            prevButton = $("<a>", {
                "class": "slider-previous slider-navigation",
                href: "#",
                title: "Previous",
                text: "Previous"
            }).appendTo($element);
            nextButton = $("<a>", {
                "class": "slider-next slider-navigation",
                href: "#",
                title: "Next",
                text: "Next"
            }).appendTo($element);
        }
        $(".slider-next", $element).click(function(e) {
            e.preventDefault();
            _this.stop(true);
            return _this.next(_this.options.navigation.effect);
        });
        $(".slider-previous", $element).click(function(e) {
            e.preventDefault();
            _this.stop(true);
            return _this.previous(_this.options.navigation.effect);
        });
        if (this.options.play.active) {
            playButton = $("<a>", {
                "class": "slider-play slider-navigation",
                href: "#",
                title: "Play",
                text: "Play"
            }).appendTo($element);
            stopButton = $("<a>", {
                "class": "slider-stop slider-navigation",
                href: "#",
                title: "Stop",
                text: "Stop"
            }).appendTo($element);
            playButton.click(function(e) {
                e.preventDefault();
                return _this.play(true);
            });
            stopButton.click(function(e) {
                e.preventDefault();
                return _this.stop(true);
            });
            if (this.options.play.swap) {
                stopButton.css({
                    display: "none"
                });
            }
        }
        if (this.options.pagination.active) {
            pagination = $("<ul>", {
                "class": "slider-pagination"
            }).appendTo($element);
            $.each(new Array(this.data.total), function(i) {
                var paginationItem, paginationLink;
                paginationItem = $("<li>", {
                    "class": "slider-pagination-item"
                }).appendTo(pagination);
                paginationLink = $("<a>", {
                    href: "#",
                    "data-slider-item": i,
                    
                }).appendTo(paginationItem);
                return paginationLink.click(function(e) {
                    e.preventDefault();
                    _this.stop(true);
                    return _this.goto(($(e.currentTarget).attr("data-slider-item") * 1) + 1);
                });
            });
        }
        $(window).bind("resize", function() {
            return _this.update();
        });
        this._setActive();
        if (this.options.play.auto) {
            this.play();
        }
        return this.options.callback.loaded(this.options.start);
    };
    Plugin.prototype._setActive = function(number) {
        var $element, current;
        $element = $(this.element);
        this.data = $.data(this);
        current = number > -1 ? number : this.data.current;
        $(".active", $element).removeClass("active");
        return $(".slider-pagination li:eq(" + current + ") a", $element).addClass("active");
    };
    Plugin.prototype.update = function() {
        var $element, height, width;
        $element = $(this.element);
        this.data = $.data(this);
        $(".slider-control", $element).children(":not(:eq(" + this.data.current + "))").css({
            display: "none",
            left: 0,
            zIndex: 0
        });
        width = $element.width();
        height = (this.options.height / this.options.width) * width;
        this.options.width = width;
        this.options.height = height;
        return $(".slider-control, .slider-container", $element).css({
            width: width,
            height: height
        });
    };
    Plugin.prototype.next = function(effect) {
        var $element;
        $element = $(this.element);
        this.data = $.data(this);
        $.data(this, "direction", "next");
        if (effect === void 0) {
            effect = this.options.navigation.effect;
        }
        if (effect === "fade") {
            return this._fade();
        } else {
            return this._slide();
        }
    };
    Plugin.prototype.previous = function(effect) {
        var $element;
        $element = $(this.element);
        this.data = $.data(this);
        $.data(this, "direction", "previous");
        if (effect === void 0) {
            effect = this.options.navigation.effect;
        }
        if (effect === "fade") {
            return this._fade();
        } else {
            return this._slide();
        }
    };
    Plugin.prototype.goto = function(number) {
        var $element, effect;
        $element = $(this.element);
        this.data = $.data(this);
        if (effect === void 0) {
            effect = this.options.pagination.effect;
        }
        if (number > this.data.total) {
            number = this.data.total;
        } else if (number < 1) {
            number = 1;
        }
        if (typeof number === "number") {
            if (effect === "fade") {
                return this._fade(number);
            } else {
                return this._slide(number);
            }
        } else if (typeof number === "string") {
            if (number === "first") {
                if (effect === "fade") {
                    return this._fade(0);
                } else {
                    return this._slide(0);
                }
            } else if (number === "last") {
                if (effect === "fade") {
                    return this._fade(this.data.total);
                } else {
                    return this._slide(this.data.total);
                }
            }
        }
    };
    Plugin.prototype._setuptouch = function() {
        var $element, next, previous, slidesControl;
        $element = $(this.element);
        this.data = $.data(this);
        slidesControl = $(".slider-control", $element);
        next = this.data.current + 1;
        previous = this.data.current - 1;
        if (previous < 0) {
            previous = this.data.total - 1;
        }
        if (next > this.data.total - 1) {
            next = 0;
        }
        slidesControl.children(":eq(" + next + ")").css({
            display: "block",
            left: this.options.width
        });
        return slidesControl.children(":eq(" + previous + ")").css({
            display: "block",
            left: -this.options.width
        });
    };
    Plugin.prototype._touchstart = function(e) {
        var $element, touches;
        $element = $(this.element);
        this.data = $.data(this);
        touches = e.originalEvent.touches[0];
        this._setuptouch();
        $.data(this, "touchtimer", Number(new Date()));
        $.data(this, "touchstartx", touches.pageX);
        $.data(this, "touchstarty", touches.pageY);
        return e.stopPropagation();
    };
    Plugin.prototype._touchend = function(e) {
        var $element, duration, prefix, slidesControl, timing, touches, transform,
            _this = this;
        $element = $(this.element);
        this.data = $.data(this);
        touches = e.originalEvent.touches[0];
        slidesControl = $(".slider-control", $element);
        if (slidesControl.position().left > this.options.width * 0.5 || slidesControl.position().left > this.options.width * 0.1 && (Number(new Date()) - this.data.touchtimer < 250)) {
            $.data(this, "direction", "previous");
            this._slide();
        } else if (slidesControl.position().left < -(this.options.width * 0.5) || slidesControl.position().left < -(this.options.width * 0.1) && (Number(new Date()) - this.data.touchtimer < 250)) {
            $.data(this, "direction", "next");
            this._slide();
        } else {
            prefix = this.data.vendorPrefix;
            transform = prefix + "Transform";
            duration = prefix + "TransitionDuration";
            timing = prefix + "TransitionTimingFunction";
            slidesControl[0].style[transform] = "translateX(0px)";
            slidesControl[0].style[duration] = this.options.effect.slide.speed * 0.85 + "ms";
        }
        slidesControl.on("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", function() {
            prefix = _this.data.vendorPrefix;
            transform = prefix + "Transform";
            duration = prefix + "TransitionDuration";
            timing = prefix + "TransitionTimingFunction";
            slidesControl[0].style[transform] = "";
            slidesControl[0].style[duration] = "";
            return slidesControl[0].style[timing] = "";
        });
        return e.stopPropagation();
    };
    Plugin.prototype._touchmove = function(e) {
        var $element, prefix, slidesControl, touches, transform;
        $element = $(this.element);
        this.data = $.data(this);
        touches = e.originalEvent.touches[0];
        prefix = this.data.vendorPrefix;
        slidesControl = $(".slider-control", $element);
        transform = prefix + "Transform";
        $.data(this, "scrolling", Math.abs(touches.pageX - this.data.touchstartx) < Math.abs(touches.pageY - this.data.touchstarty));
        if (!this.data.animating && !this.data.scrolling) {
            e.preventDefault();
            this._setuptouch();
            slidesControl[0].style[transform] = "translateX(" + (touches.pageX - this.data.touchstartx) + "px)";
        }
        return e.stopPropagation();
    };
    Plugin.prototype.play = function(next) {
        var $element, currentSlide, slidesContainer,
            _this = this;
        $element = $(this.element);
        this.data = $.data(this);
        if (!this.data.playInterval) {
            if (next) {
                currentSlide = this.data.current;
                this.data.direction = "next";
                if (this.options.play.effect === "fade") {
                    this._fade();
                } else {
                    this._slide();
                }
            }
            $.data(this, "playInterval", setInterval((function() {
                currentSlide = _this.data.current;
                _this.data.direction = "next";
                if (_this.options.play.effect === "fade") {
                    return _this._fade();
                } else {
                    return _this._slide();
                }
            }), this.options.play.interval));
            slidesContainer = $(".slider-container", $element);
            if (this.options.play.pauseOnHover) {
                slidesContainer.unbind();
                slidesContainer.bind("mouseenter", function() {
                    return _this.stop();
                });
                slidesContainer.bind("mouseleave", function() {
                    if (_this.options.play.restartDelay) {
                        return $.data(_this, "restartDelay", setTimeout((function() {
                            return _this.play(true);
                        }), _this.options.play.restartDelay));
                    } else {
                        return _this.play();
                    }
                });
            }
            $.data(this, "playing", true);
            $(".slider-play", $element).addClass("slider-playing");
            if (this.options.play.swap) {
                $(".slider-play", $element).hide();
                return $(".slider-stop", $element).show();
            }
        }
    };
    Plugin.prototype.stop = function(clicked) {
        var $element;
        $element = $(this.element);
        this.data = $.data(this);
        clearInterval(this.data.playInterval);
        if (this.options.play.pauseOnHover && clicked) {
            $(".slider-container", $element).unbind();
        }
        $.data(this, "playInterval", null);
        $.data(this, "playing", false);
        $(".slider-play", $element).removeClass("slider-playing");
        if (this.options.play.swap) {
            $(".slider-stop", $element).hide();
            return $(".slider-play", $element).show();
        }
    };
    Plugin.prototype._slide = function(number) {
        var $element, currentSlide, direction, duration, next, prefix, slidesControl, timing, transform, value,
            _this = this;
        $element = $(this.element);
        this.data = $.data(this);
        if (!this.data.animating && number !== this.data.current + 1) {
            $.data(this, "animating", true);
            currentSlide = this.data.current;
            if (number > -1) {
                number = number - 1;
                value = number > currentSlide ? 1 : -1;
                direction = number > currentSlide ? -this.options.width : this.options.width;
                next = number;
            } else {
                value = this.data.direction === "next" ? 1 : -1;
                direction = this.data.direction === "next" ? -this.options.width : this.options.width;
                next = currentSlide + value;
            }
            if (next === -1) {
                next = this.data.total - 1;
            }
            if (next === this.data.total) {
                next = 0;
            }
            this._setActive(next);
            slidesControl = $(".slider-control", $element);
            if (number > -1) {
                slidesControl.children(":not(:eq(" + currentSlide + "))").css({
                    display: "none",
                    left: 0,
                    zIndex: 0
                });
            }
            slidesControl.children(":eq(" + next + ")").css({
                display: "block",
                left: value * this.options.width,
                zIndex: 10
            });
            this.options.callback.start(currentSlide + 1);
            if (this.data.vendorPrefix) {
                prefix = this.data.vendorPrefix;
                transform = prefix + "Transform";
                duration = prefix + "TransitionDuration";
                timing = prefix + "TransitionTimingFunction";
                slidesControl[0].style[transform] = "translateX(" + direction + "px)";
                slidesControl[0].style[duration] = this.options.effect.slide.speed + "ms";
                return slidesControl.on("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", function() {
                    slidesControl[0].style[transform] = "";
                    slidesControl[0].style[duration] = "";
                    slidesControl.children(":eq(" + next + ")").css({
                        left: 0
                    });
                    slidesControl.children(":eq(" + currentSlide + ")").css({
                        display: "none",
                        left: 0,
                        zIndex: 0
                    });
                    $.data(_this, "current", next);
                    $.data(_this, "animating", false);
                    slidesControl.unbind("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd");
                    slidesControl.children(":not(:eq(" + next + "))").css({
                        display: "none",
                        left: 0,
                        zIndex: 0
                    });
                    if (_this.data.touch) {
                        _this._setuptouch();
                    }
                    return _this.options.callback.complete(next + 1);
                });
            } else {
                return slidesControl.stop().animate({
                    left: direction
                }, this.options.effect.slide.speed, (function() {
                    slidesControl.css({
                        left: 0
                    });
                    slidesControl.children(":eq(" + next + ")").css({
                        left: 0
                    });
                    return slidesControl.children(":eq(" + currentSlide + ")").css({
                        display: "none",
                        left: 0,
                        zIndex: 0
                    }, $.data(_this, "current", next), $.data(_this, "animating", false), _this.options.callback.complete(next + 1));
                }));
            }
        }
    };
    Plugin.prototype._fade = function(number) {
        var $element, currentSlide, next, slidesControl, value,
            _this = this;
        $element = $(this.element);
        this.data = $.data(this);
        if (!this.data.animating && number !== this.data.current + 1) {
            $.data(this, "animating", true);
            currentSlide = this.data.current;
            if (number) {
                number = number - 1;
                value = number > currentSlide ? 1 : -1;
                next = number;
            } else {
                value = this.data.direction === "next" ? 1 : -1;
                next = currentSlide + value;
            }
            if (next === -1) {
                next = this.data.total - 1;
            }
            if (next === this.data.total) {
                next = 0;
            }
            this._setActive(next);
            slidesControl = $(".slider-control", $element);
            slidesControl.children(":eq(" + next + ")").css({
                display: "none",
                left: 0,
                zIndex: 10
            });
            this.options.callback.start(currentSlide + 1);
            if (this.options.effect.fade.crossfade) {
                slidesControl.children(":eq(" + this.data.current + ")").stop().fadeOut(this.options.effect.fade.speed);
                return slidesControl.children(":eq(" + next + ")").stop().fadeIn(this.options.effect.fade.speed, (function() {
                    slidesControl.children(":eq(" + next + ")").css({
                        zIndex: 0
                    });
                    $.data(_this, "animating", false);
                    $.data(_this, "current", next);
                    return _this.options.callback.complete(next + 1);
                }));
            } else {
                return slidesControl.children(":eq(" + currentSlide + ")").stop().fadeOut(this.options.effect.fade.speed, (function() {
                    slidesControl.children(":eq(" + next + ")").stop().fadeIn(_this.options.effect.fade.speed, (function() {
                        return slidesControl.children(":eq(" + next + ")").css({
                            zIndex: 10
                        });
                    }));
                    $.data(_this, "animating", false);
                    $.data(_this, "current", next);
                    return _this.options.callback.complete(next + 1);
                }));
            }
        }
    };
    Plugin.prototype._getVendorPrefix = function() {
        var body, i, style, transition, vendor;
        body = document.body || document.documentElement;
        style = body.style;
        transition = "transition";
        vendor = ["Moz", "Webkit", "Khtml", "O", "ms"];
        transition = transition.charAt(0).toUpperCase() + transition.substr(1);
        i = 0;
        while (i < vendor.length) {
            if (typeof style[vendor[i] + transition] === "string") {
                return vendor[i];
            }
            i++;
        }
        return false;
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