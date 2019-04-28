<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * The Factory builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class FactoryFile extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return database_path('factories/' . $this->crud->getModelFullStudly() . 'Factory.php');
    }

    /**
     * Build the file.
     */
    public function build() {
        // Generate migration content.
        $content = $this->compileAll('factory-content', '// TODO');

        // Write factory file.
        $stub = $this->stub('factory');
        $this->replace($stub, '#CONTENT', $content);
        $this->generatePhpFile($this->file(), $stub);
    }

}
