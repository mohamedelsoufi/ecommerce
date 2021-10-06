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

Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});

Route::group(['middleware' => ['changeLang']], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\register@register');

    Route::post('login', 'App\Http\Controllers\Api\site\authentication\auth@login')->name('user');

    Route::group(['middleware' => ['checkJWTtoken:user']], function() {
        Route::get('profile', 'App\Http\Controllers\Api\site\authentication\profile@getProfile')->name('user');
        Route::post('logout', 'App\Http\Controllers\Api\site\authentication\auth@logout')->name('user');
    });
});







