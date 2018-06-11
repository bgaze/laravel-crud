<?php

namespace Bgaze\Crud\Core;

use Illuminate\Console\Command as Base;
use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Core\Builder;

/**
 * Description of Command
 *
 * @author bgaze
 */
class Command extends Base {

    use ConsoleHelpersTrait;

    /**
     * TODO
     * 
     * @var type 
     */
    protected $theme;

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Bak\Core\Crud
     */
    protected $crud;

    /**
     * The theme builders instances.
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $builders;

    /**
     * TODO
     * 
     * @param type $class
     * @param type $description
     */
    public function __construct($class, $description) {
        $this->theme = call_user_func("{$class}::name");

        $this->description = $description;

        $this->signature = $this->compileSignature($class);

        parent::__construct();
    }

    protected function compileSignature($class) {
        $layout = config('crud.layout') ? config('crud.layout') : call_user_func("{$class}::layout");

        $only = implode('|', array_keys(call_user_func("{$class}::builders")));

        $timestamps = array_keys(config('crud-definitions.timestamps'));
        $timestamps[0] = '[' . $timestamps[0] . ']';
        $timestamps[] = 'false';
        $timestamps = implode('|', $timestamps);

        $softDeletes = array_keys(config('crud-definitions.softDeletes'));
        $softDeletes[0] = '[' . $softDeletes[0] . ']';
        $softDeletes[] = 'false';
        $softDeletes = implode('|', $softDeletes);

        return "crud:{$this->theme} 
            {model : The name of the Model.}
            {--p|plurals= : The plurals versions of the Model\'s names.}
            {--t|timestamps : Add timestamps directives : <fg=cyan>{$timestamps}</>}
            {--s|soft-deletes : Add soft delete directives : <fg=cyan>{$softDeletes}</>}
            {--c|content=* : The list of Model\'s fields (signature syntax).}
            {--o|only=* : Generate only selected files : <fg=cyan>{$only}</>}
            {--layout= : The layout to extend into generated views : <fg=cyan>[{$layout}]</>}";
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try {
            $this->h1('Welcome to CRUD generator');

            // Configure  CRUD based on theme and model inputs.
            $this->h2('Configuration');
            $this->configure();

            // Build.
            $this->nl($this->option('no-interaction'));
            $this->h2('Generation');
            $this->build();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            $this->nl();
            $this->line($e->getTraceAsString());
        }
    }

    ############################################################################
    # CONFIGURE CRUD

    /**
     * Get the CRUD instance.
     * 
     * If this is a sub command, the parent command CRUD instance is returned.
     * Otherwise, a new instance is created based on command arguments and options.
     * 
     * @return void
     */
    protected function configure() {
        // Resolve CRUD instance.
        $this->crud = $this->laravel->make("crud.theme.{$this->theme}.class", [$this->argument('model')]);

        // Instanciate Theme builders.
        $this->getBuilders();

        // Get Layout.
        $this->crud->setLayout($this->option('layout'));

        // Show configuration summary.
        $this->dl('Theme', $this->theme);
        $this->dl('Model name', $this->crud->getModelFullName());
        $this->dl('Views layout', $this->crud->getViewsLayout());

        // Get plurals value.
        $this->getPluralsInput();

        // Check that no file already exists.
        $this->ensureNoFileExists();

        // Get timestamps value.
        $this->getTimestampsInput();

        // Get timestamps value.
        $this->getSoftDeletesInput();

        // Add content.
        $this->getFieldsInput();
    }

    /**
     * TODO
     */
    protected function getBuilders() {
        $builders = $this->crud::builders();

        if ($this->option('only') && !empty($this->option('only'))) {
            $builders = array_intersect_key($builders, array_flip($this->option('only')));
        }

        $this->builders = collect(static::builders())->map(function($class) {
            return new $class($this);
        });
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
    protected function ensureNoFileExists() {
        $errors = $this->builders->map(function(Builder $builder) {
                    return $builder->fileExists();
                })->filter();

        if ($errors->count() === 1) {
            throw new \Exception($errors->first());
        } elseif ($errors->count() > 1) {
            throw new \Exception(implode("\n "));
        }
    }

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

        if (!$ask) {
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

        if (!$ask) {
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

        if (!$ask) {
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

    ############################################################################
    # BUILD

    /**
     * Generate a summary of generator's actions.
     * 
     * If some files to generate already exists, an eroor is raised, 
     * otherwise a formatted summary of generated files is returned.
     * 
     * @return string
     * @throws \Exception
     */
    protected function summarize() {
        $files = $this->builders->map(function(Builder $builder) {
            return str_replace(base_path() . '/', '', $builder->file());
        });

        if ($files->count() === 1) {
            return " <fg=green>Following file will be generated :</> " . $files->first();
        }

        return " <fg=green>Following files will be generated :</>\n  " . $files->implode("\n  ");
    }

    /**
     * TODO
     */
    protected function build() {
        if (!$this->option('no-interaction')) {
            $this->line($this->summarize());
            $this->nl();
        }

        if ($this->option('no-interaction') || $this->confirm('Continue?', true)) {
            $this->builders->each(function(Builder $builder, $name) {
                $this->dl(ucfirst(str_replace('-', ' ', $name)), $builder->build());
            });
            $this->nl();
        }
    }

}
