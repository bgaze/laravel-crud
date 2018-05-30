<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class CrudMakeCommand extends GeneratorCommand {

    use ConsoleHelpersTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:make 
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
    protected $description = 'Create a new CRUD';

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Support\Theme\Crud
     */
    protected $crud;

    /**
     * TODO
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD generator";
    }

    /**
     * TODO
     */
    protected function files() {
        return ['migrationPath', 'modelPath', 'factoryPath', 'requestPath', 'controllerPath', 'indexViewPath', 'showViewPath', 'createViewPath', 'editViewPath'];
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function summary() {
        return " <fg=green>Routes will be added to :</> "
                . str_replace(base_path() . '/', '', $this->crud->routesPath())
                . "\n" . parent::summary();
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     */
    protected function build() {
        $config = collect([
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--layout' => $this->option('layout'),
            '--timestamps' => $this->crud->content->timestamps,
            '--soft-deletes' => $this->crud->content->softDeletes,
            '--content' => $this->crud->content->originalInputs(),
            '--no-interaction' => true
        ]);

        // Generate migration file
        $this->call('crud:migration', $config->except(['--layout'])->all());

        // Generate model file
        $this->call('crud:model', $config->except(['--layout'])->all());

        // Generate Model factory
        $this->call('crud:factory', $config->except(['--timestamps', '--soft-deletes', '--layout'])->all());

        // Generate request file
        $this->call('crud:request', $config->except(['--timestamps', '--soft-deletes', '--layout'])->all());

        // Generate views
        $this->call('crud:views', $config->all());

        // Generate controller
        $this->call('crud:controller', $config->except(['--timestamps', '--soft-deletes', '--layout', '--content'])->all());
    }

}
