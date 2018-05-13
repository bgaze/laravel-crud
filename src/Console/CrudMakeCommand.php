<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class CrudMakeCommand extends Command {

    use \Bgaze\Crud\Support\ConsoleHelpersTrait;
    use \Bgaze\Crud\Support\CrudHelpersTrait;
    use \Bgaze\Crud\Support\Migration\FieldsWizardTrait;
    use \Bgaze\Crud\Support\Migration\IndexesWizardTrait;

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
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

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
    public function __construct(Filesystem $files) {
        parent::__construct();

        // Initialize filesystem.
        $this->files = $files;

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
        // Intro.
        $this->h1("Welcome to CRUD generator");
        $this->line("This wizard will drive you through the process to create a ready-to-use CRUD related to a new Eloquent Model.");
        $this->nl();

        // Acquire names definition.
        $this->h2("Step 1/3 : Names definition");
        $this->getNamesDefinition();

        // Check that no CRUD file already exists.
        $this->checkForExistingFiles();

        // Acquire fields definition.
        $this->h2("Step 2/3 : Model definition");
        $this->getMigrationDefinition();

        // Summarize CRUD.
        $this->h2("Step 3/3 : CRUD generation");
        $this->line("Following features will be generated based on your inputs.");
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

        // Ask for confirmation then generate CRUD.
        if ($this->confirm("Continue?", true)) {
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
     * TODO
     */
    protected function checkForExistingFiles() {
        $errors = collect([]);

        // Migration.
        $tmp = $this->files->glob(base_path("database/migrations/*_create_{$this->names->table}_table.php"));
        if (count($tmp)) {
            $errors = $errors->concat($tmp);
        }

        // Model.
        $tmp = app_path($this->names->singular . '.php');
        if ($this->files->exists($tmp)) {
            $errors->push($tmp);
        }

        // Request.
        $tmp = app_path('Http/Requests/' . $this->names->singular . 'FormRequest.php');
        if ($this->files->exists($tmp)) {
            $errors->push($tmp);
        }

        // If some files already exists, throw exception.
        if ($errors->isNotEmpty()) {
            $tmp = "Following file(s) already exists :\n";
            $tmp .= $errors->map(function($p) {
                        return ' - ' . $this->stripBasePath($p);
                    })->implode("\n");
            throw new \Exception($tmp);
        }
    }

    /**
     * Get migration content
     */
    protected function getMigrationDefinition() {
        // Prepare required definitions.
        $this->prepareIndexesDefinition();
        $this->prepareFieldsDefinition();

        // Intro text.
        $this->line("We are now going to build your model data.");
        $this->line("Please note that an <fg=cyan>auto-incremented id</> field will be automatically added.");

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
        $this->line("For available column types, enter <fg=cyan>list</>.");
        $this->line("For a column detailed syntax, <fg=cyan>omit arguments and options.</>");
        $this->fieldsWizard();

        // Indexes wizard.
        $this->h3('Indexes');
        $this->line("Finaly, we can add indexes to the table if necessary.");
        $this->nl();
        $this->line("Syntax is <fg=cyan>indexType column1 [column2 column3 ...]</>");
        $this->line("Available types are : <fg=cyan>" . $this->indexes_definitions->keys()->implode('</>, <fg=cyan>') . "</>.");
        $this->indexesWizard();
    }

    ############################################################################
    # GENERATORS                                                               #
    ############################################################################

    /**
     * Generate migration file
     */
    protected function makeMigration() {
        $content = [];

        if ($this->migration->timestamps) {
            $content[] = config("crud-definitions.migrate.timestamps.{$this->migration->timestamps}.template");
        }

        if ($this->migration->softDeletes) {
            $content[] = config("crud-definitions.migrate.softDeletes.{$this->migration->softDeletes}.template");
        }

        foreach ($this->migration->fields as $field) {
            $content[] = $this->compileMigrationField($field);
        }

        foreach ($this->migration->indexes as $index) {
            $content[] = $this->compileMigrationIndex($index);
        }

        $this->call('bgaze:crud:migration', [
            'name' => "create_{$this->names->table}_table",
            '--content' => $content
        ]);
    }

    /**
     * Generate model file
     */
    protected function makeModel() {
        $fields = collect($this->migration->fields);

        $this->call('bgaze:crud:model', [
            'name' => $this->names->singular,
            'table' => $this->names->table,
            '--timestamps' => $this->migration->timestamps,
            '--soft-delete' => $this->migration->softDeletes,
            '--fillables' => $fields->keys()->all(),
            '--dates' => $fields->filter(function($field) {
                        return in_array($field->type, ['date', 'dateTime', 'dateTimeTz', 'time', 'timeTz', 'timestamp', 'timestampTz']);
                    })->keys()->all()
        ]);
    }

    /**
     * Generate request file
     */
    protected function makeRequest() {
        $this->call('bgaze:crud:request', [
            'name' => "{$this->names->singular}FormRequest",
        ]);
    }

    /**
     * Generate controller
     */
    protected function makeController() {
        $this->call('bgaze:crud:controller', [
            'name' => "{$this->names->singular}Controller",
        ]);
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

}
