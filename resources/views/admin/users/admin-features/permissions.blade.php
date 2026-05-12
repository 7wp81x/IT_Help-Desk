@extends('admin.users.index')
@section('title', 'Admin Permissions')

@section('user-content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Permissions</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Grant or revoke administrator permissions</p>
        </div>
        <div>
            <a href="{{ route('admin.users.admins') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Admins</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Admins List -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Administrators</h3>
                    <p class="text-xs text-gray-500 mt-1">Select an admin to manage permissions</p>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-[600px] overflow-y-auto">
                    @foreach($admins as $admin)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer transition {{ $selectedAdmin && $selectedAdmin->id == $admin->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}"
                         onclick="window.location.href='{{ route('admin.users.admins.permissions', $admin) }}'">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $admin->name }}</p>
                                <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                            </div>
                            @if($admin->is_super_admin ?? false)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Super Admin</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Permissions Form -->
        <div class="lg:col-span-2">
            @php $selectedAdminPermissions = $selectedAdmin ? $selectedAdmin->getPermissionNames()->toArray() : []; @endphp
            @if($selectedAdmin)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Permissions for: {{ $selectedAdmin->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Select which permissions this admin should have</p>
                </div>
                
                <form action="{{ route('admin.users.admins.permissions.update', $selectedAdmin) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- User Management Permissions -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">User Management</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/30 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="view_users" 
                                           {{ in_array('view_users', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">View Users</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="create_users" 
                                           {{ in_array('create_users', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Create Users</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="edit_users" 
                                           {{ in_array('edit_users', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Edit Users</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="delete_users" 
                                           {{ in_array('delete_users', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Delete Users</span>
                                </label>
                            </div>
                        </div>

                        <!-- Ticket Management Permissions -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">Ticket Management</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="view_all_tickets" 
                                           {{ in_array('view_all_tickets', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">View All Tickets</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="assign_tickets" 
                                           {{ in_array('assign_tickets', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Assign Tickets</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="delete_tickets" 
                                           {{ in_array('delete_tickets', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Delete Tickets</span>
                                </label>
                            </div>
                        </div>

                        <!-- System Permissions -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-white mb-3">System Access</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="view_reports" 
                                           {{ in_array('view_reports', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">View Reports</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="system_settings" 
                                           {{ in_array('system_settings', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">System Settings</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="permissions[]" value="view_audit_logs" 
                                           {{ in_array('view_audit_logs', $selectedAdminPermissions) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">View Audit Logs</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                            Save Permissions
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <i class="bi bi-shield-shaded text-5xl text-gray-400 mb-4 block"></i>
                <p class="text-gray-500 dark:text-gray-400">Select an administrator from the list to manage their permissions</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection