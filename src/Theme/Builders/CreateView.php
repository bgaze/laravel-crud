<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of CreateView
 *
 * @author bgaze
 */
class CreateView extends Builder {

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
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('create-view');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function content() {
        $stub = $this->stub('form-group');

        return $this->crud
                        ->content(false)
                        ->map(function(Field $field) use($stub) {
                            return $this->formGroup($field);
                        })
                        ->implode("\n");
    }

    /**
     * TODO
     * 
     * @return type
     */
    protected function formGroup(Field $field) {
        $stub = $this->stub('form-group');

        switch ($field->config('type')) {
            case 'boolean':
                $template = "Form::checkbox('FieldName', '1')";
                break;
            case 'array':
                $choices = $field->input()->getArgument('allowed');
                $template = "Form::select('FieldName', " . $this->compileValueForPhp($choices) . ")";
                break;
            default:
                $template = "Form::text('FieldName')";
                break;
        }

        $this->replace($stub, '#FIELD', $template);

        return $stub;
    }

}
