// JavaScript Document
function huanxingJDT(){
    var big_huan=document.getElementById("big_huan");
    if(!big_huan){ return null;}
    var big_huan_jd = document.getElementById("big_huan_jd").innerHTML;
    var kzz = /\s/ig;
    big_huan_jd = Math.floor(parseFloat(big_huan_jd.replace(kzz,"")));

    big_huan_jd = big_huan_jd-big_huan_jd%10+ Math.floor(big_huan_jd%10/5)*5;
    //得到5的倍数分
    big_huan.className = "big_huan huan_jd_"+big_huan_jd;
}

//计算器
function JS_calc(a,b,c){
    var bg_a = document.getElementById("bg_a");
    if(!bg_a){return null;}
    var v_a = document.getElementById("v_a");
    var bg_b = document.getElementById("bg_b");
    var v_b = document.getElementById("v_b");
    var bg_c = document.getElementById("bg_c");
    var v_c = document.getElementById("v_c");
    var jine =document.getElementById("jine").value;
    var s_zz = /\s/ig;
    var num_zz = /^\d{1,}\.{0,1}\d{0,}$/ig;
    jine = jine.replace(s_zz,"");
    if(!num_zz.test(jine)){ //||parseFloat(jine)<100
        document.getElementById("jine").value="";
        return null;
    }
    jine = parseFloat(jine);
    //var x = Math.floor(jine*a*100)/100;
    //var y = Math.floor(jine*b*100)/100;
    //var z = Math.floor(jine*c*100)/100;

    var x = a;
    var y = b;
    var z = c;
    v_a.innerHTML = x+"元";
    v_b.innerHTML = y+"元";
    v_c.innerHTML = z+"元";
    if(x>=y && x>=z){
        bg_a.style.width="100%";
        bg_b.style.width = y/x*100+"%";
        bg_c.style.width = z/x*100+"%";
    }else if(y>=x && y>=z){
        bg_b.style.width="100%";
        bg_a.style.width = x/y*100+"%";
        bg_c.style.width = z/y*100+"%";
    }else{
        bg_c.style.width="100%";
        bg_a.style.width = a/z*100+"%";
        bg_b.style.width = b/z*100+"%";
    }
}