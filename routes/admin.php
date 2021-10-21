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

Route::get('/login', 'App\Http\Controllers\Api\admin\authentication@loginView')->name('loginView')->middleware('guest:admin');
Route::post('/login', 'App\Http\Controllers\Api\admin\authentication@login');

Route::get('/logout', 'App\Http\Controllers\Api\admin\authentication@logout')->middleware('auth:admin');













