<?php

namespace Bgaze\Crud;

use Illuminate\Support\ServiceProvider as Base;
use Bgaze\Crud\Support\ThemeProviderTrait;
use Bgaze\Crud\Theme\Crud;

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
        $this->publishes([__DIR__ . '/config/crud.php' => config_path('crud.php')], 'crud');

        // Register & publish default theme.
        $this->registerTheme(Crud::class, 'The default theme for CRUD generator', __DIR__ . '/Theme/Views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        // Check that Tidy extension is loaded.
        if (!extension_loaded('tidy')) {
            throw new \Exception('bgaze/laravel-crud requires Tidy extension, please enable it.');
        }

        // Merge definitions.
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
