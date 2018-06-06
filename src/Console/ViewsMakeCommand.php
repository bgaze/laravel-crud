<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Core\GeneratorCommand;
use Bgaze\Crud\Core\Crud;

/**
 * Generate CRUD views.
 * 
 * @author bgaze <benjamin@bgaze.fr>
 */
class ViewsMakeCommand extends GeneratorCommand {

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:views 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|timestamps : Add timestamps directives}
        {--s|soft-deletes : Add soft delete directives}
        {--c|content=* : The list of Model\'s fields (signature syntax).}
        {--theme= : The theme to use to generate CRUD.}
        {--layout= : The layout to extend into generated views.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD views';

    /**
     * The message to display when the command is ran.
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD views generator";
    }

    /**
     * An array of CRUD method to execute in order to check that no file to generate already exists.
     * 
     * @return array
     */
    protected function files() {
        return ['indexViewPath', 'showViewPath', 'createViewPath', 'editViewPath'];
    }

    /**
     * Build the files.
     * 
     * @return void
     */
    protected function build() {
        // Write index view.
        $this->writeIndexView();

        // Write show view.
        $this->writeShowView();

        // Write create view.
        $this->writeCreateView();

        // Write edit view.
        $this->writeEditView();
    }

    /**
     * Build the index view.
     * 
     * @return void
     */
    protected function writeIndexView() {
        $path = $this->crud->generatePhpFile('views.index', $this->crud->indexViewPath(), function(Crud $crud, $stub) {
            $crud
                    ->replace($stub, '#THEAD', $crud->content->toTableHead())
                    ->replace($stub, '#TBODY', $crud->content->toTableBody())
            ;

            return $stub;
        });

        $this->dl('Index view created', $path);
    }

    /**
     * Build the show view.
     * 
     * @return void
     */
    protected function writeShowView() {
        $path = $this->crud->generatePhpFile('views.show', $this->crud->showViewPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#CONTENT', $crud->content->toShow());

            return $stub;
        });

        $this->dl('Show view created', $path);
    }

    /**
     * Build the create view.
     * 
     * @return void
     */
    protected function writeCreateView() {
        $path = $this->crud->generatePhpFile('views.create', $this->crud->createViewPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#FORM', $crud->content->toForm(true));

            return $stub;
        });

        $this->dl('Create view created', $path);
    }

    /**
     * Build the edit view.
     * 
     * @return void
     */
    protected function writeEditView() {
        $path = $this->crud->generatePhpFile('views.edit', $this->crud->editViewPath(), function(Crud $crud, $stub) {
            $crud->replace($stub, '#FORM', $crud->content->toForm(false));

            return $stub;
        });

        $this->dl('Edit view created', $path);
    }

}
