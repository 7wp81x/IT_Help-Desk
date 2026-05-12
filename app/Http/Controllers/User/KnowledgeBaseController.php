<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Models\Category;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = KnowledgeBase::with('category')
            ->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sort options
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
        }

        $articles = $query->paginate(12);
        $categories = Category::whereHas('knowledgeBases', function($q) {
            $q->where('is_active', true);
        })->get();

        // Popular articles
        $popularArticles = KnowledgeBase::where('is_active', true)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        return view('user.knowledgebase.index', compact('articles', 'categories', 'popularArticles'));
    }

    public function show(KnowledgeBase $article)
    {
        if (!$article->is_active) {
            abort(404);
        }

        // Increment views
        $article->increment('views');

        $article->load('category');

        // Related articles
        $relatedArticles = KnowledgeBase::where('is_active', true)
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->orderBy('views', 'desc')
            ->limit(4)
            ->get();

        return view('user.knowledgebase.show', compact('article', 'relatedArticles'));
    }
}
