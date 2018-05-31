<?php

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\Crud;

/**
 * TODO
 *
 * Test test
 * 
 * @author bgaze
 */
abstract class Content {

    /**
     * TODO
     * 
     * Test test
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $crud;

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
        $this->reset();
    }

    public function setCrud(Crud $crud) {
        $this->crud = $crud;
    }

    public function reset() {
        $this->fields = collect([]);
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

        $content = $this->fields->map(function(Field $field) use ($function, $arguments) {
                    return call_user_func_array([$field, $function], $arguments);
                })
                ->filter()
                ->implode("\n");

        return rtrim($content);
    }

    /**
     * TODO
     * 
     * @return array
     */
    public function originalInputs() {
        return $this->fields->map(function(Field $field) {
                    return $field->question;
                })->toArray();
    }

    /**
     * TODO
     * 
     * @param string $field
     * @param string $data
     */
    public function add($field, $data) {
        // Instanciate field.
        $field = new static::$fieldClass($this->crud, $field, $data);

        // Check that it doesn't already exists.
        if ($this->has($field->name)) {
            $type = $field->isIndex() ? 'index' : 'field';
            throw new \Exception("'{$field->name}' {$type} already exists.");
        }

        // If field is an index, check that all selected columns exists.
        if ($field->config('type') === 'index') {
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
     * @return type
     */
    abstract public function toMigration();

    /**
     * TODO
     * 
     * @return type
     */
    abstract public function toModeleFillables();

    /**
     * TODO
     * 
     * @return type
     */
    abstract public function toModeleDates();

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toRequest();

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toFactory();

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toTableHead();

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toTableBody();

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toForm($create);

    /**
     * TODO
     * 
     * @return string
     */
    abstract public function toShow();
}
