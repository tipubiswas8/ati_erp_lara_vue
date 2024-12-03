<?php

namespace Database\Seeders\Sa;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Sa\SaUser;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrOrganization;

// php artisan db:seed Database\Seeders\UserSeeder
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaUser::factory()
            ->count(10)
            ->make()
            ->filter() // Remove null entries
            ->each(fn($user) => $user->save());

        // $emojis = ['😊', '😂', '🤔', '🙌', '👍', '🎉', '💻', '📱', '💼', '🌟'];


        // // Loop through each chunk of employees to create a user entry if it doesn’t exist
        // HrEmployee::chunk(100, function ($employees) use ($emojis) {
        //     foreach ($employees as $index => $employee) {
        //         $organization = HrOrganization::inRandomOrder()->first();
        //         // Check if a user with the same emp_id already exists
        //         if (!SaUser::where('emp_id', $employee->employee_id)->exists()) {
        //             // Prepare the individual user data
        //             $userData = [
        //                 'user_name' => 'tipu' . $employee->employee_id,
        //                 // 'email' => fake()->unique()->safeEmail(), // when use mongodb
        //                 'emp_id' => $employee->employee_id,
        //                 'org_id' => $organization->org_id ? $organization->org_id : null,
        //                 'role_id' => fake()->numberBetween(1, 20),
        //                 'password' => Hash::make('123456'),
        //                 'emoji' => $emojis[$index % count($emojis)], // Rotate through emojis
        //                 'created_by' => 1,
        //             ];
        //             // Create the user
        //             SaUser::create($userData);
        //         }
        //     }
        // });
    }
}
