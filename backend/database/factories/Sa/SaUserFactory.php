<?php

namespace Database\Factories\Sa;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Sa\SaUser;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrOrganization;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SaUserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get an employee who does not already have a user account
        $employee = HrEmployee::whereNotIn('employee_id', SaUser::pluck('emp_id'))->inRandomOrder()->first();
        // If no available employees without users, return an empty array to avoid issues


        // Generate a unique username
        $fakeUsername = fake()->unique()->userName();
        // Check for uniqueness of username in the database
        while (SaUser::where('user_name', $fakeUsername)->exists()) {
            // Generate a new username if it already exists
            $fakeUsername = fake()->unique()->userName();
        }

        $organization = [];
        // Randomly select an organization
        if (env('USE_MONGODB', false)) {
            // data get randomly for mongodb
            $organization = HrOrganization::raw(function ($collection) {
                return $collection->aggregate([
                    ['$sample' => ['size' => 1]]
                ]);
            })->first();
        } else {
            // inRandomOrder method is not supported mongodb
            $organization = HrOrganization::inRandomOrder()->first();
        }

        return [
            'user_name' => $fakeUsername,
            'emp_id' => $employee ? $employee->employee_id : 0,
            'role_id' => fake()->numberBetween(1, 20),
            'org_id' => $organization ? $organization->org_id : '',
            'password' => static::$password ??= Hash::make('123456'),
            'emoji' => fake()->emoji(),
            'remember_token' => Str::random(10),
            'created_by' => fake()->numberBetween(1, 20),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
