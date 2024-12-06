<?php

namespace Database\Factories\Hr;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\Hr\HrOrganization;

class HrOrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create(); // Create a Faker instance

        // Generate a unique org_name
        $fakeOrgName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        // Check for uniqueness of org_name in the database
        while (HrOrganization::where('org_name', $fakeOrgName)->exists()) {
            // Generate a new org_name if it already exists
            $fakeOrgName = Str::title($faker->unique()->words(3, true)); // Generate a unique name with 3 words
        }

        // Generate a unique org_abbr
        $fakeOrgAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        // Check for uniqueness of org_abbr in the database
        while (HrOrganization::where('org_abbr', $fakeOrgAbbr)->exists()) {
            // Generate a new org_abbr if it already exists
            $fakeOrgAbbr = strtoupper($faker->unique()->lexify(str_repeat('?', rand(4, 8)))); // Random uppercase abbreviation of 4-8 characters
        }

        return [
            'org_name' => $fakeOrgName,
            'org_abbr' => $fakeOrgAbbr,
            'org_slogan' => $faker->sentence(rand(6, 10)), // Random slogan with 6-10 words
            'org_details' => $faker->paragraph(rand(1, 2)), // Random details with 2-4 sentences
            'org_email' => $faker->unique()->safeEmail, // Unique email
            'org_phone' => $faker->unique()->numerify('###########'), // Unique 11-digit phone number
            'org_website' => $faker->url, // Random website URL
            'org_address' => $faker->address, // Random address
            'org_logo' => 'logos/logo' . rand(1, 5) . '.png', // Random logo from predefined options
            'created_by' => $faker->numberBetween(1, 10), // Random user ID between 1 and 10
        ];
    }
}
