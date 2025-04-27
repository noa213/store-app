<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;


class AppServiceProvider extends ServiceProvider
{
    public function register()
        {
            $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });}
    /**
     * Register any application services.
     */


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
