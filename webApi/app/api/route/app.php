<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::group(function () {
    Route::post('login', 'User/loginAdmin');
});
Route::group(function () {
    Route::post('getUserInfo', 'User/getUserInfo');
    Route::post('logout', 'User/logoutAdmin');
})->middleware(\app\api\middleware\Token::class);
