<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\IndexView;
use Bgaze\Crud\Themes\Vue\RegisterComponentTrait;

/**
 * The Index view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class IndexComponent extends IndexView {

    use RegisterComponentTrait;

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/Index.vue");
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

        $path = $this->generateFile($this->file(), $stub);

        $this->registerComponent('Index', $this->crud->getPluralFullStudly() . 'Index');

        return $path;
    }

}
