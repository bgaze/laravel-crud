<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Crud as Base;
use Bgaze\Crud\Theme\Builders;

/**
 * Description of Crud
 *
 * @author bgaze
 */
class Crud extends Base {

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    static public function name() {
        return 'default';
    }

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    static public function stubs() {
        return [
            'partials.index-head' => __DIR__ . '/Stubs/partials/index-head.stub',
            'partials.index-body' => __DIR__ . '/Stubs/partials/index-body.stub',
            'partials.show-group' => __DIR__ . '/Stubs/partials/show-group.stub',
            'partials.form-group' => __DIR__ . '/Stubs/partials/form-group.stub',
            'views.index' => __DIR__ . '/Stubs/views/index.stub',
            'views.show' => __DIR__ . '/Stubs/views/show.stub',
            'views.create' => __DIR__ . '/Stubs/views/create.stub',
            'views.edit' => __DIR__ . '/Stubs/views/edit.stub',
            'migration' => __DIR__ . '/Stubs/migration.stub',
            'model' => __DIR__ . '/Stubs/model.stub',
            'factory' => __DIR__ . '/Stubs/factory.stub',
            'seeds' => __DIR__ . '/Stubs/seeds.stub',
            'request' => __DIR__ . '/Stubs/request.stub',
            'controller' => __DIR__ . '/Stubs/controller.stub',
            'routes' => __DIR__ . '/Stubs/routes.stub',
        ];
    }

    /**
     * The builders availables in the CRUD theme.
     * 
     * @return array Name as key, full class name as value.
     */
    static public function builders() {
        return [
            'migration-class' => Builders\Migration::class,
            'model-class' => Builders\Model::class,
            'factory-file' => Builders\Factory::class,
            'seeds-class' => Builders\Seeds::class,
            'request-class' => Builders\Request::class,
            'controller-class' => Builders\Controller::class,
            'index-view' => Builders\Views\Index::class,
            'create-view' => Builders\Views\Create::class,
            'edit-view' => Builders\Views\Edit::class,
            'show-view' => Builders\Views\Show::class,
        ];
    }

    ############################################################################
    # VARIABLES

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
     * Exemple : MyGrandParent\MyParent\MyModel
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
     * Exemple : MyGrandParent\MyParent\MyModel
     * 
     * @return string
     */
    public function getPluralFullStudly() {
        return $this->plural->implode('');
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

}
