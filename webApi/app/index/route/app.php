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
    Route::post('getAreaList', 'Index/getAreaList');
    Route::post('getHospitalList', 'Index/getHospitalList');
    Route::post('getDepartment', 'Index/getDepartment');
    Route::post('submitContent', 'Index/submitContent');
    Route::post('ajaxLogin', 'Admin/ajaxLogin');
});
