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
    // Artisan::call('jwt:secret');
    return "cache clear";
});

Route::group(['middleware' => ['changeLang']], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\authentication\registration@userRegister');

    Route::post('login', 'App\Http\Controllers\Api\site\authentication\auth@login')->name('user');

    //forget passwored
    Route::group(['prefix' => 'forgetPasswored'], function(){
        Route::post('sendMail', 'App\Http\Controllers\Api\site\authentication\resetPasswored@sendEmail')->name('user');
        Route::post('checkCode', 'App\Http\Controllers\Api\site\authentication\resetPasswored@checkCode')->name('user');
        Route::post('passwordResetProcess', 'App\Http\Controllers\Api\site\authentication\resetPasswored@passwordResetProcess')->name('user');
    });

    //verification
    Route::group(['prefix' => 'verification'], function(){
        Route::post('sendMail', 'App\Http\Controllers\Api\site\authentication\verification@sendEmail')->name('user');
        Route::post('/', 'App\Http\Controllers\Api\site\authentication\verification@passwordResetProcess')->name('user');
    });

                             // ****************************//
                            //  should log in (pass token) //
                          //*************************** *//

    Route::group(['middleware' => ['checkJWTtoken:user']], function() {
        //profile 
        Route::group(['prefix' => 'profile'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\authentication\profile@getProfile')->name('user');
            Route::get('details', 'App\Http\Controllers\Api\site\user@profil_details')->name('user');
            Route::post('edite', 'App\Http\Controllers\Api\site\authentication\profile@editProdile')->name('user');
            Route::post('edite/image', 'App\Http\Controllers\Api\site\authentication\profile@edit_image')->name('user');
            Route::post('address/add', 'App\Http\Controllers\Api\site\authentication\profile@add_address')->name('user');
            Route::post('address/edit', 'App\Http\Controllers\Api\site\authentication\profile@edit_address')->name('user');
        });

        //comments
        Route::group(['prefix' => 'comment'], function(){
            Route::post('add', 'App\Http\Controllers\Api\site\user@add_comment');
            Route::post('delete', 'App\Http\Controllers\Api\site\user@delete_comment');
            Route::post('edit', 'App\Http\Controllers\Api\site\user@edit_comment');
        });
        
        //cart
        Route::group(['prefix' => 'cart'], function(){
            Route::get('/', 'App\Http\Controllers\Api\site\user@cart_get');
            Route::post('add', 'App\Http\Controllers\Api\site\user@cart_add');
            Route::post('edit', 'App\Http\Controllers\Api\site\user@cart_edit');
            Route::post('remove', 'App\Http\Controllers\Api\site\user@cart_remove');
            Route::post('empty', 'App\Http\Controllers\Api\site\user@cart_empty');
        });

        //order
        Route::group(['prefix' => 'order'], function(){
            Route::post('address', 'App\Http\Controllers\Api\site\user@order_address');
            Route::post('make', 'App\Http\Controllers\Api\site\user@make_order');
            Route::post('tracking', 'App\Http\Controllers\Api\site\user@order_tracking');
            Route::post('details', 'App\Http\Controllers\Api\site\user@order_details');
            Route::post('cancel', 'App\Http\Controllers\Api\site\user@cancel_order');
        });

        Route::post('logout', 'App\Http\Controllers\Api\site\authentication\auth@logout')->name('user');

        Route::post('changePassword', 'App\Http\Controllers\Api\site\authentication\profile@changePassword')->name('user');

        Route::get('home', 'App\Http\Controllers\Api\site\user@home');

        //loves
        Route::post('love', 'App\Http\Controllers\Api\site\user@love');
        Route::get('loves', 'App\Http\Controllers\Api\site\user@get_love');

        Route::post('rating', 'App\Http\Controllers\Api\site\user@rating');

        Route::post('contact_us', 'App\Http\Controllers\Api\site\all@contact_us')->name('user');
    });
});










