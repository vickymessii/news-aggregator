<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from external news APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsAPIKey = env('NEWS_API_KEY');
        $nycAPIKey = env('NYC_TIMES_API_KEY');
        $guardianAPIKey = env('THE_GURDIAN_API_KEY');

        $newsAPIs = [
            [
                'url' => 'https://newsapi.org/v2/top-headlines?country=us&apiKey=' . $newsAPIKey,
                'source' => 'NewsAPI',
                'parse_key' => 'articles',
            ],
            [
                'url' => 'https://api.nytimes.com/svc/topstories/v2/us.json?api-key=' . $nycAPIKey,
                'source' => 'NYC Times',
                'parse_key' => 'results',
            ],
            [
                'url' => 'https://content.guardianapis.com/search?sections?q=us&api-key=' . $guardianAPIKey . '&show-fields=all',
                'source' => 'The Guardian',
                'parse_key' => 'response.results',
            ],
        ];

        foreach ($newsAPIs as $api) {
            $response = Http::get($api['url']);
            Log::info("response",[$response]);
            if ($response->successful()) {
                $articles = data_get($response->json(), $api['parse_key'], []);
                foreach ($articles as $article) {
                    // Normalize data depending on the source
                    $normalizedArticle = $this->normalizeArticle($article, $api['source']);

                    // Store or update the article
                    Article::updateOrCreate(
                        ['url' => $normalizedArticle['url']],
                        $normalizedArticle
                    );
                }
            } else {
                $this->error("Failed to fetch articles from {$api['source']}.");
            }
        }

        $this->info('Articles fetched successfully!');
    }

    /**
     * Normalize article data based on the API source.
     */
    private function normalizeArticle(array $article, string $source): array
    {
        switch ($source) {
            case 'NYC Times':
                return [
                    'title' => $article['title'] ?? 'No Title',
                    'content' => $article['abstract'] ?? 'No Content',
                    'author' => $article['byline'] ?? 'Unknown',
                    'source' => $source,
                    'category' => $article['section'] ?? 'General',
                    'url' => $article['url'] ?? '',
                    'published_at' => $article['published_date'] ?? now(),
                ];

            case 'NewsAPI':
                return [
                    'title' => $article['title'] ?? 'No Title',
                    'content' => $article['content'] ?? 'No Content',
                    'author' => $article['author'] ?? 'Unknown',
                    // 'source' => $article['source']['name'] ?? 'Unknown',
                    'source' => $source,
                    'category' => 'General', // Modify if categories are needed
                    'url' => $article['url'] ?? '',
                    'published_at' => $article['publishedAt'] ?? now(),
                ];

            case 'The Guardian':
                return [
                    'title' => $article['webTitle'] ?? 'No Title',
                    'content' => $article['fields']['bodyText'] ?? 'No Content',
                    'author' => $article['fields']['byline'] ?? 'Unknown',
                    'source' => $source,
                    'category' => $article['sectionId'] ?? 'General',
                    'url' => $article['webUrl'] ?? '',
                    'published_at' => $article['webPublicationDate'] ?? now(),
                ];

            default:
                return [];
        }
    }
}
