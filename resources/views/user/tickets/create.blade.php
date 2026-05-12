@extends('layouts.app')

@section('title', 'Create New Ticket')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Ticket</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Create a new support ticket</p>
        </div>
        
        <div>
            <a href="{{ route('user.tickets.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Tickets</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter ticket title">
                    @error('title') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                    <textarea name="description" 
                              rows="6" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe the issue in detail...">{{ old('description') }}</textarea>
                    @error('description') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                        <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority *</label>
                        <select name="priority" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority') 
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Attachment -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachments</label>
                    <input type="file" 
                           name="attachments[]" 
                           multiple
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can select multiple files. Max size: 5MB per file.</p>
                    @error('attachments.*') 
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('user.tickets.index') }}" 
                   class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                    Create Ticket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection