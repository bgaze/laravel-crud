<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class ModelMakeCommand extends GeneratorCommand {

    use ConsoleHelpersTrait;

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
     * @param Crud $crud
     */
    protected function build(Crud $crud) {
        // Write model file.
        $path = $crud->generatePhpFile('model', $crud->modelPath(), function(Crud $crud, $stub) {
            $crud
                    ->replace($stub, '#TIMESTAMPS', $crud->content->timestamps ? 'public $timestamps = true;' : '')
                    ->replace($stub, '#SOFTDELETE', $crud->content->softDeletes ? 'use Illuminate\Database\Eloquent\SoftDeletes;' : '')
                    ->replace($stub, '#FILLABLES', $crud->content->toModeleFillables())
                    ->replace($stub, '#DATES', $crud->content->toModeleDates())
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Model class created : <fg=white>$path</>");
    }

}
