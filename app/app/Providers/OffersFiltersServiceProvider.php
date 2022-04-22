<?php

namespace App\Providers;

use App\Services\Offers\FiltersService;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;


class OffersFiltersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Interfaces\FiltersServiceInterface', function () {
            return new FiltersService(new Manager());
        });
    }
}
