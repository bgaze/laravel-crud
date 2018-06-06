<?php

namespace Bgaze\Crud\Core;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Core\Crud;

/**
 * GeneratorCommand
 * 
 * This is the base Class for all the commands of this package.
 * 
 * It mainly do two thing :
 * - Managing CRUD instance for the command and it's sub commands based on provided arguments.
 * - Managing user inputs, providing wizards if possible.
 *
 * @author bgaze
 */
abstract class GeneratorCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * Sub command status.
     * 
     * True if current execution was called within an other GeneratorCommand execution.
     * 
     * @var boolean 
     */
    protected $subcommand = false;

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Core\Crud
     */
    protected $crud;

    /**
     * The message to display when the command is ran.
     * 
     * @return string
     */
    abstract protected function welcome();

    /**
     * Build the files.
     * 
     * @return void
     */
    abstract protected function build();

    /**
     * An array of CRUD method to execute in order to check that no file to generate already exists.
     * 
     * @return array
     */
    abstract protected function files();

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try {
            if (!$this->subcommand) {
                // Show title when direct call.
                $this->h1($this->welcome());
                $this->h2('Configuration');

                // Instantiate CRUD based on theme and model inputs.
                $this->initCrud();
            }

            // Build.
            $this->nl(!$this->subcommand && $this->option('no-interaction'));
            $this->h2('Generation', !$this->subcommand);

            if (!$this->option('no-interaction')) {
                $this->line($this->summary());
                $this->nl();
            }

            if ($this->option('no-interaction') || $this->confirm('Continue?', true)) {
                $this->build();
                $this->nl(!$this->subcommand);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->nl();
            $this->line($e->getTraceAsString());
        }
    }

    /**
     * Call another console command.
     * 
     * If the command is an instance of \Bgaze\Crud\Core\GeneratorCommand, it
     * subcommand status is set to true.
     *
     * @param  string  $command The command name
     * @param  array   $arguments The command arguments
     * 
     * @return int The command exit code
     */
    public function call($command, array $arguments = []) {
        $arguments['command'] = $command;
        $command = $this->getApplication()->find($command);

        if ($command instanceof \Bgaze\Crud\Core\GeneratorCommand) {
            $command->initAsSubCommand($this->crud);
        }

        return $command->run($this->createInputFromArguments($arguments), $this->output);
    }

    /**
     * Flag the command as a sub command.
     * 
     * This will prevent any interaction and minimize the console output.
     */
    public function initAsSubCommand(Crud $crud) {
        $this->subcommand = true;
        $this->crud = $crud;
    }

    /**
     * Get the CRUD instance.
     * 
     * If this is a sub command, the parent command CRUD instance is returned.
     * Otherwise, a new instance is created based on command arguments and options.
     * 
     * @return void
     */
    protected function initCrud() {
        // Get required theme.
        $theme = $this->option('theme') ? $this->option('theme') : config('crud.theme');

        // Resolve CRUD instance.
        $this->crud = $this->laravel->make($theme, [$this->argument('model')]);

        // Get Layout.
        if ($this->optionExists('layout')) {
            $this->crud->setLayout($this->option('layout'));
        }

        // Show configuration summary.
        $this->dl('Theme', $theme);
        $this->dl('Model name', $this->crud->getModelFullName());
        $this->dl('Views layout', $this->crud->getViewsLayout());

        // Get plurals value.
        $this->getPluralsInput();

        // Check that no file already exists.
        $this->summary();

        // Get timestamps value.
        if ($this->optionExists('timestamps')) {
            $this->getTimestampsInput();
        }

        // Get timestamps value.
        if ($this->optionExists('soft-deletes')) {
            $this->getSoftDeletesInput();
        }

        // Add content.
        if ($this->optionExists('content')) {
            $this->getFieldsInput();
        }
    }

    /**
     * Generate a summary of generator's actions.
     * 
     * If some files to generate already exists, an eroor is raised, 
     * otherwise a formatted summary of generated files is returned.
     * 
     * @return string
     * @throws \Exception
     */
    protected function summary() {
        $errors = collect([]);
        $files = collect([]);

        foreach ($this->files() as $v) {
            try {
                $files->push(str_replace(base_path() . '/', '', $this->crud->{$v}()));
            } catch (\Exception $e) {
                $errors->push($e->getMessage());
            }
        }

        if ($errors->isNotEmpty()) {
            throw new \Exception($errors->implode("\n"));
        }

        if ($files->count() === 1) {
            return " <fg=green>Following file will be generated :</> " . $files->first();
        }

        return " <fg=green>Following files will be generated :</>\n  " . $files->implode("\n  ");
    }

    /**
     * Check if an option is present into command signature.
     * 
     * @param string $option The full name of the option without dashes.
     * 
     * @return boolean
     */
    protected function optionExists($option) {
        return preg_match("/--([a-zA-Z]\\|)?{$option}/", $this->signature);
    }

    ############################################################################
    # MANAGE INPUTS

    /**
     * Set CRUD's plurals names based on command inputs.
     * 
     * If plural wasn't provided and interraction are allowed, user is
     * ask to confirm default value based on Model's name, or to provide his own.
     * 
     * @return void 
     */
    protected function getPluralsInput() {
        $value = $this->option('plural');
        $ask = (!$value && !$this->option('no-interaction') && !$this->option('quiet'));

        if ($ask) {
            $value = $this->ask('Please confirm plurals version of Model name :', $this->crud->getPluralsFullName());
        }

        $this->crud->setPlurals($value);

        if (!$ask && !$this->subcommand) {
            $this->dl('Plurals', $this->crud->getPluralsFullName());
        }
    }

    /**
     * Set Models's Timestamps type based on command inputs.
     * 
     * If option wasn't provided and interraction are allowed, user is
     * ask to choose a value.
     * 
     * @return void 
     */
    protected function getTimestampsInput() {
        $value = $this->option('timestamps');
        $ask = (!$value && !$this->option('no-interaction') && !$this->option('quiet'));

        if ($ask) {
            $timestamps = array_keys(config('crud-definitions.timestamps'));
            $timestamps[] = 'none';
            $value = $this->choice('Do you wish to add timestamps?', $timestamps, 0);
        }

        $this->crud->setTimestamps(($value === 'none') ? false : $value);

        if (!$ask && !$this->subcommand) {
            $this->dl('Timsestamps', $this->crud->content->timestamps ? $this->crud->content->timestamps : 'none');
        }
    }

    /**
     * Set Model's SoftDeletes type based on command inputs.
     * 
     * If option wasn't provided and interraction are allowed, user is
     * ask to choose a value.
     * 
     * @return void 
     */
    protected function getSoftDeletesInput() {
        $value = $this->option('soft-deletes');
        $ask = (!$value && !$this->option('no-interaction') && !$this->option('quiet'));

        if ($ask) {
            $softDeletes = array_keys(config('crud-definitions.softDeletes'));
            $softDeletes[] = 'none';
            $value = $this->choice('Do you wish to add SoftDeletes?', $softDeletes, 0);
        }

        $this->crud->setSoftDeletes(($value === 'none') ? false : $value);

        if (!$ask && !$this->subcommand) {
            $this->dl('Soft deletes', $this->crud->content->softDeletes ? $this->crud->content->softDeletes : 'none');
        }
    }

    /**
     * Set Model's fields based on command inputs.
     * 
     * If option was provided, content is prepended to model.
     * If interraction are allowed, content wizard is fired.
     * 
     * @return void 
     */
    protected function getFieldsInput() {
        // If fields where provided through option, add them.
        if ($this->hasOption('content')) {
            foreach ($this->option('content') as $question) {
                list($field, $data) = $this->parseSignedInput($question);
                $this->crud->content->add($field, $data);
                $this->dl('Field added', $question, !$this->subcommand);
            }
        }

        // Id no interactions quit.
        if ($this->option('no-interaction')) {
            return;
        }

        // Intro.
        $this->info(" We are now going to define model's data.");
        $this->line(" For available types, enter <fg=cyan>list</>.");
        $this->line(" For a type detailed syntax, <fg=cyan>omit arguments and options.</>");

        // Commands list for autocomplete.
        $fields = collect(config('crud-definitions.fields'))->keys()->merge(['list', 'no'])->toArray();

        // Loop and ask for fields while no explicit break.
        while (true) {
            // Ask user for input.
            $continue = $this->askForFieldInput($fields);

            // Manage wizard exit.
            if (!$continue && (!$this->crud->content->isEmpty() || $this->confirm("You haven't added any field. Continue?", true))) {
                break;
            }
        }
    }

    /**
     * Ask the user for a new Model field.
     * 
     * @param array $fields The list of available fields.
     * 
     * @return boolean Wether to continue or not to add fields to Model.
     */
    protected function askForFieldInput(array $fields) {
        // User input.
        $question = trim($this->anticipate('Add a field', $fields, 'no'));

        // Manage wizard exit.
        if ($question === 'no') {
            return false;
        }

        // Manage 'list' command.
        if ($question === 'list') {
            $this->showFieldsHelp();
            return true;
        }

        // Catch any error to prevent unwanted wizard exit.
        try {
            // Parse user input.
            list($name, $input) = $this->parseSignedInput($question);

            // If empty arguments, show help.
            if (empty($input)) {
                $this->line($this->showFieldHelp($name));
                return true;
            }

            // Add to content.
            $this->crud->content->add($name, $input);
            $this->dl('Field added', $question);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->line($e->getTraceAsString());
        }

        // Continue.
        return true;
    }

    /**
     * Parse the user input and return an array conatining two values :
     * - First : the name of the field type to add.
     * - Second : arguments and options to generate the field.
     * 
     * @param string $question The user input
     * 
     * @return array 
     * @throws \Exception The asked field wasn't recognized
     */
    protected function parseSignedInput($question) {
        $reg = '/^(' . collect(config('crud-definitions.fields'))->keys()->implode('|') . ')(\s.*)?$/';

        if (!preg_match($reg, $question, $matches)) {
            throw new \Exception("Invalid input '$question'.");
        }

        return [
            $matches[1], // Field type.
            isset($matches[2]) ? trim($matches[2]) : '' // Arguments and options.
        ];
    }

    /**
     * Display help for a field type.
     * 
     * @param type $name The name of the field.
     */
    protected function showFieldHelp($name) {
        $config = config("crud-definitions.fields.{$name}");
        $this->line("   <fg=green>{$config['description']}</>\n   Signature :   <fg=cyan>{$name} {$config['signature']}</>\n");
    }

    /**
     * Display a help tablefor all availables fields type.
     */
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

}
