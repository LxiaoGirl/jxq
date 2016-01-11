<script>
    var my_alert=function(text,t,fun){
        t = t || 1;
        t = parseInt(t);
        layer.msg(text,{time:t*1000},function(){
            if(typeof fun == "function")fun();
        });
    }
</script>