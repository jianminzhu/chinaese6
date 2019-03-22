<?php
include_once "fileUtil.php";
include_once('phpQuery.php');
include_once('rb.php');

function spider($startUrl, $parseDataAndPageFunc, $makeNextUrlFunc, $dealDataFunc, $startPno = 1, $limit = 0)
{
    $allData = [];
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
    $allData[$startPno]=$data;
    $perDeal($data, $dealDataFunc);
    if (!$totalPage) {
        $totalPage = $limit;
    }
    $endPage = $totalPage;
    if ($limit > 0) {
        $endPage = $startPno + $limit;
    }
    if ($endPage > $totalPage) {
        $endPage = $totalPage;
    }
    for ($page = $startPno + 1; $page <= $endPage; $page++) {
        $nextUrl = $makeNextUrlFunc($startUrl, $page);
        $nextPageHtml = ExtGetHtml($nextUrl);
        list($nextPageData) = $parseDataAndPageFunc($nextPageHtml);
        $allData[$page]=$nextPageData;
        $perDeal($nextPageData, $dealDataFunc);
        if (count($nextPageData) < 10) {
            echo "\ntotalage $page";
            break;

        }
    }
    return $allData;
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


/**
 * @param $row
 * @return mixed
 */
function appendInsertToFile($row,$sqlFile = "a.sql")
{
    try {
        list($pwd, $id, $age, $sex, $nickname, $main_pic, $profile, $address) = getColValue($row);
        $valuePer = " INSERT INTO member( id, nickname,pwd, age , `profile`, address, sex , main_pic)VALUES('$id','$nickname', '$pwd', '$age','$profile','$address','$sex', '$main_pic');\n";
        file_put_contents($sqlFile, $valuePer, FILE_APPEND);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
function getColValue($row)
{
    $pwd = md5("111222333");
    $id = $row['id'];
    $age = $row['age'];
    $sex = $row['sex'];
    $nickname = ($row['nickname']);
    $main_pic = $row['main_pic'];
    $profile = str_replace("\n", "\\n", str_replace("\r\n", "\\r\\n", $row['profile']));
    $address = ($row['address']);
    return array($pwd, $id, $age, $sex, $nickname, $main_pic, $profile, $address);
}


function saveToDbByReadBean($row){
    try {
        list($pwd, $id, $age, $sex, $nickname, $main_pic, $profile, $address) = getColValue($row);
        $m = R::dispense( 'member4' );
        $m->pwd=$pwd;
        $m->id=$id;
        $m->age=$age;
        $m->sex=$sex;
        $m->nickname=$nickname;
        $m->main_pic=$main_pic;
        $m->profile=$profile;
        $m->address=$address;
        $id = R::store( $m );
        echo "\nsucc".$id;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}