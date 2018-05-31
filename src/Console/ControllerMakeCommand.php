<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Core\GeneratorCommand;
use Bgaze\Crud\Core\Crud;

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
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD Controller generator";
    }

    /**
     * TODO
     */
    protected function files() {
        return ['controllerPath'];
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function summary() {
        return " <fg=green>Routes will be added to :</> "
                . str_replace(base_path() . '/', '', $this->crud->routesPath())
                . "\n" . parent::summary();
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
        $this->dl('Controller class created', $path);
    }

    /**
     * TODO
     * 
     */
    public function writeRoutes() {
        $stub = $this->crud->populateStub('routes');

        $path = $this->crud->routesPath();
        
        $this->crud->files->append($path, $stub);

        $this->info(" Routes added to :<fg=white> " . str_replace(base_path() . '/', '', $path) . "</>");
    }

}
