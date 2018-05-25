<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class RequestMakeCommand extends GeneratorCommand {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:request 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}
        {--c|content=* : The list of Model\'s fields (signature syntax).}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD form request class';

    /**
     * TODO
     */
    protected function files() {
        return ['requestPath'];
    }

    /**
     * TODO
     * 
     */
    protected function build() {
        // Write request file.
        $path = $this->crud->generatePhpFile('request', $this->crud->requestPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#RULES', $crud->content->toRequest());
            return $stub;
        });

        // Show success message.
        $this->info(" Request class created : <fg=white>$path</>");
    }

}
