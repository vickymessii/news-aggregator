<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Article API",
 *     description="API documentation for managing articles"
 * )
 */
class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get a list of articles",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Search keyword for filtering articles",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter by source",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by publication date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Breaking News"),
     *                      @OA\Property(property="content", type="string", example="This is the content of the article."),
     *                      @OA\Property(property="source", type="string", example="CNN"),
     *                      @OA\Property(property="category", type="string", example="Politics"),
     *                      @OA\Property(property="author", type="string", example="John Doe"),
     *                      @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *              )
     *             ),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=100)
     *         )
     *     )
     * )
     */

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
    /**
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     summary="Get article details",
     *     description="Retrieve details of a specific article by ID.",
     *     operationId="getArticle",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Breaking News"),
     *             @OA\Property(property="content", type="string", example="This is the content of the article."),
     *             @OA\Property(property="source", type="string", example="CNN"),
     *             @OA\Property(property="category", type="string", example="Politics"),
     *             @OA\Property(property="author", type="string", example="John Doe"),
     *             @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     )
     * )
     */

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }
}

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     description="Article schema",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking News"),
 *     @OA\Property(property="content", type="string", example="This is the content of the article."),
 *     @OA\Property(property="source", type="string", example="CNN"),
 *     @OA\Property(property="category", type="string", example="Politics"),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
 * )
 */
