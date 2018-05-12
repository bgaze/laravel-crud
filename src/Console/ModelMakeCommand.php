<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ModelMakeCommand extends GeneratorCommand {

    use \Bgaze\Crud\Support\CrudHelpersTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bgaze:crud:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD Eloquent model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle() {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $path = str_replace(base_path() . '/', '', $path);

        $this->call('bgaze:cs-fixer:fix', ['path' => [$path], '--quiet' => true]);

        $this->info("Model created : $path");
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name) {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
                        ->replaceTable($stub)
                        ->replaceTimestamps($stub)
                        ->replaceSoftDelete($stub)
                        ->replaceFillables($stub)
                        ->replaceDates($stub)
                        ->replaceClass($stub, $name)
        ;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['table', InputArgument::REQUIRED, 'The table containing Model data.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['timestamps', 't', InputOption::VALUE_NONE, 'Add timestamps directives'],
            ['soft-delete', 's', InputOption::VALUE_NONE, 'Add soft delete directives'],
            ['fillables', 'f', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The list of Model\'s fillable fields', []],
            ['dates', 'd', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The list of Model\'s date fields', []],
        ];
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() {
        return config('crud.stubs.modele');
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getTableInput() {
        return trim($this->argument('table'));
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceTable(&$stub) {
        $stub = str_replace('DummyTableName', $this->getTableInput(), $stub);

        return $this;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getTimestampsInput() {
        return $this->option('timestamps');
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceTimestamps(&$stub) {
        $tmp = $this->getTimestampsInput() ? 'public $timestamps = true;' : '';

        $stub = str_replace('#TIMESTAMPS', $tmp, $stub);

        return $this;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getSoftDeleteInput() {
        return $this->option('soft-delete');
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceSoftDelete(&$stub) {
        $tmp = $this->getSoftDeleteInput() ? 'use Illuminate\Database\Eloquent\SoftDeletes;' : '';

        $stub = str_replace('#SOFTDELETE', $tmp, $stub);

        return $this;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getFillablesInput() {
        return collect($this->option('fillables'))->map(function($v) {
                    return trim($v);
                })->filter();
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceFillables(&$stub) {
        $tmp = $this->getFillablesInput()->map(function($v) {
                    return $this->compileValueForPhp($v);
                })->implode(', ');

        $stub = str_replace('#FILLABLES', "protected \$fillable = [$tmp];", $stub);

        return $this;
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getDatesInput() {
        return collect($this->option('dates'))->map(function($v) {
                    return trim($v);
                })->filter();
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @return $this
     */
    protected function replaceDates(&$stub) {
        $tmp = $this->getDatesInput();

        if ($this->getSoftDeleteInput() && !$tmp->contains('deleted_at')) {
            $tmp->prepend('deleted_at');
        }

        $tmp = $tmp->map(function($v) {
            return $this->compileValueForPhp($v);
        });

        $stub = str_replace('#DATES', $tmp->isEmpty() ? '' : "protected \$dates = [" . $tmp->implode(', ') . "];", $stub);

        return $this;
    }

}
