<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Hr\OrganizationSeeder;
use Database\Seeders\Hr\DepartmentSeeder;
use Database\Seeders\Hr\DesignationSeeder;
use Database\Seeders\Hr\EmployeeSeeder;
use Database\Seeders\Sa\UserSeeder;
use Database\Seeders\Sa\RolePermissionSeeder;

// php artisan db:seed 
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(OrganizationSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);
        // Add other seeders here if needed
    }
}
