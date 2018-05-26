<?php

namespace Bgaze\Crud\Support;

use Illuminate\Console\Command;
use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Theme\Crud;

/**
 * Description of GeneratorCommand
 *
 * @author bgaze
 */
abstract class GeneratorCommand extends Command {

    use ConsoleHelpersTrait;

    /**
     * TODO
     * 
     * @var type 
     */
    protected $isSubCommand = false;

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Support\Theme\Crud
     */
    protected $crud;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try {
            // Show title when direct call.
            $this->h1($this->welcome(), !$this->isSubCommand);
            $this->h2('Configuration', !$this->isSubCommand);

            // Instantiate CRUD based on theme and model inputs.
            $this->getCrud();

            // Get plurals value.
            $this->getPluralsInput();

            // Check that no file already exists.
            $summary = $this->summary();

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

            // Build.
            $this->nl(!$this->isSubCommand && $this->option('no-interaction'));
            $this->h2('Generation', !$this->isSubCommand);

            if (!$this->option('no-interaction')) {
                $this->line($summary);
                $this->nl();
            }

            if ($this->option('no-interaction') || $this->confirm('Continue?', true)) {
                $this->build();
                $this->nl(!$this->isSubCommand);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->nl();
            //$this->line($e->getTraceAsString());
        }
    }

    /**
     * TODO
     * 
     */
    abstract protected function welcome();

    /**
     * TODO
     * 
     */
    abstract protected function build();

    /**
     * TODO
     * 
     */
    abstract protected function files();

    protected function getCrud() {
        $theme = $this->option('theme') ? $this->option('theme') : config('crud.theme');
        $this->crud = $this->laravel->make($theme, ['model' => $this->argument('model')]);

        if ($this->optionExists('layout')) {
            $this->crud->setLayout($this->option('layout'));
        }

        if (!$this->isSubCommand) {
            $this->dl('Theme', $theme);
            $this->dl('Model name', $this->crud->getModelFullName());
            $this->dl('Views layout', $this->crud->getViewsLayout());
        }
    }

    /**
     * TODO
     * 
     * @return type
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
     * TODO
     * 
     * @param type $option
     * @return type
     */
    protected function optionExists($option) {
        return preg_match("/--([a-zA-Z]\\|)?{$option}/", $this->signature);
    }

    /**
     * Call another console command.
     *
     * @param  string  $command
     * @param  array   $arguments
     * @return int
     */
    public function call($command, array $arguments = []) {
        $arguments['command'] = $command;
        $command = $this->getApplication()->find($command);

        if ($command instanceof \Bgaze\Crud\Support\GeneratorCommand) {
            $command->isSubCommand = true;
        }

        return $command->run($this->createInputFromArguments($arguments), $this->output);
    }

    ############################################################################
    # MANAGE INPUTS

    /**
     * TODO
     */
    protected function getPluralsInput() {
        $value = $this->option('plural');
        $ask = (!$value && !$this->option('no-interaction') && !$this->option('quiet'));

        if ($ask) {
            $value = $this->ask('Please confirm plurals version of Model name :', $this->crud->getPluralsFullName());
        }

        $this->crud->setPlurals($value);

        if (!$ask && !$this->isSubCommand) {
            $this->dl('Plurals', $this->crud->getPluralsFullName());
        }
    }

    /**
     * TODO
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

        if (!$ask && !$this->isSubCommand) {
            $this->dl('Timsestamps', $this->crud->content->timestamps ? $this->crud->content->timestamps : 'none');
        }
    }

    /**
     * TODO
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

        if (!$ask && !$this->isSubCommand) {
            $this->dl('Soft deletes', $this->crud->content->softDeletes ? $this->crud->content->softDeletes : 'none');
        }
    }

    /**
     * TODO
     */
    protected function getFieldsInput() {
        // If fields where provided through option, add them.
        if ($this->hasOption('content')) {
            foreach ($this->option('content') as $question) {
                list($field, $data) = $this->parseSignedInput($question);
                $this->crud->content->add($field, $data);
                $this->dl('Field added', $question);
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
     * TODO
     * 
     * @param array $fields
     * @return boolean
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
        }

        // Continue.
        return true;
    }

    /**
     * TODO
     * 
     * @param string $question
     * @return array
     * @throws \Exception
     */
    protected function parseSignedInput($question) {
        $reg = '/^(' . collect(config('crud-definitions.fields'))->keys()->implode('|') . ')(\s.*)?$/';

        if (!preg_match($reg, $question, $matches)) {
            throw new \Exception("Invalid input '$question'.");
        }

        return [
            $matches[1], // Field name
            isset($matches[2]) ? trim($matches[2]) : '' // User input
        ];
    }

    /**
     * TODO
     * 
     * @param type $name
     */
    protected function showFieldHelp($name) {
        $config = config("crud-definitions.fields.{$name}");
        $this->line("   <fg=green>{$config['description']}</>\n   Signature :   <fg=cyan>{$name} {$config['signature']}</>\n");
    }

    /**
     * TODO
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
