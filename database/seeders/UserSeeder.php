<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ============ ADMIN ============
        $adminDepartmentId = Department::where('name', 'IT Department')->value('id');
        $userDepartmentId = Department::where('name', 'Sales Department')->value('id');

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('Password@123'),
                'phone' => '09101966700',
                'role' => 'admin',
                'status' => 'active',
                'department_id' => $adminDepartmentId,
                'position' => 'System Administrator',
                'email_verified_at' => now(),
            ]
        );

        // ============ REGULAR USER ============
        User::firstOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'User One',
                'password' => Hash::make('Password@123'),
                'phone' => '09181234567',
                'role' => 'user',
                'status' => 'active',
                'department_id' => $userDepartmentId,
                'position' => 'Sales Representative',
                'email_verified_at' => now(),
            ]
        );

        // ============ AGENTS ONLY ============
        $departments = Department::all();

        foreach ($departments as $department) {

            $slug = Str::slug($department->name, '-');

            for ($i = 1; $i <= 2; $i++) {

                $schedule = $i === 1
                    ? ['shift_start' => '08:00', 'shift_end' => '20:00']
                    : ['shift_start' => '20:00', 'shift_end' => '08:00'];

                User::firstOrCreate(
                    [
                        'email' => "agent-{$slug}-{$i}@example.com"
                    ],
                    [
                        'name' => $department->name . ' Agent ' . $i,

                        // ✅ FIXED employee_id
                        'employee_id' => $this->generateEmployeeId($department->name),

                        'password' => Hash::make('Password@123'),
                        'phone' => '0918' . rand(1000000, 9999999),

                        'role' => 'agent',
                        'status' => 'active',

                        'department_id' => $department->id,
                        'position' => 'Support Agent',

                        // IMPORTANT (based on your blade fields)
                        'specialization' => null,
                        'skills' => null,

                        'schedule' => json_encode($schedule),
                        'day_off' => false,

                        'email_verified_at' => now(),
                    ]
                );
            }
        }

        // ============ SUMMARY ============
        $this->command->info('Users seeded successfully!');
        $this->command->info('Total Admins: ' . User::where('role', 'admin')->count());
        $this->command->info('Total Agents: ' . User::where('role', 'agent')->count());
        $this->command->info('Total Users: ' . User::where('role', 'user')->count());
        $this->command->info('Total All: ' . User::count());

        $this->command->info("\n=== AGENT DISTRIBUTION BY DEPARTMENT ===");

        foreach (Department::all() as $department) {
            $count = User::where('role', 'agent')
                ->where('department_id', $department->id)
                ->count();

            $this->command->info($department->name . ': ' . $count . ' agent(s)');
        }
    }

    // ✅ FIXED: function must be OUTSIDE run()
    private function generateEmployeeId($departmentName)
    {
        $code = strtoupper(substr(
            preg_replace('/[^A-Za-z]/', '', $departmentName),
            0,
            3
        ));

        return 'AGT-' . $code . '-' . rand(1000, 9999);
    }
}