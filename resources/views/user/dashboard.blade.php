@extends('layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header with Date/Time -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar3 text-blue-500 text-lg"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                        </div>
                        <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-clock text-blue-500 text-lg"></i>
                            <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success') && !session('open_verify_modal'))
            <div class="mb-6 rounded-2xl border border-green-200/80 bg-green-50 dark:border-green-900/40 dark:bg-green-950/20 p-4 text-sm text-green-800 dark:text-green-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <i class="bi bi-ticket-perforated text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Total Tickets</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['my_tickets'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                        <i class="bi bi-clock-history text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Open</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['open_tickets'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <i class="bi bi-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Resolved</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['resolved_tickets'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full">
                        <i class="bi bi-archive text-gray-600 dark:text-gray-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Closed</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['closed_tickets'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Verification Message - REPOSITIONED HERE (Below Stats, Above Main Content) -->
        @if (!Auth::user()->email_verified_at)
            <div class="mb-6 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-950/30 dark:to-yellow-950/30 border-l-4 border-amber-500 rounded-r-xl shadow-sm p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-full flex items-center justify-center">
                                <i class="bi bi-envelope-exclamation text-amber-600 dark:text-amber-400 text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-amber-800 dark:text-amber-200">Email Verification Required</h4>
                            <p class="text-sm text-amber-700 dark:text-amber-300 mt-0.5">
                                Please verify your email address to access all features including ticket notifications and priority support.
                            </p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                <i class="bi bi-info-circle"></i> Check your inbox for the verification code.
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="openVerifyModal()" class="flex-shrink-0 inline-flex items-center justify-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 text-sm font-medium transition duration-200 shadow-sm hover:shadow">
                        <i class="bi bi-pencil-square"></i>
                        Enter Verification Code
                    </button>
                </div>
            </div>
        @endif

        @include('user.partials.email-verification-modal')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Tickets -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">My Recent Tickets</h3>
                            <a href="{{ route('user.tickets.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View All →
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($myTickets as $ticket)
                            <a href="{{ route('user.tickets.show', $ticket) }}" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-200 block">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->ticket_number ?? '#' . $ticket->id }} - {{ $ticket->subject }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Created {{ $ticket->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @php
                                            $statusColors = [
                                                'open' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                'in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                'resolved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                'closed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400'
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Agent: {{ $ticket->assignedAgent->name ?? 'Not assigned' }}
                                </p>
                            </a>
                        @empty
                            <div class="p-12 text-center">
                                <i class="bi bi-inbox text-4xl text-gray-400 dark:text-gray-500 block mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400">No tickets yet.</p>
                                <a href="{{ route('user.tickets.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-3 inline-block">
                                    Create your first ticket →
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">In Progress</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['in_progress_tickets'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Resolution Rate</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                            @if($stats['my_tickets'] > 0)
                                {{ round(($stats['resolved_tickets'] / $stats['my_tickets']) * 100) }}%
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h4>
                    <div class="space-y-3">
                        <a href="{{ route('user.tickets.create') }}" class="block w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-center font-medium">
                            <i class="bi bi-plus-circle mr-2"></i> New Ticket
                        </a>
                        <a href="{{ route('user.tickets.index') }}" class="block w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 text-center font-medium">
                            <i class="bi bi-list mr-2"></i> View All Tickets
                        </a>
                        <!-- Knowledge Base removed -->
                    </div>
                </div>

                <!-- Support Info -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/50 dark:to-indigo-950/50 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Need Help?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Our support team is available 24/7 to help you.
                    </p>
                    <a href="{{ route('user.support') }}" class="block w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-center font-medium text-sm">
                        <i class="bi bi-headset mr-2"></i> Get Support
                    </a>
                </div>

                <!-- Helpful Resources -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Helpful Resources</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="{{ route('user.agents') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 flex items-center gap-2">
                                <i class="bi bi-people"></i> View Our Agents
                            </a>
                        </li>
                    
                        <li>
                            <a href="{{ route('user.ratings') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 flex items-center gap-2">
                                <i class="bi bi-star"></i> My Ratings
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 flex items-center gap-2">
                                <i class="bi bi-person"></i> My Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        
        // Format date: Monday, May 12, 2026
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = now.toLocaleDateString('en-US', options);
        
        // Format time: 02:30:45 PM
        const formattedTime = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        
        document.getElementById('currentDate').textContent = formattedDate;
        document.getElementById('currentTime').textContent = formattedTime;
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
@include('user.partials.email-verification-modal-scripts')
@endsection