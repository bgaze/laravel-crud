<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Support\Str;
use Bgaze\Crud\Core\Crud as Base;
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
class Crud extends Base {

    /**
     * The unique name of the CRUD theme.
     * 
     * It is used to register Theme's singleton.
     * 
     * @return string
     */
    static public function name() {
        return 'crud-default';
    }

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
        return $this->stubInDir(__DIR__ . '/stubs', $name);
    }

    /**
     * Initialize CRUD content.
     * 
     * @return \Bgaze\Crud\Core\Content
     */
    protected function instantiateContent() {
        return new Content($this);
    }

    ############################################################################
    # PATHES

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
