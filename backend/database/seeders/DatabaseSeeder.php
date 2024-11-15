<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Hr\EmployeeSeeder;
use Database\Seeders\Sa\UserSeeder;

// php artisan db:seed 
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(EmployeeSeeder::class);
        $this->call(UserSeeder::class);
        // Add other seeders here if needed
    }
}
