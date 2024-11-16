<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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
        $newsAPIs = [

            // will add API's here
        ];

        foreach ($newsAPIs as $url) {
            $response = Http::get($url);

            if ($response->successful()) {
                $articles = $response->json()['articles'];
                foreach ($articles as $article) {
                    Article::updateOrCreate(
                        ['url' => $article['url']],
                        [
                            'title' => $article['title'],
                            'content' => $article['content'],
                            'author' => $article['author'],
                            'source' => $article['source']['name'],
                            'category' => 'General', // Modify as needed
                            'url' => $article['url'],
                            'published_at' => $article['publishedAt'],
                        ]
                    );
                }
            }
        }

        $this->info('Articles fetched successfully!');
    }
}
