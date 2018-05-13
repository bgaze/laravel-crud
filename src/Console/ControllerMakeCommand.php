<?php

namespace Bgaze\Crud\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;

class ControllerMakeCommand extends GeneratorCommand {

    use \Bgaze\Crud\Support\CrudHelpersTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:controller 
        {name : The name of the class.}
        {--m|model= : The name of the Model class.}
        {--p|plural= : The plural version of the Model class name.}
        {--r|request= : The name of the request class validating Model\'s form.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD controller class related to a Model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle() {
        if (!preg_match('/^[A-Z][a-zA-Z]+Controller$/', $this->getNameInput())) {
            throw new \Exception("Controller name must end with 'Controller'");
        }

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            throw new \Exception($this->stripBasePath($path) . ' already exists!');
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->finalizeFileGeneration($path, 'Controller created : %s');

        $this->files->append(base_path('routes/web.php'), $this->buildRoutes($name));
        $this->info("Routes added to <fg=white>routes/web.php</>");
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name) {
        $replace = $this->getReplacementValues($name);
        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    /**
     * TODO
     * 
     * @param type $name
     * @return type
     */
    public function buildRoutes($name) {
        $replace = $this->getReplacementValues($name);
        $stub = $this->replaceClass($this->files->get($this->getRoutesStub()), $name);
        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    /**
     * TODO
     * 
     * @param type $name
     * @return type
     */
    protected function getReplacementValues($name) {
        $controllerNamespace = $this->getNamespace($name);
        $modelSingular = $this->getModelInput();
        $modelPlural = $this->getPluralInput();

        return [
            'DummyFullModelClass' => $this->parseModel($modelSingular),
            'DummyModelClass' => $modelSingular,
            'DummyRequestClass' => $this->getRequestInput(),
            'DummyModelVariable' => lcfirst($modelSingular),
            'DummyModelPluralVariable' => lcfirst($modelPlural),
            'DummyViewsNamespace' => Str::kebab($modelPlural),
            "use {$controllerNamespace}\Controller;\n" => '',
        ];
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     */
    protected function parseModel($model) {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput() {
        return trim($this->argument('name'));
    }

    /**
     * Get the name of the Model.
     *
     * @return string
     */
    protected function getModelInput() {
        if ($this->option('model')) {
            return trim($this->option('model'));
        }

        preg_match('/^([A-Z][a-zA-Z]+)Controller$/', $this->getNameInput(), $matches);

        return $matches[1];
    }

    /**
     * Get the plural version of the Model class name.
     *
     * @return string
     */
    protected function getPluralInput() {
        if ($this->option('plural')) {
            return trim($this->option('plural'));
        }

        return Str::plural($this->getModelInput());
    }

    /**
     * Get the name of the request class validating Model\s form.
     *
     * @return string
     */
    protected function getRequestInput() {
        if ($this->option('request')) {
            return trim($this->option('request'));
        }

        return $this->getModelInput() . 'FormRequest';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() {
        return config('crud.stubs.controller');
    }

    /**
     * Get the stub file for the routes.
     *
     * @return string
     */
    protected function getRoutesStub() {
        return config('crud.stubs.routes');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) {
        return $rootNamespace . '\Http\Controllers';
    }

}
