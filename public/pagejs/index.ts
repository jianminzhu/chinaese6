function json(url,data){
    return $.ajax({url:url, data:data, dataType: "json"}).then(function (data) {
        return eval(data);
    });
}
$(function () {
    function s(name) {
        return $(`select[name=${name}]`);
    }
    var $countryLive = s("countryLive")
    var $stateLive = s("stateLive")
    var $cityLive = s("cityLive")
    $countryLive.on("change",function () {
        var countryid = $(this).val()
        $stateLive.html("");
        $cityLive.html("");
        json("/index.php/index/addr/loadstates",{countryid: countryid}).then(function (data) {
            data.splice(0,0,{n:"", v: "-1"})
            GenSelectOption($stateLive.get(0), data, "v", "n");
        })
    })
    $stateLive.on("change",function () {
        var stateid = $(this).val()
        $cityLive.html("");
        json("/index.php/index/addr/loadstates",{stateid: stateid}).then(function (data) {
            data.splice(0,0,{n:"", v: "-1"})
            GenSelectOption($cityLive.get(0), data, "v", "n");
        })
    })

})