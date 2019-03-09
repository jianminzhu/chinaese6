function json(url, data) {
    return $.ajax({ url: url, data: data, dataType: "json" }).then(function (data) {
        return eval(data);
    });
}
function search() {
    function s(name) {
        return $("select[name=" + name + "]");
    }
    var $countryLive = s("countryLive");
    var $stateLive = s("stateLive");
    var $cityLive = s("cityLive");
    $countryLive.on("change", function () {
        var countryid = $(this).val();
        $stateLive.html("");
        $cityLive.html("");
        json("/index.php/index/addr/loadstates", { countryid: countryid }).then(function (data) {
            data.splice(0, 0, { n: "Any", v: "-1", cn: "任意" });
            GenSelectOption($stateLive.get(0), data, "v", LANG == "en-us" ? "n" : "cn");
        });
    });
    $stateLive.on("change", function () {
        var stateid = $(this).val();
        $cityLive.html("");
        json("/index.php/index/addr/loadstates", { stateid: stateid }).then(function (data) {
            data.splice(0, 0, { n: "Any", v: "-1", cn: "任意" });
            GenSelectOption($cityLive.get(0), data, "v", LANG == "en-us" ? "n" : "cn");
        });
    });
    var $form = $('form[name="searchForm"]');
    var $bsearch = $form.find("[name=b_search]");
    $("body").delegate("a[data-page]", "click", function () {
        var clickPage = $(this).data("page");
        if (clickPage != $form.find("[name=pno]").val()) {
            $form.find("[name=pno]").val(clickPage);
            $bsearch.trigger("click");
        }
    });
    $("select").on("change", function () {
        $("input[name=pno]").val(1);
    });
    $bsearch.on("click", function () {
        $form.trigger("click");
    });
    $form.on("submit", function () {
        var $searchResults = $("[name=searchResults]");
        $searchResults.html("");
        $.ajax({ url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html" }).then(function (html) {
            $searchResults.html(html);
        });
        return false;
    });
}
$(function () {
    search();
});
