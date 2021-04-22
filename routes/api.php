<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
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
Route::group(['prefix' => '/auth'], function () {
    Route::post('/register', ['\App\Http\Controllers\Api\AuthController', 'register']);
    Route::post('/login', ['\App\Http\Controllers\Api\AuthController', 'login']);
});


Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('products', 'App\Http\Controllers\Api\ProductController');
    Route::put('/user/{user}', ['\App\Http\Controllers\Api\UserController', 'update']);
    Route::get('/users', ['\App\Http\Controllers\Api\UserController', 'index']);
    Route::post('/auth/logout', ['\App\Http\Controllers\Api\AuthController', 'logout']);

});
