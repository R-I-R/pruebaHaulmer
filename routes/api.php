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

Route::group([
	'jwt.auth' => 'api',
	'prefix' => 'auth'
], function ($router) {
	Route::post('login', [\App\Http\Controllers\Api\UserController::class, 'login'])->name('login');
	Route::delete('login', [\App\Http\Controllers\Api\UserController::class, 'logout'])->name('logout');
	Route::post('new', [\App\Http\Controllers\Api\UserController::class, 'new'])->name('new');
	Route::get('me', [\App\Http\Controllers\Api\UserController::class, 'me'])->name('me');
	Route::put('me', [\App\Http\Controllers\Api\UserController::class, 'update'])->name('update');
	Route::delete('me', [\App\Http\Controllers\Api\UserController::class, 'delete'])->name('delete');
});
