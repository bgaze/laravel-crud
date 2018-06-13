<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Field;
/**
 * Description of IndexView
 *
 * @author bgaze
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
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('index-view');

        $this
                ->replace($stub, '#THEAD', $this->tableHead())
                ->replace($stub, '#TBODY', $this->tableBody())
        ;

        return $this->generateFile($this->file(), $stub);
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

        return $content
                        ->map(function(Field $field) {
                            return sprintf('<th>%s</th>', $field->label());
                        })
                        ->implode("\n                ");
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

        return $content
                        ->map(function(Field $field) {
                            $stub = '<td>{{ $ModelCamel->FieldName }}</td>';
                            $this->replace($stub, 'ModelCamel')->replace($stub, 'FieldName', $field->label());
                            return $stub;
                        })
                        ->implode("\n                    ");
    }

}
