<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Esi\Api;
use App\Esi\Manager;

class EsiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('\App\Esi\Api', function ($app) {
            return new Api();
        });
        $this->app->singleton('\App\Esi\Manager', function ($app) {
            $esi = $app->make(Api::class);
            return new Manager($esi);
        });

    }
}
