$(function () {
    $("body").delegate("[data-lang]", "click", function () {
        let lang = $(this).data("lang");
        $.ajax({
            url: '/index.php/index/a/tolang',
            data: {"lang": lang},
            success: function (html) {
                location.reload();
            }, error: function (e) {
            }
        })
        return false;
    });
})