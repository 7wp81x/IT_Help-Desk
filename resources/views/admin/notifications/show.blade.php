@extends('layouts.app')

@section('title', data_get($data, 'title', 'Notification'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ data_get($data, 'title', 'Notification') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ data_get($data, 'timestamp', $notification->created_at->toDateTimeString()) }}</p>
        </div>
        <a href="{{ route('admin.notifications') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <i class="bi bi-arrow-left"></i>
            Back to notifications
        </a>
    </div>

    @if(!empty($warning))
        <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 dark:border-yellow-700 dark:bg-yellow-950/10 p-4 text-sm text-yellow-800 dark:text-yellow-200">
            {{ $warning }}
        </div>
    @endif

    <div class="rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-slate-950 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="mb-6">
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ data_get($data, 'message', 'No notification details available.') }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @if(data_get($data, 'ticket_number'))
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Ticket Number</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ data_get($data, 'ticket_number') }}</p>
                    </div>
                @endif

                @if(data_get($data, 'ticket_subject'))
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Ticket Subject</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ data_get($data, 'ticket_subject') }}</p>
                    </div>
                @endif

                @if(data_get($data, 'application_id'))
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Application ID</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ data_get($data, 'application_id') }}</p>
                    </div>
                @endif

                @if(data_get($data, 'applicant_name'))
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Applicant Name</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ data_get($data, 'applicant_name') }}</p>
                    </div>
                @endif

                @if(data_get($data, 'deleted_by.name'))
                    <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 p-4">
                        <p class="text-xs uppercase tracking-[0.24em] text-gray-500 dark:text-gray-400">Deleted By</p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ data_get($data, 'deleted_by.name') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection