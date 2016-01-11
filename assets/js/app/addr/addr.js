/* 
 * 日期插件
 * 滑动选取日期（年，月，日）
 * V1.1
 */
(function ($) {
    $.fn.addr = function (options,Ycallback,Ncallback) {
        //插件默认选项
        var that = $(this);
        var docType = $(this).is('input');
        var datetime = false;
        var nowdate = new Date();
        var indexY=1,indexM=1,indexD=1;
        var indexH=1,indexI=1,indexS=0;
        var initY=parseInt((nowdate.getYear()+"").substr(1,2));
        var initM=parseInt(nowdate.getMonth()+"")+1;
        var initD=parseInt(nowdate.getDate()+"");
        var initH=parseInt(nowdate.getHours());
        var initI=parseInt(nowdate.getMinutes());
        var initS=parseInt(nowdate.getYear());
        var yearScroll=null,monthScroll=null,dayScroll=null;
        var HourScroll=null,MinuteScroll=null,SecondScroll=null;
        $.fn.addr.defaultOptions = {
            beginyear:2000,
            endyear:2020,
            beginmonth:1,
            endmonth:12,
            beginday:1,
            endday:31,
            beginhour:1,
            endhour:12,
            beginminute:00,
            endminute:59,
            curdate:false,
            theme:"date",
            mode:null,
            event:"click",
            show:true
        }
        //用户选项覆盖插件默认选项   
        var opts = $.extend( true, {}, $.fn.addr.defaultOptions, options );
        if(opts.theme === "datetime"){datetime = true;}
        if(!opts.show){
            that.unbind('click');
        }
        else{
            //绑定事件（默认事件为获取焦点）
            that.bind(opts.event,function () {
                createUL();      //动态生成控件显示的日期
                init_iScrll();   //初始化iscrll
                extendOptions(); //显示控件
                that.blur();
                if(datetime){
                    showdatetime();
                    refreshTime();
                }
                refreshDate();
                bindButton();
            })
        };
        function refreshDate(){
            yearScroll.refresh();
            monthScroll.refresh();
            dayScroll.refresh();

            resetInitDete();
            yearScroll.scrollTo(0, 0, 100, true);
            monthScroll.scrollTo(0, 0, 100, true);
            dayScroll.scrollTo(0, 0, 100, true);
        }
        function refreshTime(){
            HourScroll.refresh();
            MinuteScroll.refresh();
            SecondScroll.refresh();
            if(initH>12){    //判断当前时间是上午还是下午
                SecondScroll.scrollTo(0, initD*40-40, 100, true);   //显示“下午”
                initH=initH-12-1;
            }
            HourScroll.scrollTo(0, initH*40, 100, true);
            MinuteScroll.scrollTo(0, initI*40, 100, true);
            initH=parseInt(nowdate.getHours());
        }
        function resetIndex(){
            indexY=1;
            indexM=1;
            indexD=1;
        }
        function resetInitDete(){
            if(opts.curdate){return false;}
            else if(that.val()===""){return false;}
            initY = parseInt(that.val().substr(2,2));

            initM = parseInt(that.val().substr(5,2));
            initD = parseInt(that.val().substr(8,2));
        }
        function bindButton(){
            resetIndex();
            $("#dateconfirm").unbind('click').click(function () {
                var datestr = $("#yearwrapper ul li:eq("+indexY+")").text()+"-"+ $("#monthwrapper ul li:eq("+indexM+")").text()+"-"+ $("#daywrapper ul li:eq("+Math.round(indexD)+")").text();

                $('#province').val($("#yearwrapper ul li:eq("+indexY+")").attr('name'));
                $('#city1').val($("#yearwrapper ul li:eq("+indexM+")").attr('name'));
                $('#district').val($("#yearwrapper ul li:eq("+indexD+")").attr('name'));

                if(Ycallback===undefined){
                    if(docType){that.val(datestr);}else{that.html(datestr);}
                }else{
                    Ycallback(datestr);
                }
                $("#datePage").hide();
                $("#dateshadow").hide();
            });
            $("#datecancle").click(function () {
                $("#datePage").hide();
                $("#dateshadow").hide();
                callback(false);
            });
        }
        function extendOptions(){
            $("#datePage").show();
            $("#dateshadow").show();
        }



        // 滑动   ajax 操作

        //获取地址的ajax
        var get_region=function(pid,type){
            $.ajaxSetup({
                async : false
            });
            if(type)type = 'city';
            var data = '';
            $.post('/index.php/mobiles/home/ajax_get_region_list?region_id='+pid+'&type='+type,{},function(rs){
                data = rs;
            },'json');
            return data;
        }

        function init_iScrll() {
            yearScroll = new iScroll("yearwrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function () {
                    indexY = (this.y/40)*(-1)+1;
                    var strY = $("#yearwrapper ul li:eq("+indexY+")").attr("name");
                    var citys = '';
                    // 获取城市的 ajax
                    //alert(strM) //这个是  获取的 城市里面的 name值  相当于是ID
                    var citys = get_region(strY,true);
                    var str = '';
                    var str1 = '';
                    if(citys){
                        $(citys.city).each(function(i,v){
                            str += '<li name="'+ v.region_id+'">'+ v.region_name+'</li>';
                        });
                        $(citys.district).each(function(i,v){
                            str1 += '<li name="'+ v.region_id+'">'+ v.region_name+'</li>';
                        });
                    }

                    $("#monthwrapper ul").html("<li>&nbsp;</li>"+str+"<li>&nbsp;</li>");  //获取的字符串拼接到这里
                    $("#daywrapper ul").html("<li>&nbsp;</li>"+str1+"<li>&nbsp;</li>"); //拼接
                    monthScroll.refresh();
                }});
            monthScroll = new iScroll("monthwrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function (){
                    var strM = $("#monthwrapper ul li:eq("+indexM+")").attr("name");
                    var distinct = '';
                    distinct = get_region(strM,false);
                    var str = '';
                    if(distinct){
                        $(distinct).each(function(i,v){
                            str += '<li name="'+ v.region_id+'">'+ v.region_name+'</li>';
                        });
                    }
                    //alert(strM)
                    indexM = (this.y/40)*(-1)+1;

                    $("#daywrapper ul").html("<li>&nbsp;</li>"+str+"<li>&nbsp;</li>");
                    dayScroll.refresh();
                }});


            dayScroll = new iScroll("daywrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function () {
                    indexD = (this.y/40)*(-1)+1;
                }});
        }

        function showdatetime(){
            init_iScroll_datetime();
            addTimeStyle();
            $("#datescroll_datetime").show();
            $("#Hourwrapper ul").html(createHOURS_UL());
            $("#Minutewrapper ul").html(createMINUTE_UL());
            $("#Secondwrapper ul").html(createSECOND_UL());
        }

        //日期+时间滑动
        function init_iScroll_datetime(){
            HourScroll = new iScroll("Hourwrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function () {
                    indexH = Math.round((this.y/40)*(-1))+1;
                    HourScroll.refresh();
                }})
            MinuteScroll = new iScroll("Minutewrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function () {
                    indexI = Math.round((this.y/40)*(-1))+1;
                    HourScroll.refresh();
                }})
            SecondScroll = new iScroll("Secondwrapper",{snap:"li",vScrollbar:false,
                onScrollEnd:function () {
                    indexS = Math.round((this.y/40)*(-1));
                    HourScroll.refresh();
                }})
        }
        function checkdays (year,month){
            var new_year = year;    //取当前的年份        
            var new_month = month++;//取下一个月的第一天，方便计算（最后一天不固定）        
            if(month>12)            //如果当前大于12月，则年份转到下一年        
            {
                new_month -=12;        //月份减        
                new_year++;            //年份增        
            }
            var new_date = new Date(new_year,new_month,1);                //取当年当月中的第一天        
            return (new Date(new_date.getTime()-1000*60*60*24)).getDate();//获取当月最后一天日期    
        }
        function  createUL(){
            CreateDateUI();
            $("#yearwrapper ul").html(createYEAR_UL());
            $("#monthwrapper ul").html(createMONTH_UL());
            $("#daywrapper ul").html(createDAY_UL());
        }
        function CreateDateUI(){
            var str = ''+
                '<div id="dateshadow"></div>'+
                '<div id="datePage" class="page">'+
                '<section>'+
                '<div id="datetitle"><h1>选择地址</h1></div>'+
                '<div id="datemark"><a id="markyear"></a><a id="markmonth"></a><a id="markday"></a></div>'+
                '<div id="timemark"><a id="markhour"></a><a id="markminut"></a><a id="marksecond"></a></div>'+
                '<div id="datescroll">'+
                '<div id="yearwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="monthwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="daywrapper">'+
                '<ul></ul>'+
                '</div>'+
                '</div>'+
                '<div id="datescroll_datetime">'+
                '<div id="Hourwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="Minutewrapper">'+
                '<ul></ul>'+
                '</div>'+
                '<div id="Secondwrapper">'+
                '<ul></ul>'+
                '</div>'+
                '</div>'+
                '</section>'+
                '<footer id="dateFooter">'+
                '<div id="setcancle">'+
                '<ul>'+
                '<li id="dateconfirm">确定</li>'+
                '<li id="datecancle">取消</li>'+
                '</ul>'+
                '</div>'+
                '</footer>'+
                '</div>'
            $("#datePlugin").html(str);
        }
        function addTimeStyle(){
            $("#datePage").css("height","380px");
            $("#datePage").css("top","60px");
            $("#yearwrapper").css("position","absolute");
            $("#yearwrapper").css("bottom","200px");
            $("#monthwrapper").css("position","absolute");
            $("#monthwrapper").css("bottom","200px");
            $("#daywrapper").css("position","absolute");
            $("#daywrapper").css("bottom","200px");
        }
        //创建 --年-- 列表
        function createYEAR_UL(){
            return cityone
        }
        //创建 --月-- 列表
        function createMONTH_UL(){
            $("#monthwrapper ul").html("");
            return citytwo
        }

        function createDAY_UL(){
            $("#daywrapper ul").html("");
            return citythree
        }



        function createHOURS_UL(){
            var str="<li>&nbsp;</li>";
            for(var i=opts.beginhour;i<=opts.endhour;i++){
                str+='<li>'+i+'时</li>'
            }
            return str+"<li>&nbsp;</li>";;
        }
        //创建 --分-- 列表
        function createMINUTE_UL(){
            var str="<li>&nbsp;</li>";
            for(var i=opts.beginminute;i<=opts.endminute;i++){
                if(i<10){
                    i="0"+i
                }
                str+='<li>'+i+'分</li>'
            }
            return str+"<li>&nbsp;</li>";;
        }
        //创建 --分-- 列表
        function createSECOND_UL(){
            var str="<li>&nbsp;</li>";
            str+="<li>上午</li><li>下午</li>"
            return str+"<li>&nbsp;</li>";;
        }
    }
})(jQuery);  
