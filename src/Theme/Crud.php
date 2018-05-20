<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Support\CrudHelpersTrait;

/**
 * Description of Theme
 *
 * @author bgaze
 */
class Crud {

    use CrudHelpersTrait;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $files;

    /**
     * TODO
     *
     * @var type 
     */
    public $root;

    /**
     * TODO
     *
     * @var type 
     */
    public $definitions;

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

    ############################################################################
    # THEME "CONSTANTS"

    /**
     * TODO
     * 
     * @return string
     */
    static public function name() {
        return 'CrudDefault';
    }

    /**
     * TODO
     * 
     * @return type
     */
    static public function views() {
        return Str::kebab(self::name());
    }

    /**
     * TODO
     *
     * @var array 
     */
    protected function filesMap() {
        return [
            'getMigrationPath',
            'getModelPath',
            'getRequestPath',
            'getRequestPath',
            'getControllerPath',
            'getIndexViewPath',
            'getShowViewPath',
            'getCreateViewPath',
            'getEditViewPath',
            'getFactoryPath'
        ];
    }

    /**
     * TODO
     *
     * @var array 
     */
    protected function variablesMap() {
        return [];
    }

    ############################################################################
    # INIT

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
    public function getStub($name) {
        return $this->files->get($this->root . '/stubs/' . str_replace('.', '/', $name) . '.stub');
    }

    /**
     * TODO
     * 
     * @param type $stub
     * @param type $var
     * @return $this
     */
    public function replace(&$stub, $name, $value = false) {
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
        $path = $this->stripBasePath($path);

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
    # NESTED MODEL MANAGEMENT

    /**
     * TODO
     * 
     * @param type $separator
     * @return type
     */
    public function getModelWithParents($separator = '\\') {
        $name = '';

        if (!empty($this->namespace)) {
            $name .= $this->namespace . '\\';
        }

        $name .= $this->getModelStudly();

        if ($separator !== '\\') {
            return str_replace('\\', $separator, $name);
        }

        return $name;
    }

    /**
     * TODO
     * 
     * @param type $separator
     * @return type
     */
    public function getPluralWithParents($separator = '\\') {
        $name = '';

        if (!empty($this->namespace)) {
            $name .= $this->namespace . '\\';
        }

        $name .= $this->getPluralStudly();

        if ($separator !== '\\') {
            return str_replace('\\', $separator, $name);
        }

        return $name;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralWithParentsKebabDot() {
        return collect(explode('\\', $this->getPluralWithParents()))
                        ->map(function($value) {
                            return Str::kebab($value);
                        })->implode('.');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralWithParentsKebabSlash() {
        return collect(explode('\\', $this->getPluralWithParents()))
                        ->map(function($value) {
                            return Str::kebab($value);
                        })->implode('/');
    }

    ############################################################################
    # VARIABLES

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelStudly() {
        return $this->model;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelKebab() {
        return Str::kebab($this->model);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelSnake() {
        return Str::snake($this->model);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelCamel() {
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

    ############################################################################
    # MIGRATION

    public function getTableName() {
        return Str::snake($this->getPluralWithParents(''));
    }

    public function getMigrationClass() {
        $class = 'Create' . $this->getPluralWithParents('') . 'Table';

        if (class_exists($class)) {
            throw new \Exception("A '{$class}' class already exists.");
        }

        return $class;
    }

    public function getMigrationPath() {
        $file = Str::snake($this->getMigrationClass());

        if (count($this->files->glob(database_path("migrations/*_{$file}.php")))) {
            throw new \Exception("A '{$file}.php' migration file already exists.");
        }

        $prefix = date('Y_m_d_His');

        return database_path("migrations/{$prefix}_{$file}.php");
    }

    ############################################################################
    # MODEL

    public function getModelNamespace() {
        $namespace = trim(app()->getNamespace(), '\\');

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

    public function getModelClass() {
        return $this->getModelNamespace() . '\\' . $this->getModelStudly();
    }

    public function getModelPath() {
        $path = app_path($this->getModelWithParents('/') . '.php');

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    ############################################################################
    # REQUEST

    public function getRequestClass() {
        return $this->getModelStudly() . 'FormRequest';
    }

    public function getRequestNamespace() {
        $namespace = trim(app()->getNamespace(), '\\') . '\\Http\\Requests';

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

    public function getRequestPath() {
        $path = 'Http\\Requests\\';

        if (!empty($this->namespace)) {
            $path .= $this->namespace . '\\';
        }

        $path .= $this->getModelStudly() . 'FormRequest.php';

        $path = app_path(str_replace('\\', '/', $path));

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    ############################################################################
    # CONTROLLER

    public function getControllerClass() {
        return $this->getModelStudly() . 'Controller';
    }

    public function getControllerNamespace() {
        $namespace = trim(app()->getNamespace(), '\\') . '\\Http\\Controllers';

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

    public function getControllerPath() {
        $path = 'Http\\Controllers\\';

        if (!empty($this->namespace)) {
            $path .= $this->namespace . '\\';
        }

        $path .= $this->getModelStudly() . 'Controller.php';

        $path = app_path(str_replace('\\', '/', $path));

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    public function getRoutesPath() {
        return base_path('routes/web.php');
    }

    ############################################################################
    # VIEWS

    protected function getViewPath($views) {
        $path = resource_path('views/' . $this->getPluralWithParentsKebabSlash() . "/{$views}.blade.php");

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    public function getIndexViewPath() {
        return $this->getViewPath('index');
    }

    public function getShowViewPath() {
        return $this->getViewPath('show');
    }

    public function getCreateViewPath() {
        return $this->getViewPath('create');
    }

    public function getEditViewPath() {
        return $this->getViewPath('edit');
    }

    public function getViewsLayout() {
        return self::views() . '::layout';
    }

    ############################################################################
    # FACTORY

    public function getFactoryPath() {
        $path = database_path('factories/' . $this->getModelWithParents('') . 'Factory.php');

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    ############################################################################
    # CRUD MAKE

    /**
     * TODO
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getCrudFilesSummary() {
        $errors = collect([]);
        $files = collect([]);

        foreach ($this->filesMap() as $v) {
            try {
                $files->push($this->{$v}());
            } catch (\Exception $e) {
                $errors->push($e->getMessage());
            }
        }

        if ($errors->isNotEmpty()) {
            $tmp = "Cannot proceed to CRUD generation :\n - ";
            $tmp .= $errors->implode("\n - ");
            throw new \Exception($tmp);
        }

        $tmp = "<fg=green>Following files will be generated :</>\n - ";
        $tmp .= $files->map(function($path) {
                    return $this->stripBasePath($path);
                })->implode("\n - ");

        return $tmp;
    }

}
