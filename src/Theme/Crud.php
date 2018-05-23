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
     * @var \Illuminate\Support\Collection 
     */
    protected $model;

    /**
     * TODO
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $plural;

    /**
     * TODO
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $plurals;

    /**
     * TODO
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files, $model, $plural = null) {
        // Parse model input to get model full name.
        $this->model = $this->parseModel($model);

        // Parse plurar input to get model full plurar form.
        $this->plurals = $this->parsePlural($plural);

        // Determine plural form.
        $this->plural = clone $this->model;
        $this->plural->pop();
        $this->plural->push($this->plurals->last());

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
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function parseModel($model) {
        $model = str_replace('/', '\\', trim($model, '\\/ '));

        if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $model)) {
            throw new \Exception("Model name is invalid.");
        }

        return collect(explode('\\', $model));
    }

    /**
     * TODO
     * 
     * @param type $plural
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function parsePlural($plural) {
        $auto = $this->model->map(function($v) {
            return Str::plural($v);
        });
        $error = "Plural name is invalid. It sould be something like : " . $auto->implode('\\');

        if (empty($plural)) {
            return $auto;
        }

        $plural = str_replace('/', '\\', trim($plural, '\\/ '));
        if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $plural)) {
            throw new \Exception($error);
        }

        $plural = collect(explode('\\', $model));
        if ($plural->count() !== $this->model->count()) {
            throw new \Exception($error);
        }

        return $plural;
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
     * @return type
     * @throws \Exception
     */
    public function migrationPath() {
        $file = Str::snake($this->getMigrationClass());

        if (count($this->files->glob(database_path("migrations/*_{$file}.php")))) {
            throw new \Exception("A '*_{$file}.php' migration file already exists.");
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
        $path = app_path(trim($this->modelsSubDirectory() . '/' . $this->model->implode('/') . '.php', '/'));

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
        $path = app_path('Http/Requests/' . $this->model->implode('/') . 'FormRequest.php');

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
        $path = app_path('Http/Controllers/' . $this->model->implode('/') . 'Controller.php');

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
        $path = database_path('factories/' . $this->model->implode('') . 'Factory.php');

        if ($this->files->exists($path)) {
            $path = $this->stripBasePath($path);
            throw new \Exception("A '{$path}' file already exists.");
        }

        return $path;
    }

    /**
     * TODO
     * 
     * @param type $views
     * @return string
     * @throws \Exception
     */
    protected function viewPath($views) {
        $path = resource_path('views/' . $this->getPluralsKebabSlash() . "/{$views}.blade.php");

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
     * @return type
     */
    public function getModelFullName() {
        return $this->model->implode('\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelStudly() {
        return $this->model->last();
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelCamel() {
        return Str::camel($this->model->last());
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getPluralFullName() {
        return $this->plural->implode('\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralCamel() {
        return Str::camel($this->plural->last());
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function getPluralsFullName() {
        return $this->plurals->implode('\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralsKebabDot() {
        return $this->plurals
                        ->map(function($v) {
                            return Str::kebab($v);
                        })
                        ->implode('.');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getPluralsKebabSlash() {
        return $this->plurals
                        ->map(function($v) {
                            return Str::kebab($v);
                        })
                        ->implode('/');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getTableName() {
        return Str::snake($this->plurals->implode(''));
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getMigrationClass() {
        $class = 'Create' . $this->plurals->implode('') . 'Table';

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
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim($this->modelsSubDirectory() . '\\' . $parents->implode('\\'), '\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelClass() {
        return app()->getNamespace() . trim($this->modelsSubDirectory() . '\\' . $this->model->implode('\\'), '\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getRequestClass() {
        return $this->model->last() . 'FormRequest';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getRequestNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim('Http\\Requests\\' . $parents->implode('\\'), '\\');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getControllerClass() {
        return $this->model->last() . 'Controller';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function getControllerNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim('Http\\Controllers\\' . $parents->implode('\\'), '\\');
    }

}
