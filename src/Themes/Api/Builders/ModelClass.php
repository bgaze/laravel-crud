<?php

namespace Bgaze\Crud\Themes\Api\Builders;

use Bgaze\Crud\Core\Builder;
use Bgaze\Crud\Core\Entry;

/**
 * Description of Model
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class ModelClass extends Builder {

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
     */
    public function build() {
        $stub = $this->stub('model');

        $this
                ->replace($stub, '#TIMESTAMPS', $this->timestamps())
                ->replace($stub, '#SOFTDELETE', $this->softDeletes())
                ->replace($stub, '#FILLABLES', $this->fillables())
                ->replace($stub, '#DATES', $this->dates())
                ->replace($stub, '#ANNOTATIONS', $this->annotations())
                ->replace($stub, '#METHODS', $this->compileAll('model-methods'))
        ;

        $this->generatePhpFile($this->file(), $stub);
    }

    /**
     * Compile CRUD timestamps.
     * 
     * @return string
     */
    protected function timestamps() {
        return $this->crud->timestamps() ? 'public $timestamps = true;' : '';
    }

    /**
     * Compile CRUD soft deletes.
     * 
     * @return string
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
        $exclude = ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'];

        $fillables = $this->crud->content()
                ->map(function(Entry $entry) use($exclude) {
                    if ($entry->isIndex() || in_array($entry->name(), $exclude)) {
                        return false;
                    }
                    return $entry->columns();
                })
                ->flatten()
                ->filter()
                ->toArray();

        return 'protected $fillable = ' . $this->compileArrayForPhp($fillables) . ';';
    }

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    protected function dates() {
        $dates = $this->crud
                ->content(false)
                ->filter(function(Entry $entry) {
                    return $entry->isDate();
                })
                ->keys();

        if ($this->crud->softDeletes() && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . $this->compileArrayForPhp($dates->toArray()) . ';';
    }

    /**
     * Compile CRUD content to phpDocumentor properties annotations.
     * 
     * @return string
     */
    protected function annotations() {
        $content = $this->compileAll('model-annotations');
        return empty($content) ? "" : "/**\n{$content}\n */";
    }

}
