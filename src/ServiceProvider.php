<?php

namespace Bgaze\Crud;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Bgaze\Crud\Themes\DefaultTheme\Crud;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Publish configuration.
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'crud');

        // Register & publish default theme views.
        $this->loadViewsFrom(__DIR__ . '/Themes/DefaultTheme/views', 'crud-default');
        $this->publishes([__DIR__ . '/Themes/DefaultTheme/views' => resource_path('views/vendor/crud-default')]);

        // Register commands.
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ControllerMakeCommand::class,
                Console\MigrateMakeCommand::class,
                Console\ModelMakeCommand::class,
                Console\RequestMakeCommand::class,
                Console\ViewsMakeCommand::class,
                Console\RelationMakeCommand::class,
                Console\CrudMakeCommand::class,
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
        $this->app->bind('CrudDefault', function ($app, $parameters) {
            return new Crud($app->make('Illuminate\Filesystem\Filesystem'), $parameters['model'], $parameters['plural'] ?: null);
        });
    }

}
