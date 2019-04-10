<?php

namespace app\index\controller;

require_once 'ext_util/flexpay/FlexpayUtil.php';
require_once 'ext_util/fileUtil.php';

class Pages extends Base
{

    public function about()
    {
        $this->headData();
        return view("/index/about");
    }


    public function succ()
    {
        try {
            $params = request()->param();
            $custom1 = $params["custom1"];
            db("verotel_pay")->insert($params);
            db("verotel_user")->where("custom1", $custom1)->update(["is_pay_succ"=>1]);
            if ($this->isLogin()) {
                $loginUser = $this->loginUser();
                db("pay")->insert(["m_id" => $loginUser->id, "type" => 1, "cost" => $params["priceAmount"]]);
                $this->refreshVipInfo($loginUser);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->headData();
        return  view("/index/paysucc",["data"=>json_encode($params)]);
    }

    public function cancel()
    {
        echo json_encode(request()->param());

    }
}



