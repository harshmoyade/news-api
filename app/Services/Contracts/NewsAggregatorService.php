<?php

namespace App\Services\Contracts;

use App\Models\Article;
use App\Services\NewsApiSource;
use App\Services\GuardianSource;
use App\Services\NytSource;
class NewsAggregatorService
{
    protected array $sources;

    public function __construct()
    {
        $this->sources = [
            new NewsApiSource(),
            new GuardianSource(),
            new NytSource(),
        ];
    }

    public function fetchAndStore(): void
    {
        foreach ($this->sources as $source) {
            $articles = $source->fetch();

            foreach ($articles as $data) {
                Article::updateOrCreate(
                    ['url' => $data['url']],
                    $data
                );
            }
        }
    }
}
