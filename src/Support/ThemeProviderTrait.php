<?php

namespace Bgaze\Crud\Support;

use Bgaze\Crud\Core\Command;

/**
 * Register easily CRUD themes from service providers.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 */
trait ThemeProviderTrait {

    /**
     * Register a new CRUD theme
     * 
     * @param string $class         The CRUD class to use
     */
    protected function registerTheme($class) {
        // Check if theme is enabled.
        $name = call_user_func("{$class}::name");
        if (!config("crud.{$name}.enabled", true)) {
            return;
        }

        // Register & publish default theme views.
        $views = call_user_func("{$class}::views");
        if ($views) {
            $viewsNamespace = call_user_func("{$class}::viewsNamespace");
            $this->loadViewsFrom($views, $viewsNamespace);
            $this->publishes([$views => resource_path("views/vendor/{$viewsNamespace}")], "{$viewsNamespace}-views");
        }

        // Register commands.
        if ($this->app->runningInConsole()) {
            // Register theme class.
            $this->app->bind("crud.theme.{$name}.class", function ($app, $parameters) use ($class) {
                return new $class($parameters[0]);
            });

            // Register theme command.
            $this->app->singleton("crud.theme.{$name}.command", function () use ($class) {
                return new Command($class);
            });
            $this->commands("crud.theme.{$name}.command");
        }
    }

}
