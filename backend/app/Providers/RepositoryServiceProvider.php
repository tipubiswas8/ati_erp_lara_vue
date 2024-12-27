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
                'App\Interface\Sa\SaRoleInterface' => 'App\Repositories\Sa\SaRoleRepository',
                'App\Interface\Sa\SaPermissionInterface' => 'App\Repositories\Sa\SaPermissionRepository',
                'App\Interface\Hr\HrEmployeeInterface' => 'App\Repositories\Hr\HrEmployeeRepository',
                'App\Interface\Hr\HrOrganizationInterface' => 'App\Repositories\Hr\HrOrganizationRepository',
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
