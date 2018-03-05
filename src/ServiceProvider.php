<?php

namespace Bgaze\Crud;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Publish configuration.
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'config');

        // Load & publish views.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'crud');
        $this->publishes([__DIR__ . '/resources/views' => resource_path('views/crud')], 'views');

        // Load and publish stubs.
        $this->loadViewsFrom(__DIR__ . '/resources/stubs', 'stubs');
        $this->publishes([__DIR__ . '/resources/stubs' => resource_path('stubs/crud')], 'stubs');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Crud\ControllerMakeCommand::class,
                Console\Crud\MigrateMakeCommand::class,
                Console\Crud\ModelMakeCommand::class,
                Console\Crud\RequestMakeCommand::class,
                Console\Crud\ViewsMakeCommand::class,
                Console\Crud\RelationMakeCommand::class,
                Console\Crud\ThemeMakeCommand::class,
                Console\CrudCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/config/crud_dic.php', 'crud_dic');
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');
    }

}
