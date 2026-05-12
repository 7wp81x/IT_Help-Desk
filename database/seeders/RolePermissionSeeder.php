<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions - USE firstOrCreate() to avoid duplicates
        $permissions = [
            'view tickets',
            'create tickets',
            'edit tickets',
            'delete tickets',
            'assign tickets',
            'close tickets',
            'manage users',
            'manage roles',
            'view reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        $agentRole = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        $agentRole->syncPermissions([
            'view tickets',
            'create tickets',
            'edit tickets',
            'assign tickets',
            'close tickets'
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view tickets',
            'create tickets'
        ]);
    }
}