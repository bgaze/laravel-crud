<?php

namespace Bgaze\Crud\Themes\Api;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Crud as Base;
use Bgaze\Crud\Themes\Api\Builders;

/**
 * The core class of the CRUD theme
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Crud extends Base {

    /**
     * The unique name of the CRUD theme.
     * 
     * @return string
     */
    static public function name() {
        return 'crud:api';
    }

    /**
     * The description the CRUD theme.
     * 
     * @return string
     */
    static public function description() {
        return 'Generate a REST API CRUD files: <fg=cyan>migration, model, factory, seeder, request, resource, controller, routes</>';
    }

    /**
     * The stubs availables in the CRUD theme.
     * 
     * @return array Name as key, absolute path as value.
     */
    static public function stubs() {
        return [
            'migration' => __DIR__ . '/Stubs/migration.stub',
            'model' => __DIR__ . '/Stubs/model.stub',
            'factory' => __DIR__ . '/Stubs/factory.stub',
            'seeds' => __DIR__ . '/Stubs/seeds.stub',
            'request' => __DIR__ . '/Stubs/request.stub',
            'resource' => __DIR__ . '/Stubs/resource.stub',
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
            'migration-class' => Builders\MigrationClass::class,
            'model-class' => Builders\ModelClass::class,
            'factory-file' => Builders\FactoryFile::class,
            'seeds-class' => Builders\SeedsClass::class,
            'request-class' => Builders\RequestClass::class,
            'resource-class' => Builders\ResourceClass::class,
            'controller-class' => Builders\ControllerClass::class,
            'routes-registration' => Builders\RoutesRegistration::class,
        ];
    }

    /**
     * Get CRUD index url to display in successfull generation message.
     * 
     * Cannot be generated with route() helper as created routes are not
     * loaded in current process.
     * 
     * @return string
     */
    public function indexPath() {
        return url('api/' . $this->getPluralsKebabSlash());
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
