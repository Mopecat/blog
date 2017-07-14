/**
 * Created by liuyang on 2017/6/22.
 */
$(document).ready(function() {
    var titlechange = document.title;
    if(document.addEventListener("visibilitychange", function () {
        var setout;//定时器
        document.hidden ? (document.title = "(●—●)喔哟，走了啊！", clearTimeout(setout)) : (document.title = "(/≧▽≦/)咦！又回来哩！" + titlechange , setout = setTimeout(function () {
            document.title = titlechange
        }, 2e3))
    }));
    function dateRecord() {
        window.setInterval(function () {dateRecord()}, 1e3);
        var n = new Date("2/4/2017 11:30:00"), a = new Date, r = a.getTime() - n.getTime(), o = 864e5, l = r / o,
            i = Math.floor(l), s = 24 * (l - i), d = Math.floor(s), p = 60 * (s - d), m = Math.floor(60 * (s - d)),
            c = Math.floor(60 * (p - m));
        $("#span_dt").html(i + "天" + d + "小时" + m + "分" + c + "秒");
    }
    dateRecord();

    var screen_width=$(window).width();
    if(screen_width<=768){
        $('.menu-item-has-children').mouseover(function(){
            $('.sub-menu').show();
            // $('.sub-menu li').css('background','#00ded4');
        });
        $('.menu-item-has-children').mouseout(function(){
            $('.sub-menu').hide();
        });
    }
});
