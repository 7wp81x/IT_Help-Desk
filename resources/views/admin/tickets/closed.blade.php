@extends('layouts.app')

@section('title', 'Closed Tickets')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tickets.all') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Closed Tickets</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Archived tickets</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Closed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 dark:text-white">#{{ $ticket->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                {{ Str::limit($ticket->subject, 50) }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $ticket->user->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->category->name ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $ticket->assignedAgent->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->closed_at ? $ticket->closed_at->format('M d, Y') : 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No closed tickets found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection