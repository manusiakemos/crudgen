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
        $this->publishes([
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),
            __DIR__ . '/../routes/crud.php' => base_path('routes/crud.php'),
            __DIR__ . '/../resources/views' => resource_path('views/vendor/crudgen'),
            __DIR__ . '/../stubs' => base_path('stubs/vendor/crudgen'),
            __DIR__ . '/../database/crudgen.json' => database_path('json/crudgen.json'),
            __DIR__ . '/../public' => public_path('vendor/crudgen'),
            __DIR__ . '/../config/crud.php' => config_path('crud.php'),
            __DIR__ . '/../helper' => app_path('helper'),
            __DIR__ . '/Http/Controllers/CrudController.php' => app_path('Http/Controllers/CrudController.php'),
        ], 'crudgen');

        $this->publishes([
            __DIR__ . '/../src/View/Components' => app_path('View/Components'),
            __DIR__ . '/../resources/views/components' => resource_path('views/vendor/crudgen/components'),
        ], 'component');
    }
}
