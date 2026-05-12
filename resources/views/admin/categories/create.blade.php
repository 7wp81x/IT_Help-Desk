@extends('layouts.app')

@section('title', 'Create Category')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Category</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new category to organize support tickets.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category Name *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Icon (Bootstrap Icon classes, e.g. <code>bi bi-tags</code>)</label>
                        <input type="text" name="icon" value="{{ old('icon', 'bi bi-tags') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Color</label>
                        <input type="color" name="color" value="{{ old('color', '#3B82F6') }}" class="w-full h-12 p-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority Level</label>
                    <select name="priority_level" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('priority_level', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('priority_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">Create Category</button>
            </div>
        </form>
    </div>
</div>
@endsection
