<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Regular User (Customer submitting tickets)
          User::firstOrCreate(
        ['email' => 'admin@helpdesk.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('Password@123'),
            'phone' => '+1 (555) 000-0000',
            'role' => 'admin',
            'status' => 'active',
            'department' => 'IT Department',
            'position' => 'System Administrator',
            'email_verified_at' => now(), // Mark admin email as verified
        ]
    );

      


        User::create([
            'name' => 'Mike Wilson',
            'email' => 'mike@helpdesk.com',
            'password' => Hash::make('Password@123'),
            'phone' => '+1 (555) 456-7890',
            'role' => 'agent',
            'status' => 'active',
            'employee_id' => 'AGT-002',
            'department' => 'Software Support',
            'position' => 'Support Specialist',
            'specialization' => 'Software & Applications',
            'tickets_handled' => 0,
            'rating' => 0,
            'skills' => json_encode(['Software Troubleshooting', 'Database', 'API Support']),
            'email_verified_at' => now(), // Mark agent email as verified
        ]);

    }
}