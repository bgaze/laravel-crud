<?php

namespace Bgaze\Crud\Themes\Api\Compilers;

use Bgaze\Crud\Core\Compiler;
use Bgaze\Crud\Core\Entry;

/**
 * Description of ModelProperty
 *
 * @author bgaze
 */
class ModelMethods extends Compiler {

    /**
     * Get the default compilation function for an entry.
     * 
     * @param Bgaze\Crud\Core\Entry $entry The entry 
     * @return string The compiled entry
     */
    public function compileDefault(Entry $entry) {
        return null;
    }

}
