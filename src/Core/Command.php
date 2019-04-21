<?php

namespace Bgaze\Crud\Core;

use Illuminate\Support\Collection;
use Illuminate\Console\Command as Base;
use Bgaze\Crud\Support\ConsoleHelpersTrait;
use Bgaze\Crud\Core\Builder;
use Symfony\Component\Console\Helper\TableSeparator;

/**
 * The CRUD generator command.
 * 
 * This command signature is dynamically generated based on a CRUD theme class.
 * So multiple instances can exist simultaneously.
 * 
 * Please see \Bgaze\Crud\Support\ThemeProviderTrait for more details.
 *
 * @author bgaze <benjamin@bgaze.fr>
 */
class Command extends Base {

    use ConsoleHelpersTrait;

    /**
     * The CRUD theme name.
     * 
     * @var string 
     */
    protected $theme;

    /**
     * The CRUD instance.
     *
     * @var \Bgaze\Crud\Core\Crud
     */
    protected $crud;

    /**
     * The theme builders instances.
     *
     * @var \Illuminate\Support\Collection 
     */
    protected $builders;

    ############################################################################
    # PUBLIC ACCESS

    /**
     * The command constructor.
     * 
     * @param string $class         The CRUD theme class
     * @return void
     */
    public function __construct($class) {
        $this->theme = call_user_func("{$class}::name");

        $this->description = call_user_func("{$class}::description");

        $this->signature = $this->compileSignature($class);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        $this->setCustomStyles();
        $this->h1("Welcome to {$this->theme} generator");

        // Configure  CRUD based on theme and model inputs.
        $this->h2('Configuration');
        $this->getConfiguration();

        // Build.
        $this->nl($this->option('no-interaction'));
        $this->h2('Generation');
        $this->build();
    }

    /**
     * Get the CRUD instance.
     * 
     * @return \Bgaze\Crud\Core\Crud
     */
    public function getCrud() {
        return $this->crud;
    }

    ############################################################################
    # PREPARE THEME SIGNATURE

    /**
     * 
     * @param string $class     The CRUD theme class
     * @return void
     */
    protected function compileSignature($class) {
        $builders = collect(call_user_func("{$class}::builders"))->map(function($builder) {
                    return call_user_func("{$builder}::slug");
                })->implode('|');

        $timestamps = $this->getDatesModifiersChoices('timestamps', true);

        $softDeletes = $this->getDatesModifiersChoices('softDeletes', true);

        $signature = "{$this->theme} 
            {model : The FullName of the Model}
            {--p|plurals= : The Plurals version of the Model's name}
            {--t|timestamps= : Add timestamps directive: <fg=cyan>{$timestamps}</>}
            {--s|soft-deletes= : Add soft delete directive: <fg=cyan>{$softDeletes}</>}
            {--c|content=* : The list of Model's fields using SignedInput syntax}
            {--o|only=* : Generate only selected files: <fg=cyan>{$builders}</>}";

        $layout = call_user_func("{$class}::layout");
        if ($layout) {

            $signature .= "            {--l|layout= : The layout to extend into generated views: <fg=cyan>[{$layout}]</>}";
        }

        return $signature;
    }

    /**
     * Get the list of options for dates modifiers (timestamps & soft deletes).
     * 
     * @param string $key       The modifier [timestamps|softDeletes]
     * @param type $signature   Return it in signature format
     * @return string
     */
    protected function getDatesModifiersChoices($key, $signature = false) {
        $list = array_keys(config("crud-definitions.{$key}"));
        $list[] = 'none';

        if ($signature) {
            $list[0] = '[' . $list[0] . ']';
            return implode('|', $list);
        }

        return $list;
    }

    ############################################################################
    # CONFIGURE CRUD

    /**
     * Create the CRUD instance and configure it.
     * 
     * If interractions are allowed, choices are proposed to user for most options.
     * 
     * @return void
     */
    protected function getConfiguration() {
        // Resolve CRUD instance.
        $this->crud = $this->laravel->make("crud.theme.{$this->theme}.class", [$this->argument('model')]);

        // Instanciate Theme builders.
        $this->getBuilders();

        // Set Layout.
        $this->getLayoutInput();

        // Show model FullName.
        $this->dl('Model name', $this->crud->getModelFullName());

        // Get plurals value.
        $this->getPluralsInput();

        // Check that nothing prevents builders executions.
        $this->isBuildPossible();

        // Get timestamps value.
        $this->getTimestampsInput();

        // Get timestamps value.
        $this->getSoftDeletesInput();

        // Add content.
        $this->getContentInput();
    }

    /**
     * Instanciate the required builders, based on 'only' option.
     * 
     * @return void
     */
    protected function getBuilders() {
        // Get builders list based on "only" option.
        $only = $this->option('only');
        $builders = collect($this->crud::builders())->filter(function($class) use($only) {
            return (!$only || empty($only) || in_array(call_user_func("{$class}::slug"), $only));
        });

        // Instanciate selected builders.
        $filesystem = resolve('Illuminate\Filesystem\Filesystem');
        $this->builders = $builders->map(function($class) use(&$filesystem) {
            return new $class($filesystem, $this);
        });
    }

    /**
     * Check that nothing prevents builders executions.
     * 
     * @return void
     * @throws \Exception
     */
    protected function isBuildPossible() {
        $errors = $this->builders->map(function(Builder $builder) {
                    return $builder->cannotBuild();
                })->filter();

        if ($errors->count() === 1) {
            throw new \Exception("Cannot generate this CRUD: " . $errors->first());
        }

        if ($errors->count() > 1) {
            throw new \Exception("Cannot generate this CRUD:\n- " . $errors->implode("\n- "));
        }
    }

    /**
     * Set CRUD's layout based on command inputs and configuration.
     * 
     * @return void 
     */
    protected function getLayoutInput() {
        if (!$this->crud::layout()) {
            return;
        }

        if ($this->hasOption('layout')) {
            $this->crud->setLayout($this->option('layout'));
        } elseif (config("crud.{$this->theme}.layout")) {
            $this->crud->setLayout(config("crud.{$this->theme}.layout"));
        }

        $this->dl('Views layout', $this->crud->getViewsLayout());
    }

    /**
     * Set CRUD's plurals names based on command inputs.
     * 
     * If plurals wasn't provided and interraction are allowed, user is
     * ask to confirm default value based on Model's name, or to provide his own.
     * 
     * @return void 
     */
    protected function getPluralsInput() {
        $value = $this->option('plurals');
        $ask = (!$value && !$this->option('no-interaction') && !$this->option('quiet'));

        if ($ask) {
            $value = $this->ask('Please confirm plurals version of Model name:', $this->crud->getPluralsFullName());
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
            $value = $this->choice('Do you wish to add timestamps?', $this->getDatesModifiersChoices('timestamps'), 0);
        }

        $this->crud->setTimestamps($value);

        if (!$ask) {
            $this->dl('Timestamps', $this->crud->timestamps() ? $this->crud->timestamps() : 'none');
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
            $value = $this->choice('Do you wish to add SoftDeletes?', $this->getDatesModifiersChoices('softDeletes'), 0);
        }

        $this->crud->setSoftDeletes($value);

        if (!$ask) {
            $this->dl('Soft deletes', $this->crud->softDeletes() ? $this->crud->softDeletes() : 'none');
        }
    }

    /**
     * Set Model's content based on command inputs.
     * 
     * If option was provided, content is prepended to model.
     * If interraction are allowed, content wizard is fired.
     * 
     * @return void 
     */
    protected function getContentInput() {
        $fields = collect(config('crud-definitions.fields'))->keys();

        // If fields where provided through option, add them.
        if ($this->option('content')) {
            foreach ($this->option('content') as $question) {
                list($field, $data) = $this->parseContentInput($fields, $question);
                $this->crud->add($field, $data);
                $this->dl('Field added', $question);
            }
        }

        // Id no interactions quit.
        if ($this->option('no-interaction')) {
            return;
        }

        // Intro.
        $this->info(" You are now going to define model's data.");
        $this->line(" For available types, enter <fg=cyan>list</>.");
        $this->line(" For a type detailed syntax, <fg=cyan>omit arguments and options.</>");

        // Commands list for autocomplete.
        $fields->push('list')->push('no');

        // Loop and ask for fields while no explicit break.
        while (true) {
            // Ask user for input.
            $continue = $this->askForContentInput($fields);

            // Manage wizard exit.
            if (!$continue && (!$this->crud->content()->isEmpty() || $this->confirm("You haven't added any field. Continue?", true))) {
                break;
            }
        }
    }

    /**
     * Ask the user for a new Model content.
     * 
     * @param array $fields The list of available fields.
     * @return boolean Wether to continue or not to add fields to Model.
     */
    protected function askForContentInput($fields) {
        // User input.
        $question = trim($this->anticipate('Add a field', $fields->all(), 'no'));

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
            list($name, $data) = $this->parseContentInput($fields, $question);

            // If empty arguments, show help.
            if (empty($data)) {
                $this->line($this->showFieldHelp($name));
                return true;
            }

            // Add to content.
            $this->crud->add($name, $data);
            $this->dl('Field added', $question);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        // Continue.
        return true;
    }

    /**
     * Parse the user input and return an array conatining two values:
     * - First:     the name of the field type to add.
     * - Second:    arguments and options to generate the field.
     * 
     * @param \Illuminate\Support\Collection $fields    The available contents
     * @param string $question                          The user input
     * 
     * @return array 
     * @throws \Exception The asked field wasn't recognized
     */
    protected function parseContentInput(Collection $fields, $question) {
        $reg = '/^(' . $fields->implode('|') . ')(\s.*)?$/';

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
     * @param type $name    The name of the field.
     */
    protected function showFieldHelp($name) {
        $signature = config("crud-definitions.fields.{$name}");

        preg_match('/^((\s?\{[^-\}]+\})*)/', $signature, $a);
        $arguments = empty($a[1]) ? '' : " <fg=yellow>{$a[1]}</>";

        preg_match('/((\{--[^\}]+\}\s?)*)$/', $signature, $o);
        $options = empty($o[1]) ? '' : " <fg=cyan>{$o[1]}</>";

        $this->line("   <info>Add a {$name} field to table.</info>\n   Signature:  {$arguments}{$options}\n");
    }

    /**
     * Display a help tablefor all availables fields type.
     */
    protected function showFieldsHelp() {
        $rows = [];

        foreach (config('crud-definitions.fields') as $name => $signature) {
            if ($name === 'index' || $name === 'hasOne') {
                $rows[] = new TableSeparator();
            }

            preg_match('/^((\s?\{[^-\}]+\})*)/', $signature, $a);
            preg_match('/((\{--[^\}]+\}\s?)*)$/', $signature, $o);
            $rows[] = [$name, $a[1], $o[1]];
        }

        $this->table(['Command', 'Arguments', 'Options'], $rows);
    }

    ############################################################################
    # BUILD

    /**
     * Display a summary of generator's actions.
     */
    protected function summarize() {
        $this->warn('Following action(s) will be executed:');
        $this->builders->each(function(Builder $builder) {
            $builder->summarize(false);
        });
        $this->nl();
    }

    /**
     * Build the CRUD
     * 
     * @return void
     */
    protected function build() {
        // If interractions allowed, show a summary.
        if (!$this->option('no-interaction')) {
            $this->warn('Following action(s) will be executed:');
            $this->builders->each(function(Builder $builder) {
                $builder->summarize();
            });
            $this->nl();
        }

        // If interractions, ask for user confirmation.
        if (!$this->option('no-interaction') && !$this->confirm('Proceed?', true)) {
            return;
        }

        // Execute builders showing an action summary.
        $this->builders->each(function(Builder $builder) {
            $builder->build();
            $builder->done();
        });
        $this->nl();

        // Show success message.
        $this->comment(' CRUD generated successfully.');
        if (method_exists($this->crud, 'indexPath')) {
            $this->line(' <comment>Index path:</comment> ' . $this->crud->indexPath());
        }
        $this->nl();
    }

}
