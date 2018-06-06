<?php

namespace Bgaze\Crud\Core;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;
use Validator;
use Bgaze\Crud\Core\Crud;

/**
 * A CRUD content (field or index) 
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
abstract class Field {

    /**
     * The CRUD instance.
     * 
     * @var \Bgaze\Crud\Core\Crud 
     */
    protected $crud;

    /**
     * The content type
     * 
     * @var string 
     */
    public $type;

    /**
     * The unique name of the content.
     * 
     * @var string 
     */
    public $name;

    /**
     * The label to use for this content.
     * 
     * @var string 
     */
    public $label;

    /**
     * The original user input.
     * 
     * @var string 
     */
    public $question;

    /**
     * The content StringInput instance.
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    public $input;

    /**
     * The content arguments provided by user.
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $arguments;

    /**
     * The content options provided by user.
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $options;

    /**
     * Class constructor
     * 
     * @param \Bgaze\Crud\Core\Crud  $crud The CRUD instance
     * @param type $field The field type
     * @param type $question The user signed input
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
     * Parse user input based on a signature.
     * 
     * @param type $signature The signature to use
     * @param type $question User input
     * @return void
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
     * Validate user input.
     * 
     * @throws \Exception
     * @return void
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
     * Get content's configuration entry by key.
     * 
     * @param string $key The key of the entry
     * @param mixed $default The default value of the entry
     * @return mixed
     */
    public function config($key, $default = false) {
        return config("crud-definitions.fields.{$this->type}.{$key}", $default);
    }

    /**
     * Check if the content is an index.
     * 
     * @return boolean
     */
    public function isIndex() {
        return ($this->config('type') === 'index');
    }

    /**
     * Generate the unique name of the content.
     * 
     * @return string
     */
    public function getName() {
        if ($this->isIndex()) {
            return 'index:' . implode('_', $this->input->getArgument('columns'));
        }

        return $this->input->getArgument('column');
    }

    /**
     * Generate the label of the content.
     * 
     * @return string
     */
    abstract public function getLabel();

    /**
     * Compile content to migration class body line.
     * 
     * @return string
     */
    abstract public function toMigration();

    /**
     * Compile content to factory class body line.
     * 
     * @return string
     */
    abstract public function toFactory();

    /**
     * Compile content to request class body line.
     * 
     * @return string
     */
    abstract public function toRequest();

    /**
     * Compile content to index view table head cell.
     * 
     * @return string
     */
    abstract public function toTableHead();

    /**
     * Compile content to index view table body cell.
     * 
     * @return string
     */
    abstract public function toTableBody();

    /**
     * Compile content to form group.
     * 
     * @param boolean $create Is the form a create form rather than an edit form
     * @return string
     */
    abstract public function toForm($create);

    /**
     * Compile content to request show view group.
     * 
     * @return string
     */
    abstract public function toShow();
}
