define(function (require, exports, module) {
    var $ = require('jquery');
	require('sys');
    require('jqform');

    var pluginName = 'validate';
    var config = {};

    var deg = {
        mobile: /^1[3|4|5|7|8|9][0-9]{9}$/,
        mima: /^([a-zA-Z0-9_-])$/,
        phone: /^1[3|4|5|7|8][0-9]\d{4,8}$/,
        telephone:/^((\d{3,4}-)?\d{7,8})$|^(1[3|4|5|7|8|9][0-9]{9})$/,
        money100: /^[1-9]{1}\d{0,7}00$/,
        email: /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/,
        number: /^[0-9]*$/,
        numberpos: /^[1-9][0-9]*$/,
        int: /^-?\d+$/,
        decimal: /^(-?\d+)(\.\d+)?/,
        chinese: /^[\u4e00-\u9fa5],{0,}$/,
        letters: /(\w){5,6}/,
        rate: /^([6-9](\.[0-9])?)$|^([1][0-9](\.[0-9])?)$|^(20)$/
    };

    var state = true;

    var Plugin = function (element, options) {
        var global = this,
            elem = element,
            opts = $.extend({}, config, options);

        /**
         * 密码强度确认
         * @param string 密码
         * @returns {number} 1 强 -1 中 0 弱
         */
        var authPassWord = function(string){
            if(/[a-zA-Z]+/.test(string) && /[0-9]+/.test(string) && /\W+\D+/.test(string)) {
                return 1;
            }else if(/[a-zA-Z]+/.test(string) || /[0-9]+/.test(string) || /\W+\D+/.test(string)) {
                if(/[a-zA-Z]+/.test(string) && /[0-9]+/.test(string)) {
                    return -1;
                }else if(/\[a-zA-Z]+/.test(string) && /\W+\D+/.test(string)) {
                    return -1;
                }else if(/[0-9]+/.test(string) && /\W+\D+/.test(string)) {
                    return -1;
                }else{
                    return 0;
                }
            }
            return 0;
        };

        /**
         * 验证身份证
         * @param gets
         * @returns {*}
         */
        var idcard = function(gets){

            var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子;
            var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值，10代表X;

            if (gets.length == 18){
                var a_idCard = gets.split("");// 得到身份证数组
                if (isValidityBrithBy18IdCard(gets)&&isTrueValidateCodeBy18IdCard(a_idCard)) {
                    return true;
                }
                return false;
            }
            return false;

            function isTrueValidateCodeBy18IdCard(a_idCard) {
                var sum = 0; // 声明加权求和变量
                if (a_idCard[17].toLowerCase() == 'x') {
                    a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
                }
                for ( var i = 0; i < 17; i++) {
                    sum += Wi[i] * a_idCard[i];// 加权求和
                }
                valCodePosition = sum % 11;// 得到验证码所位置
                if (a_idCard[17] == ValideCode[valCodePosition]) {
                    return true;
                }
                return false;
            }

            function isValidityBrithBy18IdCard(idCard18){
                var year = idCard18.substring(6,10);
                var month = idCard18.substring(10,12);
                var day = idCard18.substring(12,14);
                var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                // 这里用getFullYear()获取年份，避免千年虫问题
                if(temp_date.getFullYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){
                    return false;
                }
                return true;
            }

            function isValidityBrithBy15IdCard(idCard15){
                var year =  idCard15.substring(6,8);
                var month = idCard15.substring(8,10);
                var day = idCard15.substring(10,12);
                var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
                if(temp_date.getYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){
                    return false;
                }
                return true;
            }

        }

        var examine = function (obj, params, elem) {
            var result = -1;
            var items = params.filtrate.split(' ');
            var val = obj.val();

            for (var i = 0; i < items.length; i++) {
                var key = items[i];

                if (key === 'checkbox' && !obj.attr("checked")) {
                    result = i;
                }else if(key === 'select'){
                    if(val==="" || val==="0"){
                        result=0;
                    }
                }else if(key === 'radio'){
                    if($("input[name='"+obj.attr("name")+"']:checked").length==0){
                        result=1;
                    }
                }else if(key === 'norequired'){
                    ;
                }else if(key === 'required' && val === ''){
                    result = i;
                } else if (val.length > 0 && /^min[0-9]+/.test(key) && val.length < key.match('[0-9]+')[0]) {
                    result = i;
                } else if (val.length > 0 && /^max[0-9]+/.test(key) && val.length > key.match('[0-9]+')[0]) {
                    result = i;
                } else if(key === 'nric' && !idcard(val)){
                    result = i;
                } else if(typeof(deg[key])!== 'undefined'){
                    if (!deg[key].test(val) && val!=="") {
                        result = i;
                    }
                }else if(key==="minval"){
                    if(!isNaN(obj.attr("min")) &&  parseInt(obj.attr("min")) > parseInt(val)){
                        result = i;
                    }
                }else if(key==="maxval"){
                    if(!isNaN(obj.attr("max")) &&  parseInt(obj.attr("max")) < parseInt(val)){
                        result = i;
                    }
                }

                if (result !== -1) {
                    state = false;
                    break;
                }
            }
            if (params.callback) {
                var other = null;
                if (params.degree && result === -1) {
                    other = authPassWord(val);
                }
                if(typeof(params.relevance) !== "undefined" && result == -1){
                    other = elem.find(params.relevance).val() === val;
                    if(!other){
                    //    result=9;
                    //    state=false;
                    }
                }
                params.callback.call(obj, result, other);
            }
        };

        //初始化
        var init = function () {
            var selectors = '';
            for (var selector in opts) {
                selectors += selector + ',';
                var params = opts[selector];
                if(elem.find(selector).data('params', params).is("select")){
                    elem.find(selector).data('params', params).on('blur', function () {
                        var self = $(this);
                        examine(self, self.data('params'), elem);
                    }).on('change', function () {
                        var self = $(this);
                        examine(self, self.data('params'), elem);
                    });
                }else{
                    elem.find(selector).data('params', params).on('blur', function () {
                        var self = $(this);
                        examine(self, self.data('params'), elem);
                    });
                }

            }

            selectors = selectors.substring(0, selectors.length - 1);

            var ajax_url = $(elem).attr('action');
            ajax_url = (ajax_url != '') ? ajax_url : window.location.href;

            var _obj = $(elem).find(':submit');
            var _tips;

            var options = {
                url:ajax_url,
                dataType:'json',
                type:'POST',
                beforeSubmit:function(){
                    state = true;
                    elem.find(selectors).trigger('blur');
                    if (typeof(elem.data('state')) === "undefined") {
                        elem.data('state', true);
                    }

                    var flag = elem.data('state') && state;

                    if(flag)
                    {
                        _tips = _obj.val();
                        _obj.val('正在提交请稍侯...').attr('disabled',true);
                    }

                    return flag;
                },
                success: function(result){
                    if(result.code == 0){
                        if(result.url != ''){
                            sys.alert(result.msg);
                            setTimeout(function() {window.location.href=result.url;},2000);
                        }else{
                           sys.alert(result.msg);
                           setTimeout(function() {window.location.href="https://www.juxueqiu.com/index.php/user";},2000);
                        }
                    } else {
                        sys.alert(result.msg, 'z-error');
                        if(result.code == 2){
                            $('#login_captcha').show();
                        }
                        _obj.val(_tips).attr('disabled',false);
                    }
                },
                error:function(){
                    sys.alert('服务器繁忙请稍后再试！', 'z-error');
                    _obj.val(_tips).attr('disabled',false);
                }
            };

            var regoptions = {
                dataType:'json',
                beforeSubmit:function(){
                   state = true;
                    elem.find(selectors).trigger('blur');
                    if (typeof(elem.data('state')) === "undefined") {
                        elem.data('state', true);
                    }
                    var f=elem.data('state') && state;

                    if($("#isexsmobile").val()=="1")
                    {
                        f=false;
                    }
                    //wsb-2015.5.13 是否有效推荐人
                    if($("#isvaliinviter").val()=="1")
                    {
                        f=false;
                    }

                    if(f)
                    {
                        sys.dialog.smscode(elem.find("input[name='mobile']").val(),elem.find("input[name='password']").val(),elem.find("input[name='referrer']").val(),elem.find("input[name='captcha']").val(),elem.find("input[name='invite_code']").val());
                    }

                    return  false;
                },
                success: function(result){
                    ;
                },
                error:function(){
                    ;
                }
            };

            
        }();

        return global;
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