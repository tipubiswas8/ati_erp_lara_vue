<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Hr\HrEmployee;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    { 
        Route::bind('employee', function ($value) {
            return HrEmployee::where('employee_id', $value)->firstOrFail(); // Use 'employee_id' or your custom primary key
        });
    }
}
