<?php
include_once "fileUtil.php";

function yieldRange($start, $limit, $step)
{
    if ($start == $limit || $step == 0) {
        return $start;
    }

    $mark = ($limit - $start > 0) == ($step > 0);
    if (!$mark) {
        $step = -$step;
    }

    $distance = abs($limit - $start);
    for ($i = $start; abs($start - $i) <= $distance; $i += $step) {
        yield  $i;
    }
}

echo "yield start memory : " . memory_get_usage() . " bytes\n";
foreach (yieldRange(500, 500, 1) as $yield) {
    ExtDownloadTxt("http://www.bytrip.com/i/status/2/area/null/low_age/18/high_age/100/sex/2/lt/1/rt/0/hot/0/p_$yield/");
    echo "\n"+$yield+" succ";
}
echo "yield end memory : " . memory_get_usage() . " bytes\n";



