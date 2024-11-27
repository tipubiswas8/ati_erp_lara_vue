<?php

namespace Database\Factories\Hr;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\Hr\HrEmployee;
use Faker\Factory as Faker;

// php artisan make:factory Database\Factories\MySql\Hr\Employee\EmployeeFactory
class HrEmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create(); // Create a Faker instance

        static $employeeIdCounter = null;
        // Increment the employee ID for each factory instance
        if (is_null($employeeIdCounter)) {
            // Get the current maximum employee ID from the database
            $maxEmployeeId = HrEmployee::max('employee_id');
            $employeeIdCounter = is_null($maxEmployeeId) ? 1 : $maxEmployeeId + 1; // Start from the next number
        }
        return [
            'employee_id' => $employeeIdCounter++, // Max Employee ID
            'efull_name' => $faker->name(),
            'deprtmn_id' => $faker->numberBetween(1, 100), // Random department ID
            'desgton_id' => $faker->numberBetween(1, 100), // Random designation ID
            'usrdemp_id' => 'JD' . $faker->unique()->numberBetween(1000, 9999), // Unique user ID
            'astatus_fg' => $faker->boolean(), // Active status flag
            'userdsl_no' => $faker->unique()->numberBetween(1000, 2000), // Unique user serial number
            'created_by' => $faker->numberBetween(1, 10),
            'relgion_id' => $faker->numberBetween(1, 10), // Random religion ID
            'biometicid' => 'B' . str_pad($faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT), // Unique biometric ID
            'emphire_dt' => $faker->date('Y-m-d', 'now'), // Hire date
            'empstrt_dt' => $faker->date('Y-m-d', 'now'), // Start date
            'bld_grp_id' => $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']), // Blood group
            'sorgndr_id' => $faker->randomElement(['M', 'F']), // Gender
            'ofie_email' => $faker->unique()->safeEmail(), // Unique office email
            'ppo_hemail' => $faker->unique()->safeEmail(), // Unique personal email
            'omobile_no' => $this->generatePhoneNumber(15), // Mobile number
            'pmobile_no' => $this->generatePhoneNumber(15), // Personal mobile number
            'mstatus_id' => $faker->numberBetween(1, 5), // Marital status ID
            'dtof_birth' => $faker->date('Y-m-d', 'now'), // Date of birth
            'plof_birth' => $faker->city(), // Birth place
            'nationalid' => 'A12345' . $faker->unique()->numberBetween(1, 999), // Unique national ID
            'weight_kgs' => $faker->numberBetween(50, 100), // Weight in kg
            'height_cms' => $faker->numberBetween(150, 200), // Height in cm
            'color_eyes' => $faker->randomElement(['Brown', 'Blue', 'Green', 'Black']), // Eye color
            'idnty_mark' => $faker->sentence(3), // Identity mark
            'pres_addrs' => $faker->address(), // Present address
            'perm_addrs' => $faker->address(), // Permanent address
            'emp_id' => $faker->unique()->numberBetween(1, 1000), // Unique Employee ID
            'empl_photo' => 'photos/' . $faker->unique()->userName() . '.jpg', // Unique photo path
            'empshow_fg' => $faker->boolean(), // Show employee flag
            'company_id' => 100,
            'cbranch_id' => $faker->numberBetween(1, 5), // Branch ID
            'cobunit_id' => $faker->numberBetween(1, 5), // Unit ID
            'ptgunit_id' => $faker->numberBetween(1, 5), // Target unit ID
            'recshow_fg' => $faker->boolean(), // Record show flag
            'emp_temp_to' => 'None', // Temporary employment status
            'emp_temp_cc' => 'None', // Temporary employment CC
            'rperson_id' => 1, // Responsible person ID
            'designation' => $faker->jobTitle(), // Job title
            'emer_addrs' => $faker->address(), // Emergency address
            'emp_sigimg' => 'signatures/' . $faker->unique()->userName() . '.png', // Unique signature image path
            'emp_nidimg' => 'nid_images/' . $faker->unique()->userName() . '_nid.png', // Unique NID image path
            'country_id' => $faker->numberBetween(1, 250), // Country ID
            'ppo_haemal' => 'None', // Previous haematology
            'p2ndmob_no' => $this->generatePhoneNumber(15), // Second mobile number
            'p3rdmob_no' => $this->generatePhoneNumber(15), // Third mobile number
            'emp_pp_img' => 'pp_images/' . $faker->unique()->userName() . '.png', // Unique profile picture path
            'emp_bc_img' => 'bc_images/' . $faker->unique()->userName() . '.png', // Unique birth certificate image path
            'pwsadrlink' => 'http://example.com/' . $faker->unique()->numberBetween(1, 1000), // Unique web link
            'pskypeaddr' => $faker->userName(), // Skype ID
            'temn_dt' => $faker->dateTimeThisDecade()->format('Y-m-d H:i:s'), // Timestamp
            'temn_by' => $faker->numberBetween(1, 100),
            'temn_remarks' => $faker->sentence(6), // Remarks
            'temn_reason' => 'New hire ' . $faker->unique()->numberBetween(1, 100), // Reason for entry
            'ptg_emp_id' => $faker->numberBetween(1, 5), // Target employee ID
            'emp_typ_id' => $faker->numberBetween(1, 5), // Employee type ID
            'fcm_reg_id' => $faker->uuid(), // Unique FCM ID
            'emp_cat_id' => $faker->numberBetween(1, 5), // Employee category ID
            'wkshift_id' => $faker->numberBetween(1, 10), // Work shift ID
            'emk_person' => $this->getShortName(6), // Emergency contact person
            'schedule' => $faker->numberBetween(0, 1), // Schedule type
            'empltyp_id' => $faker->numberBetween(1, 5), // Employment type ID
            'empltp_dur' => $faker->numberBetween(1, 36), // Employment duration in months
            'old_emp_id' => 'OLD' . $faker->unique()->numberBetween(1000, 9999), // Unique old employee ID
            'hireemp_id' => $faker->numberBetween(1, 10),
            'emp_bn_name' => $faker->name() . ' ' . $faker->lastName(), // Unique Bangladeshi name
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified()
    {
        // 
    }

    private function getShortName($maxLength)
    {
        // Generate a random name
        $name = $this->faker->name;
        // Return a name with a maximum of $maxLength characters
        return substr($name, 0, $maxLength);
    }
    // Custom method to generate a phone number with a maximum of $maxDigits
    private function generatePhoneNumber($maxDigits)
    {
        // Generate a random number string of maximum $maxDigits length
        return $this->faker->numberBetween(0, str_repeat('9', $maxDigits)); // This ensures the number has a max of 15 digits
    }
}
