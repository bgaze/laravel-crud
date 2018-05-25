<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

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
     * TODO
     */
    protected function files() {
        return ['factoryPath'];
    }

    /**
     * TODO
     * 
     */
    protected function build() {
        // Write request file.
        $path = $this->crud->generatePhpFile('factory', $this->crud->factoryPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#CONTENT', $crud->content->toFactory());
            return $stub;
        });

        // Show success message.
        $this->info(" Factory file created : <fg=white>$path</>");
    }

}
