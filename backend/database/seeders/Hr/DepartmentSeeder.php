<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\HrDepartment;
use Database\Seeders\Hr\OrganizationSeeder;

// php artisan db:seed Database\Seeders\DepartmentSeeder
class DepartmentSeeder extends Seeder
{

    public function getRandomDeptId()
    {
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
        return $department ? $department->dept_id : null;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // /* for factory */
        // // create for save data to database
        // HrDepartment::factory(10)->create();
        // // make for make factory data without save to database
        // // HrDepartment::factory(10)->make();


        // // /* for seeder */ 
        // // /* Method 1*/
        // // if use HrDepartment::create([]); use boot method from HrDepartment Model
        // //  Generate 10 organization entries
        
        // foreach (range(1, 10) as $i) {
        //     $latestDept = HrDepartment::max('dept_id');
        //     if ($latestDept) {
        //         $latestSequencePart = substr($latestDept, -6) + 1; // Extract the sequence part (last 6 digits)
        //     } else {
        //         $latestSequencePart = '1';
        //     }
        //     HrDepartment::create([
        //         'dept_name' => 'Department ' . $latestSequencePart, // for unique dept_name
        //         'dept_abbr' => 'TDPT' . $latestSequencePart, // for unique dept_abbr
        //         'dept_title' => 'Test Department ' . $i,
        //         'ud_sl_no' => $i,
        //         'dept_str_id' => 'DPT' . date('Ymd') . '000001',
        //         'org_id' => OrganizationSeeder::getRandomOrgId(),
        //         'cbranch_id' => $i,
        //         'cobunit_id' => $i,
        //         'ptgunit_id' => $i,
        //         'created_by' => $i,
        //     ]);
        // }



        // // /* Method 2*/
        // // if use DB::table('hr_departments')->insert($departments); please commend the boot method from HrDepartment Model

        // $currentDate = now()->format('Ymd'); // Get the current date in YYYYMMDD format
        // $latestDept = HrDepartment::max('dept_id');
        // if ($latestDept) {
        //     $latestSequencePart = substr($latestDept, -6); // Extract the sequence part (last 6 digits)
        //     $baseId = $currentDate . str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
        //     $lastSixDigit = str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
        // } else {
        //     $lastSixDigit = '000001';
        //     $baseId = $currentDate . '000001';  // Default ID for the first record of the day
        // }
        // $departments = []; // Initialize an empty array to hold department data
        // // Generate 10 department entries
        // foreach (range(1, 10) as $i) {
        //     $departments[] = [ // Append each department entry to the array
        //         'dept_id' => 'DPT' . $baseId,
        //         'dept_name' => 'Department ' . $lastSixDigit, // for unique dept_name
        //         'dept_abbr' => 'TDPT' . $lastSixDigit, // for unique dept_abbr
        //         'dept_title' => 'Test Department ' . $i,
        //         'ud_sl_no' => $i,
        //         'dept_str_id' => 'DPT' . date('Ymd') . '000001',
        //         'org_id' => OrganizationSeeder::getRandomOrgId(),
        //         'cbranch_id' => $i,
        //         'cobunit_id' => $i,
        //         'ptgunit_id' => $i,
        //         'created_by' => $i,
        //     ];
        //     $lastSixDigit =  str_pad($lastSixDigit + 1, 6, '0', STR_PAD_LEFT);
        //     $baseId = $baseId + 1;
        // }
        // // Insert all data into the `hr_departments` table
        // DB::table('hr_departments')->insert($departments);




        /* Method 3*/
        $currentDate = now()->format('Ymd'); // Get the current date in YYYYMMDD format
        $latestDept = HrDepartment::max('dept_id');
        if ($latestDept) {
            $latestSequencePart = substr($latestDept, -6); // Extract the sequence part (last 6 digits)
            $baseId = $currentDate . str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
            $lastSixDigit = str_pad($latestSequencePart + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $lastSixDigit = '000001';
            $baseId = $currentDate . '000001';  // Default ID for the first record of the day
        }

        // Generate 10 department entries
        foreach (range(1, 10) as $i) {
            $department = new HrDepartment;
            $department->dept_id = 'DPT' . $baseId;
            $department->dept_name = 'Department ' . $lastSixDigit; // for unique dept_name
            $department->dept_abbr = 'TDPT' . $lastSixDigit; // for unique dept_abbr
            $department->dept_title = 'Test Department ' . $i;
            $department->ud_sl_no = $i;
            $department->dept_str_id = 'DPT' . date('Ymd') . '000001';
            $department->org_id = OrganizationSeeder::getRandomOrgId();
            $department->cbranch_id = $i;
            $department->cobunit_id = $i;
            $department->ptgunit_id = $i;
            $department->created_by = $i;
            $department->save();

            $lastSixDigit =  str_pad($lastSixDigit + 1, 6, '0', STR_PAD_LEFT);
            $baseId = $baseId + 1;
        }
    }
}
