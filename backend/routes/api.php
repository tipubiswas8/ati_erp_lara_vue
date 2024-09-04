<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

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

Route::post('/login', function (Request $request) {
    return app(UserController::class)->login($request);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', function () {
        return app(UserController::class)->users();
    });
    Route::post('/user-register', [UserController::class, 'registration']);
    Route::put('/user-update', [UserController::class, 'update']);
    Route::patch('/user-status', [UserController::class, 'status']);
    Route::delete('/user-delete', [UserController::class, 'destroy']);
});
