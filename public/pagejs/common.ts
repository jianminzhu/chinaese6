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

function payDo(fun) {
    mdata("/index/a/ajaxIsPay").then(function (res) {
        if (res.isSuccess) {
            fun();
        } else {
            window.location.href = "/index.php/index/m/upgrade"
        }
    });
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

/*
* 搜索相关事件*/
function search() {
    let $form = $('form[name="searchForm"]');
    let $bsearch = $form.find("[name=b_search]");
    $("body").delegate("[data-page]", "click", function () {
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
        $.ajax({url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html"}).then(function (html) {
            $searchResults.html(html)
        });
        return false;
    })
}

function action() {
    $("body").delegate("[data-opt-closeDialog]", "click", function () {
        var $id = $(this).attr("data-opt-closeDialog");
        $(this).closest(`div#${$id}`).hide();
    });
    $("body").delegate("[data-opt-tabs]", "click", function () {
        $(this).parent().find(".border-bottom").removeClass("border-bottom");
        $(this).addClass("border-bottom");
        var jroot = $("#" + $(this).attr("data-opt-tabs"));
        var index = $(this).index()
        jroot.find(">div").hide();
        var jit = jroot.find(`>div:eq(${index})`);
        jit.show();
    })
    $("body").delegate("[data-opt-interest]", "click", function () {
        let jit = $(this);
        var mid = jit.data("dMid");
        loginDo(function () {
            mdata("/index/m/interest", {to_mid: mid}).then(function (res) {
                jit.removeClass("fill-action-unhighlight").addClass("fill-action-highlight")
                showNotice(res.data);
            });
        });
    })
    //发送消息相关
    $("body").delegate("[data-opt-dosendmsg]", "click", function () {
        var jit = $(this)
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-dosendmsg");
                let msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mdata("/index/mail/send", {to_m_id: $id, msg: msg, "type": 2}).then(function (res) {
                        showNotice(res.data);
                        showDialogSentMsg($id)
                        window.location.reload();
                    });
                }
            });
        })
    })

    $("body").delegate("[data-opt-ajaxsendmsg]", "click", function () {
        var jit = $(this)
        loginDo(function () {
            payDo(function () {
                var $mid = jit.attr("data-opt-ajaxsendmsg");
                let msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mdata("/index/mail/send", {to_m_id: $mid, msg: msg, "type": 2}).then(function (res) {
                        showNotice(res.data);
                        showDialogSentMsg($mid)
                    });
                }
            });
        })
    })

    $("body").delegate("[data-opt-sendmsg]", "click", function () {
        var mid = $(this).data("dMid");
        loginDo(function () {
            payDo(function () {
                showDialogSentMsg(mid);
            });
        })
    })


    $("body").delegate("[data-opt-addfavorite]", "click", function () {
        var jit = $(this);
        let mid = jit.data("dMid");
        loginDo(function () {
            $.ajax({
                url: "/index.php/index/m/favorite",
                dataType: "json",
                data: {to_mid: mid}
            }).then(function (res) {
                let data = res.data;
                showNotice(data.emsg);
                jit.addClass(data.addClass);
                jit.removeClass(data.removeClass);
            });
        })
    })

}

$(function () {
    action();

    search();
})