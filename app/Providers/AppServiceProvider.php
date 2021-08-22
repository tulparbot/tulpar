<?php

namespace App\Providers;

use App\Extensions\FileStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {
            Cache::extend('file', function ($app) {
                return Cache::repository(new FileStore($app['files'], config('cache.stores.file.path'), null));
            });
        });
    }
}
