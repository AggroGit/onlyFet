<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Algo;

class testServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //
        $this->app->bind(Algo::class, function ($app) {
          return new Algo("Datos cargados desde service provider");
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
