<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;

/**
 * Description of Model
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ModelClass extends Builder {

    use FieldsTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path(trim($this->crud->modelsSubDirectory() . '/' . $this->crud->model()->implode('/') . '.php', '/'));
    }

    /**
     * Build the file.
     */
    public function build() {
        $stub = $this->stub('model');

        $this
                ->replace($stub, '#TIMESTAMPS', $this->timestamps())
                ->replace($stub, '#SOFTDELETE', $this->softDeletes())
                ->replace($stub, '#FILLABLES', $this->fillables())
                ->replace($stub, '#DATES', $this->dates())
                ->replace($stub, '#PROPERTIES', $this->properties())
        ;

        $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile CRUD timestamps.
     * 
     * @return string
     */
    protected function timestamps() {
        return $this->crud->timestamps() ? 'public $timestamps = true;' : '';
    }

    /**
     * Compile CRUD soft deletes.
     * 
     * @return string
     */
    protected function softDeletes() {
        return $this->crud->softDeletes() ? 'use \Illuminate\Database\Eloquent\SoftDeletes;' : '';
    }

    /**
     * Compile CRUD content to Model fillables array.
     * 
     * @return string
     */
    protected function fillables() {
        $fillables = $this->crud
                ->content(false)
                ->map(function(Field $field) {
                    if (in_array($field->name(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'])) {
                        return false;
                    }
                    return $field->columns();
                })
                ->flatten()
                ->filter()
                ->toArray();
        return 'protected $fillable = ' . $this->compileArrayForPhp($fillables) . ';';
    }

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    protected function dates() {
        $dates = $this->crud
                ->content(false)
                ->filter(function(Field $field) {
                    return $field->isDate();
                })
                ->keys();

        if ($this->crud->softDeletes() && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . $this->compileArrayForPhp($dates->toArray()) . ';';
    }

    /**
     * Compile CRUD content to phpDocumentor properties annotations.
     * 
     * @return string
     */
    protected function properties() {
        $content = $this->crud->content(false)->map(function(Field $field) {
            return $this->fieldTemplate($field);
        });

        return "/**\n" . $content->implode("\n") . "\n */";
    }

    /**
     * Generate a phpDocumentor property annotation
     * 
     * @param string $type  The property type
     * @param string $name  The property name
     * @return string
     */
    protected function property($type, $name) {
        return "* @property {$type} \${$name}";
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return $this->property('string', $field->name());
    }

    /**
     * Get the template for a bigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function bigIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return $this->property('boolean', $field->name());
    }

    /**
     * Get the template for a date field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTemplate(Field $field) {
        return $this->property('\Carbon\Carbon', $field->name());
    }

    /**
     * Get the template for a dateTime field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a dateTimeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function dateTimeTzTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a decimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function decimalTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a double field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function doubleTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a float field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function floatTemplate(Field $field) {
        return $this->property('float', $field->name());
    }

    /**
     * Get the template for a integer field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function integerTemplate(Field $field) {
        return $this->property('integer', $field->name());
    }

    /**
     * Get the template for a json field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonTemplate(Field $field) {
        return $this->property('array', $field->name());
    }

    /**
     * Get the template for a jsonb field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function jsonbTemplate(Field $field) {
        return $this->jsonTemplate($field);
    }

    /**
     * Get the template for a mediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function mediumIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a morphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function morphsTemplate(Field $field) {
        return $this->property('integer', $field->name() . '_id') . "\n" . $this->property('string', $field->name() . '_type');
    }

    /**
     * Get the template for a nullableMorphs field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function nullableMorphsTemplate(Field $field) {
        return $this->morphsTemplate($field);
    }

    /**
     * Get the template for a smallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function smallIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a softDeletes field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTemplate(Field $field) {
        return $this->property('\Carbon\Carbon', 'deleted_at');
    }

    /**
     * Get the template for a softDeletesTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTzTemplate(Field $field) {
        return $this->softDeletesTemplate($field);
    }

    /**
     * Get the template for a time field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a timeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timeTzTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a timestamp field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a timestampTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampTzTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

    /**
     * Get the template for a timestamps field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTemplate(Field $field) {
        return $this->property('\Carbon\Carbon', 'created_at') . "\n" . $this->property('\Carbon\Carbon', 'updated_at');
    }

    /**
     * Get the template for a timestampsTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTzTemplate(Field $field) {
        return $this->timestampsTemplate($field);
    }

    /**
     * Get the template for a tinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function tinyIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a unsignedBigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedBigIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a unsignedDecimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedDecimalTemplate(Field $field) {
        return $this->floatTemplate($field);
    }

    /**
     * Get the template for a unsignedInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a unsignedMediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedMediumIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a unsignedSmallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedSmallIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a unsignedTinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function unsignedTinyIntegerTemplate(Field $field) {
        return $this->integerTemplate($field);
    }

    /**
     * Get the template for a year field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function yearTemplate(Field $field) {
        return $this->dateTemplate($field);
    }

}
