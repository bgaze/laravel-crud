<?php

namespace Bgaze\Crud\Support\Migration;

use Bgaze\Crud\Support\SignedInput;
use Validator;

/**
 * TODO
 *
 * @author bgaze
 */
trait FieldsWizardTrait {

    /**
     * Migration fields definition
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $fields_definitions;

    /**
     * Prepare fields définition.
     * 
     * @return void
     */
    protected function prepareFieldsDefinition() {
        $this->fields_definitions = collect(config('crud-definitions.migrate.fields'))->map(function($definition, $name) {
            $tmp = (object) $definition;

            $tmp->validation = isset($tmp->validation) ? array_merge(config('crud-definitions.migrate.validation'), $tmp->validation) : config('crud-definitions.migrate.validation');

            $help = SignedInput::help($tmp->signature);
            $tmp->help = $name . ' ' . $help;

            list($options, $arguments) = explode(' [--] ', $help);
            $tmp->help_row = [
                'name' => $name,
                'arguments' => $arguments,
                'options' => trim(str_replace('] [', ' ', $options), '[]')
            ];

            return $tmp;
        });
    }

    /**
     * Field wizard
     */
    protected function fieldsWizard() {
        // Get fields definitions.
        $this->prepareFieldsDefinition();

        // Regex to check if requested field exists.
        $reg = '/^(' . $this->fields_definitions->keys()->implode('|') . ')(\s.*)?$/';

        // Command list for autocomplete.
        $columns = $this->fields_definitions->keys()->merge(['list', 'no'])->toArray();

        // Loop and ask for fields while no explicit break.
        while (true) {
            // User input.
            $question = trim($this->anticipate('Add a column', $columns, 'no'));

            // Mange wizard exit.
            if ($question === 'no') {
                if (empty($this->migration->fields) && !$this->confirm("You haven't added any field. Continue?")) {
                    continue;
                }
                break;
            }

            // Manage 'list' command.
            if ($question === 'list') {
                $this->table(['Column name', 'Arguments', 'Options'], $this->fields_definitions->map(function ($v) {
                            return $v->help_row;
                        }));
                continue;
            }

            // Check if requested field exists.
            if (!preg_match($reg, $question, $m)) {
                $this->error("Invalid input '$question'.");
                continue;
            }

            // Process user input.
            try {
                $this->field($m[1], isset($m[2]) ? trim($m[2]) : '');
            }
            // Catch any error to prevent unwanted exit.
            catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Process new field user input.
     * 
     * @param string $type
     * @param string $userInput
     * @return void
     * @throws \Exception
     */
    protected function field($type, $userInput) {
        // Retrieve field definition.
        $column = $this->fields_definitions->get($type);

        // Empty arguments, show field help.
        if (empty($userInput)) {
            $this->line("   {$column->description}");
            $this->line("   Signature :   <fg=cyan>$type {$column->signature}</>");
            $this->line("   Usage :       <fg=cyan>{$column->help}</>");
            return;
        }

        // Parse user input based on signature.
        $input = SignedInput::input($column->signature, $userInput);

        // Check if column name already exists.
        $name = $input->getArgument('column');
        if (isset($this->migration->fields[$name])) {
            throw new \Exception("'$name' field already exists in this migration.");
        }

        // Validation user input.
        if (!empty($column->validation)) {
            $validator = Validator::make($input->getOptions() + $input->getArguments(), $column->validation);

            if ($validator->fails()) {
                throw new \Exception(implode("\n", $validator->errors()->all()));
            }
        }

        // Add field to migration.
        $this->migration->fields[$name] = (object) [
                    'type' => $type,
                    'arguments' => $input->getArguments(),
                    'options' => $input->getOptions()
        ];
    }

    /**
     * Compile migration field to PHP sentence.
     * 
     * @param \stdClass $field
     * @return string
     */
    protected function compileMigrationField($field) {
        $column = $this->fields_definitions->get($field->type);

        $template = $column->template;

        foreach ($field->arguments as $k => $v) {
            $template = str_replace("%$k", $this->compileValueForPhp($v), $template);
        }

        foreach ($field->options as $k => $v) {
            if ($v) {
                $template .= str_replace('%value', $this->compileValueForPhp($v), config("crud-definitions.migrate.modifiers.$k"));
            }
        }

        return $template . ';';
    }

}
