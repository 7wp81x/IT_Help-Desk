@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $article->title }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('user.knowledgebase') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Back to Knowledge Base
            </a>
        </div>

        <!-- Article Card -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-white">
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                        {{ $article->category->name ?? 'Article' }}
                    </span>
                </div>
                <h1 class="text-3xl font-bold mb-4">{{ $article->title }}</h1>
                <div class="flex items-center gap-6 text-purple-100">
                    <span>
                        <i class="bi bi-calendar-event mr-2"></i>
                        {{ $article->created_at->format('F d, Y') }}
                    </span>
                    <span>
                        <i class="bi bi-eye mr-2"></i>
                        {{ $article->views }} views
                    </span>
                    <span>
                        <i class="bi bi-file-text mr-2"></i>
                        Reading time: ~{{ ceil(str_word_count($article->content) / 200) }} min
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($article->content)) !!}
                </div>

                <!-- Article Meta -->
                <div class="border-t border-gray-200 mt-8 pt-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Article Details</h4>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="text-gray-600">Category</dt>
                                    <dd class="font-medium text-gray-900">{{ $article->category->name ?? 'Uncategorized' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-600">Last Updated</dt>
                                    <dd class="font-medium text-gray-900">{{ $article->updated_at->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-600">Status</dt>
                                    <dd>
                                        <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">
                                            Published
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Was this helpful?</h4>
                            <div class="flex items-center gap-3">
                                <button class="flex-1 px-4 py-2 border border-green-300 text-green-700 rounded-lg hover:bg-green-50 transition duration-200 flex items-center justify-center gap-2">
                                    <i class="bi bi-hand-thumbs-up"></i> Yes
                                </button>
                                <button class="flex-1 px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition duration-200 flex items-center justify-center gap-2">
                                    <i class="bi bi-hand-thumbs-down"></i> No
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Need Help Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                    <h4 class="font-semibold text-gray-900 mb-2">Couldn't find what you're looking for?</h4>
                    <p class="text-gray-600 mb-4">Our support team is here to help. Create a ticket and we'll get back to you as soon as possible.</p>
                    <a href="{{ route('user.tickets.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Create a Support Ticket
                    </a>
                </div>
            </div>
        </div>

        <!-- Related Articles -->
        @if($relatedArticles->isNotEmpty())
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($relatedArticles as $related)
                        <a href="{{ route('user.knowledgebase.show', $related) }}" class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 p-6 block">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $related->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $related->content }}</p>
                            <p class="text-xs text-gray-500">
                                <i class="bi bi-eye"></i> {{ $related->views }} views
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
