@extends('layouts.app')

@section('title', 'Apply as Agent')

@section('content')
@push('styles')
    <style>
        .main-content { margin-left: 0 !important; }
    </style>
@endpush

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center py-8">
    <div class="max-w-6xl w-full mx-auto px-4">
        
        <!-- Main Card - No Scroll -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-12">
                
                <!-- Left Side - Info (4/12 = 33%) -->
                <div class="md:col-span-4 bg-blue-600 p-6 flex flex-col justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-2">Apply as Agent</h1>
                        <p class="text-blue-100 text-sm mb-8">Join our support team</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-white text-sm">48hr review process</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-white text-sm">Secure & encrypted</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-white text-sm">Flexible schedule</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-white text-sm">Remote work option</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-white/20">
                        <a href="{{ route('welcome') }}" class="text-white/70 hover:text-white text-sm flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
                
                <!-- Right Side - Form (8/12 = 66%) -->
                <div class="md:col-span-8 p-6">
                    <form method="POST" action="{{ route('agent.application.submit') }}" enctype="multipart/form-data" 
                          x-data="{ 
                              submitting: false,
                              selectedCerts: @json(old('certifications', []))
                          }" 
                          @submit.prevent="submitting = true; $el.submit()" 
                          class="space-y-4">
                        @csrf

                        <!-- Name -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">First Name *</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('first_name') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Last Name *</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('last_name') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('email') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       placeholder="09XXXXXXXXX"
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('phone') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Documents -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Documents *</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="file" name="cover_letter_file" required
                                           accept=".pdf,.doc,.docx,.txt"
                                           class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-400">Cover Letter (PDF, DOC, DOCX)</p>
                                    @error('cover_letter_file') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <input type="file" name="resume" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-400">Resume / CV (PDF, DOC, DOCX)</p>
                                    @error('resume') <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Certifications -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Certifications (Optional)</label>
                            <div class="grid grid-cols-2 gap-2">
                                @php
                                    $certs = ['CompTIA A+', 'Network+', 'Microsoft Certified', 'AWS Certified', 'Cisco CCNA', 'ITIL Foundation'];
                                @endphp
                                @foreach($certs as $cert)
                                    <label class="flex items-center gap-2 py-1 cursor-pointer">
                                        <input type="checkbox" name="certifications[]" value="{{ $cert }}" 
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5" 
                                               x-model="selectedCerts">
                                        <span class="text-xs text-gray-700 dark:text-gray-300">{{ $cert }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition disabled:opacity-50 mt-3 text-sm"
                                :disabled="submitting">
                            <span x-show="!submitting">Submit Application</span>
                            <span x-show="submitting" class="inline-flex items-center justify-center gap-2">
                                <svg class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection