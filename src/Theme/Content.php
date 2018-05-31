<?php

namespace Bgaze\Crud\Theme;

use Bgaze\Crud\Core\Content as Base;
use Bgaze\Crud\Theme\Field;

/**
 * TODO
 *
 * Test test
 * 
 * @author bgaze
 */
class Content extends Base {

    /**
     * 
     * @param type $field
     * @param type $question
     */
    protected function instantiateField($field, $question) {
        return new Field($this->crud, $field, $question);
    }

    /**
     * TODO
     * 
     * @return type
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
     * TODO
     * 
     * @return type
     */
    public function toModeleFillables() {
        $fillables = $this->fields->filter(function(Field $field) {
                    return !$field->isIndex();
                })->keys();

        return 'protected $fillable = ' . $this->crud->compileValueForPhp($fillables->toArray()) . ';';
    }

    /**
     * TODO
     * 
     * @return type
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
     * TODO
     * 
     * @return string
     */
    public function toRequest() {
        return $this->compile('// TODO', 'toRequest');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toFactory() {
        return $this->compile('// TODO', 'toFactory');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableHead() {
        return $this->compile('<!-- TODO -->', 'toTableHead');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableBody() {
        return $this->compile('<!-- TODO -->', 'toTableBody');
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toForm($create) {
        return $this->compile('<!-- TODO -->', 'toForm', [$create]);
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toShow() {
        return $this->compile('<!-- TODO -->', 'toShow');
    }

}
