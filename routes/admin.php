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
date_default_timezone_set('Africa/cairo');

App::singleton('langs', function(){
    return [
        'en' => 'Einglish',
        'ar' => 'Arabic',
    ];
});

Route::get('/login', 'App\Http\Controllers\Api\admin\authentication@loginView')->name('loginView')->middleware('guest:admin');
Route::post('/login', 'App\Http\Controllers\Api\admin\authentication@login');


Route::group(['middleware' => 'auth:admin'],function(){
    Route::get('/', 'App\Http\Controllers\Api\admin\home@home');
    Route::get('/logout', 'App\Http\Controllers\Api\admin\authentication@logout');

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\users@index');
        Route::get('/block/{id}', 'App\Http\Controllers\Api\admin\users@destroy');
    });
    
    Route::group(['prefix' => 'venders'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\vendors@index');
        Route::get('/block/{id}', 'App\Http\Controllers\Api\admin\vendors@destroy');
    });
    
    Route::group(['prefix' => 'products'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\products@index');
        Route::get('/delete/{id}', 'App\Http\Controllers\Api\admin\products@destroy');
        Route::get('/active/{id}', 'App\Http\Controllers\Api\admin\products@active');
    });
    
    Route::group(['prefix' => 'comments'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\comments@index');
        Route::get('/delete/{id}', 'App\Http\Controllers\Api\admin\comments@destroy');
    });
    
    Route::group(['prefix' => 'main_categories'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\main_categories@index');
        Route::get('/delete/{id}', 'App\Http\Controllers\Api\admin\main_categories@destroy');
        Route::get('/active/{id}', 'App\Http\Controllers\Api\admin\main_categories@active');
        Route::get('/add', 'App\Http\Controllers\Api\admin\main_categories@create');
        Route::post('/add', 'App\Http\Controllers\Api\admin\main_categories@store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Api\admin\main_categories@show');
        Route::post('/edit/{id}', 'App\Http\Controllers\Api\admin\main_categories@edit');
    });
    
    Route::group(['prefix' => 'sub_categories'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\sub_categories@index');
        Route::get('/delete/{id}', 'App\Http\Controllers\Api\admin\sub_categories@destroy');
        Route::get('/active/{id}', 'App\Http\Controllers\Api\admin\sub_categories@active');
        Route::get('/add', 'App\Http\Controllers\Api\admin\sub_categories@create');
        Route::post('/add','App\Http\Controllers\Api\admin\sub_categories@store');
        Route::get('/edit/{id}', 'App\Http\Controllers\Api\admin\sub_categories@show');
        Route::post('/edit/{id}', 'App\Http\Controllers\Api\admin\sub_categories@edit');
    });
    
    Route::group(['prefix' => 'orders'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\orders@index');
        Route::get('/details/{id}', 'App\Http\Controllers\Api\admin\orders@show');
        Route::get('/cancel/{id}', 'App\Http\Controllers\Api\admin\orders@cancel');
        Route::get('/active/{id}', 'App\Http\Controllers\Api\admin\orders@active');
        Route::get('/finish/{id}', 'App\Http\Controllers\Api\admin\orders@finish');
    });
    
    Route::group(['prefix' => 'promoCodes'], function(){
        Route::get('/', 'App\Http\Controllers\Api\admin\promoCode@index');
        Route::get('/add', 'App\Http\Controllers\Api\admin\promoCode@create');
        Route::post('/add', 'App\Http\Controllers\Api\admin\promoCode@store');
        Route::get('/expiry/{id}', 'App\Http\Controllers\Api\admin\promoCode@expiry');
    });
});
