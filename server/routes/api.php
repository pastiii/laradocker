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

Route::get('approval/export', 'Admin\ApprovalController@Export');
Route::get('approval/pdf-file', 'Admin\ApprovalController@PdfFile');

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

    #企业
    Route::prefix('enterprise')->namespace('Admin')->group(function () {
        Route::post('/create-enterprise', 'EnterpriseController@CreateEnterprise');
        Route::post('/edit-enterprise', 'EnterpriseController@EditEnterprise');
        Route::get('/enterprise-detail', 'EnterpriseController@EnterpriseDetail');
        Route::get('/enterprise-list', 'EnterpriseController@EnterpriseList');
        Route::get('/del-enterprise', 'EnterpriseController@DelEnterprise');
    });

    #审批
    Route::prefix('approval')->namespace('Admin')->group(function () {
        Route::get('/approval-list', 'ApprovalController@ApprovalList');
        Route::get('/approval-detail', 'ApprovalController@ApprovalDetail');
        Route::post('/approval', 'ApprovalController@Approval');
        Route::get('/enterprise', 'ApprovalController@Enterprise');
        Route::get('/approval-process', 'ApprovalController@ApprovalProcess');
        Route::get('/apply-list', 'ApprovalController@ApplyList');
    });

    #员工
    Route::prefix('staff')->namespace('Admin')->group(function () {
        Route::get('/staff-list', 'StaffController@StaffList');
        Route::get('/staff-detail', 'StaffController@StaffDetail');
        Route::get('/enterprise-list', 'StaffController@EnterpriseList');
        Route::post('/edit-programme', 'StaffController@EditProgramme');
        Route::get('/programme', 'StaffController@Programme');
        Route::post('/import', 'StaffController@Import');
        Route::get('/import-log', 'StaffController@ImportLog');
    });

    #统计
    Route::prefix('statistics')->namespace('Admin')->group(function () {
        Route::get('/enterprise', 'StatisticsController@Enterprise');
        Route::get('/region', 'StatisticsController@Region');
        Route::get('/total', 'StatisticsController@Total');
    });

    #发布
    Route::prefix('report')->namespace('Admin')->group(function () {
        Route::get('/region', 'ReportController@Region');
        Route::get('/overview', 'ReportController@Overview');
        Route::post('/publishing', 'ReportController@Publishing');
    });

    #退登
    Route::get('/admin/logout', 'Admin\LoginController@Logout');
});

#后台登录
Route::post('/admin/login', 'Admin\LoginController@Login');


#todo ---------------------------------------------------  我叫分割线  -------------------------------------------------#

Route::group(['middleware' => 'mobile'], function () {
    #发布的数据
    Route::prefix('mobile')->namespace('Mobile')->group(function () {
        Route::get('/area-report', 'ReportController@AreaReport');
        Route::get('/total-report', 'ReportController@TotalReport');
    });

    #退出登录
    Route::get('/mobile/logout', 'Mobile\LoginController@Logout');
});

#手机端登陆
Route::post('/mobile/login', 'Mobile\LoginController@Login');

#后台手机登陆
Route::post('/admin/phone-login', 'Admin\LoginController@PhoneLogin');

#复工申请
Route::prefix('apply')->namespace('Admin')->group(function () {
    Route::post('/create-apply', 'ApplyController@CreateApply');
    Route::post('/edit-apply', 'ApplyController@EditApply');
    Route::get('/apply-detail', 'ApplyController@ApplyDetail');
    Route::get('/approval-process', 'ApplyController@ApprovalProcess');
    Route::post('/apply-init', 'ApplyController@ApplyInit');
    Route::post('/save-apply', 'ApplyController@SaveApply');
    Route::get('/get-save', 'ApplyController@GetSave');
});



#手机端手机登陆
Route::post('/mobile/phone-login', 'Mobile\LoginController@PhoneLogin');

#复工申请
Route::prefix('/mobile/apply')->namespace('Mobile')->group(function () {
    Route::post('/create-apply', 'ApplyController@CreateApply');
    Route::post('/edit-apply', 'ApplyController@EditApply');
    Route::get('/apply-detail', 'ApplyController@ApplyDetail');
    Route::get('/approval-process', 'ApplyController@ApprovalProcess');
    Route::post('/apply-init', 'ApplyController@ApplyInit');
    Route::post('/save-apply', 'ApplyController@SaveApply');
    Route::get('/get-save', 'ApplyController@GetSave');
});



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