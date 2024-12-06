<?php

namespace Database\Seeders\Hr;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Hr\HrOrganization;

// php artisan db:seed Database\Seeders\OrganizationSeeder
class OrganizationSeeder extends Seeder
{
    public static function getRandomOrgId()
    {
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

        if (!$organization) {
            return; // Ensure an organization exists
        }
        return $organization->org_id;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // /* for factory */
        // // create for save data to database
        // HrOrganization::factory(10)->create();
        // //  make for make factory data without save to database
        // // HrOrganization::factory(10)->make();


        // /* for seeder */ 
        $logos = [
            'logos/logo1.png',
            'logos/logo2.png',
            'logos/logo3.png',
            'logos/logo4.png',
            'logos/logo5.png',
        ];

        // // /* Method 1*/
        // // if use HrOrganization::create([]); use boot method from HrOrganization Model
        // //  Generate 10 organization entries

        // foreach (range(1, 100) as $i) {
        //     $latestOrg = HrOrganization::max('org_id');
        //     if ($latestOrg) {
        //         $latestSequencePart = substr($latestOrg, -5) + 1; // Extract the sequence part (last 5 digits)
        //     } else {
        //         $latestSequencePart = '1';
        //     }
        //     HrOrganization::create([
        //         'org_name' => 'ATI Limited ' . $latestSequencePart,
        //         'org_abbr' => 'ATI' . $latestSequencePart,
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


        // /* Method 2*/
        // if use DB::table('hr_organizations')->insert($organizations); please commend the boot method from HrOrganization Model

        function orgIdmaker($latestOrgId, $currentDate)
        {
            // Increment the numeric part of the ID
            $lastNumber = $latestOrgId ? (int)substr($latestOrgId, -5) : 0;
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            return "ORG{$currentDate}{$newNumber}";
        }

        // Get the current date and the latest organization ID
        $currentDate = now()->format('Ymd');
        $latestOrg = DB::table('hr_organizations')
            ->where('org_id', 'like', "ORG$currentDate%")
            ->orderBy('org_id', 'desc')
            ->first();

        $latestOrgId = $latestOrg ? $latestOrg->org_id : null;

        $latestOrg = HrOrganization::max('org_id');
        if ($latestOrg) {
            $latestSequencePart = substr($latestOrg, -5) + 1; // Extract the sequence part (last 5 digits)
        } else {
            $latestSequencePart = '1';
        }

        $organizations = []; // Initialize an empty array to hold organization data
        // Generate 10 organization entries
        foreach (range(1, 10) as $i) {
            $newOrgId = orgIdmaker($latestOrgId, $currentDate); // Generate a unique ID for each iteration

            $latestOrgId = $newOrgId; // Update the latest ID for the next iteration
            $organizations[] = [ // Append each organization entry to the array
                'org_id' => $newOrgId,
                'org_name' => 'ATI Limited ' . $latestSequencePart,
                'org_abbr' => 'ATI' . $latestSequencePart,
                'org_slogan' => 'Your trusted tech partner, Ati Limited!',
                'org_details' => 'Advance Technology Innovation Limited' . $i,
                'org_email' => 'http://example.com/' . $i,
                'org_phone' => '01912654320' . ($i + 1),
                'org_website' => 'http://example.com/' . $i,
                'org_address' => 'ATI Center, House # 7 Gareeb-e-Nawaz Ave, Dhaka 1230',
                'org_logo' => $logos[$i % count($logos)], // Rotate through logos
                'created_by' => 1,
            ];
            $latestSequencePart = $latestSequencePart + 1;
        }
        // Insert all data into the hr_organizations table
        DB::table('hr_organizations')->insert($organizations);
    }
}
