<?php

namespace Bgaze\Crud\Themes\Api;

use Illuminate\Support\ServiceProvider as Base;

/**
 * The Api theme service provider
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ServiceProvider extends Base
{

    /**
     * Bootstrap the theme services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish theme configuration.
        $this->publishes([__DIR__ . '/Config/crud-api.php' => config_path('crud-api.php')], 'crud-config');
    }


    /**
     * Register the theme services.
     *
     * @return void
     */
    public function register()
    {
        // Merge theme configuration.
        $this->mergeConfigFrom(__DIR__ . '/Config/crud-api.php', 'crud-api');

        // Register theme console command.
        if ($this->app->runningInConsole()) {
            $this->commands(Command::class);
        }
    }
}
