<?php


namespace Bgaze\Crud\Themes\Api;

use Bgaze\Crud\Support\Crud\Crud as BaseCrud;
use Bgaze\Crud\Support\Definitions;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Crud
 *
 * @property string $TableName  Example: my_grand_parent_my_parent_my_models
 * @property string $ResourceNamespace  Example: App\Http\Resources\MyGrandParent\MyParent
 * @property string $ResourceClass  Example: MyModelResource
 * @property string $RequestNamespace  Example: App\Http\Requests\MyGrandParent\MyParent
 * @property string $RequestClass  Example: MyModelFormRequest
 * @property string $PluralsParentsKebabDot  Example: my-grand-parents.my-parents
 * @property string $PluralsKebabSlash  Example: my-grand-parents/my-parents/my-models
 * @property string $PluralsKebabDot  Example: my-grand-parents.my-parents.my-models
 * @property string $PluralsFullStudly  Example: MyGrandParentsMyParentsMyModels
 * @property string $PluralsFullName  Example: MyGrandParents\MyParents\MyModels
 * @property string $PluralStudly  Example: MyModels
 * @property string $PluralFullStudly  Example: MyGrandParentMyParentMyModels
 * @property string $PluralFullName  Example: MyGrandParent\MyParent\MyModels
 * @property string $PluralCamel  Example: myModels
 * @property string $ModelStudly  Example: MyModel
 * @property string $ModelNamespace  Example: App\MyGrandParent\MyParent
 * @property string $ModelFullStudly  Example: MyGrandParentMyParentMyModel
 * @property string $ModelFullName  Example: MyGrandParent\MyParent\MyModel
 * @property string $ModelClass  Example: App\MyGrandParent\MyParent\MyModel
 * @property string $ModelCamel  Example: myModel
 * @property string $MigrationClass  Example: Create["MyGrandParents","MyParents","MyModels"]Table
 * @property string $ControllerNamespace  Example: App\Http\Controllers\MyGrandParent\MyParent
 * @property string $ControllerClass  Example: MyModelController
 */
class Crud extends BaseCrud
{

    /**
     * Set the Model parents and name.
     *
     * @param  Collection  $model
     *
     * @return  Crud
     */
    public function setModel(Collection $model)
    {
        parent::setModel($model);

        $httpNamespace = trim(app()->getNamespace() . 'Http\\%s\\' . $this->getParents()->implode('\\'), '\\');

        $this->addVariables([
            'ModelClass' => $this->model->toBase()->prepend(Definitions::modelsNamespace())->implode('\\'),
            'ModelNamespace' => $this->getParents()->toBase()->prepend(Definitions::modelsNamespace())->implode('\\'),
            'ModelFullName' => $this->model->implode('\\'),
            'ModelFullStudly' => $this->model->implode(''),
            'ModelStudly' => $this->model->last(),
            'ModelCamel' => Str::camel($this->model->last()),
            'ModelSnake' => Str::snake($this->model->last()),
            'RequestClass' => $this->model->last() . 'FormRequest',
            'RequestNamespace' => sprintf($httpNamespace, 'Requests'),
            'ResourceClass' => $this->model->last() . 'Resource',
            'ResourceNamespace' => sprintf($httpNamespace, 'Resources'),
            'ControllerClass' => $this->model->last() . 'Controller',
            'ControllerNamespace' => sprintf($httpNamespace, 'Controllers'),
        ]);

        return $this;
    }


    /**
     * Set the plural version of Model's parents and name.
     *
     * @param  Collection  $plurals
     *
     * @return  Crud
     */
    public function setPlurals(Collection $plurals)
    {
        parent::setPlurals($plurals);

        $pluralsKebab = $plurals->map(function ($v) {
            return Str::kebab($v);
        });

        $parentsKebab = $this->getParents(true)->map(function ($v) {
            return Str::kebab($v);
        });

        $this->addVariables([
            'PluralFullName' => $this->plural->implode('\\'),
            'PluralFullStudly' => $this->plural->implode(''),
            'PluralStudly' => Str::studly($this->plurals->last()),
            'PluralCamel' => Str::camel($this->plurals->last()),
            'PluralsFullName' => $this->plurals->implode('\\'),
            'PluralsFullStudly' => $this->plurals->implode(''),
            'PluralsKebabDot' => $pluralsKebab->implode('.'),
            'PluralsKebabSlash' => $pluralsKebab->implode('/'),
            'PluralsParentsKebabDot' => $parentsKebab->implode('.'),
            'TableName' => Str::snake($this->plural->implode('')),
            'MigrationClass' => 'Create' . $this->plural->implode('') . 'Table',
        ]);

        return $this;
    }

}