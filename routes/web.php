<?php

use Illuminate\Support\Facades\Route;






Route::middleware(['throttle:5,1'])->group(function(){
    Route::get('/test', 'App\Http\Controllers\TestController@index')->name('test');
});













Route::get('/', function () {
    return view('welcome');
});
