<?php

namespace Bgaze\Crud\Theme;

use Bgaze\Crud\Core\Content as Base;
use Bgaze\Crud\Theme\Field;

/**
 * The manager for CRUD content (fields & indexes).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Content extends Base {

    /**
     * Instanciate a new CRUD content.
     * 
     * @param string $field The type of the content
     * @param string $data The user parameters (signed input)
     * 
     * @return \Bgaze\Crud\Core\Field The new content instance
     */
    protected function instantiateField($field, $question) {
        return new Field($this->crud, $field, $question);
    }

    /**
     * Compile CRUD content to migration class body.
     * 
     * @return string
     */
    public function toMigration() {
        $content = $this->fields->map(function(Field $field) {
            return $field->toMigration();
        });

        if ($this->softDeletes) {
            $content->prepend(config("crud-definitions.softDeletes.{$this->softDeletes}"));
        }

        if ($this->timestamps) {
            $content->prepend(config("crud-definitions.timestamps.{$this->timestamps}"));
        }

        return $content->implode("\n");
    }

    /**
     * Compile CRUD content to Model fillables array.
     * 
     * @return string
     */
    public function toModeleFillables() {
        $fillables = $this->fields->filter(function(Field $field) {
                    return !$field->isIndex();
                })->keys();

        return 'protected $fillable = ' . $this->crud->compileValueForPhp($fillables->toArray()) . ';';
    }

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    public function toModeleDates() {
        $dates = $this->fields->filter(function(Field $field) {
                    return ($field->config('type') === 'date');
                })->keys();

        if ($this->softDeletes && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return 'protected $dates = ' . $this->crud->compileValueForPhp($dates->toArray()) . ';';
    }

    /**
     * Compile CRUD content to request class body.
     * 
     * @return string
     */
    public function toRequest() {
        return $this->compile('// TODO', 'toRequest');
    }

    /**
     * Compile CRUD content to factory class body.
     * 
     * @return string
     */
    public function toFactory() {
        return $this->compile('// TODO', 'toFactory');
    }

    /**
     * Compile CRUD content to index view table head.
     * 
     * @return string
     */
    public function toTableHead() {
        return $this->compile('<!-- TODO -->', 'toTableHead');
    }

    /**
     * Compile CRUD content to index view table body.
     * 
     * @return string
     */
    public function toTableBody() {
        return $this->compile('<!-- TODO -->', 'toTableBody');
    }

    /**
     * Compile CRUD content to form body.
     * 
     * @param boolean $create Is the form a create form rather than an edit form
     * @return string
     */
    public function toForm($create) {
        return $this->compile('<!-- TODO -->', 'toForm', [$create]);
    }

    /**
     * Compile CRUD content to request show view body.
     * 
     * @return string
     */
    public function toShow() {
        return $this->compile('<!-- TODO -->', 'toShow');
    }

}
