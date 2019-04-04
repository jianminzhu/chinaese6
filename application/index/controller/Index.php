<?php

namespace app\index\controller;


class Index extends Base
{
    public function index()
    {
        $this->headData();
        try {
            $this->searchMembers();
        } catch ( Exception $e) {
            echo $e->getMessage();
        }
        return $this->fetch('index');
    }

    public function search()
    {
        $this->searchMembers();
        return view("/index/search");
    }
}
