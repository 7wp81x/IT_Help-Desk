@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Department</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new department to the system</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.departments.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department Name *</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('name') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active') ? 'checked' : 'checked' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.departments.index') }}" 
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                    Create Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection