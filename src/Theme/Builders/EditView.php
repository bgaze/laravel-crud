<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Theme\Builders\CreateView;

/**
 * Description of EditView
 *
 * @author bgaze
 */
class EditView extends CreateView {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('views/' . $this->crud->getPluralsKebabSlash() . "/edit.blade.php");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('edit-view');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generatePhpFile($this->file(), $stub);
    }

}
