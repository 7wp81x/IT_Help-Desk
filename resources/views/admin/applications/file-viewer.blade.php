@extends('layouts.app')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-900 shadow rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Preview {{ strtoupper($fileExt) }} File</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $fileName }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="bi bi-arrow-left"></i> Back to Application
                    </a>
                    <a href="{{ route('admin.applications.download', ['application' => $application->id, 'fileType' => $fileType]) }}" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2 text-sm font-medium text-white hover:from-indigo-700 hover:to-blue-700">
                        <i class="bi bi-download"></i> Download File
                    </a>
                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        <i class="bi bi-box-arrow-up-right"></i> Open Raw
                    </a>
                </div>
            </div>

            <div class="px-6 py-6">
                @if($viewerUrl)
                    <div class="rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800">
                        <iframe src="{{ $viewerUrl }}" class="w-full min-h-[80vh] bg-white" frameborder="0" allowfullscreen></iframe>
                    </div>
                @elseif(in_array($fileExt, ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp']))
                    <div class="rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 bg-black">
                        <img src="{{ $fileUrl }}" alt="{{ $fileName }}" class="w-full object-contain" />
                    </div>
                @elseif($fileExt === 'txt')
                    <div class="rounded-3xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-6">
                        <pre class="whitespace-pre-wrap break-words text-sm text-gray-700 dark:text-gray-200">{{ $fileContent }}</pre>
                    </div>
                @else
                    <div class="rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-950 p-10 text-center">
                        <p class="text-base font-medium text-gray-900 dark:text-white">This file type cannot be rendered directly in your browser.</p>
                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Use Download or Open Raw to view it in an external app.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
