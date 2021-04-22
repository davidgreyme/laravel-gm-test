<?php

namespace App\Providers;

use App\Interfaces\ProductInterface;
use App\Interfaces\UserInterface;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            UserInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            ProductInterface::class,
            ProductRepository::class
        );
    }
}
