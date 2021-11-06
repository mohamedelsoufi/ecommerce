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


Route::group(['middleware' => ['changeLang'], 'prefix' => 'vender'], function() {
    Route::post('register', 'App\Http\Controllers\Api\site\authentication\registration@venderRegister');

    Route::post('login', 'App\Http\Controllers\Api\site\authentication\auth@login')->name('vender');

    Route::post('forgetPasswored/sendMail', 'App\Http\Controllers\Api\site\authentication\resetPasswored@sendEmail')->name('vender');
    Route::post('forgetPasswored/checkCode', 'App\Http\Controllers\Api\site\authentication\resetPasswored@checkCode')->name('vender');
    Route::post('forgetPasswored/passwordResetProcess', 'App\Http\Controllers\Api\site\authentication\resetPasswored@passwordResetProcess')->name('vender');

    Route::post('verification/sendMail', 'App\Http\Controllers\Api\site\authentication\verification@sendEmail')->name('vender');
    Route::post('verification', 'App\Http\Controllers\Api\site\authentication\verification@passwordResetProcess')->name('vender');

    Route::group(['middleware' => ['checkJWTtoken:vender']], function() {
        Route::get('profile', 'App\Http\Controllers\Api\site\authentication\profile@getProfile')->name('vender');
        Route::post('profile/edite', 'App\Http\Controllers\Api\site\authentication\profile@editProdile')->name('vender');
        Route::post('profile/edite/image', 'App\Http\Controllers\Api\site\authentication\profile@edit_image')->name('vender');
        Route::post('profile/address/add', 'App\Http\Controllers\Api\site\authentication\profile@add_address')->name('vender');
        Route::post('profile/address/edit', 'App\Http\Controllers\Api\site\authentication\profile@edit_address')->name('vender');

        Route::post('changePassword', 'App\Http\Controllers\Api\site\authentication\profile@changePassword')->name('vender');

        Route::post('logout', 'App\Http\Controllers\Api\site\authentication\auth@logout')->name('vender');

        Route::post('contact_us', 'App\Http\Controllers\Api\site\all@contact_us')->name('vender');

        Route::get('home', 'App\Http\Controllers\Api\site\vender@home');

        Route::get('products', 'App\Http\Controllers\Api\site\vender@products');
        Route::get('products/informations', 'App\Http\Controllers\Api\site\vender@products_informations');
        Route::get('products/money', 'App\Http\Controllers\Api\site\vender@money');

        Route::get('product/order', 'App\Http\Controllers\Api\site\vender@product_order');
        Route::post('product/add', 'App\Http\Controllers\Api\site\vender@product_add');
        Route::post('product/delete', 'App\Http\Controllers\Api\site\vender@product_delete');
        Route::post('product/edit', 'App\Http\Controllers\Api\site\vender@product_edit');

    });
});





