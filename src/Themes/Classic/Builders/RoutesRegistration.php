<?php

namespace Bgaze\Crud\Themes\Classic\Builders;

use Bgaze\Crud\Themes\Api\Builders\RoutesRegistration as Base;

/**
 * Description of Routes
 *
 * @author bgaze
 */
class RoutesRegistration extends Base {

    /**
     * The routes file that the builder updates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return base_path('routes/web.php');
    }

}
