<?php

namespace Database\Factories\Hr;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\Hr\HrOrganization;
use App\Models\Hr\HrDepartment;

class HrDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create(); // Create a Faker instance

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

        // Generate a unique dept_name
        $fakeDeptName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        // Check for uniqueness of dept_name in the database
        while (HrDepartment::where('dept_name', $fakeDeptName)->exists()) {
            // Generate a new dept_name if it already exists
            $fakeDeptName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        }

        // Generate a unique dept_abbr
        $fakeDeptAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        // Check for uniqueness of dept_abbr in the database
        while (HrDepartment::where('dept_abbr', $fakeDeptAbbr)->exists()) {
            // Generate a new dept_abbr if it already exists
            $fakeDeptAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        }

        return [
            'dept_name' => $fakeDeptName,
            'dept_abbr' => $fakeDeptAbbr,
            'dept_title' => $faker->sentence(rand(6, 10)), // Random title with 6-10 words
            'ud_sl_no' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
            'dept_str_id' => 'DPT' . date('Ymd') . '000001',
            'org_id' => $organization ? $organization->org_id : null,
            'cbranch_id' => $faker->numberBetween(1, 5), // Random user ID between 1 and 5
            'cobunit_id' => $faker->numberBetween(0, 1), // Random user ID between 0 and 1
            'ptgunit_id' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
            'created_by' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
        ];
    }
}
