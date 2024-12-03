<?php

namespace Database\Seeders\Sa;

use Illuminate\Database\Seeder;
use App\Models\Sa\Role;
use App\Models\Sa\Permission;
use App\Models\Hr\HrOrganization;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Randomly select an organization
        $organization = HrOrganization::inRandomOrder()->first();

        if (!$organization) {
            $this->command->info('No organization found. Seeder execution aborted.');
            return; // Exit if no organization exists
        }

        // Check for existing roles and create if not found
        $admin = Role::firstOrCreate(['name' => 'admin'], ['org_id' => $organization->org_id, 'created_by' => 1]);
        $editor = Role::firstOrCreate(['name' => 'editor'], ['org_id' => $organization->org_id, 'created_by' => 1]);

        // Permissions array
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'view-post',
        ];

        // Assign permissions
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName], ['org_id' => $organization->org_id, 'created_by' => 1]);

            // Check before attaching the permission
            if (!$admin->permissions()->where('permissions.id', $permission->id)->exists()) {
                $admin->permissions()->attach($permission, ['org_id' => $organization->org_id, 'created_by' => 1]);
            }
        }

        // Attach 'view-post' permission to editor role
        $viewPostPermission = Permission::where('name', 'view-post')->first();
        if ($viewPostPermission && !$editor->permissions()->where('permissions.id', $viewPostPermission->id)->exists()) {
            $editor->permissions()->attach($viewPostPermission, ['org_id' => $organization->org_id, 'created_by' => 1]);
        }
    }
}
