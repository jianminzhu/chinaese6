<?php
header("Content-Type: text/html;charset=utf-8");
require('phpQuery.php');
function BytripMemberPics($id)
{
    $url = "http://www.bytrip.com/i/u_$id.html";
    $detail = phpQuery::newDocumentFile($url);
    pq($detail);

//    $height = strstr(pq("p.des span:eq(0)")->html(), "身 高：", "");
//    $weight = strstr(pq("p.des span:eq(1)")->html(), "体 重：", "");
    $height = pq("p.des  span:eq(0) ")->html();
    $weight = pq("p.des  span:eq(1) ")->html();
    $pics = [];
    foreach (pq("div.m-ablum div.bd li img") as $imgs) {
        $filePath = parse_url(pq($imgs)->attr("src"))["path"];
        $pics[] = ["m_id" => $id, "file_path" => $filePath];
    }
    return ["pics" => $pics, "member" => ["height" => $height, "weight" => $weight]];
}