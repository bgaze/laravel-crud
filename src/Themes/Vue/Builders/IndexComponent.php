<?php

namespace Bgaze\Crud\Themes\Vue\Builders;

use Bgaze\Crud\Themes\Classic\Builders\Views\Index;

/**
 * The Index view builder.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class IndexComponent extends Index {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return resource_path('assets/js/components/' . $this->crud->getPluralsKebabSlash() . "/index.blade.php");
    }

}
