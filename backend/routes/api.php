<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sa\SaUserController;
use App\Http\Controllers\Hr\HrEmployeeController;

Route::post('/login', function (Request $request) {
    return app(SaUserController::class)->login($request);
});


// Route::middleware('auth:api')->group(function () {
Route::get('/users', function () {
    return app(SaUserController::class)->users();
});
Route::get('/employees', function () {
    return app(HrEmployeeController::class)->allEmployee();
});
Route::post('/user-register', [SaUserController::class, 'registration']);
Route::put('/user-update', [SaUserController::class, 'update']);
Route::patch('/user-status', [SaUserController::class, 'status']);
Route::delete('/user-delete', [SaUserController::class, 'destroy']);
// });
