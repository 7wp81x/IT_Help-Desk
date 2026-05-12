@props(['collapsed' => false])

<aside id="default-sidebar"
       class="fixed top-0 left-0 z-40 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 shadow-xl"
       :style="{ width: sidebarCollapsed ? '80px' : '280px' }"
       x-data="sidebarComponent()"
       x-init="init()"
       x-show="true"
       x-cloak>
    
    <nav class="h-full flex flex-col bg-white dark:bg-gray-900">
        <!-- Header / Logo Area -->
        <div class="py-4 px-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between" :class="{ 'flex-col gap-3': sidebarCollapsed, 'flex-row': !sidebarCollapsed }">
                <!-- Logo and Title -->
                <div class="flex items-center gap-2" :class="{ 'flex-col text-center': sidebarCollapsed, 'flex-row': !sidebarCollapsed }">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="bi bi-headset text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="transition-all duration-300" 
                         :class="{ 'hidden opacity-0': sidebarCollapsed, 'block opacity-100': !sidebarCollapsed }">
                        <span class="text-sm font-bold text-gray-800 dark:text-white whitespace-nowrap">Agent Portal</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 block">IT Helpdesk</span>
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

        <!-- Menu Items - AGENT SPECIFIC (No dropdowns, direct links only) -->
        <div class="flex-1 px-3 py-4 overflow-y-auto">
            <ul class="space-y-1">
                <!-- Dashboard -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-green-600 text-white shadow-md': activeMenu === 'dashboard',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'dashboard'
                        }"
                        @click="setActiveMenu('dashboard')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Dashboard') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-speedometer2 text-xl" :class="{ 'text-white': activeMenu === 'dashboard' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Dashboard</span>
                    </a>
                </li>

                <!-- Assigned to Me (My Tickets) -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-green-600 text-white shadow-md': activeMenu === 'my-tickets',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'my-tickets'
                        }"
                        @click="setActiveMenu('my-tickets')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Assigned to Me') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-person-check text-xl" :class="{ 'text-white': activeMenu === 'my-tickets' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Assigned to Me</span>
                        <span class="ml-auto bg-green-100 text-green-700 text-xs rounded-full px-2 py-0.5" :class="{ 'hidden': sidebarCollapsed }">{{ $assignedCount ?? 0 }}</span>
                    </a>
                </li>

                <!-- Open Tickets (Unassigned) -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400': activeMenu === 'open-tickets',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'open-tickets'
                        }"
                        @click="setActiveMenu('open-tickets')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Open Tickets') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-envelope-open text-xl" :class="{ 'text-yellow-600': activeMenu === 'open-tickets' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Open Tickets</span>
                        <span class="ml-auto bg-yellow-500 text-white text-xs rounded-full px-2 py-0.5" :class="{ 'hidden': sidebarCollapsed }">{{ $openCount ?? 0 }}</span>
                    </a>
                </li>

                <!-- In Progress Tickets -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400': activeMenu === 'in-progress',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'in-progress'
                        }"
                        @click="setActiveMenu('in-progress')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'In Progress') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-arrow-repeat text-xl" :class="{ 'text-purple-600': activeMenu === 'in-progress' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">In Progress</span>
                        <span class="ml-auto bg-purple-500 text-white text-xs rounded-full px-2 py-0.5" :class="{ 'hidden': sidebarCollapsed }">{{ $inProgressCount ?? 0 }}</span>
                    </a>
                </li>

                <!-- Resolved Tickets -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400': activeMenu === 'resolved',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'resolved'
                        }"
                        @click="setActiveMenu('resolved')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Resolved') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-check-circle text-xl" :class="{ 'text-green-600': activeMenu === 'resolved' }"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Resolved</span>
                        <span class="ml-auto bg-green-500 text-white text-xs rounded-full px-2 py-0.5" :class="{ 'hidden': sidebarCollapsed }">{{ $resolvedCount ?? 0 }}</span>
                    </a>
                </li>

                <!-- Closed Tickets -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400': activeMenu === 'closed',
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': activeMenu !== 'closed'
                        }"
                        @click="setActiveMenu('closed')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Closed') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-archive text-xl"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Closed</span>
                        <span class="ml-auto bg-gray-500 text-white text-xs rounded-full px-2 py-0.5" :class="{ 'hidden': sidebarCollapsed }">{{ $closedCount ?? 0 }}</span>
                    </a>
                </li>

                <li class="pt-2"><div class="border-t border-gray-200 dark:border-gray-800"></div></li>

                <!-- Create New Ticket -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed
                        }"
                        @click="setActiveMenu('create-ticket')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Create Ticket') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-plus-circle text-xl"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Create Ticket</span>
                    </a>
                </li>

                <!-- Knowledge Base -->
                <li>
                    <a href="#"
                        class="flex items-center gap-3 p-2.5 rounded-lg transition-all duration-200"
                        :class="{
                            'justify-center': sidebarCollapsed,
                            'justify-start': !sidebarCollapsed,
                            'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': true
                        }"
                        @click="setActiveMenu('knowledgebase')"
                        @mouseenter="sidebarCollapsed ? showTooltip($event, 'Knowledge Base') : null"
                        @mouseleave="hideTooltip()">
                        <i class="bi bi-journal-bookmark-fill text-xl text-cyan-500"></i>
                        <span class="text-sm font-medium" :class="{ 'hidden': sidebarCollapsed }">Knowledge Base</span>
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
                    <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?background=10b981&color=fff&name=' . urlencode(Auth::user()->name) }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-9 h-9 rounded-full object-cover shadow-md">
                </div>
                
                <div class="flex-1 text-left" :class="{ 'hidden': sidebarCollapsed }">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ Auth::user()->name }}
                        @if(Auth::user()->employee_id)
                            <span class="text-xs text-green-600 dark:text-green-400 font-mono ml-1">({{ Auth::user()->employee_id }})</span>
                        @endif
                    </p>
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
                
                <a href="{{ route('agent.profile') }}" @click="profileOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="bi bi-person-circle text-green-600 text-base"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('agent.profile.password') }}" @click="profileOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="bi bi-key text-green-600 text-base"></i>
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
            
            if (currentPath.includes('/agent/dashboard')) {
                this.activeMenu = 'dashboard';
            } else if (currentPath.includes('/agent/tickets/assigned')) {
                this.activeMenu = 'my-tickets';
            } else if (currentPath.includes('/agent/tickets/open')) {
                this.activeMenu = 'open-tickets';
            } else if (currentPath.includes('/agent/tickets/in-progress')) {
                this.activeMenu = 'in-progress';
            } else if (currentPath.includes('/agent/tickets/resolved')) {
                this.activeMenu = 'resolved';
            } else if (currentPath.includes('/agent/tickets/closed')) {
                this.activeMenu = 'closed';
            } else if (currentPath.includes('/agent/tickets/create')) {
                this.activeMenu = 'create-ticket';
            } else if (currentPath.includes('/agent/knowledgebase')) {
                this.activeMenu = 'knowledgebase';
            } else if (currentPath.includes('/agent/profile')) {
                this.activeMenu = 'profile';
            }
        },
        
        setActiveMenu(menu) {
            this.activeMenu = menu;
        },
        
        toggleSidebar() {
            const newState = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', newState);
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { 
                detail: { collapsed: newState } 
            }));
            this.hideTooltip();
        },
        
        toggleTheme() {
            const newTheme = !this.isDarkMode;
            localStorage.setItem('darkMode', newTheme);
            if (newTheme) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
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