<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use app\Services\NewsAggregatorService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsAggregatorService::class, function ($app) {

            $sources = collect(config('news_sources'))
                ->map(fn ($class) => $app->make($class))
                ->toArray();

            return new NewsAggregatorService($sources);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
