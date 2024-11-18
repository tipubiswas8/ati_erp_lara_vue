<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $interface = [];

        if (env('USE_MONGODB', false)) {
            $interfaces = [
                'App\Interface\Sa\SaUserInterface' => 'App\Repositories\Sa\SaUserRepositoryMongo',
                'App\Interface\Hr\HrEmployeeInterface' => 'App\Repositories\Hr\HrEmployeeRepositoryMongo',
            ];
        } else {
            $interfaces = [
                'App\Interface\Sa\SaUserInterface' => 'App\Repositories\Sa\SaUserRepository',
                'App\Interface\Hr\HrEmployeeInterface' => 'App\Repositories\Hr\HrEmployeeRepository',
            ];
        }

        foreach ($interfaces as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}