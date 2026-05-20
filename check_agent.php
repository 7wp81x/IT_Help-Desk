<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Get the latest agent
$agent = User::where('role', 'agent')->latest()->first();

if ($agent) {
    echo "=== Latest Agent Record ===\n";
    echo "ID: " . $agent->id . "\n";
    echo "Name: " . $agent->name . "\n";
    echo "Email: " . $agent->email . "\n";
    echo "Employee ID: " . $agent->employee_id . "\n";
    echo "Password Hash: " . substr($agent->password, 0, 60) . "...\n";
    echo "Email Verified At: " . ($agent->email_verified_at ? 'YES' : 'NO') . "\n";
    echo "Status: " . $agent->status . "\n";
    echo "Role: " . $agent->role . "\n";
    echo "Created At: " . $agent->created_at . "\n";
    echo "Updated At: " . $agent->updated_at . "\n";
    
    // Check application record
    $app = User::find($agent->id)->agentApplication;
    if ($app) {
        echo "\n=== Linked Application ===\n";
        echo "Generated Employee ID: " . $app->generated_employee_id . "\n";
        echo "Generated Password: " . ($app->generated_password ? substr($app->generated_password, 0, 6) . '*****' : 'NOT SET') . "\n";
    }
    
    // Test password verification
    echo "\n=== Password Verification Test ===\n";
    if ($app && $app->generated_password) {
        $testPassword = $app->generated_password;
        $isValid = Hash::check($testPassword, $agent->password);
        echo "Test with generated password: " . ($isValid ? 'PASS ✓' : 'FAIL ✗') . "\n";
        echo "Plain password: " . $testPassword . "\n";
        echo "Hash matches: " . ($isValid ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "No agents found in database\n";
}
