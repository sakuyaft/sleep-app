<?php

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

Route::post('/json_async',[JsonAsyncController::class,'process'])->name('json_async');


Route::get('/heartrate', function () {
    return view('heartrate');
});