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
            
            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $leaderRole = Role::firstOrCreate(['name' => 'leader']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);

        // Assign permissions to admin role (full access)
        $adminRole->givePermissionTo($permissions);

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
        $leaderRole->givePermissionTo($leaderPermissions);

        // Assign permissions to operator role
        $operatorPermissions = [
            'units.view',
            'maintenance.create',
            'maintenance.edit',
            'tags.create',
            'dashboard.view',
        ];
        $operatorRole->givePermissionTo($operatorPermissions);

        // Create default admin user
        $admin = \App\Models\User::firstOrCreate([
            'nrp' => 'admin001',
        ], [
            'name' => 'Administrator',
            'email' => 'admin@warehouse-amtpm.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($adminRole);

        // Create default leader user
        $leader = \App\Models\User::firstOrCreate([
            'nrp' => 'leader001',
        ], [
            'name' => 'Team Leader',
            'email' => 'leader@warehouse-amtpm.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $leader->assignRole($leaderRole);

        // Create default operator user
        $operator = \App\Models\User::firstOrCreate([
            'nrp' => 'operator001',
        ], [
            'name' => 'Operator',
            'email' => 'operator@warehouse-amtpm.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $operator->assignRole($operatorRole);
    }
}
