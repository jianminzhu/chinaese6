function search() {
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
