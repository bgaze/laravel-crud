<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\CrudHelpersTrait;

class ViewsMakeCommand extends Command {

    use CrudHelpersTrait;

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
            $layout = $theme->getViewsLayout();
        }

        // Write index view.
        $this->writeIndex($theme, $layout);

        // Write show view.
        $this->writeShow($theme, $layout);

        // Write create view.
        $this->writeCreate($theme, $layout);

        // Write edit view.
        $this->writeEdit($theme, $layout);
    }

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Theme\Crud $theme
     */
    protected function writeIndex($theme, $layout) {
        $path = $theme->getViewsPath() . '/index.blade.php';

        $path = $theme->generatePhpFile('views.index', $path, function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, 'PluralWithParentsKebabDot')
                    ->replace($stub, 'PluralWithParents')
                    ->replace($stub, 'ModelCamel')
                    ->replace($stub, 'PluralCamel')
                    ->replace($stub, 'ModelWithParents')
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
    protected function writeShow($theme, $layout) {
        $path = $theme->getViewsPath() . '/show.blade.php';

        $path = $theme->generatePhpFile('views.show', $path, function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, 'PluralWithParentsKebabDot')
                    ->replace($stub, 'PluralWithParents')
                    ->replace($stub, 'ModelWithParents')
                    ->replace($stub, 'ModelCamel')
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
    protected function writeCreate($theme, $layout) {
        $path = $theme->getViewsPath() . '/create.blade.php';

        $path = $theme->generatePhpFile('views.create', $path, function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, 'PluralWithParentsKebabDot')
                    ->replace($stub, 'PluralWithParents')
                    ->replace($stub, 'ModelWithParents')
                    ->replace($stub, 'ModelCamel')
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
    protected function writeEdit($theme, $layout) {
        $path = $theme->getViewsPath() . '/edit.blade.php';

        $path = $theme->generatePhpFile('views.edit', $path, function($theme, $stub) use($layout) {
            $theme
                    ->replace($stub, 'ViewsLayout', $layout)
                    ->replace($stub, 'PluralWithParentsKebabDot')
                    ->replace($stub, 'PluralWithParents')
                    ->replace($stub, 'ModelWithParents')
                    ->replace($stub, 'ModelCamel')
                    ->replace($stub, '#FIELDS', $this->option('create-fields') ?: '')
            ;

            return $stub;
        });

        // Show success message.
        $this->info("Edit view created : <fg=white>$path</>");
    }

}
