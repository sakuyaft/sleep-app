<?php

use App\Http\Controllers\HeartRateController;
use App\Http\Controllers\JsonAsyncController;
use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::post('/json_process',[JsonController::class,'process'])->name('json_process');

//非同期通信処理
Route::post('/json_async',[JsonAsyncController::class,'process'])->name('json_async');


//心拍数グラフ
Route::get('/heartrate', function () {
    return view('heartrate');
});

Route::post('/heartrate_process',[HeartRateController::class,'process'])->name('heartrate_process');

//JavaScriptだけで実装
Route::get('/sleep', function () {
    return view('onlyJs');
});

