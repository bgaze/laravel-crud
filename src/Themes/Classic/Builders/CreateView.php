<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
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
     */
    public function build() {
        $stub = $this->stub('views.create');

        $this->replace($stub, '#CONTENT', $this->compileAll('form-content', '    <!-- TODO -->'));

        $this->generateFile($this->file(), $stub);
    }

}
