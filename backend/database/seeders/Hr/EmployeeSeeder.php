<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;

// php artisan db:seed Database\Seeders\EmployeeSeeder
class EmployeeSeeder extends Seeder
{
    function getRandomDesigId()
    {
        $designation = [];
        // Randomly select an designation
        if (env('USE_MONGODB', false)) {
            // data get randomly for mongodb
            $designation = HrDesignation::raw(function ($collection) {
                return $collection->aggregate([
                    ['$sample' => ['size' => 1]]
                ]);
            })->first();
        } else {
            // inRandomOrder method is not supported mongodb
            $designation = HrDesignation::inRandomOrder()->first();
        }
        return $designation ? $designation->desig_id : null;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // /* for factory */
        // // create for save data to database
        // HrEmployee::factory(100)->create();
        // //  make for make factory data without save to database
        // // HrEmployee::factory(10)->make();


        
        // /* for seeder */ 
        $employees = []; // Initialize an empty array to hold employee data
        // Generate 10 employee entries
        foreach (range(1, 10) as $i) {
            $deptSeeder  = new DepartmentSeeder;
            $randomDeptId = $deptSeeder->getRandomDeptId();
            static $employeeIdCounter = null;
            // Increment the employee ID for each factory instance
            if (is_null($employeeIdCounter)) {
                // Get the current maximum employee ID from the database
                $maxEmployeeId = HrEmployee::max('employee_id');
                $employeeIdCounter = is_null($maxEmployeeId) ? 1 : $maxEmployeeId + 1; // Start from the next number
            }

            $employees[] = [ // Append each employee entry to the array
                'employee_id' => $employeeIdCounter++,
                'en_full_name' => 'John Doe ' . $i,
                'dept_id' => $randomDeptId ? $randomDeptId : 'DPT' . date('Ymd') . '000001',
                'desig_id' => self::getRandomDesigId() ? self::getRandomDesigId() : date('Ymd') . '000001',
                'usrdemp_id' => 'JD' . $i,
                'astatus_fg' => 1,
                'userdsl_no' => 100 + $i,
                'created_by' => 1,
                'relgion_id' => 1,
                'biometicid' => 'B' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'emphire_dt' => '2022-01-0' . ($i % 10 + 1),
                'empstrt_dt' => '2022-01-0' . ($i % 10 + 2),
                'bld_grp_id' => 'O+',
                'sorgndr_id' => $i % 2 === 0 ? 'M' : 'F',
                'ofie_email' => 'john.doe' . $i . '@example.com',
                'ppo_hemail' => 'john.doe' . $i . '@company.com',
                'omobile_no' => '12345678' . $i,
                'pmobile_no' => '09876543' . $i,
                'mstatus_id' => 1,
                'dtof_birth' => '1990-01-0' . ($i % 10 + 1),
                'plof_birth' => 'City ' . $i,
                'nationalid' => 'A12345' . $i,
                'weight_kgs' => 70 + $i,
                'height_cms' => 175 + $i,
                'color_eyes' => 'Brown',
                'idnty_mark' => 'None',
                'pres_addrs' => '123 Main St, City ' . $i,
                'perm_addrs' => '456 Another St, City ' . $i,
                'emp_id' => $i,
                'empl_photo' => 'photos/johndoe' . $i . '.jpg',
                'empshow_fg' => 1,
                'org_id' => OrganizationSeeder::getRandomOrgId(),
                'cbranch_id' => 1,
                'cobunit_id' => 1,
                'ptgunit_id' => 1,
                'recshow_fg' => 0,
                'emp_temp_to' => 'None',
                'emp_temp_cc' => 'None',
                'rperson_id' => 1,
                'designation' => 'Senior Developer ' . $i,
                'emer_addrs' => '789 Emergency St, City ' . $i,
                'emp_sigimg' => 'signatures/johndoe' . $i . '.png',
                'emp_nidimg' => 'nid_images/johndoe_nid' . $i . '.png',
                'country_id' => 1,
                'ppo_haemal' => 'None',
                'p2ndmob_no' => '12345678' . ($i + 1),
                'p3rdmob_no' => '09876543' . ($i + 1),
                'emp_pp_img' => 'pp_images/johndoe' . $i . '.png',
                'emp_bc_img' => 'bc_images/johndoe' . $i . '.png',
                'pwsadrlink' => 'http://example.com/' . $i,
                'pskypeaddr' => 'johndoe_skype' . $i,
                'temn_dt' => now(),
                'temn_by' => 'Admin',
                'temn_remarks' => 'Initial entry ' . $i,
                'temn_reason' => 'New hire ' . $i,
                'ptg_emp_id' => 1,
                'emp_typ_id' => 1,
                'fcm_reg_id' => '12345' . $i,
                'emp_cat_id' => 1,
                'wkshift_id' => 1,
                'emk_person' => 'Abc',
                'schedule' => 0,
                'empltyp_id' => 1,
                'empltp_dur' => 12,
                'old_emp_id' => 'OLD123' . $i,
                'hireemp_id' => 1,
                'bn_full_name' => 'জাহিদ ' . $i,
            ];
        }
        // Insert all data into the employees table
        DB::table('hr_employees')->insert($employees);
    }
}
