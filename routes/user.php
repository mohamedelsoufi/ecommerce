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

date_default_timezone_set('Africa/cairo');

Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    return "cache clear";
});

Route::group(['middleware' => ['changeLang']], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\users\authentication\registration@create');
    Route::post('login', 'App\Http\Controllers\Api\site\users\authentication\login@login');

    Route::group(['prefix' => 'resetPasswored'], function(){
        Route::post('sendCode', 'App\Http\Controllers\Api\site\users\authentication\resetPasswored@sendCode');
        Route::post('checkCode', 'App\Http\Controllers\Api\site\users\authentication\resetPasswored@checkCode');
        Route::post('passwordResetProcess', 'App\Http\Controllers\Api\site\users\authentication\resetPasswored@passwordResetProcess')->middleware('checkJWTtoken:user');
    });    

    Route::group(['middleware' => ['checkJWTtoken:user']], function() {
        Route::post('logout', 'App\Http\Controllers\Api\site\users\authentication\login@logout');

        Route::group(['prefix' => 'verification'], function(){
            Route::post('sendCode', 'App\Http\Controllers\Api\site\users\authentication\verification@send_code_to_user_from_token');
            Route::post('/', 'App\Http\Controllers\Api\site\users\authentication\verification@verificationProcess');
        });

        Route::group(['prefix' => 'profile'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\users\authentication\profile@index');
            Route::post('update', 'App\Http\Controllers\Api\site\users\authentication\profile@update');
            Route::post('changePassword', 'App\Http\Controllers\Api\site\users\authentication\profile@changePasswordProcess');
        });

        Route::group(['prefix' => 'addresses'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\users\addresses@index');
            Route::post('create', 'App\Http\Controllers\Api\site\users\addresses@create');
            Route::post('update', 'App\Http\Controllers\Api\site\users\addresses@update');
            Route::post('delete', 'App\Http\Controllers\Api\site\users\addresses@delete');
        });

        Route::post('rating', 'App\Http\Controllers\Api\site\users\ratings@create');

        Route::group(['prefix' => 'comments'], function(){
            Route::post('create', 'App\Http\Controllers\Api\site\users\comments@create');
            Route::post('delete', 'App\Http\Controllers\Api\site\users\comments@delete');
            Route::post('update', 'App\Http\Controllers\Api\site\users\comments@update');
        });

        Route::group(['prefix' => 'loves'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\users\loves@index');
            Route::post('change', 'App\Http\Controllers\Api\site\users\loves@change');
        });
        
        Route::group(['prefix' => 'cart'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\users\carts@index');
            Route::post('create', 'App\Http\Controllers\Api\site\users\carts@create');
            Route::post('update', 'App\Http\Controllers\Api\site\users\carts@update');
            Route::post('delete', 'App\Http\Controllers\Api\site\users\carts@delete');
            Route::post('empty', 'App\Http\Controllers\Api\site\users\carts@empty');
        });

        //order
        Route::group(['prefix' => 'order'], function(){
            Route::post('address', 'App\Http\Controllers\Api\site\user@order_address');
            Route::post('make', 'App\Http\Controllers\Api\site\user@make_order');
            Route::post('tracking', 'App\Http\Controllers\Api\site\user@order_tracking');
            Route::post('details', 'App\Http\Controllers\Api\site\user@order_details');
            Route::post('cancel', 'App\Http\Controllers\Api\site\user@cancel_order');
        });

        Route::get('home', 'App\Http\Controllers\Api\site\user@home');

        Route::post('contact_us', 'App\Http\Controllers\Api\site\all@contact_us')->name('user');
    });
});










