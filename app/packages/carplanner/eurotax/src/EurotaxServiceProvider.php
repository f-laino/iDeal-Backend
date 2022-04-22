<?php

namespace CarPlanner\Eurotax;

use Illuminate\Support\ServiceProvider;


class EurotaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../Config/eurotax.php' => config_path('eurotax.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/eurotax.php', 'eurotax');

        $this->app->singleton('eurotax', function () {
            return new EurotaxService();
        });
    }
}
