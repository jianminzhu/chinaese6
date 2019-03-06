

$(function () {
    http://localhost/index.php/index/spAddress/loadcities?countryid=42
    function s(name){
        return $(`select[name=${name}]`);
    }


    $countryLive=s("countryLive")
    $stateLive=s("stateLive")
    $cityLive=s("cityLive")


})