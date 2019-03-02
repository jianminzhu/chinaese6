$(function () {
    $("body").delegate("[data-lang]", "click", function () {
        $.ajax({
            url: '/index.php/index/a/tolang',
            data: {"lang": $(this).data("lang")}
            , success: function () {
                location.reload();
            }
        })
    });
})