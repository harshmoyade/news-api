<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiSource implements NewsSourceInterface
{
    public function fetch(): array
    {
        $response = Http::get(
            config('services.newsapi.base_url') .'/top-headlines',
            [
                'apiKey' => config('services.newsapi.key'),
                'language' => 'en',
            ]
        );

        if (!$response->successful()) {
            return [];
        }

        $articles = $response->json('articles', []);

        return collect($articles)->map(fn ($article) => [
            'title' => $article['title'],
            'description' => $article['description'] ?? null,
            'content' => $article['content'] ?? null,
            'author' => $article['author'] ?? null,
            'source' => 'newsapi',
            'category' => null,
            'url' => $article['url'],
            'image_url' => $article['urlToImage'] ?? null,
            'published_at' => isset($article['publishedAt'])
                ? Carbon::parse($article['publishedAt'])
                : now(),
        ])->toArray();
    }
}
