function mhtml(url, data) {
    if (data === void 0) { data = {}; }
    return $.ajax({ url: "/index.php" + url, type: 'get', data: data, dataType: "html" });
}
function mdata(url, data) {
    if (data === void 0) { data = {}; }
    return $.ajax({ url: "/index.php" + url, dataType: "json", data: data }).then(function (res) {
        return eval(res);
    });
}
function parseURL(url) {
    var a = document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':', ''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function () {
            var ret = {}, seg = a.search.replace(/^\?/, '').split('&'), len = seg.length, i = 0, s;
            for (; i < len; i++) {
                if (!seg[i]) {
                    continue;
                }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
        hash: a.hash.replace('#', ''),
        path: a.pathname.replace(/^([^\/])/, '/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
        segments: a.pathname.replace(/^\//, '').split('/')
    };
}
function getQueryString(name, url) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = url.substr(1).match(reg);
    if (r != null)
        return unescape(r[2]);
    return null;
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
function payDo(fun, type) {
    if (type === void 0) { type = "ajax"; }
    mdata("/index/a/ajaxIsPay").then(function (res) {
        if (res.isSuccess) {
            fun();
        }
        else {
            // if (type == "ajax") {
            //
            //     var jpayDialog = $("#dialogUpgrade");
            //     if (jpayDialog.length == 0) {
            //         mhtml("/index/m/upgradeDialog").then(function (res) {
            //             jpayDialog = $(res);
            //             $("body").prepend(jpayDialog);
            //         })
            //     } else {
            //         jpayDialog.show();
            //     }
            // } else {
            //     window.location.href = "/index/m/upgrade";
            //     window.location.reload();
            // }
            window.location.href = "/index.php/index/m/upgrade";
            window.location.reload();
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
/*
* 搜索相关事件*/
function search() {
    var $form = $('form[name="searchForm"]');
    var $bsearch = $form.find("[name=b_search]");
    $("body").delegate("[data-page]", "click", function () {
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
        $.ajax({ url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html" }).then(function (html) {
            $searchResults.html(html);
        });
        return false;
    });
}
function action() {
    $("body").delegate("[name=dialogUpgradeForm]", "submit", function () {
        return false;
    });
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
    //表单发送消息
    $("body").delegate("[data-opt-dosendmsg]", "click", function () {
        var jit = $(this);
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-dosendmsg");
                var msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mdata("/index/mail/send", { to_m_id: $id, msg: msg, "type": 2 }).then(function (res) {
                        showNotice(res.data);
                        window.location.reload();
                    });
                }
            }, "form");
        });
    });
    //表单发送消息
    $("body").delegate("[data-opt-showconcat]", "click", function () {
        var jit = $(this);
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-showconcat");
                var msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mhtml("/index/m/concat", { mid: $id }).then(function (res) {
                        $("#dialogMsg").html(res).show();
                    });
                }
            });
        });
    });
    //对话框发送消息
    $("body").delegate("[data-opt-ajaxsendmsg]", "click", function () {
        var jit = $(this);
        loginDo(function () {
            payDo(function () {
                var $mid = jit.attr("data-opt-ajaxsendmsg");
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
    //显示发送消息窗口
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
        loginDo(function () {
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
    });
}
$(function () {
    action();
    search();
});
