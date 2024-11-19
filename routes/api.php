<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsFeedController;
use App\Http\Controllers\PreferenceController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes (no authentication required)
// Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
// });

Route::middleware('auth:sanctum')->group(function () {
    // Preferences
    Route::prefix('preferences')->group(function () {
        Route::post('/', [PreferenceController::class, 'store']);  // Save preferences
        Route::get('/', [PreferenceController::class, 'show']);   // Get preferences
    });

   // Personalized news feed
   Route::get('news-feed', [NewsFeedController::class, 'personalized']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});


// Article routes (no authentication required for listing and viewing articles)
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);     // Get list of articles
    Route::get('{id}', [ArticleController::class, 'show']);    // Get article details by ID
});
