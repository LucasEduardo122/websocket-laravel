<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/users', ['App\Http\Controllers\Api\UserController', 'index'])->name('users.index');
    Route::get('/users/{userId}', ['App\Http\Controllers\Api\UserController', 'show'])->name('users.show');
    Route::get('/userLogged', ['App\Http\Controllers\Api\UserController', 'logged'])->name('users.logged');

    Route::get('/messages/{userId}', ['App\Http\Controllers\Api\MessageController', 'index'])->name('message.index');
    Route::post('/messages/store', ['App\Http\Controllers\Api\MessageController', 'store'])->name('message.store');
});