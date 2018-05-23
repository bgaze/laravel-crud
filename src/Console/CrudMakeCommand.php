<?php

namespace Bgaze\Crud\Console;

use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Support\GeneratorCommand;
use Bgaze\Crud\Theme\Crud;

class CrudMakeCommand extends GeneratorCommand {

    use ConsoleHelpersTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:make 
        {model : The name of the Model.}
        {--p|plural= : The plural version of the Model\'s name.}
        {--theme= : The theme to use to generate CRUD.}
        {--layout= : The layout to extend into generated views.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD';

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Support\Theme\Crud
     */
    protected $crud;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->h1("Welcome to CRUD generator");
        parent::handle();
    }

    /**
     * TODO
     * 
     * @param Crud $crud
     */
    protected function build(Crud $crud) {
        // Make crud accessible globally.
        $this->crud = $crud;

        // Show intro text.
        $this->intro();

        // Check that no CRUD file already exists.
        $summary = $this->summary();

        // Acquire data.
        if (!$this->option('no-interaction') && !$this->option('quiet')) {
            $this->getTimestampsInput();
            $this->getSoftDeleteInput();
            $this->getFieldsInput();
        }

        // Generate CRUD.
        $this->make($summary);
    }

    /**
     * TODO
     * 
     * @return void
     */
    protected function intro() {
        if (!$this->option('no-interaction')) {
            $this->line("This wizard will drive you through the process to create a ready-to-use CRUD related to a new Eloquent Model.");
            $this->nl();
        }

        $this->line('<fg=green>Model :</> ' . $this->crud->getModelWithParents());
        $this->line('<fg=green>Plural form of model\'s name :</> ' . $this->crud->getPluralStudly());
        $this->nl();

        if (!$this->option('no-interaction')) {
            $this->line("We are now going to build your model.");
            $this->nl();
        }
    }

    /**
     * TODO
     * 
     * @return string
     */
    public function summary() {
        $errors = collect([]);
        $files = collect([]);

        foreach (['migration', 'model', 'request', 'controller', 'indexView', 'showView', 'createView', 'editView', 'factory'] as $v) {
            try {
                $files->push($this->crud->{"{$v}Path"}());
            } catch (\Exception $e) {
                $errors->push($e->getMessage());
            }
        }

        if ($errors->isNotEmpty()) {
            $tmp = "Cannot proceed to CRUD generation :\n - ";
            $tmp .= $errors->implode("\n - ");
            throw new \Exception($tmp);
        }

        $tmp = "<fg=green>Following files will be generated :</>\n - ";
        $tmp .= $files->map(function($path) {
                    return str_replace(base_path() . '/', '', $path);
                })->implode("\n - ");

        return $tmp;
    }

    ############################################################################
    # ACQUIRE DATA

    /**
     * TODO
     */
    protected function getTimestampsInput() {
        $this->h2('Timestamps');
        $tmp = $this->choice('Do you wish to add timestamps?', ['timestamps', 'timestampsTz', 'nullableTimestamps', 'No'], 0);
        $this->crud->content->timestamps = ($tmp === 'No') ? false : $tmp;
    }

    /**
     * TODO
     */
    protected function getSoftDeleteInput() {
        $this->h2('Soft delete');
        $tmp = $this->choice('Do you wish to enable soft delete?', ['softDeletes', 'softDeletesTz', 'No'], 0);
        $this->crud->content->softDeletes = ($tmp === 'No') ? false : $tmp;
    }

    /**
     * TODO
     */
    protected function getFieldsInput() {
        // Intro.
        $this->h2('Fields & indexes');
        $this->line("We are now going to define model's data.");
        $this->line("Please note that an <fg=cyan>auto-incremented id</> field will be automatically added.");
        $this->nl();
        $this->line("For available types, enter <fg=cyan>list</>.");
        $this->line("For a type detailed syntax, <fg=cyan>omit arguments and options.</>");

        // Commands list for autocomplete.
        $fields = collect(config('crud-definitions.fields'))->keys()->merge(['list', 'no'])->toArray();

        // Loop and ask for fields while no explicit break.
        while (true) {
            // User input.
            $question = trim($this->anticipate('Add a column', $fields, 'no'));

            // Manage wizard exit.
            if ($question === 'no') {
                if ($this->crud->content->isEmpty() && !$this->confirm("You haven't added any field. Continue?", true)) {
                    continue;
                }
                break;
            }

            // Manage 'list' command.
            if ($question === 'list') {
                $this->showFieldsHelp();
                continue;
            }

            // Catch any error to prevent unwanted wizard exit.
            try {
                // Parse user input.
                list($definition, $input) = $this->parseUserInput($question);

                // If empty arguments, show help.
                if (empty($input)) {
                    $this->line($definition->help);
                    continue;
                }

                // Add to content.
                $this->crud->content->add($definition, $input);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
                $this->error($e->getTraceAsString());
            }
        }
    }

    protected function showFieldHelp($name) {
        $config = config("crud-definitions.fields.{$name}");
        $this->line("   {$config['description']}\n   Signature :   <fg=cyan>{$name} {$config['signature']}</>\n");
    }

    protected function showFieldsHelp() {
        $rows = collect(config('crud-definitions.fields'))->map(function ($config, $name) {
            $pos = strpos($config['signature'], '--');

            if ($pos) {
                return [$name, substr($config['signature'], 0, $pos - 2), substr($config['signature'], $pos - 1)];
            }

            return [$name, $config['signature'], ''];
        });

        $this->table(['Command', 'Arguments', 'Options'], $rows);
    }

    ############################################################################
    # GENERATE CRUD

    /**
     * TODO
     * 
     * @param type $summary
     */
    protected function make($summary) {
        $this->h2("CRUD generation");

        if (!$this->option('no-interaction')) {
            $this->line($summary);
            $this->nl();
        }

        if ($this->option('no-interaction') || $this->confirm("Continue?", true)) {
            $this->makeMigration();
            $this->makeModel();
            $this->makeFactory();
            $this->makeRequest();
            $this->makeController();
            $this->makeViews();
            $this->nl();
        }
    }

    /**
     * Generate migration file
     */
    protected function makeMigration() {
        $this->call('crud:migration', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--timestamps' => $this->crud->content->timestamps,
            '--soft-deletes' => $this->crud->content->softDeletes,
            '--content' => $this->crud->content->originalInputs()
        ]);
    }

    /**
     * Generate model file
     */
    protected function makeModel() {
        $this->call('crud:model', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--timestamps' => $this->crud->content->timestamps,
            '--soft-deletes' => $this->crud->content->softDeletes,
            '--content' => $this->crud->content->originalInputs()
        ]);
    }

    /**
     * Generate Model factory
     */
    protected function makeFactory() {
        $this->call('crud:factory', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--content' => $this->crud->content->originalInputs()
        ]);
    }

    /**
     * Generate request file
     */
    protected function makeRequest() {
        $this->call('crud:request', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--content' => $this->crud->content->originalInputs()
        ]);
    }

    /**
     * Generate views
     */
    protected function makeViews() {
        $this->call('crud:views', [
            'model' => $this->argument('model'),
            '--plural' => $this->option('plural'),
            '--theme' => $this->option('theme'),
            '--layout' => $this->option('layout'),
            '--timestamps' => $this->crud->content->timestamps,
            '--soft-deletes' => $this->crud->content->softDeletes,
            '--content' => $this->crud->content->originalInputs()
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

}
