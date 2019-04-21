<?php

namespace Bgaze\Crud;

use Illuminate\Support\Facades\Validator;
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

        // Register table colum name validation rule.
        Validator::extend('table_column', function ($attribute, $value, $parameters, $validator) {
            return preg_match(config('crud.table_column_format'), $value);
        }, '":attribute" must be lowercased, must start with a letter and cannot end with underscore.');

        // Register table colum name validation rule.
        Validator::extend('model_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match(config('crud.model_fullname_format'), $value);
        }, '":attribute" must be a valid Model fullname.');

        // Register & publish default themes.
        $this->registerTheme(Themes\Api\Crud::class);
        $this->registerTheme(Themes\Classic\Crud::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        // Merge package configuration.
        $this->mergeConfigFrom(__DIR__ . '/config/crud.php', 'crud');

        // Validate configuration.
        $dir = config('crud.models-directory', false);
        if ($dir && !empty($dir) && $dir !== true && !preg_match('/^([A-Z][a-z]+)+$/', $dir)) {
            throw new \Exception("Your configuration for 'crud.models-directory' is invalid.\nSpecified value must match /^([A-Z][a-z]+)+$/.");
        }
    }

}
