<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Bgaze\Crud\Support\SignedInput;
use Validator;

class CrudCommand extends Command {

    use \Bgaze\Crud\Support\ConsoleHelpersTrait;
    use \Bgaze\Crud\Support\CrudHelpersTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bgaze:crud:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD';

    /**
     * Migration fields definition
     * 
     * @var \Illuminate\Support\Collection 
     */
    protected $definitions;

    /**
     * Storage for migration names
     * 
     * @var \stdClass 
     */
    protected $names;

    /**
     * Storage for migration data
     *
     * @var type 
     */
    protected $migration;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Initialize definitions.
        $this->definitions = $this->fieldsDefinition();

        // Initialize names.
        $this->names = (object) [
                    'singular' => false,
                    'plurar' => false,
                    'table' => false
        ];

        // Initialize migration.
        $this->migration = (object) [
                    'timestamps' => false,
                    'softDeletes' => false,
                    'fields' => [],
                    'indexes' => []
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->intro();

        // Acquire required data.
        $this->h2("Step 1/3 : Names definition");
        $this->getNamesDefinition();

        $this->h2("Step 2/3 : Model definition");
        $this->getMigrationDefinition();

        // Summarize CRUD.
        $this->h2("Step 3/3 : CRUD generation");
        $this->summarize();

        // Ask for confimrmation and generate CRUD.
        if ($this->confirm("Generate CRUD?", true)) {
            $this->makeMigration();
            $this->makeModel();
            $this->makeRequest();
            $this->makeController();
            $this->makeViews();
            $this->makeRoutes();
            $this->makeFactory();
        }
    }

    ############################################################################
    # DEFINITIONS                                                              #
    ############################################################################

    /**
     * Get the names to use to generate the CRUD
     */
    protected function getNamesDefinition() {
        // Get Model name.
        $this->line("We will first define the model name, which is also the Model class name.");
        $this->line("It should be <fg=blue>camel cased and singular</>, for instance <fg=green>MyNewModel</>.");
        while (!$this->names->singular) {
            $tmp = $this->ask("Model name", "MyNewModel");

            if (!$this->isValidCamelCase($tmp)) {
                continue;
            }

            $this->names->singular = $tmp;
        }

        // Get name's plurar form.
        $this->line("Please confirm plurar form of the Model name.");
        $this->line("It should also be <fg=blue>camel cased</>, for instance <fg=green>MyNewModels</>.");
        while (!$this->names->plurar) {
            $tmp = $this->ask("Plural form", Str::plural($this->names->singular));

            if (!$this->isValidCamelCase($tmp)) {
                continue;
            }

            $this->names->plurar = $tmp;
        }

        // Get table name.
        $this->line("Please confirm the Model table's name.");
        $this->line("It should be <fg=blue>snake cased and plurar</>, for instance <fg=green>my_new_models</>.");
        while (!$this->names->table) {
            $tmp = $this->ask("Table name", Str::snake($this->names->plurar));

            if (!$this->isValidSnakeCase($tmp)) {
                continue;
            }

            $this->names->table = $tmp;
        }
    }

    /**
     * Get migration content
     */
    protected function getMigrationDefinition() {
        $this->line("We are now going to build your model data.");
        $this->line("Please note that an <fg=blue>auto-incremented id</> field will be automatically added.");

        // Timestamps.
        $this->h3('Timestamps');
        $tmp = $this->choice('Do you wish to add timestamps?', ['timestamps', 'timestampsTz', 'nullableTimestamps', 'No'], 0);
        if ($tmp !== 'No') {
            $this->migration->timestamps = $tmp;
        }

        // Soft delete.
        $this->h3('Soft delete');
        $tmp = $this->choice('Do you wish enable soft delete?', ['softDeletes', 'softDeletesTz', 'No'], 0);
        if ($tmp !== 'No') {
            $this->migration->softDeletes = $tmp;
        }

        // Fields wizard.
        $this->h3('Custom fields');
        $this->line("We are now going to define model's fields.");
        $this->nl();
        $this->line("For available column types, enter <fg=blue>list</>.");
        $this->line("For a column detailed syntax, <fg=blue>omit arguments and options.</>");
        $this->fieldsWizard();
    }

    /**
     * Field wizard
     */
    protected function fieldsWizard() {
        // Regex to check if requested field exists.
        $reg = '/^(' . $this->definitions->keys()->implode('|') . ')(\s.*)?$/';

        // Command list for autocomplete.
        $columns = $this->definitions->keys()->merge(['list', 'no'])->toArray();

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
                $this->table(['Column name', 'Arguments', 'Options'], $this->definitions->map(function ($v) {
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
        $column = $this->definitions->get($type);

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

    ############################################################################
    # GENERATORS                                                               #
    ############################################################################

    /**
     * Generate migration file
     */
    protected function makeMigration() {
        $fields = [];

        if ($this->migration->timestamps) {
            $fields[] = config("crud-definitions.migrate.timestamps.{$this->migration->timestamps}.template");
        }

        if ($this->migration->softDeletes) {
            $fields[] = config("crud-definitions.migrate.softDeletes.{$this->migration->softDeletes}.template");
        }

        foreach ($this->migration->fields as $field) {
            $fields[] = $this->compileMigrationFields($field);
        }

        $this->call('bgaze:crud:migration', [
            'name' => "create_{$this->names->table}_table",
            '--fields' => $fields
        ]);
    }

    /**
     * Compile migration field to PHP sentence.
     * 
     * @param \stdClass $field
     * @return string
     */
    protected function compileMigrationFields($field) {
        $column = $this->definitions->get($field->type);

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

    /**
     * Generate model file
     */
    protected function makeModel() {
        
    }

    /**
     * Generate request file
     */
    protected function makeRequest() {
        
    }

    /**
     * Generate controller
     */
    protected function makeController() {
        
    }

    /**
     * Generate views
     */
    protected function makeViews() {
        
    }

    /**
     * Generate routes
     */
    protected function makeRoutes() {
        
    }

    /**
     * Generate Model factory
     */
    protected function makeFactory() {
        
    }

    ############################################################################
    # MISC                                                                     #
    ############################################################################

    /**
     * Show an intro message.
     */
    protected function intro() {
        $this->h1("Welcome to CRUD generator");
        $this->line("This wizard will drive you through the process to create a ready-to-use CRUD related to a new Eloquent Model.");
        /*
          $this->line("After asking you for required data, following features will be generated :");
          $this->nl();
          $this->ul([
          'Migration class',
          'Model class',
          'Request class with field type based validation',
          'Controller class with CRUD actions',
          'CRUD Views',
          'Routes',
          'Model Factory'
          ]);
         */
        $this->nl();
    }

    /**
     * Show a summary before generation.
     */
    protected function summarize() {
        
    }

}
