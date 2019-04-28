<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * The Request class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class RequestClass extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path('Http/Requests/' . $this->crud->model()->implode('/') . 'FormRequest.php');
    }

    /**
     * Build the file.
     */
    public function build() {
        // Generate migration content.
        $content = $this->compileAll('request-rules', '// TODO');

        // Write migration file.
        $stub = $this->stub('request');
        $this->replace($stub, '#CONTENT', $content);
        $this->generatePhpFile($this->file(), $stub);
    }

}
