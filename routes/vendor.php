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

Route::group(['middleware' => ['changeLang'], 'prefix' => 'vendor'], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\vendors\authentication\registration@create');

    Route::post('login', 'App\Http\Controllers\Api\site\vendors\authentication\login@login');

    Route::group(['prefix' => 'resetPasswored'], function(){
        Route::post('sendCode', 'App\Http\Controllers\Api\site\vendors\authentication\resetPasswored@sendCode');
        Route::post('checkCode', 'App\Http\Controllers\Api\site\vendors\authentication\resetPasswored@checkCode');
        Route::post('passwordResetProcess', 'App\Http\Controllers\Api\site\vendors\authentication\resetPasswored@passwordResetProcess')->middleware('checkJWTtoken:vendor');
    });   

    Route::group(['middleware' => ['checkJWTtoken:vendor']], function() {
        Route::post('logout', 'App\Http\Controllers\Api\site\vendors\authentication\login@logout')->name('vender');

        Route::group(['prefix' => 'verification'], function(){
            Route::post('sendCode', 'App\Http\Controllers\Api\site\vendors\authentication\verification@send_code_to_vendor_from_token');
            Route::post('/', 'App\Http\Controllers\Api\site\vendors\authentication\verification@verificationProcess');
        });

        Route::group(['prefix' => 'profile'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\vendors\authentication\profile@index');
            Route::post('update', 'App\Http\Controlles\Api\site\vendors\authentication\profile@update');
            Route::post('changePassword', 'App\Http\Controllers\Api\site\vendors\authentication\profile@changePasswordProcess');
        });

        //product
        Route::group(['prefix' => 'products'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\vendors\products@index');
            Route::post('create', 'App\Http\Controllers\Api\site\vendors\products@create');
            Route::post('update', 'App\Http\Controllers\Api\site\vendors\products@update');
            Route::post('delete', 'App\Http\Controllers\Api\site\vendors\products@delete');
        });

        //products
        Route::group(['prefix' => 'products'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\vender@products');
            Route::get('informations', 'App\Http\Controllers\Api\site\vender@products_informations');
            Route::get('money', 'App\Http\Controllers\Api\site\vender@money');
        });

        Route::get('home', 'App\Http\Controllers\Api\site\vender@home');

        //product
        Route::group(['prefix' => 'product'], function(){
            Route::get('order', 'App\Http\Controllers\Api\site\vender@product_order');
            Route::post('add', 'App\Http\Controllers\Api\site\vender@product_add');
            Route::post('delete', 'App\Http\Controllers\Api\site\vender@product_delete');
            Route::post('edit', 'App\Http\Controllers\Api\site\vender@product_edit');
        });

    });
});





