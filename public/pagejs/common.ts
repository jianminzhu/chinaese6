function mhtml(url, data = {}) {
    return $.ajax({url: "/index.php" + url, type: 'get', data: data, dataType: "html"});
}

function mdata(url, data = {}) {
    return $.ajax({url: "/index.php" + url, dataType: "json", data: data}).then(function (res) {
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
            var ret = {},
                seg = a.search.replace(/^\?/, '').split('&'),
                len = seg.length, i = 0, s;
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
    if (r != null) return unescape(r[2]);
    return null;
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

function payDo(fun, type = "ajax") {
    mdata("/index/a/ajaxIsPay").then(function (res) {
        if (res.isSuccess) {
            fun();
        } else {
            let location = window.location;
            let srcUrl=location.protocol+"//"+location.host;
            let url = srcUrl+"/index.php/index/m/upgrade";
            window.location.href = url;
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
class Loding{
   static initLoding() {
        if ($("#css_loading").length==0) {
            $("body").append(`
<style>
    .loader-inner {
        width: 100px;
        height: 60px;
        position: relative;
    }

    .loader-line-wrap {
        -webkit-animation: spin 2000ms cubic-bezier(.175, .885, .32, 1.275) infinite;
        animation: spin 2000ms cubic-bezier(.175, .885, .32, 1.275) infinite;
        box-sizing: border-box;
        height: 50px;
        left: 0;
        overflow: hidden;
        position: absolute;
        top: 0;
        -webkit-transform-origin: 50% 100%;
        transform-origin: 50% 100%;
        width: 100px;
    }

    .loader-line {
        border: 4px solid transparent;
        border-radius: 100%;
        box-sizing: border-box;
        height: 100px;
        left: 0;
        margin: 0 auto;
        position: absolute;
        right: 0;
        top: 0;
        width: 100px;
    }

    .loader-line-wrap:nth-child(1) {
        -webkit-animation-delay: -50ms;
        animation-delay: -50ms;
    }

    .loader-line-wrap:nth-child(2) {
        -webkit-animation-delay: -100ms;
        animation-delay: -100ms;
    }

    .loader-line-wrap:nth-child(3) {
        -webkit-animation-delay: -150ms;
        animation-delay: -150ms;
    }

    .loader-line-wrap:nth-child(4) {
        -webkit-animation-delay: -200ms;
        animation-delay: -200ms;
    }

    .loader-line-wrap:nth-child(5) {
        -webkit-animation-delay: -250ms;
        animation-delay: -250ms;
    }

    .loader-line-wrap:nth-child(1) .loader-line {
        border-color: hsl(0, 80%, 60%);
        height: 90px;
        width: 90px;
        top: 7px;
    }

    .loader-line-wrap:nth-child(2) .loader-line {
        border-color: hsl(60, 80%, 60%);
        height: 76px;
        width: 76px;
        top: 14px;
    }

    .loader-line-wrap:nth-child(3) .loader-line {
        border-color: hsl(120, 80%, 60%);
        height: 62px;
        width: 62px;
        top: 21px;
    }

    .loader-line-wrap:nth-child(4) .loader-line {
        border-color: hsl(180, 80%, 60%);
        height: 48px;
        width: 48px;
        top: 28px;
    }

    .loader-line-wrap:nth-child(5) .loader-line {
        border-color: hsl(240, 80%, 60%);
        height: 34px;
        width: 34px;
        top: 35px;
    }

    @-webkit-keyframes spin {
        0%, 15% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0%, 15% {
            -webkit-transform: rotate(0);
            transform: rotate(0);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>
<div id="css_loading" style="position: fixed;top:45%;left:45%;z-index:10002;display: none">
    <div class="loader-inner">
        <div class="loader-line-wrap">
            <div class="loader-line"></div>
        </div>
        <div class="loader-line-wrap">
            <div class="loader-line"></div>
        </div>
        <div class="loader-line-wrap">
            <div class="loader-line"></div>
        </div>
        <div class="loader-line-wrap">
            <div class="loader-line"></div>
        </div>
        <div class="loader-line-wrap">
            <div class="loader-line"></div>
        </div>
    </div>
</div>
`)

        }

    }
    static showLoding() {
        $("#css_loading").show();
    }
    static hideLodin() {
        $("#css_loading").hide();
    }
}

/*
* 搜索相关事件*/
function search() {
    let $form = $('form[name="searchForm"]');
    let $bsearch = $form.find("[name=b_search]");
    Loding.initLoding();
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
        $searchResults.html("");
        Loding.showLoding();
        $.ajax({url: "/index.php/index/index/search?" + $form.serialize(), dataType: "html"}).then(function (html) {
            setTimeout(function () {
                $searchResults.html(html)
                Loding.hideLodin();
            },200)
        });
        return false;
    })
}

function action() {
    $("body").delegate("[name=dialogUpgradeForm]", "submit", function () {
        return false;
    });
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
    //表单发送消息
    $("body").delegate("[data-opt-dosendmsg]", "click", function () {
        var jit = $(this)
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-dosendmsg");
                let msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mdata("/index/mail/send", {to_m_id: $id, msg: msg, "type": 2}).then(function (res) {
                        showNotice(res.data);
                        window.location.reload();
                    });
                }
            },"form");
        })
    })

    //表单发送消息
    $("body").delegate("[data-opt-showconcat]", "click", function () {
        var jit = $(this)
        loginDo(function () {
            payDo(function () {
                var $id = jit.attr("data-opt-showconcat");
                let msg = jit.parent().find("textarea[name=message]").val();
                if (msg != "") {
                    mhtml("/index/m/concat", {mid: $id}).then(function (res) {
                        $("#dialogMsg").html(res).show()
                    });
                }
            });
        })
    })


    //对话框发送消息
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

    //显示发送消息窗口
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