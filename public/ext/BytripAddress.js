var getCityes = function (stateId) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            "url": "http://www.bytrip.com/By/Program/ajaxGetCity.html?id=" + stateId,
            dataType: "json"
        }).then(function (data) {
            for (var _i = 0, data_1 = data; _i < data_1.length; _i++) {
                var d = data_1[_i];
                d.pid = stateId;
            }
            resolve(data);
        }).catch(function (e) {
            reject(e);
        });
    });
};
var saveAddress = function (data) {
    return $.ajax({
        method: "post",
        "url": "/index.php/index/addr/saveAddrs",
        dataType: "html",
        data: { addressJsonStr: JSON.stringify(data) }
    });
};
$(function () {
    var data = ["110000", "120000", "130000", "140000", "150000", "210000", "220000", "230000", "310000", "320000", "330000", "340000", "350000", "360000", "370000", "410000", "420000", "430000", "440000", "450000", "460000", "500000", "510000", "520000", "530000", "540000", "610000", "620000", "630000", "640000", "650000", "710000", "810000", "820000", "990000"];
    for (var _i = 0, data_2 = data; _i < data_2.length; _i++) {
        var stateId = data_2[_i];
        getCityes(stateId).then(function (data) {
            saveAddress(data).then(function (data) {
                console.log(JSON.stringify(data));
            });
        });
    }
});
