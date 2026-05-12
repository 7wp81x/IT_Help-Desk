@extends('layouts.app')

@section('title', 'Apply as Agent')

@section('content')
@push('styles')
    <style>
        .main-content { margin-left: 0 !important; }
    </style>
@endpush
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-0 xl:gap-0">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-10 text-white">
                <div class="max-w-xl">
                    <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-white shadow-sm">Agent Application</span>
                    <h1 class="mt-8 text-4xl font-semibold tracking-tight">Join our support team</h1>
                    <p class="mt-4 text-gray-100 leading-7">Submit your application to become a trusted IT agent. We review applications quickly and will notify you by email.</p>
                    <div class="mt-8 space-y-4 text-sm text-gray-200">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle mt-1 text-white"></i>
                            <span>Fast review process</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-file-pdf mt-1 text-white"></i>
                            <span>Upload resume in PDF, DOC, or DOCX</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-shield-alt mt-1 text-white"></i>
                            <span>Secure application storage</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 sm:p-10">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Apply as an Agent</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Complete the form below and attach your resume.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm text-gray-600 dark:text-gray-200">
                        <i class="fas fa-user-tie"></i>
                        Agent application
                    </div>
                </div>

                <form method="POST" action="{{ route('agent.application.submit') }}" enctype="multipart/form-data" x-data="{ submitting: false, selectedCertifications: @json(old('certifications', [])), handleFile(event) { const file = event.target.files[0]; if (!file) return; if (file.size > 5242880) { this.$refs.resumeError.textContent = 'Resume must be 5MB or less.'; event.target.value = ''; } else { this.$refs.resumeError.textContent = ''; } } }" @submit.prevent="if (!submitting) { submitting = true; $el.submit(); }" class="space-y-6">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">First name</span>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Jane">
                            @error('first_name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Last name</span>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Doe">
                            @error('last_name') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Email address</span>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="jane.doe@example.com">
                            @error('email') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Phone number</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="(555) 123-4567">
                            @error('phone') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                        </label>
                    </div>

                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Cover letter</span>
                        <textarea name="cover_letter" rows="5" required class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" placeholder="Tell us why you would be a great agent.">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </label>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Certifications</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Select all that apply.</p>
                            </div>
                        </div>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @php
                                $options = ['CompTIA A+', 'Network+', 'Microsoft Certified', 'AWS Certified', 'Cisco CCNA', 'ITIL Foundation'];
                            @endphp
                            @foreach($options as $option)
                                <label class="inline-flex cursor-pointer items-center rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 shadow-sm transition duration-150 hover:border-blue-400 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-blue-500 dark:hover:bg-gray-800"
                                       :class="selectedCertifications.includes('{{ $option }}') ? 'border-blue-500 bg-blue-600 text-white dark:border-blue-500 dark:bg-blue-600' : ''">
                                    <input type="checkbox" name="certifications[]" value="{{ $option }}" class="mr-3 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" x-model="selectedCertifications">
                                    {{ $option }}
                                </label>
                            @endforeach
                        </div>
                        @error('certifications') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <label class="block">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Resume</span>
                        <input type="file" name="resume" required x-on:change="handleFile($event)" accept=".pdf,.doc,.docx" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm outline-none transition duration-150 file:mr-4 file:rounded-full file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                        <p x-ref="resumeError" class="mt-2 text-sm text-red-500"></p>
                        @error('resume') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
                    </label>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-3 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 text-base font-semibold text-white transition duration-200 hover:from-blue-700 hover:to-blue-800 disabled:cursor-not-allowed disabled:opacity-50" :class="submitting ? 'opacity-70' : ''" :disabled="submitting">
                        <span x-show="!submitting">Submit application</span>
                        <span x-show="submitting" class="inline-flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin"></i>
                            Sending application...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection