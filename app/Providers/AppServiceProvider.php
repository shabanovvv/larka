<?php

namespace App\Providers;

use App\Repositories\CacheTechnologyRepository;
use App\Repositories\EloquentTechnologyRepository;
use App\Repositories\TechnologyRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TechnologyRepositoryInterface::class, function ($app) {
            // Декоратор для кэша: CacheTechnologyRepository -> EloquentTechnologyRepository
            return new CacheTechnologyRepository(
                new EloquentTechnologyRepository()
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
