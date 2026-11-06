<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'admin@warehouse-amtpm.com';
$password = 'password123';
$nrp = 'ADMIN001';

// Check if user exists
$user = User::where('email', $email)->first();

if ($user) {
    echo "User exists: " . $user->name . "\n";
    $user->password = bcrypt($password);
    $user->nrp = $nrp;
    $user->email_verified_at = now();
    $user->save();
    echo "Password updated successfully!\n";
} else {
    $user = User::create([
        'name' => 'Admin AMTPM',
        'email' => $email,
        'nrp' => $nrp,
        'password' => bcrypt($password),
        'email_verified_at' => now(),
    ]);
    echo "User created successfully!\n";
}

echo "\nLogin credentials:\n";
echo "Email: " . $email . "\n";
echo "Password: " . $password . "\n";
echo "NRP: " . $nrp . "\n";
