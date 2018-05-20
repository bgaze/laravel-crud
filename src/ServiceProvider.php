<?php

namespace Bgaze\Crud;

use Illuminate\Support\ServiceProvider as Base;
use Bgaze\Crud\Theme\Crud;

class ServiceProvider extends Base {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Publish configuration.
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'crud');

        // Register & publish default theme views.
        $this->loadViewsFrom(__DIR__ . '/Theme/views', Crud::views());
        $this->publishes([__DIR__ . '/Theme/views' => resource_path('views/vendor/' . Crud::views())]);

        // Register commands.
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ControllerMakeCommand::class,
                Console\MigrateMakeCommand::class,
                Console\ModelMakeCommand::class,
                Console\RequestMakeCommand::class,
                Console\ViewsMakeCommand::class,
                Console\FactoryMakeCommand::class,
                Console\CrudMakeCommand::class,
                Console\RelationMakeCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        // Merge configuration.
        $this->mergeConfigFrom(__DIR__ . '/config/definitions.php', 'crud-definitions');
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');

        // Register default theme class.
        $this->app->bind(Crud::name(), function ($app, $parameters) {
            return new Crud($app->make('Illuminate\Filesystem\Filesystem'), $parameters['model'], $parameters['plural'] ?: null);
        });
    }

}
