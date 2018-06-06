<?php

namespace Bgaze\Crud\Core;

use Bgaze\Crud\Core\Field;
use Bgaze\Crud\Core\Crud;

/**
 * The manager for CRUD content (fields & indexes).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Content {

    /**
     * The CRUD instance.
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $crud;

    /**
     * The content list.
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $fields;

    /**
     * The timestamps to use.
     *
     * @var boolean|string 
     */
    public $timestamps = false;

    /**
     * The soft deletes to use.
     *
     * @var boolean|string 
     */
    public $softDeletes = false;

    /**
     * Class constructor.
     * 
     * @param \Bgaze\Crud\Core\Crud $crud The CRUD theme to use
     */
    public function __construct(Crud $crud) {
        $this->crud = $crud;
        $this->fields = collect();
    }

    /**
     * Check if a content already exists into CRUD.
     * 
     * @param string $id The unique id of the content
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
     * Check if CRUD has no content.
     * 
     * @return boolean
     */
    public function isEmpty() {
        return $this->fields->isEmpty();
    }

    /**
     * Compile CRUD content using a \Bgaze\Crud\Core\Field method.
     * 
     * @param string $ifEmpty The string to insert if CRUD has no content
     * @param type $method The \Bgaze\Crud\Core\Field method to use
     * @param type $arguments Arguments to pass to the method
     * @return string
     */
    protected function compile($ifEmpty, $method, $arguments = []) {
        if ($this->isEmpty()) {
            return $ifEmpty;
        }

        $content = $this->fields->map(function(Field $field) use ($method, $arguments) {
                    return call_user_func_array([$field, $method], $arguments);
                })
                ->filter()
                ->implode("\n");

        return rtrim($content);
    }

    /**
     * Return an array of user original inputs.
     * 
     * @return array
     */
    public function originalInputs() {
        return $this->fields->map(function(Field $field) {
                    return $field->question;
                })->toArray();
    }

    /**
     * Instanciate a new CRUD content.
     * 
     * @param string $field The type of the content
     * @param string $data The user parameters (signed input)
     * 
     * @return \Bgaze\Crud\Core\Field The new content instance
     */
    abstract protected function instantiateField($field, $data);

    /**
     * Add a new content to CRUD.
     * 
     * @param string $field The type of the content
     * @param string $data The user parameters (signed input)
     * @throws \Exception
     * @return void
     */
    public function add($field, $data) {
        // Instanciate field.
        $field = $this->instantiateField($field, $data);

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
     * Compile CRUD content to migration class body.
     * 
     * @return string
     */
    abstract public function toMigration();

    /**
     * Compile CRUD content to Model fillables array.
     * 
     * @return string
     */
    abstract public function toModeleFillables();

    /**
     * Compile CRUD content to Model dates array.
     * 
     * @return string
     */
    abstract public function toModeleDates();

    /**
     * Compile CRUD content to request class body.
     * 
     * @return string
     */
    abstract public function toRequest();

    /**
     * Compile CRUD content to factory class body.
     * 
     * @return string
     */
    abstract public function toFactory();

    /**
     * Compile CRUD content to index view table head.
     * 
     * @return string
     */
    abstract public function toTableHead();

    /**
     * Compile CRUD content to index view table body.
     * 
     * @return string
     */
    abstract public function toTableBody();

    /**
     * Compile CRUD content to form body.
     * 
     * @param boolean $create Is the form a create form rather than an edit form
     * @return string
     */
    abstract public function toForm($create);

    /**
     * Compile CRUD content to request show view body.
     * 
     * @return string
     */
    abstract public function toShow();
}
