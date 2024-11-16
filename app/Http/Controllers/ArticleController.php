<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve search parameters
        $keyword = $request->input('keyword');
        $category = $request->input('category');
        $source = $request->input('source');
        $date = $request->input('date');

        // Query the articles with conditions
        $query = Article::query();

        if ($keyword) {
            $query->where('title', 'like', "%{$keyword}%")
                ->orWhere('content', 'like', "%{$keyword}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($source) {
            $query->where('source', $source);
        }

        if ($date) {
            $query->whereDate('published_at', $date);
        }

        // Paginate the results
        $articles = $query->paginate(10);

        return response()->json($articles);
    }
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }
}
