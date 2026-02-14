<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticalResource;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                    ->orWhere('content', 'like', "%{$request->q}%");
            });
        }

        if ($request->filled('source')) {
            $query->whereIn('source', explode(',', $request->source));
        }

        if ($request->filled('category')) {
            $query->whereIn('category', explode(',', $request->category));
        }

        if ($request->filled('author')) {
            $query->where('author', 'like', "%{$request->author}%");
        }

        if ($request->filled('from')) {
            $query->whereDate('published_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('published_at', '<=', $request->to);
        }

        return ArticalResource::collection(
            $query->orderByDesc('published_at')->paginate(50)
        );

    }

    public function categories()
    {
        $query = Article::select('category')->whereNotNull('category')->distinct()->orderBy('category', 'ASC')->get();
        return response()->json($query);
    }
}
