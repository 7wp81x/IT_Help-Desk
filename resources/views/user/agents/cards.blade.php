@forelse($agents as $agent)
    <div class="agent-card group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-600 to-red-600 p-6 text-white">
            <div class="flex items-center gap-4">
                @if($agent->avatar)
                    <img src="{{ asset('storage/avatars/' . $agent->avatar) }}" 
                         alt="{{ $agent->name }}"
                         class="w-16 h-16 rounded-full object-cover border-2 border-white/30 shadow-lg">
                @else
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold backdrop-blur-sm">
                        {{ strtoupper(substr($agent->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="text-lg font-bold">{{ $agent->name }}</h4>
                    <p class="text-orange-100 text-sm">{{ $agent->specialization ?? $agent->department ?? 'Support Agent' }}</p>
                    <span class="inline-flex items-center gap-1 mt-1 text-xs text-orange-100">
                        <i class="bi bi-envelope-fill text-xs"></i>
                        {{ $agent->email }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</span>
                    <span class="text-xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($agent->average_rating, 1) }}/5</span>
                </div>
                <div class="flex items-center gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($agent->average_rating))
                            <i class="bi bi-star-fill text-yellow-400 text-sm"></i>
                        @elseif($i <= ceil($agent->average_rating))
                            <i class="bi bi-star-half text-yellow-400 text-sm"></i>
                        @else
                            <i class="bi bi-star text-yellow-400 text-sm"></i>
                        @endif
                    @endfor
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ $agent->total_ratings }} ratings)</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-orange-50 dark:bg-orange-950/30 rounded-xl p-3 text-center">
                    <i class="bi bi-ticket-detailed text-orange-600 dark:text-orange-400 text-lg block mb-1"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Resolved</p>
                    <p class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $agent->total_resolved }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-950/30 rounded-xl p-3 text-center">
                    <i class="bi bi-clock text-green-600 dark:text-green-400 text-lg block mb-1"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Response Time</p>
                    <p class="text-sm font-bold text-green-600 dark:text-green-400">~{{ $agent->avg_response_time ?? 2 }}h</p>
                </div>
            </div>
            
            @if($agent->specialization)
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                <div class="flex flex-wrap gap-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                        <i class="bi bi-tag-fill text-xs mr-1"></i>{{ $agent->specialization }}
                    </span>
                    @if($agent->position)
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                        <i class="bi bi-briefcase-fill text-xs mr-1"></i>{{ $agent->position }}
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="p-4 bg-gray-50 dark:bg-gray-800/50">
            <a href="{{ route('user.agents.show', $agent) }}" class="block w-full text-center px-4 py-2.5 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg transition duration-200 font-medium shadow-sm">
                <i class="bi bi-person-circle mr-2"></i> View Profile
            </a>
        </div>
    </div>
@empty
    <div class="col-span-full">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-person-slash text-3xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Agents Found</h3>
            <p class="text-gray-500 dark:text-gray-400">No support agents match your search criteria.</p>
        </div>
    </div>
@endforelse
