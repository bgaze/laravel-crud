<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Core\GeneratorCommand;
use Bgaze\Crud\Core\Crud;

class ModelMakeCommand extends GeneratorCommand {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:model 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|timestamps : Add timestamps directives}
        {--s|soft-deletes : Add soft delete directives}
        {--c|content=* : The list of Model\'s fields (signature syntax).}
        {--theme= : The theme to use to generate CRUD.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD Eloquent model class';
    /**
     * TODO
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD Model generator";
    }

    /**
     * TODO
     */
    protected function files() {
        return ['modelPath'];
    }

    /**
     * TODO
     * 
     */
    protected function build() {
        // Write model file.
        $path = $this->crud->generatePhpFile('model', $this->crud->modelPath(), function(Crud $crud, $stub) {
            $crud
                    ->replace($stub, '#TIMESTAMPS', $crud->content->timestamps ? 'public $timestamps = true;' : '')
                    ->replace($stub, '#SOFTDELETE', $crud->content->softDeletes ? 'use \Illuminate\Database\Eloquent\SoftDeletes;' : '')
                    ->replace($stub, '#FILLABLES', $crud->content->toModeleFillables())
                    ->replace($stub, '#DATES', $crud->content->toModeleDates())
            ;

            return $stub;
        });

        // Show success message.
        $this->dl('Model class created', $path);
    }

}
