<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Themes\Classic\Builders\IndexView;

/**
 * The Show view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ShowView extends IndexView {

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
     */
    public function build() {
        $stub = $this->stub('views.show');
        $this->replace($stub, '#CONTENT', $this->compileContent('partials.show-group'));
        $this->generateFile($this->file(), $stub);
    }

}
