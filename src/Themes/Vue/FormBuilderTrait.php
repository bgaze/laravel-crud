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
                return '<input type="checkbox" id="FieldName" v-model="ModelCamel.FieldName"/>';
            case 'array':
                $choices = $field->input()->getArgument('allowed');

                if ($field->input()->getOption('nullable')) {
                    array_unshift($choices, '');
                }

                $field = '<select id="FieldName" v-model="ModelCamel.FieldName">';
                foreach ($choices as $choice) {
                    $field .= sprintf("\n%s<option value=\"%s\">%s</option>", str_repeat(' ', 12), $choice, $choice);
                }
                return $field . str_repeat(' ', 8) . '</select>';
            default:
                return '<input type="text" id="FieldName" v-model="ModelCamel.FieldName"/>';
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
