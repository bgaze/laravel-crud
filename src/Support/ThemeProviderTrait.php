<?php

namespace Bgaze\Crud\Support;

use Bgaze\Crud\Core\Command;

/**
 *
 * @author bgaze
 */
trait ThemeProviderTrait {

    protected function registerTheme($class, $description, $views = false) {
        // Register & publish default theme views.
        if ($views) {
            $viewsNamespace = call_user_func("{$class}::views");
            $this->loadViewsFrom($views, $viewsNamespace);
            $this->publishes([$views => $viewsNamespace]);
        }

        // Register commands.
        if ($this->app->runningInConsole()) {
            $name = call_user_func("{$class}::name");

            // Register theme class.
            $this->app->bind("crud.theme.{$name}.class", function ($app, $parameters) use ($class) {
                return new $class($app->make('Illuminate\Filesystem\Filesystem'), $parameters[0]);
            });

            // Register theme command.
            $this->app->singleton("crud.theme.{$name}.command", function () use ($class, $description) {
                return new Command($class, $description);
            });
            $this->commands("crud.theme.{$name}.command");
        }
    }

}
