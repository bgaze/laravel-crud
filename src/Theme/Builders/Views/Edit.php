<?php

namespace Bgaze\Crud\Theme\Builders\Views;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Theme\FormBuilderTrait;

/**
 * Description of EditView
 *
 * @author bgaze
 */
class Edit extends Builder {

    use FormBuilderTrait;

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
        $stub = $this->stub('views.edit');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generateFile($this->file(), $stub);
    }

}
