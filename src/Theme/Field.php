<?php

namespace Bgaze\Crud\Theme;

use Illuminate\Console\Parser;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;
use Validator;
use Bgaze\Crud\Theme\Crud;
use Illuminate\Support\Str;

/**
 * TODO
 *
 * @author bgaze
 */
class Field {

    /**
     * TODO
     * 
     * @var \Bgaze\Crud\Theme\Crud 
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
     * @param \Bgaze\Crud\Theme\Crud  $crud
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
    public function getLabel() {
        $label = Str::studly($this->name);

        if (!preg_match_all('/[A-Z][a-z]+|\d+/', $label, $matches) || !isset($matches[0])) {
            return $label;
        }

        return implode(' ', $matches[0]);
    }

    /**
     * TODO
     * 
     * @return string
     */
    protected function getDefaultFactoryTemplate() {
        switch ($this->config('type')) {
            case 'boolean':
                return '(mt_rand(0, 1) === 1)';
            case 'integer':
                return 'mt_rand(0, 1000)';
            case 'float':
                return "(mt_rand() / mt_getrandmax()) * " . str_repeat('9', $this->input->getArgument('total'));
            case 'date':
                return "Carbon::createFromTimeStamp(\$faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp())";
            case 'array':
                return 'array_random(' . compile_value_for_php($this->input->getArgument('allowed')) . ')';
            default:
                return "\$faker->sentence()";
        }
    }

    /**
     * TODO
     * 
     * @return string
     */
    protected function getDefaultFormTemplate() {
        switch ($this->config('type')) {
            case 'boolean':
                return "Form::checkbox('FieldName', '1')";
            case 'array':
                return "Form::select('FieldName', " . compile_value_for_php($this->input->getArgument('allowed')) . ")";
            default:
                return "Form::text('FieldName')";
        }
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
        if ($this->isIndex()) {
            return null;
        }

        $template = $this->config('factory');

        if (!$template) {
            $template = $this->getDefaultFactoryTemplate();
        }

        return "'{$this->name}' => {$template},";
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toRequest() {
        if ($this->isIndex()) {
            return null;
        }

        $rules = [];

        if ($this->options->contains('nullable')) {
            $rules[] = $this->input->getOption('nullable') ? 'nullable' : 'required';
        } elseif (preg_match('/^nullable/', $this->type)) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        switch ($this->config('type')) {
            case 'boolean':
                $rules[] = 'boolean';
                break;
            case 'integer':
                $rules[] = 'integer';
                break;
            case 'float':
                $rules[] = 'numeric';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'array':
                $rules[] = 'in:' . implode(',', $this->input->getArgument('allowed'));
                break;
            default:
                break;
        }

        if ($this->options->contains('unique') && $this->input->getOption('unique')) {
            $rules[] = 'unique:' . $this->crud->getTableName() . ',' . $this->name;
        }

        return sprintf("'%s' => '%s',", $this->name, implode('|', $rules));
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableHead() {
        if ($this->isIndex()) {
            return null;
        }

        return $this->crud->populateStub('views.partial.table-head', function(Crud $crud, $stub) {
                    $crud->replace($stub, 'FieldLabel', $this->label);
                });
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toTableBody() {
        if ($this->isIndex()) {
            return null;
        }

        return $this->crud->populateStub('views.partial.table-body', function(Crud $crud, $stub) {
                    $crud
                            ->replace($stub, 'FieldLabel', $this->label)
                            ->replace($stub, 'FieldName', $this->name)
                    ;
                });
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toForm(bool $create) {
        if ($this->isIndex()) {
            return null;
        }

        return $this->crud->populateStub('views.partial.form-group', function(Crud $crud, $stub) {
                    $crud
                            ->replace($stub, 'FieldLabel', $this->label)
                            ->replace($stub, '#FIELD', $this->getDefaultFormTemplate())
                            ->replace($stub, 'FieldName', $this->name)
                    ;
                });
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function toShow() {
        if ($this->isIndex()) {
            return null;
        }

        return $this->crud->populateStub('views.partial.show-group', function(Crud $crud, $stub) {
                    $crud
                            ->replace($stub, 'ModelCamel')
                            ->replace($stub, 'FieldLabel', $this->label)
                            ->replace($stub, 'FieldName', $this->name)
                    ;
                });
    }

}
