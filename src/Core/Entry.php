<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Str;
use Bgaze\Crud\Definitions;
use Bgaze\Crud\Support\SignedInput;
use Bgaze\Crud\Core\Crud;

/**
 * A content entry of a CRUD (entry or index).
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Entry extends SignedInput {

    /**
     * The unique name of the entry.
     * 
     * @var string 
     */
    protected $name;

    /**
     * The label to use for the entry.
     * 
     * @var string 
     */
    protected $label;

    /**
     * The related model for relations.
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $related = null;

    /**
     * The class constructor.
     * 
     * @param string $type      The entry type. 
     * @param string $data      Options and arguments
     */
    public function __construct($type, $data, $crudClass) {
        // Instanciate entry.
        parent::__construct($type . ' ' . Definitions::get($type));

        // Set & validate user input.
        $this->ask($data);
        $this->validate(Definitions::VALIDATION);

        // Init related model for relations.
        if ($this->isRelation()) {
            $this->related = new $crudClass($this->argument('related'));
            $this->related->setPlurals($this->option('plurals', false));
        }

        // Set entry name.
        $this->setName();

        // Set entry label.
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
            $columns = $this->argument('columns');
            sort($columns);
            $this->name = 'index:' . implode(',', $columns);
        } elseif (in_array($this->command(), ['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz', 'rememberToken'])) {
            $this->name = $this->command();
        } elseif (in_array($this->command(), ['morphs', 'nullableMorphs', 'morphTo'])) {
            $this->name = $this->command() . ':' . $this->option('name');
        } elseif ($this->isRelation()) {
            $this->name = $this->command() . ':' . $this->argument('related');
        } else {
            $this->name = $this->argument('column');
        }
    }

    /**
     * Check if the entry is an index.
     * 
     * @return boolean
     */
    public function isIndex() {
        return Definitions::isIndex($this->command());
    }

    /**
     * Check if the entry is a relation.
     * 
     * @return boolean
     */
    public function isRelation() {
        return Definitions::isRelation($this->command());
    }

    /**
     * Check if the entry is an date.
     * 
     * @return boolean
     */
    public function isDate() {
        return Definitions::isDate($this->command());
    }

    /**
     * The unique name of the entry.
     * 
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * The label to use for the entry.
     * 
     * @return string
     */
    public function label() {
        return $this->label;
    }

    /**
     * The related model to use for the entry.
     * 
     * @return \Bgaze\Crud\Core\Model
     */
    public function related() {
        return $this->related;
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
     * Get entry arguments.
     * 
     * @return array
     */
    public function arguments() {
        return $this->input()->getArguments();
    }

    /**
     * Get entry argument by key.
     * 
     * @param string $key       The key of the entry
     * @param mixed $default    The default value of the entry
     * @return mixed
     */
    public function argument($key, $default = null) {
        if ($this->definition()->hasArgument($key) && $this->input()->hasArgument($key)) {
            $value = $this->input()->getArgument($key);
        }

        if ($value === null) {
            return $default;
        }

        return $value;
    }

    /**
     * Get entry options.
     * 
     * @return array
     */
    public function options() {
        return $this->input()->getOptions();
    }

    /**
     * Get entry option by key.
     * 
     * @param string $key       The key of the entry
     * @param mixed $default    The default value of the entry
     * @return mixed
     */
    public function option($key, $default = null) {
        if ($this->definition()->hasOption($key) && $this->input()->hasOption($key)) {
            $value = $this->input()->getOption($key);
        }

        if ($value === null) {
            return $default;
        }

        return $value;
    }

}
