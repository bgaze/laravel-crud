<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;

/**
 * The Request class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class RequestClass extends Builder {

    use FieldsTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path('Http/Requests/' . $this->crud->model()->implode('/') . 'FormRequest.php');
    }

    /**
     * Build the file.
     */
    public function build() {
        $stub = $this->stub('request');

        $this->replace($stub, '#CONTENT', $this->content());

        $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile the file content.
     * 
     * @return string
     */
    protected function content() {
        $content = $this->crud
                ->content(false)
                ->map(function(Field $field) {
                    $template = $this->fieldTemplate($field);

                    if ($template === false) {
                        return false;
                    }

                    return $this->requestGroup($field, $template);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '// TODO';
        }

        return $content;
    }

    /**
     * Compile content to request class body line.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field
     * @param string $template                  The field rules
     * @return string
     */
    protected function requestGroup(Field $field, $template) {
        $rules = [];
        $definition = $field->definition();

        if ($definition->hasOption('nullable')) {
            $rules[] = $field->input()->getOption('nullable') ? 'nullable' : 'required';
        } elseif (preg_match('/^nullable/', $field->config('type'))) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        $rules[] = $template;

        if (in_array('unique', $definition->getOptions()) && $definition->getOption('unique')) {
            $rules[] = 'unique:' . $this->crud->getTableName() . ',' . $field->name();
        }

        return sprintf("'%s' => '%s',", $field->name(), implode('|', array_filter($rules)));
    }

    /**
     * Get the default rules for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules of the field
     */
    public function defaultTemplate(Field $field) {
        return null;
    }

    /**
     * Get the rules for a bigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function bigIntegerTemplate(Field $field) {
        return 'integer';
    }

    /**
     * Get the rules for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function booleanTemplate(Field $field) {
        return 'boolean';
    }

    /**
     * Get the rules for a date field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function dateTemplate(Field $field) {
        return 'date_format:Y-m-d';
    }

    /**
     * Get the rules for a dateTime field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function dateTimeTemplate(Field $field) {
        return 'date_format:Y-m-d H:i:s';
    }

    /**
     * Get the rules for a dateTimeTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function dateTimeTzTemplate(Field $field) {
        return 'date_format:Y-m-d H:i:s';
    }

    /**
     * Get the rules for a decimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function decimalTemplate(Field $field) {
        return 'numeric';
    }

    /**
     * Get the rules for a double field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function doubleTemplate(Field $field) {
        return 'numeric';
    }

    /**
     * Get the rules for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function enumTemplate(Field $field) {
        return 'in:' . implode(',', $field->input()->getArgument('allowed'));
    }

    /**
     * Get the rules for a float field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function floatTemplate(Field $field) {
        return 'numeric';
    }

    /**
     * Get the rules for a integer field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function integerTemplate(Field $field) {
        return 'integer|min:-2147483648|max:2147483647';
    }

    /**
     * Get the rules for a ipAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function ipAddressTemplate(Field $field) {
        return 'ip';
    }

    /**
     * Get the rules for a json field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function jsonTemplate(Field $field) {
        return 'array';
    }

    /**
     * Get the rules for a jsonb field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function jsonbTemplate(Field $field) {
        return 'array';
    }

    /**
     * Get the rules for a macAddress field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function macAddressTemplate(Field $field) {
        return 'regex:^([0-9a-fA-F]{2}:){5}([0-9a-fA-F]{2})$';
    }

    /**
     * Get the rules for a mediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function mediumIntegerTemplate(Field $field) {
        return 'integer|min:-8388608|max:8388607';
    }

    /**
     * Get the rules for a smallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function smallIntegerTemplate(Field $field) {
        return 'integer|min:-32768|max:32767';
    }

    /**
     * Get the template for a softDeletes field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a softDeletesTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTzTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a timestamps field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTemplate(Field $field) {
        return false;
    }

    /**
     * Get the template for a timestampsTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTzTemplate(Field $field) {
        return false;
    }

    /**
     * Get the rules for a tinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function tinyIntegerTemplate(Field $field) {
        return 'integer|min:-128|max:127';
    }

    /**
     * Get the rules for a unsignedBigInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedBigIntegerTemplate(Field $field) {
        return 'integer|min:0';
    }

    /**
     * Get the rules for a unsignedDecimal field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedDecimalTemplate(Field $field) {
        return 'numeric';
    }

    /**
     * Get the rules for a unsignedInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedIntegerTemplate(Field $field) {
        return 'integer|min:0|max:4294967295';
    }

    /**
     * Get the rules for a unsignedMediumInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedMediumIntegerTemplate(Field $field) {
        return 'integer|min:0|max:16777215';
    }

    /**
     * Get the rules for a unsignedSmallInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedSmallIntegerTemplate(Field $field) {
        return 'integer|min:0|max:65535';
    }

    /**
     * Get the rules for a unsignedTinyInteger field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function unsignedTinyIntegerTemplate(Field $field) {
        return 'integer|min:0|max:255';
    }

    /**
     * Get the rules for a uuid field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function uuidTemplate(Field $field) {
        return 'regex:^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-4[0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$';
    }

    /**
     * Get the rules for a year field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The rules for the field
     */
    public function yearTemplate(Field $field) {
        return 'regex:^\d{4}$';
    }

}
