@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Ticket</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update your ticket details</p>
        </div>

        <div>
            <a href="{{ route('user.tickets.show', $ticket) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Ticket</span>
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('user.tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $ticket->subject) }}"
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
                              placeholder="Describe the issue in detail...">{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Classification</label>
                        <div class="px-4 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm text-gray-700 dark:text-gray-200">
                            {{ $ticket->category->name ?? 'Pending classification by support team' }}
                        </div>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority <span class="text-gray-400">(optional)</span></label>
                        <select name="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="" {{ old('priority', $ticket->priority) === null ? 'selected' : '' }}>Keep current priority</option>
                            <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Attachments -->
                @if($ticket->attachments->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Attachments</label>
                    <div class="space-y-2">
                        @foreach($ticket->attachments as $attachment)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <i class="bi bi-file-earmark text-gray-500"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $attachment->original_name }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($attachment->size / 1024, 1) }} KB</p>
                                </div>
                                <a href="{{ route('user.tickets.download', $attachment) }}"
                                   class="text-blue-600 hover:text-blue-700 text-sm">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- New Attachments -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Add New Attachments</label>
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
            <div class="flex justify-between gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <form action="{{ route('user.tickets.destroy', $ticket) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm transition">
                        <i class="bi bi-trash mr-1"></i> Delete Ticket
                    </button>
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('user.tickets.show', $ticket) }}"
                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition">
                        <i class="bi bi-check-circle mr-1"></i> Update Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection