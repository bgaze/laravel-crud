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

    ############################################################################
    # INITIALZATION

    /**
     * TODO
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files, $model, $plural = null) {
        // Theme root dir.
        $class = new \ReflectionClass($this);
        $this->root = dirname($class->getFileName());

        // Filesystem.
        $this->files = $files;

        // Get Model name and namespace.
        $this->parseModelName($model, $plural);
    }

    /**
     * TODO
     * 
     * @param type $model
     * @param type $plural
     * @throws Exception
     */
    protected function parseModelName($model, $plural) {
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

        if (!$plural) {
            $this->plural = Str::plural($this->model);
        } else if (!preg_match('/^([A-Z][a-z]+)+$/', $plural)) {
            throw new Exception("Plurar name is invalid.");
        } else {
            $this->plural = $plural;
        }
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
    # FILES GENERATION

    /**
     * TODO
     * 
     * @param type $name
     * @return type
     */
    protected function getStub($name) {
        return $this->files->get($this->root . '/stubs/' . str_replace('.', '/', $name) . '.stub');
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $var
     * @return $this
     */
    public function replaceInStub(&$stub, $name, $value = false) {
        if ($value === false) {
            $value = $this->{'get' . $name}();
        }

        $stub = str_replace($name, $value, $stub);

        return $this;
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $path
     * @param callable $replace
     */
    public function generateFile($stub, $path, callable $replace) {
        // Get stub content.
        $stub = $this->getStub($stub);

        // Do custom replacements.
        $stub = $replace($this, $stub);

        // Strip base path.
        $path = self::stripBasePath($path);

        // Create output dir if necessary.
        if (!$this->files->isDirectory(dirname(base_path($path)))) {
            $this->files->makeDirectory(dirname(base_path($path)), 0777, true, true);
        }

        // Create file.
        $this->files->put(base_path($path), $stub);

        // Return file path.
        return $path;
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $path
     * @param callable $replace
     */
    public function generatePhpFile($stub, $path, callable $replace) {
        // Generate file.
        $path = $this->generateFile($stub, $path, $replace);

        // Fix it with PhpCsFixer.
        php_cs_fixer($path, ['--quiet' => true]);

        // Return file path.
        return $path;
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

    /**
     * TODO
     * 
     * @param type $path
     * @return type
     */
    static public function stripBasePath($path) {
        return str_replace(base_path() . '/', '', $path);
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
        return $this->namespace;
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

    ############################################################################
    # MIGRATION

    public function getMigrationClass() {
        $class = 'Create' . $this->getPluralStudly() . 'Table';

        if (class_exists($class)) {
            throw new \Exception("A {$class} class already exists.");
        }

        return $class;
    }

    public function getMigrationPath() {
        $file = Str::snake($this->getMigrationClass());

        if (count($this->files->glob(database_path("migrations/*_{$file}.php")))) {
            throw new \Exception("A {$file}.php migration file already exists.");
        }

        $prefix = date('Y_m_d_His');

        return database_path("migrations/{$prefix}_{$file}.php");
    }

}
