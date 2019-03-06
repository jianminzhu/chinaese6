<?php
function ExtDirectory($dir)
{
    return is_dir($dir) or ExtDirectory(dirname($dir)) and mkdir($dir, 0777);

}

function ExtWriteFile($fileWithPath, $content, $isCover = false)
{
    $pathinfo = pathinfo($fileWithPath);
    $dirname = $pathinfo["dirname"];
    if (!file_exists($dirname) || $isCover) {
        ExtDirectory($dirname);
        $f = fopen($fileWithPath, 'w');
        fwrite($f, $content);
        fclose($f);
    }
}


function ExtDownloadPic($url, $path = 'downloads/', $withHost = false, $isCover = false)
{
    $urlParam = parse_url($url);
    $pathinfo = pathinfo($urlParam["path"]);
    $fullPath = $path . ($withHost ? DIRECTORY_SEPARATOR . $urlParam["host"] : "") . DIRECTORY_SEPARATOR . $pathinfo["dirname"];
    $filename = $pathinfo["basename"];
    $realFileFullPath = $fullPath . DIRECTORY_SEPARATOR . $filename;
    if (!file_exists($realFileFullPath) || $isCover) {
        ExtDirectory($fullPath);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $resource = fopen($realFileFullPath, 'a');
        fwrite($resource, $file);
        fclose($resource);
    }
}

function ExtGetHtml($szUrl, $encoding = "", $timeout = "60", $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)')
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $szUrl);
    curl_setopt($curl, CURLOPT_HEADER, 0);  //0表示不输出Header，1表示输出
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_ENCODING, $encoding);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}


function ExtDownloadTxt($url, $path = 'downloads/', $withHost = false, $isCover = false)
{
    $urlParam = parse_url($url);
    $pathinfo = pathinfo($urlParam["path"]);
    $fullPath = $path . ($withHost ? DIRECTORY_SEPARATOR . $urlParam["host"] : "") . DIRECTORY_SEPARATOR . $pathinfo["dirname"];
    $filename = $pathinfo["basename"];
    $realFileFullPath = $fullPath . DIRECTORY_SEPARATOR . $filename;
    if (!file_exists($realFileFullPath) || $isCover) {
        ExtDirectory($fullPath);
        $fileContent = getHtml($url);
        $resource = fopen($realFileFullPath, 'a');
        fwrite($resource, $fileContent);
        fclose($resource);
    }
}