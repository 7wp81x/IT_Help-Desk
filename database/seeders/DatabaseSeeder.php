<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            CategorySeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
            AnnouncementSeeder::class,
        ]);

        $exampleUser = User::firstOrCreate(
            ['email' => 'example.user@example.com'],
            [
                'name' => 'Example User',
                'password' => Hash::make('Example@123'),
                'phone' => '09990000000',
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $category = Category::first();
        $department = Department::first();

        if ($category && $department) {
            Ticket::firstOrCreate(
                ['ticket_number' => 'EXAMPLE-001'],
                [
                    'user_id' => $exampleUser->id,
                    'category_id' => $category->id,
                    'department_id' => $department->id,
                    'subject' => 'Example ticket: Cannot access company VPN',
                    'description' => 'I am unable to connect to the company VPN from my home network. Please assist with troubleshooting.',
                    'status' => 'open',
                    'priority' => 'high',
                ]
            );
        }
    }
}