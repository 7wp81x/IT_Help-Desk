<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (optional)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Department::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $departments = [
            [
                'name' => 'Technical Support',
                'slug' => 'technical-support',
                'description' => 'Handles technical issues, software bugs, hardware problems, and system errors.',
                'is_active' => true,
            ],
            [
                'name' => 'Network Operations',
                'slug' => 'network-operations',
                'description' => 'Manages network connectivity, VPN, WiFi, and firewall issues.',
                'is_active' => true,
            ],
            [
                'name' => 'Security Operations',
                'slug' => 'security-operations',
                'description' => 'Handles security incidents, access control, and compliance.',
                'is_active' => true,
            ],
            [
                'name' => 'Database Administration',
                'slug' => 'database-administration',
                'description' => 'Manages databases, performance, and data integrity.',
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Operations',
                'slug' => 'cloud-operations',
                'description' => 'Manages cloud infrastructure and services.',
                'is_active' => true,
            ],
            [
                'name' => 'Email & Collaboration',
                'slug' => 'email-collaboration',
                'description' => 'Handles email systems, collaboration tools, and messaging issues.',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $deptData) {
            Department::updateOrCreate(
                ['slug' => $deptData['slug']],
                $deptData
            );
        }

        $this->command->info('IT Departments seeded successfully!');
        $this->command->info('Total departments: ' . Department::count());
    }
}