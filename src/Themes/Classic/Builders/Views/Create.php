<?php

namespace Bgaze\Crud\Themes\Classic\Builders\Views;

use Bgaze\Crud\Themes\Classic\FormBuilder;

/**
 * The Create view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Create extends FormBuilder {

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
        return $this->buildForm('views.create', 'partials.form-group');
    }

}
