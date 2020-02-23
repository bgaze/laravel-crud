<?php

namespace Bgaze\Crud\Themes\Api;

use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Theme\Command as BaseCommand;
use Bgaze\Crud\Support\Theme\Composer;
use Exception;
use Illuminate\Support\Str;

/**
 * Class Theme
 *
 * @package Bgaze\Crud\Theme\Api
 */
class Command extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "crud:api 
            {model : The FullName of the Model}
            {--p|plurals= : The Plurals version of the Model's name}
            {--o|only=* : Execute only provided tasks}
            {--t|timestamps : Add a timestamps directive}
            {--s|soft-deletes : Add a softDelete directive}
            {--c|content=* : The list of Model's entries using SignedInput syntax}
            {--f|force : Overwrite any existing file}";

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = "Generate an API CRUD";


    /**
     * The stubs available in the CRUD theme.
     *
     * @return array Name as key, absolute path as value.
     */
    public function stubs()
    {
        return [
            'relation' => __DIR__ . '/Stubs/relation.php.stub',
            'migration' => __DIR__ . '/Stubs/migration.php.stub',
            'model' => __DIR__ . '/Stubs/model.php.stub',
            'factory' => __DIR__ . '/Stubs/factory.php.stub',
            'seeder' => __DIR__ . '/Stubs/seeder.php.stub',
            'request' => __DIR__ . '/Stubs/request.php.stub',
            'resource' => __DIR__ . '/Stubs/resource.php.stub',
            'controller' => __DIR__ . '/Stubs/controller.php.stub',
            'routes-compact' => __DIR__ . '/Stubs/routes-compact.php.stub',
            'routes-expanded' => __DIR__ . '/Stubs/routes-expanded.php.stub',
        ];
    }


    /**
     * The tasks available in the CRUD theme.
     *
     * @return array Name as key, full class name as value.
     */
    public function tasks()
    {
        return [
            'migration' => Tasks\BuildMigrationClass::class,
            'model' => Tasks\BuildModelClass::class,
            'factory' => Tasks\BuildFactoryFile::class,
            'seeder' => Tasks\BuildSeederClass::class,
            'request' => Tasks\BuildRequestClass::class,
            'resource' => Tasks\BuildResourceClass::class,
            'controller' => Tasks\BuildControllerClass::class,
            'routes' => Tasks\RegisterRoutes::class,
        ];
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
        $composer->setPlural();
        $this->setVariables();
        $composer->setTasks();

        // Check that nothing prevents CRUD generation.
        $composer->checkIfGenerationIsPossible();

        // Configure CRUD content.
        $composer->setTimestamps();
        $composer->setSoftDeletes();
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
        return url('api/' . $this->crud->getVariable('PluralsKebabSlash'));
    }


    /**
     * Show exit message based on build success.
     *
     * @param  bool  $built
     */
    protected function end($built)
    {
        if ($built) {
            $this->comment(' CRUD generated successfully.');
            $this->line(' <comment>Index path:</comment> ' . $this->index());
            $this->nl();
        } else {
            parent::end($built);
        }
    }


    /**
     * Register required variables.
     */
    protected function setVariables()
    {
        $namespace = $this->crud->getModel()->toBase();
        $namespace->pop();
        $namespace = app()->getNamespace() . 'Http\\%s\\' . $namespace->implode('\\');

        $plurals = $this->crud->getPlurals()->implode('');
        $model = $this->crud->getModel()->last();

        $this->crud->addVariables([
            'ModelNamespace' => Definitions::modelsNamespace(),
            'MigrationClass' => 'Create' . $plurals . 'Table',
            'TableName' => Str::snake($plurals),
            'RequestClass' => $model . 'FormRequest',
            'RequestNamespace' => trim(sprintf($namespace, 'Requests'), '\\'),
            'ResourceClass' => $model . 'Resource',
            'ResourceNamespace' => trim(sprintf($namespace, 'Resources'), '\\'),
            'ControllerClass' => $model . 'Controller',
            'ControllerFullName' => $this->crud->ModelFullName . 'Controller',
            'ControllerNamespace' => trim(sprintf($namespace, 'Controllers'), '\\'),
        ]);
    }
}
