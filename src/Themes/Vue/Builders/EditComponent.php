<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

/**
 * The Edit view builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class EditComponent extends CreateComponent {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/Edit.vue");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $path = $this->buildForm('components.edit', 'partials.form-group');

        $this->crud->registerComponent('Edit', '/edit/:id');

        return $path;
    }

}
