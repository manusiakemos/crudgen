<?php

namespace Manusiakemos\Crudgen;

use Illuminate\Support\ServiceProvider;

class CrudgenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'crudgen');

        $this->publishes([
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/crudgen'),
            __DIR__.'/../stubs' => base_path('stubs/vendor/crudgen'),
            __DIR__.'/../database/crudgen.json' => database_path('json/crudgen.json'),
            __DIR__.'/../public' => public_path('vendor/crudgen'),
            __DIR__.'/../helper' => app_path('helper'),
        ], 'crudgen');
    }
}
