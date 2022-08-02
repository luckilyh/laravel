<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//未登录返回错误信息(不能修改)
Route::get('login', 'Api\AuthorizationsController@login')->name('login');

$middleware = [];
if (config('app.env') == 'local') {
//     $middleware[] = 'apilogger';
}

Route::middleware($middleware)->prefix('v1')->namespace('Api')->name('api.v1.')->group(function () {
    Route::get('version', function () {
        return 'this is version v1';
    })->name('version');
    Route::any('test', 'TestController@test')
        ->name('test');
    // 轮播图
    Route::get('banner', 'IndexController@banner')
        ->name('banner');
    // 碎片详情
    Route::get('fragment', 'PersonalController@fragment')
        ->name('fragment');

    //一分钟可以调用 10 次
    Route::middleware('throttle:' . config('api.rate_limits.sign'))
        ->group(function () {
            // 图片验证码
            Route::post('captchas', 'CaptchasController@store')
                ->name('captchas.store');
            // 短信验证码
            Route::post('verificationCodes', 'VerificationCodesController@store')
                ->name('verificationCodes.store');
            // 短信验证码(带图片验证码)
//            Route::post('verificationCodes', 'VerificationCodesController@store2')
//                ->name('verificationCodes.store');
            // 用户注册
            Route::post('users', 'UsersController@store')
                ->name('users.store');
            // 第三方登录
            Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
                ->name('socials.authorizations.store');
            // 登录
            Route::post('authorizations', 'AuthorizationsController@store')
                ->name('api.authorizations.store');
            // 忘记密码
            Route::post('forget_password', 'AuthorizationsController@forgetPassword')
                ->name('user.forget_password');
        });

    //一分钟调用 60 次
    Route::middleware('throttle:' . config('api.rate_limits.access'))
        ->group(function () {
            // 上传(本地)
            Route::post('upload/local', 'UploadController@local')
                ->name('upload.local');
            // 上传(云端)
            Route::post('upload/cloud', 'UploadController@cloud')
                ->name('upload.cloud');
            // 刷新token
            Route::put('authorizations/current', 'AuthorizationsController@update')
                ->name('authorizations.update');
            // 删除token
            Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                ->name('authorizations.destroy');
            // 登录后可以访问的接口
            Route::middleware('auth:api')->group(function() {
                // 发送邮箱验证
                Route::post('send_email', 'VerificationCodesController@email')
                    ->name('verificationCodes.send_email');
                // 当前登录用户信息
                Route::get('user', 'UsersController@me')
                    ->name('user.show');
                // 修改密码
                Route::post('change_password', 'AuthorizationsController@changePassword')
                    ->name('user.change_password');
            });
        });
});

Route::middleware($middleware)->prefix('v2')->name('api.v2.')->group(function () {
    Route::get('version', function () {
        return 'this is version v2';
    })->name('version');
});
