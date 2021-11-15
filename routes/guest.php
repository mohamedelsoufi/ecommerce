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

Route::group(['middleware' => ['changeLang'], 'prefix' => 'guest'], function() {
    Route::get('mainCategorys', 'App\Http\Controllers\Api\site\guest@getCategories');
    Route::get('mainCategorys/details', 'App\Http\Controllers\Api\site\guest@main_cate_details');

    Route::get('SubCategorys/details', 'App\Http\Controllers\Api\site\guest@sub_cate_details');

    Route::get('product/details', 'App\Http\Controllers\Api\site\guest@product_details');

    Route::get('filter', 'App\Http\Controllers\Api\site\guest@filter');
    Route::get('search', 'App\Http\Controllers\Api\site\guest@search');

    Route::post('promoCode/check', 'App\Http\Controllers\Api\site\user@check_promoCode');
});










