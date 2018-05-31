<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Bgaze\Crud\Theme\Content;

/**
 * The core class of the CRUD package
 * 
 * It manages variables and stub to use to generate files.
 * 
 * It can be extended to create custom Crud theme.
 * 
 * <b>Important :</b> 
 * 
 * In this class, getXXX method name pattern is reserved for CRUD variables.<br/>
 * Any method starting with 'get' MUST NOT have any required argument, MUST return a stringable value, 
 * and WILL be used as replacement into stubs.
 * 
 * Exemple :
 * 
 * If the method 'getMyVariableName' exists, then it's return value will be used 
 * to replace any occurence of 'MyVariableName' into stubs.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Crud {

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    public $files;

    /**
     * The Model parents and name.<br/>
     * Example : ['MyGrandParent', 'MyParent', 'MyModel']
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $model;

    /**
     * The Model's parents and the plural version of its name.<br/>
     * Example : ['MyGrandParent', 'MyParent', 'MyModels']
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $plural;

    /**
     * The plural version of Model's parents and name.<br/>
     * Example : ['MyGrandParents', 'MyParents', 'MyModels']
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $plurals;

    /**
     * The layout to extend in generated views.
     *
     * @var string
     */
    protected $layout;

    /**
     * Model's content manager.
     *
     * @var \Bgaze\Crud\Theme\Content 
     */
    public $content;

    /**
     * The constructor of the class.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files The filesystem instance
     * @return void
     */
    public function __construct(Filesystem $files) {
        // Filesystem.
        $this->files = $files;
    }

    /**
     * Initialize CRUD default values.
     * 
     * @param string $model The full name of the Model (including parents)
     */
    public function init($model) {
        // Init CRUD content.
        $this->content = new Content($this);

        // Parse model input to get model full name.
        $this->model = $this->parseModel($model);

        // Init plurars.
        $this->setPlurals();

        // Init timestamps.
        $this->setTimestamps();

        // Init soft deletes.
        $this->setSoftDeletes();

        // Init layout.
        $this->setLayout();
    }

    ############################################################################
    # THEME IDENTITY

    /**
     * The unique name of the CRUD theme.
     * 
     * It is used to register Theme's singleton.
     * 
     * @return string
     */
    static public function name() {
        return 'CrudDefault';
    }

    /**
     * The views namespace.
     * 
     * It is used to publish and register Theme's views.
     * 
     * @return string
     */
    static public function views() {
        return Str::kebab(self::name());
    }

    /**
     * The Theme base layout.
     * 
     * The default layout to extend in views.
     * 
     * @return string
     */
    static public function layout() {
        return self::views() . '::layout';
    }

    ############################################################################
    # NAMES MANAGEMENT

    /**
     * Models sub-directory, based on global configuration.
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
     * Parse and validate Model's name and parents.
     * 
     * @param string $model
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
     * Parse and validate plurals versions of Model's name and parents.<br/>
     * Compute and set default value if empty.
     * 
     * @param string $value
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    public function setPlurals($value = false) {
        $this->plurals = $this->model->map(function($v) {
            return Str::plural($v);
        });

        if (!empty($value)) {
            $error = "Plural names are invalid. It sould be something like : " . $this->plurals->implode('\\');

            $value = str_replace('/', '\\', trim($value, '\\/ '));
            if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $value)) {
                throw new \Exception($error);
            }

            $value = collect(explode('\\', $value));
            if ($value->count() !== $this->model->count()) {
                throw new \Exception($error);
            }

            $this->plurals = $value;
        }

        // Determine plural form.
        $this->plural = clone $this->model;
        $this->plural->pop();
        $this->plural->push($this->plurals->last());
    }

    /**
     * Validate timestamps option, set default value if empty.
     * 
     * @param type $value
     * @throws \Exception
     * @return void
     */
    public function setTimestamps($value = false) {
        $timestamps = array_keys(config('crud-definitions.timestamps'));
        $this->content->timestamps = $timestamps[0];

        if (!empty($value)) {
            if (!in_array($value, $timestamps)) {
                throw new \Exception("Allowed values for timestamps are : " . implode(', ', $timestamps));
            }

            $this->content->timestamps = $value;
        }
    }

    /**
     * Validate soft deletes option, set default value if empty.
     * 
     * @param type $value
     * @throws \Exception
     * @return void
     */
    public function setSoftDeletes($value = false) {
        $softDeletes = array_keys(config('crud-definitions.softDeletes'));
        $this->content->softDeletes = $softDeletes[0];

        if (!empty($value)) {
            if (!in_array($value, $softDeletes)) {
                throw new \Exception("Allowed values for timestamps are : " . implode(', ', $softDeletes));
            }

            $this->content->softDeletes = $value;
        }
    }

    /**
     * Set default layout for CRUD's views.
     * 
     * @param type $value
     * @return void
     */
    public function setLayout($value = false) {
        if ($value) {
            $this->layout = $this->option('layout');
        } elseif (config('crud.layout')) {
            $this->layout = config('crud.layout');
        } else {
            $this->layout = self::layout();
        }
    }

    ############################################################################
    # FILES GENERATION

    /**
     * Get a stub file relative to Theme's dir and return it's content.
     * 
     * Uses a dotted syntax, exemple :
     * 
     * 'my-parent-dir.my-stub' => 'theme-root-directory/stubs/my-parent-dir/my-stub.stub'
     * 
     * @param string $name The name of the stub
     * @return string
     */
    public function stub($name) {
        return $this->files->get(__DIR__ . '/stubs/' . str_replace('.', '/', $name) . '.stub');
    }

    /**
     * Replace a variable name in a stub string.
     * 
     * @param string $stub The stub content string
     * @param string $name The variable name
     * @param string|false $value The value to use. If false, the '$this->get{$name}()' method is called.
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
     * Populate a stub file content and returns resulting string.
     * 
     * Any existing method starting with "get" is automatically use as replacement.
     * 
     * Exemple :
     * 
     * If the method 'getMyVariableName' exists, then it's return value will be used 
     * to replace any occurence of 'MyVariableName' into stub.
     * 
     * 
     * @param string $stub The stub file name (dotted syntax)
     * @param callable $replace A callback to do custom replacements
     * @return string
     */
    public function populateStub($stub, callable $replace = null) {
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

        return $stub;
    }

    /**
     * Generate a file using a stub file.
     * 
     * @param string $stub The stub file name (dotted syntax)
     * @param string $path The path of the file relative to base_path()
     * @param callable $replace A callback to do custom replacements
     * @return string
     */
    public function generateFile($stub, $path, callable $replace = null) {
        // Get stub content.
        $stub = $this->populateStub($stub, $replace);

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
     * Generate a file using a stub file then fix it using PHP-CS-Fixer.
     * 
     * @param string $stub The stub file name (dotted syntax)
     * @param string $path The path of the file relative to base_path()
     * @param callable $replace A callback to do custom replacements
     * @return string
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
     * Remove base_path() from a path string.
     * 
     * @param string $path The path of the file
     * @return string The path of the file relative to base_path()
     */
    protected function stripBasePath($path) {
        return str_replace(base_path() . '/', '', $path);
    }

    /**
     * Get the path of the migration file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
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
     * Get the path of the Model file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
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
     * Get the path of the Request file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
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
     * Get the path of the Controller file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
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
     * Get the path of the application routes file.
     * 
     * @return string
     */
    public function routesPath() {
        return base_path('routes/web.php');
    }

    /**
     * Get the path of the Model factory file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
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
     * Get the path of a view file to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @param string $views The name of the view
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
     * Get the path of the index view to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
     * @throws \Exception
     */
    public function indexViewPath() {
        return $this->viewPath('index');
    }

    /**
     * Get the path of the show view to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
     * @throws \Exception
     */
    public function showViewPath() {
        return $this->viewPath('show');
    }

    /**
     * Get the path of the create view to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
     * @throws \Exception
     */
    public function createViewPath() {
        return $this->viewPath('create');
    }

    /**
     * Get the path of the edit view to generate for current CRUD.
     * Throw an exception if file already exists.
     * 
     * @return string
     * @throws \Exception
     */
    public function editViewPath() {
        return $this->viewPath('edit');
    }

    ############################################################################
    # VARIABLES

    /**
     * Get a list of variables present in the class (based on existing methods starting with 'get').
     *
     * @return array
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
     * Get the Model full name
     * 
     * Exemple : MyGrandParent\MyParent\MyModel
     * 
     * @return string
     */
    public function getModelFullName() {
        return $this->model->implode('\\');
    }

    /**
     * Get the Model name
     * 
     * Exemple : MyModel
     * 
     * @return string
     */
    public function getModelStudly() {
        return $this->model->last();
    }

    /**
     * Get the Model camel cased name
     * 
     * Exemple : myModel
     * 
     * @return string
     */
    public function getModelCamel() {
        return Str::camel($this->model->last());
    }

    /**
     * Get the Model plural full name
     * 
     * Exemple : MyGrandParent\MyParent\MyModels
     * 
     * @return string
     */
    public function getPluralFullName() {
        return $this->plural->implode('\\');
    }

    /**
     * Get the Model plural name camel cased
     * 
     * Exemple : myModels
     * 
     * @return string
     */
    public function getPluralCamel() {
        return Str::camel($this->plural->last());
    }

    /**
     * Get the plurals version of Model full name
     * 
     * Exemple : MyGrandParents\MyParents\MyModels
     * 
     * @return string
     */
    public function getPluralsFullName() {
        return $this->plurals->implode('\\');
    }

    /**
     * Get the plurals version of Model full name kebab cased and separated with dots
     * 
     * Exemple : my-grand-parents.my-parents.my-models
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
     * Get the plurals version of Model full name kebab cased and separated with slashes
     * 
     * Exemple : my-grand-parents/my-parents/my-models
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
     * Get the Model's table name
     * 
     * Exemple : my_grand_parents_my_parents_my_models
     * 
     * @return string
     */
    public function getTableName() {
        return Str::snake($this->plurals->implode(''));
    }

    /**
     * Get the migration class name
     * 
     * Exemple : CreateMyGrandParentsMyParentsMyModelTable
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
     * Get the Model namespace
     * 
     * Exemple : App\MyGrandParent\MyParent
     * 
     * @return string
     */
    public function getModelNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim($this->modelsSubDirectory() . '\\' . $parents->implode('\\'), '\\');
    }

    /**
     * Get the Model full name with namespace
     * 
     * Exemple : App\MyGrandParent\MyParent\MyModel
     * 
     * @return string
     */
    public function getModelClass() {
        return app()->getNamespace() . trim($this->modelsSubDirectory() . '\\' . $this->model->implode('\\'), '\\');
    }

    /**
     * Get the Request class name
     * 
     * Exemple : MyModelFormRequest
     * 
     * @return string
     */
    public function getRequestClass() {
        return $this->model->last() . 'FormRequest';
    }

    /**
     * Get the Request class namespace
     * 
     * Exemple : Http\Requests\MyGrandParent\MyParent
     * 
     * @return string
     */
    public function getRequestNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim('Http\\Requests\\' . $parents->implode('\\'), '\\');
    }

    /**
     * Get the Controller class name
     * 
     * Exemple : MyModelController
     * 
     * @return string
     */
    public function getControllerClass() {
        return $this->model->last() . 'Controller';
    }

    /**
     * Get the Controller class name with parents
     * 
     * Exemple : MyGrandParent\MyParent\MyModelController
     * 
     * @return string
     */
    public function getControllerFullName() {
        return $this->getModelFullName() . 'Controller';
    }

    /**
     * Get the Controller class namespace
     * 
     * Exemple : Http\Controllers\MyGrandParent\MyParent
     * 
     * @return string
     */
    public function getControllerNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim('Http\\Controllers\\' . $parents->implode('\\'), '\\');
    }

    /**
     * Get the layout to extend in views
     * 
     * @return string
     */
    public function getViewsLayout() {
        return $this->layout;
    }

}
