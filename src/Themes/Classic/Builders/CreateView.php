<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\FieldsTemplatesTrait;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class CreateView extends Builder {

    use FieldsTemplatesTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('views/' . $this->crud->getPluralsKebabSlash() . "/create.blade.php");
    }

    /**
     * Build the file.
     */
    public function build() {
        $this->buildForm('views.create', 'partials.form-group');
    }

    /**
     * Build the form view file.
     * 
     * @param string $viewStub      The main stub to use to compile form view file
     * @param string $groupStub     The stub to use to compile a field form group
     */
    public function buildForm($viewStub, $groupStub) {
        $stub = $this->stub($viewStub);

        $this->replace($stub, '#CONTENT', $this->content($groupStub));

        $this->generateFile($this->file(), $stub);
    }

    /**
     * Compile form fields.
     * 
     * @param string $groupStub The stub to use to compile a field form group.
     * @return string
     */
    protected function content($groupStub) {
        $content = $this->crud
                ->content(false)
                ->map(function(Field $field) use($groupStub) {
                    return $this->formGroup($field, $groupStub);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '    <!-- TODO -->';
        }

        return $content;
    }

    /**
     * Compile a form group.
     * 
     * @param \Bgaze\Crud\Core\Field $field     The field to compile
     * @param string $groupStub                 The stub to use to compile a field form group.
     * @return string
     */
    protected function formGroup(Field $field, $groupStub) {
        $template = $this->fieldTemplate($field);

        if (empty($template)) {
            return null;
        }

        $stub = $this->stub($groupStub);

        $this
                ->replace($stub, '#FIELD', $template)
                ->replace($stub, 'ModelCamel')
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

    /**
     * Get the default template for a field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function defaultTemplate(Field $field) {
        return "{!! Form::text('FieldName') !!}";
    }

    /**
     * Get the template for a boolean field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function booleanTemplate(Field $field) {
        return "<label for=\"FieldName0\">{!! Form::radio('FieldName', 0, !\$ModelCamel->FieldName, ['id' => 'FieldName0']) !!} No</label>"
                . "\n        <label for=\"FieldName1\">{!! Form::radio('FieldName', 1, \$ModelCamel->FieldName, ['id' => 'FieldName1']) !!} Yes</label>";
    }

    /**
     * Get the template for a enum field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function enumTemplate(Field $field) {
        $choices = $field->input()->getArgument('allowed');

        if ($field->input()->getOption('nullable')) {
            array_unshift($choices, '');
        }

        $value = $this->compileArrayForPhp(array_combine($choices, $choices), true);

        return sprintf("{!! Form::select('FieldName', %s) !!}", $value);
    }

    /**
     * Get the template for a text field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function textTemplate(Field $field) {
        return "{!! Form::textarea('FieldName') !!}";
    }

    /**
     * Get the template for a rememberToken field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function rememberTokenTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a softDeletes field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a softDeletesTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function softDeletesTzTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a timestamps field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTemplate(Field $field) {
        return null;
    }

    /**
     * Get the template for a timestampsTz field.
     * 
     * @param Bgaze\Crud\Core\Field $field The field 
     * @return string The template for the field
     */
    public function timestampsTzTemplate(Field $field) {
        return null;
    }

}
