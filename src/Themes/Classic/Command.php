<?php

namespace Bgaze\Crud\Themes\Classic;

use Bgaze\Crud\Support\Theme\Composer;
use Bgaze\Crud\Themes\Api\Command as BaseCommand;
use Exception;

class Command extends BaseCommand
{
    /**
     * Theme's layout
     */
    const DEFAULT_LAYOUT = 'crud-classic::layout';

    /**
     * The tasks that need the layout option.
     */
    const TASKS_USING_LAYOUT = ['index-view', 'create-view', 'edit-view', 'show-view'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "crud:classic 
            {model : The FullName of the Model}
            {--p|plurals= : The Plurals version of the Model's name}
            {--o|only=* : Execute only provided tasks}
            {--l|layout= : The layout to extend into generated views}
            {--t|timestamps : Add a timestamps directive}
            {--s|soft-deletes : Add a softDelete directive}
            {--c|content=* : The list of Model's entries using SignedInput syntax}
            {--f|force : Overwrite any existing file}";

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = "Generate a classic CRUD";


    /**
     * The stubs available in the CRUD theme.
     *
     * @return array Name as key, absolute path as value.
     */
    public function stubs()
    {
        return array_merge(parent::stubs(), [
            'partials.index-head' => __DIR__ . '/Stubs/partials/index-head.blade.stub',
            'partials.index-body' => __DIR__ . '/Stubs/partials/index-body.blade.stub',
            'partials.show-group' => __DIR__ . '/Stubs/partials/show-group.blade.stub',
            'partials.form-group' => __DIR__ . '/Stubs/partials/form-group.blade.stub',
            'views.index' => __DIR__ . '/Stubs/index-view.blade.stub',
            'views.show' => __DIR__ . '/Stubs/show-view.blade.stub',
            'views.create' => __DIR__ . '/Stubs/create-view.blade.stub',
            'views.edit' => __DIR__ . '/Stubs/edit-view.blade.stub',
            'controller' => __DIR__ . '/Stubs/controller.php.stub',
            'routes-compact' => __DIR__ . '/Stubs/routes-compact.php.stub',
            'routes-expanded' => __DIR__ . '/Stubs/routes-expanded.php.stub',
        ]);
    }


    /**
     * The tasks available in the CRUD theme.
     *
     * @return array Name as key, full class name as value.
     */
    public function tasks()
    {
        return array_merge(parent::tasks(), [
            'routes' => Tasks\RegisterRoutes::class,
            'index-view' => Tasks\BuildIndexView::class,
            'create-view' => Tasks\BuildCreateView::class,
            'edit-view' => Tasks\BuildEditView::class,
            'show-view' => Tasks\BuildShowView::class,
        ]);
    }


    /**
     * Set the CRUD instance.
     *
     * @return $this
     */
    public function setCrud()
    {
        $this->crud = new Crud($this);
        return $this;
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function compose()
    {
        $composer = new Composer($this);

        // Set CRUD identity.
        $composer->setModel();
        $composer->setPlurals();
        $composer->setTasks();

        // Check that nothing prevents CRUD generation.
        $composer->checkIfGenerationIsPossible();

        // Configure CRUD content.
        $composer->setTimestamps();
        $composer->setSoftDeletes();
        if ($this->crud->getTasks()->only(static::TASKS_USING_LAYOUT)->isNotEmpty()) {
            $composer->setLayout(static::DEFAULT_LAYOUT);
        }
        $composer->setContent();

        $this->nl($this->option('no-interaction'));
    }


    /**
     * Get CRUD's index url
     *
     * @return string
     */
    protected function index()
    {
        return url($this->crud->getVariable('PluralsKebabSlash'));
    }


}
