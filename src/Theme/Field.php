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
     * @param string $field
     * @param string $question
     */
    public function __construct($field, $question) {
        // Store original user input.
        $this->question = $field . ' ' . $question;

        // Store definition data.
        $this->type = $field;

        // Parse user input.
        $this->parse($this->config('signature'), $question);

        // Validate user input.
        $this->validate();

        // Generate field unique name.
        if ($this->isIndex()) {
            $this->name = 'index:' . implode('_', $this->input->getArgument('columns'));
        } else {
            $this->name = $this->input->getArgument('column');
        }
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
        }
        foreach ($options as $option) {
            $definition->addOption($option);
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
     * Compile field to migration PHP sentence.
     * 
     * @return string
     */
    public function toMigration() {
        $tmp = $this->config('template');

        foreach ($this->input->getArguments() as $k => $v) {
            $tmp = str_replace("%$k", compile_value_for_php($v), $tmp);
        }

        foreach ($this->input->getOptions() as $k => $v) {
            if ($v) {
                $tmp .= str_replace('%value', compile_value_for_php($v), config("crud-definitions.modifiers.{$k}"));
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
        switch ($this->config('type')) {
            case 'integer':
                $default = 'mt_rand(0, 1000)';
                break;
            case 'float':
                $default = "(mt_rand() / mt_getrandmax()) * " . str_repeat('9', $this->input->getArgument('total'));
                break;
            case 'date':
                $default = "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())";
                break;
            case 'string':
                $default = "\$faker->sentence()";
                break;
            case 'array':
                $default = 'array_random(' . compile_value_for_php($this->input->getArgument('allowed')) . ')';
                break;
            default:
                return null;
        }

        return "'{$this->name}' => " . $this->config('factory', $default) . ",";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toRequest() {
        return '// ' . $this->question;
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableHead() {
        return "<!-- {$this->question} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableBody() {
        return "<!-- {$this->question} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toForm(bool $create) {
        return "<!-- {$this->question} -->";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toShow() {
        return "<!-- {$this->question} -->";
    }

}
