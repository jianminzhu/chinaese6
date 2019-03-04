<?php
header("Content-Type: text/html;charset=utf-8");
require('phpQuery.php');
function get_numerics($str)
{
    preg_match_all('/\d+/', $str, $matches);
    return $matches[0];
}

function BytripSearchMembers($url)
{
    pq(phpQuery::newDocumentFile($url));
    $members = pq("div.travel-bd > ul > li");
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
            "sex" => $sex == "男" ? 1: 0
        ];
    }
    return $toDbMembers;
}



function BytripMemberPics($id)
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
    return ["pics" => $pics, "member" => ["id" => $id, "height" => $height, "weight" => $weight]];
}