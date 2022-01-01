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
    Route::post('getUserList', 'User/getUserList');

    Route::post('uploadFile', 'File/uploadFile');

    Route::post('submitSetting', 'Setting/submitSetting');
    Route::post('getSetting', 'Setting/getSetting');
    
    Route::post('problemList', 'Problem/problemList');
    Route::post('submitProblem', 'Problem/submitProblem');
    Route::post('deleteProblem', 'Problem/deleteProblem');

    Route::post('getHospitalList', 'Hospital/getHospitalList');
    Route::post('submitHospital', 'Hospital/submitHospital');
    Route::post('deleteHospital', 'Hospital/deleteHospital');
    Route::post('getDepartmentList', 'Hospital/getDepartmentList');
    Route::post('submitDepartmentList', 'Hospital/submitDepartmentList');
    Route::post('deleteDepartmentList', 'Hospital/deleteDepartmentList');

    Route::post('getUserDataList', 'UserData/getUserDataList');
    Route::post('exportUserData', 'UserData/exportUserData');

    Route::post('getAdminUserList', 'AdminUser/getAdminUserList');
    Route::post('deleteAdminUser', 'AdminUser/deleteAdminUser');
    Route::post('submitAdminUser', 'AdminUser/submitAdminUser');

})->middleware(\app\api\middleware\Token::class);
