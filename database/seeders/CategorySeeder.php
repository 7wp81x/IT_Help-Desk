<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware',
                'slug' => 'hardware',
                'description' => 'Issues related to computer hardware, printers, monitors, and physical equipment.',
                'icon' => 'bi-pc-display',
                'color' => '#EF4444',
                'priority_level' => 1, // 1 = High
                'is_active' => true,
            ],
            [
                'name' => 'Software',
                'slug' => 'software',
                'description' => 'Problems with operating systems, applications, and software installations.',
                'icon' => 'bi-window',
                'color' => '#3B82F6',
                'priority_level' => 1, // 1 = High
                'is_active' => true,
            ],
            [
                'name' => 'Network',
                'slug' => 'network',
                'description' => 'Connectivity issues, Wi-Fi problems, and network access.',
                'icon' => 'bi-wifi',
                'color' => '#10B981',
                'priority_level' => 1, // 1 = High
                'is_active' => true,
            ],
            [
                'name' => 'Email',
                'slug' => 'email',
                'description' => 'Email configuration, sending/receiving issues, and mailbox problems.',
                'icon' => 'bi-envelope',
                'color' => '#F59E0B',
                'priority_level' => 2, // 2 = Medium
                'is_active' => true,
            ],
            [
                'name' => 'Database',
                'slug' => 'database',
                'description' => 'Database connection, query performance, and data integrity issues.',
                'icon' => 'bi-database',
                'color' => '#8B5CF6',
                'priority_level' => 2, // 2 = Medium
                'is_active' => true,
            ],
            [
                'name' => 'Security',
                'slug' => 'security',
                'description' => 'Access control, permissions, and security-related concerns.',
                'icon' => 'bi-shield-lock',
                'color' => '#EC4899',
                'priority_level' => 1, // 1 = High
                'is_active' => true,
            ],
            [
                'name' => 'Printer',
                'slug' => 'printer',
                'description' => 'Printer not working, paper jam, and printing issues.',
                'icon' => 'bi-printer',
                'color' => '#6B7280',
                'priority_level' => 3, // 3 = Low
                'is_active' => true,
            ],
            [
                'name' => 'VPN Access',
                'slug' => 'vpn-access',
                'description' => 'VPN connection, remote access, and authentication issues.',
                'icon' => 'bi-shield-shaded',
                'color' => '#14B8A6',
                'priority_level' => 2, // 2 = Medium
                'is_active' => true,
            ],
            [
                'name' => 'Backup & Recovery',
                'slug' => 'backup-recovery',
                'description' => 'Data backup, restoration, and disaster recovery.',
                'icon' => 'bi-archive',
                'color' => '#F97316',
                'priority_level' => 3, // 3 = Low
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Device',
                'slug' => 'mobile-device',
                'description' => 'Smartphone, tablet, and mobile application issues.',
                'icon' => 'bi-phone',
                'color' => '#06B6D4',
                'priority_level' => 3, // 3 = Low
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}