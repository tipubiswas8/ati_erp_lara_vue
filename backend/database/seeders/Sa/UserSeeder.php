<?php

namespace Database\Seeders\Sa;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Sa\SaUser;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrOrganization;
use Database\Seeders\Hr\OrganizationSeeder;

// php artisan db:seed Database\Seeders\UserSeeder
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emojis = ['😊', '😂', '🤔', '🙌', '👍', '🎉', '💻', '📱', '💼', '🌟'];
        
        // /* for facrory */
        // if (env('USE_MONGODB', false)) {
        //     // Loop through each chunk of employees to create a user entry if it doesn’t exist
        //     HrEmployee::chunk(10, function ($employees) use ($emojis) {
        //         foreach ($employees as $index => $employee) {
        //             $organization = [];
        //             // Randomly select an organization
        //             if (env('USE_MONGODB', false)) {
        //                 // data get randomly for mongodb
        //                 $organization = HrOrganization::raw(function ($collection) {
        //                     return $collection->aggregate([
        //                         ['$sample' => ['size' => 1]]
        //                     ]);
        //                 })->first();
        //             } else {
        //                 // inRandomOrder method is not supported mongodb
        //                 $organization = HrOrganization::inRandomOrder()->first();
        //             }
        //             // Check if a user with the same emp_id already exists
        //             if (!SaUser::where('emp_id', $employee->employee_id)->exists()) {
        //                 $userData = [
        //                     'user_name' => 'tipu' . $employee->employee_id,
        //                     'email' => fake()->unique()->safeEmail(), // when use mongodb
        //                     'emp_id' => $employee->employee_id,
        //                     'org_id' => $organization ? $organization->org_id : null,
        //                     'role_id' => fake()->numberBetween(1, 20),
        //                     'password' => Hash::make('123456'),
        //                     'emoji' => $emojis[$index % count($emojis)], // Rotate through emojis
        //                     'created_by' => 1,
        //                 ];
        //                 // Create the user
        //                 SaUser::create($userData);
        //             }
        //         }
        //     });
        // } else {
        //     SaUser::factory()
        //         ->count(10)
        //         ->make()
        //         ->filter() // Remove null entries
        //         ->each(fn($user) => $user->save());
        // }



        
        /* for seedre */
        // Loop through each chunk of employees to create a user entry if it doesn’t exist
        HrEmployee::chunk(10, function ($employees) use ($emojis) {
            foreach ($employees as $index => $employee) {
                $randomOrgId = OrganizationSeeder::getRandomOrgId();
                // Check if a user with the same emp_id already exists
                if (!SaUser::where('emp_id', $employee->employee_id)->exists()) {
                    // Prepare the individual user data
                    if (env('USE_MONGODB', false)) {
                        $userData = [
                            'user_name' => 'tipu' . $employee->employee_id,
                            'email' => fake()->unique()->safeEmail(), // when use mongodb
                            'emp_id' => $employee->employee_id,
                            'org_id' => $randomOrgId,
                            'role_id' => fake()->numberBetween(1, 20),
                            'password' => Hash::make('123456'),
                            'emoji' => $emojis[$index % count($emojis)], // Rotate through emojis
                            'created_by' => 1,
                        ];
                    } else {
                        $userData = [
                            'user_name' => 'tipu' . $employee->employee_id,
                            'emp_id' => $employee->employee_id,
                            'org_id' => $randomOrgId,
                            'role_id' => fake()->numberBetween(1, 20),
                            'password' => Hash::make('123456'),
                            'emoji' => $emojis[$index % count($emojis)], // Rotate through emojis
                            'created_by' => 1,
                        ];
                    }
                    // Create the user
                    SaUser::create($userData);
                }
            }
        });
    }
}
