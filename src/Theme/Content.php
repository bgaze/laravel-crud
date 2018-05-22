<?php

namespace Bgaze\Crud\Theme;

use Bgaze\Crud\Theme\Field;

/**
 * TODO
 *
 * @author bgaze
 */
class Content {

    /**
     * TODO
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $fields;

    /**
     * TODO
     *
     * @var array 
     */
    public $timestamps = false;

    /**
     * TODO
     *
     * @var array 
     */
    public $softDeletes = false;

    /**
     * TODO
     * 
     */
    public function __construct() {
        $this->fields = collect([]);
    }

    /**
     * TODO
     * 
     * @param string $field
     * @param string $data
     */
    public function add($field, $data) {
        // Instanciate field.
        $field = new Field($field, $data);

        // Check that it doesn't already exists.
        if ($this->has($field->name)) {
            $type = ($field->dataType === 'index' ) ? 'index' : field;
            throw new \Exception("'{$field->name}' {$type} already exists.");
        }

        // If field is an index, check that all selected columns exists.
        if ($field->dataType === 'index') {
            foreach ($field->input->getArgument('columns') as $column) {
                if (!$this->has($column)) {
                    throw new \Exception("'$column' doesn't exists in fields list.");
                }
            }
        }

        // Add to fields list.
        $this->fields->put($field->name, $field);
    }

    /**
     * TODO
     * 
     * @param type $id
     * @return boolean
     */
    public function has($id) {
        if (($id === 'created_at' || $id === 'updated_at') && $this->timestamps) {
            return true;
        }

        if ($id === 'deleted_at' && $this->softDeletes) {
            return true;
        }

        return $this->fields->has($id);
    }

    /**
     * TODO
     */
    public function isEmpty() {
        return $this->fields->isEmpty();
    }

    /**
     * TODO
     * 
     * @return string
     */
    protected function compile($ifEmpty, $function, $arguments = []) {
        if ($this->isEmpty()) {
            return $ifEmpty;
        }

        return $this->fields->filter(function(Field $field) {
                    return ($field->dataType !== 'index');
                })->map(function(Field $field) use ($function, $arguments) {
                    return call_user_func_array([$field, $function], $arguments);
                })->implode("\n");
    }

    /**
     * TODO
     * 
     * @return array
     */
    public function originalInputs() {
        return $this->fields->map(function(Field $field) {
                    return $field->originalInput;
                })->toArray();
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
                    return ($field->dataType !== 'index');
                })->keys();

        return 'protected $fillable = [' . compile_value_for_php($fillables->toArray()) . '];';
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function toModeleDates() {
        $dates = $this->fields->filter(function(Field $field) {
                    return ($field->dataType === 'date');
                })->keys();

        if ($this->softDeletes && !$dates->contains('deleted_at')) {
            $dates->prepend('deleted_at');
        }

        return '$dates $fillable = [' . compile_value_for_php($dates->toArray()) . '];';
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
    public function toForm(bool $create) {
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
