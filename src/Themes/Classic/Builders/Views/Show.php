<?php

namespace Bgaze\Crud\Themes\Classic\Builders\Views;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * The Show view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Show extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('views/' . $this->crud->getPluralsKebabSlash() . "/show.blade.php");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('views.show');
        $this->replace($stub, '#CONTENT', $this->content());
        return $this->generateFile($this->file(), $stub);
    }

    /**
     * Build the class content.
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
                            return $this->showGroup($field);
                        })
                        ->implode("\n");
    }

    /**
     * Compile content to request show view group.
     * 
     * @param \Bgaze\Crud\Core\Field $field
     * @return string
     */
    protected function showGroup(Field $field) {
        $stub = $this->stub('partials.show-group');

        $this
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

}
