@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Support & FAQ
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="bi bi-headset text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Support Hours</p>
                        <p class="text-2xl font-bold">24/7</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <i class="bi bi-question-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">FAQ Topics</p>
                        <p class="text-2xl font-bold">{{ count($faqs) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                        <i class="bi bi-chat-text-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Your Open Tickets</p>
                        <p class="text-2xl font-bold">{{ $openTickets }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- FAQ Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- FAQ Accordion -->
                <div class="bg-white rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Frequently Asked Questions</h3>
                        <p class="text-sm text-gray-600 mt-1">Find answers to common questions below</p>
                    </div>
                    <div x-data="{ openFaq: null }" class="divide-y divide-gray-200">
                        @foreach($faqs as $faqGroup)
                            <!-- FAQ Category -->
                            <div class="p-6 border-b border-gray-200 last:border-b-0">
                                <h4 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="bi bi-folder text-blue-600"></i>
                                    {{ $faqGroup['category'] }}
                                </h4>
                                
                                <div class="space-y-3">
                                    @foreach($faqGroup['items'] as $index => $item)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden"
                                             x-data="{ open: false }"
                                             @click.outside="open = false">
                                            <!-- Question -->
                                            <button @click="open = !open" 
                                                    class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 transition duration-200 flex items-center justify-between text-left">
                                                <span class="font-medium text-gray-900">{{ $item['question'] }}</span>
                                                <i class="bi bi-chevron-down text-gray-600 transition-transform duration-200"
                                                   :class="{ 'rotate-180': open }"></i>
                                            </button>
                                            
                                            <!-- Answer -->
                                            <div x-show="open" 
                                                 x-collapse
                                                 class="px-4 py-3 bg-white text-gray-600 text-sm border-t border-gray-200">
                                                {{ $item['answer'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Still Need Help -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow p-8 text-white">
                    <h4 class="text-xl font-bold mb-2">Didn't find your answer?</h4>
                    <p class="text-blue-100 mb-6">Our support team is ready to help you with any issue. Create a ticket and we'll get back to you as soon as possible.</p>
                    <a href="{{ route('user.tickets.create') }}" class="inline-block px-6 py-3 bg-white text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition duration-200">
                        Create Support Ticket
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Quick Links -->
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.tickets.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 text-sm">
                                <i class="bi bi-ticket"></i> My Tickets
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.tickets.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 text-sm">
                                <i class="bi bi-plus-circle"></i> Create Ticket
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.knowledgebase') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 text-sm">
                                <i class="bi bi-journal-bookmark"></i> Knowledge Base
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.agents') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 text-sm">
                                <i class="bi bi-people"></i> Our Agents
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.announcements') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-blue-50 text-sm">
                                <i class="bi bi-megaphone"></i> Announcements
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support Info -->
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6">
                    <h4 class="font-semibold text-emerald-900 mb-3">Contact Information</h4>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-emerald-700 font-medium">Email</p>
                            <a href="mailto:support@ithelpdesk.com" class="text-emerald-600 hover:text-emerald-700">
                                support@ithelpdesk.com
                            </a>
                        </div>
                        <div>
                            <p class="text-emerald-700 font-medium">Response Time</p>
                            <p class="text-emerald-600">Usually within 24 hours</p>
                        </div>
                        <div>
                            <p class="text-emerald-700 font-medium">Availability</p>
                            <p class="text-emerald-600">Monday - Friday, 8 AM - 6 PM</p>
                        </div>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-6">
                    <h4 class="font-semibold text-blue-900 mb-3">Tips for Better Support</h4>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex gap-2">
                            <i class="bi bi-check-circle text-blue-600 flex-shrink-0"></i>
                            <span>Provide detailed descriptions</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="bi bi-check-circle text-blue-600 flex-shrink-0"></i>
                            <span>Include error messages</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="bi bi-check-circle text-blue-600 flex-shrink-0"></i>
                            <span>Attach screenshots when needed</span>
                        </li>
                        <li class="flex gap-2">
                            <i class="bi bi-check-circle text-blue-600 flex-shrink-0"></i>
                            <span>Check knowledge base first</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
