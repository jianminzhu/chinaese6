<?php
require_once 'fileUtil.php';


$url= ["https://fonts.googleapis.com/css?family=Roboto:300,400,700"
    , "https://www.chinalovecupid.com/assets/css/basscss/basscss.min.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/site/modal/modal.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/global/global.css?v=165"
    , "https://www.chinalovecupid.com/assets/desktop/css/chinalovecupid-hybrid.css?v=24"
    , "https://www.chinalovecupid.com/assets/css/site/generic/membercolor.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/site/generic/membercolor_chinalovecupid.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/site/generic/memberheader.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/site/generic/memberlayout.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.all.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.dialog.css?v=165"
    , "https://www.chinalovecupid.com/assets/css/site/payment/upgradeMembership.css?v=165"
    , "https://fonts.googleapis.com/earlyaccess/notosanssc.css"
    , "https://fonts.googleapis.com/earlyaccess/notosanstc.css"
    , "https://fonts.googleapis.com/css?family=Roboto:400,700"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.base.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.theme.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.core.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.resizable.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.accordion.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.autocomplete.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.button.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.dialog.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.slider.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.tabs.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.datepicker.css"
    , "https://www.chinalovecupid.com/assets/css/jquery/ui-themes/ui-smoothness/jquery.ui.progressbar.css"
    , "https://www.chinalovecupid.com/assets/images/upgradeMembership/rightarrow.png"
    , "https://www.chinalovecupid.com/assets/images/upgradeMembership/icon-clock.png"
    , "https://www.chinalovecupid.com/assets/images/upgrademembership/bg-timer.gif"
    , "https://fonts.gstatic.com/s/roboto/v18/KFOlCnqEu92Fr1MmWUlfBBc4.woff2"
    , "https://fonts.gstatic.com/s/roboto/v18/KFOmCnqEu92Fr1Mu4mxK.woff2"];


$urls = [
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/1.png",
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/2.png",
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/3.png",
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/132.png",
    "https://live.adyen.com/hpp/img/pm/wechatpay.png",
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/861.png",
    "https://www.chinalovecupid.com//assets/images/upgradeMembership/430.png",
    "https://live.adyen.com/hpp/img/pm/moneybookers.png",
];


foreach ($urls as $u) {
    if (trim($u)!="") {
        ExtDownloadPic( $u, "./css");
    }
}