<?php

namespace Bgaze\Crud\Themes\DefaultTheme;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Bgaze\Crud\Themes\DefaultTheme\Crud;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Register default theme views.
        $this->loadViewsFrom(__DIR__ . '/views', 'crud-default');

        // Publish theme views.
        $this->publishes([__DIR__ . '/views' => resource_path('views/vendor/crud-default')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        // Register default theme singleton.
        $this->app->singleton('crud-default', function ($app) {
            return new Crud($app->make('Illuminate\Filesystem\Filesystem'));
        });
    }

}
