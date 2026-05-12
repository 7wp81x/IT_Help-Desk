@props(['collapsed' => false])

<aside id="default-sidebar"
       class="fixed top-0 left-0 z-40 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 shadow-xl"
       :style="{ width: sidebarCollapsed ? '80px' : '280px' }"
       x-data="sidebarComponent()"
       x-init="init()"
       x-show="true"
       x-cloak>
    
    <nav class="h-full flex flex-col bg-white dark:bg-gray-900">
        <!-- Header / Logo Area - Compact -->
        <div class="py-4 px-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between" :class="{ 'flex-col gap-3': sidebarCollapsed, 'flex-row': !sidebarCollapsed }">
                <!-- Logo and Title -->
                <div class="flex items-center gap-2" :class="{ 'flex-col text-center': sidebarCollapsed, 'flex-row': !sidebarCollapsed }">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="bi bi-headset text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="transition-all duration-300" 
                         :class="{ 'hidden opacity-0': sidebarCollapsed, 'block opacity-100': !sidebarCollapsed }">
                        <span class="text-sm font-bold text-gray-800 dark:text-white whitespace-nowrap">IT Helpdesk</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 block">Ticketing System</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-1" :class="{ 'flex-row justify-center w-full': sidebarCollapsed, 'flex-row': !sidebarCollapsed }">
                    <!-- Dark/Light Mode Toggle -->
                    <button @click="toggleTheme()"
                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800"
                            @mouseenter="sidebarCollapsed ? showTooltip($event, isDarkMode ? 'Light Mode' : 'Dark Mode') : null"
                            @mouseleave="hideTooltip()">
                        <i class="bi text-lg transition-all duration-300"
                           :class="{ 
                               'bi-moon-stars text-gray-600 dark:text-gray-400': !isDarkMode,
                               'bi-sun text-yellow-500': isDarkMode
                           }"></i>
                    </button>

                    <!-- Collapse Toggle -->
                    <button id="toggle-sidebar"
                            class="w-8 h-8 flex items-center justify-center rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800"
                            @click="toggleSidebar()"
                            @mouseenter="sidebarCollapsed ? showTooltip($event, 'Expand') : showTooltip($event, 'Collapse')"
                            @mouseleave="hideTooltip()">
                        <i class="bi text-lg text-gray-600 dark:text-gray-400 transition-transform duration-300" 
                           :class="{ 'bi-chevron-right': sidebarCollapsed, 'bi-chevron-left': !sidebarCollapsed }"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Items -->
        <div class="flex-1 px-3 py-4 overflow-y-auto">
            @php
                $pendingAgentApplicationCount = \App\Models\AgentApplication::where('status', 'pending')->count();
            @endphp
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'dashboard',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'dashboard'
                        }"
                        @click="setActiveMenu('dashboard')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Dashboard') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-speedometer2 text-xl" :class="{ 'text-white': activeMenu === 'dashboard' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Dashboard</span>
                    </a>
                </li>

                <!-- Tickets Dropdown -->
                <li x-data="{ ticketsOpen: false }" x-init="ticketsOpen = activeMenu === 'tickets'">
                    <button @click="ticketsOpen = !ticketsOpen; if(ticketsOpen) setActiveMenu('tickets')"
                            class="w-full flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                            :class="{
                                'justify-center': sidebarCollapsed,
                                'justify-start': !sidebarCollapsed,
                                'bg-blue-600 text-white shadow-md': activeMenu === 'tickets',
                                'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'tickets'
                            }"
                            @mouseenter="sidebarCollapsed ? showTooltip($event, 'Tickets') : null"
                            @mouseleave="hideTooltip()">
                        <i class="bi bi-ticket-detailed text-xl" :class="{ 'text-white': activeMenu === 'tickets' }"></i>
                        <span class="text-sm font-medium flex-1 text-left" :class="{ 'hidden': sidebarCollapsed }">Tickets</span>
                        <i class="bi bi-chevron-down text-xs transition-transform duration-200"
                           :class="{ 
                               'hidden': sidebarCollapsed,
                               'rotate-180': ticketsOpen 
                           }"></i>
                    </button>
                    
                    <ul x-show="ticketsOpen" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        :class="{ 
                            'absolute left-20 top-24 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 min-w-[200px] py-2 px-2 space-y-1': sidebarCollapsed,
                            'ml-6 mt-1 space-y-1': !sidebarCollapsed
                        }"
                        class="ml-6 mt-1 space-y-1">
                        
                        <!-- All Tickets -->
                        <li>
                            <a href="{{ route('admin.tickets.all') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'all-tickets',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'all-tickets'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('all-tickets'); ticketsOpen = true">
                                <i class="bi bi-list-ul text-lg"></i>
                                <span class="text-sm">All Tickets</span>
                                <span class="ml-auto bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full px-2 py-0.5">{{ $ticketCounts['all'] ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <!-- My Tickets (Agents Only) -->
                        @if(Auth::user()->role === 'agent')
                        <li>
                            <a href="{{ route('admin.tickets.assigned-to-me') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'my-tickets',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'my-tickets'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('my-tickets'); ticketsOpen = true">
                                <i class="bi bi-person-badge text-lg"></i>
                                <span class="text-sm">My Tickets</span>
                                <span class="ml-auto bg-blue-600 text-white text-xs rounded-full px-2 py-0.5">{{ \App\Models\Ticket::where('assigned_to', Auth::id())->count() }}</span>
                            </a>
                        </li>
                        @endif
                        
                        <!-- Open Tickets -->
                        <li>
                            <a href="{{ route('admin.tickets.open') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400': activeSubMenu === 'open-tickets',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'open-tickets'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('open-tickets'); ticketsOpen = true">
                                <i class="bi bi-envelope-open text-lg"></i>
                                <span class="text-sm">Open Tickets</span>
                                <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5">{{ $ticketCounts['open'] ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <!-- In Progress Tickets -->
                        <li>
                            <a href="{{ route('admin.tickets.in-progress') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400': activeSubMenu === 'in-progress',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'in-progress'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('in-progress'); ticketsOpen = true">
                                <i class="bi bi-arrow-repeat text-lg"></i>
                                <span class="text-sm">In Progress</span>
                                <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5">{{ $ticketCounts['in_progress'] ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <!-- Resolved Tickets -->
                        <li>
                            <a href="{{ route('admin.tickets.resolved') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400': activeSubMenu === 'resolved',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'resolved'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('resolved'); ticketsOpen = true">
                                <i class="bi bi-check-circle text-lg"></i>
                                <span class="text-sm">Resolved</span>
                                <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-0.5">{{ $ticketCounts['resolved'] ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <!-- Closed Tickets -->
                        <li>
                            <a href="{{ route('admin.tickets.closed') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                                :class="{
                                    'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400': activeSubMenu === 'closed',
                                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'closed'
                                }"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('closed'); ticketsOpen = true">
                                <i class="bi bi-archive text-lg"></i>
                                <span class="text-sm">Closed Tickets</span>
                                <span class="ml-auto bg-gray-500 text-white text-xs rounded-full px-2 py-0.5">{{ $ticketCounts['closed'] ?? 0 }}</span>
                            </a>
                        </li>
                        
                        <li class="pt-1">
                            <hr class="border-gray-200 dark:border-gray-700 my-1">
                        </li>
                        
                        <!-- Create New Ticket -->
                        <li>
                            <a href="{{ route('admin.tickets.create') }}"
                                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30"
                                @click="setActiveMenu('tickets'); setActiveSubMenu('create-ticket'); ticketsOpen = true">
                                <i class="bi bi-plus-circle text-lg"></i>
                                <span class="text-sm font-medium">Create Ticket</span>
                            </a>
                        </li>
                    </ul>
                </li>

            
                <!-- User Management Dropdown - FIXED: Stays open when active -->
<li x-data="{ userOpen: false }" x-init="userOpen = activeMenu === 'usermanagement'">
    <button @click="userOpen = !userOpen; if(userOpen) setActiveMenu('usermanagement')"
            class="w-full flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
            :class="{
                'justify-center': sidebarCollapsed,
                'justify-start': !sidebarCollapsed,
                'bg-blue-600 text-white shadow-md': activeMenu === 'usermanagement',
                'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'usermanagement'
            }"
            @mouseenter="sidebarCollapsed ? showTooltip($event, 'User Management') : null"
            @mouseleave="hideTooltip()">
        <i class="bi bi-people text-xl" :class="{ 'text-white': activeMenu === 'usermanagement' }"></i>
        <span class="text-sm font-medium flex-1 text-left" :class="{ 'hidden': sidebarCollapsed }">User Management</span>
        <i class="bi bi-chevron-down text-xs transition-transform duration-200"
           :class="{ 'hidden': sidebarCollapsed, 'rotate-180': userOpen }"></i>
    </button>
    
    <ul x-show="userOpen" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        :class="{ 
            'absolute left-20 top-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 min-w-[200px] py-2 px-2 space-y-1': sidebarCollapsed,
            'ml-6 mt-1 space-y-1': !sidebarCollapsed
        }"
        class="ml-6 mt-1 space-y-1">
        
        <!-- All Users -->
        <li>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'all-users',
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'all-users'
                }"
                @click="setActiveMenu('usermanagement'); setActiveSubMenu('all-users'); userOpen = true">
                <i class="bi bi-people text-lg"></i>
                <span class="text-sm">All Users</span>
            </a>
        </li>
        
        <!-- Admins -->
        <li>
            <a href="{{ route('admin.users.admins') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'admins',
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'admins'
                }"
                @click="setActiveMenu('usermanagement'); setActiveSubMenu('admins'); userOpen = true">
                <i class="bi bi-shield-lock text-lg"></i>
                <span class="text-sm">Admins</span>
            </a>
        </li>
        
        <!-- Agents -->
        <li>
            <a href="{{ route('admin.users.agents') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'agents',
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'agents'
                }"
                @click="setActiveMenu('usermanagement'); setActiveSubMenu('agents'); userOpen = true">
                <i class="bi bi-headset text-lg"></i>
                <span class="text-sm">Agents</span>
            </a>
        </li>
        
        <!-- End Users -->
        <li>
            <a href="{{ route('admin.users.end-users') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-all duration-200"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': activeSubMenu === 'end-users',
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeSubMenu !== 'end-users'
                }"
                @click="setActiveMenu('usermanagement'); setActiveSubMenu('end-users'); userOpen = true">
                <i class="bi bi-person text-lg"></i>
                <span class="text-sm">End Users</span>
            </a>
        </li>
    </ul>
</li>

                <!-- Agent Applications -->
                <li>
                    <a href="{{ route('admin.applications') }}"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'agent-applications',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'agent-applications'
                        }"
                        @click="setActiveMenu('agent-applications')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Agent Applications') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-file-earmark-person text-xl" :class="{ 'text-white': activeMenu === 'agent-applications' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Agent Applications</span>
                        <span class="ml-auto inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-[11px] font-semibold text-blue-700 dark:bg-blue-900/20 dark:text-blue-200" :class="{ 'hidden': sidebarCollapsed }">{{ $pendingAgentApplicationCount }}</span>
                    </a>
                </li>

                <!-- Categories -->
                <li>
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'categories',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'categories'
                        }"
                        @click="setActiveMenu('categories')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Categories') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-tags text-xl" :class="{ 'text-white': activeMenu === 'categories' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Categories</span>
                    </a>
                </li>

                <!-- Departments -->
                <li>
                    <a href="{{ route('admin.departments.index') }}"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'departments',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'departments'
                        }"
                        @click="setActiveMenu('departments')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Departments') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-building text-xl" :class="{ 'text-white': activeMenu === 'departments' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Departments</span>
                    </a>
                </li>

                <li class="pt-2"><div class="border-t border-gray-200 dark:border-gray-800"></div></li>

                <!-- Reports -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'reports',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'reports'
                        }"
                        @click="setActiveMenu('reports')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Reports') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-bar-chart-line text-xl" :class="{ 'text-white': activeMenu === 'reports' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Reports</span>
                    </a>
                </li>

                <!-- Settings -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-blue-600 text-white shadow-md': activeMenu === 'settings',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'settings'
                        }"
                        @click="setActiveMenu('settings')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Settings') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-gear text-xl" :class="{ 'text-white': activeMenu === 'settings' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Settings</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Profile Section -->
        <div class="border-t border-gray-200 dark:border-gray-800 p-2 relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen"
                    class="w-full flex items-center gap-3 p-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-800"
                    :class="{ 'justify-center': sidebarCollapsed, 'justify-start': !sidebarCollapsed }"
                    @mouseenter="sidebarCollapsed ? showTooltip($event, Auth::user()->name) : null"
                    @mouseleave="hideTooltip()">
                
                <div class="flex-shrink-0">
                    <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?background=2563EB&color=fff&name=' . urlencode(Auth::user()->name) }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-9 h-9 rounded-full object-cover shadow-md">
                </div>
                
                <div class="flex-1 text-left" :class="{ 'hidden': sidebarCollapsed }">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>
                <i class="bi bi-chevron-down text-xs text-gray-500 transition-transform duration-200" 
                   :class="{ 'hidden': sidebarCollapsed, 'rotate-180': profileOpen }"></i>
            </button>
            
            <div x-show="profileOpen" 
                 x-cloak 
                 @click.away="profileOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="absolute bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-1 z-50"
                 :class="{ 'left-20 bottom-2 min-w-[200px]': sidebarCollapsed, 'left-3 right-3 bottom-14': !sidebarCollapsed }">
                
                <a href="{{ route('admin.profile') }}" @click="profileOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="bi bi-person-circle text-blue-600 text-base"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('admin.profile.password') }}" @click="profileOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="bi bi-key text-blue-600 text-base"></i>
                    <span>Change Password</span>
                </a>
                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="bi bi-box-arrow-right text-red-600 text-base"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</aside>

<script>
function sidebarComponent() {
    return {
        activeMenu: 'dashboard',
        activeSubMenu: null,
        tooltipTimeout: null,
        tooltipElement: null,
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        
        init() {
            this.tooltipElement = document.createElement('div');
            this.tooltipElement.className = 'fixed px-3 py-1.5 bg-gray-900 dark:bg-gray-700 text-white text-sm font-medium rounded-lg whitespace-nowrap shadow-xl z-[9999]';
            this.tooltipElement.style.display = 'none';
            this.tooltipElement.style.pointerEvents = 'none';
            document.body.appendChild(this.tooltipElement);
            
            window.addEventListener('theme-toggle', (event) => {
                if (event.detail.darkMode !== undefined) {
                    this.isDarkMode = event.detail.darkMode;
                }
            });
            
            window.addEventListener('sidebar-toggle', (event) => {
                if (event.detail.collapsed !== undefined) {
                    this.sidebarCollapsed = event.detail.collapsed;
                    this.hideTooltip();
                }
            });
            
            this.updateActiveMenuFromRoute();
        },
        
    updateActiveMenuFromRoute() {
    const currentPath = window.location.pathname;
    
    if (currentPath === '/admin/dashboard' || currentPath === '/admin') {
        this.activeMenu = 'dashboard';
    } else if (currentPath.includes('/admin/tickets')) {
        this.activeMenu = 'tickets';
        if (currentPath.includes('/assigned') || currentPath.includes('/my-tickets')) {
            this.activeSubMenu = 'my-tickets';
        } else if (currentPath.includes('/in-progress')) {
            this.activeSubMenu = 'in-progress';
        } else if (currentPath.includes('/resolved')) {
            this.activeSubMenu = 'resolved';
        } else if (currentPath.includes('/closed')) {
            this.activeSubMenu = 'closed';
        } else if (currentPath.includes('/create')) {
            this.activeSubMenu = 'create-ticket';
        } else if (currentPath.includes('/open')) {
            this.activeSubMenu = 'open-tickets';
        } else {
            this.activeSubMenu = 'all-tickets';
        }
    } else if (currentPath.includes('/admin/users')) {
        this.activeMenu = 'usermanagement';
        
        // Use includes() for nested routes, not strict equality
        if (currentPath.includes('/admin/users/admins')) {
            this.activeSubMenu = 'admins';
        } 
        else if (currentPath.includes('/admin/users/agents')) {
            this.activeSubMenu = 'agents';
        } 
        else if (currentPath.includes('/admin/users/end-users')) {
            this.activeSubMenu = 'end-users';
        }
        // For All Users route (index)
        else if (currentPath === '/admin/users' || currentPath === '/admin/users/') {
            this.activeSubMenu = 'all-users';
        }
        else {
            this.activeSubMenu = 'all-users';
        }
        
        // Save current submenu to localStorage for create/edit/show pages
        if (this.activeSubMenu) {
            localStorage.setItem('currentSubMenu', this.activeSubMenu);
        }
    } else if (currentPath.includes('/admin/applications')) {
        this.activeMenu = 'agent-applications';
        this.activeSubMenu = null;
    } else if (currentPath.includes('/admin/categories')) {
        this.activeMenu = 'categories';
    } else if (currentPath.includes('/admin/departments')) {
        this.activeMenu = 'departments';
    } else if (currentPath.includes('/admin/reports')) {
        this.activeMenu = 'reports';
    } else if (currentPath.includes('/admin/settings')) {
        this.activeMenu = 'settings';
    }
},

        
        setActiveMenu(menu) {
            this.activeMenu = menu;
        },
        
        setActiveSubMenu(submenu) {
            this.activeSubMenu = submenu;
        },
        
        toggleSidebar() {
            const newState = !this.sidebarCollapsed;
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                detail: { collapsed: newState } 
            }));
            this.hideTooltip();
        },
        
        toggleTheme() {
            const newTheme = !this.isDarkMode;
            const event = new CustomEvent('theme-toggle', { 
                detail: { darkMode: newTheme } 
            });
            window.dispatchEvent(event);
        },
        
        showTooltip(event, text) {
            if (!this.sidebarCollapsed) return;
            if (this.tooltipTimeout) clearTimeout(this.tooltipTimeout);
            
            this.tooltipTimeout = setTimeout(() => {
                const rect = event.currentTarget.getBoundingClientRect();
                this.tooltipElement.textContent = text;
                this.tooltipElement.style.display = 'block';
                this.tooltipElement.style.left = (rect.right + 8) + 'px';
                this.tooltipElement.style.top = (rect.top + rect.height / 2 - 20) + 'px';
            }, 300);
        },
        
        hideTooltip() {
            if (this.tooltipTimeout) clearTimeout(this.tooltipTimeout);
            if (this.tooltipElement) this.tooltipElement.style.display = 'none';
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }

.overflow-y-auto::-webkit-scrollbar {
    width: 5px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.dark .overflow-y-auto::-webkit-scrollbar-track {
    background: #1f2937;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #4b5563;
}
</style>