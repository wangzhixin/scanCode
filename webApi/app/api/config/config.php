<?php
// +----------------------------------------------------------------------
// | 配置文件
// +----------------------------------------------------------------------
$webUrl = 'http://scancode.pureplusclinic.com';
return [
    'web_url' => $webUrl,
    'tokenKey' => 'FcE0eVtwb2OCu4Kfek8s45emhyWdeJxb', //token加密key
    'MD5KEYAdmin' => 'VAdkcNoTulpHMfStO5t47p7znnT2MFT3', //后台用户密码加密key
    'allow_origin' => [$webUrl,"http://localhost:9528"], //api跨域请求允许地址
    'page' => 15, //分页数量
    'adminRoles' => 'admin',
    'storeRoles' => 'store',
    'show_api_time' => false, //返回接口执行时间,
    'web_header_token_key'=>'scancode-admin'
];
