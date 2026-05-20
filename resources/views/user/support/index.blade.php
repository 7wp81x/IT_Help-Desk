@extends('layouts.app')

@section('title', 'Support & FAQ')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header with Date/Time -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Support & FAQ</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get help, find answers, and contact our support team</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-calendar3 text-emerald-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentDate"></span>
                    </div>
                    <div class="w-px h-4 bg-gray-300 dark:bg-gray-600"></div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-clock text-emerald-500 text-lg"></i>
                        <span class="text-sm text-gray-700 dark:text-gray-300" id="currentTime"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="bi bi-headset text-emerald-600 dark:text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Support Hours</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">24/7</p>
                    <p class="text-xs text-gray-400 mt-1">Always here for you</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="bi bi-question-circle-fill text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">FAQ Topics</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($faqs) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Categories to help you</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                    <i class="bi bi-chat-text-fill text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Your Open Tickets</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $openTickets }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting response</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FAQ Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- FAQ Accordion -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Frequently Asked Questions</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Find answers to common questions below</p>
                </div>
                <div x-data="{ openFaq: null }" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($faqs as $faqGroup)
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="bi bi-folder-fill text-emerald-600 dark:text-emerald-400"></i>
                                {{ $faqGroup['category'] }}
                            </h4>
                            
                            <div class="space-y-3">
                                @foreach($faqGroup['items'] as $index => $item)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-800"
                                         x-data="{ open: false }"
                                         @click.outside="open = false">
                                        <button @click="open = !open" 
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition duration-200 flex items-center justify-between text-left">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $item['question'] }}</span>
                                            <i class="bi bi-chevron-down text-gray-600 dark:text-gray-400 transition-transform duration-200"
                                               :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-collapse
                                             class="px-4 py-3 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-sm border-t border-gray-200 dark:border-gray-700">
                                            {!! $item['answer'] !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Still Need Help -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl shadow-lg p-8 text-white">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h4 class="text-xl font-bold mb-2">Still need help?</h4>
                        <p class="text-emerald-100 mb-4">Our support team is ready to help you with any issue.</p>
                        <a href="{{ route('user.tickets.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-600 rounded-lg font-medium hover:bg-emerald-50 transition duration-200 shadow-md">
                            <i class="bi bi-plus-circle"></i>
                            Create Support Ticket
                        </a>
                    </div>
                    <div class="hidden lg:block">
                        <i class="bi bi-chat-dots-fill text-6xl text-white/20"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Links -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800/50 dark:to-gray-800/50">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Quick Links</h4>
                </div>
                <div class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all group">
                                <i class="bi bi-ticket text-blue-600 dark:text-blue-400"></i>
                                <span class="flex-1">My Tickets</span>
                                <i class="bi bi-chevron-right text-gray-400 opacity-0 group-hover:opacity-100 transition"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.tickets.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-all group">
                                <i class="bi bi-plus-circle text-emerald-600 dark:text-emerald-400"></i>
                                <span class="flex-1">Create Ticket</span>
                                <i class="bi bi-chevron-right text-gray-400 opacity-0 group-hover:opacity-100 transition"></i>
                            </a>
                        </li>
                        <!-- Knowledge Base removed -->
                        <li>
                            <a href="{{ route('user.agents') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/30 transition-all group">
                                <i class="bi bi-people text-orange-600 dark:text-orange-400"></i>
                                <span class="flex-1">Our Agents</span>
                                <i class="bi bi-chevron-right text-gray-400 opacity-0 group-hover:opacity-100 transition"></i>
                            </a>
                        </li>
                      
                    </ul>
                </div>
            </div>

            <!-- Support Info -->
            <div class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <i class="bi bi-info-circle-fill text-emerald-600 dark:text-emerald-400 text-lg"></i>
                    </div>
                    <h4 class="font-semibold text-emerald-900 dark:text-emerald-400">Contact Information</h4>
                </div>
                <div class="space-y-4 text-sm">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-envelope-fill text-emerald-600 dark:text-emerald-400 mt-0.5"></i>
                        <div>
                            <p class="text-emerald-700 dark:text-emerald-300 font-medium">Email</p>
                            <a href="mailto:support@ithelpdesk.com" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700">
                                support@ithelpdesk.com
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-clock-fill text-emerald-600 dark:text-emerald-400 mt-0.5"></i>
                        <div>
                            <p class="text-emerald-700 dark:text-emerald-300 font-medium">Response Time</p>
                            <p class="text-emerald-600 dark:text-emerald-400">Usually within 24 hours</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-calendar-week-fill text-emerald-600 dark:text-emerald-400 mt-0.5"></i>
                        <div>
                            <p class="text-emerald-700 dark:text-emerald-300 font-medium">Availability</p>
                            <p class="text-emerald-600 dark:text-emerald-400">Monday - Friday, 8 AM - 6 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="bi bi-lightbulb-fill text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                    <h4 class="font-semibold text-blue-900 dark:text-blue-400">Tips for Better Support</h4>
                </div>
                <ul class="space-y-3 text-sm text-blue-800 dark:text-blue-300">
                    <li class="flex gap-2">
                        <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                        <span>Provide detailed descriptions</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                        <span>Include error messages</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                        <span>Attach screenshots when needed</span>
                    </li>
                    <li class="flex gap-2">
                        <i class="bi bi-check-circle-fill text-blue-600 dark:text-blue-400 flex-shrink-0"></i>
                        <span>Check knowledge base first</span>
                    </li>
                </ul>
            </div>

            <!-- Navigation Helper -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30 rounded-xl border border-amber-200 dark:border-amber-800 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <i class="bi bi-compass-fill text-amber-600 dark:text-amber-400 text-lg"></i>
                    </div>
                    <h4 class="font-semibold text-amber-900 dark:text-amber-400">Create New Ticket</h4>
                </div>
                <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">
                    Navigate to <a href="{{ route('user.tickets.create') }}" class="font-semibold text-amber-800 dark:text-amber-200 hover:underline">"New Ticket"</a> in the sidebar or visit <a href="{{ route('user.tickets.index') }}" class="font-semibold text-amber-800 dark:text-amber-200 hover:underline">"My Tickets"</a>. Fill in the required information and submit your ticket.
                </p>
                <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">
                    Click on your profile icon in the bottom left, then select <a href="{{ route('user.profile') }}" class="font-semibold text-amber-800 dark:text-amber-200 hover:underline">"Edit Profile"</a> to update your information.
                </p>
                <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">
                    Go to your profile and select <a href="{{ route('user.profile.password') }}" class="font-semibold text-amber-800 dark:text-amber-200 hover:underline">"Change Password"</a> and follow the instructions.
                </p>
                <a href="{{ route('user.tickets.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm transition-colors w-full justify-center">
                    <i class="bi bi-plus-circle"></i>
                    Create New Ticket
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Live Date and Time
function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options);
    const formattedTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    const dateElement = document.getElementById('currentDate');
    const timeElement = document.getElementById('currentTime');
    if (dateElement) dateElement.textContent = formattedDate;
    if (timeElement) timeElement.textContent = formattedTime;
}

updateDateTime();
setInterval(updateDateTime, 1000);
</script>
@endsection