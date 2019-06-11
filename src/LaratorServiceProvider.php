<?php

namespace Syahrulyusuf97\Larator\Providers;

use Illuminate\Support\ServiceProvider;

class LaratorServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Syahrulyusuf97\Larator\commands\GenerateDashboard',
        'Syahrulyusuf97\Larator\commands\MakeUserAdmin',
        'Syahrulyusuf97\Larator\commands\MakeUserDev',
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register our controller
        // $this->app->make('Devdojo\Calculator\CalculatorController');
        // $this->app->register('syahrulyusuf97\larator\provider\LaratorServiceProvider');
        $this->commands($this->commands);
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
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        //to publish
        //vendor:publish
        // $this->publishes([
        //     __DIR__.'/path/to/config/package.php' => config_path('package.php'),
        // ]);
        // $this->publishes([
        //     __DIR__.'/views' => base_path('resources/views/wisdmlabs/todolist'),
        // ]);
        $this->publishes([
            __DIR__.'/Helpers/*' => base_path('app/Helpers'),
        ]);
    }
}
