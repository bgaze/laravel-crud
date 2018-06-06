<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Core\GeneratorCommand;

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
     * The message to display when the command is ran.
     * 
     * @return string
     */
    protected function welcome() {
        return "Welcome to CRUD generator";
    }

    /**
     * An array of CRUD method to execute in order to check that no file to generate already exists.
     * 
     * @return array
     */
    protected function files() {
        return ['migrationPath', 'modelPath', 'factoryPath', 'requestPath', 'controllerPath', 'indexViewPath', 'showViewPath', 'createViewPath', 'editViewPath'];
    }

    /**
     * Generate a summary of generator's actions.
     * 
     * If some files to generate already exists, an eroor is raised, 
     * otherwise a formatted summary of generated files is returned.
     * 
     * @return string
     * @throws \Exception
     */
    protected function summary() {
        return " <fg=green>Routes will be added to :</> "
                . str_replace(base_path() . '/', '', $this->crud->routesPath())
                . "\n" . parent::summary();
    }

    /**
     * Build the files.
     * 
     * @return void
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
