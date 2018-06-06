<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Core\GeneratorCommand;
use Bgaze\Crud\Core\Crud;

/**
 * Generate a CRUD Factory class.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 */
class FactoryMakeCommand extends GeneratorCommand {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:factory 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}
        {--c|content=* : The list of Model\'s fields (signature syntax).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD model factory file';

    /**
     * The message to display when the command is ran.
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD Factory generator";
    }

    /**
     * An array of CRUD method to execute in order to check that no file to generate already exists.
     * 
     * @return array
     */
    protected function files() {
        return ['factoryPath'];
    }

    /**
     * Build the files.
     * 
     * @return void
     */
    protected function build() {
        // Write request file.
        $path = $this->crud->generatePhpFile('factory', $this->crud->factoryPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#CONTENT', $crud->content->toFactory());
            return $stub;
        });

        // Show success message.
        $this->dl('Factory file created', $path);
    }

}
