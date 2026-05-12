@extends('layouts.app')

@section('title', 'Resolved Tickets')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tickets.all') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Resolved Tickets</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tickets that have been resolved</p>
            </div>
        </div>
    </div>
    <!-- Table content -->
</div>
@endsection