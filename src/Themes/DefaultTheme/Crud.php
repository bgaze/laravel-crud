<?php

namespace Bgaze\Crud\Themes\DefaultTheme;

use Bgaze\Crud\Support\Theme\Crud as Base;

/**
 * Description of Theme
 *
 * @author bgaze
 */
class Theme extends Base {

    /**
     * TODO
     * 
     * @return string
     */
    public function getModelClass() {
        return $this->namespace . '\\' . $this->model;
    }

}
