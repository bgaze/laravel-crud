<?php

namespace Bgaze\Crud\Console;

use Illuminate\Support\Composer;
use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class MigrateMakeCommand extends GeneratorCommand {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:migration 
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
    protected $description = 'Create a new CRUD migration file';

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @param  \Illuminate\Support\Composer  $composer
     * @return void
     */
    public function __construct(Composer $composer) {
        parent::__construct();
        $this->composer = $composer;
    }

    /**
     * TODO
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD Migration generator";
    }

    /**
     * TODO
     */
    protected function files() {
        return ['migrationPath'];
    }

    /**
     * TODO
     * 
     */
    protected function build() {
        // Write migration file.
        $path = $this->crud->generatePhpFile('migration', $this->crud->migrationPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#CONTENT', $crud->content->toMigration());
            return $stub;
        });

        // Update autoload.
        $this->composer->dumpAutoloads();

        // Show success message.
        $this->dl('Migration class created', $path);
    }

}
