<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;
use Bgaze\Crud\Support\SignedInput;

/**
 * A content entry of a CRUD (field or index).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Field extends SignedInput {

    /**
     * The configuration of the field.
     * 
     * @var \stdClass 
     */
    public $config;

    /**
     * The unique name of the field.
     * 
     * @var string 
     */
    public $name;

    /**
     * The label to use for the field.
     * 
     * @var string 
     */
    public $label;

    /**
     * The class constructor.
     * 
     * @param string $type      The field type. 
     * @param string $data      Options and arguments
     */
    public function __construct($type, $data) {
        // Get fields configuration.
        $this->config = (object) config("crud-definitions.fields.{$type}");

        // Instanciate field.
        parent::__construct($type . ' ' . $this->config('signature'));

        // Set & validate user input.
        $this->ask($data);
        $this->validate(config('crud-definitions.validation'));

        // Set field name.
        $this->setName();

        // Set field label.
        $this->setLabel();
    }

    /**
     * Generate the label of the content.
     * 
     * @return string
     */
    protected function setLabel() {
        $this->label = ucfirst(str_replace('_', ' ', Str::snake($this->name)));
    }

    /**
     * Generate the unique name of the content.
     * 
     * @return string
     */
    protected function setName() {
        if ($this->isIndex()) {
            $this->name = 'index:' . implode('_', $this->input()->getArgument('columns'));
        } else {
            $this->name = $this->input()->getArgument('column');
        }
    }

    /**
     * Get content's configuration entry by key.
     * 
     * @param string $key The key of the entry
     * @param mixed $default The default value of the entry
     * @return mixed
     */
    public function config($key, $default = false) {
        if (!isset($this->config->{$key})) {
            return $default;
        }

        return $this->config->{$key};
    }

    /**
     * Check if the field is an index.
     * 
     * @return boolean
     */
    public function isIndex() {
        return ($this->config('type') === 'index');
    }

    /**
     * The unique name of the field.
     * 
     * @return string
     */
    function name() {
        return $this->name;
    }

    /**
     * The label to use for the field.
     * 
     * @return string
     */
    function label() {
        return $this->label;
    }

}
