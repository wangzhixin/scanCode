<?php

namespace app\api\controller;

class Error
{
    public function __call($method, $args)
    {
        throw new \think\exception\HttpException(404, '404');
    }
    public function assets()
    {
    }
}
