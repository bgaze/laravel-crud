<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;

/**
 * Description of Model
 *
 * @author bgaze
 */
class Model {

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

    # CONFIGURATION

    /**
     * The constructor of the class.
     *
     * @return void
     */
    public function __construct($model, $plurals = false) {
        // Parse model input to get model full name.
        $this->setModel($model);

        // Init plurals.
        $this->setPlurals($plurals);
    }

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

    # GETTERS

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

    # NAMES VARIABLES

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
