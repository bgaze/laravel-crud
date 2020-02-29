<?php

namespace Bgaze\Crud\Themes\Classic;

use Illuminate\Support\ServiceProvider as Base;

/**
 * The Classic theme service provider
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
        $this->publishes([__DIR__ . '/Config/crud-classic.php' => config_path('crud-classic.php')], 'crud-config');

        // Register & publish default theme views.
        $this->loadViewsFrom(__DIR__ . '/Views', 'crud-classic');
        $this->publishes([__DIR__ . '/Views' => resource_path('views/vendor/crud-classic')], 'crud-classic-views');
    }


    /**
     * Register the theme services.
     *
     * @return void
     */
    public function register()
    {
        // Merge theme configuration.
        $this->mergeConfigFrom(__DIR__ . '/Config/crud-classic.php', 'crud-classic');

        // Register theme console command.
        if ($this->app->runningInConsole()) {
            $this->commands(Command::class);
        }
    }
}
