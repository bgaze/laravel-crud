<?php

namespace Bgaze\Crud\Theme\Builders;

use Bgaze\Crud\Core\Builder;

/**
 * Description of Model
 *
 * @author bgaze
 */
class Model extends Builder {

    /**
     * The file that the builder generates.
     * 
     * @return string The absolute path of the file
     */
    public function file() {
        return app_path(trim($this->crud->modelsSubDirectory() . '/' . $this->crud->model()->implode('/') . '.php', '/'));
    }

    /**
     * Build the file.
     * 
     * @return string The relative path of the generated file
     */
    public function build() {
        $stub = $this->stub('migration');

        $this
                ->replace($stub, '#TIMESTAMPS', $this->timestamps())
                ->replace($stub, '#SOFTDELETE', $this->softDeletes())
                ->replace($stub, '#FILLABLES', $this->fillables())
                ->replace($stub, '#DATES', $this->dates())
        ;

        return $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * TODO
     */
    protected function timestamps() {
        return $this->crud->timestamps() ? 'public $timestamps = true;' : '';
    }

    /**
     * TODO
     */
    protected function softDeletes() {
        return $this->crud->softDeletes() ? 'use \Illuminate\Database\Eloquent\SoftDeletes;' : '';
    }

    /**
     * Compile CRUD content to Model fillables array.
     * 
     * @return string
     */
    protected function fillables() {
        $fillables = $this->crud
                ->content(false)
                ->keys()
                ->toArray();
        return 'protected $fillable = ' . $this->compileValueForPhp($fillables) . ';';
    }

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    protected function dates() {
        $dates = $this->crud
                ->content(false)
                ->filter(function(Field $field) {
                    return ($field->config('type') === 'date');
                })
                ->keys();

        if ($this->crud->softDeletes() && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . $this->compileValueForPhp($dates->toArray()) . ';';
    }

}
