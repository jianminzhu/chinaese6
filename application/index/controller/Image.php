<?php

namespace app\index\controller;

use think\Controller;


//创建一个类TestImage，继承基类Controller
class Image extends Controller
{


    //定义一个方法名upload_img，和view/TestImage文件夹下面的upload_img同名，提交信息时匹配文件
    public function upload_img()
    {
        //判断是否是post 方法提交的
        if (request()->isPost()) {
            $data = input('post.');
            //处理图片上传
            //提交时在浏览器存储的临时文件名称
            if ($_FILES['image']['tmp_name']) {
                $data['image'] = $this->upload();
            }
            //讲传入的图片写入到test_images表中，使用Thinkphp5自定义的函数insert()
            $add = db('test_images')->insert($data);
            if ($add) {
                //如果添加成功，提示添加成功。success也可以定义跳转链接，success('添加图片成功！','这里写人跳转的url')
                $this->success('添加图片成功！');
            } else {
                $this->error('添加图片失败！');
            }
            return;
        }
        return view();
    }
}