<?php

use Illuminate\Support\Facades\Route;






Route::middleware(['throttle:5,1'])->group(function(){
    Route::post('store', 'App\Http\Controllers\TestController@storeRegistration')->name('store');
});

Route::get('/test', 'App\Http\Controllers\TestController@index')->name('test');
Route::get('registration', 'App\Http\Controllers\TestController@registration')->name('registration');
Route::get('login', 'App\Http\Controllers\TestController@login')->name('login');
Route::post('signin', 'App\Http\Controllers\TestController@signin')->name('signin');













Route::get('/', function () {
    return view('welcome');
});
