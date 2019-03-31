<?php

namespace app\index\controller;


class Verotel extends Base
{
    public function succ()
    {
        echo json_encode(request()->param());

    }
    public function cancel()
    {
        echo json_encode(request()->param());

    }
}



