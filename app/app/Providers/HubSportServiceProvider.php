<?php

namespace App\Providers;

use App\Services\HubSpotService;
use Illuminate\Support\ServiceProvider;


class HubSportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Interfaces\HubSpotServiceInterface', function () {
            return new HubSpotService(config('hubspot.api_key'), config('hubspot.portal'), config('hubspot.agent_form'));
        });
    }
}
