<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * The Request class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Request extends Builder {

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
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('request');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile the file content.
     * 
     * @return string
     */
    protected function content() {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '// TODO';
        }

        return $content
                        ->map(function(Field $field) {
                            return $this->requestGroup($field);
                        })
                        ->implode("\n");
    }

    /**
     * Compile content to request class body line.
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string
     */
    protected function requestGroup(Field $field) {
        $rules = [];

        $definition = $field->definition();

        if ($definition->hasOption('nullable')) {
            $rules[] = $field->input()->getOption('nullable') ? 'nullable' : 'required';
        } elseif (preg_match('/^nullable/', $field->config('type'))) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        $rules[] = $this->getTypeRules($field);

        if (in_array('unique', $definition->getOptions()) && $definition->getOption('unique')) {
            $rules[] = 'unique:' . $this->crud->getTableName() . ',' . $field->name();
        }

        return sprintf("'%s' => '%s',", $field->name(), implode('|', array_filter($rules)));
    }

    /**
     * Get rules template based on field type.
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string|null
     */
    protected function getTypeRules(Field $field) {
        switch ($field->config('type')) {
            case 'boolean':
                return 'boolean';
            case 'integer':
                return 'integer';
            case 'float':
                return 'numeric';
            case 'date':
                return 'date';
            case 'array':
                return 'in:' . implode(',', $field->input()->getArgument('allowed'));
            default:
                return null;
        }
    }

}
