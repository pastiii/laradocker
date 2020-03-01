<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user', function (Request $request) {
    return "jjj";
});


Route::get('/asd', 'Auth\DemoController@Asd');
Route::get('/demo', 'Auth\DemoController@Demo');
Route::post('/demo', 'Auth\DemoController@Demo');


Route::group(['middleware' => 'login'], function () {
    #用户
    Route::prefix('user')->namespace('Admin')->group(function () {
        Route::post('/create-user', 'UserController@CreateUser');
        Route::post('/edit-user', 'UserController@EditUser');
        Route::get('/user-detail', 'UserController@UserDetail');
        Route::get('/user-list', 'UserController@UserList');
        Route::post('/lock-user', 'UserController@LockUser');
        Route::get('/del-user', 'UserController@DelUser');
    });

    #员工
    Route::prefix('staff')->namespace('Admin')->group(function () {
        Route::get('/staff-list', 'StaffController@StaffList');
        Route::post('/edit-state', 'StaffController@EditState');
        Route::get('/export', 'StaffController@Export');
        Route::get('/staff-detail', 'StaffController@StaffDetail');
    });

    #退登
    Route::get('/admin/logout', 'Admin\LoginController@Logout');
});

#后台登录
Route::post('/admin/login', 'Admin\LoginController@Login');


#todo ---------------------------------------------------  我叫分割线  -------------------------------------------------#

Route::group(['middleware' => 'mobile'], function () {
    #发布的数据
    Route::prefix('mobile/staff')->namespace('Mobile')->group(function () {
        Route::post('/create-staff', 'StaffController@CreateStaff');
    });

    #退出登录
    Route::get('/mobile/logout', 'Mobile\LoginController@Logout');
});

#手机端登陆
Route::post('/mobile/login', 'Mobile\LoginController@Login');

#后台手机登陆
Route::post('/admin/phone-login', 'Admin\LoginController@PhoneLogin');

#手机端手机登陆
Route::post('/mobile/phone-login', 'Mobile\LoginController@PhoneLogin');


#todo---------------------------------------------------  公共  --------------------------------------------------------#
Route::get('/common/send-sms', 'CommonController@SendSms');
Route::get('/common/area-select', 'CommonController@AreaSelect');
Route::post('/common/upload-file', 'CommonController@UploadFile');
Route::get('/common/down-file', 'CommonController@DownFile');

#apply
Route::post('/common/upload-doc', 'CommonController@UploadDoc');
Route::get('/common/down-doc', 'CommonController@DownDoc');
Route::get('/common/apply-enterprise', 'CommonController@ApplyEnterprise');
Route::get('/common/file-down', 'CommonController@FileDown');