<?php

namespace Syahrulyusuf97\Larator\Providers;

use Illuminate\Support\ServiceProvider;

class LaratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register our controller
        // $this->app->make('Devdojo\Calculator\CalculatorController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //include __DIR__.'/routes/routes.php'; atau
        //$this->loadRoutesFrom(__DIR__.'/routes/web.php');
        //load views
        //$this->loadViewsFrom(__DIR__.'/resources/views', 'contactform');
        //load migrations
        // /$this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        //to publish
        //vendor:publish
        // $this->publishes([
        //     __DIR__.'/path/to/config/package.php' => config_path('package.php'),
        // ]);
        // $this->publishes([
        //     __DIR__.'/views' => base_path('resources/views/wisdmlabs/todolist'),
        // ]);
    }
}
