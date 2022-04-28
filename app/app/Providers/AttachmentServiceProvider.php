<?php

namespace App\Providers;

use App\Interfaces\Files\FileManagerServiceInterface;
use App\Services\AttachmentService;
use App\Services\Files\FileNameService;
use Illuminate\Support\ServiceProvider;

class AttachmentServiceProvider extends ServiceProvider
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
        $fileManager = $this->app->get('FileService');
        $fileNameService = new FileNameService();

        $this->app->singleton(
            'attachments','App\Services\AttachmentService',
            function () use ($fileManager, $fileNameService) {
                return new AttachmentService($fileManager, $fileNameService);
        }
        );
    }
}
