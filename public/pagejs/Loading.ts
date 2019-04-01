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
