<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup {nrp? : NRP of the user to setup as admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup admin role and permissions for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nrp = $this->argument('nrp') ?? 'ADM001';

        $this->info("Setting up admin access for NRP: {$nrp}");

        // Find user
        $user = User::where('nrp', $nrp)->first();

        if (!$user) {
            $this->error("User with NRP {$nrp} not found!");
            return 1;
        }

        $this->info("Found user: {$user->name}");

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->info("Admin role: " . ($adminRole->wasRecentlyCreated ? 'Created' : 'Already exists'));

        // Get all permissions
        $allPermissions = Permission::all();
        $this->info("Total permissions in database: {$allPermissions->count()}");

        // Sync all permissions to admin role
        $adminRole->syncPermissions($allPermissions);
        $this->info("Synced all permissions to admin role");

        // Assign admin role to user
        $user->assignRole('admin');
        $this->info("Assigned admin role to user");

        // Verify
        $this->newLine();
        $this->info("Verification:");
        $this->table(
            ['Check', 'Result'],
            [
                ['Has admin role', $user->hasRole('admin') ? '✓ Yes' : '✗ No'],
                ['Has system.view permission', $user->hasPermissionTo('system.view') ? '✓ Yes' : '✗ No'],
                ['Total permissions', $user->getAllPermissions()->count()],
            ]
        );

        $this->newLine();
        $this->info("✓ Admin access setup complete!");
        $this->warn("Please clear cache and logout/login to see changes:");
        $this->warn("  php artisan cache:clear");
        $this->warn("  php artisan config:clear");
        $this->warn("  php artisan view:clear");

        return 0;
    }
}
