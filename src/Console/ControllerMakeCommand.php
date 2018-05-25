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
     */
    protected function files() {
        return ['controllerPath'];
    }

    /**
     * TODO
     * 
     */
    protected function build() {
        // Write controller file.
        $this->writeController();

        // Write routes.
        $this->writeRoutes();
    }

    /**
     * TODO
     * 
     */
    public function writeController() {
        $path = $this->crud->generatePhpFile('controller', $this->crud->controllerPath());
        $this->info(" Controller class created : <fg=white>{$path}</>");
    }

    /**
     * TODO
     * 
     */
    public function writeRoutes() {
        $stub = $this->crud->stub('routes');

        $this->crud
                ->replace($stub, 'ModelFullName')
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'PluralsKebabDot')
                ->replace($stub, 'PluralsKebabSlash')
        ;

        $path = $this->crud->routesPath();
        $this->crud->files->append($path, $stub);

        $this->info(" Routes added to <fg=white>{$path}</>");
    }

}
