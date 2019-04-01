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
            var location_1 = window.location;
            var srcUrl = location_1.protocol + "//" + location_1.host;
            var url = srcUrl + "/index.php/index/m/upgrade";
            window.location.href = url;
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
var Loding = /** @class */ (function () {
    function Loding() {
    }
    Loding.initLoding = function () {
        if ($("#css_loading").length == 0) {
            $("body").append("\n<style>\n    .loader-inner {\n        width: 100px;\n        height: 60px;\n        position: relative;\n    }\n\n    .loader-line-wrap {\n        -webkit-animation: spin 2000ms cubic-bezier(.175, .885, .32, 1.275) infinite;\n        animation: spin 2000ms cubic-bezier(.175, .885, .32, 1.275) infinite;\n        box-sizing: border-box;\n        height: 50px;\n        left: 0;\n        overflow: hidden;\n        position: absolute;\n        top: 0;\n        -webkit-transform-origin: 50% 100%;\n        transform-origin: 50% 100%;\n        width: 100px;\n    }\n\n    .loader-line {\n        border: 4px solid transparent;\n        border-radius: 100%;\n        box-sizing: border-box;\n        height: 100px;\n        left: 0;\n        margin: 0 auto;\n        position: absolute;\n        right: 0;\n        top: 0;\n        width: 100px;\n    }\n\n    .loader-line-wrap:nth-child(1) {\n        -webkit-animation-delay: -50ms;\n        animation-delay: -50ms;\n    }\n\n    .loader-line-wrap:nth-child(2) {\n        -webkit-animation-delay: -100ms;\n        animation-delay: -100ms;\n    }\n\n    .loader-line-wrap:nth-child(3) {\n        -webkit-animation-delay: -150ms;\n        animation-delay: -150ms;\n    }\n\n    .loader-line-wrap:nth-child(4) {\n        -webkit-animation-delay: -200ms;\n        animation-delay: -200ms;\n    }\n\n    .loader-line-wrap:nth-child(5) {\n        -webkit-animation-delay: -250ms;\n        animation-delay: -250ms;\n    }\n\n    .loader-line-wrap:nth-child(1) .loader-line {\n        border-color: hsl(0, 80%, 60%);\n        height: 90px;\n        width: 90px;\n        top: 7px;\n    }\n\n    .loader-line-wrap:nth-child(2) .loader-line {\n        border-color: hsl(60, 80%, 60%);\n        height: 76px;\n        width: 76px;\n        top: 14px;\n    }\n\n    .loader-line-wrap:nth-child(3) .loader-line {\n        border-color: hsl(120, 80%, 60%);\n        height: 62px;\n        width: 62px;\n        top: 21px;\n    }\n\n    .loader-line-wrap:nth-child(4) .loader-line {\n        border-color: hsl(180, 80%, 60%);\n        height: 48px;\n        width: 48px;\n        top: 28px;\n    }\n\n    .loader-line-wrap:nth-child(5) .loader-line {\n        border-color: hsl(240, 80%, 60%);\n        height: 34px;\n        width: 34px;\n        top: 35px;\n    }\n\n    @-webkit-keyframes spin {\n        0%, 15% {\n            -webkit-transform: rotate(0);\n            transform: rotate(0);\n        }\n        100% {\n            -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n        }\n    }\n\n    @keyframes spin {\n        0%, 15% {\n            -webkit-transform: rotate(0);\n            transform: rotate(0);\n        }\n        100% {\n            -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n        }\n    }\n</style>\n<div id=\"css_loading\" style=\"position: fixed;top:45%;left:45%;z-index:10002;display: none\">\n    <div class=\"loader-inner\">\n        <div class=\"loader-line-wrap\">\n            <div class=\"loader-line\"></div>\n        </div>\n        <div class=\"loader-line-wrap\">\n            <div class=\"loader-line\"></div>\n        </div>\n        <div class=\"loader-line-wrap\">\n            <div class=\"loader-line\"></div>\n        </div>\n        <div class=\"loader-line-wrap\">\n            <div class=\"loader-line\"></div>\n        </div>\n        <div class=\"loader-line-wrap\">\n            <div class=\"loader-line\"></div>\n        </div>\n    </div>\n</div>\n");
        }
    };
    Loding.showLoding = function () {
        $("#css_loading").show();
    };
    Loding.hideLodin = function () {
        $("#css_loading").hide();
    };
    return Loding;
}());
/*
* 搜索相关事件*/
function search() {
    var $form = $('form[name="searchForm"]');
    var $bsearch = $form.find("[name=b_search]");
    Loding.initLoding();
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
        $searchResults.html("");
        Loding.showLoding();
        $.ajax({ url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html" }).then(function (html) {
            setTimeout(function () {
                $searchResults.html(html);
                Loding.hideLodin();
            }, 200);
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
