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
    protected $timestamps = false;

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    protected $softDeletes = false;

    /**
     * Model's Field objects.
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
        $columns = $this->columns();

        // If index
        if ($field->isIndex()) {
            // Check that it doesn't already exists.
            if ($this->content()->has($field->name())) {
                throw new \Exception("'{$field->name()}' index already exists.");
            }

            // Check that all selected columns exists.
            foreach ($field->input()->getArgument('columns') as $column) {
                if (!$columns->contains($field->name())) {
                    throw new \Exception("'$column' doesn't exists in fields list.");
                }
            }
        }

        // Otherwise check that no field alreay exists.
        $intersect = $columns->intersect($field->columns());
        if ($intersect->isNotEmpty()) {
            throw new \Exception("Following columns already exist: " . $intersect->implode(', '));
        }

        // Add to fields list.
        $this->content()->put($field->name(), $field);

        // Manage timestamps & softDeletes status.
        if ($field->name() === 'timestamps' || $field->name() === 'timestampsTz') {
            $this->timestamps = $field->name();
        }
        if ($field->name() === 'softDeletes' || $field->name() === 'softDeletesTz') {
            $this->softDeletes = $field->name();
        }
    }

    /**
     * Reorder the CRUD content this way :
     * - Relations first
     * - Then columns except timestamps & softDeletes
     * - Then timestamps
     * - Then softDeletes
     * - Then indexes
     */
    public function reorderContent() {
        $content = collect();

        // Relations first.
        $this->content->filter(function(Field $field, $key) {
            return $field->isRelation();
        })->each(function(Field $field, $key) use($content) {
            $content->put($key, $field);
        });

        // Then columns except timestamps & softDeletes.
        $this->content->filter(function(Field $field) {
            if (in_array($field->name(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'])) {
                return false;
            }
            return (!$field->isRelation() && !$field->isIndex());
        })->each(function(Field $field, $key) use($content) {
            $content->put($key, $field);
        });

        // Then timestamps.
        $this->content->filter(function(Field $field) {
            return in_array($field->name(), ['timestamps', 'timestampsTz']);
        })->each(function(Field $field, $key) use($content) {
            $content->put($key, $field);
        });

        // Then softDeletes.
        $this->content->filter(function(Field $field) {
            return in_array($field->name(), ['softDeletes', 'softDeletesTz']);
        })->each(function(Field $field, $key) use($content) {
            $content->put($key, $field);
        });

        // Then indexes.
        $this->content->filter(function(Field $field) {
            return $field->isIndex();
        })->each(function(Field $field, $key) use($content) {
            $content->put($key, $field);
        });

        // Update CRUD content.
        $this->content = $content;
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
        return $this->content(false)
                        ->map(function(Field $field) {
                            return $field->columns();
                        })
                        ->prepend('id')
                        ->flatten();
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

}
