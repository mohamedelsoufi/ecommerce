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
    Route::post('register', 'App\Http\Controllers\Api\site\authentication\registration@userRegister');

    Route::post('login', 'App\Http\Controllers\Api\site\authentication\auth@login')->name('user');

    Route::post('forgetPasswored/sendMail', 'App\Http\Controllers\Api\site\authentication\resetPasswored@sendEmail')->name('user');
    Route::post('forgetPasswored/checkCode', 'App\Http\Controllers\Api\site\authentication\resetPasswored@checkCode')->name('user');
    Route::post('forgetPasswored/passwordResetProcess', 'App\Http\Controllers\Api\site\authentication\resetPasswored@passwordResetProcess')->name('user');

    Route::post('verification/sendMail', 'App\Http\Controllers\Api\site\authentication\verification@sendEmail')->name('user');
    Route::post('verification', 'App\Http\Controllers\Api\site\authentication\verification@passwordResetProcess')->name('user');


    Route::group(['middleware' => ['checkJWTtoken:user']], function() {
        Route::get('profile', 'App\Http\Controllers\Api\site\authentication\profile@getProfile')->name('user');
        Route::post('changePassword', 'App\Http\Controllers\Api\site\authentication\profile@changePassword')->name('user');

        Route::post('logout', 'App\Http\Controllers\Api\site\authentication\auth@logout')->name('user');

        Route::get('home', 'App\Http\Controllers\Api\site\user@home');

        Route::post('love', 'App\Http\Controllers\Api\site\user@love');

        Route::post('comment/add', 'App\Http\Controllers\Api\site\user@add_comment');
        Route::post('comment/delete', 'App\Http\Controllers\Api\site\user@delete_comment');
        Route::post('comment/edit', 'App\Http\Controllers\Api\site\user@edit_comment');

        Route::post('rating', 'App\Http\Controllers\Api\site\user@rating');

        Route::post('contact_us', 'App\Http\Controllers\Api\site\all@contact_us')->name('user');

        Route::get('cart', 'App\Http\Controllers\Api\site\user@cart_get');
        Route::post('cart/add', 'App\Http\Controllers\Api\site\user@cart_add');
        Route::post('cart/edit', 'App\Http\Controllers\Api\site\user@cart_edit');
        Route::post('cart/remove', 'App\Http\Controllers\Api\site\user@cart_remove');
        Route::post('cart/empty', 'App\Http\Controllers\Api\site\user@cart_empty');
    });
});










