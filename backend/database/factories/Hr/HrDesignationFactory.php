<?php

namespace Database\Factories\Hr;

use App\Models\Hr\HrDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\Hr\HrOrganization;
use App\Models\Hr\HrDesignation;

class HrDesignationFactory extends Factory
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

        $department = [];
        // Randomly select an department
        if (env('USE_MONGODB', false)) {
            // data get randomly for mongodb
            $department = HrDepartment::raw(function ($collection) {
                return $collection->aggregate([
                    ['$sample' => ['size' => 1]]
                ]);
            })->first();
        } else {
            // inRandomOrder method is not supported mongodb
            $department = HrDepartment::inRandomOrder()->first();
        }
        
        // Generate a unique desig_name
        $fakeDesigName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        // Check for uniqueness of desig_name in the database
        while (HrDesignation::where('desig_name', $fakeDesigName)->exists()) {
            // Generate a new desig_name if it already exists
            $fakeDesigName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        }

        // Generate a unique desig_abbr
        $fakeDesigAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        // Check for uniqueness of desig_abbr in the database
        while (HrDesignation::where('desig_abbr', $fakeDesigAbbr)->exists()) {
            // Generate a new desig_abbr if it already exists
            $fakeDesigAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        }



        return [
            'desig_name' => $fakeDesigName,
            'desig_abbr' => $fakeDesigAbbr,
            'desig_title' => $faker->sentence(rand(6, 10)), // Random title with 6-10 words
            'ud_sl_no' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
            'desig_str_id' => date('Ymd') . '000001',
            'org_id' => $organization ? $organization->org_id : '',
            'dept_id' => $department ? $department->dept_id : null,
            'cbranch_id' => $faker->numberBetween(1, 5), // Random user ID between 1 and 5
            'cobunit_id' => $faker->numberBetween(0, 1), // Random user ID between 0 and 1
            'ptgunit_id' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
            'created_by' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
        ];
    }
}
