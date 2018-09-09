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

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    abstract static public function name();

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
     * The views namespace.
     * 
     * It is used to publish and register Theme's views.
     * 
     * @return string
     */
    static public function views() {
        return 'crud-' . str_replace(':', '-', static::name());
    }

    /**
     * The Theme base layout.
     * 
     * The default layout to extend in views.
     * 
     * @return string
     */
    static public function layout() {
        return static::views() . '::layout';
    }

    ############################################################################
    # CONFIGURATION

    /**
     * Parse and validate Model's name and parents.
     * 
     * @param string $value The name of the model including parents
     * @return \Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function setModel($value) {
        $model = str_replace('/', '\\', trim($value, '\\/ '));

        if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $model)) {
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
            if (!preg_match('/^((([A-Z][a-z]+)+)\\\\)*(([A-Z][a-z]+)+)$/', $value)) {
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
        if ($value) {
            $this->layout = $this->option('layout');
        } elseif (config('crud.layout')) {
            $this->layout = config('crud.layout');
        } else {
            $this->layout = self::layout();
        }
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
        if ($this->content()->has($field->name())) {
            $type = $field->isIndex() ? 'index' : 'field';
            throw new \Exception("'{$field->name()}' {$type} already exists.");
        }

        // If field is an index, check that all selected columns exists.
        if ($field->config('type') === 'index') {
            foreach ($field->input()->getArgument('columns') as $column) {
                if (!$this->has($column)) {
                    throw new \Exception("'$column' doesn't exists in fields list.");
                }
            }
        }

        // Add to fields list.
        $this->content()->put($field->name(), $field);
    }

    /**
     * Check if a content already exists into CRUD.
     * 
     * @param string $id The unique name of the content
     * @return boolean
     */
    protected function has($id) {
        if (($id === 'created_at' || $id === 'updated_at') && $this->timestamps()) {
            return true;
        }

        if ($id === 'deleted_at' && $this->softDeletes()) {
            return true;
        }

        return $this->content()->has($id);
    }

    ############################################################################
    # GETTERS

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
        $columns = $this->content(false)->pluck('name');

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

}
