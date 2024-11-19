<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NewsFeedController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/news-feed/personalized",
     *     summary="Get personalized news feed",
     *     tags={"News Feed"},
     *     security={{"sanctum": {}}},
     *     description="Fetches a personalized news feed based on the authenticated user's preferences (sources, categories, authors). Results are cached for 30 minutes.",
     *     @OA\Response(
     *         response=200,
     *         description="List of personalized articles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Breaking News"),
     *                     @OA\Property(property="content", type="string", example="Article content here..."),
     *                     @OA\Property(property="source", type="string", example="CNN"),
     *                     @OA\Property(property="category", type="string", example="Politics"),
     *                     @OA\Property(property="author", type="string", example="John Doe"),
     *                     @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="last_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
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
