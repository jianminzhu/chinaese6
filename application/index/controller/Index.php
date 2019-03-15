<?php

namespace app\index\controller;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;


class Index extends Base
{
    public function index()
    {
        $this->headData();
        try {
            $this->searchData();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        return $this->fetch('index');
    }

    public function search()
    {
        $this->searchData();
        return view("/index/search");
    }
}
