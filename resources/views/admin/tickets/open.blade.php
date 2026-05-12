@extends('layouts.app')

@section('title', 'Open Tickets')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tickets.all') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Open Tickets</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tickets waiting for response</p>
            </div>
        </div>
    </div>

    <!-- Similar table structure as closed.blade.php but for open tickets -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Table content here -->
    </div>
</div>
@endsection