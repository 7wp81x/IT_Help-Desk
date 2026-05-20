<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Department;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware Issue',
                'slug' => 'hardware-issue',
                'department_slug' => 'technical-support',
                'description' => 'Issues related to physical hardware, workstation failures, and peripheral problems.',
                'icon' => 'bi-pc-display',
                'color' => '#EF4444',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Software Installation',
                'slug' => 'software-installation',
                'department_slug' => 'technical-support',
                'description' => 'New software installs, application setup, and configuration requests.',
                'icon' => 'bi-window',
                'color' => '#3B82F6',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Printer Problem',
                'slug' => 'printer-problem',
                'department_slug' => 'technical-support',
                'description' => 'Printer failures, paper jams, and print queue issues.',
                'icon' => 'bi-printer',
                'color' => '#6B7280',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'VPN Access',
                'slug' => 'vpn-access',
                'department_slug' => 'network-operations',
                'description' => 'VPN connection, remote access, and secure network authentication issues.',
                'icon' => 'bi-shield-shaded',
                'color' => '#14B8A6',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'WiFi Connectivity',
                'slug' => 'wifi-connectivity',
                'department_slug' => 'network-operations',
                'description' => 'Wireless network access problems, signal issues, and roaming failures.',
                'icon' => 'bi-wifi',
                'color' => '#10B981',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Firewall Issue',
                'slug' => 'firewall-issue',
                'department_slug' => 'network-operations',
                'description' => 'Firewall access, rule changes, blocked traffic, and security policies.',
                'icon' => 'bi-shield-lock',
                'color' => '#F97316',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Access Management',
                'slug' => 'access-management',
                'department_slug' => 'security-operations',
                'description' => 'User access requests, permission changes, and identity management.',
                'icon' => 'bi-person-check',
                'color' => '#8B5CF6',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'department_slug' => 'security-operations',
                'description' => 'Password resets, account lockouts, and security credential support.',
                'icon' => 'bi-key',
                'color' => '#EC4899',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Backup & Recovery',
                'slug' => 'backup-recovery',
                'department_slug' => 'database-administration',
                'description' => 'Data backup, restoration, and disaster recovery support.',
                'icon' => 'bi-archive',
                'color' => '#F97316',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Query Performance',
                'slug' => 'query-performance',
                'department_slug' => 'database-administration',
                'description' => 'Database performance tuning, query optimization, and slow reports.',
                'icon' => 'bi-speedometer2',
                'color' => '#0EA5E9',
                'priority_level' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Provisioning',
                'slug' => 'cloud-provisioning',
                'department_slug' => 'cloud-operations',
                'description' => 'Provisioning cloud resources, services, and access requests.',
                'icon' => 'bi-cloud-plus',
                'color' => '#0EA5E9',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Cloud Backup',
                'slug' => 'cloud-backup',
                'department_slug' => 'cloud-operations',
                'description' => 'Cloud backup, data retention, and restore workflows.',
                'icon' => 'bi-cloud-upload',
                'color' => '#22C55E',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Email Delivery',
                'slug' => 'email-delivery',
                'department_slug' => 'email-collaboration',
                'description' => 'Email sending, receiving, and delivery failures.',
                'icon' => 'bi-envelope',
                'color' => '#F59E0B',
                'priority_level' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Calendar Sync',
                'slug' => 'calendar-sync',
                'department_slug' => 'email-collaboration',
                'description' => 'Calendar sharing, sync issues, and collaboration access.',
                'icon' => 'bi-calendar-event',
                'color' => '#6366F1',
                'priority_level' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            $department = Department::where('slug', $category['department_slug'])->first();
            if (! $department) {
                throw new \RuntimeException('Department not found for category: ' . $category['name']);
            }

            $categoryData = [
                'name' => $category['name'],
                'description' => $category['description'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'priority_level' => $category['priority_level'],
                'is_active' => $category['is_active'],
                'department_id' => $department->id,
            ];

            Category::updateOrCreate(
                ['slug' => $category['slug']],
                array_merge($categoryData, ['slug' => $category['slug']])
            );
        }
    }
}
