<?php

namespace Database\Seeders\Sa;

use Illuminate\Database\Seeder;
use App\Models\Sa\SaRole;
use App\Models\Sa\SaPermission;
use Database\Seeders\Hr\OrganizationSeeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orgId = OrganizationSeeder::getRandomOrgId();

        // if (!$orgId) {
        //     $this->command->info('No organization found. Seeder execution aborted.');
        //     return; // Exit if no organization exists
        // }

        // Check for existing roles and create if not found
        $admin = SaRole::firstOrCreate(['name' => 'admin'], ['org_id' => $orgId, 'created_by' => 1]);
        $editor = SaRole::firstOrCreate(['name' => 'editor'], ['org_id' => $orgId, 'created_by' => 1]);

        // Permissions array
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'view-post',
        ];

        // Assign permissions
        foreach ($permissions as $permissionName) {
            $permission = SaPermission::firstOrCreate(['name' => $permissionName], ['org_id' => $orgId, 'created_by' => 1]);

            // Check before attaching the permission
            if (!$admin->permissions()->where('sa_permissions.id', $permission->id)->exists()) {
                $admin->permissions()->attach($permission, ['org_id' => $orgId, 'created_by' => 1]);
            }
        }

        // Attach 'view-post' permission to editor role
        $viewPostPermission = SaPermission::where('name', 'view-post')->first();
        if ($viewPostPermission && !$editor->permissions()->where('sa_permissions.id', $viewPostPermission->id)->exists()) {
            $editor->permissions()->attach($viewPostPermission, ['org_id' => $orgId, 'created_by' => 1]);
        }
    }
}
