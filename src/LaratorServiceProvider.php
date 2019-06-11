<?php

namespace Syahrulyusuf97\Larator;

use Illuminate\Support\ServiceProvider;

class LaratorServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Syahrulyusuf97\Larator\Commands\GenerateDashboard',
        'Syahrulyusuf97\Larator\Commands\MakeUserAdmin',
        'Syahrulyusuf97\Larator\Commands\MakeUserDev',
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register our controller
        $this->app->make('Syahrulyusuf97\Larator\Http\Controllers\CreateDashboard');
        $this->app->make('Syahrulyusuf97\Larator\Http\Controllers\CreateUser');
        //register commands
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
        // $this->publishes([
        //     __DIR__.'/Helpers/*' => base_path('app/Helpers'),
        // ]);
        // $this->commands([
        //     \Vendor\Package\Commands\FooCommand ::class,
        // ]);
    }
}
