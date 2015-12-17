// JavaScript Document
$(function(){
    //gdFun("#homeslider");
    //bfbFun();
    chooseOne("#JS_chooseone>li","#JS_co_input");
    tabFun("#tab_title","#tab_nr");
});

function  gdFun(obj){
    if($(obj).length<=0){ return null;}
    $(obj).flexslider({
        animation:"slide"
    });
}

function bfbFun(){
    if($(".bfb").length<=0){ return null;}
    $(".bfb").each(function(index, element) {
        var v = $(this).attr("data-bfb");
        $(this).radialIndicator({
            barColor: {
                0: '#004ea2',
                33: '#004ea2',
                66: '#004ea2',
                100: '#004ea2'
            },
            initValue: v,
            barWidth:10,
            percentage: true
        });
    });
}

function chooseOne(li,input){
    if($(li).length<=0){return null;}
    $(li).on("tap",function(){
        var t = $(this);
        t.parent().children("li").removeClass("current");
        t.addClass("current");
        $(input).val(t.children("a").attr('val'));
    });
}

function tabFun(t,nr){
    if($(t).length<=0){
        return null;
    }
    $(t).find(".tab_ts").click(function(){
        var n = $(this).index();
        var tb = $(nr).find(".tab_block");
        $(t).find(".tab_ts").removeClass("current");
        $(this).addClass("current");
        tb.hide();
        tb.eq(n).show();
    });
}

/*alert*/
function closeAlert(t){
    t.parentNode.parentNode.parentNode.removeChild(t.parentNode.parentNode)
}
function alertFun(txt,n){
    if( $("div.myalert").length>=1){
        return null;
    }
    var alert_time = null;
    var alertdiv = document.createElement("div");
    var alertdiv_nr = document.createElement("div");
    alertdiv.className ="info_black text-center myalert";
    alertdiv_nr.className = "rel";
    //alertdiv_nr.innerHTML = "<i onclick='closeAlert(this)' class='f16 abs c_fff' style='border-radius:50%; border:1px #fff solid;background:#171717; display:inline-block; width:30px; height:30px; line-height:30px; right:-25px; top:-25px;'>X</i>"+txt;
    alertdiv_nr.innerHTML =txt;
    alertdiv.appendChild(alertdiv_nr);
    document.getElementsByTagName("body")[0].appendChild(alertdiv);
    alertdiv.style.marginTop=(0-parseFloat(alertdiv.offsetHeight)/2)+"px"
    alert_time=setTimeout(function(){
        alertdiv.parentNode.removeChild(alertdiv);
    },n*1000);

}

/*判断余额*/
function checkYuE(id,ye){
    var zijin = document.getElementById(id);
    var shuz = /^\d{1,}\.?\d{0,}$/ig;
    zijin.value = zijin.value.replace(/\s/ig,"");
    if(!shuz.test(zijin.value)){//判断输入金额的格式，是否全是数字
        alertFun("请输入正确的投标金额",1);
        return false;
    }
    if(Number(zijin.value)<ye){//判断金额是否大于100
        alertFun("账户余额不足",1);
        return false;
    }
    return true;
}
//close popup
$('.cd-popup').on('click', function(event){
    if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
        event.preventDefault();
        $(this).removeClass('is-visible');
    }
});



var to_app_login=function(){
    window.Jxq.login();
}

var borrow_mode=function(mode){
    if(mode){
        switch (mode){
            case '1':
                return '先息后本';
            case '2':
                return '等额本息';
            case '3':
                return '一次性本息';
            case '4':
                return '等额本金';
        }
    }
},
    price_format=function(price,format,flag){
        if(isNaN(price))price=0;
        switch (format){
            case 1:
                price=parseInt(price);
                break;
            case 2:
                break;
            case 3:
                price = (price/10000);
                price=price.toFixed(2);
                break;
            case 4:
                break;
            default :
                price = Math.round(price,2);
        }
        return flag?'¥'+price:price;
    },
    rate_format=function(rate){
        if(rate > 0){
            rate= parseFloat(rate);
        }
        return rate;
    },
    scroll_fun=function(fun){
        var scrollTop = $(this).scrollTop(),scrollHeight = $(document).height(),windowHeight = $(this).height();
        if(scrollTop + windowHeight == scrollHeight){
            if(typeof fun == "function")fun();
        }
    },
    unixtime_style = function(unixtime,format){
        if(!unixtime)return '无';
        var timestr = new Date(parseInt(unixtime) * 1000);
        if(!format)return timestr.getFullYear()+"-"+timestr.getMonth()+1+"-"+timestr.getDate()+" "+timestr.getHours()+":"+timestr.getMinutes()+":"+timestr.getSeconds();
        format=format.replace("Y",timestr.getFullYear());
        format=format.replace("y",timestr.getYear());
        format=format.replace("m",timestr.getMonth()+1);
        format=format.replace("d",timestr.getDate());
        format=format.replace("w",timestr.getDay()||7);
        format=format.replace("H",timestr.getHours());
        format=format.replace("h",timestr.getHours());
        format=format.replace("i",timestr.getMinutes());
        format=format.replace("s",timestr.getSeconds());
        if(timestr.getHours() > 12){
            format=format.replace("am",'pm');
            format=format.replace("上午",'下午');
        }
        return format;
    };

//(function(){
//    $(document).ajaxStart(function(){
//        if($(this).find('#wait-img').length == 0){
//            $('body').append('<img id="wait-img" src="/assets/images/app/wait.gif" style="position: absolute;z-index:9999;"/>');
//            $('#wait-img').css({'top':(window.innerHeight-100)/2,'left':(window.innerWidth-100)/2})
//        }
//    });
//    $(document).ajaxStop(function(){
//        $('#wait-img').remove();
//    });
//})();