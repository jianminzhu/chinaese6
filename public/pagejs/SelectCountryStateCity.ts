function json(url, data) {
    return $.ajax({url: url, data: data, dataType: "json"}).then(function (data) {
        return (data);
    });
}
function s(name) {
    return $(`select[name=${name}]`);
}
function SelectCountryStateCity(countryid, stateid, cityid) {
    var $countryLive = s("countryid")
    var $stateLive = s("stateid")
    var $cityLive = s("cityid")
    json("/index.php/index/addr/loadstates", {countryid: countryid}).then(function (data) {
        data.splice(0, 0, {n: "Any", v: "-1", cn: "任意"})
        GenSelectOption($stateLive.get(0), data, "v", "n",stateid);
        json("/index.php/index/addr/loadstates", {stateid: stateid}).then(function (data) {
            data.splice(0, 0, {n: "Any", v: "-1", cn: "任意"})
            GenSelectOption($cityLive.get(0), data, "v", "n",cityid);
        })
    })
}
$(function () {
    var $countryLive = s("countryid")
    var $stateLive = s("stateid")
    var $cityLive = s("cityid")
    $countryLive.on("change", function () {
        var countryid = $(this).val()
        $stateLive.html("<option value=\"-1\">Any</option>");
        $cityLive.html("<option value=\"-1\">Any</option>");
        json("/index.php/index/addr/loadstates", {countryid: countryid}).then(function (data) {
            data.splice(0, 0, {n: "Any", v: "-1", cn: "任意"})
            GenSelectOption($stateLive.get(0), data, "v", "n");
        })
    })
    $stateLive.on("change", function () {
        var stateid = $(this).val()
        $cityLive.html("<option value=\"-1\">Any</option>");
        json("/index.php/index/addr/loadstates", {stateid: stateid}).then(function (data) {
            data.splice(0, 0, {n: "Any", v: "-1", cn: "任意"})
            GenSelectOption($cityLive.get(0), data, "v", "n");
        })
    })
})