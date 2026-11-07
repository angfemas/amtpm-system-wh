<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Unit Management
            'units.view',
            'units.create',
            'units.edit',
            'units.delete',
            'units.qr.generate',
            
            // Category Management
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            
            // Area Management
            'areas.view',
            'areas.create',
            'areas.edit',
            'areas.delete',
            
            // Maintenance Management
            'maintenance.view',
            'maintenance.create',
            'maintenance.edit',
            'maintenance.delete',
            'maintenance.approve',
            'maintenance.complete',
            
            // Checklist Management
            'checklist.view',
            'checklist.create',
            'checklist.edit',
            'checklist.delete',
            
            // Custom Field Management
            'custom_fields.view',
            'custom_fields.create',
            'custom_fields.edit',
            'custom_fields.delete',
            
            // Red/White Tag Management
            'tags.view',
            'tags.create',
            'tags.edit',
            'tags.delete',
            'tags.resolve',
            
            // Reports
            'reports.view',
            'reports.export',
            
            // Settings
            'settings.view',
            'settings.edit',

            // Homepage Management
            'homepage.view',
            'homepage.carousel.manage',
            'homepage.slider.manage',
            'homepage.flow.manage',
            'homepage.sections.manage',

            // Dashboard
            'dashboard.view',

            // System Administration
            'system.view',
            'system.users.manage',
            'system.roles.manage',
            'system.permissions.manage',
            'system.sections.manage',
            'system.activity.view',
            'system.settings.manage',
            'system.logo.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $leaderRole = Role::firstOrCreate(['name' => 'leader']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // Assign permissions to admin role (full access)
        $adminRole->syncPermissions($permissions);

        // Assign permissions to leader role
        $leaderPermissions = [
            'units.view',
            'maintenance.view',
            'maintenance.approve',
            'maintenance.complete',
            'tags.view',
            'tags.resolve',
            'reports.view',
            'reports.export',
            'dashboard.view',
        ];
        $leaderRole->syncPermissions($leaderPermissions);

        // Assign permissions to operator role
        $operatorPermissions = [
            'units.view',
            'maintenance.create',
            'maintenance.edit',
            'tags.create',
            'dashboard.view',
        ];
        $operatorRole->syncPermissions($operatorPermissions);
    }
}
