<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Field;

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
abstract class Crud {

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
     * The timestamps to use.
     *
     * @var boolean|string 
     */
    protected $timestamps;

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    protected $softDeletes;

    /**
     * Model's content (fields & indexes).
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $content;

    /**
     * The constructor of the class.
     *
     * @return void
     */
    public function __construct($model) {
        // Init CRUD content.
        $this->content = collect();

        // Parse model input to get model full name.
        $this->setModel($model);

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
    # Define theme identity and integrate it into Laravel.

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    abstract static public function name();

    /**
     * The description the CRUD theme.
     * 
     * @return string
     */
    abstract static public function description();

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    abstract static public function stubs();

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    abstract static public function builders();

    /**
     * The views directory.
     * 
     * This function should return the theme views location, or false if the theme has no views.
     * Any "Views" directory at the theme root will be used by default.
     * 
     * If defined, theme views will be registred and made publishable.
     * 
     * @return string|false
     */
    static public function views() {
        $dir = dirname((new \ReflectionClass(static::class))->getFileName()) . '/Views';
        return is_dir($dir) ? $dir : false;
    }

    /**
     * The views namespace.
     * 
     * It is used to publish and register Theme's views.
     * 
     * @return string
     */
    static public function viewsNamespace() {
        return static::views() ? str_replace(':', '-', static::name()) : false;
    }

    /**
     * The Theme base layout.
     * 
     * The default layout to extend in views.
     * 
     * @return string
     */
    static public function layout() {
        return static::views() ? static::viewsNamespace() . '::layout' : false;
    }

    ############################################################################
    # CONFIGURATION
    # Configure theme based on user input. 

    /**
     * Parse and validate Model's name and parents.
     * 
     * @param string $value The name of the model including parents
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function setModel($value) {
        $model = str_replace('/', '\\', trim($value, '\\/ '));

        if (!preg_match(config('crud.model_fullname_format'), $model)) {
            throw new \Exception("Model name is invalid.");
        }

        $this->model = collect(explode('\\', $model));
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
        $default = $this->model->map(function($v) {
            return Str::plural($v);
        });

        if (!empty($value)) {
            $error = "Plural names are invalid. It sould be something like : " . $default->implode('\\');

            $value = str_replace('/', '\\', trim($value, '\\/ '));
            if (!preg_match(config('crud.model_fullname_format'), $value)) {
                throw new \Exception($error);
            }

            $value = collect(explode('\\', $value));
            if ($value->count() !== $this->model->count()) {
                throw new \Exception($error);
            }

            $this->plurals = $value;
        } else {
            $this->plurals = $default;
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

        if ($value === 'none') {
            $this->timestamps = false;
        } else if (empty($value)) {
            $this->timestamps = $timestamps[0];
        } else {
            if (!in_array($value, $timestamps)) {
                throw new \Exception("Allowed values for timestamps are : " . implode(', ', $timestamps));
            }

            $this->timestamps = $value;
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

        if ($value === 'none') {
            $this->softDeletes = false;
        } else if (empty($value)) {
            $this->softDeletes = $softDeletes[0];
        } else {
            if (!in_array($value, $softDeletes)) {
                throw new \Exception("Allowed values for soft deletes are : " . implode(', ', $timestamps));
            }

            $this->softDeletes = $value;
        }
    }

    /**
     * Set default layout for CRUD's views.
     * 
     * @param type $value
     * @return void
     */
    public function setLayout($value = false) {
        $this->layout = $value ? $value : static::layout();
    }

    /**
     * Add a new content to CRUD.
     * 
     * @param string $type The type of the content
     * @param string $data The user parameters (signed input)
     * @throws \Exception
     * @return void
     */
    public function add($type, $data) {
        $field = new Field($type, $data);

        // Check that it doesn't already exists.
        if ($field->isIndex() && $this->content()->has($field->name())) {
            throw new \Exception("'{$field->name()}' index already exists.");
        }
        if ($this->columns()->contains($field->name())) {
            throw new \Exception("'{$field->name()}' column already exists.");
        }

        // If field is an index, check that all selected columns exists.
        if ($field->isIndex()) {
            foreach ($field->input()->getArgument('columns') as $column) {
                if (!$this->columns()->contains($field->name())) {
                    throw new \Exception("'$column' doesn't exists in fields list.");
                }
            }
        }

        // Add to fields list.
        $this->content()->put($field->name(), $field);
    }

    ############################################################################
    # GETTERS
    # Get CRUD related informations for internal use.

    /**
     * Get a list of variables present in the class (based on existing methods starting with 'get').
     *
     * @return array
     */
    public function variables() {
        $variables = [];

        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 3) === 'get') {
                $variables[] = substr($method, 3);
            }
        }

        rsort($variables);

        return $variables;
    }

    /**
     * Models sub-directory, based on global configuration.
     * 
     * @return string
     */
    public function modelsSubDirectory() {
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
     * The Model parents and name.<br/>
     * Example : ['MyGrandParent', 'MyParent', 'MyModel']
     *
     * @var \Illuminate\Support\Collection 
     */
    public function model() {
        return $this->model;
    }

    /**
     * The Model's parents and the plural version of its name.<br/>
     * Example : ['MyGrandParent', 'MyParent', 'MyModels']
     *
     * @var \Illuminate\Support\Collection 
     */
    public function plural() {
        return $this->plural;
    }

    /**
     * The plural version of Model's parents and name.<br/>
     * Example : ['MyGrandParents', 'MyParents', 'MyModels']
     *
     * @var \Illuminate\Support\Collection 
     */
    public function plurals() {
        return $this->plurals;
    }

    /**
     * The timestamps to use.
     *
     * @var boolean|string 
     */
    public function timestamps() {
        return $this->timestamps;
    }

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    public function softDeletes() {
        return $this->softDeletes;
    }

    /**
     * Model's content (fields & indexes).
     *
     * @var \Illuminate\Support\Collection 
     */
    public function content($withIndexes = true) {
        if (!$withIndexes) {
            return $this->content->filter(function(Field $field) {
                        return !$field->isIndex();
                    });
        }

        return $this->content;
    }

    /**
     * Model's table columns list.
     *
     * @var \Illuminate\Support\Collection 
     */
    public function columns() {
        $columns = $this->content(false)->map(function(Field $field) {
                    if ($field->command() === 'morphs' || $field->command() === 'nullableMorphs') {
                        return[$field->name() . '_id', $field->name() . '_type'];
                    }

                    return $field->name();
                })->flatten();

        $columns->prepend('id');

        if ($this->timestamps()) {
            $columns->push('created_at');
            $columns->push('updated_at');
        }

        if ($this->softDeletes()) {
            $columns->push('deleted_at');
        }

        return $columns;
    }

    /**
     * Get the layout to extend in views
     * 
     * @return string
     */
    public function getViewsLayout() {
        return $this->layout;
    }

    ############################################################################
    # NAMES VARIABLES
    # Main CRUD names and plurals formats.

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
     * Get the Model studly full name
     * 
     * Exemple : MyGrandParentMyParentMyModel
     * 
     * @return string
     */
    public function getModelFullStudly() {
        return $this->model->implode('');
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
     * Get the Model plural studly full name
     * 
     * Exemple : MyGrandParentMyParentMyModels
     * 
     * @return string
     */
    public function getPluralFullStudly() {
        return $this->plural->implode('');
    }

    /**
     * Get the Model plural name studly cased
     * 
     * Exemple : MyModels
     * 
     * @return string
     */
    public function getPluralStudly() {
        return Str::studly($this->plural->last());
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

    ############################################################################
    # RESOURCES VARIABLES
    # Main CRUD files and resources variables.

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
     * Get the Model namespace
     * 
     * Exemple : App\MyGrandParent\MyParent
     * 
     * @return string
     */
    public function getModelNamespace() {
        $parents = clone $this->model;
        $parents->pop();

        $namespace = trim($this->modelsSubDirectory() . '\\' . $parents->implode('\\'), '\\');

        return trim(app()->getNamespace() . $namespace, '\\');
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
     * Get the Request class name
     * 
     * Exemple : MyModelFormRequest
     * 
     * @return string
     */
    public function getResourceClass() {
        return $this->model->last() . 'Resource';
    }

    /**
     * Get the Request class namespace
     * 
     * Exemple : Http\Requests\MyGrandParent\MyParent
     * 
     * @return string
     */
    public function getResourceNamespace() {
        $parents = clone $this->model;
        $parents->pop();
        return app()->getNamespace() . trim('Http\\Resources\\' . $parents->implode('\\'), '\\');
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

}
