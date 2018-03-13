<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use \App\Eve\Group;
use \App\Eve\UserService;
use \App\Esi\Manager;

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
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Eve\Group', function ($app) {
          return new Group();
        });
        $this->app->singleton('App\Eve\UserService', function ($app) {
            $manager = $app->make(Manager::class);
            return new UserService($manager);
        });
        $this->app->singleton('App\Eve\Discord', function ($app) {
            return new \App\Eve\Discord;
        });
    }
}
