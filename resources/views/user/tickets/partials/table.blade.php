<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ticket List</h3>
        <a href="{{ route('user.tickets.create') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
            <i class="bi bi-plus-circle"></i> New Ticket
        </a>
    </div>
    <div class="overflow-x-auto">
        <div class="min-w-[900px]">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Ticket #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider min-w-[200px]">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-28">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-24">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-32">Created</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap w-24">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono font-semibold text-gray-900 dark:text-white">#{{ $ticket->ticket_number ?? $ticket->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[200px] mt-0.5">
                                    <i class="bi bi-file-text text-[10px] mr-1"></i>
                                    {{ Str::limit($ticket->description, 60) }}
                                </p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 whitespace-nowrap">
                                <i class="bi bi-tag text-xs"></i>
                                {{ $ticket->category->name ?? 'General' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg 
                                @if($ticket->status == 'open') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @elseif($ticket->status == 'in_progress') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400
                                @elseif($ticket->status == 'resolved') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400
                                @endif">
                                <i class="bi 
                                    @if($ticket->status == 'open') bi-envelope-open text-xs
                                    @elseif($ticket->status == 'in_progress') bi-arrow-repeat text-xs
                                    @elseif($ticket->status == 'resolved') bi-check-circle text-xs
                                    @else bi-archive text-xs
                                    @endif"></i>
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg 
                                @if($ticket->priority == 'critical') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                @elseif($ticket->priority == 'high') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                @else bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @endif">
                                <i class="bi 
                                    @if($ticket->priority == 'critical') bi-exclamation-triangle-fill text-xs
                                    @elseif($ticket->priority == 'high') bi-arrow-up-circle text-xs
                                    @elseif($ticket->priority == 'medium') bi-dash-circle text-xs
                                    @else bi-arrow-down-circle text-xs
                                    @endif"></i>
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $ticket->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $ticket->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <a href="{{ route('user.tickets.show', $ticket) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                   title="View Ticket">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                @if($ticket->status === 'in_progress')
                                    <a href="{{ route('user.tickets.edit', $ticket) }}" 
                                       class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-yellow-100 hover:text-yellow-600 dark:hover:bg-yellow-900/30 dark:hover:text-yellow-400 transition-all duration-200 flex items-center justify-center"
                                       title="Edit Ticket">
                                        <i class="bi bi-pencil text-sm"></i>
                                    </a>
                                    <form action="{{ route('user.tickets.destroy', $ticket) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                                title="Delete Ticket">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </form>
                                @elseif($ticket->status === 'open')
                                    <form action="{{ route('user.tickets.cancel', $ticket) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Canceling this ticket will close it and notify support. Continue?')">
                                        @csrf
                                        <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-orange-100 hover:text-orange-600 dark:hover:bg-orange-900/30 dark:hover:text-orange-400 transition-all duration-200 flex items-center justify-center"
                                                title="Cancel Ticket">
                                            <i class="bi bi-x-circle text-sm"></i>
                                        </button>
                                    </form>
                                @elseif(in_array($ticket->status, ['resolved', 'closed', 'canceled']))
                                    <form action="{{ route('user.tickets.destroy', $ticket) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                                title="Delete Ticket">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="bi bi-ticket text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No tickets found</p>
                                <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition-colors">
                                    <i class="bi bi-plus-circle"></i> Create your first ticket
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
        {{ $tickets->appends(request()->only('search', 'status', 'priority'))->links() }}
    </div>
    @endif
</div>