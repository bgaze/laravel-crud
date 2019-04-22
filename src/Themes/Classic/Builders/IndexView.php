<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;

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
                        ->map(function(Entry $entry) use($stub) {
                            return $this->entryCell($entry, $stub);
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
                        ->map(function(Entry $entry) use($stub) {
                            return $this->entryCell($entry, $stub);
                        })
                        ->filter()
                        ->implode("\n");
    }

    /**
     * Generate a table cell for a entry
     * 
     * @param Entry $entry
     * @param string $stub
     * @return string
     */
    protected function entryCell(Entry $entry, $stub) {
        if (in_array($entry->name(), ['rememberToken', 'softDeletes', 'softDeletesTz'])) {
            return null;
        }

        if (in_array($entry->name(), ['timestamps', 'timestampsTz'])) {
            return $this->tableCell($stub, 'Created at', 'created_at') . "\n" . $this->tableCell($stub, 'Updated at', 'updated_at');
        }

        return $this->tableCell($stub, $entry->label(), $entry->name());
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
        $this->replace($stub, 'EntryLabel', $label)->replace($stub, 'EntryName', $name);
        return $stub;
    }

}
