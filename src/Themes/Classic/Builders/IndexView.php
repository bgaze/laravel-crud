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
                            return $this->fieldCell($field, $stub);
                        })
                        ->filter()
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
                            return $this->fieldCell($field, $stub);
                        })
                        ->filter()
                        ->implode("\n");
    }

    /**
     * Generate a table cell for a field
     * 
     * @param Field $field
     * @param string $stub
     * @return string
     */
    protected function fieldCell(Field $field, $stub) {
        if (in_array($field->name(), ['rememberToken', 'softDeletes', 'softDeletesTz'])) {
            return null;
        }

        if (in_array($field->name(), ['timestamps', 'timestampsTz'])) {
            return $this->tableCell($stub, 'Created at', 'created_at') . "\n" . $this->tableCell($stub, 'Updated at', 'updated_at');
        }

        return $this->tableCell($stub, $field->label(), $field->name());
    }

    /**
     * Generate a table cell 
     * 
     * @param string $stub
     * @param string $label
     * @param string $name
     * @return string
     */
    protected function tableCell($stub, $label, $name) {
        $this->replace($stub, 'FieldLabel', $label)->replace($stub, 'FieldName', $name);
        return $stub;
    }

}
