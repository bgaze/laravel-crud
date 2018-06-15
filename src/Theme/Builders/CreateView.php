<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Theme\FormBuilderTrait;

/**
 * Description of CreateView
 *
 * @author bgaze
 */
class CreateView extends Builder {

    use FormBuilderTrait;

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
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('create-view');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generateFile($this->file(), $stub);
    }

}
