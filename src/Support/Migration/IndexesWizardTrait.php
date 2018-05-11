<?php

namespace Bgaze\Crud\Support\Migration;

use Bgaze\Crud\Support\SignedInput;
use Validator;

/**
 * TODO
 *
 * @author bgaze
 */
trait IndexesWizardTrait {

    /**
     * Migration indexes definition
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $indexes_definitions;

    /**
     * Prepare indexes définition.
     * 
     * @return void
     */
    protected function prepareIndexesDefinition() {
        $this->indexes_definitions = collect(config('crud-definitions.migrate.indexes'));
    }

    /**
     * Index wizard
     */
    protected function indexesWizard() {
        // Get indexes definitions.
        $this->prepareIndexesDefinition();

        // Regex to check if requested index exists.
        $reg = '/^(' . $this->indexes_definitions->keys()->implode('|') . ')(\s.*)?$/';

        // Command list for autocomplete.
        $indexes = $this->indexes_definitions->keys()->merge(['list', 'no'])->toArray();

        // Loop and ask for indexes while no explicit break.
        while (true) {
            // User input.
            $question = trim($this->anticipate('Add an index', $indexes, 'no'));

            // Mange wizard exit.
            if ($question === 'no') {
                break;
            }

            // Check if requested index exists.
            if (!preg_match($reg, $question, $m)) {
                $this->error("Invalid input '$question'.");
                continue;
            }

            // Process user input.
            try {
                $this->addIndexToMigration($m[1], isset($m[2]) ? trim($m[2]) : '');
            }
            // Catch any error to prevent unwanted exit.
            catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    /**
     * Process new index user input.
     * 
     * @param string $type
     * @param string $userInput
     * @return void
     * @throws \Exception
     */
    protected function addIndexToMigration($type, $userInput) {
        // Parse user input based on signature.
        $input = SignedInput::input('{columns*}', $userInput);

        // Validate provided column(s).
        $columns = array_keys($this->migration->fields);
        $columns[] = 'id';
        if ($this->migration->timestamps) {
            $columns[] = 'created_at';
            $columns[] = 'updated_at';
        }
        if ($this->migration->timestamps) {
            $columns[] = 'deleted_at';
        }

        foreach ($input->getArgument('columns') as $c) {
            if (!in_array($c, $columns)) {
                throw new \Exception("'$c' doesn't exists in fields list.");
            }
        }

        // Add index to migration.
        $this->migration->indexes[] = (object) [
                    'type' => $type,
                    'columns' => $input->getArgument('columns')
        ];
    }

    /**
     * Compile migration index to PHP sentence.
     * 
     * @param \stdClass $index
     * @return string
     */
    protected function compileMigrationIndex($index) {
        $columns = collect($index->columns)->map(function($c) {
                    return $this->compileValueForPhp($c);
                })->implode(', ');

        return str_replace('%columns', $columns, $this->indexes_definitions->get($index->type));
    }

}
