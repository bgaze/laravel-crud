<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;

class ViewsMakeCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'crud:views 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}
        {--l|layout= : The layout to extend into generated views.}
        {--index-thead= : The HTML to insert into index table head.}
        {--index-tbody= : The HTML to insert into index table body.}
        {--create-fields= : The HTML to insert into create form.}
        {--edit-fields= : The HTML to insert into edit form.}
        {--show-content= : The HTML to insert into show page.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD views';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle() {
        // Get CRUD theme.
        $theme = $this->getTheme();

        // Get layout.
        if ($this->option('layout')) {
            $layout = $this->option('layout');
        } elseif (config('crud.layout')) {
            $layout = config('crud.layout');
        } else {
            $layout = $theme::layout();
        }

        // Write index view.
        $this->writeIndexView($theme, $layout);

        // Write show view.
        $this->writeShowView($theme, $layout);

        // Write create view.
        $this->writeCreateView($theme, $layout);

        // Write edit view.
        $this->writeEditView($theme, $layout);
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    protected function writeIndexView($theme, $layout) {
        $path = $theme->generatePhpFile('views.index', $theme->indexViewPath(), function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#THEAD', $this->option('index-thead') ?: '')
                    ->replace($stub, '#TBODY', $this->option('index-tbody') ?: '')
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Index view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    protected function writeShowView($theme, $layout) {
        $path = $theme->generatePhpFile('views.show', $theme->showViewPath(), function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#CONTENT', $this->option('show-content') ?: '')
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Show view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    protected function writeCreateView($theme, $layout) {
        $path = $theme->generatePhpFile('views.create', $theme->createViewPath(), function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#FIELDS', $this->option('create-fields') ?: '')
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Create view created : <fg=white>$path</>");
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    protected function writeEditView($theme, $layout) {
        $path = $theme->generatePhpFile('views.edit', $theme->editViewPath(), function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, '#FIELDS', $this->option('edit-fields') ?: '')
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Edit view created : <fg=white>$path</>");
    }

}
