<?php

require_once __DIR__ . '/bootstrap/app.php';

$app = app();

// Load the User model
$users = $app->make(\App\Models\User::class);

// Check for admin users
$adminCount = $users::where('role', 'admin')->count();
echo "Admin users found: " . $adminCount . "\n";

if ($adminCount > 0) {
    $admin = $users::where('role', 'admin')->first();
    echo "First admin: " . $admin->name . " (" . $admin->email . ")\n";
} else {
    echo "No admin users found. You may need to create one.\n";
}

// Check for regular users
$userCount = $users::where('role', 'user')->count();
echo "Regular users found: " . $userCount . "\n";
