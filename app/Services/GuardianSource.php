<?php

namespace App\Services;

use App\Services\Contracts\NewsSourceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GuardianSource implements NewsSourceInterface
{
    public function fetch(): array
    {
        $response = Http::get(
            config('services.guardian.base_url') . '/search',
            [
                'api-key' => config('services.guardian.key'),
                'show-fields' => 'trailText,bodyText,thumbnail',
            ]
        );

        if (!$response->successful()) {
            return [];
        }

        $results = $response->json('response.results', []);

        return collect($results)->map(fn ($item) => [
            'title' => $item['webTitle'],
            'description' => $item['fields']['trailText'] ?? null,
            'content' => $item['fields']['bodyText'] ?? null,
            'author' => null,
            'source' => 'guardian',
            'category' => $item['sectionName'] ?? null,
            'url' => $item['webUrl'],
            'image_url' => $item['fields']['thumbnail'] ?? null,
            'published_at' => Carbon::parse($item['webPublicationDate']),
        ])->toArray();
    }
}
