<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class ControllerMakeCommand extends GeneratorCommand {

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
     * TODO
     * 
     * @param Crud $crud
     */
    protected function build(Crud $crud) {
        // Write controller file.
        $this->writeController($crud);

        // Write routes.
        $this->writeRoutes($crud);
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $crud
     */
    public function writeController($crud) {
        $path = $crud->generatePhpFile('controller', $crud->controllerPath());
        $this->info("Controller class created : <fg=white>{$path}</>");
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $crud
     */
    public function writeRoutes($crud) {
        $stub = $crud->stub('routes');

        $crud
                ->replace($stub, 'ModelWithParents')
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'PluralWithParentsKebabDot')
                ->replace($stub, 'PluralWithParentsKebabSlash')
        ;

        $path = $crud->routesPath();
        $crud->files->append($path, $stub);

        $this->info("Routes added to <fg=white>{$path}</>");
    }

}
