<?php

namespace Database\Seeders\Sa;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sa\Role;
use App\Models\Sa\Permission;

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
        // Check for existing roles and create if not found
        $admin = Role::firstOrCreate(['name' => 'admin'], ['created_by' => 1]);
        $editor = Role::firstOrCreate(['name' => 'editor'], ['created_by' => 1]);

        // Permissions array
        $permissions = [
            'create-post',
            'edit-post',
            'delete-post',
            'view-post',
        ];

        // Assign permissions
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName], ['created_by' => 1]);
        
            // Check before attaching the permission
            if (!$admin->permissions()->where('permissions.id', $permission->id)->exists()) {
                $admin->permissions()->attach($permission, ['created_by' => 1]);
            }
        }
        
        // Attach 'view-post' permission to editor role
        $viewPostPermission = Permission::where('name', 'view-post')->first();
        if ($viewPostPermission && !$editor->permissions()->where('permissions.id', $viewPostPermission->id)->exists()) {
            $editor->permissions()->attach($viewPostPermission, ['created_by' => 1]);
        }
        
    }
}
