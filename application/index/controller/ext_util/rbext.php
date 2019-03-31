<?php
include_once "rb.php";
function rbsave($tableName, $data)
{
    $p = rbObject($tableName, $data);
    return R::store($p);
}

/**
 * @param $tableName
 * @param $data
 * @return array|\RedBeanPHP\OODBBean
 */
function rbObject($tableName, $data)
{
    $p = R::dispense($tableName);
    foreach ($data as $key => $value) {
        $p[$key] = $value;
    }
    return $p;
}
function object2array($array)
{
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object2array($value);
        }
    }
    return $array;
}