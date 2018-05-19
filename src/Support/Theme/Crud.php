<?php

namespace Bgaze\Crud\Support\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Description of Theme
 *
 * @author bgaze
 */
abstract class Crud {

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * TODO
     *
     * @var type 
     */
    protected $root;

    /**
     * TODO
     *
     * @var type 
     */
    protected $model;

    /**
     * TODO
     *
     * @var type 
     */
    protected $plural;

    /**
     * TODO
     *
     * @var type 
     */
    protected $namespace;

    /**
     * TODO
     *
     * @var type 
     */
    protected $definitions;

    /**
     * TODO
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files) {
        $this->files = $files;

        $class = new \ReflectionClass($this);
        $this->root = dirname($class->getFileName());
    }

    ############################################################################
    # CORE

    /**
     * TODO
     * 
     * @param type $model
     * @param type $plural
     * @throws Exception
     */
    public function init($model, $plural = null) {
        $model = trim($model, '\\/ ');
        $model = str_replace('/', '\\', $model);

        if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $model)) {
            throw new Exception("Model name is invalid.");
        }

        $tmp = explode('\\', $model);
        if (count($tmp) > 1) {
            $this->model = array_pop($tmp);
            $this->namespace = implode('\\', $tmp);
        } else {
            $this->model = $model;
            $this->namespace = null;
        }

        $this->model = $model;

        if (empty($plural)) {
            $this->plural = Str::plural($this->model);
        } else if (!preg_match('/^([A-Z][a-z]+)+$/', $model)) {
            throw new Exception("Plurar name is invalid.");
        } else {
            $this->plural = $plural;
        }
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $var
     * @return $this
     */
    protected function replaceInStub(&$stub, $var) {
        $stub = str_replace($var, $this->{"get$var"}(), $stub);
        return $this;
    }

    /**
     * TODO
     * 
     * @param type $name
     * @return type
     */
    protected function getStub($name) {
        return $this->files->get($this->root . '/' . str_replace('.', '/', $name) . '.stubs');
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $path
     * @param callable $replace
     */
    protected function generateFile($stub, $path, callable $replace) {
        $stub = $replace($this->getStub($stub));

        $fullpath = app_path($path);

        if (!$this->files->isDirectory(dirname($fullpath))) {
            $this->files->makeDirectory(dirname($fullpath), 0777, true, true);
        }

        $this->files->put($fullpath, $stub);

        return $path;
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $path
     * @param callable $replace
     */
    protected function generatePhpFile($stub, $path, callable $replace) {
        $path = $this->generateFile($stub, $path, $replace);

        php_cs_fixer(base_path($path), ['--quiet' => true]);

        return $path;
    }

    /**
     * TODO
     * 
     */
    protected function loadDefinitions() {
        $this->definitions = (object) [
                    'validation' => collect(config('crud-definitions.migrate.validation')),
                    'timestamps' => collect(config('crud-definitions.migrate.timestamps')),
                    'indexes' => collect(config('crud-definitions.migrate.indexes')),
                    'modifiers' => collect(config('crud-definitions.migrate.modifiers')),
                    'fields' => collect(config('crud-definitions.migrate.fields'))->map([$this, 'loadFieldDefinition'])
        ];
    }

    /**
     * TODO
     * 
     * @param array $definition
     * @param type $name
     * @return type
     */
    protected function loadFieldDefinition(array $definition, $name) {
        $tmp = (object) $definition;

        $help = SignedInput::help($tmp->signature);
        $tmp->help = $name . ' ' . $help;

        list($options, $arguments) = explode(' [--] ', $help);
        $tmp->help_row = [
            'name' => $name,
            'arguments' => $arguments,
            'options' => trim(str_replace('] [', ' ', $options), '[]')
        ];

        return $tmp;
    }

    ############################################################################
    # HELPERS

    /**
     * Prepare value for PHP generation depending on it's type
     * 
     * @param mixed $value
     * @return mixed
     */
    static public function compileValueForPhp($value) {
        if ($value === true || $value === 'true') {
            return 'true';
        }

        if ($value === false || $value === 'false') {
            return 'false';
        }

        if ($value === null || $value === 'null') {
            return 'null';
        }

        if (!is_numeric($value)) {
            return "'" . addslashes($value) . "'";
        }

        return $value;
    }

    ############################################################################
    # VARIABLES

    /**
     * TODO
     * 
     * @return string
     */
    public function getModeleStudly() {
        return $this->model;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModeleKebab() {
        return Str::kebab($this->model);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModeleSnake() {
        return Str::snake($this->model);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModeleCamel() {
        return Str::camel($this->model);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralStudly() {
        return $this->plural;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralKebab() {
        return Str::kebab($this->plural);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralSnake() {
        return Str::snake($this->plural);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralCamel() {
        return Str::camel($this->plural);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getNamespaceStudly() {
        return $this->getViewsNamespace();
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getNamespaceKebabDot() {
        if (empty($this->namespace)) {
            return $this->getPluralKebab();
        }

        return collect(explode('\\', $this->namespace))
                        ->push($this->plural)
                        ->map(function($value) {
                            Str::kebab($value);
                        })->implode('.');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getNamespaceKebabSlash() {
        if (empty($this->namespace)) {
            return $this->getPluralKebab();
        }

        return collect(explode('\\', $this->namespace))
                        ->push($this->plural)
                        ->map(function($value) {
                            Str::kebab($value);
                        })->implode('/');
    }

}
