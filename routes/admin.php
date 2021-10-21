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

Route::get('/', 'App\Http\Controllers\Api\admin\home@home')->middleware('auth:admin');

Route::get('/users', 'App\Http\Controllers\Api\admin\users@userShow')->middleware('auth:admin');
Route::get('/users/block/{id}', 'App\Http\Controllers\Api\admin\users@block')->middleware('auth:admin');

Route::get('/venders', 'App\Http\Controllers\Api\admin\venders@venderShow')->middleware('auth:admin');
Route::get('/venders/block/{id}', 'App\Http\Controllers\Api\admin\venders@block')->middleware('auth:admin');

Route::get('/products', 'App\Http\Controllers\Api\admin\products@productShow')->middleware('auth:admin');
Route::get('/products/active/{id}', 'App\Http\Controllers\Api\admin\products@active')->middleware('auth:admin');

Route::get('/logout', 'App\Http\Controllers\Api\admin\authentication@logout')->middleware('auth:admin');


Route::get('/login', 'App\Http\Controllers\Api\admin\authentication@loginView')->name('loginView')->middleware('guest:admin');
Route::post('/login', 'App\Http\Controllers\Api\admin\authentication@login');














