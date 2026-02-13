<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Contracts\NewsAggregatorService;

class NewsFetch extends Command
{
    protected $signature = 'news:fetch';

    protected $description = 'Fetch news from all sources and store them';

    public function handle(NewsAggregatorService $service)
    {
        $service->fetchAndStore();

        $this->info('News updated successfully.');
    }
}
