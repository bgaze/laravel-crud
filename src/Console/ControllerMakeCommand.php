<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Core\GeneratorCommand;

/**
 * Generate a CRUD controller class.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 */
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
     * The message to display when the command is ran.
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD Controller generator";
    }

    /**
     * An array of CRUD method to execute in order to check that no file to generate already exists.
     * 
     * @return array
     */
    protected function files() {
        return ['controllerPath'];
    }

    /**
     * Generate a summary of generator's actions.
     * 
     * If some files to generate already exists, an eroor is raised, 
     * otherwise a formatted summary of generated files is returned.
     * 
     * @return string
     * @throws \Exception
     */
    protected function summary() {
        return " <fg=green>Routes will be added to :</> "
                . str_replace(base_path() . '/', '', $this->crud->routesPath())
                . "\n" . parent::summary();
    }

    /**
     * Build the files.
     * 
     * @return void
     */
    protected function build() {
        // Write controller file.
        $this->writeController();

        // Write routes.
        $this->writeRoutes();
    }

    /**
     * Build the Controller file.
     * 
     */
    public function writeController() {
        $path = $this->crud->generatePhpFile('controller', $this->crud->controllerPath());
        $this->dl('Controller class created', $path);
    }

    /**
     * Append routes to routes file.
     * 
     */
    public function writeRoutes() {
        $stub = $this->crud->populateStub('routes');

        $path = $this->crud->routesPath();

        $this->crud->files->append($path, $stub);

        $this->info(" Routes added to :<fg=white> " . str_replace(base_path() . '/', '', $path) . "</>");
    }

}
