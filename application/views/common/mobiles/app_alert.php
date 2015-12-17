<!-- 弹出消息层 -->
<!--<div id="alert" class="info_black text-center" style="display: none"></div>-->
<!-- 弹出消息层 end-->
<script>
//    var my_alert=function(text){
//        $("#alert").text(text).show();
//        setTimeout(function(){ $("#alert").fadeOut(500);},1000);
//    }
    var my_alert=function(text,t,fun){
        if(typeof t == "undefined" ||isNaN(t)){
            if(typeof fun == "function"){
                layer.msg(text, function(){
                    fun();
                });
            }else{
                layer.msg(text);
            }
        }else{
            t=parseInt(t);
            if(t <= 0)t=1;
            t=t*1000;
            if(typeof fun == "function"){
                layer.msg(text,{time:t},function(){
                    fun();
                });
            }else{
                layer.msg(text,{time:t});
            }
        }
//        alertFun(text,t);
    }
</script>