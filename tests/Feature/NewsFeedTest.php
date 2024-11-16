<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Preference;
use App\Models\Article;

class NewsFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_personalized_news_feed_requires_authentication()
    {
        $response = $this->getJson('/api/news-feed');
        $response->assertStatus(401);
    }

    public function test_personalized_news_feed_returns_articles_based_on_preferences()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $preference = Preference::factory()->create([
            'user_id' => $user->id,
            'sources' => ['BBC News'],
            'categories' => ['Technology'],
        ]);

        Article::factory()->create([
            'title' => 'Tech News',
            'source' => 'BBC News',
            'category' => 'Technology',
        ]);

        Article::factory()->create([
            'title' => 'World News',
            'source' => 'CNN',
            'category' => 'World',
        ]);

        $response = $this->getJson('/api/news-feed');
        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'Tech News']);
    }
}
