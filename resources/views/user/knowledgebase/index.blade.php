@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Knowledge Base
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                        <i class="bi bi-journal-bookmark-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Articles</p>
                        <p class="text-2xl font-bold">{{ $articles->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <i class="bi bi-search text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Categories</p>
                        <p class="text-2xl font-bold">{{ $categories->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="bi bi-fire"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Popular Articles</p>
                        <p class="text-2xl font-bold">{{ $popularArticles->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Search & Filter -->
                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Search & Filter</h3>
                    <form method="GET" class="space-y-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Articles</label>
                            <input type="text" name="search" placeholder="Search..." 
                                   value="{{ request('search') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Most Recent</option>
                                <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title A-Z</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                            Apply Filters
                        </button>
                    </form>
                </div>

                <!-- Popular Articles -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Popular Articles</h3>
                    <ul class="space-y-3">
                        @foreach($popularArticles as $popular)
                            <li>
                                <a href="{{ route('user.knowledgebase.show', $popular) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm line-clamp-2">
                                    {{ $popular->title }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-eye"></i> {{ $popular->views }} views
                                </p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Articles List -->
                <div class="space-y-4">
                    @forelse($articles as $article)
                        <a href="{{ route('user.knowledgebase.show', $article) }}" class="bg-white rounded-xl shadow hover:shadow-lg transition-shadow duration-200 p-6 block">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 hover:text-blue-600">{{ $article->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span class="inline-block">
                                            <i class="bi bi-tag"></i> {{ $article->category->name ?? 'Uncategorized' }}
                                        </span>
                                        <span class="inline-block ml-4">
                                            <i class="bi bi-eye"></i> {{ $article->views }} views
                                        </span>
                                    </p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium whitespace-nowrap">
                                    {{ ucfirst(str_replace('_', ' ', $article->category->type ?? 'Article')) }}
                                </span>
                            </div>
                            <p class="text-gray-600 line-clamp-2">{{ $article->content }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    Created {{ $article->created_at->diffForHumans() }}
                                </span>
                                <span class="text-blue-600 text-sm font-medium">Read Article →</span>
                            </div>
                        </a>
                    @empty
                        <div class="bg-white rounded-xl shadow p-12 text-center">
                            <i class="bi bi-journal-x text-4xl text-gray-300 block mb-4"></i>
                            <p class="text-gray-500 text-lg">No articles found matching your search.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-8">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
