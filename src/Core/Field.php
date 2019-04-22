<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;
use Bgaze\Crud\Support\SignedInput;
use Bgaze\Crud\Definitions;

/**
 * A content entry of a CRUD (field or index).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Field extends SignedInput {

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
        // Instanciate field.
        parent::__construct($type . ' ' . Definitions::get($type));

        // Set & validate user input.
        $this->ask($data);
        $this->validate(Definitions::VALIDATION);

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
            $columns = $this->input()->getArgument('columns');
            sort($columns);
            $this->name = 'index:' . implode(',', $columns);
        } elseif (in_array($this->command(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz', 'rememberToken'])) {
            $this->name = $this->command();
        } elseif (in_array($this->command(), ['morphs', 'nullableMorphs', 'morphTo'])) {
            $this->name = $this->command() . ':' . $this->input()->getOption('name');
        } elseif ($this->isRelation()) {
            $this->name = $this->command() . ':' . $this->input()->getOption('related');
        } else {
            $this->name = $this->input()->getArgument('column');
        }
    }

    /**
     * Get content's configuration entry by key.
     * 
     * @param string $key       The key of the entry
     * @param mixed $default    The default value of the entry
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
        return Definitions::isIndex($this->command());
    }

    /**
     * Check if the field is a relation.
     * 
     * @return boolean
     */
    public function isRelation() {
        return Definitions::isRelation($this->command());
    }

    /**
     * Check if the field is an date.
     * 
     * @return boolean
     */
    public function isDate() {
        return Definitions::isDate($this->command());
    }

    /**
     * The unique name of the field.
     * 
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * Get the columns added to the table.
     * 
     * @return array
     */
    public function columns() {
        if ($this->isIndex()) {
            return [];
        }

        if ($this->command() === 'timestamp' || $this->command() === 'timestampTz') {
            return ['created_at', 'updated_at'];
        }

        if ($this->command() === 'softDeletes' || $this->command() === 'softDeletesTz') {
            return ['deleted_at'];
        }

        if ($this->command() === 'morphs' || $this->command() === 'nullableMorphs') {
            return [$this->name . '_id', $this->name . '_type'];
        }

        if ($this->command() === 'rememberToken') {
            return ['remember_token'];
        }

        return [$this->name];
    }

    /**
     * The label to use for the field.
     * 
     * @return string
     */
    public function label() {
        return $this->label;
    }

}
