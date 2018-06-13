<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * Description of ShowView
 *
 * @author bgaze
 */
class ShowView extends Builder {

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
        $stub = $this->stub('show-view');
        $this->replace($stub, '#CONTENT', $this->content());
        return $this->generateFile($this->file(), $stub);
    }

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
                            return $this->showGroup($field);
                        })
                        ->implode("\n");
    }

    /**
     * Compile content to request show view group.
     * 
     * @return string
     */
    protected function showGroup(Field $field) {
        $stub = $this->stub('show-group');

        $this
                ->replace($stub, 'FieldLabel', $field->label())
                ->replace($stub, 'FieldName', $field->name())
        ;

        return $stub;
    }

}
