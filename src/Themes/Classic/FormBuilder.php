<?php

namespace Bgaze\Crud\Themes\Classic;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * A form view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class FormBuilder extends Builder {

    /**
     * Build the form view file.
     * 
     * @param string $viewStub      The main stub to use to compile form view file
     * @param string $groupStub     The stub to use to compile a field form group
     * @return string               The relative path of the generated file
     */
    public function buildForm($viewStub, $groupStub) {
        $stub = $this->stub($viewStub);

        $this->replace($stub, '#CONTENT', $this->content($groupStub));

        return $this->generateFile($this->file(), $stub);
    }

    /**
     * Compile form fields.
     * 
     * @param string $groupStub The stub to use to compile a field form group.
     * @return string
     */
    protected function content($groupStub) {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '    <!-- TODO -->';
        }

        return $content
                        ->map(function(Field $field) use($groupStub) {
                            return $this->formGroup($field, $groupStub);
                        })
                        ->implode("\n");
    }

    /**
     * Compile a form group.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @param string $groupStub                 The stub to use to compile a field form group.
     * @return string
     */
    protected function formGroup(Field $field, $groupStub) {
        $method = $field->config('type') . 'Template';

        if (method_exists($this, $method)) {
            $template = $this->{$method}($field);
        } else {
            $template = $this->defaultTemplate($field);
        }

        $stub = $this->stub($groupStub);

        $this
                ->replace($stub, '#FIELD', $template)
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

    /**
     * Compile a boolean field to checkbox.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function booleanTemplate(Field $field) {
        return "Form::checkbox('FieldName', '1')";
    }

    /**
     * Compile an array field to select.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function arrayTemplate(Field $field) {
        $choices = $field->input()->getArgument('allowed');

        if ($field->input()->getOption('nullable')) {
            array_unshift($choices, '');
        }

        $value = $this->compileArrayForPhp(array_combine($choices, $choices), true);

        return sprintf("Form::select('FieldName', %s)", $value);
    }

    /**
     * Compile a field to text input.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @return string
     */
    protected function defaultTemplate(Field $field) {
        return "Form::text('FieldName')";
    }

}
