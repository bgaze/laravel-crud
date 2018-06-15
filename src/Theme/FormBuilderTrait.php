<?php

namespace Bgaze\Crud\Theme;

use Bgaze\Crud\Core\Field;

/**
 *
 * @author bgaze
 */
trait FormBuilderTrait {

    /**
     * TODO
     * 
     * @return type
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
     * TODO
     * 
     * @return type
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
     * TODO
     * 
     * @return type
     */
    protected function formGroup(Field $field) {
        $stub = $this->stub('form-group');

        $this
                ->replace($stub, '#FIELD', $this->formField($field))
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

}
