@forelse($agents as $agent)
<tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white text-base font-bold shadow-sm flex-shrink-0">
                {{ strtoupper(substr($agent->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $agent->name }}</p>
                <p class="text-xs text-gray-500">{{ $agent->position ?? 'Support Agent' }}</p>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $agent->email }}</p>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($agent->department)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                <i class="bi bi-building text-xs"></i>
                {{ $agent->department }}
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                <i class="bi bi-building text-xs"></i>
                No Department
            </span>
        @endif
    </td>
    <td class="px-6 py-4 user-status-cell whitespace-nowrap">
        @if($agent->status == 'active')
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                <i class="bi bi-circle-fill text-xs"></i>
                Active
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                <i class="bi bi-circle-fill text-xs"></i>
                Inactive
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
            <i class="bi bi-ticket text-xs"></i>
            {{ $agent->tickets_count ?? 0 }}
        </span>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center justify-center gap-1.5">
            <button type="button"
                    class="toggle-status-btn w-8 h-8 rounded-lg transition-all duration-200 flex items-center justify-center group"
                    data-id="{{ $agent->id }}"
                    data-active="{{ $agent->status === 'active' ? 'true' : 'false' }}"
                    data-self="{{ $agent->id === auth()->id() ? 'true' : 'false' }}"
                    title="{{ $agent->id === auth()->id() ? 'You cannot change the status of your own account.' : ($agent->status === 'active' ? 'Deactivate Agent' : 'Activate Agent') }}">
                @if($agent->status === 'active')
                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 flex items-center justify-center">
                        <i class="bi bi-toggle-on text-base"></i>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 flex items-center justify-center">
                        <i class="bi bi-toggle-off text-base"></i>
                    </div>
                @endif
            </button>
            <a href="{{ route('admin.users.show', $agent) }}" 
               class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-blue-100 hover:text-blue-600 dark:hover:bg-blue-900/30 dark:hover:text-blue-400 transition-all duration-200 flex items-center justify-center"
               title="View Agent">
                <i class="bi bi-eye text-sm"></i>
            </a>
            <a href="{{ route('admin.users.edit', $agent) }}" 
               class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-green-100 hover:text-green-600 dark:hover:bg-green-900/30 dark:hover:text-green-400 transition-all duration-200 flex items-center justify-center"
               title="Edit Agent">
                <i class="bi bi-pencil-square text-sm"></i>
            </a>
            <button onclick="showDeleteModal('{{ $agent->id }}', '{{ addslashes($agent->name) }}')" 
                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 transition-all duration-200 flex items-center justify-center"
                    title="Delete Agent">
                <i class="bi bi-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-12 text-center">
        <div class="flex flex-col items-center gap-3">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                <i class="bi bi-headset text-2xl text-gray-400 dark:text-gray-500"></i>
            </div>
            <p class="text-gray-500 dark:text-gray-400">No agent accounts found</p>
            <a href="{{ route('admin.users.create', ['role' => 'agent']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white rounded-lg text-sm transition-all">
                <i class="bi bi-plus-circle"></i> Create your first agent
            </a>
        </div>
    </td>
</tr>
@endforelse