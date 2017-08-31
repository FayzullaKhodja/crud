<?php

namespace Khodja\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Khodja\Crud\Commands\MakeCrud',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // $this->publishes([
        //     __DIR__.'/config/upload.php' => config_path('upload.php')
        // ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->app->singleton('crud', function($app) {
            return new Crud;
        });
    }
}
