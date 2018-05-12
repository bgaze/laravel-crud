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
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'bgaze-crud-config');

        // Register commands
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
        $this->mergeConfigFrom(__DIR__ . '/config/definitions.php', 'crud-definitions');
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');
    }

}
