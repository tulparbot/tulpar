<?php

namespace App\Providers;

use App\Extensions\FileStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Tulpar\Extension\Container;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->singleton('extensions', function () {
            return new Container();
        });

        foreach (config('tulpar.extensions') as $extension) {
            app('extensions')->add(new $extension());
        }
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
