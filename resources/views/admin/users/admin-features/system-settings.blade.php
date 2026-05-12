@extends('admin.users.index')
@section('title', 'System Settings')

@section('user-content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">System Settings</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure system-wide settings and preferences</p>
        </div>
        <div>
            <a href="{{ route('admin.users.admins') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <!-- General Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">General Settings</h3>
                <p class="text-sm text-gray-500">Basic system configuration</p>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">System Name</label>
                        <input type="text" name="system_name" value="{{ $settings['system_name'] ?? 'IT Helpdesk' }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">System Email</label>
                        <input type="email" name="system_email" value="{{ $settings['system_email'] ?? 'support@helpdesk.com' }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time Zone</label>
                        <select name="timezone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            <option value="UTC" {{ ($settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                            <option value="America/Chicago" {{ ($settings['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                            <option value="America/Denver" {{ ($settings['timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                            <option value="America/Los_Angeles" {{ ($settings['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                            <option value="Asia/Manila" {{ ($settings['timezone'] ?? '') == 'Asia/Manila' ? 'selected' : '' }}>Philippine Time</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                   {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Maintenance Mode</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">When enabled, only admins can access the system</p>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Settings</button>
                </div>
            </form>
        </div>

        <!-- Ticket Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket Settings</h3>
                <p class="text-sm text-gray-500">Configure ticket system behavior</p>
            </div>
            <form action="{{ route('admin.settings.tickets.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Auto-close after (days)</label>
                        <input type="number" name="auto_close_days" value="{{ $settings['auto_close_days'] ?? 7 }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max tickets per user</label>
                        <input type="number" name="max_tickets_per_user" value="{{ $settings['max_tickets_per_user'] ?? 0 }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">0 = unlimited</p>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="require_rating" value="1" 
                                   {{ ($settings['require_rating'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Require rating on ticket closure</span>
                        </label>
                    </div>
                    
                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="send_notifications" value="1" 
                                   {{ ($settings['send_notifications'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Send email notifications</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection