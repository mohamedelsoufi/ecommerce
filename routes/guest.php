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

Route::group(['middleware' => ['changeLang'], 'prefix' => 'guest'], function() {
    Route::post('contact_us', 'App\Http\Controllers\Api\site\all@contact_us');

    Route::get('main_categories', 'App\Http\Controllers\Api\site\guest\main_catecories@index');
    Route::get('main_categories/details', 'App\Http\Controllers\Api\site\guest\main_catecories@details');

    Route::get('sub_categories', 'App\Http\Controllers\Api\site\guest\sub_catecories@index');
    Route::get('sub_categories/details', 'App\Http\Controllers\Api\site\guest\sub_catecories@details');
    Route::get('sub_categories/byMainCategory', 'App\Http\Controllers\Api\site\guest\sub_catecories@sub_categories_by_main_category');

    Route::get('search', 'App\Http\Controllers\Api\site\guest\search@search');


    Route::get('product/details', 'App\Http\Controllers\Api\site\guest\products@index');
    Route::get('product/bySubCategory', 'App\Http\Controllers\Api\site\guest\products@product_by_sub_category');
});










