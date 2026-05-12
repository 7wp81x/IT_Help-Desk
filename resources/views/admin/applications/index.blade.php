@extends('layouts.app')

@section('title', 'Agent Applications')

@section('content')
<div class="space-y-6" style="margin-left: 0 !important;">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Agent Applications</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Search, filter, and review incoming agent applications.</p>
        </div>
        <form method="GET" action="{{ route('admin.applications') }}" class="grid w-full max-w-2xl gap-3 sm:grid-cols-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by name or email" class="rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
            <select name="status" class="rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                <option value="">All statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition duration-150 hover:bg-blue-700">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Applicant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Certifications</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Submitted</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($applications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $application->full_name }}</div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $application->phone ?? 'No phone provided' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $application->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $application->certifications_list ?: 'None' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badge = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200';
                                    if ($application->status === 'approved') {
                                        $badge = 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200';
                                    } elseif ($application->status === 'rejected') {
                                        $badge = 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200';
                                    }
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">{{ ucfirst($application->status) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-2 rounded-full border border-blue-600 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 transition duration-150 hover:bg-blue-100 dark:border-blue-500 dark:bg-blue-900/40 dark:text-blue-200 dark:hover:bg-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($application->status === 'pending')
                                        <a href="{{ route('admin.applications.show', $application) }}#approve" class="inline-flex items-center gap-2 rounded-full border border-green-600 bg-green-50 px-3 py-2 text-sm font-semibold text-green-700 transition duration-150 hover:bg-green-100 dark:border-green-500 dark:bg-green-900/40 dark:text-green-200 dark:hover:bg-green-900">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="{{ route('admin.applications.show', $application) }}#reject" class="inline-flex items-center gap-2 rounded-full border border-red-600 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 transition duration-150 hover:bg-red-100 dark:border-red-500 dark:bg-red-900/40 dark:text-red-200 dark:hover:bg-red-900">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-700 transition duration-150 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200 dark:hover:bg-gray-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @elseif($application->status === 'approved' && $application->user)
                                        <a href="{{ route('admin.users.show', $application->user) }}" class="inline-flex items-center gap-2 rounded-full border border-indigo-600 bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-700 transition duration-150 hover:bg-indigo-100 dark:border-indigo-500 dark:bg-indigo-900/40 dark:text-indigo-200 dark:hover:bg-indigo-900">
                                            <i class="fas fa-user-tie"></i>
                                        </a>
                                    @else
                                        <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-700 transition duration-150 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-900/40 dark:text-gray-200 dark:hover:bg-gray-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No applications found. Try adjusting your filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $applications->links() }}
    </div>
</div>
@endsection