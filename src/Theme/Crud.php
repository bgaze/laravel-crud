<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Theme\Content;

/**
 * TODO
 *
 * @author bgaze
 */
class Crud {

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $files;

    /**
     * TODO
     *
     * @var \Bgaze\Crud\Theme\Content 
     */
    public $content;

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
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files, $model, $plural = null) {
        // Get Model name and namespace.
        $this->parseModelName($model, $plural);

        // Filesystem.
        $this->files = $files;

        // Init CRUD content.
        $this->content = new Content();
    }

    ############################################################################
    # THEME IDENTITY

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
     * @return string
     */
    static public function layout() {
        return self::views() . '::layout';
    }

    ############################################################################
    # NAMES MANAGEMENT

    /**
     * TODO
     * 
     * @return string
     */
    protected function modelsSubDirectory() {
        $dir = config('crud.models-directory', false);

        if ($dir === true) {
            return 'Models';
        }

        if ($dir && !empty($dir)) {
            return $dir;
        }

        return '';
    }

    /**
     * TODO
     * 
     * @param type $model
     * @param type $plural
     * @throws Exception
     */
    protected function parseModelName($model, $plural) {
        // Parse Model.
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

        // Parse plural.
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

    ############################################################################
    # FILES GENERATION

    /**
     * TODO
     * 
     * @param type $name
     * @return type
     */
    public function stub($name) {
        return $this->files->get(__DIR__ . '/stubs/' . str_replace('.', '/', $name) . '.stub');
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
    public function generateFile($stub, $path, callable $replace = null) {
        // Get stub content.
        $stub = $this->stub($stub);

        // Replace common variables.
        foreach ($this->variablesMap() as $var) {
            $this->replace($stub, $var);
        }

        // Do custom replacements.
        if ($replace !== null) {
            $stub = $replace($this, $stub);
        }

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
    public function generatePhpFile($stub, $path, callable $replace = null) {
        // Generate file.
        $path = $this->generateFile($stub, $path, $replace);

        // Fix it with PhpCsFixer.
        php_cs_fixer($path, ['--quiet' => true]);

        // Return file path.
        return $path;
    }

    ############################################################################
    # PATHES

    /**
     * TODO
     * 
     * @param type $path
     * @return type
     */
    protected function stripBasePath($path) {
        return str_replace(base_path() . '/', '', $path);
    }

    /**
     * TODO
     * 
     * @param type $views
     * @return string
     * @throws \Exception
     */
    protected function viewPath($views) {
        $path = resource_path('views/' . $this->getPluralWithParentsKebabSlash() . "/{$views}.blade.php");

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function migrationPath() {
        $file = Str::snake($this->getMigrationClass());

        if (count($this->files->glob(database_path("migrations/*_{$file}.php")))) {
            throw new \Exception("A '{$file}.php' migration file already exists.");
        }

        $prefix = date('Y_m_d_His');

        return database_path("migrations/{$prefix}_{$file}.php");
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function modelPath() {
        $path = $this->modelsSubDirectory() . '/' . $this->getModelWithParents('/') . '.php';

        $path = app_path(trim($path, '/'));

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function requestPath() {
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

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function controllerPath() {
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

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function routesPath() {
        return base_path('routes/web.php');
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function factoryPath() {
        $path = database_path('factories/' . $this->getModelWithParents('') . 'Factory.php');

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function indexViewPath() {
        return $this->viewPath('index');
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function showViewPath() {
        return $this->viewPath('show');
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function createViewPath() {
        return $this->viewPath('create');
    }

    /**
     * TODO
     * 
     * @return type
     * @throws \Exception
     */
    public function editViewPath() {
        return $this->viewPath('edit');
    }

    ############################################################################
    # VARIABLES

    /**
     * TODO
     *
     * @var array 
     */
    protected function variablesMap() {
        $variables = [];

        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 3) !== 'get') {
                continue;
            }

            $variables[] = substr($method, 3);
        }

        rsort($variables);

        return $variables;
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
    public function getTableName() {
        return Str::snake($this->getPluralWithParents(''));
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getMigrationClass() {
        $class = 'Create' . $this->getPluralWithParents('') . 'Table';

        if (class_exists($class)) {
            throw new \Exception("A '{$class}' class already exists.");
        }

        return $class;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelNamespace() {
        $dir = $this->modelsSubDirectory();

        $namespace = trim(app()->getNamespace(), '\\');

        if (!empty($dir)) {
            $namespace .= '\\' . $dir;
        }

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelClass() {
        return $this->getModelNamespace() . '\\' . $this->getModelStudly();
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getRequestClass() {
        return $this->getModelStudly() . 'FormRequest';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getRequestNamespace() {
        $namespace = trim(app()->getNamespace(), '\\') . '\\Http\\Requests';

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getControllerClass() {
        return $this->getModelStudly() . 'Controller';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getControllerNamespace() {
        $namespace = trim(app()->getNamespace(), '\\') . '\\Http\\Controllers';

        if (!empty($this->namespace)) {
            $namespace .= '\\' . $this->namespace;
        }

        return $namespace;
    }

}
