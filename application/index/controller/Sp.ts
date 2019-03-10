$(function () {
    // var addr=120100;
    var addr=420100;
    var a = [
    ];
    var url = `http://www.bytrip.com/i/status/2/area/${addr}/low_age/18/high_age/40/sex/2/lt/1/rt/0/hot/0`;
    var start = 1;
    var page = 60;
    for (var i = start; i <= page; i++) {
        a.push(url+`/p_${i}`)
    }
    $("textarea").val(a.join("\n"))
    $("form").trigger("submit")

})