<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\Views\Index;

/**
 * The Index view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class IndexComponent extends Index {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/index.blade.php");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('components.index');

        $this
                ->replace($stub, '#THEAD', $this->tableHead())
                ->replace($stub, '#TBODY', $this->tableBody())
        ;

        return $this->generateFile($this->file(), $stub);
    }

}
