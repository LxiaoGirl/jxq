/**
 * Created by zhangd on 14-7-9.
 */

(function($){
    $.fn.showPic = function(){

        $.each(this, function(index, obj){
            /*点击小图切换大图*/
            $(obj).find('.thumbnail li a').click(function(){
                $(obj).find(".zoompic img").hide().attr({ "src": $(this).attr("href"), "title": $("> img", this).attr("title") });
                $(obj).find(".thumbnail li.current").removeClass("current");
                $(this).parents("li").addClass("current");
                return false;
            });

            $(obj).find('.thumbnail li a').mouseover(function(){
                $(obj).find(".zoompic img").hide().attr({ "src": $(this).attr("href"), "title": $("> img", this).attr("title") });
                $(obj).find(".thumbnail li.current").removeClass("current");
                $(this).parents("li").addClass("current");
                return false;
            });

            $(obj).find('.clickPrevious').click(function(){
                $(obj).find('.thumbnail li').each(function(index, obj0){
                    if($(obj0).attr("class")=='current'){
                        if(index!=0){
                            $(obj).find(".zoompic img").hide().attr({ "src": $(this).prev().children().children().attr("href"), "title": $("> img", $(this).prev().children().children()).attr("title") });
                            $(obj).find(".thumbnail li.current").removeClass("current");
                            $(this).prev().addClass("current");
                        }
                    }

                });
                return false;
            });

            $(obj).find('.clickNext').bind('click',function(){
                var i = -1;
                $(obj).find('.thumbnail li').each(function(index, obj1){
                    if($(obj1).attr("class")=='current'){
                        i = index+1;
                    }
                    if(i==index){
                        $(obj).find(".zoompic img").hide().attr({ "src": $(this).children().children().attr("href"), "title": $("> img", $(this).children().children()).attr("title") });
                        $(obj).find(".thumbnail li.current").removeClass("current");
                        $(this).addClass("current");
                    }
                });
                return false;
            });

            $(obj).find(".zoompic>img").load(function(){
                $(obj).find(".zoompic>img:hidden").show();
            });
            /*点击小图切换大图 end*/

            /*小图片左右滚动*/
            var $slider = $(obj).find('.slider ul');
            var $slider_child_l = $(obj).find('.slider ul li').length;
            var $slider_width = $(obj).find('.slider ul li').width();
            $slider.width($slider_child_l * $slider_width);

            var slider_count = 0;

            if ($slider_child_l <= 5) {
                $(obj).find('.btn-right').css({cursor: 'auto'});
                $(obj).find('.btn-right').addClass("dasabled");
            }

            $(obj).find('.btn-right').click(function() {
                if ($slider_child_l < 5 || slider_count >= $slider_child_l - 5) {
                    return false;
                }

                slider_count++;
                $slider.animate({left: '-=' + $slider_width + 'px'}, 'fast');
                slider_pic();
            });

            $(obj).find('.btn-left').click(function() {
                if (slider_count <= 0) {
                    return false;
                }
                slider_count--;
                $slider.animate({left: '+=' + $slider_width + 'px'}, 'fast');
                slider_pic();
            });

            function slider_pic() {
                if (slider_count >= $slider_child_l - 5) {
                    $(obj).find('.btn-right').css({cursor: 'auto'});
                    $(obj).find('.btn-right').addClass("dasabled");
                }
                else if (slider_count > 0 && slider_count <= $slider_child_l - 5) {
                    $(obj).find('.btn-left').css({cursor: 'pointer'});
                    $(obj).find('.btn-left').removeClass("dasabled");
                    $(obj).find('.btn-right').css({cursor: 'pointer'});
                    $(obj).find('.btn-right').removeClass("dasabled");
                }
                else if (slider_count <= 0) {
                    $(obj).find('.btn-left').css({cursor: 'auto'});
                    $(obj).find('.btn-left').addClass("dasabled");
                }
            }
            /*小图片左右滚动  end*/
        });
    };

})(jQuery);


$(document).ready(function (){

    $('.zoombox').showPic();
});
