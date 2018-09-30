<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\ShowView;
use Bgaze\Crud\Themes\Vue\RegisterComponentTrait;

/**
 * The Show view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ShowComponent extends ShowView {

    use RegisterComponentTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/Show.vue");
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('components.show');

        $this->replace($stub, '#CONTENT', $this->content());

        $path = $this->generateFile($this->file(), $stub);

        $this->registerComponent('Show', $this->crud->getModelFullStudly() . 'Show', '/:id');

        return $path;
    }

}
