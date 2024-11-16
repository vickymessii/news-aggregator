<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NewsFeedController extends Controller
{
    public function personalized()
    {
        $user = Auth::user();

        $cacheKey = 'user_' . $user->id . '_news_feed';
        $articles = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($user) {
            $preferences = Preference::where('user_id', $user->id)->first();

            if (!$preferences) {
                return [];
            }

            $query = Article::query();

            if ($preferences->sources) {
                $query->whereIn('source', $preferences->sources);
            }

            if ($preferences->categories) {
                $query->whereIn('category', $preferences->categories);
            }

            if ($preferences->authors) {
                $query->whereIn('author', $preferences->authors);
            }

            return $query->orderBy('published_at', 'desc')->paginate(10);
        });

        return response()->json($articles);
    }
}
