<?php

namespace Bgaze\Crud\Console;

use Illuminate\Console\Command;

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
    protected $signature = 'crud:make 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--t|theme= : The theme to use to generate CRUD.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD';

    /**
     * The theme instance.
     *
     * @var \Bgaze\Crud\Support\Theme\Crud
     */
    protected $theme;

    /**
     * Storage for migration data
     *
     * @var type 
     */
    protected $migration;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // Initialize theme.
        $this->theme = $this->getTheme();

        // Check that no CRUD file already exists.
        $summary = $this->theme->getCrudFilesSummary();

        // Intro.
        $this->h1("Welcome to CRUD generator");
        $this->line("This wizard will drive you through the process to create a ready-to-use CRUD related to a new Eloquent Model.");
        $this->nl();

        // Acquire fields definition.
        $this->h2("Model attributes definition");
        $this->getMigrationDefinition();

        // Ask for confirmation then generate CRUD.
        $this->h2("CRUD generation");
        $this->line($summary);
        if ($this->confirm("Continue?", true)) {
            $this->makeMigration();
            $this->makeModel();
            $this->makeRequest();
            $this->makeViews();
            $this->makeController();
            $this->makeFactory();
        }
    }

    /**
     * Get migration content
     */
    protected function getMigrationDefinition() {
        // Prepare required definitions.
        $this->prepareIndexesDefinition();
        $this->prepareFieldsDefinition();

        // Initialize migration.
        $this->migration = (object) [
                    'timestamps' => false,
                    'softDeletes' => false,
                    'fields' => [],
                    'indexes' => []
        ];

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
        $tmp = $this->choice('Do you wish to enable soft delete?', ['softDeletes', 'softDeletesTz', 'No'], 0);
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

        $this->call('crud:migration', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--content' => $content
        ]);
    }

    /**
     * Generate model file
     */
    protected function makeModel() {
        $fields = collect($this->migration->fields);

        $this->call('crud:model', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
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
        $this->call('crud:request', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme')
        ]);
    }

    /**
     * Generate views
     */
    protected function makeViews() {
        $this->call('crud:views', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme')
        ]);
    }

    /**
     * Generate controller
     */
    protected function makeController() {
        $this->call('crud:controller', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme')
        ]);
    }

    /**
     * Generate Model factory
     */
    protected function makeFactory() {
        $this->call('crud:factory', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme')
        ]);
    }

}
