<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;

class ControllerMakeCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:controller 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD controller class related to a Model';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle() {
        // Get CRUD theme.
        $theme = $this->getTheme();

        // Write controller file.
        $this->writeController($theme);

        // Write routes.
        $this->writeRoutes($theme);
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    public function writeController($theme) {
        $path = $theme->generatePhpFile('controller', $theme->controllerPath());
        $this->info("Controller class created : <fg=white>{$path}</>");
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    public function writeRoutes($theme) {
        $stub = $theme->stub('routes');
        $path = $theme->routesPath();

        $theme
                ->replace($stub, 'ModelWithParents')
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'PluralWithParentsKebabDot')
                ->replace($stub, 'PluralWithParentsKebabSlash')
        ;

        $theme->files->append($path, $stub);

        $this->info("Routes added to <fg=white>{$path}</>");
    }

}
