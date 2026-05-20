<!-- Statistics Cards - Row 1 -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
    <!-- Total Tickets Card -->
    <a href="{{ route('admin.tickets.all') }}" class="group relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Total Tickets</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['total_tickets']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-ticket-perforated text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Open Tickets Card -->
    <a href="{{ route('admin.tickets.open') }}" class="group relative overflow-hidden bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Open Tickets</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['open_tickets']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock-history text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- In Progress Card -->
    <a href="{{ route('admin.tickets.in-progress') }}" class="group relative overflow-hidden bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">In Progress</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['in_progress_tickets']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-arrow-repeat text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Resolved Tickets Card -->
    <a href="{{ route('admin.tickets.resolved') }}" class="group relative overflow-hidden bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Resolved Tickets</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['resolved_tickets']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-check-circle text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Statistics Cards - Row 2 -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
    <!-- Closed Tickets Card -->
    <a href="{{ route('admin.tickets.closed') }}" class="group relative overflow-hidden bg-gradient-to-br from-gray-500 to-gray-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Closed Tickets</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['closed_tickets']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-archive text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Agents Card -->
    <a href="{{ route('admin.users.agents') }}" class="group relative overflow-hidden bg-gradient-to-br from-cyan-500 to-cyan-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Total Agents</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['total_agents']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-headset text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Users Card -->
    <a href="{{ route('admin.users.end-users') }}" class="group relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Total Users</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>

    <!-- Total Categories Card -->
    <a href="{{ route('admin.categories.index') }}" class="group relative overflow-hidden bg-gradient-to-br from-pink-500 to-pink-700 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block">
        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
        <div class="absolute -right-4 -top-4 w-14 h-14 bg-white/10 rounded-full"></div>
        <div class="relative p-3 md:p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-pink-100 text-[10px] md:text-xs font-medium uppercase tracking-wide">Categories</p>
                    <p class="text-lg md:text-2xl font-bold text-white mt-1">{{ number_format($stats['total_categories']) }}</p>
                </div>
                <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-tags text-white text-base md:text-xl"></i>
                </div>
            </div>
        </div>
    </a>
</div>