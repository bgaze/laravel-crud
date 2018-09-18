<?php

namespace Bgaze\Crud;

use Illuminate\Support\ServiceProvider as Base;
use Bgaze\Crud\Support\ThemeProviderTrait;
use Bgaze\Crud\Themes;

/**
 * The package service provider
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ServiceProvider extends Base {

    use ThemeProviderTrait;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Publish configuration.
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'crud-config');

        // Register & publish default themes.
        $this->registerTheme(Themes\Api\Crud::class, 'Generate a REST API CRUD using default theme');
        $this->registerTheme(Themes\Classic\Crud::class, 'Generate a classic CRUD using default theme', __DIR__ . '/Themes/Classic/Views');
        $this->registerTheme(Themes\Vue\Crud::class, 'Generate a Vue.js CRUD using default theme', __DIR__ . '/Themes/Vue/Views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        // Merge crud definitions.
        $this->mergeConfigFrom(__DIR__ . '/config/definitions.php', 'crud-definitions');

        // Merge package configuration.
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');

        // Validate configuration.
        $dir = config('crud.models-directory', false);
        if ($dir && !empty($dir) && $dir !== true && !preg_match('/^([A-Z][a-z]+)+$/', $dir)) {
            throw new \Exception("Your configuration for 'crud.models-directory' is invalid.\nSpecified value must match /^([A-Z][a-z]+)+$/.");
        }
    }

}
