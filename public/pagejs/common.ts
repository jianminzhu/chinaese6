function mhtml(url, data = {}) {
    return $.ajax({url: "/index.php" + url, data: data});
}

function mdata(url, data = {}) {
    return $.ajax({url: "/index.php" + url, dataType: "json", data: data}).then(function (res) {
        return eval(res);
    });
}

function showLoginDialog(data = {}) {
    var $loginDialog = $("body").find("#loginDialog")
    if ($loginDialog.length == 0) {
        mhtml("/index/a/loginDialog").then(function (html) {
            $loginDialog = html;
            $("body").append($loginDialog)
        })
    }
    ;
    $loginDialog.show();
}

function showNotice(msg) {
    var $b = $("body");
    var $notice = $b.find("#notices")
    if ($notice.length == 0) {
        $notice = $(`<div id="notices" role="dialog"><p class="m0 p1 bg-green white"></p></div>`);
        $b.prepend($notice);
    }
    $notice.find("p").html(msg);
    $b.addClass("notices-open");
    setTimeout(function () {
        $b.removeClass("notices-open");
    }, 2000);
}


function loginDo(fun) {
    mdata("/index/a/ajaxIsLogin").then(function (res) {
        if (res.isSuccess) {
            fun();
        } else {
            window.location.href = "/index.php/index/a/login"
        }
    });
}

function showDialogSentMsg(toMid) {
    mhtml("/index/dialog/sentMsgDialog", {otherId: toMid, name: "sentMsg"}).then(function (html) {
        let $sentMsgDialog = $("body").find("#sentMsgDialog");
        if ($sentMsgDialog.length == 0) {
            $sentMsgDialog = $("<div></div>").attr("id", "sentMsgDialog");
            $("body").prepend($sentMsgDialog)
        }
        $sentMsgDialog.html(html).show();
    });

}

function action() {
    $("body").delegate("[data-opt-closeDialog]", "click", function () {
        var $id = $(this).attr("data-opt-closeDialog");
        $(this).closest(`div#${$id}`).hide();
    });




    //发送消息相关
    $("body").delegate("[data-opt-doSendMsg]", "click", function () {
        var jit=$(this)
        loginDo(function () {
            mdata("/index/m/isPay").then(function (res) {
                var $id = jit.attr("data-opt-doSendMsg");
                if (res.isSuccess) {
                  mdata("/index/mail/send",{toId:$id,"msg": jit.parent("textarea[name=message]").val()}).then(function (res) {
                      showNotice(res.msg);
                  })
                }
            })
        });
    })

    $("body").delegate("[data-opt-sendmsg]", "click", function () {
        var mid = $(this).data("dMid");
        loginDo(function () {
            showDialogSentMsg(mid);
        });
    })

    $("body").delegate("[data-opt-sendInterest]", "click", function () {

    })

    $("body").delegate("[data-opt-addToFavorite]", "click", function () {
        let mid = $(this).data("dMid");
        $.ajax({
            url: "/index.php/index/m/addToFavorite",
            dataType: "json",
            data: {favorite_mid: mid}
        }).then(function (res) {
            showNotice(res.msg);
        });
    })
}

$(function () {
    action();
})