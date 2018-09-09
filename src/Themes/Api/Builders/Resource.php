<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * The Seeds class builder
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Resource extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path('Http/Resources/' . $this->crud->model()->implode('/') . 'Resource.php');
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('resource');

        $this->replace($stub, '#CONTENT', $this->content());

        return $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile the file content.
     * 
     * @return string
     */
    protected function content() {
        $columns = $this->crud->columns();

        if ($columns->isEmpty()) {
            return 'parent::toArray($request)';
        }

        $content = $columns
                ->map(function($column) {
                    return "'{$column}' => \$this->{$column},";
                })
                ->implode("\n");

        return "[\n{$content}\n]";
    }

}
