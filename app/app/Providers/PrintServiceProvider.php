<?php

namespace App\Providers;

use App\Services\PrintService;
use Illuminate\Support\ServiceProvider;

class PrintServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Interfaces\PrintServiceInterface', function () {
            return new PrintService();
        });
    }
}
