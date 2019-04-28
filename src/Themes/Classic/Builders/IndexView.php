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
                ->replace($stub, '#THEAD', $this->compileContent('partials.index-head'))
                ->replace($stub, '#TBODY', $this->compileContent('partials.index-body'))
        ;

        $this->generateFile($this->file(), $stub);
    }

    /**
     * Run the 'index-rows' compiler against all CRUD entries.
     * 
     * @param string $stub          The stub used to generate rows
     * @return string
     */
    protected function compileContent($stub) {
        $compilers = $this->crud::compilers();
        $compiler = new $compilers['print-content']($this->files, $this->crud, $stub);

        $content = $this->crud->content()
                ->map(function(Entry $entry) use($compiler) {
                    return $compiler->compile($entry);
                })
                ->filter()
                ->implode("\n");

        if (empty($content)) {
            return '<!-- TODO -->';
        }

        return $content;
    }

}
