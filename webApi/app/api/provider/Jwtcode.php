<?php

namespace app\api\provider;

use \Firebase\JWT\JWT;

class Jwtcode
{
    public function getToken($data)
    {
        $time = time();
        $token = [
            'iss' => config('config.web_url'), //签发者 可选
            'aud' => config('config.web_url'), //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + (3600 * 10), //过期时间
            'data' => $data
        ];
        $token = JWT::encode($token, config('config.tokenKey')); //输出Token
        return $token;
    }
    public function getData($token)
    {
        try {
            $get = JWT::decode($token, config('config.tokenKey'), array('HS256'));
            if ($get) {
                return $get->data;
            } else {
                return "";
            }
        } catch (\Throwable $th) {
            return "";
        }
    }
}
