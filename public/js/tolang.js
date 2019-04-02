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

    function dialog (msg, offset,f, close ) {
        let jit = $(`<div style='position: absolute;height: 50px;width:150px;top:${offset.top};left:${offset.left};background-color: #FFF'>${msg}</div>`).show();
        $("body").append(jit);
        try {
            f(jit);
        } catch (e) {
        }
        if (close) {
            setTimeout(function () {
                jit.remove();
            }, close * 1000);
        }
    }
})
