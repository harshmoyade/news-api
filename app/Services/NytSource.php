<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NytSource implements NewsSourceInterface
{
    public function fetch(): array
    {
        $response = Http::get(
            config('services.nyt.base_url') . '/topstories/v2/home.json',
            [
                'api-key' => config('services.nyt.key'),
            ]
        );

        if (!$response->successful()) {
            return [];
        }

        $results = $response->json('results', []);

        return collect($results)->map(function ($item) {

            $image = $item['multimedia'][0]['url'] ?? null;

            return [
                'title' => $item['title'],
                'description' => $item['abstract'] ?? null,
                'content' => null,
                'author' => $item['byline'] ?? null,
                'source' => 'nytimes',
                'category' => $item['section'] ?? null,
                'url' => $item['url'],
                'image_url' => $image,
                'published_at' => isset($item['published_date'])
                    ? Carbon::parse($item['published_date'])
                    : now(),
            ];
        })->toArray();
    }
}
