<?php

namespace Bgaze\Crud\Theme\Builders\Views;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Theme\FormBuilderTrait;

/**
 * Description of CreateView
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Create extends Builder {

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
        $stub = $this->stub('views.create');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generateFile($this->file(), $stub);
    }

}
