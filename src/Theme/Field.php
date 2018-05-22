<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;
use Validator;

/**
 * TODO
 *
 * @author bgaze
 */
class Field {

    /**
     * TODO
     * 
     * @var string 
     */
    public $columnType;

    /**
     * TODO
     * 
     * @var string 
     */
    public $template;

    /**
     * TODO
     *
     * @var string 
     */
    public $dataType;

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
    public $originalInput;

    /**
     * TODO
     *
     * @var \Symfony\Component\Console\Input\StringInput
     */
    public $input;

    /**
     * TODO
     * 
     * @param string $field
     * @param string $data
     */
    public function __construct($field, $data) {
        // Store original user input.
        $this->originalInput = $field . ' ' . $data;

        // Get field definition.
        $definition = config("crud-definitions.fields.$field");

        // Store definition data.
        $this->template = $definition['template'];
        $this->dataType = $definition['type'];
        $this->columnType = $field;

        // Field definition.
        $this->definition = $definition;

        // Parse user input.
        $this->parse($definition['signature'], $data);

        // Validate user input.
        $this->validate();

        // Generate field unique name.
        if ($this->dataType === 'index') {
            $this->name = 'index:' . implode(',', $this->input->getArgument('columns'));
        } else {
            $this->name = $this->input->getArgument('column');
        }
    }

    /**
     * TODO
     * 
     * @param type $data
     */
    protected function parse($signature, $data) {
        // Parse signature.
        list($name, $arguments, $options) = Parser::parse($signature);

        // Build InputDefinition.
        $definition = new InputDefinition();
        foreach ($arguments as $argument) {
            $definition->addArgument($argument);
        }
        foreach ($options as $option) {
            $definition->addOption($option);
        }

        // Create StringInput.
        $this->input = new StringInput($data);
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
     * Compile field to migration PHP sentence.
     * 
     * @return string
     */
    public function toMigration() {
        $tmp = $this->template;

        foreach ($this->input->getArguments() as $k => $v) {
            $tmp = str_replace("%$k", $this->compileValueForPhp($v), $tmp);
        }

        foreach ($this->input->getOptions() as $k => $v) {
            if ($v) {
                $tmp .= str_replace('%value', $this->compileValueForPhp($v), config("crud-definitions.modifiers.{$k}"));
            }
        }

        return $tmp . ';';
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toFactory() {
        return '// ' . $this->originalInput;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toRequest() {
        return '// ' . $this->originalInput;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableHead() {
        return "<!-- {$this->originalInput} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableBody() {
        return "<!-- {$this->originalInput} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toForm(bool $create) {
        return "<!-- {$this->originalInput} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toShow() {
        return "<!-- {$this->originalInput} -->";
    }

}
