<?php

namespace Bgaze\Crud;

use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Utils\BladeFormatter;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as Base;

/**
 * The package service provider
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ServiceProvider extends Base
{

    /**
     * Bootstrap the package services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration.
        $this->publishes([__DIR__ . '/Config/crud.php' => config_path('crud.php')], 'crud-config');

        // Register table column name validation rule.
        Validator::extend('table_column', function ($attribute, $value, $parameters, $validator) {
            return preg_match(Definitions::COLUMN_FORMAT, $value);
        }, '":attribute" must be lowercase, must start with a letter and cannot end with underscore.');

        // Register table column name validation rule.
        Validator::extend('model_name', function ($attribute, $value, $parameters, $validator) {
            return preg_match(Definitions::MODEL_NAME_FORMAT, $value);
        }, '":attribute" must be a valid Model full name.');
    }


    /**
     * Register the package services.
     *
     * @return void
     * @throws Exception
     */
    public function register()
    {
        // Merge package configuration.
        $this->mergeConfigFrom(__DIR__ . '/Config/crud.php', 'crud');

        // Validate configuration.
        $dir = config('crud.models-directory', false);
        if ($dir !== null && $dir !== false && $dir !== true && !preg_match('/^([A-Z][a-z0-9]+)+$/', $dir)) {
            throw new Exception("Your configuration for 'crud.models-directory' is invalid.\nSpecified value must be 'true', 'false', 'null' or match /^([A-Z][a-z0-9]+)+$/.");
        }

        // Register Blade formatter service.
        $this->app->singleton(BladeFormatter::class, function ($app) {
            return new BladeFormatter();
        });
    }
}
