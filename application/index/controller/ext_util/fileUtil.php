<?php
function ExtDirectory($dir)
{
    return is_dir($dir) or ExtDirectory(dirname($dir)) and mkdir($dir, 0777);

}

function ExtDownload($url, $path = 'downloads/', $withHost = false, $isCover = false)
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