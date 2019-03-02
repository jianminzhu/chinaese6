<?php

namespace app\index\model;

use think\Model;


class Member extends Model
{
   /* public function getSexAttr($value)
    {
        $sex = [1 => lang("性别男"), 2 => lang("性别女")];
        try {
            return $sex [$value];
        } catch (\Exception $e) {
            return "";
        }
    }*/

    public function getAgeAttr($value)
    {
        try {
            return intval($value);
        } catch (\Exception $e) {
            return "";
        }
    }

}
