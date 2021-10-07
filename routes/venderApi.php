<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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

// date_default_timezone_set('Africa/cairo');

Route::group(['middleware' => ['changeLang'], 'prefix' => 'vender'], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\register@registerEmployee');

    Route::post('login', 'App\Http\Controllers\Api\site\authentication\auth@login')->name('vender');

    Route::post('forgetPasswored/sendMail', 'App\Http\Controllers\Api\site\authentication\resetPasswored@sendEmail')->name('vender');
    Route::post('forgetPasswored/checkCode', 'App\Http\Controllers\Api\site\authentication\resetPasswored@checkCode')->name('vender');
    Route::post('forgetPasswored/passwordResetProcess', 'App\Http\Controllers\Api\site\authentication\resetPasswored@passwordResetProcess')->name('vender');

    Route::group(['middleware' => ['checkJWTtoken:vender']], function() {
        Route::get('profile', 'App\Http\Controllers\Api\site\authentication\profile@getProfile')->name('vender');
        Route::post('logout', 'App\Http\Controllers\Api\site\authentication\auth@logout')->name('vender');
    });
});





