<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Themes\Api\Builders\Controller as Base;

/**
 * The Controller class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Controller extends Base {

    /**
     * Get routes file path
     * 
     * @return string The absolute path of the file
     */
    protected function routesPath() {
        return base_path('routes/web.php');
    }

}
