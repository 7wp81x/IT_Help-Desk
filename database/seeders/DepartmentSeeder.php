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
                'name' => 'Information Technology',
                'description' => 'Responsible for IT infrastructure, software development, and technical support',
                'is_active' => true,
            ],
            [
                'name' => 'Human Resources',
                'description' => 'Manages employee relations, recruitment, benefits, and company culture',
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'description' => 'Handles financial planning, accounting, budgeting, and reporting',
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => 'Responsible for brand management, digital marketing, and customer acquisition',
                'is_active' => true,
            ],
            [
                'name' => 'Sales',
                'description' => 'Drives revenue through lead generation and customer relationships',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Support',
                'description' => 'Provides assistance and resolution to customer inquiries and issues',
                'is_active' => true,
            ],
            [
                'name' => 'Operations',
                'description' => 'Oversees daily business operations and process optimization',
                'is_active' => true,
            ],
            [
                'name' => 'Research & Development',
                'description' => 'Innovates and develops new products, features, and technologies',
                'is_active' => true,
            ],
            [
                'name' => 'Legal',
                'description' => 'Handles legal compliance, contracts, and regulatory matters',
                'is_active' => true,
            ],
            [
                'name' => 'Administration',
                'description' => 'Manages office operations, facilities, and administrative tasks',
                'is_active' => true,
            ],
            [
                'name' => 'Product Management',
                'description' => 'Defines product strategy, roadmap, and feature development',
                'is_active' => true,
            ],
            [
                'name' => 'Quality Assurance',
                'description' => 'Ensures product quality through testing and quality control processes',
                'is_active' => true,
            ],
            [
                'name' => 'Security',
                'description' => 'Manages information security, compliance, and risk assessment',
                'is_active' => true,
            ],
            [
                'name' => 'Data Analytics',
                'description' => 'Analyzes data to provide insights and support decision making',
                'is_active' => true,
            ],
            [
                'name' => 'Procurement',
                'description' => 'Manages vendor relationships and purchasing of goods and services',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departments seeded successfully!');
        $this->command->info('Total departments: ' . Department::count());
    }
}