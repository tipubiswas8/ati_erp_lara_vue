<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\HrDesignation;

// php artisan db:seed Database\Seeders\DesignationSeeder
class DesignationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // /* for factory */
        // // create for save data to database
        // HrDesignation::factory(10)->create();
        // // make for make factory data without save to database
        // // HrDesignation::factory(10)->make();



        // // /* for seeder */
        // // /* Method 1*/

        // // if use HrDesignation::create([]); use boot method from HrDesignation Model
        // // Generate 10 organization entries
        // foreach (range(1, 10) as $i) {
        //     $deptSeeder  = new DepartmentSeeder;
        //     $randomDeptId = $deptSeeder->getRandomDeptId();
        //     $latestDesig = HrDesignation::max('desig_id');
        //     if ($latestDesig) {
        //         $latestSequencePart = substr($latestDesig, -6) + 1; // Extract the sequence part (last 6 digits)
        //     } else {
        //         $latestSequencePart = '1';
        //     }
        //     HrDesignation::create([
        //         'desig_name' => 'Designation ' . $latestSequencePart,
        //         'desig_abbr' => 'TDESG' . $latestSequencePart,
        //         'desig_title' => 'Test Designation ' . $i,
        //         'ud_sl_no' => $i,
        //         'desig_str_id' => date('Ymd') . '000001',
        //         'org_id' => OrganizationSeeder::getRandomOrgId(),
        //         'dept_id' => $randomDeptId,
        //         'cbranch_id' => $i,
        //         'cobunit_id' => $i,
        //         'ptgunit_id' => $i,
        //         'created_by' => $i,
        //     ]);
        // }



        /* Method 2*/
        // if use DB::table('hr_designations')->insert($designations); please commend the boot method from HrDesignation Model

        $currentDate = now()->format('Ymd'); // Get the current date in YYYYMMDD format
        $latestDesig = HrDesignation::max('desig_id');
        if ($latestDesig) {
            $latestSequencePart = (int)substr($latestDesig, -6) + 1; // Extract the sequence part (last 6 digits)
            $baseId =  $currentDate . str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $latestSequencePart = '1';
            $baseId = $currentDate . '000001';  // Default ID for the first record of the day
        }
        $designations = []; // Initialize an empty array to hold designation data
        // Generate 10 designation entries
        foreach (range(1, 10) as $i) {
            $deptSeeder  = new DepartmentSeeder;
            $randomDeptId = $deptSeeder->getRandomDeptId();
            $designations[] = [ // Append each designation entry to the array
                'desig_id' => (int)$baseId,
                'desig_name' => 'Designation ' . $latestSequencePart,
                'desig_abbr' => 'TDESG' . $latestSequencePart,
                'desig_title' => 'Test Designation ' . $i,
                'ud_sl_no' => $i,
                'desig_str_id' => date('Ymd') . '000001',
                'org_id' => OrganizationSeeder::getRandomOrgId(),
                'dept_id' => $randomDeptId,
                'cbranch_id' => $i,
                'cobunit_id' => $i,
                'ptgunit_id' => $i,
                'created_by' => $i,
            ];
            $latestSequencePart = $latestSequencePart + 1;
            $baseId = $baseId + 1;
        }
        // Insert all data into the `hr_designations` table
        DB::table('hr_designations')->insert($designations);
    }
}
