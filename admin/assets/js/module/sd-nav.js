seajs.use('jquery', function () {
    $(function () {
        var box = $('#js_sdNav'),
            nav = box.find('.nav'),
            floor = box.find('.floor'),
            navs = nav.find('dd'),
            nav_sel = nav.find('dd[class^="z-sel"]');

        var curT = nav_sel.position().top;

        var move = function (top) {
            floor.stop(true, true).animate({
                top: top
            }, 200);
        };

        floor.css({
            top: curT
        });

        navs.on('mouseenter', function () {
            move($(this).position().top);
        });
        nav.on('mouseleave', function () {
            move(curT);
        });
    });
});
