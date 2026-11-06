<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin user with strong password
        $admin = \App\Models\User::updateOrCreate(
            ['nrp' => 'ADM001'],
            [
                'name' => 'Administrator',
                'email' => 'admin@warehouse-amtpm.com',
                'password' => Hash::make('Adm!n@Warehouse2024'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create Leader user with strong password
        $leader = \App\Models\User::updateOrCreate(
            ['nrp' => 'LDR001'],
            [
                'name' => 'Team Leader',
                'email' => 'leader@warehouse-amtpm.com',
                'password' => Hash::make('L3ad3r@Team2024'),
                'email_verified_at' => now(),
            ]
        );
        $leader->assignRole('leader');

        // Create Operator user with strong password
        $operator = \App\Models\User::updateOrCreate(
            ['nrp' => 'OPR001'],
            [
                'name' => 'Warehouse Operator',
                'email' => 'operator@warehouse-amtpm.com',
                'password' => Hash::make('Op3rat0r@Work2024'),
                'email_verified_at' => now(),
            ]
        );
        $operator->assignRole('operator');

        // Create Test user with Resend email for email testing
        $testUser = \App\Models\User::updateOrCreate(
            ['nrp' => 'TEST001'],
            [
                'name' => 'Test User',
                'email' => 'malikverseid@gmail.com',
                'password' => Hash::make('Test@User2024'),
                'email_verified_at' => now(),
            ]
        );
        $testUser->assignRole('operator');
    }
}
