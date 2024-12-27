<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sa\SaRoleController;
use App\Http\Controllers\Sa\SaUserController;
use App\Http\Controllers\Sa\SaPermissionController;
use App\Http\Controllers\Hr\HrEmployeeController;
use App\Http\Controllers\Hr\HrOrganizationController;

Route::post('/login', function (Request $request) {
    return app(SaUserController::class)->login($request);
});


// Route::middleware('auth:api')->group(function () {
// user
Route::prefix('security-access')->group(function () {
    Route::get('/users', function () {
        return app(SaUserController::class)->users();
    });
    Route::post('/user-register', [SaUserController::class, 'registration']);
    Route::put('/user-update', [SaUserController::class, 'update']);
    Route::patch('/user-status', [SaUserController::class, 'status']);
    Route::delete('/user-delete/{id}', [SaUserController::class, 'destroy']);
    // role and permission
    Route::resource('roles', SaRoleController::class);
    Route::resource('permissions', SaPermissionController::class);
});
Route::prefix('hr')->group(function () {
// employees
Route::get('/all-employees', function () {
    return app(HrEmployeeController::class)->allEmployee();
});
Route::resource('employees', HrEmployeeController::class);
Route::get('employees/restore', [HrEmployeeController::class, 'restore']);
Route::resource('organizations', HrOrganizationController::class);
});
// });

// Route::middleware(['role:admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index']);
// });

// Route::middleware(['permission:create-post'])->group(function () {
//     Route::post('/posts', [PostController::class, 'store']);
// });


// $user = User::find(1);
// $role = Role::where('name', 'admin')->first();
// $user->assignRole($role);

// if ($user->hasRole('admin')) {
//     echo "User is an admin.";
// }

// if ($user->hasPermission('edit-post')) {
//     echo "User can edit posts.";
// }
