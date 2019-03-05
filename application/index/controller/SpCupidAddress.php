<?php

namespace app\index\controller;
require('ext_util/fileUtil.php');
require('ext_util/BytripUtil.php');

use app\index\model\Address;
use think\Controller;
use think\Db;

class SpCupidAddress extends Controller
{

    public function test(){

        return $this->getAllAddress(5);


    }
    public function getAllAddress($countryid)
    {

        $contryStateUrl = "https://www.chinalovecupid.com/zc/widget/loadstates?countryid=$countryid";
        $states = json_decode(ExtGetHtml($contryStateUrl));
        echo json_encode($states)."=========================";
        $stateId= $states[0]["ATTRIBUTEID"] ;
        $stateCityUrl = "https://www.chinalovecupid.com/zc/widget/loadcities?stateid=$stateId";
        $cities = json_decode(ExtGetHtml($stateCityUrl.$stateId));
        foreach ($cities as $city) {
            $toDbCity = new CupidAddress($city);
            $toDbCity["countryid"] = $countryid;
            $toDbCity["stateid"] = $stateId;
            try {
                $toDbCity->save();
            } catch (\Exception $e) {
            }
            echo "save succ " . json_encode($toDbCity);
        }
        //foreach ($states as $state) {  }
        $dataDb = Db::table("CupidAddress")->select();
        return json_encode($dataDb);
    }

}



