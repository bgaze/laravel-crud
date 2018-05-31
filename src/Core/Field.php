<?php

namespace Bgaze\Crud\Core;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;
use Validator;
use Bgaze\Crud\Core\Crud;

/**
 * TODO
 *
 * @author bgaze
 */
abstract class Field {

    /**
     * TODO
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $crud;

    /**
     * TODO
     * 
     * @var string 
     */
    public $type;

    /**
     * TODO
     * 
     * @var string 
     */
    public $name;

    /**
     * TODO
     * 
     * @var string 
     */
    public $label;

    /**
     * TODO
     * 
     * @var string 
     */
    public $question;

    /**
     * TODO
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    public $input;

    /**
     * TODO
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $arguments;

    /**
     * TODO
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $options;

    /**
     * TODO
     * 
     * @param \Bgaze\Crud\Core\Crud  $crud
     * @param type $field
     * @param type $question
     */
    public function __construct(Crud $crud, $field, $question) {
        // Link to CRUD instance.
        $this->crud = $crud;

        // Instanciate arguments and options.
        $this->arguments = collect();
        $this->options = collect();

        // Store original user input.
        $this->question = $field . ' ' . $question;

        // Store definition data.
        $this->type = $field;

        // Parse user input.
        $this->parse($this->config('signature'), $question);

        // Validate user input.
        $this->validate();

        // Generate field unique name.
        $this->name = $this->getName();

        // Generate field's label.
        $this->label = $this->getLabel();
    }

    /**
     * TODO
     * 
     * @param type $data
     */
    protected function parse($signature, $question) {
        // Parse signature.
        list($name, $arguments, $options) = Parser::parse($signature);

        // Build InputDefinition.
        $definition = new InputDefinition();
        foreach ($arguments as $argument) {
            $definition->addArgument($argument);
            $this->arguments->push($argument->getName());
        }
        foreach ($options as $option) {
            $definition->addOption($option);
            $this->options->push($option->getName());
        }

        // Create StringInput.
        $this->input = new StringInput($question);
        $this->input->bind($definition);
    }

    /**
     * TODO
     */
    protected function validate() {
        // Check that input matches signature format.
        $this->input->validate();

        // Check that provided values are valid.
        $validator = Validator::make($this->input->getOptions() + $this->input->getArguments(), config('crud-definitions.validation'));
        if ($validator->fails()) {
            throw new \Exception(implode("\n", $validator->errors()->all()));
        }
    }

    /**
     * TODO
     * 
     * @param type $key
     * @return type
     */
    public function config($key, $default = false) {
        return config("crud-definitions.fields.{$this->type}.{$key}", $default);
    }

    /**
     * TODO
     * 
     * @return type
     */
    public function isIndex() {
        return ($this->config('type') === 'index');
    }

    /**
     * TODO
     */
    public function getName() {
        if ($this->isIndex()) {
            return 'index:' . implode('_', $this->input->getArgument('columns'));
        }

        return $this->input->getArgument('column');
    }

    /**
     * TODO
     */
    abstract public function getLabel();

    /**
     * Compile field to migration PHP sentence.
     * 
     * @return string
     */
    abstract public function toMigration();

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
    abstract public function toRequest();

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
