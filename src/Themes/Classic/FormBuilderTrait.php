<?php

namespace Bgaze\Crud\Themes\Classic;

use Bgaze\Crud\Core\Field;

/**
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
trait FormBuilderTrait {

    /**
     * Build the form fields.
     * 
     * @return string
     */
    protected function content() {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '    <!-- TODO -->';
        }

        return $content
                        ->map(function(Field $field) {
                            return $this->formGroup($field);
                        })
                        ->implode("\n");
    }

    /**
     * Build a form field
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string
     */
    protected function formField(Field $field) {
        switch ($field->config('type')) {
            case 'boolean':
                return "Form::checkbox('FieldName', '1')";
            case 'array':
                $choices = $field->input()->getArgument('allowed');
                if ($field->input()->getOption('nullable')) {
                    array_unshift($choices, '');
                }
                $choices = array_combine($choices, $choices);
                return sprintf("Form::select('FieldName', %s)", $this->compileArrayForPhp($choices, true));
            default:
                return "Form::text('FieldName')";
        }
    }

    /**
     * Build a form group
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string
     */
    protected function formGroup(Field $field) {
        $stub = $this->stub('partials.form-group');

        $this
                ->replace($stub, '#FIELD', $this->formField($field))
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

}
