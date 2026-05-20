@forelse($ratings as $rating)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1">
                    <!-- Agent & Ticket Info -->
                    <div class="flex items-center gap-3 mb-3">
                        @if($rating->agent && $rating->agent->avatar)
                            <img src="{{ asset('storage/avatars/' . $rating->agent->avatar) }}"
                                 alt="{{ $rating->agent->name }}"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-red-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr($rating->agent->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $rating->agent->name ?? 'Unknown Agent' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="bi bi-ticket"></i>
                                Ticket #{{ $rating->ticket->ticket_number ?? $rating->ticket->id ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <!-- Rating Stars and Comment -->
                    <div class="mb-3">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $rating->rating ? '-fill' : '' }} text-yellow-400 text-base"></i>
                                @endfor
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $rating->rating }}/5</span>
                        </div>
                        @if($rating->comment)
                            <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg mt-2">
                                <i class="bi bi-quote text-gray-400 mr-1"></i>
                                {{ $rating->comment }}
                            </p>
                        @endif
                    </div>

                    <!-- Metadata -->
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-clock-history"></i>
                            Rated {{ $rating->created_at->diffForHumans() }}
                        </span>
                        @if($rating->updated_at != $rating->created_at)
                            <span class="flex items-center gap-1">
                                <i class="bi bi-pencil"></i>
                                Edited {{ $rating->updated_at->diffForHumans() }}
                            </span>
                        @endif
                        @if($rating->created_at->diffInDays(now()) <= 7)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-xs">
                                <i class="bi bi-pencil-square"></i>
                                Editable ({{ 7 - $rating->created_at->diffInDays(now()) }} days left)
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    @if($rating->created_at->diffInDays(now()) <= 7)
                        <a href="{{ route('user.ratings.edit', $rating) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition duration-200 text-sm font-medium">
                            <i class="bi bi-pencil"></i>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('user.ratings.destroy', $rating) }}"
                              onsubmit="return confirm('Are you sure you want to delete this rating?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition duration-200 text-sm font-medium">
                                <i class="bi bi-trash"></i>
                                Delete
                            </button>
                        </form>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-xs">
                            <i class="bi bi-lock"></i>
                            Cannot edit (7 days passed)
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-star text-3xl text-gray-400 dark:text-gray-500"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Ratings Yet</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't submitted any ratings yet.</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mb-6">You can rate agents after your tickets are resolved.</p>
        <a href="{{ route('user.tickets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white rounded-lg transition duration-200 font-medium">
            <i class="bi bi-ticket"></i>
            View My Tickets
        </a>
    </div>
@endforelse
