<?php

namespace App\Providers\Files;

use App\Services\Storages\FileManagerService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider
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
        $s3Client = Storage::disk('files');

        $this->app->singleton('App\Interfaces\Files\FileManagerServiceInterface', function () use ($s3Client) {
            return new FileManagerService($s3Client);
        });
    }
}
