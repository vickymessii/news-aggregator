<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     summary="Set user preferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *     description="Store or update user preferences for news feed filtering (sources, categories, authors).",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"CNN", "BBC"}),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Politics", "Technology"}),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Smith"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Preferences stored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=42),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"CNN", "BBC"}),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Politics", "Technology"}),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Smith"}),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", additionalProperties=@OA\Property(type="array", @OA\Items(type="string")))
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
    public function store(Request $request)
    {
        $request->validate([
            'sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $user = Auth::user();

        $preference = Preference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'sources' => $request->input('sources'),
                'categories' => $request->input('categories'),
                'authors' => $request->input('authors'),
            ]
        );

        return response()->json($preference, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/preferences",
     *     summary="Get user preferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *     description="Retrieve the preferences set by the authenticated user.",
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=42),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"CNN", "BBC"}),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Politics", "Technology"}),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Smith"}),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Preferences not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No preferences found for the user.")
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
    public function show()
    {
        $user = Auth::user();
        $preference = Preference::where('user_id', $user->id)->firstOrFail();
        return response()->json($preference);
    }
}
