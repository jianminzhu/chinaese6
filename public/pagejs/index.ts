

function search() {

    let $form = $('form[name="searchForm"]');
    let $bsearch = $form.find("[name=b_search]");
    $("body").delegate("a[data-page]", "click", function () {
        let clickPage = $(this).data("page");
        if (clickPage != $form.find("[name=pno]").val()) {
            $form.find("[name=pno]").val(clickPage);
            $bsearch.trigger("click");
        }
    })
    $("select").on("change", function () {
        $("input[name=pno]").val(1)
    })
    $bsearch.on("click", function () {
        $form.trigger("click");
    })
    $form.on("submit", function () {

        let $searchResults = $("[name=searchResults]");
        $searchResults.html("")
        $.ajax({url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html"}).then(function (html) {
            $searchResults.html(html)
        });
        return false;
    })
}

$(function () {
    search();
})