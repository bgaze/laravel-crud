<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

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
     * TODO
     * 
     * @param Crud $crud
     */
    protected function build(Crud $crud) {
        // Get layout.
        if ($this->option('layout')) {
            $layout = $this->option('layout');
        } elseif (config('crud.layout')) {
            $layout = config('crud.layout');
        } else {
            $layout = $crud::layout();
        }

        // Write index view.
        $this->writeIndexView($crud, $layout);

        // Write show view.
        $this->writeShowView($crud, $layout);

        // Write create view.
        $this->writeCreateView($crud, $layout);

        // Write edit view.
        $this->writeEditView($crud, $layout);
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     * @param string $layout
     */
    protected function writeIndexView(Crud $crud, $layout) {
        $path = $crud->generatePhpFile('views.index', $crud->indexViewPath(), function(Crud $crud, $stub) use($layout) {
            $crud
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#THEAD', $crud->content->toTableHead())
                    ->replace($stub, '#TBODY', $crud->content->toTableBody())
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Index view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     * @param string $layout
     */
    protected function writeShowView(Crud $crud, $layout) {
        $path = $crud->generatePhpFile('views.show', $crud->showViewPath(), function(Crud $crud, $stub) use($layout) {
            $crud
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#CONTENT', $crud->content->toShow())
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Show view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     * @param string $layout
     */
    protected function writeCreateView(Crud $crud, $layout) {
        $path = $crud->generatePhpFile('views.create', $crud->createViewPath(), function(Crud $crud, $stub) use($layout) {
            $crud
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#FORM', $crud->content->toForm(true));

            return $stub;
        });

        // Show success message.
        $this->info("Create view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     * @param string $layout
     */
    protected function writeEditView(Crud $crud, $layout) {
        $path = $crud->generatePhpFile('views.edit', $crud->editViewPath(), function(Crud $crud, $stub) use($layout) {
            $crud
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#FORM', $crud->content->toForm(false))
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Edit view created : <fg=white>$path</>");
    }

}
