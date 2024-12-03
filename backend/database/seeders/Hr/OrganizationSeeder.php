<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\HrOrganization;
use stdClass;

// php artisan db:seed Database\Seeders\OrganizationSeeder
class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create for save data to database
        HrOrganization::factory(10)->create();
        //  make for make factory data without save to database
        // HrOrganization::factory(10)->make();


        // $logos = [
        //     'logos/logo1.png',
        //     'logos/logo2.png',
        //     'logos/logo3.png',
        //     'logos/logo4.png',
        //     'logos/logo5.png',
        // ];


        // // if use HrOrganization::create([]); use boot method from HrOrganization Model
        // //  Generate 10 organization entries

        // foreach (range(1, 100) as $i) {
        //     HrOrganization::create([
        //         'org_name' => 'ATI Limited ' . $i,
        //         'org_abbr' => 'ATI' . $i,
        //         'org_slogan' => 'Your trusted tech partner, Ati Limited!',
        //         'org_details' => 'Advance Technology Innovation Limited ' . $i,
        //         'org_email' => 'http://example.com/' . $i,
        //         'org_phone' => '01912654320' . ($i + 1),
        //         'org_website' => 'http://example.com/' . $i,
        //         'org_address' => 'ATI Center, House # 7 Gareeb-e-Nawaz Ave, Dhaka 1230',
        //         'org_logo' => $logos[$i % count($logos)], // Rotate through logos
        //         'created_by' => 1,
        //     ]);
        // }




        // // if use DB::table('hr_organizations')->insert($organizations); please commend the boot method from HrOrganization Model

        // function orgIdmaker($latestOrgId, $currentDate)
        // {
        //     // Increment the numeric part of the ID
        //     $lastNumber = $latestOrgId ? (int)substr($latestOrgId, -5) : 0;
        //     $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        //     return "ORG{$currentDate}{$newNumber}";
        // }

        // // Get the current date and the latest organization ID
        // $currentDate = now()->format('Ymd');
        // $latestOrg = DB::table('hr_organizations')
        //     ->where('org_id', 'like', "ORG$currentDate%")
        //     ->orderBy('org_id', 'desc')
        //     ->first();

        // $latestOrgId = $latestOrg ? $latestOrg->org_id : null;

        // $organizations = []; // Initialize an empty array to hold organization data
        // // Generate 10 organization entries
        // foreach (range(1, 100) as $i) {
        //     $newOrgId = orgIdmaker($latestOrgId, $currentDate); // Generate a unique ID for each iteration
        //     $latestOrgId = $newOrgId; // Update the latest ID for the next iteration
        //     $organizations[] = [ // Append each organization entry to the array
        //         'org_id' => $newOrgId,
        //         'org_name' => 'ATI Limited ' . $i,
        //         'org_abbr' => 'ATI' . $i,
        //         'org_slogan' => 'Your trusted tech partner, Ati Limited!',
        //         'org_details' => 'Advance Technology Innovation Limited' . $i,
        //         'org_email' => 'http://example.com/' . $i,
        //         'org_phone' => '01912654320' . ($i + 1),
        //         'org_website' => 'http://example.com/' . $i,
        //         'org_address' => 'ATI Center, House # 7 Gareeb-e-Nawaz Ave, Dhaka 1230',
        //         'org_logo' => $logos[$i % count($logos)], // Rotate through logos
        //         'created_by' => 1,
        //     ];
        // }
        // // Insert all data into the hr_organizations table
        // DB::table('hr_organizations')->insert($organizations);
    }
}
