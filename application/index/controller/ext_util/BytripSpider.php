<?php
include_once "fileUtil.php";
require('phpQuery.php');

function spider($startUrl, $parseDataAndPageFunc, $makeNextUrlFunc, $dealDataFunc, $startPno = 1, $limit = 0)
{
    $perDeal = function ($datas, $dealDataFunc) {
        if (is_array($datas)) {
            foreach ($datas as $d) {
                try {
                    $dealDataFunc($d);
                } catch (Exception $e) {
                    echo "error to deal row data " . json_encode($d);
                }
            }
        } else {
            try {
                $dealDataFunc($datas);
            } catch (Exception $e) {
                echo "error to deal row data " . json_encode($datas);
            }
        }
    };

    $html = ExtGetHtml($startUrl);
    list($data, $totalPage) = $parseDataAndPageFunc($html);
    $perDeal($data, $dealDataFunc);

    $endPage = $totalPage;
    if ($limit > 0) {
        $endPage = $startPno + $limit;
        echo "limit";
    }
    if ($endPage > $totalPage) {
        $endPage = $totalPage;
    }
    for ($x = $startPno + 1; $x <= $endPage; $x++) {
        $nextUrl = $makeNextUrlFunc($startUrl, $x);
        echo "nexturl: $nextUrl";
        $nextPageHtml = ExtGetHtml($nextUrl);
        list($nextPageData) = $parseDataAndPageFunc($nextPageHtml);
        $perDeal($nextPageData, $dealDataFunc);
    }
}

function BytripSearchMembers($html)

{
    pq(phpQuery::newDocumentHTML($html));
    $members = pq("div.travel-bd > ul > li");
    $page = pq("a.end")->html();
    $toDbMembers = [];
    foreach ($members as $m) {
        $jit = pq($m);
        list($address, $sex, $age) = explode(" ", $jit->find("div.information>ul>li:eq(0)")->html());
        $profile = str_replace("伴游心情：", "", $jit->find("div.information>ul>p")->html());
        $toDbMembers[] = [
            "main_pic" => $jit->find("a.face img")->attr("src"),
            "nickname" => $jit->find("a.name")->html(),
            "id" => $jit->find("div.information>div>a.but-letter")->attr("uid"),
            "profile" => $profile,
            "address" => $address,
            "age" => $age,
            "sex" => $sex == "男" ? 1 : 0
        ];
    }
    return [$toDbMembers, $page];
}

function BytripMember($id)
{
    $url = "http://www.bytrip.com/i/u_$id.html";
    $detail = phpQuery::newDocumentFile($url);

    pq($detail);
    $height = null;
    try {
        $height = get_numerics(pq("p.des span:eq(0)")->html())[0];
    } catch (Exception $e) {
    }
    $weight = null;
    try {
        $weight = get_numerics(pq("p.des span:eq(1)")->html())[0];
    } catch (Exception $e) {
    }
    $pics = [];
    foreach (pq("div.m-ablum div.bd li img") as $imgs) {
        try {
            $filePath = parse_url(pq($imgs)->attr("src"))["path"];
            $pics[] = ["m_id" => $id, "file_path" => $filePath];
        } catch (Exception $e) {
        }
    }
    $member = ["id" => $id, "height" => $height, "weight" => $weight];
    $main_pic = pq(" div.m-user > div > div.img > img")->attr("src");
    $nickname = pq("div.m-user p.information a.name")->html();
    $member["nickname"] = $nickname;
    $member["main_pic"] = $main_pic;
    try {
        $sex = pq("body > div:nth-child(4) > div > div.view > div.view-left > section.m-peo > table > tbody > tr:nth-child(2) > td:nth-child(2) > b")->html();
        $member["sex"] = ($sex == "男" ? 1 : 0);
    } catch (Exception $e) {
    }
    return ["pics" => $pics, "member" => $member];
}

function spicerAll($limit=0)
{
    $allCityids = [110100, 120100, 130100, 130200, 130300, 130400, 130500, 130600, 130700, 130800, 130900, 131000, 131100, 140100, 140200, 140300, 140400, 140500, 140600, 140700, 140800, 140900, 141000, 141100, 150100, 150200, 150300, 150400, 150500, 150600, 150700, 150800, 150900, 152200, 152500, 152900, 210100, 210200, 210300, 210400, 210500, 210600, 210700, 210800, 210900, 211000, 211100, 211200, 211300, 211400, 220100, 220200, 220300, 220400, 220500, 220600, 220700, 220800, 222400, 230100, 230200, 230300, 230400, 230500, 230600, 230700, 230800, 230900, 231000, 231100, 231200, 232700, 310100, 320100, 320200, 320300, 320400, 320500, 320600, 320700, 320800, 320900, 321000, 321100, 321200, 321300, 330100, 330200, 330300, 330400, 330500, 330600, 330700, 330800, 330900, 331000, 331100, 340100, 340200, 340300, 340400, 340500, 340600, 340700, 340800, 341000, 341100, 341200, 341300, 341500, 341600, 341700, 341800, 350100, 350200, 350300, 350400, 350500, 350600, 350700, 350800, 350900, 360100, 360200, 360300, 360400, 360500, 360600, 360700, 360800, 360900, 361000, 361100, 370100, 370200, 370300, 370400, 370500, 370600, 370700, 370800, 370900, 371000, 371100, 371200, 371300, 371400, 371500, 371600, 371700, 410100, 410200, 410300, 410400, 410500, 410600, 410700, 410800, 410881, 410900, 411000, 411100, 411200, 411300, 411400, 411500, 411600, 411700, 420100, 420200, 420300, 420500, 420600, 420700, 420800, 420900, 421000, 421100, 421200, 421300, 422800, 429004, 429005, 429006, 429021, 430100, 430200, 430300, 430400, 430500, 430600, 430700, 430800, 430900, 431000, 431100, 431200, 431300, 433100, 440100, 440200, 440300, 440400, 440500, 440600, 440700, 440800, 440900, 441200, 441300, 441400, 441500, 441600, 441700, 441800, 441900, 442000, 445100, 445200, 445300, 450100, 450200, 450300, 450400, 450500, 450600, 450700, 450800, 450900, 451000, 451100, 451200, 451300, 451400, 460100, 460200, 469001, 469002, 469003, 469005, 469006, 469007, 469025, 469026, 469027, 469028, 469030, 469031, 469033, 469034, 469035, 469036, 469037, 469038, 469039, 500100, 510100, 510300, 510400, 510500, 510600, 510700, 510800, 510900, 511000, 511100, 511300, 511400, 511500, 511600, 511700, 511800, 511900, 512000, 513200, 513300, 513400, 520100, 520200, 520300, 520400, 522200, 522300, 522400, 522600, 522700, 530100, 530300, 530400, 530500, 530600, 530700, 530800, 530900, 532300, 532500, 532600, 532800, 532900, 533100, 533300, 533400, 540100, 542100, 542200, 542300, 542400, 542500, 542600, 610100, 610200, 610300, 610400, 610500, 610600, 610700, 610800, 610900, 611000, 620100, 620200, 620300, 620400, 620500, 620600, 620700, 620800, 620900, 621000, 621100, 621200, 622900, 623000, 630100, 632100, 632200, 632300, 632500, 632600, 632700, 632800, 640100, 640200, 640300, 640400, 640500, 650100, 650200, 652100, 652200, 652300, 652700, 652800, 652900, 653000, 653100, 653200, 654000, 654200, 654300, 659001, 659002, 659003, 659004, 710100, 710200, 710300, 710400, 710500, 710600, 710700, 710800, 710900, 711100, 711200, 711300, 711400, 711500, 711700, 711900, 712100, 712400, 712500, 712600, 712700, 810100, 810200, 810300, 820100, 820200];
    foreach ($allCityids as $cityid) {
        spider("http://www.bytrip.com/Index/Fere/index/status/2/area/$cityid/low_age/18/high_age/40/sex/2/lt/1/rt/0/hot/p",
            function ($html) {
                return BytripSearchMembers($html);
            },
            function ($url, $nextPno) {
                return $url . "/p_$nextPno";
            },
            function ($row) {
                $pwd = md5("111222333");
                $id = $row['id'];
                $age = $row['age'];
                $sex = $row['sex'];
                $nickname = ($row['nickname']);
                $main_pic = $row['main_pic'];
                $profile = str_replace("\n", "\\n", str_replace("\r\n", "\\r\\n", $row['profile']));
                $address = ($row['address']);
                $valuePer = " INSERT INTO member2( id, nickname,pwd, age , `profile`, address, sex , main_pic)VALUES('$id','$nickname', '$pwd', '$age','$profile','$address','$sex', '$main_pic');\n";

                try {
                    ExtDownloadPic("http://www.bytrip.com/$main_pic", ".");
                } catch (Exception $e) {
                }
                file_put_contents("a.sql", $valuePer, FILE_APPEND);
            },1,$limit
        );
    }
}
