<?php

namespace App\Providers;

use App\Services\RabbitService;
use Illuminate\Support\ServiceProvider;

class RabbitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RabbitService::class, function ($app) {
            return new RabbitService();
        });
    }

    public function boot(): void
    {
        //
    }
}
