function mhtml(url, data) {
    if (data === void 0) { data = {}; }
    return $.ajax({ url: "/index.php" + url, data: data });
}
function mdata(url, data) {
    if (data === void 0) { data = {}; }
    return $.ajax({ url: "/index.php" + url, dataType: "json", data: data }).then(function (res) {
        return eval(res);
    });
}
function showLoginDialog(data) {
    if (data === void 0) { data = {}; }
    var $loginDialog = $("body").find("#loginDialog");
    if ($loginDialog.length == 0) {
        mhtml("/index/a/loginDialog").then(function (html) {
            $loginDialog = html;
            $("body").append($loginDialog);
        });
    }
    ;
    $loginDialog.show();
}
function showNotice(msg) {
    var $b = $("body");
    var $notice = $b.find("#notices");
    if ($notice.length == 0) {
        $notice = $("<div id=\"notices\" role=\"dialog\"><p class=\"m0 p1 bg-green white\"></p></div>");
        $b.prepend($notice);
    }
    $notice.find("p").html(msg);
    $b.addClass("notices-open");
    setTimeout(function () {
        $b.removeClass("notices-open");
    }, 2000);
}
function payDo(fun) {
    mdata("/index/a/ajaxIsPay").then(function (res) {
        if (res.isSuccess) {
            fun();
        }
        else {
            window.location.href = "/index.php/index/m/upgrade";
        }
    });
}
function loginDo(fun) {
    mdata("/index/a/ajaxIsLogin").then(function (res) {
        if (res.isSuccess) {
            fun();
        }
        else {
            window.location.href = "/index.php/index/a/login";
        }
    });
}
function showDialogSentMsg(toMid) {
    mhtml("/index/dialog/sentMsgDialog", { otherId: toMid, name: "sentMsg" }).then(function (html) {
        var $sentMsgDialog = $("body").find("#sentMsgDialog");
        if ($sentMsgDialog.length == 0) {
            $sentMsgDialog = $("<div></div>").attr("id", "sentMsgDialog");
            $("body").prepend($sentMsgDialog);
        }
        $sentMsgDialog.html(html).show();
    });
}
function action() {
    $("body").delegate("[data-opt-closeDialog]", "click", function () {
        var $id = $(this).attr("data-opt-closeDialog");
        $(this).closest("div#" + $id).hide();
    });
    $("body").delegate("[data-opt-tabs]", "click", function () {
        $(this).parent().find(".border-bottom").removeClass("border-bottom");
        $(this).addClass("border-bottom");
        var jroot = $("#" + $(this).attr("data-opt-tabs"));
        var index = $(this).index();
        jroot.find(">div").hide();
        var jit = jroot.find(">div:eq(" + index + ")");
        jit.show();
    });
    $("body").delegate("[data-opt-interest]", "click", function () {
        var jit = $(this);
        var mid = jit.data("dMid");
        loginDo(function () {
            mdata("/index/m/interest", { to_mid: mid }).then(function (res) {
                jit.removeClass("fill-action-unhighlight").addClass("fill-action-highlight");
                showNotice(res.data);
            });
        });
    });
    //发送消息相关
    $("body").delegate("[data-opt-dosendmsg]", "click", function () {
        var jit = $(this);
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-dosendmsg");
                var msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mdata("/index/mail/send", { to_m_id: $id, msg: msg, "type": 2 }).then(function (res) {
                        showNotice(res.data);
                        showDialogSentMsg($id);
                        window.location.reload();
                    });
                }
            });
            // })
        });
        $("body").delegate("[data-opt-ajaxSendMsg]", "click", function () {
            var jit = $(this);
            loginDo(function () {
                payDo(function () {
                    var $mid = jit.attr("data-opt-ajaxSendMsg");
                    var msg = jit.parent().find("textarea[name=message]").val();
                    if (msg != "") {
                        mdata("/index/mail/send", { to_m_id: $mid, msg: msg, "type": 2 }).then(function (res) {
                            showNotice(res.data);
                            showDialogSentMsg($mid);
                        });
                    }
                });
            });
        });
        $("body").delegate("[data-opt-sendmsg]", "click", function () {
            var mid = $(this).data("dMid");
            loginDo(function () {
                payDo(function () {
                    showDialogSentMsg(mid);
                });
            });
        });
        $("body").delegate("[data-opt-addfavorite]", "click", function () {
            var jit = $(this);
            var mid = jit.data("dMid");
            $.ajax({
                url: "/index.php/index/m/favorite",
                dataType: "json",
                data: { to_mid: mid }
            }).then(function (res) {
                var data = res.data;
                showNotice(data.emsg);
                jit.addClass(data.addClass);
                jit.removeClass(data.removeClass);
            });
        });
    }, $(function () {
        action();
    }));
}
