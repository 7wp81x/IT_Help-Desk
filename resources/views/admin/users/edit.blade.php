@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update user information</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Password (optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password (leave blank to keep current)</label>
                    <input type="password" name="password"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
                    <select name="role" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>End User</option>
                        <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Support Agent</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Employee ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('employee_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                    <select name="department" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->name }}" {{ old('department', $user->department) == $department->name ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Position -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position/Title</label>
                    <input type="text" name="position" value="{{ old('position', $user->position) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Specialization -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Specialization</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $user->specialization) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('specialization') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Skills -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Skills</label>
                    <textarea name="skills" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('skills', $user->skills) }}</textarea>
                    @error('skills') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection