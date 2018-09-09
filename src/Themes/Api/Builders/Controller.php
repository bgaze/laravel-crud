<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * The Controller class builder
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
     * Get routes file path
     * 
     * @return string The absolute path of the file
     */
    protected function routesPath() {
        return base_path('routes/api.php');
    }

}
