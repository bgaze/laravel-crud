<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of Controller
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Controller extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path('Http/Controllers/' . $this->crud->model()->implode('/') . 'Controller.php');
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        // Write controller file.
        $path = $this->generatePhpFile($this->file(), $this->stub('controller'));

        // Write routes.
        $this->files->append($this->routesPath(), $this->stub('routes'));

        // Return relative path.
        return $path;
    }

    /**
     * TODO
     */
    protected function routesPath() {
        return base_path('routes/web.php');
    }

}
