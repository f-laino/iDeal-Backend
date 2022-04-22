<?php

namespace App\Providers;

use App\Services\CdkService;
use Illuminate\Support\ServiceProvider;

class CdkServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("App\Interfaces\CdkServiceInterface", function () {
            return new CdkService(
                config('cdk.client_id'),
                config('cdk.client_secret'),
                config('cdk.url'),
                config('cdk.api_version'),
                config('cdk.service')
            );
        });
    }


}
