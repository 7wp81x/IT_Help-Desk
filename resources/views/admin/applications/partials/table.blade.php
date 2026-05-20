<div class="min-w-[800px]">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Applications List</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Info</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Certifications</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($applications as $application)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($application->full_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]" title="{{ $application->full_name }}">
                                        {{ $application->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        ID: #{{ $application->id }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <i class="bi bi-envelope text-xs text-gray-400"></i>
                                    <span class="truncate max-w-[180px]" title="{{ $application->email }}">{{ $application->email }}</span>
                                </div>
                                @if($application->phone)
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="bi bi-telephone text-xs text-gray-400"></i>
                                    <span class="text-xs">{{ $application->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $certs = $application->certifications_list ? explode(',', $application->certifications_list) : [];
                            @endphp
                            @if(count($certs) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($certs, 0, 2) as $cert)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ trim($cert) }}
                                        </span>
                                    @endforeach
                                    @if(count($certs) > 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            +{{ count($certs) - 2 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                $badgeIcon = 'bi-clock-history';
                                if ($application->status === 'approved') {
                                    $badgeClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
                                    $badgeIcon = 'bi-check-circle-fill';
                                } elseif ($application->status === 'rejected') {
                                    $badgeClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
                                    $badgeIcon = 'bi-x-circle-fill';
                                }
                                
                                $isOrphaned = $application->status === 'approved' && !$application->user;
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="status-badge inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg {{ $badgeClass }}">
                                    <i class="bi {{ $badgeIcon }} text-xs"></i>
                                    {{ ucfirst($application->status) }}
                                </span>
                                @if($isOrphaned)
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400" title="No agent account found">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    Orphaned
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $application->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $application->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Only View Button and Delete Button (no approve/reject) -->
                                <a href="{{ route('admin.applications.show', $application) }}" 
                                   class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
                                   title="View Details">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>

                                <!-- Delete button only for rejected or non-approved -->
                                @if($application->status !== 'approved')
                                    <button type="button"
                                            data-delete-url="{{ route('admin.applications.destroy', $application) }}"
                                            data-delete-title="{{ $application->full_name }}"
                                            class="open-delete-modal-btn w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                                            title="Delete Application">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                @elseif($application->status === 'approved' && $application->user)
                                    <a href="{{ route('admin.users.show', $application->user) }}" 
                                       class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-purple-100 hover:text-purple-600 dark:hover:bg-purple-900/30 dark:hover:text-purple-400 transition-all duration-200 flex items-center justify-center"
                                       title="View Agent Profile">
                                        <i class="bi bi-person-badge text-sm"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <i class="bi bi-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">No applications found</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($applications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>