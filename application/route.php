<?php
//引入Route


return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[profile]'     => [
        ':id'   => ['/index/m/profile/', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

    '__alias__' =>  [
        'home'  =>  'index/index',
        'admin'=> 'admin/index'
    ],
];
