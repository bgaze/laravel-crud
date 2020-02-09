<?php

namespace Bgaze\Crud\Support\Theme;

use Bgaze\Crud\Support\Crud\Crud;
use Bgaze\Crud\Support\Crud\Entry;
use Bgaze\Crud\Support\Definitions;
use Bgaze\Crud\Support\Tasks\Task;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class Composer
{
    /**
     * The command instance.
     *
     * @var Command
     */
    protected $command;

    /**
     * The CRUD instance.
     *
     * @var Crud
     */
    protected $crud;

    /**
     * Is the command executed in no interactions mode.
     *
     * @var boolean
     */
    protected $noInteractions;

    /**
     * Shall the command overwrite existing files.
     *
     * @var boolean
     */
    protected $force;

    /**
     * @var Collection
     */
    protected $entries;

    /**
     * @var string
     */
    protected $entryFormat;


    /**
     * CrudComposer constructor.
     *
     * @param  Command  $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->crud = $command->getCrud();

        $this->noInteractions = $this->command->option('no-interaction');
        $this->force = $this->command->option('force');

        $this->entries = Definitions::signatures()->keys()->sort();
        $this->entryFormat = '/^(' . $this->entries->implode('|') . ')(\s.*)?$/';
        $this->entries->push('list')->push('no');
    }


    /**
     * Parse and validate Model's name and parents.
     *
     * @return void
     * @throws Exception
     */
    public function setModel()
    {
        $model = trim($this->command->argument('model'), '\\/ ');
        $model = str_replace('/', '\\', $model);

        if (!preg_match(Definitions::MODEL_NAME_FORMAT, $model)) {
            throw new Exception("Model name is invalid.");
        }

        $model = collect(explode('\\', $model));

        $this->crud->setModel($model);

        $this->crud->addVariables([
            'ModelClass' => $model->toBase()->prepend(Definitions::modelsNamespace())->implode('\\'),
            'ModelFullName' => $model->implode('\\'),
            'ModelFullStudly' => $model->implode(''),
            'ModelStudly' => $model->last(),
            'ModelCamel' => Str::camel($model->last()),
        ]);
    }


    /**
     * Ask and validate plurals versions of Model's name and parents.
     * Compute and set default value if empty.
     *
     * @return void
     * @throws Exception
     */
    public function setPlurals()
    {
        $plurals = $this->crud->getModel()->map(function ($v) {
            return Str::plural($v);
        });

        $value = $this->command->option('plurals');
        $ask = (!$value && !$this->noInteractions);

        if ($ask) {
            $value = $this->command->ask('Please confirm plurals version of Model name:', $plurals->implode('\\'));
        }

        if (!empty($value)) {
            $error = 'Plural names are invalid. It should be something like : ' . $plurals->implode('\\');

            $value = str_replace('/', '\\', trim($value, '\\/ '));
            if (!preg_match(Definitions::MODEL_NAME_FORMAT, $value)) {
                throw new Exception($error);
            }

            $value = collect(explode('\\', $value));
            if ($value->count() !== $this->crud->getModel()->count()) {
                throw new Exception($error);
            }

            $plurals = $value;
        }

        $this->crud->setPlurals($plurals);

        $pluralsKebab = $plurals->map(function ($v) {
            return Str::kebab($v);
        });

        $this->crud->addVariables([
            'PluralsFullName' => $plurals->implode('\\'),
            'PluralsFullStudly' => $plurals->implode(''),
            'PluralsKebabDot' => $pluralsKebab->implode('.'),
            'PluralsKebabSlash' => $pluralsKebab->implode('/'),
        ]);

        if (!$ask) {
            $this->command->dl('Plurals', $this->crud->getPlurals()->implode('\\'));
        }
    }


    /**
     * Ask and validate plurals versions of Model's name and parents.
     * Compute and set default value if empty.
     *
     * @return void
     */
    public function setPlural()
    {
        $plural = $this->crud->getModel()->toBase();
        $plural->pop();
        $plural->push($this->crud->getPlurals()->last());

        $this->crud->setPlural($plural);

        $this->crud->addVariables([
            'PluralFullName' => $plural->implode('\\'),
            'PluralFullStudly' => $plural->implode(''),
            'PluralStudly' => Str::studly($plural->last()),
            'PluralCamel' => Str::camel($plural->last()),
        ]);
    }


    /**
     * Ask and validate plurals versions of Model's name and parents.
     * Compute and set default value if empty.
     *
     * @return void
     * @throws Exception
     */
    public function setTasks()
    {
        $tasks = collect($this->command->tasks());

        $value = $this->command->option('only');
        foreach ($value as $task) {
            if (!$tasks->has($task)) {
                throw new Exception("The task {$task} doesn't exists.");
            }
        }

        $default = $tasks->keys()->filter(function ($name) {
            return $this->command->config("tasks.{$name}", true);
        });

        $ask = (empty($value) && !$this->noInteractions);
        if ($ask) {
            if ($default->count() === $tasks->count()) {
                $value = '*';
            } else {
                $value = $default->implode(',');
            }

            $choices = $tasks
                ->map(function ($task) {
                    return $task::title();
                })
                ->prepend('All available tasks', '*');

            $value = $this->command->choice('Please confirm the tasks you want to execute', $choices->all(), $value, null, true);

            if (in_array('*', $value)) {
                $value = $tasks->keys()->all();
            }
        }

        $this->crud->setTasks($tasks->only($value ?: $default));

        if (!$ask) {
            $this->command->dl('Tasks', $this->crud->getTasks()->keys()->implode(', '));
        }
    }


    /**
     * Check that nothing prevents CRUD generation.
     *
     * @throws Exception
     */
    public function checkIfGenerationIsPossible()
    {
        $errors = $this->crud->getTasks()
            ->map(function (Task $task) {
                return $task->cantBeDone();
            })
            ->filter();

        if ($errors->count() === 1) {
            throw new Exception("Cannot generate this CRUD: " . $errors->first());
        }

        if ($errors->count() > 1) {
            throw new Exception("Cannot generate this CRUD:\n - " . $errors->implode("\n - "));
        }
    }


    /**
     * Set default layout for CRUD's views.
     *
     * @param  string  $default  Theme's default layout
     *
     * @return void
     */
    public function setLayout($default)
    {
        $default = $this->command->config('layout', $default);

        $value = $this->command->option('layout');
        $ask = (!$value && !$this->noInteractions);

        if ($ask) {
            $value = $this->command->ask('Please confirm the layout to extend in views:', $default);
        }

        $this->crud->setLayout(empty($value) ? $default : $value);
        $this->crud->addVariable('$key', empty($value) ? $default : $value);

        if (!$ask) {
            $this->command->dl('Views layout', $this->crud->getLayout());
        }
    }


    /**
     * Set Models's Timestamps type based on command inputs.
     *
     * If option wasn't provided and interaction are allowed, user is
     * ask to choose a value.
     *
     * @return void
     * @throws Exception
     */
    public function setTimestamps()
    {
        $value = $this->command->option('timestamps');
        $ask = (!$value && !$this->noInteractions);

        if ($value || ($ask && $this->command->confirm('Do you wish to add timestamps?', true))) {
            $this->crud->addContent(new Entry('timestamps', ''));
        }

        if (!$ask) {
            $this->command->dl('Timestamps', $this->crud->getTimestamps() ? 'yes' : 'no');
        }
    }


    /**
     * Set Model's SoftDeletes type based on command inputs.
     *
     * If option wasn't provided and interaction are allowed, user is
     * ask to choose a value.
     *
     * @return void
     * @throws Exception
     */
    public function setSoftDeletes()
    {
        $value = $this->command->option('soft-deletes');
        $ask = (!$value && !$this->noInteractions);

        if ($value || ($ask && $this->command->confirm('Do you wish to add SoftDeletes?', true))) {
            $this->crud->addContent(new Entry('softDeletes', ''));
        }

        if (!$ask) {
            $this->command->dl('Soft deletes', $this->crud->getSoftDeletes() ? 'yes' : 'no');
        }
    }


    /**
     * Set Model's content based on command inputs.
     *
     * If option was provided, content is prepended to model.
     * If interaction are allowed, content wizard is fired.
     *
     * @return void
     * @throws Exception
     */
    public function setContent()
    {
        // If entries where provided through option, add them.
        if ($this->command->option('content')) {
            foreach ($this->command->option('content') as $question) {
                list($name, $parameters) = $this->parseContentInput($question);
                $this->crud->addContent(new Entry($name, $parameters));
                $this->command->dl('Entry added', $question);
            }
            $this->command->nl(!$this->noInteractions);
        }

        // If no interactions quit.
        if ($this->noInteractions) {
            return;
        }

        // Intro.
        $this->command->info(" You are now going to define model's data.");
        $this->command->line(" For available entries, enter <fg=cyan>list</>.");
        $this->command->line(" For an entry detailed syntax, <fg=cyan>omit arguments and options.</>");

        // Loop and ask for entries while no explicit break.
        while (true) {
            // Ask user for input.
            $continue = $this->askForContentInput();

            // Manage wizard exit.
            $empty = $this->crud->getContent()->except(['timestamps', 'timestampsTz', 'softDeletes', 'softDeletesTz'])->isEmpty();
            if (!$continue && (!$empty || $this->command->confirm("You haven't added any entry. Continue?", true))) {
                break;
            }
        }
    }


    /**
     * Ask the user for a new entry.
     *
     * @return boolean Whether to continue or not to add entries to Model.
     * @throws Exception
     */
    protected function askForContentInput()
    {
        // User input.
        $question = trim($this->command->anticipate('Add an entry?', $this->entries->all(), 'no'));

        // Manage wizard exit.
        if ($question === 'no') {
            return false;
        }

        // Manage 'list' command.
        if ($question === 'list') {
            $this->command->showEntriesHelp();
            return true;
        }

        // Catch any error to prevent unwanted wizard exit.
        try {
            // Parse user input.
            list($name, $parameters) = $this->parseContentInput($question);

            // If empty arguments, show help.
            if (empty($parameters)) {
                $this->command->showEntryHelp($name);
                return true;
            }

            // Add to content.
            $this->crud->addContent(new Entry($name, $parameters));
            $this->command->dl('Entry added', $question);
        } catch (Exception $e) {
            $this->command->error($e->getMessage());
        }

        // Continue.
        return true;
    }


    /**
     * Parse the user input and return an array containing two values:
     * - First:     the name of the entry type to add.
     * - Second:    arguments and options to generate the entry.
     *
     * @param  string  $question  The user input
     *
     * @return array
     * @throws Exception The asked entry wasn't recognized
     */
    protected function parseContentInput($question)
    {
        if (!preg_match($this->entryFormat, $question, $matches)) {
            throw new Exception("Invalid input '$question'.");
        }

        return [
            $matches[1], // Entry type.
            $matches[2] ?? null // Arguments and options.
        ];
    }

}