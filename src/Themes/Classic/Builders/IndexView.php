<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;

/**
 * The Index view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class IndexView extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('views/' . $this->crud->getPluralsKebabSlash() . "/index.blade.php");
    }

    /**
     * Build the file.
     */
    public function build() {
        $stub = $this->stub('views.index');

        $this
                ->replace($stub, '#THEAD', $this->tableHead())
                ->replace($stub, '#TBODY', $this->tableBody())
        ;

        $this->generateFile($this->file(), $stub);
    }

    /**
     * Compile content to index view table head cell.
     * 
     * @return string
     */
    protected function tableHead() {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '<!-- TODO -->';
        }

        $stub = $this->stub('partials.index-head');

        return $content
                        ->map(function(Field $field) use($stub) {
                            $this
                            ->replace($stub, 'FieldLabel', $field->label())
                            ->replace($stub, 'FieldName', $field->name());
                            return $stub;
                        })
                        ->implode("\n");
    }

    /**
     * Compile content to index view table body cell.
     * 
     * @return string
     */
    protected function tableBody() {
        $content = $this->crud->content(false);

        if ($content->isEmpty()) {
            return '<!-- TODO -->';
        }

        $stub = $this->stub('partials.index-body');

        return $content
                        ->map(function(Field $field) use($stub) {
                            $this
                            ->replace($stub, 'FieldLabel', $field->label())
                            ->replace($stub, 'FieldName', $field->name());
                            return $stub;
                        })
                        ->implode("\n");
    }

}
