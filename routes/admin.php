<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});

date_default_timezone_set('Africa/cairo');

App::singleton('langs', function(){
    return [
        'en' => 'Einglish',
        'ar' => 'Arabic',
    ];
});

Route::get('/', 'App\Http\Controllers\Api\admin\home@home')->middleware('auth:admin');

Route::get('/users', 'App\Http\Controllers\Api\admin\users@userShow')->middleware('auth:admin');
Route::get('/users/block/{id}', 'App\Http\Controllers\Api\admin\users@block')->middleware('auth:admin');

Route::get('/venders', 'App\Http\Controllers\Api\admin\venders@venderShow')->middleware('auth:admin');
Route::get('/venders/block/{id}', 'App\Http\Controllers\Api\admin\venders@block')->middleware('auth:admin');

Route::get('/products', 'App\Http\Controllers\Api\admin\products@productShow')->middleware('auth:admin');
Route::get('/products/delete/{id}', 'App\Http\Controllers\Api\admin\products@delete')->middleware('auth:admin');
Route::get('/products/active/{id}', 'App\Http\Controllers\Api\admin\products@active')->middleware('auth:admin');

Route::get('/comments', 'App\Http\Controllers\Api\admin\comments@commentsShow')->middleware('auth:admin');
Route::get('/comments/delete/{id}', 'App\Http\Controllers\Api\admin\comments@deleteComment')->middleware('auth:admin');

Route::get('/main_categories', 'App\Http\Controllers\Api\admin\main_categories@main_categoryShow')->middleware('auth:admin');
Route::get('/main_categories/delete/{id}', 'App\Http\Controllers\Api\admin\main_categories@main_category_delete')->middleware('auth:admin');
Route::get('/main_categories/active/{id}', 'App\Http\Controllers\Api\admin\main_categories@active')->middleware('auth:admin');
Route::get('/main_categories/add', 'App\Http\Controllers\Api\admin\main_categories@add_view')->middleware('auth:admin');
Route::post('/main_categories/add', 'App\Http\Controllers\Api\admin\main_categories@add')->middleware('auth:admin');
Route::get('/main_categories/edit/{id}', 'App\Http\Controllers\Api\admin\main_categories@edit_view')->middleware('auth:admin');
Route::post('/main_categories/edit/{id}', 'App\Http\Controllers\Api\admin\main_categories@edit')->middleware('auth:admin');


Route::get('/logout', 'App\Http\Controllers\Api\admin\authentication@logout')->middleware('auth:admin');


Route::get('/login', 'App\Http\Controllers\Api\admin\authentication@loginView')->name('loginView')->middleware('guest:admin');
Route::post('/login', 'App\Http\Controllers\Api\admin\authentication@login');














